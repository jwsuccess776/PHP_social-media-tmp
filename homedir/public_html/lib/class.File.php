<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

    /**
     *  Class for working with Files
     */

class File extends Main
{
    var $Path;
    var $File;
    var $tempPath;
    var $tempExt;
    var $FileDirs = array (
                        );

    /**
     * Constructor
     *
     * @param string $ID
     * @param string $type
     * @param string $ext
     * @return boolean true
     *
     * @access public
     */


    function Init($ID,$type='',$ext='jpg')   {}

    /**
     * Set external file
     *
     * @param string $path
     * @return boolean true
     *
     * @access public
     */

    function Check($path)   {}

    /**
     * Set external file
     *
     * @param string $path
     * @return boolean true
     *
     * @access public
     */

    function setFile($path, $ext='')
    {
        $this->tempExt = $ext;
        $this->tempPath = $path;
        $result = $this->Check($path);
        if ($result === null) return $this->Error("File error");
        return true;
    }

    /**
     * Return file as block of data
     *
     * @param string $type
     * @return string block of data
     *
     * @access public
     */

    function getFile($type='')
    {
        $this->_getFile($this->_Path);
        return $this->File;
    }


    /**
     * Return path to file
     *
     * @param string $path
     * @return string path to file
     *
     * @access public
     */

    function getInfo($type='')   {}

    /**
     * Save file to storage
     *
     * @return string path to file
     *
     * @access public
     */

    function Save()
    {
        $result = $this->Check($this->tempPath);
        if ($result === null) return $this->Error("File error");

        $this->_putFile($this->tempPath);
        return $this->Path;
    }

    /**
     * Check if video exist
     *
     * @param string $path
     * @return bollean
     *
     * @access public
     */

    function existsFile($path)
    {
        return file_exists($path);
    }

    function getExtFromPath($path) {
        $info = pathinfo($path);
        return strtolower($info['extension']);
    }
        
    /**
     * Delete file from storage with thumbs
     *
     * @access private
     */

    function Delete()
    {
        if ($this->existsFile($this->_Path)) {
            unlink($this->_Path);
        }
    }

    /**
     * Save file to storage
     *
     * @access public
     */

    function _putFile($path)
    {
        copy($path,$this->_Path);
    }

    /**
     * Read file and set variable file property
     *
     * @param string $path
     *
     * @access private
     */

    function _getFile($path) {
        if ($fd = @fopen($path,"r")){
            $this->File = fread($fd,filesize($path));
            fclose($fd);
        } else {
            return $this->CriticalError("Can't open file $path. May be it doesn't  exist or you don't have permition to read it.");
        }
    }
}
?>