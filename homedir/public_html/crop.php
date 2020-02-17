<?php
include_once "db_connect.php";
include('session_handler.inc');
include('error.php');
include('imagesizer.php');
include('message.php');

$file = formGet("thePic");
$area = formGet("area");

switch ($area){
    case "stories"  : $url = "$CONST_ADMIN_LINK_ROOT/adm_stories.php";break;
    default         : $url = "$CONST_LINK_ROOT/process.php";break;
}

# retrieve the template
$area = 'member';

//mysql_close($link);
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo PIC_CROP_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td class="tdhead"><?php echo PRGPICADMIN_TEXT2?></td>
  </tr>
  <tr>
    <td><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td align="center" valign="top">
<!--url's used in the movie-->
<!--text used in the movie-->
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="250" height="300" id="cropsave" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="cropsave.swf?file=<?=$file?>&url=<?=$url?>&cHeight=<?=CONST_THUMBS_SMALL_H?>&cWidth=<?=CONST_THUMBS_SMALL_W?>" />
<param name="quality" value="high" />
<param name="bgcolor" value="#ffffff" />
<embed src="cropsave.swf?file=<?=$file?>&url=<?=$url?>&cHeight=<?=CONST_THUMBS_SMALL_H?>&cWidth=<?=CONST_THUMBS_SMALL_W?>" quality="high" bgcolor="#ffffff" width="250" height="300" name="cropsave" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
    </td>
  </tr>
  <tr>
    <td class="tdfoot"><p>&nbsp;</p></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>