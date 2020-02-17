<?php

include('../db_connect.php');

include('../session_handler.inc');

include ('../functions.php');

include('../error.php');

include_once __INCLUDE_CLASS_PATH."/class.Banner.php";

include("../FCKeditor/fckeditor.php");

include('permission.php');

include_once('../validation_functions.php');



$db =& db::getInstance();



$id = formGet('id');



switch ($_REQUEST['act']) {

    case 'save':

        $banner = new Banner($id);
        
        $formate=sanitizeData($_REQUEST['format'], 'xss_clean'); 
        $code=sanitizeData($_REQUEST['code'], 'xss_clean'); 
        $hits=sanitizeData($_REQUEST['hits'], 'xss_clean'); 
        $active=sanitizeData($_REQUEST['active'], 'xss_clean'); 
        $lable=sanitizeData($_REQUEST['lable'], 'xss_clean');

        if ($banner->save($formate, $lable, $code, $hits, $active)) {

            redirect($CONST_LINK_ROOT.'/admin/adm_banners.php');

            break;

        } else error_page(join("<br>",$banner->error),GENERAL_USER_ERROR);

    default:

        $format = formGet('format');

$bn = new Banner;


        if ($id) $banner = $bn->getByID($id);

        $bannerSizeOptions = $bn->getSizeOptions();



}



# retrieve the template

$area = 'member';



$sql_result = mysqli_query($globalMysqlConn,"SELECT * FROM stories");



?><?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

    <td class="pageheader">

      <?= $id ? SD_ADM_BANNER_EDIT_SECTION_NAME : SD_ADM_BANNER_ADD_SECTION_NAME ?>

    </td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_banners_edit.php">

          <input type="hidden" name="act" value="save">

          <input type="hidden" name="id" value="<?=$id?>">

          <tr>

            <td align="center">



       <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">



                <tr>

                  <td colspan="2" class="tdhead">&nbsp;</td>

                </tr>

                <tr class="teven">

                  <td>

                    <?=SD_ADM_BANNER_FORMAT?>

                  </td>

                  <td> <select name="format">

                    <?php

                    foreach ($bannerSizeOptions as $k=>$v)

                        echo "<option value=\"$k\"".(($banner->size == $k) ? ' selected' : '').">".$v;

                    ?>

                    </select></td>

                </tr>

                <tr class="tdodd">

                  <td>

                    <?=SD_ADM_BANNER_TITLE?>

                  </td>

                  <td> <input name="lable" type="text" class="input" value="<?=htmlspecialchars($banner->label)?>"></td>

                </tr>

                <tr class="tdeven">

                  <td>

                    <?=SD_ADM_BANNER_CODE?>

                  </td>

                  <td>

            <?               
            // $fck = createFCKEditor( 'additional_images', 'code', $banner->code , 'Basic', null, 390); //html_entity_decode(stripslashes($news->body))

				//$fck->Create() ;

            ?>

            <textarea name="code" id="editor_adm_banner_edit"><?php echo $banner->code; ?></textarea>

                  </td>

                </tr>

                <tr class="tdodd">

                  <td>

                    <?=SD_ADM_BANNER_HITS?>

                  </td>

                  <td> <input name="hits" type="text" class="input" value="<?=htmlspecialchars($banner->hits)?>"></td>

                </tr>

                <tr class="tdodd">

                  <td>

                    <?=SD_ADM_BANNER_ACTIVE?>

                  </td>

                  <td> <input name="active" type="checkbox"<?= $banner->is_active ? ' checked' : ''?>></td>

                </tr>

                <tr class="tdodd">

                  <td>

                  </td>

                </tr>

                <tr>

                  <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class="button" value="<?=GENERAL_SAVE?>">

                    <input name="button" type="button" class="button" onclick="window.location = '<?=$CONST_LINK_ROOT?>/admin/adm_banners.php'" value="<?=GENERAL_CANCEL?>">

                  </td>

                </tr>

              </table></td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>
<script>
    window.onload = function() {
        CKEDITOR.replace( 'editor_adm_banner_edit' );
    };
</script>
<?=$skin->ShowFooter($area)?>

