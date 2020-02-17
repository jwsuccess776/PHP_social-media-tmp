<?php
include('db_connect.php');
include('functions.php');

    $query="SELECT *, mym_comment FROM adverts LEFT JOIN mymatch ON (adv_userid = mym_userid)";
    $result=mysql_query($query,$link) or die(mysql_error());
	
	while($sql_array=mysql_fetch_object($result)) {
		
		$txtMyComment=one_wordwrap($sql_array->adv_comment,25);
		$txtMyMComment=one_wordwrap($sql_array->mym_comment,25);
		$id=$sql_array->adv_userid;
		
		$txtMyComment=mysql_escape_string($txtMyComment);
		$txtMyMComment=mysql_escape_string($txtMyMComment);
		
		$message="This is a system generated message. As profiles are auto-approved";
		$title="";

		$query="UPDATE adverts SET adv_comment = '$txtMyComment' WHERE adv_userid = $id";
		$result2=mysql_query($query,$link) or die(mysql_error());
		$query="UPDATE mymatch SET mym_comment = '$txtMyMComment' WHERE mym_userid = $id";
		$result2=mysql_query($query,$link) or die(mysql_error());
		
		$query="UPDATE adverts SET adv_comment = '$txtMyComment' WHERE adv_userid = $id";
		$result2=mysql_query($query,$link) or die(mysql_error());
		$query="UPDATE mymatch SET mym_comment = '$txtMyMComment' WHERE mym_userid = $id";
		$result2=mysql_query($query,$link) or die(mysql_error());


	}

	echo "done";

?>
