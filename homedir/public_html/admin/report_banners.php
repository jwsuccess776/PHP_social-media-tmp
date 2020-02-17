<?php

include('../db_connect.php');

include('../session_handler.inc');

include_once __INCLUDE_CLASS_PATH."/class.Banner.php";

include('permission.php');
include_once('../validation_functions.php');



if (isset($_REQUEST['lstYear']))

    $lstYear =sanitizeData($_REQUEST['lstYear'], 'xss_clean'); 

else $lstYear = date('Y');



if (isset($_REQUEST['lstMonth']))

    $lstMonth =sanitizeData($_REQUEST['lstMonth'], 'xss_clean'); 

else $lstMonth = date('m');

$banner = new Banner;

$data = $banner->report($lstYear, $lstMonth);



$area = 'member';



?><?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

    <td class="pageheader"><?=BANNERS_REPORTS_SECTION_NAME?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT ?>/admin/report_banners.php' name='FrmReports'>

          <tr>

            <td colspan="6" align='left' valign='top' class="tdhead">&nbsp;</td>

          </tr>

          <tr class="tdtoprow">

            <td align='left' valign='top'> <b> </b> </td>

            <td align='left' valign='top' class="tdodd"> <b>

              <?=REPORT_BANNERS_MONTH?>

              <select class="inputf" size="1" name="lstMonth">

                <option value="-Choose-"  <?php if ($lstMonth == "") { print("selected");} ?>>

                <?=REPORT_BANNERS_CHOOSE?>

                </option>

                <option value="01" <?php if ($lstMonth == "01") { print("selected");} ?>>

                <?=MONTH_JAN?>

                </option>

                <option value="02" <?php if ($lstMonth == "02") { print("selected");} ?>>

                <?=MONTH_FEB?>

                </option>

                <option value="03" <?php if ($lstMonth == "03") { print("selected");} ?>>

                <?=MONTH_MAR?>

                </option>

                <option value="04" <?php if ($lstMonth == "04") { print("selected");} ?>>

                <?=MONTH_APR?>

                </option>

                <option value="05" <?php if ($lstMonth == "05") { print("selected");} ?>>

                <?=MONTH_MAY?>

                </option>

                <option value="06" <?php if ($lstMonth == "06") { print("selected");} ?>>

                <?=MONTH_JUN?>

                </option>

                <option value="07" <?php if ($lstMonth == "07") { print("selected");} ?>>

                <?=MONTH_JUL?>

                </option>

                <option value="08" <?php if ($lstMonth == "08") { print("selected");} ?>>

                <?=MONTH_AUG?>

                </option>

                <option value="09" <?php if ($lstMonth == "09") { print("selected");} ?>>

                <?=MONTH_SEP?>

                </option>

                <option value="10" <?php if ($lstMonth == "10") { print("selected");} ?>>

                <?=MONTH_OCT?>

                </option>

                <option value="11" <?php if ($lstMonth == "11") { print("selected");} ?>>

                <?=MONTH_NOV?>

                </option>

                <option value="12" <?php if ($lstMonth == "12") { print("selected");} ?>>

                <?=MONTH_DEC?>

                </option>

              </select>

              </b> </td>

            <td align='left' valign='top' class="tdodd"> <b>

              <?=REPORT_BANNERS_YEAR?>

              </b> </td>

            <td align='left' valign='top' class="tdodd"> <b>

              <select class="inputf" size="1" name="lstYear">

                <option value="-Choose-"><?=REPORT_BANNERS_CHOOSE?></option>

                <?php

                    for ($i=date("Y"); $i >= 2004; $i--) {

                        if ($i==$lstYear) $selected="selected"; else $selected="";

                        print("<option value='$i' $selected>$i</option>");

                    }

                ?>

              </select>

              </b> </td>

            <td align='left' valign='top' class="tdodd"> <input name="btnSubmit" type="submit" class="button" value="<?=REPORT_BANNERS_GET?>">

            </td>

          </tr>

          <tr >

            <td colspan="6" align='left' valign='top' class="tdeven">&nbsp;</td>

          </tr>

          <?php

 if (isset($lstMonth) and $lstMonth != '-Choose-' and $lstYear != '-Choose-') {

    print("<tr class='tdhead'>

                             <td>&nbsp;</td>

                             <td ><b>".REPORT_BANNERS_TDATE."</b></td>

                             <td ><b>".REPORT_BANNERS_BANNER."</b></td>

                             <td ><b>".REPORT_BANNERS_SIZE."</b></td>

                             <td ><b>".REPORT_BANNERS_HITS."</b></td>

                        </tr>

                      ");

    foreach ($data as $sql_array) {

        print("<tr class='tdodd'>

                     <td>&nbsp;</td>

                  <td >$sql_array->statdatePrint</td>

                  <td >$sql_array->bannerName</td>

                  <td >$sql_array->bannerSize</td>

                  <td >$sql_array->hits</td>

                </tr> ");

        $sum_hits += $sql_array->hits;

    }

  }

?>

            <tr class='tdfoot'>

             <td   colspan='6'>&nbsp; </td>

            </tr>

            <tr>

              <td  class='tdhead' colspan='6'><b><?=REPORT_BANNERS_SUMMARY?></b></td>

            </tr>



            <tr class='tdodd'>

              <td colspan='5'>&nbsp;</td>

              <td><?=$sum_hits?></td>

            </tr>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>