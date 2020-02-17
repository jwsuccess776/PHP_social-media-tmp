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
# Name:                 home.php
#
# Description:  Member side help page ('Help')
#
# Version:                7.3
#
######################################################################
include('db_connect.php');
include('session_handler.inc');
include($CONST_NETWORK_INCLUDE_ROOT.'/functions.php');
require_once __INCLUDE_CLASS_PATH."/class.Group.php";
require_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

$me = new Adverts($Sess_UserId);
$me->setImage('small');

# retrieve the' template
$area = 'member';

$ONLINE = $db->get_var("SELECT COUNT(mem_timeout)
                            FROM members
                                INNER JOIN adverts ON (adv_userid=mem_userid)
                            WHERE
                              adv_approved=1
                              AND adv_paused='N'
                              AND unix_timestamp(mem_timeout) > unix_timestamp(NOW())-".ONLINE_TIMEOUT_PERIOD*60);

# Email Notification
$MSGTOTAL = $db->get_var("SELECT COUNT(msg_receiverid) FROM messages WHERE (msg_receiverid='$Sess_UserId') AND msg_receiverdel='N' AND msg_read='U'");

# get member handle for welcome message
$handle = $_SESSION['Sess_UserName'];

# check users member status to display expire date
if ($Sess_Userlevel!="silver") {
        $exp_date = $db->get_var("SELECT unix_timestamp(mem_expiredate) FROM members WHERE mem_userid = '$Sess_UserId'");
        $retStatus= "".GENERAL_WELCOME_STATUS_GOLD." ".date($CONST_FORMAT_DATE_SHORT,$exp_date);
} else {
        if ($CONST_FREE != true) {
			$retStatus= "".GENERAL_WELCOME_STATUS_SILVER." <a href='$CONST_LINK_ROOT/get_premium.php'>".HOME_UPGRADE."</a>";
		} else {
			$retStatus= str_replace("-","",GENERAL_WELCOME_STATUS_SILVER);
		}
}

$groups = Group::findByMember($Sess_UserId, 5);

$emails = $db->get_results(" SELECT *, adv_approved 
						    FROM messages 
								LEFT JOIN adverts ON (msg_senderid=adv_userid) 
							WHERE msg_receiverid='$Sess_UserId' 
								AND msg_receiverdel='N' 
							ORDER BY msg_dateadded DESC
							LIMIT 4");

$encounters = $db->get_results("SELECT *
                                FROM encounters 
                                		INNER JOIN adverts ON (enc_viewerid=adv_userid) 
                                WHERE enc_userid='$Sess_UserId' AND enc_viewdate >= DATE_SUB(CURDATE(),INTERVAL 30 DAY)
                                ORDER BY enc_viewdate DESC LIMIT 3");

$favorites = $db->get_results(" SELECT * 
								FROM hotlist 
									INNER JOIN adverts ON (hot_advid = adv_userid) 
								WHERE (hot_userid=$Sess_UserId) 
								ORDER BY hot_dateadded
								LIMIT 3
								");

include_once __INCLUDE_CLASS_PATH."/class.Network.php";
$network = new Network();
$n_list = $network->getNetwork($Sess_UserId,1);
$net = array();

for ($i=0;$i<3;$i++) {
	if (isset($n_list[$i])) $net[] = $n_list[$i];
}

if ($me->adv_paused == 'Y') {
	$adstatus=MY_AD_STATUS_PAUSED;
} elseif  ($me->adv_paused == 'N') {
	$adstatus=MY_AD_STATUS_VISIBLE;
} else {
	$adstatus=MY_AD_STATUS_NONE;
}
switch ($me->adv_approved) {
	case 0:
		$approved=STATUS_PENDING;
		break;
	case 1:
		$approved=STATUS_APPROVED;
		break;
	case 2:
		$approved=STATUS_REJECTED;
		break;
}

?>
<?=$skin->ShowHeader($area)?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap="nowrap" class="home_welcome"><?= GENERAL_WELCOME?>
      <?php print("$handle"); ?></td>
    <td align="right" nowrap="nowrap" ><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?></td>
  </tr>
  <tr>
    <td colspan="2" class="home_status"><?php print("$retStatus"); ?></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" valign="top"><div class="home_box" > <a href="<?php echo $CONST_LINK_ROOT?>/get_premium.php" class="home_link"> <img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_21.gif" border="0" />
        <?= HOME_UPGRADE?>
        </a>
        <h2><img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_09.gif" />&nbsp;<a href='<?php echo $CONST_LINK_ROOT ?>/myemail.php'>
          <?=$MSGTOTAL?>
          </a><?= HOME_UNREAD?></h2>
        <table width="100%" cellpadding="10">
          <tr>
            <td valign="top"><table border="0" cellspacing="0" cellpadding="0">
				<?
					foreach ($emails as $row) {
		                $subject=stripslashes(substr($row->msg_title,0,30));
						$more = (strlen($row->msg_title)>30) ? '...' : '';
				?>
                <tr>
                  <td class="home_row"><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$row->msg_senderid?>"><?=$row->msg_senderhandle?></a> - <a href="<?=$CONST_LINK_ROOT?>/prgshowmail.php?mailid=<?=$row->msg_id?>&showmode=received"><?=$subject?><?=$more?></a></td>
                </tr>
				<?}?>
                <tr>
                  <td class="home_row"><a href="<?php echo $CONST_LINK_ROOT?>/myemail.php"><?php echo SHOW_MAIL_SECTION_NAME ?>...</a></td>
                </tr>
              </table></td>
            <td align="right" valign="top"><table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left" class="home_row"><img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_22.gif" border="0" align="absmiddle" />&nbsp;<a href="<?php echo $CONST_LINK_ROOT?>/eventscalendar.php"><?= HOME_CALENDAR?>
                    </a></td>
                  <td align="left" class="home_row"><img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_05.gif" border="0" align="absmiddle" />&nbsp;<a href="<?php echo $CONST_LINK_ROOT?>/myinfo.php"><?= HOME_SETTINGS?>
                    </a></td>
                </tr>
                <tr>
                  <td align="left" class="home_row"><img src="<?php echo $CONST_IMAGE_ROOT?>icons/whos_online.gif" alt="" align="absmiddle" />&nbsp;<a href="whoson.php"><?=$ONLINE ?>&nbsp;<?php echo SEARCH_ONLINE ?></a></td>
                  <td align="left" class="home_row"><img src="<?php echo $CONST_IMAGE_ROOT?>icons/interested_me.gif" border="0" align="absmiddle" />&nbsp;<a href="<?php echo $CONST_LINK_ROOT?>/interested_in_me.php"><?= HOME_INTERESTED_IN_ME?>
                    </a></td>
                </tr>
                <tr>
                  <td align="left" class="home_row"><img src="<?php echo $CONST_IMAGE_ROOT?>icons/my_match.gif" border="0" align="absmiddle" />&nbsp;<a href="<?php echo $CONST_LINK_ROOT?>/my_matches.php"><?= HOME_MYMATCH?>
                    </a></td>
                  <td align="left" class="home_row"><img src="<?php echo $CONST_IMAGE_ROOT?>icons/my_interests.gif" border="0" align="absmiddle" />&nbsp;<a href="<?php echo $CONST_LINK_ROOT?>/my_interests.php"><?= HOME_MY_INTERESTS?>
                    </a></td>
                </tr>
                <tr>
                  <td align="left" class="home_row">
				  <?php if ($option_manager->getValue('groups')) { ?>
				  <img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_03.gif" border="0" align="absmiddle" />&nbsp;<a href="<?php echo $CONST_GROUPS_LINK_ROOT?>/my_groups.php"><?= HOME_GROUPS?></a>
				   <?php } else {?>
                    &nbsp;
                    <?php } ?></td>
                  <td align="left" class="home_row"><?php if ($option_manager->getValue('blogs')) { ?>
                    <img src="<?php echo $CONST_IMAGE_ROOT?>icons/blog.gif" border="0" align="absmiddle" />&nbsp;<a href="<?php echo $CONST_BLOG_LINK_ROOT?>/myblogs.php"><?= MYADDBLOGS_SECTION_NAME?>
                    </a>
                    <?php } else {?>
                    &nbsp;
                    <?php } ?></td>
                </tr>
                <tr>
                  <td align="left" class="home_row">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                </tr>
              </table></td>
          </tr>
        </table>
      </div></td>
    <td width="33%" valign="top" ><div class="home_box" > <a href="<?php echo $CONST_LINK_ROOT?>/view_profile.php" class="home_link"><?php echo VIEW_PROFILE ?></a>
        <h2><img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_30.gif" />&nbsp;<?php echo $MENU_PROFILE ?></h2>
        <table width="100%" border="0" cellpadding="10">
          <tr>
            <td valign="top" class="home_profile_img"><img src="<?=$CONST_LINK_ROOT?><?=$me->adv_picture->Path?>?<?=time()?>" width="<?=$me->adv_picture->w?>" alt="" border="0"></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="home_row"><img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_10.gif" align="absmiddle" /> <a href="<?php echo $CONST_LINK_ROOT?>/prgamendad.php">
                    <?= HOME_PROFILE?>
                    </a></td>
                </tr>
                <tr>
                  <td class="home_row"><img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_14.gif" align="absbottom" /> <a href="<?="$CONST_LINK_ROOT/prgpicadmin.php?mode=show"?>">
                    <?= HOME_VIDEO?>
                    </a></td>
                </tr>
                <tr>
                  <td class="home_row"><img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_30.gif" /> <a href="<?=$CONST_LINK_ROOT?>/myinfo.php">[<?=$adstatus?>]&nbsp;[<?=$approved?>]</a> </td>
                </tr>
              </table></td>
          </tr>
        </table>
        <div class="home_profile_desc"><?=wordwrap($me->adv_comment, 15, " ", true)."&#8230;"; ?></div>
      </div></td>
  </tr>
  <tr>
    <td width="33%" valign="top"><div class="home_box" >
        <h2><img src="<?php echo $CONST_IMAGE_ROOT?>icons/icon_new.gif" /> <?=VIEWED_MY_PROFILE?></h2>
        <div class="home_box_lower">
          <table class="home_inner_profile">
            <tr>
<? foreach ($encounters as $row ){
			$enc = new Adverts($row->adv_userid);
			$enc->setImage('small');
?>
              <td valign="top"><div><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$enc->mem_userid?>"><img src="<?=$CONST_LINK_ROOT?><?=$enc->adv_picture->Path?>?<?=time()?>" width="<?=$enc->adv_picture->w?>" alt="" border="0"></a></div>
                <div><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$enc->mem_userid?>"> <?=$enc->mem_username?>,
                  <?=$enc->age?> <br>
		          <?=$enc->gcn_name?>
				</a></div></td>
<?}?>
            </tr>
          </table>
        </div>
        <a href="javascript:MDM_openWindow('<?php echo $CONST_LINK_ROOT?>/prgencounters.php','Encounters','scrollbars=yes, width=560,height=550')" class="home_more"><?=HOME_MORE?></a> </div></td>
    <td width="33%" valign="top"><div class="home_box" >
        <h2><img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_03.gif" /> <?=HOME_FRIENDS?></h2>
        <div class="home_box_lower">
          <table class="home_inner_profile">
            <tr>
<? foreach ($favorites as $row ){
			$fav = new Adverts($row->adv_userid);
			$fav->setImage('small');
?>
              <td valign="top"><div><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$fav->mem_userid?>"><img src="<?=$CONST_LINK_ROOT?><?=$fav->adv_picture->Path?>?<?=time()?>" width="<?=$fav->adv_picture->w?>" alt="" border="0"></a></div>
                <div><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$fav->mem_userid?>"> <?=$fav->mem_username?>,
                  <?=$fav->age?> <br>
		          <?=$fav->gcn_name?>
				</a></div></td>
<?}?>
            </tr>
          </table>
        </div>
        <a href="<?php echo $CONST_LINK_ROOT?>/prghotlist.php" class="home_more"><?=HOME_MORE?></a> </div></td>
    <td width="33%" valign="top"><div class="home_box" > <a class="home_link" href="<?php echo $CONST_LINK_ROOT?>/invitefriend.php">
        <?= HOME_INVITE_FRIENDS?>
        </a>
        <h2><img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_33.gif" /> <?=SOCIAL_NETWORK_SECTION_NAME?></h2>
        <div class="home_box_lower">
          <table class="home_inner_profile">
            <tr>
<? foreach ($net as $id){
			$friend = new Adverts($id);
			$friend->setImage('small');
?>
              <td valign="top"><div><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$friend->mem_userid?>"><img src="<?=$CONST_LINK_ROOT?><?=$friend->adv_picture->Path?>?<?=time()?>" width="<?=$friend->adv_picture->w?>" alt="" border="0"></a></div>
                <div><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$friend->mem_userid?>"> <?=$friend->mem_username?>,
                  <?=$friend->age?> <br>
		          <?=$friend->gcn_name?>
				</a></div></td>
<?}?>
            </tr>
          </table>
        </div>
        <a href="<?=CONST_NETWORK_LINK_ROOT?>/network.php?level=1&user_id=<?=$me->mem_userid?>" class="home_more"><?=HOME_MORE?></a></div></td>
  </tr>
  <tr>
    <td colspan="3" valign="top"><div class="home_box_request" >
        <h2><img src="<?php echo $CONST_IMAGE_ROOT?>icons/icons_33.gif" />&nbsp;<?=SOCIAL_NETWORK_REQUEST_APPROVE?></h2>
        <table width="100%" cellpadding="10">
          <tr>
            <td valign="top"><? include_once "$CONST_NETWORK_INCLUDE_ROOT/requests.inc.php" ?></td>
          </tr>
        </table>
      </div></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
