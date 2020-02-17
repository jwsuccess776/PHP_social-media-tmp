<?php
include_once "db_connect.php";
include('session_handler.inc');
include('error.php');
include('imagesizer.php');
include('message.php');
include_once __INCLUDE_CLASS_PATH."/class.CropCanvas.php";
include_once __INCLUDE_CLASS_PATH."/class.Picture.php";



# retrieve the template
$area = 'member';

//dump($_REQUEST);
//$img = new ScmsImage();
$img = new ScmsImage(true);//<--DEBUGMODE
    $root_path=$CONST_INCLUDE_ROOT;
if (isset($_POST["mode"])) {
   
    $fromfile=$CONST_INCLUDE_ROOT.$_POST["crop_file"];
    $fromorigfile=$CONST_INCLUDE_ROOT.$_POST["orig_file"];

    $approved=$option_manager->GetValue('authorisepic');
    $picture = new Picture();
    $data = array(
        "pic_userid"    => $Sess_UserId,
        "pic_private"   => 'N',
        "pic_default"   => 'Y',
        "pic_approved"   => $approved,
        "filepath"      => $fromfile,
                );
    $result = $picture->InitForSave($data);
    if ($result === null) {
        error_page(join("<br>",$picture->error),GENERAL_USER_ERROR);
    }
    $pic_id=$picture->Save();

	list($width, $height, $type, $attr) = getimagesize($fromfile); 

	$ext=substr($fromfile, -4, 4);
	$from=$CONST_INCLUDE_ROOT."/members/".$pic_id."_large".$ext;
	$to=$CONST_INCLUDE_ROOT."/members/".$pic_id.$ext;
	
	if ($height > 600) {
		copy ($from, $to);
	}

   	unlink("$from");
    unlink("$fromfile");
    if (!preg_match('/avatar/',$fromorigfile)) unlink($fromorigfile);
    include("generate_profile.php");
    header("Location: prgpicadmin.php");
    exit;

}
$_scale = ($_REQUEST['scale'] == 0)?100:$_REQUEST['scale'];
ini_set('memory_limit', '150M');
$img->loadImage($root_path.$_REQUEST['file']);
//$img->redimToPercent ( $_REQUEST['scale'],$_REQUEST['scale'] );
$img->redimToPercent ( 100, 100 );
$img->applyChangesToOriginal();
//$img->cropToDimensions ($_REQUEST['x1'],$_REQUEST['y1'],$_REQUEST['x2'],$_REQUEST['y2']);
$x1 = (int)((100 / $_scale) * $_REQUEST['x1']);
$y1 = (int)((100 / $_scale) * $_REQUEST['y1']);
$x2 = (int)((100 / $_scale) * $_REQUEST['x2']);
$y2 = (int)((100 / $_scale) * $_REQUEST['y2']);
//echo '<br>process--'.$x1.'->>>'.$y1.'->>>'.$x2.'-->>>'.$y2;
//$img->cropToDimensions ($x1,$y1,$x2,$y2);
$img->cropByAuto();
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo PIC_SAVE_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td>
<?php
$_original = $_REQUEST['file'];
$_tempfilename = "/members/crop_".time().".jpg";
if ($img->saveImage($CONST_INCLUDE_ROOT.$_tempfilename)) {
?>
<script>
function CancelAndClose() {
    window.opener.location = "prgpicadmin.php";
    window.close();
}
</script>
<form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/process.php' name="FrmPicture">
<input type='hidden' name='crop_file' value='<?=$_tempfilename?>'>
<input type='hidden' name='orig_file' value='<?=$_original?>'>
<input type='hidden' name='mode' value='save'>
<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td colspan='2' align='left' class='tdhead'>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <?php 
	  		$filename=$CONST_LINK_ROOT."".$_tempfilename;
	  		list($width, $height, $type, $attr) = getimagesize($filename); 
			if ($width > 500) $width=500; 
	   ?>
	  <img src="<?php echo $CONST_LINK_ROOT.$_tempfilename ?>" name="thePic" border="0" width="<?=$width?>">
    </td>
  </tr>
  <tr>
    <td colspan='2' align='left' class='tdfoot'>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><input type="submit" name="Submit" value="<?php echo GENERAL_SAVE?>" class="button"></td>
    <td align="center"><input type="button" name="Cancel" value="<?php echo GENERAL_CANCEL?>" onClick="window.location ='prgpicadmin.php'" class="button"></td>
  </tr>
</table>
<?php } ?>
      <p>&nbsp;</p></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>