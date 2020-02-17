<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name: 		prgprofile.php
#
# Description:  creates or updates profile information from profile.php
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include_once 'validation_functions.php';
include('session_handler.inc');
include('error.php');
include('functions.php');

$mode=$_GET['mode'];
$is_speeddating =sanitizeData($_GET['speeddating'], 'xss_clean'); 
# retrieve the template
if($is_speeddating){
	$area = 'speeddating';
} elseif ($_SESSION['Sess_JustRegistered']==true) {
    $area = 'guest';
} else {
    $area = 'member';
}

//$result=mysql_query("SELECT * FROM members WHERE mem_userid=$Sess_UserId",$link);
//$sql_user=mysql_fetch_object($result);

$sql_user=$db->get_row("SELECT * FROM members WHERE mem_userid = $Sess_UserId");

if ($mode != 'skip' && !$is_speeddating) {
	$lstPerson1=sanitizeData($_POST['lstPerson1'], 'xss_clean');
	$lstPerson2=sanitizeData($_POST['lstPerson2'], 'xss_clean');
	$lstPerson3=sanitizeData($_POST['lstPerson3'], 'xss_clean');
	$lstPhilos1=sanitizeData($_POST['lstPhilos1'], 'xss_clean');
	$lstPhilos2=sanitizeData($_POST['lstPhilos2'], 'xss_clean');
	$lstPhilos3=sanitizeData($_POST['lstPhilos3'], 'xss_clean'); 
	$lstSocial1=sanitizeData($_POST['lstSocial1'], 'xss_clean');
	$lstSocial2=sanitizeData($_POST['lstSocial2'], 'xss_clean');
	$lstSocial3=sanitizeData($_POST['lstSocial3'], 'xss_clean');
	$lstGoal1=sanitizeData($_POST['lstGoal1'], 'xss_clean');
	$lstGoal2=sanitizeData($_POST['lstGoal2'], 'xss_clean');
	$lstGoal3=sanitizeData($_POST['lstGoal3'], 'xss_clean');
	$lstHobby1=sanitizeData($_POST['lstHobby1'], 'xss_clean');
	$lstHobby2=sanitizeData($_POST['lstHobby2'], 'xss_clean');
	$lstHobby3=sanitizeData($_POST['lstHobby3'], 'xss_clean');
	$lstSport1=sanitizeData($_POST['lstSport1'], 'xss_clean');
	$lstSport2=sanitizeData($_POST['lstSport2'], 'xss_clean');
	$lstSport3=sanitizeData($_POST['lstSport3'], 'xss_clean');
	$lstMusic1=sanitizeData($_POST['lstMusic1'], 'xss_clean');
	$lstMusic2=sanitizeData($_POST['lstMusic2'], 'xss_clean');
	$lstMusic3=sanitizeData($_POST['lstMusic3'], 'xss_clean');
	$lstFood1=sanitizeData($_POST['lstFood1'], 'xss_clean');
	$lstFood2=sanitizeData($_POST['lstFood2'], 'xss_clean');
	$lstFood3=sanitizeData($_POST['lstFood3'], 'xss_clean');
	# gives basic validation if the javascript fails to catch the error
	if ($lstPerson1 == "- Choose -") {
		$error_message=PRGPROFILE_PLEASE;
		error_page($error_message,GENERAL_USER_ERROR);
	}
 	# check whether immediate authorisation
	$approved=$option_manager->GetValue('authorisead');
	# check to see if the mode is create (personal.htm) or update (prgamendpro.php)

	if ($mode == 'create') {
		# Insert the new profile
		$query="INSERT INTO profiles (	pro_userid,
						pro_person1,
						pro_person2,
						pro_person3,
						pro_philos1,
						pro_philos2,
						pro_philos3,
						pro_social1,
						pro_social2,
						pro_social3,
						pro_goal1,
						pro_goal2,
						pro_goal3,
						pro_hobby1,
						pro_hobby2,
						pro_hobby3,
						pro_sport1,
						pro_sport2,
						pro_sport3,
						pro_music1,
						pro_music2,
						pro_music3,
						pro_food1,
						pro_food2,
						pro_food3)
					VALUES ('$Sess_UserId',
						'$lstPerson1',
						'$lstPerson2',
						'$lstPerson3',
						'$lstPhilos1',
						'$lstPhilos2',
						'$lstPhilos3',
						'$lstSocial1',
						'$lstSocial2',
						'$lstSocial3',
						'$lstGoal1',
						'$lstGoal2',
						'$lstGoal3',
						'$lstHobby1',
						'$lstHobby2',
						'$lstHobby3',
						'$lstSport1',
						'$lstSport2',
						'$lstSport3',
						'$lstMusic1',
						'$lstMusic2',
						'$lstMusic3',
						'$lstFood1',
						'$lstFood2',
						'$lstFood3')";
                $db->query($query);
		/*if (!mysql_query($query,$link)) {
			if (mysql_errno($link) == 1062) {
				$query="UPDATE profiles SET pro_person1 ='$lstPerson1',
					pro_person2 ='$lstPerson2',
					pro_person3 ='$lstPerson3',
					pro_philos1 ='$lstPhilos1',
					pro_philos2 ='$lstPhilos2',
					pro_philos3 ='$lstPhilos3',
					pro_social1 ='$lstSocial1',
					pro_social2 ='$lstSocial2',
					pro_social3 ='$lstSocial3',
					pro_goal1 ='$lstGoal1',
					pro_goal2 ='$lstGoal2',
					pro_goal3 ='$lstGoal3',
					pro_hobby1 ='$lstHobby1',
					pro_hobby2 ='$lstHobby2',
					pro_hobby3 ='$lstHobby3',
					pro_sport1 ='$lstSport1',
					pro_sport2 ='$lstSport2',
					pro_sport3 ='$lstSport3',
					pro_music1 ='$lstMusic1',
					pro_music2 ='$lstMusic2',
					pro_music3 ='$lstMusic3',
					pro_food1 ='$lstFood1',
					pro_food2 ='$lstFood2',
					pro_food3 = '$lstFood3'
				WHERE pro_userid='$Sess_UserId'";
				if (!mysql_query($query,$link)) {error_page(mysql_error(),GENERAL_SYSTEM_ERROR);}
			}else{
				error_page(mysql_error(),GENERAL_SYSTEM_ERROR);
			}
		} */

	# Code to execute if the mode is update (prgamendad.php)
	} else if ($mode == 'update') {
		$query="UPDATE profiles SET pro_person1 ='$lstPerson1',
					pro_person2 ='$lstPerson2',
					pro_person3 ='$lstPerson3',
					pro_philos1 ='$lstPhilos1',
					pro_philos2 ='$lstPhilos2',
					pro_philos3 ='$lstPhilos3',
					pro_social1 ='$lstSocial1',
					pro_social2 ='$lstSocial2',
					pro_social3 ='$lstSocial3',
					pro_goal1 ='$lstGoal1',
					pro_goal2 ='$lstGoal2',
					pro_goal3 ='$lstGoal3',
					pro_hobby1 ='$lstHobby1',
					pro_hobby2 ='$lstHobby2',
					pro_hobby3 ='$lstHobby3',
					pro_sport1 ='$lstSport1',
					pro_sport2 ='$lstSport2',
					pro_sport3 ='$lstSport3',
					pro_music1 ='$lstMusic1',
					pro_music2 ='$lstMusic2',
					pro_music3 ='$lstMusic3',
					pro_food1 ='$lstFood1',
					pro_food2 ='$lstFood2',
					pro_food3 = '$lstFood3'
				WHERE pro_userid='$Sess_UserId'";
		//if (!mysql_query($query,$link)) {error_page(mysql_error(),"System Error");}
                 $db->query($query);
	}
}

# final stage of registration
if ($_SESSION['Sess_JustRegistered']==true) {
    $confirm_url = $CONST_LINK_ROOT.($is_speeddating?"/speeddating":"")."/confirm.php?id=".md5($sql_user->mem_userid);

    $data['UserName'] = $sql_user->mem_username;
    $data['Password'] = $sql_user->mem_password;
    $data['ConfirmUrl'] = $confirm_url;
    $data['CompanyName'] = $CONST_COMPANY;
    $data['Url'] = $CONST_URL;
    $data['SupportEmail'] = $CONST_SUPPMAIL;
    $m_template = ($CONST_EMAIL_CONFIRM == 'Y') ? 'Welcome_Mail_Activate' : 'Welcome_Mail';
    list($type,$message) = getTemplateByName($m_template,$data,getDefaultLanguage($sql_user->mem_userid));

	send_mail ("$sql_user->mem_email", "$CONST_MAIL", PRGPROFILE_WELCOME." $CONST_COMPANY!", "$message", $type, "ON");

	# member is now registered
	unset($_SESSION['Sess_JustRegistered']);
    session_unset();
    session_destroy();
} else {
	header("Location: $CONST_LINK_ROOT/myinfo.php"); exit;
}

$regtitle=PRGREGISTER_TEXT25;
$regtext=PRGREGISTER_TEXT31;
if($CONST_EMAIL_CONFIRM == 'Y')
	$regtext .= PRGREGISTER_TEXT38;
$reglink="<p>".PRGREGISTER_TEXT26."</p>".($is_speeddating ?
"<a href='$CONST_LINK_ROOT/speeddating/index.php'>".GENERAL_CONTINUE."</a>" :
"<a href='$CONST_LINK_ROOT/login.php'>".GENERAL_CONTINUE."</a>"
);
//mysql_close( $link );
?>

<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td colspan="2" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="pageheader"><?php echo REGISTER_SECTION_NAME ?></td>
  </tr>
	<tr>
    <td align="left" valign="top"><img src="./additional_images/completed.png" hspace="5px" width="85" height="85">&nbsp;</td>
  <td><b><?php print("$regtitle"); ?></b>
    <p><?php print("$regtext"); ?></p>
    <p><?php print("$paragraph"); ?></p>
    <p><?php print("$reglink"); ?></p></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>