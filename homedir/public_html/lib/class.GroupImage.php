<?php
require_once __INCLUDE_CLASS_PATH."/class.File.php";

class GroupImage extends File {
    var $post;
    var $orderNum;
    var $URL;

    var $width;
    var $height;
    var $htmlSize;
    var $uploaded = false;

    var $thumbPath;
    var $thumbURL;

    var $thumbWidth;
    var $thumbHeight;
    var $thumbHtmlSise;

    var $maxThumbWidth = 80;
    var $maxThumbHeight = 80;

    function __construct($post, $orderNum) {
        $this->post = intval($post);
        $this->orderNum = intval($orderNum);
        $this->_initImage();
        $this->_initThumb();
        return true;
    }

    function setFile($path,$ext='') {
         $file=$path;
        if ($this->_isValid($file) === null) {
            return null;
        } else {
            $this->_putFile($file);
            $this->_makeThumb();
            return true;
        }
    }

    function delete() {
        if ($this->existsFile(CONST_INCLUDE_ROOT.$this->_getImagePath()))
            unlink(CONST_INCLUDE_ROOT.$this->_getImagePath());
        if ($this->existsFile(CONST_INCLUDE_ROOT.$this->_getThumbPath()))
            unlink(CONST_INCLUDE_ROOT.$this->_getThumbPath());
    }

    function _isValid($file) {
        if ($this->existsFile($file)) {
            $info = getimagesize($file);
            if (!$info || !in_array($info[2], array(1, 2, 3)))
                return $this->Error(GRP_ERR_IMAGE_BAD_FILE);
            else
                return true;
        } else
            return $this->Error(GRP_ERR_IMAGE_NO_FILE);
    }

    function _putFile($path) {
        copy($path, CONST_INCLUDE_ROOT.$this->_getImagePath());
        $this->_initImage();
    }

    function _makeThumb() {
        if ($this->existsFile($this->Path)) {
            $this->thumbPath = CONST_INCLUDE_ROOT.$this->_getThumbPath();
            $info = getimagesize($this->Path);
            if ($info[0] > $this->maxThumbWidth || 
                $info[1] > $this->maxThumbHeight) { // resize needed
                switch ($info[2]) {
                    case 1: $func = 'gif'; break;
                    case 2: $func = 'jpeg'; break;
                    case 3: $func = 'png'; break;
                    default: $func = 'nothing';
                }
                $func = 'imagecreatefrom'.$func;
                if (function_exists($func)) {
                    $src = $func($this->Path);
                    $xScale = $this->maxThumbWidth / $this->width;
                    $yScale = $this->maxThumbHeight / $this->height;
                    if ($xScale > $yScale) {
                        $this->thumbHeight = $this->maxThumbHeight;
                        $this->thumbWidth = round($this->width * $yScale);
                    } else {
                        $this->thumbWidth = $this->maxThumbWidth;
                        $this->thumbHeight = round($this->height * $xScale);
                    }
                    $dst = imagecreatetruecolor($this->thumbWidth, $this->thumbHeight);
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $this->thumbWidth, $this->thumbHeight, $this->width, $this->height);
                    unset($src); // to free precious memory
                    imagejpeg($dst, $this->thumbPath);
                    unset($dst);
                }
                $this->_initThumb();
            } else { // for small images just make copy
                copy($this->Path, $this->thumbPath);
            }
        }
    }

    function _initImage() {
        $path = $this->_getImagePath();
        if ($this->existsFile(CONST_INCLUDE_ROOT.$path)) {
            $this->Path = CONST_INCLUDE_ROOT.$path;
            $this->URL = CONST_LINK_ROOT."/$path";
            list($this->width, $this->height, $this->htmlSize) = $this->_getImageSizes($this->Path);
            $this->uploaded = true;
        } else
            $this->uploaded = false;
    }

    function _initThumb() {
        $path = $this->_getThumbPath();
        if ($this->existsFile(CONST_INCLUDE_ROOT.$path)) {
            $this->thumbPath = CONST_INCLUDE_ROOT.$path;
            $this->thumbURL = CONST_LINK_ROOT."/$path";
	        list($this->thumbWidth, $this->thumbHeight, $this->thumbHtmlSize) = $this->_getImageSizes($this->thumbPath);
        } else {
            $this->thumbURL = CONST_IMAGE_ROOT.'genericgi.gif';
        }
    }


    function _getImagePath() {
        return "groups/images/p{$this->post}_{$this->orderNum}.jpg";
    }

    function _getThumbPath() {
        return "groups/images/pt{$this->post}_{$this->orderNum}.jpg";
    }

    function _getImageSizes($path) {
        $info = getimagesize($path);
        return array($info[0], $info[1], $info[3]);
    }
}
?>