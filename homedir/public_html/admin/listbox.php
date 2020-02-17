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

# Name:                 listbox.php

#

# Description:

#

# Version:               7.2

#

######################################################################



include('../db_connect.php');

include_once('../validation_functions.php'); 

include($CONST_INCLUDE_ROOT.'/session_handler.inc');

include($CONST_INCLUDE_ROOT.'/functions.php');

include($CONST_INCLUDE_ROOT.'/pop_lists.inc');

include($CONST_INCLUDE_ROOT.'/error.php');

include($CONST_INCLUDE_ROOT.'/message.php');

include('permission.php');



$mode=form_get('mode');

$recid=form_get('recid');

$lstTypes=form_get('lstTypes');

$txtBase=form_get('txtBase');

# retrieve the template

$area = 'member';



if (!isset($lstTypes) || empty($lstTypes)) {

        $lstTypes='SKG';

}

$arr_languages = $language->GetActiveList();



function split_by_lang($from, $field_name) {

        global $arr_languages;

        $res = array();

        foreach ($arr_languages as $l) {

                $field = $field_name . '_' . $l->LangID;

                if (isset($from[$field]))

                        $res[$l->LangID] = $from[$field];

        }

        return $res;

}

switch ($mode) {

        case 'edit':

                restrict_demo();

                $query = "SELECT *

                          FROM vlistbox

                          WHERE lst_recid = '$recid'";

//                          echo $query."<br>";

                $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                $opt_row = mysqli_fetch_object($result);

                $version = $db->get_var("select left(version(),1)");

                if ($version == 5){

                    $query = "SELECT vl.lst_recid, vl.lst_base, vl.lst_adult, vl.lst_order,

                                COALESCE(res.lst_value, vl.lst_value) lst_value, res.lang_id, res.lang_name,vl.lst_value opt_base

                              FROM vlistbox vl

                                LEFT JOIN   (

                                    select l.lang_id, l.lang_name,vlv.lst_value,vlv.lst_recid

                                    FROM langfile l

                                    LEFT  JOIN vlistbox_values vlv

                                        ON (vlv.lang_id = l.lang_id and vlv.lst_recid = '$recid')

                                    WHERE lang_active =1

                                ) res ON 1=1

                                WHERE vl.lst_recid = '$recid'

                              ";

                } else {

                    $query = "SELECT vl.lst_recid, vl.lst_base, vl.lst_adult, vl.lst_order,

                                COALESCE(vlv.lst_value, vl.lst_value) lst_value, l.lang_id, l.lang_name,vl.lst_value opt_base

                              FROM langfile l

                                LEFT OUTER JOIN vlistbox_values vlv ON vlv.lang_id = l.lang_id

                                RIGHT JOIN vlistbox vl ON  vl.lst_recid = vlv.lst_recid

                              WHERE vl.lst_recid = '$recid' AND lang_active =1";

                }



                $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                break;



        case 'save':

                restrict_demo();

                $arr_txtAddvalue = split_by_lang($_POST, 'txtAddvalue');

                $txtOrder =sanitizeData($_POST['txtOrder'], 'xss_clean'); 

                $txtRecid = sanitizeData($_POST['txtRecid'], 'xss_clean');

                $txtBase=sanitizeData($_POST['txtBase'], 'xss_clean'); 

                foreach ($arr_txtAddvalue as $lang => $txtAddvalue) {

#                        $txtAddvalue = preg_replace("/'/","&#039;",$txtAddvalue,-1);

                        $query = "REPLACE vlistbox_values SET lst_value='$txtAddvalue', lst_recid = '$txtRecid',  lang_id = '$lang'";

                        mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                }

                $txtAddvalue = $arr_txtAddvalue['EN'];

#                $txtAddvalue = preg_replace("/'/","&#039;",$txtAddvalue,-1);

                $query = "UPDATE vlistbox SET lst_value='$txtBase', lst_order=$txtOrder WHERE lst_recid = '$txtRecid'";

                $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                break;

        case 'add':

                restrict_demo();

                $arr_txtAddvalue = split_by_lang($_POST, 'txtAddvalue');

                $txtOrder=sanitizeData($_POST['txtOrder'], 'xss_clean');  



                $txtAddvalue = $txtBase;

                $query = "INSERT INTO vlistbox (lst_value,lst_type,lst_order,lst_base,lst_adult) VALUES ('$txtAddvalue','$lstTypes','$txtOrder','Y','Y')";

                mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                $txtRecid = mysqli_insert_id($globalMysqlConn);

                foreach ($arr_txtAddvalue as $lang => $txtAddvalue) {

                        $query = "INSERT INTO vlistbox_values (lst_recid, lang_id, lst_value) VALUES  ('$txtRecid', '$lang', '$txtAddvalue')";

                        mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                }

                break;

        case 'delete':

                restrict_demo();

                $query="DELETE FROM vlistbox_values WHERE lst_recid='$recid'";

                mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                $query="DELETE FROM vlistbox WHERE lst_recid='$recid'";

                mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                break;

}

$query="SELECT vl.lst_recid, vl.lst_type, vl.lst_base, vl.lst_adult, vl.lst_order, COALESCE(vlv.lst_value, vl.lst_value) lst_value,vl.lst_value opt_base

        FROM vlistbox vl LEFT OUTER JOIN vlistbox_values vlv ON vl.lst_recid = vlv.lst_recid AND vlv.lang_id = '" . $_SESSION['lang_id'] . "'

        WHERE vl.lst_type='$lstTypes' ORDER BY lst_order ASC";

$values_arr = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

// mysql_close( $link );



?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo LISTOPTIONS_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><?php include("admin_menu.inc.php");?></td>

  </tr>

    <tr><td>

        <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_ADMIN_LINK_ROOT?>/listbox.php' id="FrmListbox" name="FrmListbox" >

          <tr>

            <td align="left" valign="top" class="tdhead" colspan="4">&nbsp;</td>

          </tr>

          <tr class="tdodd">

            <td colspan="4" align="center"> <select class="input"  size="1" name="lstTypes" onChange="getElementById('FrmListbox').mode.value='view'; document.FrmListbox.submit(); return true;">

                <option <?php if ($lstTypes=="SKG") print("selected"); ?> value="SKG"><?php echo OPTION_SEEKING?></option>

                <option <?php if ($lstTypes=="ETH") print("selected"); ?> value="ETH"><?php echo OPTION_ETHNICITY?></option>

                <option <?php if ($lstTypes=="MRT") print("selected"); ?> value="MRT"><?php echo OPTION_MARITAL?></option>

                <option <?php if ($lstTypes=="BDY") print("selected"); ?> value="BDY"><?php echo OPTION_BODY_TYPE?></option>

                <option <?php if ($lstTypes=="RLG") print("selected"); ?> value="RLG"><?php echo OPTION_RELIGION?></option>

                <option <?php if ($lstTypes=="EDU") print("selected"); ?> value="EDU"><?php echo OPTION_EDUCATION?></option>

                <option <?php if ($lstTypes=="SMK") print("selected"); ?> value="SMK"><?php echo OPTION_SMOKER?></option>

                <option <?php if ($lstTypes=="CHL") print("selected"); ?> value="CHL"><?php echo OPTION_CHILDREN?></option>

                <option <?php if ($lstTypes=="EMP") print("selected"); ?> value="EMP"><?php echo OPTION_EMPLOYMENT?></option>

                <option <?php if ($lstTypes=="INC") print("selected"); ?> value="INC"><?php echo OPTION_INCOME?></option>

                <option <?php if ($lstTypes=="PST") print("selected"); ?> value="PST"><?php echo OPTION_PERSONALITY?></option>

                <option <?php if ($lstTypes=="PHI") print("selected"); ?> value="PHI"><?php echo OPTION_PHILOSOPHIES?></option>

                <option <?php if ($lstTypes=="SOG") print("selected"); ?> value="SOG"><?php echo OPTION_SOCIAL_GROUP?></option>

                <option <?php if ($lstTypes=="GLS") print("selected"); ?> value="GLS"><?php echo OPTION_GOALS?></option>

                <option <?php if ($lstTypes=="HBS") print("selected"); ?> value="HBS"><?php echo OPTION_HOBBIES?></option>

                <option <?php if ($lstTypes=="SPT") print("selected"); ?> value="SPT"><?php echo OPTION_SPORTS?></option>

                <option <?php if ($lstTypes=="MSC") print("selected"); ?> value="MSC"><?php echo OPTION_MUSIC?></option>

                <option <?php if ($lstTypes=="FDT") print("selected"); ?> value="FDT"><?php echo OPTION_FOOD_TASTE?></option>

                <option <?php if ($lstTypes=="EYE") print("selected"); ?> value="EYE"><?php echo OPTION_EYE_COLOR?></option>

                <option <?php if ($lstTypes=="HAR") print("selected"); ?> value="HAR"><?php echo OPTION_HAIR_COLOR?></option>

                <option <?php if ($lstTypes=="DNK") print("selected"); ?> value="DNK"><?php echo OPTION_DRINK?></option>

              </select> <input type='hidden' name='mode'> </td>

          </tr>

          <?php

 if ($mode!='edit') {



                print(" <tr class='tdtoprow'>

                         <td>&nbsp;</td>

                         <td>&nbsp;</td>

                         <td><b>Value</b></td>

                         <td><b>Order</b></td>

                      </tr>");

                while ($sql_array = mysqli_fetch_object($values_arr)) {

                    print("<tr  class='tdeven'>

                        <td  align='center' valign='middle'><a href='$CONST_ADMIN_LINK_ROOT/listbox.php?mode=edit&recid=$sql_array->lst_recid&lstTypes=$sql_array->lst_type'>".GENERAL_EDIT."</a></td>

                        <td  align='center' valign='middle'><a href='$CONST_ADMIN_LINK_ROOT/listbox.php?mode=delete&recid=$sql_array->lst_recid&lstTypes=$sql_array->lst_type'>".GENERAL_DELETE."</a></td>

                        <td  valign='middle'>$sql_array->lst_value</td>

                        <td  valign='middle'>$sql_array->lst_order</td>

                              ");

                }

                print("</tr>");

}

?>



          <?php

if ($mode!='edit') {

?>

          <tr  class="tdodd">

            <td align="right"  colspan='2'>

              <?= LISTBOX_BASE?>

            </td>

            <td valign='top'   colspan='2'> <input type='text' name='txtBase' value="<?= $lst_base?>" class="input"></td>

          </tr>

          <?php

                foreach ($arr_languages as $lang) {

?>

          <tr  class="tdeven">

            <td align="right" colspan="2">

              <?= LISTBOX_NEW ?>

              &nbsp;(

              <?= $lang->Name ?>

              )</td>

            <td  valign='top' colspan='2'> <input type='text' name='txtAddvalue_<?= $lang->LangID ?>' class="input"></td>

          </tr>

          <?php

        }

?>

          <tr  class="tdodd">

            <td align="right" colspan='2'>

              <?= LISTBOX_ORDER ?>

            </td>

            <td align="left" colspan="2"><input type='hidden' name='txtRecid' value='<?= $recid ?>'>

              <input type='text' name='txtOrder' size='5' value='<?= $lst_order ?>' class="input">

              <input type='submit' class="button" onClick="getElementById('FrmListbox').mode.value='add';" value='<?= GENERAL_SAVE ?>'></td>

          </tr>

          <?php

}else {

?>

          <tr  class="tdeven">

            <td align="right" colspan="2">

              <?= LISTBOX_BASE?>

            </td>

            <td valign='top'  colspan="2"> <input type='text' name='txtBase' value="<?= $opt_row->lst_value?>" class="input"></td>

          </tr>

         <?php

            while ($item_arr = mysqli_fetch_object($result)) {

          ?>

          <tr  class="tdodd">

            <td align="right"  colspan="2">

              <?= LISTBOX_EDIT ?>

              &nbsp;(

              <?= $item_arr->lang_name ?>

              )</td>

            <td align='left' valign='top'  colspan='2'> <input type='text' name='txtAddvalue_<?= $item_arr->lang_id ?>' value="<?= $item_arr->lst_value ?>" class="input"></td>

          </tr>

          <?php

        }

?>

          <tr  class="tdeven">

            <td align="right"  colspan="2">

              <?= LISTBOX_ORDER ?>

            </td>

            <td align="left"  colspan="2"><input type='hidden' name='txtRecid' value='<?= $recid ?>'>

              <input type='text' name='txtOrder' size='5' value="<?= $opt_row->lst_order ?>" class="input">

              <input name="SAVE" type='submit' class="button" onClick="getElementById('FrmListbox').mode.value='save';" value='<?= GENERAL_SAVE ?>'></td>

          </tr>



          <?php

}

?>  <tr  >

            <td valign="top" align="right"  colspan="4"  class="tdfoot">&nbsp;</td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>



<?=$skin->ShowFooter($area)?>