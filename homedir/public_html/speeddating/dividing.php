<?
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
# Name:                 dividing.php
#
# Description:  Handles errors occuring on the member side
#
# Version:                7.2
#
######################################################################

//divides all comming data by pages
//in:       cur_page - number - current page number
//          on_page - string - how many objects are shown on page
//          all_object - number - how many objects are exist
//          required_param - <space> or array - do we need to have some extra param in link
//          show_page - number - max shown page quantity
//out:      from - number - sart show objects from
//          till - number - show objects till
//          page_links - hash array - numbers of pages to be shown
//          page_quantity - number - total number of pages

function divide_results($cur_page, $on_page, $all_object, $show_page = 5) {

    $page_quantity = ceil($all_object/$on_page);

    $show_left = floor($show_page/2);
    $show_right = $show_left;

    if( $show_page%2 == 0) { $show_right--; }

    if ($show_left + $show_right + 1 >= $page_quantity) {
        $last_show_page = $page_quantity;
        $first_show_page = 1;
    }
    else {
        //if we are near the left border
        if ($cur_page <= $show_left) {
            $first_show_page = 1;
            $last_show_page = $show_right + $show_left + 1;
        }
        else {
            //if we are near the right border
            if (($cur_page >= $page_quantity - $show_right)) {
                $last_show_page = $page_quantity;
                $first_show_page = $page_quantity - $show_left - $show_right;
            }
            else {
                //there is enough place for left and right links
                $last_show_page = $cur_page + $show_right;
                $first_show_page = $cur_page - $show_left;
            }
        }
    }

    $j = 0;
    $pages_link = array();
    for ($i = $first_show_page; $i <= $last_show_page; $i++) {
        $pages_link[$j] = $i;
        $j++;
    }

    $from = ($cur_page-1)*$on_page;
    ($from + $on_page > $all_object ? $till = $all_object : $till = $from + $on_page);

    $divide = array("from" => $from, "till" => $till, "pages_link" => $pages_link, "page_quantity" => $page_quantity, "total" => $all_object);
    return $divide;
}

//setup params and add to session number of items on a page
//out   cur_page - number - current page number
//      on_page - string - how many objects are shown on page
function divide_setup() {
    $page=$_REQUEST["page"];
    $cur_page = $_REQUEST["cur_page"];
    $update_on_page = $_POST["update_on_page"];

    if ($update_on_page != "") {
        $on_page = $_REQUEST["on_page"];
        //  echo $on_page;
        $_SESSION["on_page"] = $on_page;
    }
    else {
        if (isset($_SESSION["on_page"])) {
            $on_page = $_SESSION["on_page"];
        }
        else {
            $on_page = 10;
            $_SESSION["on_page"] = $on_page;
        }
    }

    if ($cur_page == "") {
        $cur_page = 1;
    }
    else {
        $cur_page = $_REQUEST["cur_page"];
    }
    return array($cur_page, $on_page);
}


//create divide part HTML
//in
function result_str2($cur_page, $on_page, $qty, $required_param, $dividing) {
    $out = "";
    if ($qty > 0) {
        $out .= '<form method="post" action="'.$page_name.'" name="go_form" style="margin: 0px; padding: 0px;">
    <input type="hidden" name="cur_page" value="1">';
        if ($required_param != "") {
            foreach ($required_param as $name => $value) {
                if ("array" == gettype($value)) {
                    for ($i = 0; $i < count($value); $i++) {
                        $out .= '<input type="hidden" name="'.$name.'[]" value="'.$value[$i].'">';
                    }
                }
                else { //string
                    $out .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
                }
            }
        }
        $out .= '
</form>

<script>
function go_to_func(page_num) {
    document.forms["go_form"].cur_page.value = page_num;
    document.forms["go_form"].submit();
}
</script>

<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
<tr><td class="tdhead" colspan="5">&nbsp;</td></tr>
<tr class="tdodd">
    <td>Results: Overall - '.$dividing['total'].'</td>
    <td>Page: '.$cur_page.' of '.$dividing["page_quantity"].'</td>
    <td>On this page: '.($dividing["from"]+1).' - '.$dividing["till"].'</td>
    <td align="center">';

        if ($cur_page != 1) {
            $out .= '
        <a href="javascript: go_to_func(\'1\');">&lt;&lt;</a>&nbsp;
        <a href="javascript: go_to_func(\''.($cur_page-1).'\')">&lt;</a>';
        }

        for ($i = 0; $i < count($dividing["pages_link"]); $i++) {
            if ($dividing["pages_link"][$i] != $cur_page) {
                $out .= '&nbsp;<a href="javascript: go_to_func(\''.$dividing["pages_link"][$i].'\')")>'.$dividing["pages_link"][$i].'</a>&nbsp;';
            }
            else {
                $out .= $dividing["pages_link"][$i];
            }
        }

        if ($cur_page != $dividing["page_quantity"]) {
            $out .= '
        <a href="javascript: go_to_func(\''.($cur_page+1).'\')">&gt;</a>&nbsp;
        <a href="javascript: go_to_func(\''.$dividing['page_quantity'].'\')">&gt;&gt;</a>';
        }
        $out .= '
        &nbsp;
    </td><form method="post" action="'.$page_name.'" >
    <td align="right">


        <input type="hidden" name="update_on_page" value="yes">';
        foreach ($required_param as $name => $value) {
            if ("array" == gettype($value)) {
                for ($i = 0; $i < count($value); $i++) {
                    $out .= '<input type="hidden" name="'.$name.'[]" value="'.$value[$i].'">';
                }
            }
            else { //string
                $out .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
            }
        }
        $out .= '
        <select name="on_page" size="1" class="sel">';
        $available_values = array(10, 25, 50, 100);
        for ($i = 0; $i < count($available_values); $i++) {
            $selected = $available_values[$i] == $on_page ? " SELECTED" : "";
            $out .= '<option value="'.$available_values[$i].'" '.$selected.'>'.$available_values[$i].'</option>';
        }
        $out .= '
        </select>
        <input type="submit" class="input_button" name="submit_page" value="Go" id="button">
    </td></form>
</tr>
<tr><td class="tdfoot" colspan="5">&nbsp;</td></tr>
</table>';
    }
    return $out;
}

function result_str($cur_page, $on_page, $qty, $required_param, $dividing, $block_name = "div_", $show_select = true) {
    $out = '
<style>
    a.pager, a.pager:visited, a.pager:hover, a.pager:active {
    color: #000000;
    font-weight: bold;
}
</style>
';
    if ($qty > 0) {
        $out .= '
<form method="post" action="'.$page_name.'" name="'.$block_name.'go_form" style="margin: 0px; padding: 0px;">
    <input type="hidden" name="cur_page" value="1">';
        if ($required_param != "") {
            foreach ($required_param as $name => $value) {
                if ("array" == gettype($value)) {
                    for ($i = 0; $i < count($value); $i++) {
                        $out .= '<input type="hidden" name="'.$name.'[]" value="'.$value[$i].'">';
                    }
                }
                else { //string
                    $out .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
                }
            }
        }
        $out .= '
</form>

<script>
function '.$block_name.'go_to_func(page_num) {
    document.forms["'.$block_name.'go_form"].cur_page.value = page_num;
    document.forms["'.$block_name.'go_form"].submit();
}
</script>

<table width="100%"  border="0" cellspacing="'.$CONST_SUBTABLE_CELLSPACING.'" cellpadding="'.$CONST_SUBTABLE_CELLPADDING.'">
  <tr>
    <td align="center" class="tdhead">&nbsp;</td>
  </tr>
  <tr class="tdodd">
    <form method="post" action="'.$page_name.'"name="'.$block_name.'change_form">
      <td align="center"> <input type="hidden" name="update_on_page" value="yes">';
        foreach ($required_param as $name => $value) {
            if ("array" == gettype($value)) {
                for ($i = 0; $i < count($value); $i++) {
                    $out .= ' <input type="hidden" name="'.$name.'[]" value="'.$value[$i].'">';
                }
            } else {
                //string
                $out .= ' <input type="hidden" name="'.$name.'" value="'.$value.'">';
            }
        }
        $out .= 'Results: <b>'.($dividing["from"]+1).' - '.$dividing["till"].'</b> | Total: <b>'.$dividing['total'].'</b>';
        if ($show_select) {
            $out .= ' | Result per page:<select name="on_page" size="1" class="inputf" onchange="this.form.submit();">';
            $available_values = array(10, 25, 50, 100);
            for ($i = 0; $i < count($available_values); $i++) {
                 $selected = $available_values[$i] == $on_page ? " SELECTED" : "";
                 $out .= '<option value="'.$available_values[$i].'" '.$selected.'>'.$available_values[$i].'</option>';
            }
            $out .= '</select>';
        }
        $out .= '<br>';
        if ($cur_page != 1) {
            $out .= ' <a href="javascript: '.$block_name.'go_to_func(\'1\');" class="pager">First</a>&nbsp;|
                      <a href="javascript: '.$block_name.'go_to_func(\''.($cur_page-1).'\')" class="pager">Prev</a>&nbsp;|';
        }
        for ($i = 0; $i < count($dividing["pages_link"]); $i++) {
            if ($dividing["pages_link"][$i] != $cur_page) {
                $out .= '&nbsp;<a href="javascript: '.$block_name.'go_to_func(\''.$dividing["pages_link"][$i].'\')") class="pager">'.$dividing["pages_link"][$i].'</a>&nbsp;';
                if (!isset($dividing["pages_link"][$i+1])) {
                    //if next page doesn't exist and this is not cur page(condotion early)
                    $out .= "|";
                } else if ($cur_page != $dividing["pages_link"][$i+1]) {
                        //if next page not current
                        $out .= "|";
                }
            } else {
                $out .= '&nbsp;['.$dividing["pages_link"][$i].']&nbsp;';
            }
        }
        if ($cur_page != $dividing["page_quantity"]) {
            $out .= ' <a href="javascript: '.$block_name.'go_to_func(\''.($cur_page+1).'\')" class="pager">Next</a>&nbsp|
                      <a href="javascript: '.$block_name.'go_to_func(\''.$dividing['page_quantity'].'\')" class="pager">Last</a>';
        }
        $out .= ' </td>
    </form>
  </tr>
  <tr>
    <td align="center"  class="tdfoot">&nbsp;</td>
  </tr>
</table>';
    }
    return $out;
}

?>