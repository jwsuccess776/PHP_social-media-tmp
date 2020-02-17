<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class Trigger extends Main {
    public $userid;
    public $pool;
    public $system;
    public $poolType;


    /**
     * Activate object and create array of triggers
     *
     * @access public
     */
/*
    function __construct($userid) {
        if (!is_numeric($userid)) return $this->CriticalError("Incorrect userid");
        $this->userid = $userid;
        $this->pool = $this->getPool();
    }
*/

    function Trigger($userid) {
        if (!is_numeric($userid)) return $this->CriticalError("Incorrect userid");
        $this->userid = $userid;
        $this->pool = $this->getPool();
    }

    /**
     * Get pool of data if not exist then create it
     *
     * @access private
     */

     function getPool() {
        if (isset($_SESSION['Trigger'][$this->system]) && is_array($_SESSION['Trigger'][$this->system])) {
            $result = $_SESSION['Trigger'][$this->system];
        } else {
            $result = $_SESSION['Trigger'][$this->system] = $this->getCurrentState();
        }
        return $result;
    }

    /**
     * Set pool to new state
     *
     * @param array $pool
     *
     * @access private
     */

    function setPool($pool) {
        if (!is_array($pool)) $this->CriticalError("Incorrect pool data, it is not array");
        $_SESSION['Trigger'][$this->system] = $this->pool = $pool;
    }


    /**
     * Compare pool data with current status of system
     *
     * @return array
     *
     * @access private
     */

     function checkNew() {
        $currentList = $this->getCurrentState();
        $outList = array_diff($this->pool, $currentList);
        $inList = array_diff($currentList, array_diff($this->pool, $outList));
        $this->setPool($currentList);

        if ($this->poolType == 'in') return $this->getMessages($inList);
        elseif ($this->poolType == 'out') return $this->getMessages($outList);
        else return $this->CriticalError("Incorrect parameter poolType [$this->poolType]. Allowed values ['in','out']");
    }

    /**
     * Return current state of the system
     *
     * @return array
     *
     * @access private
     */

     function getCurrentState() {}

    /**
     * Return messages
     *
     * @param array $list
     *
     * @return mixed
     *
     * @access private
     */

     function getMessages() {}
}

class MailTrigger extends Trigger {
    var $system = 'Mail';
    var $poolType = 'in';


    /**
     * Return ids of mail messages in inbox
     *
     * @return array
     *
     * @access private
     */

     function getCurrentState() {
        $db = db::getInstance();
        $userid = $this->_PrepareData($this->userid);
        return (array)$db->get_col("SELECT msg_id  FROM messages WHERE msg_receiverid = '$userid' AND msg_receiverdel='N'");
     }

    /**
     * Return messages
     *
     * @param array $list
     *
     * @return mixed
     *
     * @access private
     */

     function getMessages($list) {
        if (!is_array($list)) return $this->CriticalError("Incorrect param \$list. It is not array");
        if (count($list) >= 1) return array(TRIGGER_MAIL_MESSAGE);
        return array();
     }

}

class LoginTrigger extends Trigger {
    var $system = 'Login';
    var $poolType = 'in';


    /**
     * Return ids of mail messages in inbox
     *
     * @return array
     *
     * @access private
     */

     function getCurrentState() {
        $db = db::getInstance();
        $userid = $this->_PrepareData($this->userid);
        include_once __INCLUDE_CLASS_PATH."/class.Network.php";
        $network = new Network();
        $sn_list = array_merge(array(0),$network->getNetwork($this->userid,1));
        return (array)$db->get_col("SELECT mem_userid
                                    FROM members
                                    WHERE mem_userid in (".join(",", $sn_list).")
                                    AND unix_timestamp(mem_timeout) > unix_timestamp(NOW())-(".(ONLINE_TIMEOUT_PERIOD*60).")");
     }

    /**
     * Return messages
     *
     * @param array $list
     *
     * @return mixed
     *
     * @access private
     */

     function getMessages($list) {
        $result = array();
        if (!is_array($list)) return $this->CriticalError("Incorrect param \$list. It is not array");
        include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
        foreach ($list as $userid){
            $mem = new Adverts();
            $mem->InitById($userid);
            $result[] = sprintf(TRIGGER_LOGIN_MESSAGE, $mem->mem_username);
        }
        return $result;
     }

}

?>