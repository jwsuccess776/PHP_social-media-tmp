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

# Name:                 dating_tips.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('session_handler.inc');

include('../message.php');

include('error.php');



if (!isset($_REQUEST['step'])) $step = 'start';



# retrieve the template

$area = 'speeddating';



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

   <tr>

    <td class="pageheader">

      <?=SD_TIPS_SECTION_NAME?>

    </td>

  </tr>

  <tr>

    <td>

	<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <tr>

          <td align="left" class="tdhead" >&nbsp;</td>

        </tr>

        <tr>

          <td class="tdodd"> <a href="<?=$CONST_LINK_ROOT?>/speeddating/dating_tips.php?step=1">Step1</a><img src="<?=$CONST_IMAGE_ROOT?>/<?=$CONST_IMAGE_LANG?>/step_arrow.gif" vspace="2">

            <a href="<?=$CONST_LINK_ROOT?>/speeddating/dating_tips.php?step=2">Step

            2</a><img src="<?=$CONST_IMAGE_ROOT?>/<?=$CONST_IMAGE_LANG?>/step_arrow.gif" vspace="2"><a href="<?=$CONST_LINK_ROOT?>/speeddating/dating_tips.php?step=3">Step

            3</a> </td>

        </tr>

        <tr>

          <td align="left" valign="top" class="tdeven" > <p>

              <?include "dating_tips_$step.inc.php"?>

            </p></td>

        </tr>

        <tr>

          <td align="left" valign="top" class="tdfoot" >&nbsp;</td>

        </tr>

      </table></td>

  </tr>

</table>



<?php //mysql_close( $link ); ?>

<?=$skin->ShowFooter($area)?>

