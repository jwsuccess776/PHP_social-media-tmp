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
# Name: 		chat.php
#
# Description:  Page containing the embedded chat applet ('Chat room')
#
# Version:		5.0
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include($CONST_NETWORK_INCLUDE_ROOT.'/functions.php');
# retrieve the template
$area = 'member';

$ONLINE = $db->get_var("SELECT COUNT(mem_timeout)
                            FROM members
                                INNER JOIN adverts ON (adv_userid=mem_userid)
                            WHERE
                              adv_approved=1
                              AND adv_paused='N'
                              AND unix_timestamp(mem_timeout) > unix_timestamp(NOW())-".ONLINE_TIMEOUT_PERIOD*60);

# generate a unique chat login ID
$query="SELECT mem_userid,mem_username FROM members WHERE mem_userid = '$Sess_UserId'";
$retval=mysql_query($query,$link) or die(mysql_error());
$sql_array = mysql_fetch_object($retval);
srand((double)microtime()*1000000);
$tempPass=rand(1000,9999);
$tempPass=$tempPass * 9;
$tempName=substr($sql_array->mem_username,0,10);
$chatname=$sql_array->mem_username.$tempPass;
$chatname=trim($chatname);
mysql_close($link);
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
    </tr>
  <tr>
    <td class="pageheader"><?=CHAT_SECTION_NAME;?></td>
    </tr>
    <tr><td><applet
   codebase="http://client1.sigmachat.com/current/"
   code="Client.class" archive="scclient_en.zip"
   width=550 height=400 MAYSCRIPT>
   <param name="username" value="<?php print("$sql_array->mem_username"); ?>">
   <param name="password" value="<?php print("$sql_array->mem_password"); ?>">
   <param name="autologin" value="yes">
   <param name="room" value="135593">
   <param name="cabbase" value="scclient_en.cab">
</applet>
      </td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>
