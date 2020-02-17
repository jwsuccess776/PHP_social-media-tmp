<?
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class MailQueue extends Main {

    public $MailQueue_ID;
    public $Email;
    public $From;
    public $Subject;
    public $Body;
    public $Type;

    /**
     * Initialisation of object
     *
     * @param mixed $data
     * @access public
     */
    function Init($data){
        if (!is_object($data)){
            $db = & db::getInstance();
            $eID = $this->_PrepareData($data);
            $row = $db->get_row("
                                SELECT 	*
                                FROM mail_queue
                                WHERE MailQueue_ID='$eID'");
            if (!$row) return $this->CriticalError("Can't find email for ID [$data]");
        } else {
            $result = $this->_CheckValue($data);
            if ($result === null)
                return $this->Error("Incorrect email data");
            $row = $data;
        }
        foreach ($row as $key => $data)
            $this->{$key} = $data;
        return true;
    }

    /**
     * Save option data
     *
     * @param numeric $value
     * @access public
     */
    function Save(){
        $eEmail     = $this->_PrepareData($this->Email);
        $eFrom      = $this->_PrepareData($this->From);
        $eSubject   = $this->_PrepareData($this->Subject);
        $eBody      = $this->_PrepareData($this->Body);
        $eType      = $this->_PrepareData($this->Type);

        $db = & db::getInstance();
        $lang =& Language::getInstance();
        if ($this->MailQueue_ID) {
            $query="UPDATE mail_queue SET
                        `Email`		= '$eEmail',
                        `From`		= '$eFrom',
                        `Subject` 	= '$eSubject',
                        `Body`		= '$eBody',
                        `Type`		= '$eType'
                    WHERE MailQueue_ID = '$this->MailQueue_ID'
                    ";
        } else {
            $query="INSERT INTO mail_queue SET
                        `Email`		= '$eEmail',
                        `From`		= '$eFrom',
                        `Subject` 	= '$eSubject',
                        `Body`		= '$eBody',
                        `Type`		= '$eType'
                    ";
        }

        $db->query($query);
        $this->MailQueue_ID = ($this->MailQueue_ID) ? $this->MailQueue_ID : $db->insert_id;
        return true;
    }

    /**
     * Prepare data before save it
     *
     * @param numeric $data
     *
     * @access private
     */
    function _PrepareData($data){
        $db = & db::getInstance();
        return $db->escape($data);
    }

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($value){
        if ($value->Email == '')   return $this->Error("Email is empty");
        if (!isEmail($value->Email))   return $this->Error("Incorrect e-mail [$value->Email]");
        if ($value->From == '')    return $this->Error("From is empty");
        if ($value->Subject == '') return $this->Error("Subject is empty");
        if ($value->Body == '')    return $this->Error("Body is empty");
        if ($value->Type == '')    return $this->Error("Type is empty");
        return true;
    }

    /**
     * Check value
     *
     * @access private
     */

    function Delete(){
        $db = & db::getInstance();
        $query="DELETE FROM mail_queue
                WHERE MailQueue_ID = '$this->MailQueue_ID'
				";
        $db->query($query);
        return true;
    }

    /**
     * Return list of emails
     *
     * @return array
     * @access public
     */
    function getList(&$pager){
        $db = & db::getInstance();
        if ($pager !== NULL) $limit = $pager->GetLimit($db->get_var("SELECT count(*) FROM mail_queue"));
        $aList = $db->get_results("
                                SELECT 	*
                                FROM mail_queue
                                ORDER BY MailQueue_ID
                                $limit
							        ");
        $aResult = array ();
        foreach ($aList as $row){
            $t = new MailQueue();
            $t->Init($row);
            $aResult[] = $t;
        }
        return $aResult;
    }

    /**
     * Return portion of emails
     *
     * @return array
     * @access public
     */
    function getPortion($count){
        $db = & db::getInstance();
        $aList = $db->get_results("
                                SELECT 	*
                                FROM mail_queue
                                ORDER BY MailQueue_ID
                                LIMIT 0,$count
							        ");
        $aResult = array ();
        foreach ($aList as $row){
            $t = new MailQueue();
            $t->Init($row);
            $aResult[] = $t;
        }
        return $aResult;
    }

    /**
     * Add statistic info
     *
     * @param numeric $count
     * @param string $starttime
     * @param string $errors
     * @access public
     */
    function addStat($count, $startime, $errors){
        $db = & db::getInstance();
        $eSize       = $this->_PrepareData($count);
        $eStartTime  = $this->_PrepareData($startime);
        $eErrors      = $this->_PrepareData($errors);

        $query="INSERT mail_queue_stat SET
                    Size		= '$eSize',
                    startTime	= '$eStartTime',
                    endTime	    = now(),
                    Errors  	= '$eErrors'
                ";
        $db->query($query);
        return true;
    }
}

?>