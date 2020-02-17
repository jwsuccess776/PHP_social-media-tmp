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

# Name:         prghotlist.php

#

# Description:  Adds and removes member hotlist entries

#

# Version:      7.2

#

######################################################################



include('db_connect.php');

include('session_handler.inc');

include_once('validation_functions.php');

if(isset($_POST['hotlist'])) $hotlist= sanitizeData($_POST['hotlist'], 'xss_clean') ;  

if(isset($_POST['private'])) $private=sanitizeData($_POST['private'], 'xss_clean') ;   

if(isset($_GET['userid'])) $userid=sanitizeData($_GET['userid'], 'xss_clean') ;   

if(!isset($hotlist) && !isset($private)) save_request();

# retrieve the template

$area = 'member';



if (isset($_POST['changed']))

    $result=mysqli_query($globalMysqlConn, "UPDATE hotlist SET hot_private='N' WHERE hot_userid='$Sess_UserId'");

# check to see whether the mode is to delete hotlist elements (if $hotlist exists)

if ( isset( $hotlist) ||  isset( $private) ) { // the hotlist variable is a list of userids to delete from the hotlist

    if ( isset( $private)) {

        foreach ( $private as $key=>$value) {

            $query="UPDATE hotlist SET hot_private = 'Y' WHERE (hot_id=$value)";

            $result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        }

    }

    if ( isset( $hotlist)) {

        foreach ( $hotlist as $key=>$value) {

            $query="delete FROM hotlist WHERE (hot_id=$value)";

            $result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        }

    }

# otherwise the mode is to add to the hotlist

}



$my = mysqli_fetch_fields(mysqli_query($globalMysqlConn,"SELECT hot_advid FROM hotlist WHERE (hot_userid=$Sess_UserId)"));
// print_r((array)$my[0]);
// die;
$me = mysqli_fetch_fields(mysqli_query($globalMysqlConn,"SELECT hot_userid FROM hotlist WHERE (hot_advid=$Sess_UserId)"));

$dual_links = array_intersect((array)$my[0], (array)$me[0]);



if ($mode == 0) {

    $colspan = 6;

    $query="SELECT * FROM hotlist INNER JOIN adverts ON (hot_advid = adv_userid) WHERE (hot_userid=$Sess_UserId) ORDER BY hot_dateadded";

} else {

    $colspan = 4;

    $query="SELECT * FROM hotlist INNER JOIN adverts ON (hot_userid = adv_userid) WHERE (hot_advid=$Sess_UserId) ORDER BY hot_dateadded";

}



$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

?>

<?=$skin->ShowHeader($area)?>



  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo HOTLIST_SECTION_NAME ?></td>

    </tr>

    <tr><td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/prghotlist.php'>

        <input type=hidden name=mode value="<?=$mode?>">

          <tr>

            <td colspan="<?=$colspan?>" class="tdhead">

                <div id="mail"> 

                 <ul id="mailnav">

                   <li><a href='<?php echo $CONST_LINK_ROOT?>/prghotlist.php' <?php if ($mode == 0){?>id='current'<?php }?>> 

                     <?php echo HOTLIST_SECTION_NAME ?></a>

                   </li>

                   <li><a href='<?php echo $CONST_LINK_ROOT?>/prghotlist.php?mode=1' <?php if ($mode == 1){?>id='current'<?php }?>> 

                     <?php echo HOTLISTED_ME_SECTION_NAME ?></a>

                   </li>

                 </ul>

               </div>

            </td>

          </tr>

          <tr  class="tdtoprow">

            <td>&nbsp;</td>

            <td>

            <strong><?php echo PRGHOTLIST_MEMBER?></strong>

              <input name="changed" value="Y" type="hidden"></td>

            <td><strong><?php echo PRGHOTLIST_TITLE?></strong></td>

            <td><strong><?php echo PRGHOTLIST_DATE?></strong></td>

    <?php if ($mode==0){?>

            <td align="center"><strong><?php echo GENERAL_PRIVATE?></strong></td>

            <td align="center"><strong><?php echo GENERAL_DELETE?></strong></td>

    <?php }?>

          </tr>

          <?php

// insert the line code here

$TOTAL = mysqli_num_rows($result);

if ($TOTAL > 0) {

    while ($sql_array = mysqli_fetch_object($result) ) {

        $checkedPrivate="";

        if ($sql_array->hot_private=='Y') $checkedPrivate="checked";

?>

        <tr  class='tdodd'>

            <td><?php if (in_array($sql_array->adv_userid, $dual_links)) { ?><img src="<?=CONST_IMAGE_ROOT?>/icons/heart.gif" border=0><?php }?>&nbsp;</td>

            <td><a href='<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$sql_array->adv_userid?>'><?=$sql_array->adv_username?></a></td>

            <td><?=$sql_array->adv_title?></td>

            <td><?=date($CONST_FORMAT_DATE_SHORT,strtotime($sql_array->hot_dateadded))?></td>

    <?php if ($mode==0){?>

            <td align='center'><input type='checkbox' name='private[]' value=<?=$sql_array->hot_id?> <?=$checkedPrivate?>></td>

            <td align='center'><input type='checkbox' name='hotlist[]' value=<?=$sql_array->hot_id ?>></td>

    <?php }?>

        </tr>

<?php    }?>

<?php } else {?>

        <tr  class='tdeven'>

            <td  align='center' colspan='<?=$colspan?>' ><?=NO_RECORDS_SECTION_NAME?></td>

        </tr>

<?php }?>

<?php if ($TOTAL > 0 && $mode == 0) {?>

        <tr>

            <td  colspan='<?=$colspan?>' align='center' class='tdfoot'>

            <input name='Validate' type='submit' class='button' value='<?=BUTTON_UPDATE?>'>

<?php } else { ?>

          <TR colspan='<?=$colspan?>' ><TD>&nbsp;</TD</TR>

<?php } ?>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>