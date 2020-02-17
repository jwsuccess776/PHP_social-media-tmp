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
# Name:         prgvideodmin.php
#
# Description:  Adds and removes additional videos for members
#
# Version:      8.0
#
######################################################################
include('db_connect.php');
include('session_handler.inc');
include('error.php');
include_once "rating/stars.inc.php";

if (!$vid_id = formGet('vid_id'))
    error_page(SHOWVIDEO_TEXT1,GENERAL_USER_ERROR);

include_once __INCLUDE_CLASS_PATH."/class.Video.php";
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$video = new Video();
$video->initById($vid_id);
$frame_info = $video->getFrameInfo();
$area='video';
?>
<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="0" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?=SHOW_VIDEO.": ".$video->vid_title?></td>
  </tr>
  <tr>
    <td valign=top><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <tr>
          <td width="60%" valign=top style="padding-right:15px;">
          
     <div><script type="text/javascript" src="<?=CONST_LINK_ROOT?>/videos/swfobject.js"></script>
                  <div id="flashcontent"> <strong>You need to upgrade your Flash Player to version 9 or newer.</strong> </div>
                  <script type="text/javascript">

                               var so = new SWFObject("<?=CONST_LINK_ROOT?>/videos/flvPlayer.swf?imagePath=<?=CONST_LINK_ROOT?>/<?=$frame_info->Path?>&videoPath=<?=CONST_LINK_ROOT?>/get_video.php?video_id=<?=$video->vid_id?>&t=<?=time()?>&autoStart=false&volAudio=60&newWidth=480&newHeight=385&disableMiddleButton=false&playSounds=true", "sotester", "480", "385", "9", "#efefef");

                               so.addParam("allowFullScreen", "true");

                               so.write("flashcontent");

                           </script>
                
</div>
           <? if ($video->vid_status == 'converted'){?>
                  <br /><div><?show_rating($video->rating, true);?></div>
                  <? } ?>
            
            <?
                include $CONST_INCLUDE_ROOT."/comment/functions.php";
                show_comments('video', $video->vid_id);
                ?>
          </td>
          <td width="40%" valign=top><?  $sql_array = new Adverts($video->vid_userid);
                            $sql_array->SetImage('small');
                            ?>
            <div class="resulthead"> <a href='<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$sql_array->adv_userid?>'>
              <?=$sql_array->adv_username?>
              </a> </div>
            <div class="vidshow_resultbody">
              <table>
                <tr>
                  <td valign="top" class="resultimage"><a href='<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$sql_array->adv_userid?>'  class="imageframe"><img border='0' src='<?=$CONST_LINK_ROOT?><?=$sql_array->adv_picture->Path?>?<?=time()?>' width=<?=$sql_array->adv_picture->w?>></a></td>
                  <td rowspan="2" valign="top"><span class="resulttitle">
                    <?=ADDED.": "?>
                    </span>
                    <?=$video->getTimeShift()?>
                    <br />
                    <span class="resulttitle">
                    <?=VIEWS.": "?>
                    </span>
                    <?=$video->vid_views?>
                    <br />
                    <span class="resulttitle">
                    <?=TAGS.": "?>
                    </span>
                    <? foreach ($video->getTags('array') as $tag) { ?>
                    <a href="<?=$CONST_LINK_ROOT?>/video_list.php?tag_id=<?=$tag->id?>">
                    <?=$tag->tag?>
                    </a>&nbsp;
                    <? } ?></td>
                </tr>
                <tr>
                  <td valign="top" class="resultimage"><?=$sql_array->statustext?>
                    <br />
                    <?=$sql_array->online?></td>
                </tr>
              </table>
            </div>
            <div class="resulthead">
              <?=GENERAL_DESCRIPTION?>
            </div>
            <div class="vidshow_resultbody"> <?php echo wordwrap($video->vid_description, 15, " ", true)."&#8230;"; ?> </div>
            <div class="resulthead">
              <?=EMBED?>
            </div>
            <div class="vidshow_resultbody">
            <textarea rows=7 cols=50 >
             <script type="text/javascript" src="<?=CONST_LINK_ROOT?>/videos/swfobject.js"></script>
                 <div id="flashcontent">
                 <strong>You need to upgrade your Flash Player to version 9 or newer.</strong>
                    </div>

            <script type="text/javascript">
			   var so = new SWFObject("<?=CONST_LINK_ROOT?>/videos/flvPlayer.swf", "sotester", "480", "385", "9", "#efefef");
	
			   so.addParam("allowFullScreen", "true");
			   so.addParam("flashVars", "imagePath=<?=CONST_LINK_ROOT?>/<?=$frame_info->Path?>&videoPath=<?=CONST_LINK_ROOT?>/get_video.php?video_id=<?=$video->vid_id?>&t=<?=time()?>&autoStart=false&volAudio=60&newWidth=480&newHeight=385&disableMiddleButton=false&playSounds=true");
            so.write("flashcontent");
           </script>
            </textarea>
            </div>
            <div class="resulthead">
              <?=RELATED_VIDEOS?>
            </div>
            <div class="vidshow_resultbody">
              <table>
                <?
                  foreach ($video->getRelativeList() as $v) {
                  $frame_info = $v->getFrameInfo('small');
               ?>
                <tr>
                  <td valign="top" class="resultimage"><a href="<?=$CONST_LINK_ROOT?>/show_video.php?vid_id=<?=$v->vid_id?>" class="imageframe"><img border='0' src='<?=$CONST_LINK_ROOT?><?=$frame_info->Path?>?<?=time()?>' width=<?=$frame_info->w?>></a> </td>
                  <td  valign="top"><span class="resulttitle">
                    <?=ADVERTISE_TITLE.": "?>
                    </span>
                    <?=$v->vid_title?>
                    <br />
                    <?show_rating($v->rating);?>
                  </td>
                </tr>
                <? } ?>
              </table>
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
