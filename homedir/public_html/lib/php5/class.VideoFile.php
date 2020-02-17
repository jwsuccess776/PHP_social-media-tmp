<?php
include_once __INCLUDE_CLASS_PATH."/class.File.php";

    /**
     *  Class for working with Video
     */

class VideoFile extends File
{
    public $Path;
    public $File;
    public $tempPath;
    public $FileDirs = array (
                        'member' => '/videos',
                        'gallery' => '/videos/gallery',
                        );
    public $AllowedExt = array (
                        'avi' => "AVI",
                        'flv' => "FLV",
                        'mpeg'=> "MPEG",
                        'mpg' => "MPG",
                        'mp4' => "MP4",
                        'mov' => "MOV",
						'wmv' => "WMV",                   
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


    function Init($ID, $type='',$ext='avi')
    {
        if (!array_key_exists($type,$this->FileDirs))
            return $this->CriticalError("Unknown storage [$type]. Check list of allowed storages.");
        $this->Path = $this->FileDirs[$type]."/$ID.$ext";
        $this->_Path = CONST_INCLUDE_ROOT.$this->FileDirs[$type]."/$ID.$ext";
        return true;
    }

    /**
     * Static et external image
     *
     * @param string $path
     * @return boolean true
     *
     * @access public
     */

    function Check($path)
    {
        $option_manager =& OptionManager::GetInstance();

        if (!file_exists($path))
            return $this->Error("Can't open file $path. May be it doesn't  exist or you don't permition to read it.");

        if (!array_key_exists($this->tempExt,$this->AllowedExt))
            return $this->Error("Incorrect file format [$this->tempExt]. Allowed formats are [". join(", ",array_keys($this->AllowedExt))."].");

        if (filesize($path) >= $option_manager->GetValue('maxvidsize'))
            return $this->Error(sprintf(PRGPICADMIN_ERROR4,$option_manager->GetValue('maxvidsize')));
        return true;
    }

    /**
     * Return path to file
     *
     * @param string $path
     * @return string path to file
     *
     * @access public
     */

    function getInfo($type='')
    {
            return  (object)array("Path"    => $this->Path,);
    }

}
?>