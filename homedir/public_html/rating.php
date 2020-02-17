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
# Name: 	    rating.php
#
# Description:
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include_once('validation_functions.php');
session_cache_limiter('private, must-revalidate');
session_start();

# retrieve the template
$area = 'member';
if (isset($_GET['lstView'])) $lstView=sanitizeData($_GET['lstView'], 'xss_clean') ;    
if (isset($_GET['source'])) $source=sanitizeData($_GET['source'], 'xss_clean') ; 
#################################
#first time the screen is entered
#################################
if (!isset($source)) {
	# set the variables in case login is clicked
	$lstView=NULL;
	$vote=NULL;
	$userid=NULL;
	$lastpic=NULL;
	$showme=NULL;
	$source=NULL;
	if (isset($Sess_UserId) && !isset($start)) {
		session_register("start");	$start=0;
	} elseif (!isset($Sess_UserId) && !isset($start)) {
		$start=0;
	} elseif (isset($Sess_UserId) && isset($start)) {
		$start=$start-1;
	}
} else {
	if ($source=='login' && $vote!=NULL) $start=$start-1;
	switch ($lstView) {
		case '':
			$showme="";
			break;
		case 'F':
			$showme="AND adv_sex='F'";
			break;
		case 'M':
			$showme="AND adv_sex='M'";
			break;
		case 'C':
			$showme="AND adv_sex='C'";
			break;
	}
}
if ($vote != NULL && $userid > 0) {
	$query="SELECT * FROM ratings WHERE rte_userid=$userid";
	$result=mysql_query($query,$link) or die("$query".mysql_error());
	if (mysql_num_rows($result) > 0 ) {
		$sql_array=mysql_fetch_object($result);
		$num_value=$sql_array->rte_value+$vote;
		$num_votes=$sql_array->rte_votes+1;
		$num_average=$num_value/$num_votes;
		$num_average=round($num_average, 1);
		$query="UPDATE ratings SET rte_votes=$num_votes, rte_value=$num_value, rte_average=$num_average WHERE rte_userid=$userid";
		$result=mysql_query($query,$link) or die("$query".mysql_error());
	} else {
		$query="INSERT INTO ratings (rte_userid, rte_votes, rte_value, rte_average) VALUES ($userid,1,$vote,$vote)";
		$result=mysql_query($query,$link) or die("$query".mysql_error());
		$num_votes=1;
		$num_average=$vote/$num_votes;
	}
}
$query = "SELECT * FROM adverts WHERE adv_picture NOT LIKE '%generic%' $showme ORDER BY MD5(RAND(NOW())) LIMIT 1";
$result=mysql_query($query,$link) or die("$query".mysql_error());
$TOTAL=mysql_num_rows($result);
$sql_array=mysql_fetch_object($result);
if ($TOTAL < 1) {print("No records found"); exit;}
$start++;
?>
<?=$skin->ShowHeader($area)?>
<div align="left">
  <table border="1" cellpadding="0" cellspacing="0" width="630" bgcolor="#FFFFFF" style="border-collapse: collapse" bordercolor="#111111">
    <!--DWLayoutTable-->
    <tr align="center">
      <td height="20" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" width="244">
        <?php
	# if this is after the first rate
	if ($vote != NULL) {
               $lastpic=str_replace("members/", "thumbs/", $lastpic);
			     print("<table border='1' cellspacing='1' width='170' bgcolor='#CCCCCC'>
                 <tr>
                   <td width='100%' colspan='3' bgcolor='#CCCCCC' style='border-style: groove' align='center' valign='middle'><img src='$CONST_LINK_ROOT$lastpic' ></td>
                 </tr>
                 <tr>
                   <td width='33%' style='font-size: 9px' align='center' valign='middle'>Your vote:</td>
                   <td width='33%' style='font-size: 9px' align='center' valign='middle'>Overall:</td>
                   <td width='34%' style='font-size: 9px' align='center' valign='middle'>Votes</td>
                 </tr>
                 <tr>
                   <td width='33%' style='font-size: 10px' align='center' valign='middle'><b>$vote</b></td>
                   <td width='33%' style='font-size: 10px' align='center' valign='middle'><font color='red'><b>$num_average</b></font></td>
                   <td width='34%' style='font-size: 10px' align='center' valign='middle'>$num_votes</td>
                 </tr>
               </table>");
     }
?>
        <p> <a href="javascript:MDM_openWindow('<?php echo $CONST_LINK_ROOT ?>/prgtop10.php?sex=F','Top_Women','width=300,height=400')"><img src="<?php echo $CONST_IMAGE_ROOT ?>/ratewomen.gif" width="110" height="28" border="0"></a></p>
        <p><a href="javascript:MDM_openWindow('<?php echo $CONST_LINK_ROOT ?>/prgtop10.php?sex=M','Top_Women','width=300,height=400')"><img src="<?php echo $CONST_IMAGE_ROOT ?>/ratemen.gif" width="110" height="28" border="0"></a></p></td>
      <td valign="top" align="left" width="380"> <div align="center">
          <center>
            <table width="380" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td><table border="0" cellpadding="0" cellspacing="0" width='100%'>
                    <tr>
                      <td align='center' valign='middle'> <form method="POST" action="<?php echo $CONST_LINK_ROOT ?>/rating.php?source=voting&lstView=<?php print("$lstView"); ?>">
                          <table border="0" cellpadding="0" cellspacing="0" bgcolor="#5B7AAE" width='100%'>
                            <tr bordercolor="#333333" bgcolor="#cc66cc">
                              <td colspan="10" align='center' valign='middle' style='font-color: #FFFFFF'><strong>Rate
                                This Picture!</strong></td>
                            </tr>
                            <tr bgcolor="#CCCCCC">
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>
                                <input type='hidden' value="<?php print("$start"); ?>" name='start'>
                                <input type='hidden' value="<?php print("$sql_array->adv_userid"); ?>" name='userid'>
                                <input type='hidden' value="<?php print("$vote"); ?>" name='vote'>
                                <input type='hidden' value="<?php print("$sql_array->adv_picture"); ?>" name='lastpic'>
                                <input type="radio" value="1" name="vote"  onclick="this.form.submit()">
                              </td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>
                                <input type="radio" value="2" name="vote"  onclick="this.form.submit()"></td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>
                                <input type="radio" value="3" name="vote"  onClick="this.form.submit()"></td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'><input type="radio" value="4" name="vote"  onclick="this.form.submit()"></td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'><input type="radio" value="5" name="vote"  onclick="this.form.submit()"></td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'><input type="radio" value="6" name="vote"  onclick="this.form.submit()"></td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'><input type="radio" value="7" name="vote"  onclick="this.form.submit()"></td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'><input type="radio" value="8" name="vote"  onclick="this.form.submit()"></td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'><input type="radio" value="9" name="vote"  onClick="this.form.submit()"></td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'><input type="radio" value="10" name="vote" onClick="this.form.submit()"></td>
                            </tr>
                            <tr bgcolor="#cc66cc">
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>1</td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>2</td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>3</td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>4</td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>5</td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>6</td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>7</td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>8</td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>9</td>
                              <td width="10%" align='center' valign='middle' style='font-color: #FFFFFF'>10</td>
                            </tr>
                            <tr bgcolor="#FFFFFF">
                              <td height='35' colspan="10" align="center"></td>
                            </tr>
                          </table>
                        </form></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td align="center"><table width="250" border="1" align="center" cellspacing="1" bgcolor="#FFFFFF">
                    <tr>
                      <td colspan="2" align="center"><a  rel='lightbox' href="<?=$sql_array->adv_picture?>><img border='0' name='thePic' src='<?php print("$CONST_LINK_ROOT$sql_array->adv_picture"); ?>' width='200'></a></td>
                    </tr>
                    <tr>
                      <?php
		if (!isset($Sess_UserId)) {
			print("<td width='50%' align='center' valign='middle'><a href='$CONST_LINK_ROOT/prgretuser.php?userid=$sql_array->adv_userid'><img border=0 src='$CONST_IMAGE_ROOT/rte_profile.jpg'></a></td>");

		} else {
            print("<td width='50%' align='center' valign='middle'><a href='$CONST_LINK_ROOT/prgretuser.php?userid=$sql_array->adv_userid'><img border=0 src='$CONST_IMAGE_ROOT/rte_profile.jpg'></a></td>
                   <td width='50%' align='center' valign='middle'><a href='$CONST_LINK_ROOT/sendmail.php?userid=$sql_array->adv_userid&handle=$sql_array->adv_username'><img border=0 src='$CONST_IMAGE_ROOT/rte_message.jpg'></a></td>");
        }
?>
                    </tr>
                    </table>
                  <strong><font size="2" face="Arial, Helvetica, sans-serif">Click Image
                  to Enlarge</font></strong></td>
              </tr>
            </table>
          </center>
        </div></td>
    </tr>
    <tr>
      <td height='2' colspan="2" align="center"></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>