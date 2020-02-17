<?
require_once(__INCLUDE_CLASS_PATH."/class.Main.php");

class Tag extends Main{
    
    var $id;
    var $tag;    

    /**
     * Add tag value to db and retung it id 
     * @param string $tag
     * @return int
     * @access public
     */

    function initByTag($tag){
        $tag = strtolower(trim($tag));
        $db = & db::getInstance();
        $result = $db->get_row("SELECT *
                                FROM tag
                                WHERE tag = '".$db->escape($tag)."'");
        if (!$result) {
            $db->query("   INSERT INTO tag
                           SET tag = '".$db->escape($tag)."'");
            $this->id = $db->insert_id;
            $this->tag = $tag;
            return $this;
        } else {
            return $this->initByObj($result);
        }
    }

    /**
     * Get tag by id
     * @param int $id
     * @return int
     * @access public
     */

    function initById($id){
        if (!is_numeric($id)) return $this->criticalError("Incorrect id [$id]"); 

        $db = & db::getInstance();
        $result = $db->get_row("SELECT * 
                                FROM tag
                                WHERE id = '".$db->escape($id)."'");
        if ($result) { 
            return $this->initByObj($result);
        } else {
            return $this->Error("Unexisting id [$id]"); 
        }
    }


    /**
     * Get tag by object
     * @param object $data
     * @return boolean
     * @access public
     */

    function initByObj($data){
        if (!is_object($data)) return $this->criticalError("Incorrect object"); 
        $this->_extract($data);
        return $this;
    }


    /**
     * Delete tag object
     *
     * @access public
     */
    function Delete(){
        $db = & db::getInstance();
        $db->query("DELETE FROM tag WHERE id = '{$this->id}'");
        return $result;
    }
}

?>