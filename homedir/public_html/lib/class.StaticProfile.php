<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class StaticProfile extends Main {

    var $Path = "profiles";

    /**
     * Initialisation of object
     *
     * @param string $username
     * @access public
     */

    function __construct($username){
        $this->fileName = CONST_INCLUDE_ROOT."/$this->Path/$username.html";
        $this->Url = CONST_LINK_ROOT."/$this->Path/$username.html";
    }

    /**
     * Save static profile to file
	 *
	 * @param $string $buf
     * @access public
     */
    function Save($buf){

        $skin =& Skin::GetInstance();
		$header = $skin->ShowHeader("guest");
		$footer = $skin->ShowFooter("guest");
        $fd = @fopen($this->fileName,"w");
        if (!$fd) {
            $this->CriticalError("Can't open file [$this->fileName], check permitions to [$this->Path] directory");
        } else {
            fwrite($fd,$header.$buf.$footer);
            fclose($fd);
        }
    }
    /**
     * Remove static profile
	 *
     * @access public
     */
    function Delete(){
        if (file_exists($this->fileName)) unlink($this->fileName);
    }


}

?>