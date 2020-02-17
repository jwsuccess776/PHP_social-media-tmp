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

# Name: 		venue_detail.php

#

# Description:

#

# # Version:      8.0

#

######################################################################

include('../db_connect.php');

//include('session_handler.inc');

include('../message.php');

include('../functions.php');



include('../error.php');

# retrieve the template

$area = 'speeddating';



$result = mysqli_query($globalMysqlConn,"SELECT * FROM sd_venues LEFT JOIN sd_venue_pic ON (vnp_venueid = vnu_venueid)

LEFT JOIN geo_country ON (vnu_countryid = gcn_countryid)

LEFT JOIN geo_state ON (vnu_stateid = gst_stateid)

LEFT JOIN geo_city ON (vnu_cityid = gct_cityid)

WHERE vnu_venueid = '$sde_venueid'", $link);

$selected_venue = mysqli_fetch_object($result);



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>



    <td class="pageheader">

      <?=SPEED_VENUES_DETAILS_NAME?>

    </td>

    </tr>

    <tr>

      <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <tr>

          <td  colspan="2" align="left" class="tdhead">&nbsp;</td>

        </tr>

        <tr class="tdodd">

          <td align="left">

            <?=ADM_PRGVENUES_VENUE?>

          </td>

          <td  colspan="3" align="left" valign="middle" >

            <?= htmlspecialchars($selected_venue->vnu_name) ?>

          </td>

        </tr>

        <tr class="tdeven">

          <td height="19" align="left" valign="middle"   >

            <?=GENERAL_COUNTRY?>

          </td>

          <td  colspan="3" align="left" valign="middle" >

            <?= htmlspecialchars($selected_venue->gcn_name) ?>

          </td>

        </tr>

        <tr class="tdodd">

          <td align="left" valign="middle"   >

            <?=GENERAL_STATE?>

          </td>

          <td  colspan="3" align="left" valign="middle" >

            <?= htmlspecialchars($selected_venue->gst_name) ?>

          </td>

        </tr>

        <tr class="tdeven">

          <td align="left" valign="middle"   >

            <?=GENERAL_CITY?>

          </td>

          <td  colspan="3" align="left" valign="middle" >

            <?= htmlspecialchars($selected_venue->gct_name) ?>

          </td>

        </tr>

        <tr class="tdodd">

          <td align="left" valign="middle"   >

            <?=ADM_PRGVENUES_ADDRESS?>

          </td>

          <td  colspan="3" align="left" valign="middle" >

            <?= htmlspecialchars($selected_venue->vnu_address) ?>

          </td>

        </tr>

        <tr class="tdeven">

          <td align="left" valign="middle"   >

            <?=ADM_PRGVENUES_PHONE?></td>

          <td  colspan="3" align="left" valign="middle" >

            <?= htmlspecialchars($selected_venue->vnu_phone) ?>

          </td>

        </tr>

        <tr class="tdodd">

          <td align="left" valign="middle"   >

            <?=ADM_PRGVENUES_URL?>

          </td>

          <td  colspan="3" align="left" valign="middle" >

            <a href="<?=$selected_venue->vnu_website?>" target=_blank><?= htmlspecialchars($selected_venue->vnu_website) ?></a>

          </td>

        </tr>

        <tr class="tdeven">

          <td align="left" valign="middle"   >

            <?=ADM_PRGVENUES_PICTURE?>

          </td>

          <td  colspan="3" align="left" valign="middle" >

            <?php

                if(isset($selected_venue))

                {

                    $filename = "venues/$selected_venue->vnu_venueid.jpg";

                    $pictureurl = "venues/$selected_venue->vnu_venueid.jpg";;

                    if(file_exists($filename))

                    {

                        srand((double) microtime() * 1000000);

                        ?>

            <img src="<?=$pictureurl?>?<?=$selected_venue->vnp_id?>" height="100">

            <br>

            <?php

                    }

                }

                ?>

          </td>

        </tr>

        <tr class="tdodd">

          <td align="left" valign="middle"   >

            <?=ADM_PRGVENUES_DESC?>

          </td>

          <td  colspan="3" align="left" valign="middle" >

            <?= htmlspecialchars($selected_venue->vnu_description) ?>

          </td>

        </tr>

        <tr class="tdeven">

          <td align="left" valign="middle"   >

            <?=ADM_PRGVENUES_DIR?>

          </td>

          <td  colspan="3" align="left" valign="middle" >

            <?= htmlspecialchars($selected_venue->vnu_directions) ?>

          </td>

        </tr>

        <tr class="tdodd">

          <td align="left" valign="middle"   >

            <?=ADM_PRGVENUES_MAP?>

          </td>

          <td  colspan="3" align="left" valign="middle" >

            <a href="<?=$selected_venue->vnu_map?>" target=_blank><?= htmlspecialchars($selected_venue->vnu_map) ?></a>

          </td>

        </tr>

        <tr>

          <td colspan="4" align="left" valign="middle" class="tdfoot"   >&nbsp;</td>

        </tr>

      </table></td>

    </tr>

  </table>



<?php //mysql_close( $link ); ?>

<?=$skin->ShowFooter($area)?>

