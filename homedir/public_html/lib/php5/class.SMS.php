<?require_once(__INCLUDE_CLASS_PATH."/class.Main.php");

class SMS extends Main{
    public $id;    public $title;    
    public $email;
    public $status;

	function SMS($id=0){
		if ($id)  $this->initById($id);
	}

    /**     * Init object
     * @param int $id
     * @return object
     * @access public
     */

    function initById($id){
        $db = & db::getInstance();
        $result = $db->get_row("SELECT *
                                FROM sms_carrier
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
            UPDATE sms_carrier SET
                id  = ".$db->escape($this->id).",
                title    = '".$db->escape($this->title)."',
                email = '".$db->escape($this->email)."',
                status = ".$db->escape($this->status)."
            WHERE id = ".$db->escape($this->id)
            );
        } else {
            $db->query("
            INSERT INTO sms_carrier SET
                title   = '".$db->escape($this->title)."',
                email = '".$db->escape($this->email)."',
                status = ".$db->escape($this->status)."
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
        if (!$this->title)  return $this->criticalerror("Empty title");
        if (!$this->status)    return $this->criticalerror("Unknown status");
        if (!$this->email)    return $this->error("Empty address");
        return true;
    }

    /**
     * Delete tag object
     *
     * @access public
     */
    function Delete(){
        $db = & db::getInstance();
        $db->query("DELETE FROM sms_carrier WHERE id = '{$this->id}'");
        return true;
    }

    /**
     * Get list of objects 
     *
     * @access public
     */

    static function getList(&$pager) {
        $result = array();
        $limit = '';
        $db = & db::getInstance();
        if (get_class($pager) == 'pager') {
            if (!is_object($pager)) return $this->criticalError("Incorrect paging object");
            $count = $db->get_var("
                                    SELECT count(*)
                                    FROM sms_carrier 
                                  ");
            $limit = $pager->getLimit($count);    
        } 
        
        $list = $db->get_results("
                                SELECT * 
                                FROM sms_carrier
                                ORDER BY title ASC".
                                $limit
                                );
        foreach ($list as $obj) {
            $sms = new SMS();
            $result[] = $sms->initByObj($obj);
        }
        return $result;
    }
}
?>