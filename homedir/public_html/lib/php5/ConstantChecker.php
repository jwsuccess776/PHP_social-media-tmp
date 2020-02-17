<?

class ConstantChecker{

    var $files = array(	'languages/english.php',
    					'languages/english_js.php',
    					'speeddating/languages/english_sd.php',
    				);
    var $root;
    var $result= array();

    /**
     * Constructor, set variables and load constants
     *
     * @param string $rootDir
     * @access public
     */

    function ConstantChecker($rootDir){
        $this->root = $rootDir;
        foreach ($this->files as $path){
            $this->realfiles[] = realpath("$this->root/$path");
            $this->LoadConstancts("$this->root/$path");
        }
    }
    /**
     * Stop executing with error
     *
     * @param string $path
     * @return null
     * @access private
     */
    function CriticalError($error){
        trigger_error($error, E_USER_ERROR);
        return null;
    }

    /**
     * Load constance from file
     *
     * @param string $path
     * @return true
     * @access public
     */

    function LoadConstancts($path){
        $file = $this->LoadFile($path);
        preg_match_all("/define\(\"(\w+)?\",/",$file,$results);
        foreach ($results[1] as $constant) {
            $this->result[$constant][count] += 1;
            $this->result[$constant][files] = array();
        }
    }

    /**
     * Load file content
     *
     * @param string $path
     * @return string $text
     * @access private
     */

    function LoadFile($path){
        if (!($fd = fopen("$path","r"))) return $this->CriticalError("Can't open file $path");
        $text = fread($fd,filesize($path));
        fclose($fd);
        return $text;
    }

    /**
     * Scan directory and search constants in files
     *
     * @param string $path
     * @access private
     */

    function ScanDir($path){
        $aFiles = $this->GetFileList("$this->root/$path");
        foreach ($aFiles as $name => $type){
            switch ($type) {
                case 'dir':
                        $this->ScanDir("$path/$name");
                        break;
                case 'php':
                case 'inc':
                        $this->FindConstants("$this->root/$path/$name");
                        break;
                default: break;
            }
        }
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
     * Look for constants in file
     *
     * @param string $path
     * @return true
     * @access private
     */
    function FindConstants($path){
        echo "Seaching in $path<br>";
        $text = $this->LoadFile($path);
        foreach ($this->result as $name => $data){
            if (preg_match("/\b$name\b/",$text))
                $this->result[$name]['files'][] = $path;
        }
        flush();
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
        if (in_array(realpath($path),$this->realfiles)) return 'constant';
        $path_parts = pathinfo($path);
        return $path_parts["extension"];
    }

    /**
     * Display results
     *
     * @param string type (FULL|ONE|UNUSED|DUBLICATE)
     * @param string $name
     *
     * @access public
     */

    function DisplayResults($type,$name=""){
        switch ($type) {
            case "FULL":
			        foreach ($this->result as $name => $data){
			            echo "$name was found in ".join(",",$data['files'])."<br>";
			        }
                    break;
            case "ONE":
                    if (!array_key_exists($name,$this->result))
                        return $this->CriticalError("Can't display unknown constant [$name]");
		            echo "$name was found in ".join(",",$this->result[$name]['files'])."<br>";
                    break;
            case "UNUSED":
			        foreach ($this->result as $name => $data){
			            if (count($data['files']) < 1) echo "Unused constant $name was found<br>";
			        }
                    break;
            case "DUBLICATE":
			        foreach ($this->result as $name => $data){
			            if ($data[count] > 1) echo "Dublicate constant $name was found<br>";
			        }
                    break;
            default: echo "
            				Please set correct type of displaying resut<BR>
            				FULL display full list<BR>
            				ONE	display one<BR>
            				UNUSED display unused only<BR>
            			" ;

        }

    }
}
$scaner = new ConstantChecker("..");
$scaner->DisplayResults('DUBLICATE');
$scaner->ScanDir('');
$scaner->DisplayResults('UNUSED');

?>