<?php
	//session_start();
	$autoloader = "../vendor-old/autoload.php";
	$config = "../../db_connect.php";
        include_once('../../validation_functions.php');

	require($autoloader);
	include ($config);

	global $DB;

	use Slim\Slim;
	use Gregwar\Cache\Cache;
	use OpenTok\OpenTok;

	use OpenTok\MediaMode;
	use OpenTok\ArchiveMode;


	$apiKey = "46312132";
	$apiKey = (string)$apiKey;

	$apiSecret = "fc6af48ba2afeb8cd977251dad3957a6a8683357";
	$apiSecret = (string)$apiSecret;

	$opentok = new OpenTok($apiKey, $apiSecret);

	if(isset($receive_id)) {
            
          $receive_id=  sanitizeData(trim($_GET['receive_id']), 'xss_clean'); 
		
		$update_status = "UPDATE members_videochat set `read`='yes' WHERE uid='".$receive_id."' AND to_uid='".$Sess_UserId."'";
		$result1=mysqli_query($globalMysqlConn, $update_status) or die(mysqli_error());

		$update_opentak= "UPDATE members_opentok_chat SET `read_st`='1' WHERE uid='".$receive_id."' AND to_uid='".$Sess_UserId."'";
		$result5=mysqli_query($globalMysqlConn, $update_opentak) or die(mysqli_error());
	
		$chat_session = "SELECT `session_id`,`token_id` FROM members_videochat WHERE uid='".$receive_id."' AND to_uid='".$Sess_UserId."'";
		$result2=mysqli_query($globalMysqlConn, $chat_session) or die(mysqli_error());

		
		// Associative array
		$row=mysqli_fetch_assoc($result2);
		$session_id = $row["session_id"];
		$token = $row["token_id"];

	} else {

            $to_uid=sanitizeData(trim($_GET['to_uid']), 'xss_clean');  
		$chat_session_old = "SELECT `session_id`,`token_id`,`read` FROM members_videochat WHERE `uid`='".$Sess_UserId."' AND `to_uid`='".$to_uid."' AND `read`='no' LIMIT 1";
		$result3=mysqli_query($globalMysqlConn, $chat_session_old) or die(mysqli_error());


		/*if(sizeof($chat_session_old) > 1 && $chat_session_old['status'] == '') {
			$chatSS = 'disconnected';
		}*/
		// Associative array
		$row3=mysqli_fetch_assoc($result3);
		
		if(!isset($row3['session_id'])){
			
			$session = $opentok->createSession();
			$session_id = $session->getSessionId();
			$token =  $opentok->generateToken($session_id);
			//die($session_id);
			mysqli_query( $globalMysqlConn ,"INSERT INTO members_videochat (`uid`,`to_uid`,`datetime`,`read`,`session_id`,`token_id`) VALUES('".$Sess_UserId."','".$to_uid."','".date('Y-m-d h:i:s')."','no','".$session_id."','".$token."')") or die(mysqli_error());

			mysqli_query( $globalMysqlConn ,"INSERT INTO members_opentok_chat (`uid`,`to_uid`,`session_id`,`token_id`,`read_st`,`username`,`datetime`) VALUES('".$Sess_UserId."','".$to_uid."','".$session_id."','".$token."','0','".$Sess_UserName."','".date('Y-m-d h:i:s')."')") or die(mysqli_error());
			
		} else {
			$session_id = $row3["session_id"];
			$token = $row3["token_id"];
		}
		
			
		
	}
	
	$data = array();

	$data['apiKey'] = $apiKey;
	$data['sessionId'] = $session_id;
    $data['token'] = $token;

	echo json_encode($data);

?>