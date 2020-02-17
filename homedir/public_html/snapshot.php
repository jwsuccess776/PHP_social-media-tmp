<?php

include_once "db_connect.php";

include('session_handler.inc');

include('error.php');

include('imagesizer.php');

include('message.php');



$userid = formGet("userid");

$area = formGet("area");

$service = formGet("service");



$swf = "camera.swf";

$height=322;



switch ($service) {

    case 'profile' : {$url = 'prgpicadmin.php';break;}

    case 'flirt'   : {$url = "prgpicflirt.php";$notes = "Send picture message"; $height= 362; $swf = "camera_flirt.swf";break;}

    case 'gallery' : {$url = "gallery/manage_items.php";$notes = "Add snapshot to gallery"; break;}

    default        : {$url = 'prgpicadmin.php';break;}

}

# retrieve the template

$area = 'member';



// mysql_close($link);

?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader">Webcam photo</td>

  </tr>

  <tr>

    <td class="tdhead"><p><?=$notes?></p></td>

  </tr>

  <tr>

    <td align="center" valign="top">

<!--url's used in the movie-->

<!--text used in the movie-->

<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="360" height="<?=$height?>" id="camera" align="middle">

<param name="allowScriptAccess" value="sameDomain" />

<param name="movie" value="<?=$swf?>?rectMinW=<?=CONST_THUMBS_SMALL_W+20?>&rectMinH=<?=CONST_THUMBS_SMALL_H+20?>&rectMaxW=<?=(CONST_THUMBS_SMALL_W+20)*2?>&rectMaxH=<?=(CONST_THUMBS_SMALL_H+20)*2?>&url=<?=$url?>" />

<param name="quality" value="high" />

<param name="bgcolor" value="#ffffff" />

<embed src="<?=$swf?>?>?rectMinW=<?=CONST_THUMBS_SMALL_W+20?>&rectMinH=<?=CONST_THUMBS_SMALL_H+20?>&rectMaxW=<?=(CONST_THUMBS_SMALL_W+20)*2?>&rectMaxH=<?=(CONST_THUMBS_SMALL_H+20)*2?>&url=<?=$url?>" quality="high" bgcolor="#ffffff" width="360" height="<?=$height?>" name="camera" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />

</object>

    </td>

  </tr>

  <tr>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td><p><?=SNAPSHOT_HELP?></p></td>

  </tr>

  <tr>

    <td class="tdfoot"><p>&nbsp;</p></td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>