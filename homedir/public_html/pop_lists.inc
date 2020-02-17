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
# Name:                 pop_lists.php
#
# Description:  Home page destination for traffic sent by affiliates
#
# Version:               7.3
#
######################################################################
function populate_lists($type,$category,$src,$matchval = '') {
GLOBAL $link;
$selected="";
$i=0; // counter to ensure that something get selected for multi selects
    $limiter = ($category=='base')?"AND lst_base='Y'":"'AND lst_adult='Y'";

    $pop_query="SELECT COALESCE(vlv.lst_value, vl.lst_value) lst_value, lst_type,vl.lst_value base_value FROM vlistbox vl LEFT OUTER JOIN vlistbox_values vlv ON vlv.lst_recid = vl.lst_recid AND vlv.lang_id = '" . $_SESSION['lang_id'] . "' WHERE lst_type='$type' $limiter ORDER BY lst_order";
      $conSting=@mysqli_connect(__CONST_DB_HOST,__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME);
        @mysqli_select_db($conSting,__CONST_DB_NAME);
 
    $popval=mysqli_query($conSting,$pop_query) or die(mysqli_error());

    while ($pop_array = mysqli_fetch_object($popval)) {
        if ($src=='srch' && $pop_array->base_value=='Not stated') continue;

        if ($matchval!="" && gettype($matchval) == 'array') {
            foreach ($matchval as $value) {
                if (strtolower($value)==strtolower($pop_array->base_value)) {
                    $selected="selected";
                    $i++;
                    break;
                } else {
                    $selected="";
                }
            }
            print("<option value=\"".htmlspecialchars($pop_array->base_value)."\" $selected>$pop_array->lst_value</option>");
        } else {
            if ($matchval!="") {
                if (strtolower($matchval)==strtolower($pop_array->base_value)) {
                    $selected="selected";
                    $i++;
                } else {
                    $selected="";
                }
            }
            print("<option value=\"".htmlspecialchars($pop_array->base_value)."\" $selected>$pop_array->lst_value</option>");
        }
    }

    if ($i == 0) $multi_select = "selected";
}

/*
function populate_checks($type,$category,$src,$matchval,$a_name) {
GLOBAL $link;
$selected="";
$i=0; // counter to ensure that something get selected for multi selects
    $limiter = ($category=='base')?"AND lst_base='Y'":"'AND lst_adult='Y'";

    $pop_query="SELECT COALESCE(vlv.lst_value, vl.lst_value) lst_value, lst_type,vl.lst_value base_value FROM vlistbox vl LEFT OUTER JOIN vlistbox_values vlv ON vlv.lst_recid = vl.lst_recid AND vlv.lang_id = '" . $_SESSION['lang_id'] . "' WHERE lst_type='$type' $limiter ORDER BY lst_order";
    $popval=mysql_query($pop_query,$link) or die(mysql_error());

    while ($pop_array = mysql_fetch_object($popval)) {
        if ($src=='srch' && $pop_array->base_value=='Not stated') continue;

        if ($matchval!="" && gettype($matchval) == 'array') {
            foreach ($matchval as $value) {
                if (strtolower($value)==strtolower($pop_array->base_value)) {
                    $selected="checked";
                    $i++;
                    break;
                } else {
                    $selected="";
                }
            }
            print("<input type='checkbox' name='$a_name' value='$pop_array->base_value' $selected>$pop_array->lst_value&nbsp;");
        } else {
            if ($matchval!="") {
                if ($matchval==$pop_array->base_value) {
                    $selected="checked";
                    $i++;
                } else {
                    $selected="";
                }
            }
            print("<input type='checkbox' name='$a_name' value='$pop_array->base_value'>$pop_array->lst_value&nbsp;");
        }
    }
}
*/
function populate_checks($type,$category,$src,$matchval,$a_name,$mode='input',$footer='Check all that apply...') {
GLOBAL $link;
$selected="";
$any_string = "- Any -";
$colnums = 3;
$i=0; // counter to ensure that something get selected for multi selects
    $limiter = ($category=='base')?"AND lst_base='Y'":"AND lst_adult='Y'";
//print_r($matchval);

    $pop_query="SELECT COALESCE(vlv.lst_value, vl.lst_value) lst_value, lst_type,vl.lst_value base_value FROM vlistbox vl LEFT OUTER JOIN vlistbox_values vlv ON vlv.lst_recid = vl.lst_recid AND vlv.lang_id = '" . $_SESSION['lang_id'] . "' WHERE lst_type='$type' $limiter ORDER BY lst_order";
    //echo $pop_query;
    $conSting=@mysqli_connect(__CONST_DB_HOST,__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME);
        @mysqli_select_db($conSting,__CONST_DB_NAME);
    $popval=mysqli_query($conSting,$pop_query) or die(mysqli_error());
    if ($mode == 'input') echo '<table border=0 width="100%" cellspacing="0" cellpadding="2"><tr valign=top class=noborder>';
    while ($pop_array = mysqli_fetch_object($popval)) $res_arr[] = $pop_array;
    array_unshift($res_arr, (object)array("lst_value"=>"No preference", "lst_type"=>$type, "base_value" => $any_string));
    $first=0;
    foreach ($res_arr as $pop_array) {
        $java_funk = "onClick=uncheck_first(this,name)";
        $selected="";
        if ($src=='srch' && $pop_array->base_value=='') continue;
        if ($mode == 'input' && $count%$colnums == 0 && $count != 0) echo "</tr><tr valign=top class=noborder>";
        $count++;

        if ($matchval!="" && gettype($matchval) == 'array') {
            foreach ($matchval as $value) {
                if ($value==$pop_array->base_value) {
                    $selected="checked";
                    $list[] = $pop_array->lst_value;
                    $i++;
                    break;
                } else {
                    $selected="";
                }
                if ($value == '- Any -' && count($matchval) > 1) $selected="";
            }
            if ((++$first)==1) {
                $selected = "";
                if ($matchval == "" || $matchval == $any_string || count($matchval) == 0 || ($matchval[0] == $any_string && count($matchval)<=1)) $selected="checked";
                $java_funk = "onClick=uncheck_other(this,name)";
            }

            if ($mode == 'input') {
?>
            <td width="<?echo round(100/$colnums)?>%">

            <table border=0 width=0% cellpadding=0 cellspacing=0><tr>
                            <td valign=top ><input type=checkbox name=<?=$a_name?> value="<?=$pop_array->base_value?>" <?=$selected?> <?=$java_funk?>></td>
                            <td><?=$pop_array->lst_value?></td>
            </tr></table>
            </td>
<?
            }
        } else {
            if ($matchval!="") {
                if ($matchval==$pop_array->base_value) {
                    $list[] = $pop_array->lst_value;
                    $selected="checked";
                    $i++;
                } else {
                    $selected="";
                }
            }
            if ((++$first)==1) {
                $selected = "";
                if ($matchval == "" || $matchval == $any_string ||  count($matchval) == 0 || ($matchval[0] == $any_string && count($matchval)<=1)) $selected="checked";
                $java_funk = "onClick=uncheck_other(this,name)";
            }

            if ($mode == 'input') {
?>
            <td width="<?echo round(100/$colnums)?>%">
            <table border=0 width=0% cellpadding=0 cellspacing=0><tr class=noborder>
                            <td valign=top><input type=checkbox name=<?=$a_name?> value="<?=$pop_array->base_value?>" <?=$selected?> <?=$java_funk?>></td>
                            <td><?=$pop_array->lst_value?></td>
            </tr></table>
            </td>
<?
            }
        }
    }
    if ($mode == 'input') {
        echo '</tr>

              </table>';
    } else {
        print(join(', ',$list));
    }
}


?>