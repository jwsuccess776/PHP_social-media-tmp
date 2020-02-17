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

# Name:                 prgmembers.php

#

# Description:  Administrator can amend members records

#

# Version:                7.2

#

######################################################################



include_once('../db_connect.php');

include_once('../validation_functions.php'); 

include_once('../session_handler.inc');

include_once('../message.php');

include_once('../pop_lists.inc');

include_once('../error.php');

include_once('../deletes.php');

include_once('../functions.php');

include('permission.php');



$txtHandle = form_get("txtHandle");

$searchEmail = form_get("searchEmail");

$mode = form_get("mode");



# retrieve the template

$area = 'member';



switch ($mode) {



        case 'start':

                $userlevel = ""; $sex= ""; $memlevel= "";

                $expire_date_year="";

                $expire_date_month="";

                $expire_date_day="";

                break;



        case 'fetch':

                $lstUsername=sanitizeData($_POST['lstUsername'], 'xss_clean');    

                break;



        case 'save':



//                restrict_demo();

                $lstUsername=sanitizeData($_POST['lstUsername'], 'xss_clean');   

                $usertype=sanitizeData($_POST['txtUsertype'], 'xss_clean');   

                $sex=sanitizeData($_POST['txtSex'], 'xss_clean');  

                $memlevel=sanitizeData($_POST['txtMemlevel'], 'xss_clean');  

                $password=sanitizeData($_POST['txtPassword'], 'xss_clean');  

                $email=sanitizeData($_POST['txtEmail'], 'xss_clean'); 



                $lstExpireDay=sanitizeData($_POST['lstExpiredate_day'], 'xss_clean');  

                $lstExpireMonth=sanitizeData($_POST['lstExpiredate_month'], 'xss_clean'); 

                $lstExpireYear=sanitizeData($_POST['lstExpiredate_year'], 'xss_clean'); 

                $Expire_date=$lstExpireYear.'-'.$lstExpireMonth.'-'.$lstExpireDay;

                if (!checkdate((int)$lstExpireMonth, (int)$lstExpireDay, (int)$lstExpireYear)) {

                    error_page(PRGMEMBERS_EXPIREDATE_ERROR,GENERAL_USER_ERROR);

                }



                $query="UPDATE members SET mem_email='$email', mem_password='$password', mem_sex='$sex', mem_type='$usertype', mem_expiredate='$Expire_date' WHERE mem_userid='$lstUsername'";



                $retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());



                $query="SELECT * FROM adverts WHERE adv_userid='$lstUsername'";

                $retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                $TOTAL = mysqli_num_rows($retval);



                if ($TOTAL > 0) {



                        $query="UPDATE adverts SET adv_sex='$sex', adv_expiredate='$Expire_date' WHERE adv_userid='$lstUsername'";

                        $retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                }

                break;



        case 'deletead':

                restrict_demo();

                $lstUsername=sanitizeData($_POST['lstUsername'], 'xss_clean');   

                delete_advert($lstUsername);

                break;

        case 'suspendme':

                restrict_demo();

                $lstUsername=sanitizeData($_POST['lstUsername'], 'xss_clean');   

                $query="UPDATE members SET mem_suspend='Y' WHERE mem_userid='$lstUsername'";

                $retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                break;

        case 'reactivateme':

                restrict_demo();

                $lstUsername=sanitizeData($_POST['lstUsername'], 'xss_clean');

                $query="UPDATE members SET mem_suspend='N' WHERE mem_userid='$lstUsername'";

                $retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

                break;

        case 'login':

                restrict_demo();

                $lstUsername=sanitizeData($_POST['lstUsername'], 'xss_clean');  

                $user = $db->get_row("SELECT * FROM members WHERE mem_userid = '$lstUsername'");



                foreach ($_SESSION as $name => $val)

                    $old_data[$name] = $val;



                $_SESSION['old_data'] = $old_data;



                $_SESSION['Sess_UserType']=$user->mem_type;

                $_SESSION['Sess_UserName']=$user->mem_username;

                $_SESSION['Sess_UserId']=$user->mem_userid;

                $_SESSION['Sess_LastVisit']=$user->mem_lastvisit;



                $sess_id="PHPSESSID=".session_id();

                if ($user->mem_expiredate < date("Y-m-d")) {

                    $_SESSION['Sess_Userlevel']="silver";

                } else {

                    $_SESSION['Sess_Userlevel']="gold";

                }



                redirect($CONST_LINK_ROOT."/home.php");



                break;

        case 'deleteme':

                restrict_demo();

                $lstUsername=sanitizeData($_POST['lstUsername'], 'xss_clean');   

                delete_advert($lstUsername);

                delete_me($lstUsername);

                delete_match($lstUsername);

                unset ($lstUsername);

                break;

}



if (isset($_POST['get'])) {



        # select advert data

        $result = mysqli_query($globalMysqlConn,"SELECT * FROM adverts WHERE adv_userid='$lstUsername'");

        $TOTAL_ADV = mysqli_num_rows($result);



        # select member data

        $result = mysqli_query($globalMysqlConn,"SELECT * FROM members WHERE mem_userid='$lstUsername'");

//        echo "SELECT * FROM members WHERE mem_userid='$lstUsername'";

        $TOTAL = mysqli_num_rows($result);



        if ($TOTAL  > 0) {

            $cur_member = mysqli_fetch_object($result);

            # place member data into variables for display

            $memlevel=$cur_member->mem_level;

            $sex=$cur_member->mem_sex;

            $usertype=$cur_member->mem_type;

            $password=$cur_member->mem_password;

			$ip_address=$cur_member->mem_ip;



			$surname=$cur_member->mem_surname;



			$forename=$cur_member->mem_forename;

			$join_date=$cur_member->mem_joindate;

            $email=$cur_member->mem_email;

            $suspended=$cur_member->mem_suspend;

            $expire_date=date('Y/m/d',strtotime($cur_member->mem_expiredate));

            $expire_date_year=date('Y',strtotime($cur_member->mem_expiredate));

            $expire_date_month=date('n',strtotime($cur_member->mem_expiredate));

            $expire_date_day=date('j',strtotime($cur_member->mem_expiredate));



        } else {

            $userlevel = ""; $sex= ""; $memlevel= "";  $password="";

            $expire_date="";

            $expire_date_year="";

            $expire_date_month="";

            $expire_date_day="";

        }

}



?>

<?=$skin->ShowHeader($area)?>

<script language="JavaScript" src="../calendar.js"></script>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo MEMBER_ADMIN_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr><td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/prgmembers.php' name="FrmMembers">

          <tr>

            <td colspan="3" align="left" class="tdhead" >&nbsp;</td>

          </tr>

          <tr class="tdodd">

            <td align="left" ><?php echo REGISTER_EMAIL ?></td>

            <td colspan="2"> <input type="text" class="input" name="searchEmail" value='<?php echo $searchEmail; ?>'>

            </td>

          </tr>

          <tr class="tdodd">

            <td align="left" ><?php echo PRGMEMBERS_SEARCH ?></td>

            <td> <input type="text" class="input" name="txtHandle" value='<?php echo $txtHandle ?>'>

            </td>

            <td align="left"> <input name='submit' type='submit' class='button' id="submit" value='<?php echo BUTTON_SEARCH ?>'>

            </td>

          </tr>

          <tr align="left" valign="top" class="tdeven">

            <td  > <input type='hidden' name="hiddenemail" value="<?php print("$email"); ?>">

              <input type="hidden" name="mode" value="fetch"> <?php echo PRGMEMBERS_USERID?>

            </td>

            <td > <select name="lstUsername" size="6" class="inputl">

                <option value='0' <?php if (!isset($lstUsername)) print("selected"); ?>>--

                <?php echo PRGMEMBERS_SELECT?> --</option>

                <?php

                        if (isset($_POST['submit']) || isset($_POST['get'])) {

                                $query="SELECT * FROM members WHERE 1";

                                if (!empty($txtHandle)) {

                                    $query .= " AND mem_username LIKE '%$txtHandle%' ";

                                }

                                if (!empty($searchEmail)) {

                                    $query .= " AND mem_email LIKE '%$searchEmail%' ";

                                }



                                $query .= " ORDER BY mem_username ASC";



                                $result=mysqli_query($globalMysqlConn,$query);

                                while ($sql_username = mysqli_fetch_object($result)) {

                                        if (isset($lstUsername) && $lstUsername==$sql_username->mem_userid) {

                                                print("<option value='$sql_username->mem_userid' selected>$sql_username->mem_username ($sql_username->mem_email)</option>\n");

                                        } else {

                                                print("<option value='$sql_username->mem_userid'>$sql_username->mem_username ($sql_username->mem_email)</option>\n");

                                        }

                                }

                        }

                    ?>

              </select></td>

            <td  valign="bottom"> <input type='submit' name='get' value='<?php echo BUTTON_GETMEMBER ?>' class='button'></td>

          </tr>

          </form>



        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/prgmembers.php' name="FrmCurMember">

        <input type="hidden" name="lstUsername" value="<?=$cur_member->mem_userid;?>">

        <input type="hidden" name="searchEmail" value="<?=$searchEmail;?>">

        <input type="hidden" name="txtHandle" value="<?=$txtHandle;?>">

        <input type="hidden" name="get" value="<?php echo BUTTON_GETMEMBER ?>">

        <input type="hidden" name="mode" value="">

          <tr>

            <td colspan="3" align="left" class="tdfoot">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="3" align="left" class="tdhead">&nbsp;</td>

          </tr>

          <tr class="tdodd">

            <td align="left"  ><?php echo PRGMEMBERS_USER_TYPE?></td>

            <td align="left"> <input name="txtUsertype" type="text" class="input" size="2" maxlength="1" value="<?php print("$usertype"); ?>">

            </td>

            <td align="left"> (<?php echo PRGMEMBERS_U?>)</td>

          </tr>

          <tr class="tdeven"  >

            <td align="left"><?php echo PRGMEMBERS_DATE?></td>

            <td align="left">

              <select name="lstExpiredate_month" class="inputf">

                <option value="">

                <?=ADM_EDIT_MONTH?>

                <?

                            $month=array(1=>MONTH_JAN,2=>MONTH_FEB,3=>MONTH_MAR,4=>MONTH_APR,5=>MONTH_MAY,6=>MONTH_JUN,

                            7=>MONTH_JUL,8=>MONTH_AUG,9=>MONTH_SEP,10=>MONTH_OCT,11=>MONTH_NOV,12=>MONTH_DEC);

                            for($i=1;$i<=12;$i++) {

                                if($i==$expire_date_month) {

                                    $selected = "selected";

                                } else {

                                    $selected = "";

                                }

                                echo '<option value="'.$i.'"'.$selected.'>'.$month[$i].'';

                            }

                            ?>

                            </select>

              <select name="lstExpiredate_day" class="inputf">

                <option value="">

                <?=ADM_EDIT_DAY?>

                <?

                            for($i=1;$i<=31;$i++){

                                if($i==$expire_date_day) {

                                    $selected = "selected";

                                } else {

                                    $selected = "";

                                }

                                echo '<option value="'.$i.'"'.$selected.'>'.$i.'';

                            }

                            ?>

                            </select>

              <select name="lstExpiredate_year" class="inputf">

                <option value="">

                <?=ADM_EDIT_YEAR?>

                <?

                            $year=date("Y");

                            for($i=$year-5;$i<=$year+10;$i++){

                                if($i==$expire_date_year){

                                    echo "<option selected value='$i'>$i\n";

                                } else {

                                    echo "<option value='$i'>$i\n";

                                }

                            }

                            ?>

                            </select>

              <a href="javascript:call.from_select();"><img src="<?php echo $CONST_IMAGE_ROOT ?>/cal/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a> </span>



            </td>

            <td align="left"> </td>

          </tr>

          <tr class="tdodd">

            <td align="left"  ><?php echo PRGMEMBERS_PASSWORD?></td>

            <td align="left" > <input name="txtPassword" type="text" class="input" value="<?php print("$password"); ?>" size="12" maxlength="10">

            </td>

            <td align="left" >&nbsp;</td>

          </tr>

          <tr class="tdodd">

            <td align="left"  ><?php echo GALLERY_NAME ?></td>

            <td colspan="2" align="left" ><?php echo $forename." ".$surname ?></td>

          </tr>

          <tr class="tdodd">

            <td align="left"  ><?php echo GENERAL_JOINDATE ?></td>

            <td colspan="2" align="left" ><?php echo $join_date ?></td>

          </tr>

          <tr class="tdodd">

            <td align="left"  ><?php echo GENERAL_IP ?></td>

            <td colspan="2" align="left" ><?=$ip_address?></td>

          </tr>

         <tr class="tdeven">

            <td align="left"  ><?php echo PRGMEMBERS_SEX?></td>

            <td align="left" > <input name="txtSex" type="text" class="input" size="2" maxlength="1" value="<?php print("$sex"); ?>">

            </td>

            <td align="left" > (<?php echo PRGMEMBERS_M?>) </td>

          </tr>

          <tr class="tdodd">

            <td align="left"  ><?php echo REGISTER_EMAIL?></td>

            <td align="left" ><input class="input" type="text" name="txtEmail" value="<?php echo $email ?>"></td>

            <td align="left" > <?if ($TOTAL > 0) {?><input type='submit' name='submit' value='<?php echo BUTTON_UPDATE ?>' class='button' onClick="FrmCurMember.mode.value='save';"><?}?></td>

          </tr>

          <tr>

            <td colspan="3" align="center" class="tdfoot">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="3" align="left" valign="top" class="tdhead"><?php echo PRGMEMBERS_CAUTION?></td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top">&nbsp;</td>

            <td colspan="2" align="left" valign="top"><?php echo PRGMEMBERS_TEXT?></td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="top">&nbsp;</td>

            <td colspan="2" align="left" valign="top"> <?if ($TOTAL > 0) {?><input type='submit' name='deletemember' value='<?php echo BUTTON_DELETEMEMBER ?>' class='button' onClick="FrmCurMember.mode.value='deleteme'; return delete_alert4(); "> <?}?></td>

          </tr>

<?php if ($suspended == "N") { ?>

          <tr class="tdodd">

            <td align="left" valign="top">&nbsp;</td>

            <td colspan="2" align="left" valign="top"> <?if ($TOTAL > 0) {?><input type='submit' name='suspendmember' value='<?php echo BUTTON_SUSPENDMEMBER ?>' class='button' onClick="FrmCurMember.mode.value='suspendme'; "> <?}?></td>

          </tr>

<?php } else { ?>

          <tr class="tdodd">

            <td align="left" valign="top">&nbsp;</td>

            <td colspan="2" align="left" valign="top"> <?if ($TOTAL > 0) {?><input type='submit' name='reactivatemember' value='<?php echo BUTTON_REACTIVATEMEMBER ?>' class='button' onClick="FrmCurMember.mode.value='reactivateme'; "> <?}?></td>

          </tr>

<?php } ?>

          <tr class="tdodd">

            <td align="left" valign="top">&nbsp;</td>

            <td colspan="2" align="left" valign="top"> <?if ($TOTAL > 0) {?><input type='submit' name='login' value='<?php echo BUTTON_LOGIN ?>' class='button' onClick="FrmCurMember.mode.value='login'; "> <?}?></td>

          </tr>



          <tr>

            <td colspan="3" align="left" valign="top" class="tdfoot"> <center>

              </center></td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>



<script>

    var const_link = "<?=$CONST_LINK_ROOT;?>";

    forma = document.forms['FrmCurMember'];

    var call = new calendar(forma, 'lstExpiredate_year', 'lstExpiredate_month', 'lstExpiredate_day');

    call.year_scroll = true;

    call.time_comp = false;

</script>

<?=$skin->ShowFooter($area)?>