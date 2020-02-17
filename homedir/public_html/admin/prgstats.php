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

# Name: 		prgstats.php

#

# Description:  Displays the demographics of the site

#

# # Version:      8.0

#

######################################################################



include('../db_connect.php');

include('../session_handler.inc');

include('permission.php');



$genders = array('M' => PRGSTATS_MALES, 'F' => PRGSTATS_FEMALES);



# retrieve the template

$area = 'member';



?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right" colspan="4">

		<?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader" colspan="4"><?php echo STATS_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <tr>

          <td align="left" valign="top" class="tdhead"  colspan="4"><?php echo PRGSTATS_AD?></td>

        </tr>

        <tr>

          <td align="left" class="tdodd" colspan="4"><?php echo PRGSTATS_AD_HELP?></td>

        </tr>



        <?php

					echo '

						<tr class="tdodd" >

							<td  align="left">&nbsp;</td>';

								reset($genders);

								foreach ($genders as $key => $value) {

									echo '<td  align="left"><b>'.$value.'</b></td>';

									eval("\$total_count$key = 0;");

								}

								echo '</tr>';



								$genders2 = $genders;

								reset($genders);

								foreach ($genders as $key => $value)

								{

									echo '

										<tr class="tdeven" >



											<td  align="left"><b>'.$value.'</b></td>';

									reset($genders2);
									foreach ($genders2 as $key => $value)
									

									{

										switch($key)

										{

											case 'M': $seek = 'adv_seekmen'; break;

											case 'F': $seek = 'adv_seekwmn'; break;

											default:  $seek = 'adv_seekcpl';

										}

										$retval = mysqli_query($globalMysqlConn,"SELECT COUNT(*) FROM adverts WHERE adv_sex = '$key' AND $seek = 'Y'");

										$count = mysqli_num_rows($retval);

										echo '

												<td  align="left">'.$count.'</td>';

										eval("\$total_count$key += $count;");

									}

									echo '</tr>';

								}



								echo '

									<tr class="tdodd" >



										<td  align="left"><b>'.PRGSTATS_TOTALS.'</b></td>';

								reset($genders);

								foreach ($genders as $key => $value)

								{

									echo '<td  align="left">'.eval("return \$total_count$key;").'</td>';

								}

								echo '</tr>';

				?>

        <tr>

          <td align="left"  valign="top" class="tdfoot" colspan="4">&nbsp;</td>

        </tr>

        <tr>

          <td align="left"  valign="top" class="tdhead" colspan="4"><?php echo PRGSTATS_RPB ?></td>

        </tr>

        <tr>

          <td  valign="top" align="left" class="tdodd" colspan="4"><?php echo PRGSTATS_RPB_HELP ?></td>

        </tr>

        <tr>

          <td align="left" class="tdeven" colspan="4"> <table width="100%"  border="0" cellspacing="$CONST_SUBTABLE_CELLSPACING" cellpadding="3" >

              <tr>

                <td valign="top" align="left" width="20" ></td>

                <td  align="left"></td>

                <td  align="left"><?php echo PRGSTATS_REGISTERED?></td>

                <td  align="left"><?php echo PRGSTATS_AD1?></td>

                <td  align="left"></td>

              </tr>

              <tr>

                <td colspan="2">

                  <?php

							reset($genders);

							$total_reg_count = 0;

							$total_adv_count = 0;

							foreach ($genders as $key => $value)

							{

								echo '

									<tr>

										<td valign="top" align="left"></td>

										<td  align="left"><b>'.$value.'</b></td>';

								$retval = mysqli_query($globalMysqlConn,"SELECT COUNT(*) FROM members WHERE mem_sex = '$key'");

								$reg_count = mysqli_num_rows($retval);

								$retval = mysqli_query($globalMysqlConn,"SELECT COUNT(*) FROM adverts WHERE adv_sex = '$key'");

								$adv_count = mysqli_num_rows($retval);

								echo '

										<td  align="left">'.$reg_count.'</td>

										<td  align="left">'.$adv_count.'</td>

										<td  align="left"></td>

									</tr>';

								$total_reg_count += $reg_count;

								$total_adv_count += $adv_count;

							}

						?>

                </td>

              </tr>

              <tr>

                <td valign="top" align="left" width="20"></td>

                <td align="left" ><b><?php echo PRGSTATS_TOTALS?></b></td>

                <td align="left" >

                  <?=$total_reg_count?>

                </td>

                <td align="left" >

                  <?=$total_adv_count?>

                </td>

                <td align="left" ></td>

              </tr>

            </table></td>

        </tr>

        <tr>

          <td align="left" valign="top" class="tdfoot" colspan="4">&nbsp;</td>

        </tr>

        <tr>

          <td align="left" valign="top" class="tdhead" colspan="4"><?php echo PRGSTATS_AGE?></td>

        </tr>

        <tr>

          <td valign="top" align="left"  class="tdeven" colspan="4"><?php echo PRGSTATS_AGE_HELP?></td>

        </tr>

        <tr>

          <td align="left" valign="top" class="tdodd"  colspan="4">

		  <table width="100%"  border="0" cellspacing="$CONST_SUBTABLE_CELLSPACING" cellpadding="3" >

              <tr>

                <td >&nbsp;</td>

                <td  align="center"><strong>&lt;= 20</strong></td>

                <td  align="center"><strong>21 - 30 </strong></td>

                <td  align="center"><strong>31 - 40 </strong></td>

                <td  align="center"><strong>41 - 50 </strong></td>

                <td  align="center"><strong>51 - 60 </strong></td>

                <td  align="center"><strong>61 - 70 </strong></td>

                <td  align="center"><strong>&gt; 70 </strong></td>

              </tr>

              <?php

					reset($genders);

					foreach ($genders as $key => $value)

					{

						echo'

							<tr>

								<td width="12%" ><strong>'.$value.'</strong></td>';

						for($gender_max = 20; $gender_max <= 80; $gender_max += 10)

						{

							switch($gender_max)

							{

								case 20: $sql_cond = '< 20'; break;

								case 80: $sql_cond = '> 70'; break;

								default: $sql_cond = 'BETWEEN '.($gender_max-9).' AND '.$gender_max; break;

							}

							$query = "SELECT count(*) AS `count` FROM members WHERE (YEAR(CURDATE())-YEAR(mem_dob)) - (RIGHT(CURDATE(),5) < RIGHT(mem_dob,5)) $sql_cond AND mem_sex = '$gender_short'";

							$retval = mysqli_query($globalMysqlConn,$query);

							$result = mysqli_fetch_object($retval);

							$count = mysqli_num_rows($retval);

							echo '<td width="12%"  align="center">'.$count.'</td>';

						}

						echo '</tr>';

					}

				?>

            </table></td>

        </tr>

        <tr>

          <td valign="top" align="left" ></td>

        </tr>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>