<?php
//session_start();
	$autoloader = "../vendor-old/autoload.php";
	$config = "../../db_connect.php";
        include_once('../../validation_functions.php'); 

	require($autoloader);
	require_once ($config);

	global $DB;
	if(isset($_POST)) {

		extract($_POST);

		if($key == 'addTime') {		
			//if($uType !== '64' ) {
				
				if($chat == 'tchat') {
					$chat_cost = 1;
				} else if($chat == 'vchat') {
					$chat_cost = 4;
				}
				print_r($session_id);
                                
                                $session_id=sanitizeData($session_id, 'xss_clean');  
				mysqli_query( $globalMysqlConn ,"DELETE FROM members_opentok_chat WHERE `session_id`='".$session_id."'") or die(mysqli_error());
				mysqli_query( $globalMysqlConn ,"DELETE FROM members_videochat WHERE `session_id`='".$session_id."'") or die(mysqli_error());

				$chatCharges =  $chat_cost*$time;
				mysqli_query( $globalMysqlConn ,"UPDATE im SET `read`='yes',`status`='disconnected' WHERE `sessionId`='".$session_id."'") or die(mysqli_error());
				$total_token = mysqli_query( $globalMysqlConn ,"SELECT `dataid`,`chat_token`,`message`,`chat_time` FROM im WHERE `sessionId`='".$session_id."'") or die(mysqli_error());

				mysqli_query( $globalMysqlConn ,"INSERT INTO `members_service_transactions` ( `id` , `member_id` , `reciever_id` , `service_id` , `service_name` , `token_value` , `created_at`) 
					VALUES ('','".$uid."','".$Sess_UserId."', '4','".$total_token['message']."', '".$total_token['chat_token']."','".date('Y-m-d h:i:s')."')") or die(mysqli_error());

				$serviceId = mysqli_insert_id($globalMysqlConn);
				$service_order_id = '10000'.sprintf('%03d',$serviceId);

				mysqli_query( $globalMysqlConn ,"UPDATE `members_service_transactions` SET service_order_id=".$service_order_id." WHERE id=".$serviceId) or die(mysqli_error());
				

				if($uType !== '64' ) {
					mysqli_query( $globalMysqlConn ,"INSERT INTO `chat_detail` (`chat_id`,`im_id`,`chat_type`,`chat_duration`,`chat_tokens`,`created_at`) VALUES ('','".$total_token['dataid']."','".$total_token['message']."','".$total_token['chat_time']."', '".$total_token['chat_token']."','".date('Y-m-d h:i:s')."')") or die(mysqli_error());
					
					if($total_token['message'] == 'tchat') {
						$chat_type = 'Live chat';
					} else if($total_token['message'] == 'vchat') {
						$chat_type = 'Video chat';
					}
					$memberValues = array();
					$memberValues['reciever_id'] = $Sess_UserId;
					$memberValues['transaction_type'] = $chat_type;
					$memberValues['total_token'] = $total_token['chat_token'];
					$memberValues['extra'] = $total_token['chat_time'];
					AddCommission($memberValues);
				}
				
				print_r($total_token);
			//}
			
		}
		

			

			if($key == 'unlockImage') {

				$token_balance = checkTokens($uid);
				$unlockCharges =  10;
					if($unlockCharges >= $token_balance ) { 	
						echo 0;
					} else {
                                            $imgId=sanitizeData($imgId, 'xss_clean');  
						mysqli_query( $globalMysqlConn ,"UPDATE chat_photos SET views=1, updated_at='".date('Y-m-d h:s:i')."' WHERE bigimage LIKE '%".$imgId."%'")  or die(mysqli_error());
						mysqli_query( $globalMysqlConn ,"INSERT INTO `members_service_transactions` ( `id` , `member_id` , `reciever_id` , `service_id` , `service_name` , `token_value` , `created_at`) VALUES ('','".$uid."','".$_SESSION['to_id']."', '6','Unlock chat image', '".$unlockCharges."','".date('Y-m-d h:i:s')."')") or die(mysqli_error());

					$serviceId = $DB->InsertID();
					$service_order_id = '10000'.sprintf('%03d',$serviceId);

					mysqli_query( $globalMysqlConn ,"UPDATE `members_service_transactions` SET service_order_id=".$service_order_id." WHERE id=".$serviceId) or die(mysqli_error());

					$memberValues = array();
					$memberValues['reciever_id'] = $_SESSION['to_id'];
					$memberValues['transaction_type'] ='Unlock Chat Image';
					$memberValues['total_token'] = $unlockCharges;
					AddCommission($memberValues);
					echo 1;
			}
		}
		
		
		if($key == 'messageCheck') {
			$message = $msg_text;
                        
                         $message=sanitizeData($message, 'xss_clean');  
			/*print_r($message);
			die('die');*/
			if($message != '') {
				//$SQL = "SELECT count(*) as total  FROM badwords WHERE word LIKE '%".$message."%'";
				$result = mysqli_query( $globalMysqlConn ,"SELECT count(*) as total FROM badwords where MATCH (word) AGAINST ('".$message."' IN BOOLEAN MODE)") or die(mysqli_error());
				// Associative array
				$row=$result->fetch_assoc();
				if($row['total']== 0) {
					echo 1;
				} else {
					echo 0;
				}	
			} else {
				echo 1;
			}
			
		}	
	} 



?>
