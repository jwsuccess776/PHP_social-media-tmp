<?php

$result=mysql_query("SELECT * FROM profiles WHERE pro_userid = $advuser",$link);
$HASprofiles = mysql_num_rows($result);
$personality="";
if ($HASprofiles > 0) {
	$sql_array2 = mysql_fetch_object($result);
	switch ($sql_array->adv_sex) {
		case 'F':
			$verb1='She';
			$verb2='herself';
			$verb3='Her';
			$verb4='her';
			$verb5='she';
			$s='s';
			break;
		case 'M':
			$verb1='He';
			$verb2='himself';
			$verb3='His';
			$verb4='his';
			$verb5='he';
			$s='s';
			break;
		case 'C':
			$verb1='They';
			$verb2='themselves';
			$verb3='Their';
			$verb4='their';
			$verb5='they';
			$s='';
			break;
		}
		
		# added by tony for formatting of profile in prgretuser 07-09-2004
		$rowhead="<tr><td>&nbsp;</td></tr><tr><td class='small'>";
		$rowfoot="</td></tr>";
		
		# interprete the profile with correct gender
		if ($sql_array2->pro_person2 !='- Not selected -' && $sql_array2->pro_person3 !='- Not selected -' ) {
			$personality="<b>$sql_array->adv_username</b> describe$s $verb2 as ".$sql_array2->pro_person1.", ".$sql_array2->pro_person2." and ".$sql_array2->pro_person3.". ";
		} elseif ($sql_array2->pro_person2 !='- Not selected -' && $sql_array2->pro_person3 =='- Not selected -'){
			$personality="<b>$sql_array->adv_username</b> describe$s $verb2 as ".$sql_array2->pro_person1." and ".$sql_array2->pro_person2.". ";
		} elseif ($sql_array2->pro_person3 !='- Not selected -' && $sql_array2->pro_person2 =='- Not selected -' ) {
			$personality="<b>$sql_array->adv_username</b> describe$s $verb2 as ".$sql_array2->pro_person1." and ".$sql_array2->pro_person3.". ";
		} elseif ($sql_array2->pro_person2 =='- Not selected -' && $sql_array2->pro_person3 =='- Not selected -' ) {
			$personality="<b>$sql_array->adv_username</b> describe$s $verb2 as ".$sql_array2->pro_person1.". ";
		}
		if ($sql_array2->pro_philos2 !='- Not selected -' && $sql_array2->pro_philos3 !='- Not selected -' ) {
			$personality=$personality."$verb3 outlook on life is ".$sql_array2->pro_philos1.", ".$sql_array2->pro_philos2." and ".$sql_array2->pro_philos3." ";
		} elseif ($sql_array2->pro_philos2 !='- Not selected -'){
			$personality=$personality."$verb3 outlook on life is ".$sql_array2->pro_philos1." and ".$sql_array2->pro_philos2." ";
		} elseif ($sql_array2->pro_philos3 !='- Not selected -' ) {
			$personality=$personality."$verb3 outlook on life is ".$sql_array2->pro_philos1." and ".$sql_array2->pro_philos3." ";
		} elseif ($sql_array2->pro_philos2 =='- Not selected -' && $sql_array2->pro_philos3 =='- Not selected -' ) {
			$personality=$personality."$verb3 outlook on life is ".$sql_array2->pro_philos1." ";
		}
		if ($sql_array2->pro_goal2 !='- Not selected -' && $sql_array2->pro_goal3 !='- Not selected -' ) {
			$personality=$personality." and $verb4 goals are ".$sql_array2->pro_goal1.", ".$sql_array2->pro_goal2." and ".$sql_array2->pro_goal3.". ";
		} elseif ($sql_array2->pro_goal2 !='- Not selected -' && $sql_array2->pro_goal3 =='- Not selected -' ){
			$personality=$personality." and $verb4 goals are  ".$sql_array2->pro_goal1." and ".$sql_array2->pro_goal2.". ";
		} elseif ($sql_array2->pro_goal3 !='- Not selected -' && $sql_array2->pro_goal2 =='- Not selected -' ) {
			$personality=$personality." and $verb4 goals are ".$sql_array2->pro_goal1." and ".$sql_array2->pro_goal3.". ";
		} elseif ($sql_array2->pro_goal2 =='- Not selected -' && $sql_array2->pro_goal3 =='- Not selected -' ) {
			$personality=$personality." and $verb4 goal is $verb4 ".$sql_array2->pro_goal1.". ";
		}
		if ($sql_array2->pro_social2 !='- Not selected -' && $sql_array2->pro_social3 !='- Not selected -' ) {
			$personality=$personality."$verb1 like$s to hang out with ".$sql_array2->pro_social1.", ".$sql_array2->pro_social2." or ".$sql_array2->pro_social3." ";
		} elseif ($sql_array2->pro_social2 !='- Not selected -'){
			$personality=$personality."$verb1 like$s to hang out with ".$sql_array2->pro_social1." or ".$sql_array2->pro_social2." ";
		} elseif ($sql_array2->pro_social3 !='- Not selected -' ) {
			$personality=$personality."$verb1 like$s to hang out with ".$sql_array2->pro_social1." or ".$sql_array2->pro_social3." ";
		} elseif ($sql_array2->pro_social2 =='- Not selected -' && $sql_array2->pro_social3 =='- Not selected -' ) {
			$personality=$personality."$verb1 like$s to hang out with ".$sql_array2->pro_social1." ";
		}
		if ($sql_array2->pro_food2 !='- Not selected -' && $sql_array2->pro_food3 !='- Not selected -' ) {
			$personality=$personality." and $verb5 enjoy$s eating ".$sql_array2->pro_food1.", ".$sql_array2->pro_food2." and ".$sql_array2->pro_food3." food. ";
		} elseif ($sql_array2->pro_food2 !='- Not selected -' && $sql_array2->pro_food3 =='- Not selected -' ){
			$personality=$personality." and $verb5 enjoy$s eating ".$sql_array2->pro_food1." and ".$sql_array2->pro_food2." food. ";
		} elseif ($sql_array2->pro_food3 !='- Not selected -' && $sql_array2->pro_food2 =='- Not selected -' ) {
			$personality=$personality." and $verb5 enjoy$s eating ".$sql_array2->pro_food1." and ".$sql_array2->pro_food3." food. ";
		} elseif ($sql_array2->pro_food2 =='- Not selected -' && $sql_array2->pro_food3 =='- Not selected -' ) {
			$personality=$personality." and $verb5 enjoy$s eating ".$sql_array2->pro_food1." food. ";
		}
		if ($sql_array2->pro_music2 !='- Not selected -' && $sql_array2->pro_music3 !='- Not selected -' ) {
			$personality=$personality."$verb1 prefer$s to listen to ".$sql_array2->pro_music1.", ".$sql_array2->pro_music2." and ".$sql_array2->pro_music3." music. ";
		} elseif ($sql_array2->pro_music2 !='- Not selected -' && $sql_array2->pro_music3 =='- Not selected -' ){
			$personality=$personality."$verb1 prefer$s to listen to ".$sql_array2->pro_music1." and ".$sql_array2->pro_music2." music. ";
		} elseif ($sql_array2->pro_music3 !='- Not selected -' && $sql_array2->pro_music2 =='- Not selected -' ) {
			$personality=$personality."$verb1 prefer$s to listen to ".$sql_array2->pro_music1." and ".$sql_array2->pro_music3." music. ";
		} elseif ($sql_array2->pro_music2 =='- Not selected -' && $sql_array2->pro_music3 =='- Not selected -' ) {
			$personality=$personality."$verb1 prefer$s to listen to ".$sql_array2->pro_music1." music. ";
		}
		if ($sql_array2->pro_hobby2 !='- Not selected -' && $sql_array2->pro_hobby3 !='- Not selected -' ) {
			$personality=$personality."$verb3 main hobbies include ".$sql_array2->pro_hobby1.", ".$sql_array2->pro_hobby2." and ".$sql_array2->pro_hobby3." ";
		} elseif ($sql_array2->pro_hobby2 !='- Not selected -' && $sql_array2->pro_hobby3 =='- Not selected -' ){
			$personality=$personality."$verb3 main hobbies include ".$sql_array2->pro_hobby1." and ".$sql_array2->pro_hobby2." ";
		} elseif ($sql_array2->pro_hobby3 !='- Not selected -' && $sql_array2->pro_hobby2 =='- Not selected -' ) {
			$personality=$personality."$verb3 main hobbies include ".$sql_array2->pro_hobby1." and ".$sql_array2->pro_hobby3." ";
		} elseif ($sql_array2->pro_hobby2 =='- Not selected -' && $sql_array2->pro_hobby3 =='- Not selected -' ) {
			$personality=$personality."$verb3 main hobby is ".$sql_array2->pro_hobby1." ";
		}
		if ($sql_array2->pro_sport2 !='- Not selected -' && $sql_array2->pro_sport3 !='- Not selected -' ) {
			$personality=$personality."and $verb4 favourite sports are ".$sql_array2->pro_sport1.", ".$sql_array2->pro_sport2." and ".$sql_array2->pro_sport3.". ";
		} elseif ($sql_array2->pro_sport2 !='- Not selected -' && $sql_array2->pro_sport3 =='- Not selected -' ){
			$personality=$personality."and $verb4 favourite sports are ".$sql_array2->pro_sport1." and ".$sql_array2->pro_sport2.". ";
		} elseif ($sql_array2->pro_sport3 !='- Not selected -' && $sql_array2->pro_sport2 =='- Not selected -' ) {
			$personality=$personality."and $verb4 favourite sports are ".$sql_array2->pro_sport1." and ".$sql_array2->pro_sport3.". ";
		} elseif ($sql_array2->pro_sport2 =='- Not selected -' && $sql_array2->pro_sport3 =='- Not selected -' ) {
			$personality=$personality."and $verb4 favourite sport is ".$sql_array2->pro_sport1.". ";
		}
	
}
?>