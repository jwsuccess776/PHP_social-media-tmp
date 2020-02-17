<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";
include_once __INCLUDE_CLASS_PATH."/class.Trigger.php";

class Notifications extends Main {
    var $aTriggers = array();
    var $activeTriggers = array(
    							"MailTrigger",
    							"LoginTrigger"
    							);
    var $userid;


    /**
     * Activate object and create array of triggers
     *
     * @access public
     */

    function __construct($userid) {
        if (!is_numeric($userid)) return $this->CriticalError("Incorrect userid");
        $this->userid = $userid;
        $this->aTriggers = $this->getTriggersList();
        $this->clearOld();
        $this->checkNewEvents();
    }

    /**
     * Create pool of triggers
     *
     * @return array of objects (class Trigger)
     * @access private
     */

     function getTriggersList() {
        if (count($this->activeTriggers)==0) return $this->CriticalError("Empty list of active triggers");
        foreach ($this->activeTriggers as $triggerType){
            $result[] = new $triggerType($this->userid);
        }
        return $result;
    }

    /**
     * Check new events
     *
     * @access private
     */

     function checkNewEvents() {
        $aNotifications = array();
        foreach ($this->aTriggers as $key => $currentTrigger){
            $aNotifications = $this->aTriggers[$key]->checkNew();
            if (count($aNotifications)) {
                $this->add($aNotifications);
            }
        }
    }

    /**
     * Remove old(expired) notifications
     *
     * @access private
     */

     function clearOld() {
        $db = & db::getInstance();
        $userid = $this->_PrepareData($this->userid);
        $db->query("DELETE FROM notify_events WHERE date_sub(now(), INTERVAL 10 MINUTE) >= created AND userid = '$userid'");
    }

    /**
     * Add notifications to queue
     *
     * @params array $notifications
     *
     * @access private
     */

     function add($notifications) {
        $db = & db::getInstance();
        $userid = $this->_PrepareData($this->userid);

        foreach ($notifications as $message) {
            if ($message == "") return $this->CriticalError("Empty notification message");
            $text = $this->_PrepareData($message);
            $db->query("INSERT INTO notify_events SET
            				userid 	= $userid,
            				text	= '$text',
            				created = now()
            		");
        }
    }

    /**
     * Remove notification
     *
     * @param int $id
     * @access private
     */

     function delete($id) {
        $db = & db::getInstance();
        $id = $this->_PrepareData($id);
        $db->query("DELETE FROM notify_events WHERE id = '$id'");
    }

    /**
     * Get last message
     *
     * @access public
     */

     function get() {
        $db = & db::getInstance();
        $id = $this->_PrepareData($this->userid);
        if ($row = $db->get_row("SELECT * FROM notify_events WHERE userid = '$id' ORDER BY id ASC LIMIT 1")){
            $this->delete($row->id);
            return $row->text;
        } else {
            return "";
        }
    }

}
?>