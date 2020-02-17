<?
include_once __INCLUDE_CLASS_PATH."/class.Main.php";
class Messages extends Main {

    /**
     * Send message to member
     *
     * @param string $LangID
     * @access public
     */
    function send($from_id,$to_id,$from_name,$subject,$message){
        $db = & db::getInstance();
        $option_manager =& OptionManager::GetInstance();

        $from_id = $this->_PrepareData($from_id);
        $to_id = $this->_PrepareData($to_id);
        $from_name = $this->_PrepareData($from_name);
        $subject = $this->_PrepareData($subject);
        $message = $this->_PrepareData($message);

   		$sql_array = $db->get_row("SELECT mem_username, mem_email, mem_block_mail
   								   FROM members WHERE mem_userid = '$to_id'");
        if (!$sql_array) return $this->error("You try to send e-mail to not existing member");

		$query="INSERT INTO messages
				(msg_senderid, msg_receiverid, msg_senderhandle, msg_title, msg_text, msg_dateadded, msg_read)
				VALUES
				('$from_id', '$to_id','$from_name' ,'$subject', '$message', NOW(), 'U' )
				";
		$db->query($query);

        if ($sql_array->mem_block_mail == 1){
			$data['ReceiverName'] = $sql_array->mem_username;
			$data['SenderName']   = $from_name;
			$data['CompanyName']  = $option_manager->GetValue('company');
			$data['Url']          = $option_manager->GetValue('url');
			$data['SupportEmail'] = $option_manager->GetValue('suppmail');

			list($type,$message) = getTemplateByName("You_Have_Got_A_Mail",$data,getDefaultLanguage($to_id));
	        send_mail ("$sql_array->mem_email", $option_manager->GetValue('mail'), PRGSENDMAIL_TEXT4, $message ,$type,"ON");
        }
	}
}
?>