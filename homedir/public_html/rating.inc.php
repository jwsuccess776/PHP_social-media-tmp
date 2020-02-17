<?php
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
# Name:         rating.inc.php
#
# Description:  Sends offer mails to people who have not visited for a while
#
# Version:      7.2
#
######################################################################
    # rating code
    if ($CONST_RATING=='Y') {
        $rate_query="SELECT * FROM ratedby WHERE rtb_userid=$userid AND rtb_raterid=$Sess_UserId";
        $result=mysqli_query($conStingru,$rate_query) or die(mysqli_error());
        if (mysqli_num_rows($result) > 0 || ($userid == $Sess_UserId)) {
            $sql_rating=mysqli_fetch_object($result);
            $vote=$sql_rating->rtb_vote;
            $rate_query="SELECT * FROM ratedby WHERE rtb_userid=$userid ORDER BY rtb_vote DESC LIMIT 1";
            $result=mysqli_query($conStingru,$rate_query) or die(mysqli_error());
            $sql_rating=mysqli_fetch_object($result);
            $high_vote=$sql_rating->rtb_vote;
            $rate_query="SELECT * FROM ratings WHERE rte_userid=$userid";
            $result=mysqli_query($conStingru,$rate_query) or die(mysqli_error());
            $sql_rating=mysqli_fetch_object($result);
            print("<table width='100%'  border='0' cellpadding='$CONST_SUBTABLE_CELLPADDING' cellspacing='$CONST_SUBTABLE_CELLSPACING'>
              <tr>
                <td colspan='4' align='left' valign='middle' class='tdhead'>&nbsp;</td>
              </tr>
              <tr class='tdodd'>
                <td width='25%' valign='middle'><b>".PRGRETUSER_YOU_RATED."</b></td>
                <td width='25%' valign='middle' align='left'>"); $idx=0; while ($idx!=$vote)
                  { print("<img src='$CONST_IMAGE_ROOT"."star.gif'>"); $idx++; } print("</td>
                <td width='25%' valign='middle'><b>".PRGRETUSER_HIGHEST_SCORE."</b></td>
                <td width='25%' valign='middle' align='left'>"); $idx=0; while ($idx!=$high_vote)
                  { print("<img src='$CONST_IMAGE_ROOT"."star.gif'>"); $idx++; } print("</td>
              </tr>
              <tr class='tdeven'>
                <td width='25%' valign='middle'><b>".PRGRETUSER_AVERAGE_SCORE."</b></td>
                <td width='25%' valign='middle' align='left'>$sql_rating->rte_average</td>
                <td width='25%' valign='middle'><b>".PRGRETUSER_VOTES."</b></td>
                <td width='25%' valign='middle' align='left'>$sql_rating->rte_votes</td>
              </tr>
              <tr >
                <td colspan='4' width='50%' valign='middle' align='center'  class='tdfoot'>
                  <input type='button' class='button' onClick=\"javascript:MDM_openWindow('$CONST_LINK_ROOT/prgtop10.php?sex=F','".PRGRETUSER_PHOTOGRAPH."','width=280,height=400')\" value='".PRGRETUSER_TOP_TEN_WOMEN."'>
                  &nbsp;
                  <input type='button' class='button' onClick=\"javascript:MDM_openWindow('$CONST_LINK_ROOT/prgtop10.php?sex=M','".PRGRETUSER_PHOTOGRAPH."','width=280,height=400')\" value='".PRGRETUSER_TOP_TEN_MEN."'>
<!--
                  &nbsp;
                  <input type='button' class='button' onClick=\"javascript:MDM_openWindow('$CONST_LINK_ROOT/prgtop10.php?sex=C','".PRGRETUSER_PHOTOGRAPH."','width=280,height=400')\" value='".PRGRETUSER_TOP_TEN_COUPLES."'>
-->
                </td>
              </tr>
            </table>");
        } else {
            print("<table width='100%'  border='0' cellpadding='$CONST_SUBTABLE_CELLPADDING' cellspacing='$CONST_SUBTABLE_CELLSPACING'>
                 <form method='POST' action='$CONST_LINK_ROOT/prgrate.php?userid=$userid'>  <tr>
                      <td  class='tdhead' colspan='12'>
                ".PRGRETUSER_RATE_MY_PICTURE."</td>
                    </tr>
                    <tr class='tdodd'>
                      <td  align='center'><img border='0' src='$CONST_IMAGE_ROOT"."sad_smile.gif'></td>
                      <td  align='center'>
                        <input type='radio' value=1 name='vote'>
                        </td>
                      <td  align='center'>1</td>
                      <td  align='center'>
                        <input type='radio' value=2 name='vote'>
                        </td>
                      <td  align='center'>2</td>
                      <td  align='center'>
                        <input type='radio' value=3 name='vote' checked>
                        </td>
                      <td  align='center'>3</td>
                      <td  align='center'>
                        <input type='radio' value=4 name='vote'>
                        </td>
                      <td  align='center'>4</td>
                      <td  align='center'>
                        <input type='radio' value=5 name='vote'>
                        </td>
                      <td  align='center'>5</td>
                      <td  align='center'><img border='0' src='$CONST_IMAGE_ROOT"."regular_smile.gif'></td>
                    </tr>
                    <tr>
                      <td  align='center' colspan='12' class='tdfoot'>
                <p align='center'>
                          <input type='submit' value='".PRGRETUSER_RATE_NOW."' name='btnSubmit' class='button'>
                      </td>
                    </tr></form>
                  </table>");
        }
    }
?>