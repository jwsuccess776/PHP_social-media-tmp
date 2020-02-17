<?php
include_once __INCLUDE_CLASS_PATH."/class.File.php";

    /**
     *  Class for working with Images
     */

class ImageFile extends File
{
    var $Path;
    var $File;
    var $Height;
    var $Width;
    var $tempPath;
    var $FileDirs = array (
                        'gallery' => '/members/gallery',
                        'member' => '/members',
                        'news'      => '/news',
                        'forum'     => '/forum/image',
                        'video'     => '/videos/frames',
                        );
    var $Thumbs = array (
                        'small' => array('w' => CONST_THUMBS_SMALL_W,'h' => CONST_THUMBS_SMALL_H),
                        'medium' => array('w' => CONST_THUMBS_MEDIUM_W,'h' => CONST_THUMBS_MEDIUM_H),
                        'large' => array('w' => CONST_THUMBS_LARGE_W,'h' => CONST_THUMBS_LARGE_H),
                        );

    var $AllowedExt = array (
                        'jpg' => "jpg",
                        'pjpeg' => "jpg",
                        'jpeg' => "jpg",
                        'gif' => 'gif',
                        'png' => 'png',
                        'x-png' => 'png'
                        );
    var $DefaultExt = 'jpg';


    function __construct() {
        if (!function_exists('imagecreatefromgif'))
            unset($this->AllowedExt['gif']);
    }

    /**
     * Constructor
     *
     * @param string $Image_ID
     * @param string $type
     * @param string $ext
     * @return boolean true
     *
     * @access public
     */


    function Init($ID,$type='',$ext='jpg')
    {
        if (!array_key_exists($type,$this->FileDirs))
            return $this->CriticalError("Unknown storage [$type]. Check list of allowed storages.");

        $this->Path = $this->FileDirs[$type]."/$ID.$ext";
        $this->_Path = CONST_INCLUDE_ROOT.$this->FileDirs[$type]."/$ID.$ext";
        $this->CurrentDir = CONST_INCLUDE_ROOT.$this->FileDirs[$type];

        $option_manager = &OptionManager::GetInstance();
        if ($option_manager->GetValue('thumbs')){
            foreach ($this->Thumbs as $key => $data){
                $this->Thumbs[$key]['Path'] = $this->FileDirs[$type]."/{$ID}_{$key}.$ext";
                $this->Thumbs[$key]['_Path'] = CONST_INCLUDE_ROOT.$this->FileDirs[$type]."/{$ID}_{$key}.$ext";
            }
        } else {
            $this->Thumbs = array();
        }
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
            return $this->Error("Can't open file [$path]. May be it doesn't  exist or you don't have permition to read it.");

        if (!array_key_exists($this->tempExt,$this->AllowedExt))
            return $this->Error("Incorrect file format [$this->tempExt]. Allowed formats are [". join(", ",array_keys($this->AllowedExt))."].");

        if (filesize($path) >= $option_manager->GetValue('maxpicsize'))
            return $this->Error(sprintf(PRGADVERTISE_TEXT22,$option_manager->GetValue('maxpicsize')));

        $aResult = getimagesize ($path);

        if (!$aResult)
            return $this->Error("Incorrect image pls check file");

        return true;
    }

    /**
     * Set external image
     *
     * @param string $path
     * @return boolean true
     *
     * @access public
     */

    function setFile($path,$ext='')
    {
        if($ext=="")
        {
            $ext="jpg";
        }
        $this->tempPath = $path;
        $this->tempExt = $ext;
        $result = $this->Check($path);
        if ($result === null) return $this->Error("File error");
        $aResult = getimagesize ($path);
        $this->Height = $aResult[1];
        $this->Width = $aResult[0];
        return true;
    }

    /**
     * Return image as block of data
     *
     * @param string $type
     * @return string block of data
     *
     * @access public
     */

    function getFile($type='')
    {
        $path = ($type) ? $this->Thumbs[$type]['_Path'] : $this->_Path;
        $this->_getFile($path);
        return $this->File;
    }

    /**
     * Create thumbnail for image
     *
     * @param string $type
     * @return boolean
     *
     * @access private
     */

    function createThumb($type) {
        if (!array_key_exists($type,$this->Thumbs))
            return $this->CriticalError("Unknown thumbnail type [$type]. Check list of allowed thumbnails.");

        if ($this->Width > $this->Height) {
            $new_width =$this->Thumbs[$type]['w'];
            $new_height=$this->Height*($this->Thumbs[$type]['w']/$this->Width);
        } else {
            $new_height = $this->Thumbs[$type]['h'];
            $new_width  = $this->Width*($this->Thumbs[$type]['h']/$this->Height);
        }
        $thumb = imagecreatetruecolor($new_width, $new_height);
        if (!$source = @imagecreatefromjpeg($this->tempPath)) {
            if (!$source = @imagecreatefromgif($this->tempPath)) {
                if (!$source = @imagecreatefrompng($this->tempPath)) {
                    return $this->CriticalError("Unknown files format. Can't open image.");
                }
            }
        }

        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $this->Width, $this->Height);
        imagejpeg($thumb,$this->Thumbs[$type]['_Path'],100);
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
        if ($type != ''){
            if ($type != '' && !array_key_exists($type,$this->Thumbs))
                return $this->CriticalError("Unknown thumbnail type [$type]. Check list of allowed thumbnails.");
            return (object)$this->Thumbs[$type];
        } else {
            return  (object)array("Path"    => $this->Path,
                            "w"     => "",
                            "h"     => "");
        }
    }

    /**
     * Save image to storage
     *
     * @return string path to file
     *
     * @access public
     */

    function Save()
    {
        $this->_putFile($this->tempPath);
        foreach ($this->Thumbs as $key => $data)
            $this->createThumb($key);
        clearCrop($this->CurrentDir);
        return $this->Path;
    }

    /**
     * Delete image from storage with thumbs
     *
     * @access private
     */

    function Delete()
    {
        if ($this->existsFile($this->_Path)) {
            unlink($this->_Path);
        }
        foreach ($this->Thumbs as $key => $data)
            if ($this->existsFile($this->Thumbs[$key]['_Path'])){
                unlink($this->Thumbs[$key]['_Path']);
            }
    }
}

class dynamicImageFile extends ImageFile {
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
        if ($type != ''){
            if ($type != '' && !array_key_exists($type,$this->Thumbs))
                return $this->CriticalError("Unknown thumbnail type [$type]. Check list of allowed thumbnails.");
            $result =  (object)$this->Thumbs[$type];
        } else {
           $result =  (object)array("Path"    => $this->Path,
                            "w"     => "",
                            "h"     => "");
        }
        $result->Path .= "?".time();
        return $result;
    }

}

?>