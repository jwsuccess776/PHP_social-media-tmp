<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";
class Avatar extends Main {
    public $db;
    public $id;
    public $pictureID;
    public $path;
    public $relativePath;
    public $URL;
    public $thumbPath;
    public $thumbURL;

    function Avatar($id) {
        $this->db =& db::getInstance();
        if (intval($id)) {
            $this->id = $id;
            $this->_retrieve();
        }
    }

    function findAll() {
        $db =& db::getInstance();
        $ids = $db->get_col('SELECT avatar_id FROM avatars a INNER JOIN pictures p ON (a.pic_id=p.pic_id)');
        $result = array();
        if (is_array($ids))
            foreach ($ids as $id)
                $result[] = new Avatar($id);
        return $result;
    }

    function _retrieve() {
        if ($this->id) {
            $this->pictureID = $this->db->get_var("SELECT pic_id FROM avatars WHERE avatar_id = '$this->id'");
			$this->path = CONST_INCLUDE_ROOT."members/avatar_$this->id.jpg";
            
			if (file_exists($this->path)) $extension="jpg";
			else $extension="gif";
			
			$this->path = CONST_INCLUDE_ROOT."members/avatar_$this->id.$extension";
			$this->relativePath = "members/avatar_$this->id.$extension";
            $this->URL = CONST_LINK_ROOT."/members/avatar_$this->id.$extension";
            $this->thumbPath = CONST_INCLUDE_ROOT."members/avatar_$this->id.$extension";
            $this->thumbURL = CONST_LINK_ROOT."/members/avatar_$this->id.$extension";
        }
    }
}
?>