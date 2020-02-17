<?
define("NETWORK_FULL",1);
define("NETWORK_SINGLE",2);

define("NETWORK_SINGLE_LEFT",1);
define("NETWORK_SINGLE_RIGHT",2);
define("NETWORK_SINGLE_EMPTY",3);
define("NETWORK_SINGLE_DUAL",4);

include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class Network extends Main{

    /**
     * Check relations between members
     *
     * @param int $sender_id person who sent request
     * @param int $target_id target of request
     * @return int Allowed values are(NETWORK_SINGLE_LEFT(->),NETWORK_SINGLE_RIGHT(<-),NETWORK_SINGLE_EMPTY(-),NETWORK_SINGLE_DUAL(<->))
     * @access public
     */

     function checkRelations($sender_id,$target_id){
         $db =& db::getInstance();
        $sender_id = $this->_PrepareData($sender_id);
        $target_id = $this->_PrepareData($target_id);
        if (!$sender_id) return $this->Error("Incorrect user ID");
        if (!$target_id) return $this->Error("Incorrect friend ID");
        $row = $db->get_row("
                SELECT A.User_ID,B.Friend_ID
                FROM social_network A
                    LEFT JOIN social_network B
                        ON (
                            A.User_ID=B.Friend_ID
                            AND B.User_ID=A.Friend_ID
                            )
                WHERE
                    (A.User_ID = '$sender_id' AND A.Friend_ID = '$target_id')
                    OR
                    (A.Friend_ID = '$sender_id' AND A.User_ID = '$target_id')
                ");

        if ($row->User_ID && $row->Friend_ID)
            return NETWORK_SINGLE_DUAL;
        if ($row->User_ID == $sender_id && !$row->Friend_ID)
            return NETWORK_SINGLE_LEFT;
        if ($row->User_ID == $target_id && !$row->Friend_ID)
            return NETWORK_SINGLE_RIGHT;
        if (!$row->User_ID && !$row->Friend_ID)
            return NETWORK_SINGLE_EMPTY;
     }


    /**
     * Create request for friendship and send message via message system
     *
     * @param int $from person who send request
     * @param int $to target of request
     * @return bolean
     * @access public
     */

     function friendshipRequest($from,$to){
        $status = $this->checkRelations($from,$to);
         $db =& db::getInstance();

        if ($status == NETWORK_SINGLE_DUAL)
            return $this->Error("You are already friends with this member");
        if ($status == NETWORK_SINGLE_LEFT)
            return $this->Error("You have already sent request to this member.Wait to approve");
        if ($status == NETWORK_SINGLE_RIGHT)
            return $this->Error("This member already sent request to you. Just approve it");

        if (!($oFrom = $db->get_row("SELECT * FROM members WHERE mem_userid = '$from'")))
            return $this->Error("You are unknown user");;
        if (!($oTo = $db->get_row("SELECT * FROM members WHERE mem_userid = '$to'")))
            return $this->Error("Unknown target_id ");

        $db->query("INSERT INTO social_network SET
                             User_ID = '$from',
                             Friend_ID = '$to'
                     ");

        $data['ReceiverName'] = $oTo->mem_username;
        $data['SenderName']   = $oFrom->mem_username;
        $data['ApproveLink']  = CONST_LINK_ROOT.'/home.php';
        $data['RejectLink']   = CONST_LINK_ROOT.'/home.php';

        $option_manager =& OptionManager::GetInstance();
        list($type,$message) = getTemplateByName("Friendship_Request",$data,getDefaultLanguage($to));
        send_mail ($oTo->mem_email, $option_manager->GetValue('mail'), SOCIAL_NETWORK_REQUEST, $message ,$type,"ON");
        return true;
     }

    /**
     * Approve friendship request
     *
     * @param int $from request sender
     * @param int $to request receiver
     * @return bolean
     * @access public
     */

     function approveRequest($from,$to){
        $status = $this->checkRelations($from,$to);
         $db =& db::getInstance();
        if ($status == NETWORK_SINGLE_RIGHT) {
            $db->query("INSERT INTO social_network SET
                                 User_ID = '$from',
                                 Friend_ID = '$to',
                                 Status = 'A'
                         ");
            $db->query("UPDATE social_network SET
                             Status = 'A'
                         WHERE User_ID = '$to'
                            AND Friend_ID = '$from'
                        ");
        } else {
            return $this->Error("You can't approve unexisting request");
        }
        return true;
     }

     /**
     * Reject friendship request
     *
     * @param int $from request sender
     * @param int $to request receiver
     * @return bolean
     * @access public
     */

     function rejectRequest($from,$to){
        $status = $this->checkRelations($from,$to);
         $db =& db::getInstance();
        if ($status == NETWORK_SINGLE_RIGHT) {
            $db->query("DELETE FROM social_network
                        WHERE User_ID = '$to'
                         AND    Friend_ID = '$from'
                         ");
        } else {
            return $this->Error("You can't reject unexisting request");
        }
        return true;
     }

     /**
     * Get level Create request for friendship and send message via message system
     *
     * @param int $user_id member from network
     * @param int $level is max level network of scan if it is 0 then will use max depth value from site config
     * @param int mode of result: all network levels 1 = FULL (default), only last level 2=SINGLE
     * @return array
     * @access public
     */

     function getNetwork($user_id,$level,$mode=NETWORK_SINGLE){
         $db =& db::getInstance();
        $user_id = $this->_PrepareData($user_id);

        if (!$user_id) return $this->error("Incorrect user_id");
        if (!$level) return $this->error("Incorrect level");

        $option_manager =& OptionManager::GetInstance();
        $max_allowed_level = $option_manager->GetValue('snetwork_depth');
        if ($level > $max_allowed_level || $level == 0) $level = $max_allowed_level;

        $all=array($user_id);

        $levels=array(0=>array($user_id));

        for ($st=1; $st<=$level; $st++) {
            //selecting friends of 1-st degree
            $current_level=$db->get_results("
                                SELECT DISTINCT Friend_ID
                                FROM social_network
                                    INNER JOIN adverts ON (adv_userid = Friend_ID)
                                WHERE User_ID IN (".join(",",$levels[$st-1]).")
                                AND Friend_ID NOT IN (".join(",",$all).")
                                AND Status = 'A'
                                ");

            foreach ($current_level as $row) $all[]=$levels[$st][]=$row->Friend_ID;

            if (empty($levels[$st]) || !count($levels[$st])) break;
        }
        unset($levels[0]);
        switch ($mode) {
            case NETWORK_FULL:
                return $levels;
                break;
            case NETWORK_SINGLE:
                $result = array_pop($levels);
                return count($result) ? $result : array();
                break;
            default : $this->CriticalError("Incorrect mode value. Please check [NETWORK_FULL,NETWORK_SINGLE]");
        }
     }

     /**
     * Get list of requests for member
     *
     * @param int $to request receiver
     * @return array
     * @access public
     */

     function getRequestList($to){
         $db =& db::getInstance();
        $to = $this->_PrepareData($to);
        $query= "
                SELECT User_ID,Friend_ID
                FROM social_network
                INNER JOIN members ON (User_ID = mem_userid)
                WHERE Friend_ID = '$to'
                AND Status = 'P'
                ";
        $list = $db->get_results($query);
        return $list;
     }

     /**
     * Remove friend
     *
     * @param int $from request sender
     * @param int $to request receiver
     * @return bolean
     * @access public
     */

     function removeFriend($from,$to){
        $status = $this->checkRelations($from,$to);
         $db =& db::getInstance();
        if ($status == NETWORK_SINGLE_DUAL) {
            $db->query("DELETE FROM social_network
                        WHERE
                            (User_ID = '$to' AND    Friend_ID = '$from')
                        OR
                            (User_ID = '$from' AND  Friend_ID = '$to')
                         ");
        } else {
            return $this->Error("This member is't you friend. You can't to remove it");
        }
        return true;
     }

     /**
     * Add e-mail to invited friends list
     *
     * @param int $user_id sender id
     * @param array friend e-mails
     * @return bolean
     * @access public
     */

     function saveInvitedFriends($user_id, $aEmails){
        $user_id = $this->_PrepareData($user_id);
         $db =& db::getInstance();
            foreach ($aEmails as $email) {
                $email = $this->_PrepareData($email);
                if ($email) {
                    $db->query("REPLACE INTO networkinvite
                                SET
                                    User_ID = $user_id,
                                    Email = '$email'
                                ");
                }
            }
        return true;
     }

     /**
     * Add user to network if he is in invited list
     *
     * @param int $user_id sender id
     * @param string user e-mail
     * @return bolean
     * @access public
     */

     function addInvitedUser($user_id, $email){
        $user_id = $this->_PrepareData($user_id);
         $db =& db::getInstance();
        $email = $this->_PrepareData($email);

        $AMembers = $db->get_results("
                        SELECT *
                        FROM networkinvite
                        WHERE Email = '$email'
                        ");
        foreach ($AMembers as $row) {
            $this->friendshipRequest($user_id,$row->User_id);
        }
        return true;
     }


}
?>