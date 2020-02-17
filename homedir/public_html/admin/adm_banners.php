<?php

include('../db_connect.php');

include('../session_handler.inc');

include ('../functions.php');

include('../error.php');

include_once __INCLUDE_CLASS_PATH."/class.Banner.php";

include('permission.php');

include_once('../validation_functions.php');



switch ($_GET['act']) {

    case 'remove':
        $mn = new Main;
        $id=sanitizeData($_REQUEST['id'], 'xss_clean'); 
        $db->query("DELETE FROM bannercodes WHERE banner_id = '".$mn->_PrepareData($id)."'");

    default:

        $format =sanitizeData($_REQUEST['format'], 'xss_clean'); 
        $bn = new Banner;
        $banners = $bn->getList($format);

}

# retrieve the template

$area = 'member';



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?php echo SD_ADM_BANNERS_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td>



       <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">



    <tr>

      <td colspan="5" align="center" class="tdhead">

        <input type="button" class='button' onclick="document.location = '<?=$CONST_LINK_ROOT?>/admin/adm_banners_edit.php'" value="<?=SD_ADM_BANNERS_ADD?>">

      </td>

    </tr>

    <tr class="tdtoprow">

      <td><?=SD_ADM_BANNER_TITLE?></td>

      <td><?=SD_ADM_BANNER_SIZE?></td>

      <td><?=SD_ADM_BANNER_STATUS?></td>

      <td><?=GENERAL_EDIT?></td>

      <td><?=GENERAL_DELETE?></td>

    </tr>

<?php

if (is_array($banners))

    foreach ($banners as $banner) {

?>

    <tr align=left class="tdodd">

      <td><a href="<?=$CONST_LINK_ROOT?>/admin/adm_banners_edit.php?id=<?=$banner->banner_id?>">

        <?=htmlspecialchars($banner->bannerName)?>

        </a></td>



      <td><?=$banner->bannerFormat?></td>

      <td><?= $banner->is_active ? ADM_BANNERS_ACTIVE : ADM_BANNERS_PAUSED ?></td>



      <td><a href="<?=$CONST_LINK_ROOT?>/admin/adm_banners_edit.php?id=<?=$banner->banner_id?>">[

        <?=GENERAL_EDIT?>

        ]</a></td>

      <td><a href="<?=$CONST_LINK_ROOT?>/admin/adm_banners.php?act=remove&id=<?=$banner->banner_id?>" onClick="if (confirm('<?=SD_ADM_BANNERS_TEXT1?>')) {return true;} else {return false;}" >[

        <?=GENERAL_DELETE?>

        ]</a></td>

        </tr>

<?php } ?>

    <tr>

      <td colspan="5" align="center" class="tdfoot">&nbsp; </td>

    </tr>

  </table>

<?php $bns = new Banner;
 $bns->displayBanner('468x60');?>

      </td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>

