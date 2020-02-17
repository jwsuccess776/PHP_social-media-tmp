<?php

/*****************************************************
* © copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:         addmymatch.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
############################
include('db_connect.php');

$query="DELETE FROM mymatch";
$retval=mysql_query($query,$link) or die(mysql_error());

$query="SELECT *, (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age FROM adverts";
$retval=mysql_query($query,$link) or die(mysql_error());

$harray=array('121','124','127','130','132','135','137','140','142','145','147','150','152','155','157','160','163','165','168','170','173','175','178','180','183','185','188','190','193','196','198','201','203','206','208','211','213','216','218','221','224','226','229');

while ($advert=mysql_fetch_object($retval)) {

	print("Processing Record $advert->adv_userid...<br>");
	flush();

	if (adv_sex=='F') {
		if ($advert->adv_seekwmn=='Y')	$mysex='F';
		elseif ($advert->adv_seekmen=='Y')	$mysex='M';
		else $mysex='C';
		
		if ($advert->adv_height == 'Not stated' OR $advert->adv_height==' ') {
			$mytoheight='Not stated';
			$myfromheight='Not stated';
		} else {
			$mytoheight=$advert->adv_height+20;
			while (!in_array ( $mytoheight, $harray)) {
				$mytoheight-=1;
				print("$mytoheight  -");
			}
			$myfromheight=$advert->adv_height;
		}
		
		$mybody=$advert->adv_bodytype;
		$myagemin=$advert->age;
		if ($myagemin < 18) $myagemin = 18;
		$myagemax=$advert->age+6;
		$myrelationship=$advert->adv_seeking;

		$query="INSERT INTO mymatch (mym_gender, mym_smoker, mym_minheight, mym_maxheight, mym_bodytype, mym_agemin, mym_agemax, mym_relationship) 
				VALUES('$mysex', '$advert->adv_smoker','$myfromheight','$mytoheight','$mybody','$myagemin','$myagemax','$myrelationship')";
		$result=mysql_query($query,$link) or die(mysql_error());

	} else {
		if ($advert->adv_seekwmn=='Y')	$mysex='F';
		elseif ($advert->adv_seekmen=='Y')	$mysex='M';
		else $mysex='C';
		
		if ($advert->adv_height == 'Not stated') {
			$mytoheight='Not stated';
			$myfromheight='Not stated';
		} else {
			$myfromheight=$advert->adv_height-5;
			while (!in_array ( $myfromheight, $harray)) {
				$myfromheight+=1;
			}
			$mytoheight=$advert->adv_height;
		}
		
		$mybody=$advert->adv_bodytype;
		$myagemin=$advert->age-10;
		if ($myagemin < 18) $myagemin = 18;
		$myagemax=$advert->age;
		$myrelationship=$advert->adv_seeking;
		$advert->adv_comment=addslashes($advert->adv_comment);
		$query="INSERT INTO mymatch (mym_userid, mym_gender, mym_smoker, mym_comment, mym_minheight, mym_maxheight, mym_bodytype, mym_agemin, mym_agemax, mym_relationship) 
				VALUES($advert->adv_userid, '$mysex', '$advert->adv_smoker','$advert->adv_comment','$myfromheight','$mytoheight','$mybody','$myagemin','$myagemax','$myrelationship')";
		$result=mysql_query($query,$link) or die(mysql_error());

	}

}

mysql_close($link);
?>
