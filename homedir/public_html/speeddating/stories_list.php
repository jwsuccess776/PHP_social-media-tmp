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

# Name:                 listbox.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('message.php');

include('error.php');

if (isset($Sess_UserId)) {

$area = 'speeddating';

}	else {

$area = 'speeddating';

}

$query="SELECT * FROM sd_stories";

$stories = mysqli_query($globalMysqlConn,$query) or die (mysqli_error());

?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td class="pageheader">

      <?=STORIES_SECTION_NAME?>

    </td>

  </tr>

  <tr>

    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <?php

                while ($story = mysqli_fetch_array($stories,MYSQLI_ASSOC)) {

            ?>

        <tr class="tdhead">

          <td colspan="3" class="tdhead">

            <?=$story["sd_title"]?>

          </td>

        </tr>

        <tr>

          <td  align="left" class="tdodd">

            <?=nl2br($story["sd_body"])?>

            &nbsp; </td>

          <td  align="right"  class="tdodd">

            <?if (file_exists($CONST_INCLUDE_ROOT."/stories/story_".$story["sd_storyid"].".gif")){?>

            <br> <img src="<?=$CONST_LINK_ROOT?>/stories/story_<?=$story["sd_storyid"]?>.gif" width="<?=$CONST_STORYIMAGE_WIDTH?>" height="<?=$CONST_STORYIMAGE_HEIGHT?>" border="0" vspace="5"  alt="">

            <?php } ?>

            &nbsp; </td>

        </tr>

        <tr >

          <td colspan="3" class="tdfoot">&nbsp; </td>

        </tr>

        <?php } ?>

        <td valign="top" align="left"  COLSPAN="3">

          <?=$div_str_bottom;?>

        </td>

        </tr>

      </table></td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>

