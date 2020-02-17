<?
require_once(__INCLUDE_CLASS_PATH."/class.Main.php");

class Comment extends Main{
    
    var $id;
    var $text;    
    var $type;
    var $ent_id;
    var $user_id;
    var $date;
    var $approve;

    /**
     * Init object
     * @param int $id
     * @return object
     * @access public
     */

    function initById($id){
        $db = & db::getInstance();
        $result = $db->get_row("SELECT *
                                FROM comments
                                WHERE id = '".$db->escape($id)."'");
        return $this->initByObj($result);
    }

    /**
     * Get comment by object
     * @param object $data
     * @return object
     * @access public
     */

    function initByObj($data){
        if (!is_object($data)) return $this->criticalError("Incorrect object"); 
        foreach ((array)$data as $key => $value) $this->{$key} = $value;
        return $this;
    }

    /**
     * Save object data to DB
     *
     * @access public
     */

    function save(){
        $db = & db::getInstance();
        $res = $this->check();
        if ($res === null) {
            return $this->Error(INVALID_DATA);
        }
        if ($this->id) {
            $db->query("
            UPDATE comments SET
                ent_id  = ".$db->escape($this->ent_id).",
                type    = '".$db->escape($this->type)."',
                user_id = ".$db->escape($this->user_id).",
                approve = ".$db->escape($this->approve).",
                text    = '".$db->escape($this->text)."'
            WHERE id = ".$db->escape($this->id)
            );
        } else {
            $db->query("
            INSERT INTO comments SET
                ent_id  = ".$db->escape($this->ent_id).",
                type    = '".$db->escape($this->type)."',
                user_id = '".$db->escape($this->user_id)."',
                approve = ".$db->escape($this->approve).",
                `date`  = now(),
                text    = '".$db->escape($this->text)."'
            ");
            $this->id = $db->insert_id;
        }
                
        return true;
    }

    /**
     * Check data 
     *
     * @return boolean
     * @access public
     */
    function check(){
//        if (!$this->id)      return $this->criticalError("Empty id");
        if (!$this->user_id) return $this->criticalerror("Empty user_id");
        if (!$this->ent_id)  return $this->criticalerror("Empty entity");
        if (!$this->type)    return $this->criticalerror("Unknown entity type");
        if (!$this->text)    return $this->error(COMMENT_EMPTY);
        return true;
    }

    /**
     * Delete tag object
     *
     * @access public
     */
    function Delete(){
        $db = & db::getInstance();
        $db->query("DELETE FROM comments WHERE id = '{$this->id}'");
        return true;
    }
}

?>