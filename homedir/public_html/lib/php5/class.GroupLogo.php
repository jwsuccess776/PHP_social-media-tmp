<?php
require_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
include_once __INCLUDE_CLASS_PATH."/class.Skin.php";

class GroupLogo extends File {
    public $groupId;
    public $extension;
    public $URL;
    public $uploaded = false;

    public $width;
    public $height;
    public $htmlSize;

    public $maxWidth = 100;
    public $maxHeight = 100;

    function GroupLogo($groupId, $extension = 'gif') {
        $skin =& Skin::GetInstance();
        $this->groupId = intval($groupId);
        $this->extension = $extension;

        $option_manager = &OptionManager::GetInstance();
        $this->maxWidth = $option_manager->getValue('groups_image_width');
        $this->maxHeight = $option_manager->getValue('groups_image_height');

        $this->_buildPaths();

        $info = @getimagesize($this->uploaded ? $this->Path : CONST_INCLUDE_ROOT.$skin->ImagePath.'genericc.jpg');
        $this->width = $info[0];
        $this->height = $info[1];
        $this->htmlSize = $info[3];
        return true;
    }

    function setFile($file) {
        if ($this->_isValid($file)) {
            if ($this->groupId) {
                $this->_putFile($file);
                $this->uploaded = true;
            } else
                $this->_buildPaths($file);
            return true;
        }
        return null;
    }

    function setGroup($groupId) {
        $this->groupId = $groupId;
        $oldPath = $this->Path;
        $this->_buildPaths();
        if ($this->Path != $oldPath) { // image was uploaded
            $this->_putFile($oldPath);
        }
    }

    function _buildPaths($path = '') {
        if ($path) {
            $this->Path = $path;
            if ($this->existsFile($path)) 
                $this->uploaded = true;
        } else {
            $path = "/groups/images/g$this->groupId.$this->extension";
            $this->Path = CONST_INCLUDE_ROOT.$path;
            if ($this->existsFile(CONST_INCLUDE_ROOT.$path)) {
                $this->URL = CONST_LINK_ROOT.$path;
                $this->uploaded = true;
            } else {
                $this->URL = CONST_IMAGE_ROOT.'genericc.jpg';
            }
        }
    }

    function _isValid($file) {
        if ($this->existsFile($file)) {
            $info = getimagesize($file);
            if (!$info || !in_array($info[2], array(1, 2, 3)))
                return $this->Error(GRP_ERR_IMAGE_BAD_FILE);
            elseif ($info[0] > $this->maxWidth || $info[1] > $this->maxHeight)
                return $this->Error(sprintf(GRP_ERR_IMAGE_FILE_TOO_BIG, $this->maxWidth, $this->maxHeight));
            else
                return true;
        } else
            return $this->Error(GRP_ERR_IMAGE_NO_FILE);
    }

    function _putFile($path) {
        copy($path, $this->Path);
    }

}
?>