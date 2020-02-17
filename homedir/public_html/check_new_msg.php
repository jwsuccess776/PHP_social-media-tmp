<?php
	$chat_receiver = "SELECT * FROM members_opentok_chat WHERE read_st='0' AND to_uid='".$Sess_UserId."'";
	$result=mysqli_query($globalMysqlConn, $chat_receiver) or die(mysqli_error());
		if (mysqli_num_rows($result) > 0) {
		    // output data of each row
		    while($row = mysqli_fetch_assoc($result)) { 
		        $id = $row["id"];
				mysqli_query($globalMysqlConn, "UPDATE members_opentok_chat SET read_st='1' WHERE id='".$id."'") or die(mysqli_error());
		        $data = $row["uid"];
				 
		    }
		} 
?>