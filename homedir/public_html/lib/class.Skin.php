<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class Skin extends Main{

    var $Name;
    var $Index;
    var $SkinPath = "/skins/";
    var $Path;
    var $Skins = array();
    var $Areas = array(
                        "member"        => "/member/",                        
                        "mobile"        => "/mobile/",                        
						"guest"         => "/guest/",                        
						"video"         => "/video/",
                        "affiliate"     => "/affiliate/",
                        "speeddating"   => "/speeddating/",
                        "directory"     => "/directory/",
                        'popup'         => '/popup/',
                        );


    /**
     * Return static link to object
     *
     * @return static link
     * @access public
     */

   public static function & getInstance(){
        $skin = & SingeltonStorage::get("instance", "Skin", null);
        if ($skin === null){
            $skin = new Skin;
        }
        return $skin;
    }


    /**
     * Constructor
     *
     * @access public
     */

    function Init($name){
    $OptionManagerLink=new OptionManager();
    $option_manager=$OptionManagerLink->GetInstance();
        //$option_manager =& OptionManager::GetInstance();
        $this->Name = $name;
        $this->Skins = $this->SkinsList();        
		if (!array_key_exists($name,$this->Skins)) $name = $option_manager->GetValue('skin');
        if (!array_key_exists($name,$this->Skins)) return $this->CriticalError("Can't load default skin");
        $this->Path = $this->SkinPath.$name."/";
        $this->ImagePath = $this->SkinPath.$name."/images/";
        $this->Index = $this->Skins[$name]."/index.php";
    }

    /**
     * Show index
     *
     * @param string $area
     * @return string
     *
     * @access public
     */

    function ShowIndex($area) {

        if (!array_key_exists($area,$this->Areas))
            return $this->CriticalError("Incorrect area <b>[$area]</b>. Allowed values for area are <b>[".join(", ",array_keys($this->Areas))."]</b>");
        $path = (file_exists($this->Skins[$this->Name].$this->Areas[$area]."/index.php")) ? $this->Skins[$this->Name].$this->Areas[$area]."/index.php" : $this->Skins[$this->Name]."/index.php";

        ob_start();
        extract($GLOBALS,EXTR_SKIP);
        include($path);
        $result = ob_get_contents();
        ob_clean();
        return $result;
    }


    /**
     * Show header
     *
     * @param string $area
     * @return string
     *
     * @access public
     */

    function ShowTemplate($area,$part) {

        if (!array_key_exists($area,$this->Areas))
            return $this->CriticalError("Incorrect area <b>[$area]</b>. Allowed values for area are <b>[".join(", ",array_keys($this->Areas))."]</b>");
        $path = $this->Skins[$this->Name].$this->Areas[$area].$part.".php";
        if (file_exists($path)) {
            ob_start();
            extract($GLOBALS,EXTR_SKIP);
            include($path);
            $result = ob_get_contents();
            ob_clean();
        } else
            return "<h2>".ucfirst($part)." file doesn't exist.Please create it</h2>";
        return $result;
    }

    /**
     * Show header
     *
     * @param string $area
     * @return string
     *
     * @access public
     */

    function ShowHeader($area) {
        return $this->ShowTemplate($area,'header');
    }

    /**
     * Show footer
     *
     * @param string $area
     * @return string
     *
     * @access public
     */

    function ShowFooter($area) {
        $MicrotimeLink=new Microtime();
        $_time=$MicrotimeLink->getInstance();;
        //$_time =&  Microtime::getInstance();
        $_time->dumpTime('footer');
       // dump($_time);
        return $this->ShowTemplate($area,'footer');
    }



    /**
     * Load list of correct skins
     *
     * @return array
     *
     * @access public
     */

    function SkinsList() {
        $result = array();
        $path = CONST_INCLUDE_ROOT.$this->SkinPath;
        foreach ($this ->GetFileList($path) as $file => $type) {
            if ($type == 'dir'){
                if ($this->CheckSkin($path.$file)) $result[$file] = $path.$file;
            }
        }
        return $result;
    }

    /**
     * Check if skin has all correct files
     *
     * @param string $path
     *
     * @return boolean
     *
     * @access public
     */


    function CheckSkin($path) {
        $result = array();
        $aFiles = $this->GetFileList($path);
//        if (!array_key_exists('header.php',$aFiles)) return null;
//        if (!array_key_exists('footer.php',$aFiles)) return null;
        if (!array_key_exists('index.php',$aFiles)) return null;
        return true;
    }

    /**
     * Get list of files
     *
     * @param string $path
     * @return array $list
     * @access public
     */

    function GetFileList($path){
        $result = array();

        if ($dir = @opendir($path)) {
          while (($file = readdir($dir)) !== false) {
              if (!preg_match("/^\.|\.\.$/",$file))
                $result[$file] = $this->FileType("$path/$file");
          }
          closedir($dir);
        } else {
            $this->CriticalError("Can't open dir [$path]");
        }
        ksort($result);
        return $result;
    }

    /**
     * Check file type
     *
     * @param string $path
     * @return string $type
     * @access private
     */

    function FileType($path){
        if (!is_readable($path)) return 'unknown';
        $type = filetype($path);
        if ($type == 'dir') return 'dir';
        if ($type != 'file') return $type;
        $path_parts = pathinfo($path);
        return $path_parts["extension"];
    }

}
?>