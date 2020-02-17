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

# Name:                 admin.php

#

# Description:  Administrators menu screen

#

# Version:                7.3

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

require_once(__INCLUDE_CLASS_PATH.'/class.Group.php');

include_once __INCLUDE_CLASS_PATH."/class.Gallery.php";

include_once __INCLUDE_CLASS_PATH."/class.Video.php";

include('permission.php');



$area = 'member';



//Time operations

$curr_time = time();

$mod_time = $curr_time - BLOCK_PERIOD_AVAILABLE;

$modified_time = date("Y-m-d H:i:s", $mod_time);

//EOF Time operations



$totalpic = $db->get_var("SELECT COUNT(pic_userid) FROM pictures WHERE pic_approved=0");

$totalpenblog = $db->get_var("SELECT COUNT(blg_approved) FROM blogs WHERE blg_approved = 'N'");

$totalpenforum = $db->get_var("SELECT COUNT(post_approved) FROM bb_posts WHERE post_approved = '0'");

$totalpen = $db->get_var("SELECT COUNT(adv_approved) FROM adverts LEFT JOIN members ON (adv_userid=mem_userid) WHERE adv_approved = 0 AND mem_confirm = 1");

$totalconf = $db->get_var("SELECT COUNT(adv_approved) FROM adverts LEFT JOIN members ON (adv_userid=mem_userid) WHERE mem_confirm = 0");

$totalevents = $db->get_var("SELECT COUNT(ev_eventid) FROM events WHERE ev_approved=0");

$gallery= new Gallery();

$totalpengallery = $gallery->CountPendingItem();



$query="SELECT COUNT(review_approved) FROM reviews WHERE review_approved=0";

$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$totalreviewapproved=mysqli_fetch_assoc($retval);



$group = new Group();
$newGroupsNum = $group->count('status = 0');



$video = new Video();

$new_video = $video->getListByStatus('new','count');



?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo ADM_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr><td>



<table width="100%"  border="0" cellspacing="0" cellpadding="0">



        <tr>

          <td align="center" ></td>

        </tr>



        <tr>

          <td align="left" ></td>

        </tr>

        <tr valign="top">

          <td align="left" ><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

              <tr>

                <td class="tdhead"><?php echo ADM_REPORTS ?></td>

                <td class="tdhead"><?php echo ADM_SETTINGS ?></td>

                <td class="tdhead"><?php echo ADM_TOOLS ?></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/documentation/Vplus Software Admin Guide v2.0.pdf" target="_blank"><?php echo ADM_ADMIN_GUIDE_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/params.php"><?php echo PARAMS_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/prgauthads.php?mode=start"><?php echo APPROVE_ADS_SECTION_NAME ?> <?php if ($totalpen > 0) print("($totalpen)"); ?></a></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/cupid.php" ><?php echo ADM_CUPID_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_picturegallery.php"><?php echo ADM_STANDART_PICTURE_GALLERY_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/prgauthpics.php?mode=start"><?=DB_OPTION_AUTHORISEPIC_LABEL?>

                    <?php if ($totalpic > 0) print("($totalpic)"); ?>

                </a></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/checkmembers.php"><?php echo CHECK_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/language.php"><?php echo LANGUAGE_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/prgauthblogs.php?mode=start"><?php echo ADM_BLOGS_APPROVE_SECTION_NAME ?>

                <?php if ($totalpenblog > 0) print("($totalpenblog)"); ?>

                </a></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/checkads.php?option=first&x=0"><?php echo BROWSE_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/premium_functions.php"><?php echo PREMIUM_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_bbforums.php?mode=start"><?php echo BB_FORUMS_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/prgstats.php"><?php echo STATS_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/listbox.php?mode=view"><?php echo LISTOPTIONS_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/prgauthforum.php?mode=start"><?php echo ADM_FORUM_APPROVE_SECTION_NAME ?>

                <?php if ($totalpenforum > 0) print("($totalpenforum)"); ?>

                </a></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/report_payments.php"><?php echo PAYMENTS_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_paysystems.php"><?php echo ADM_PAYMENTS_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adminmail.php"><?php echo SENDMAIL_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/unconfirmed.php"><?php echo UNCONFIRMED_SECTION_NAME ?> <?php if ($totalconf > 0) print("($totalconf)"); ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_services.php"><?php echo ADM_SERVICE_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/prgauthgallery.php"><?php echo APPROVE_GALLERY?><?php if ($totalpengallery > 0) print("($totalpengallery)"); ?></a></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/inactive_members.php"><?php echo INACTIVE_MEMBERS_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_geography.php"><?php echo GEOGRAPHY_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/removeme.php"><?php echo REMOVE_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?=$CONST_LINK_ROOT?>/admin/report_banners.php"><?php echo ADM_BANNERS_REPORT_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_mailtemplates.php"><?php echo ADM_MAILTEMPLATES_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_export.php"><?php echo ADM_EXPORT_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?=$CONST_LINK_ROOT?>/admin/suspended.php"><?php echo ADM_SUSPENDED_REPORT_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_pagetemplates.php"><?php echo ADM_PAGETEMPLATES_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_stories.php"><?php echo STORIES_SECTION_NAME ?></a></td>

              </tr>



              <tr>

                <td class="tdeven"><a href="https://www.idatemedia.com/get-support/" target="_blank"><?php echo ADM_RAISE_TICKET ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_spamwords.php"><?php echo ADM_SPAMWORDS_SECTION_NAME ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_news.php"><?php echo NEWS_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_sms.php"><?php echo ADM_MANAGE_SMS ?></a></td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_flirts.php"><?php echo ADM_FLIRTS_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_groups.php"><?php echo ADM_GROUPS_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_groups_approve.php"><?php echo ADM_GROUPS_APPROVE_SECTION_NAME. ($newGroupsNum ? " ($newGroupsNum)" : '') ?></a></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_banners.php"><?php echo ADM_BANNERS_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven">&nbsp;</td>

                <td  class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/prgmembers.php"><?php echo ADM_MEMBER_ADMIN_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven">&nbsp;</td>

                <td  class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_db_optimize.php"><?php echo DB_OPTIMIZE_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_picturegallery.php"></a></td>

                <td  class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/email_dl.php"><?php echo EMAIL_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven">&nbsp;</td>

                <td  class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_mail_queue.php"><?=ADM_MAIL_QUEUE_SECTION_NAME?></a></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven">&nbsp;</td>

                <td  class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_process_queue.php"><?php echo ADM_PROCESS_MAIL ?></a></td>

              </tr>

<?if ($option_manager->getValue('video_conversion')) {?>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven">&nbsp;</td>

                <td  class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/prgauthvideo.php"><?php echo ADM_CONVERT_VIDEO ?></a> <?if ($new_video){?>(<?=$new_video?>)<?}?></td>

              </tr>

<?}?>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven">&nbsp;</td>

                <td  class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_video_list.php"><?php echo ADM_MANAGE_VIDEO ?></a></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven">&nbsp;</td>

                <td  class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/adm_db_maintenance.php"><?php echo DB_MAINTENANCE_SECTION_NAME ?></a></td>

              </tr>

              <tr>

                <td class="tdfoot">&nbsp;</td>

                <td class="tdfoot">&nbsp;</td>

                <td  class="tdfoot">&nbsp;</td>

              </tr>

              <tr>

                <td class="tdhead"><?php echo ADM_EVENTS ?></td>

                <td class="tdhead"><?php echo ADM_AFFILIATES ?></td>

                <td class="tdhead"><?php echo ADM_SPEEDDATING ?>&nbsp;</td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/addevent.php"><?php echo ADDEVENTS_SECTION_NAME ?></a></td>

                <td class="tdeven"><?php if(file_exists($CONST_INCLUDE_ROOT."/affiliates/index.php")) print("<a href='".$CONST_LINK_ROOT."/affiliates/aff_authorise.php?mode=show'>".AFF_AUTHORISE_SUBJECT_APPROVAL."</a>"); else print(GENERAL_NO_MODULE); ?></td>

                <td class="tdeven"><?php if(file_exists($CONST_INCLUDE_ROOT."/speeddating/adm_events.php")) print("<a href='".$CONST_LINK_ROOT."/speeddating/adm_events.php'>".ADM_MANAGE_EVENTS."</a>"); else print(GENERAL_NO_MODULE); ?></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/approvereview.php"><?php echo ADM_APPROVE_REVIEWS ?>&nbsp;<?php if ($totalreviewapproved > 0) print("($totalreviewapproved)"); ?></a></td>

                <td class="tdeven"><?php if(file_exists($CONST_INCLUDE_ROOT."/affiliates/index.php")) print("<a href='".$CONST_LINK_ROOT."/affiliates/frn_payments.php'>".PRN_PAYMENTS_TITLE."</a>"); ?></td>

                <td class="tdeven"><?php if(file_exists($CONST_INCLUDE_ROOT."/speeddating/adm_prgvenues.php")) print("<a href='".$CONST_LINK_ROOT."/speeddating/adm_prgvenues.php'>".ADM_MANAGE_VENUES."</a>");  ?></td>

              </tr>

              <tr>

                <td class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/admin/approveevent.php"><?=APPROVEEVENTS_SECTION_NAME?>&nbsp;<?php if ($totalevents > 0) print("($totalevents)"); ?></a></td>

                <td class="tdeven"><?php if(file_exists($CONST_INCLUDE_ROOT."/affiliates/index.php")) print("<a href='".$CONST_LINK_ROOT."/affiliates/aff_banlist.php'>".AFF_BANNERS_SECTION_NAME."</a>"); ?></td>

                <td class="tdeven"><?php if(file_exists($CONST_INCLUDE_ROOT."/speeddating/adm_prgvenues.php")) print("<a href='".$CONST_LINK_ROOT."/speeddating/adm_stories.php'>".SD_ADM_STORIES_SECTION_NAME."</a>");?></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven"><?php if(file_exists($CONST_INCLUDE_ROOT."/affiliates/index.php")) print("<a href='".$CONST_LINK_ROOT."/affiliates/adm_affiliates.php'>".ADM_AFFILIATES_ADMINISTRATION."</a>"); ?></td>

                <td class="tdeven"><?php if(file_exists($CONST_INCLUDE_ROOT."/speeddating/adm_report_event.php")) print("<a href='".$CONST_LINK_ROOT."/speeddating/adm_report_event.php'>".ADM_REPORTS."</a>");  ?></td>

              </tr>

              <tr>

                <td class="tdeven">&nbsp;</td>

                <td class="tdeven"><?php if(file_exists($CONST_INCLUDE_ROOT."/affiliates/index.php")) print("<a href='".$CONST_LINK_ROOT."/affiliates/aff_statistics.php'>".ADM_AFFILIATES_PERFORMANCE."</a>"); ?></td>

                <td class="tdeven"><?php if(file_exists($CONST_INCLUDE_ROOT."/speeddating/adm_waiting_list.php")) print("<a href='".$CONST_LINK_ROOT."/speeddating/adm_waiting_list.php'>".ADM_WAITING_LIST."</a>");  ?></td>

              </tr>

              <tr>

                <td class="tdeven"></td>

                <td class="tdeven"></td>

                <td class="tdeven">

                </td>

              </tr>

              <tr>

                <td class="tdfoot">&nbsp;</td>

                <td  class="tdfoot">&nbsp;</td>

                <td  class="tdfoot">&nbsp;</td>

              </tr>

            </table>

          </td>

        </tr>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>