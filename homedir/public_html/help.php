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

# Name: 		help.php

#

# Description:  Member side help page ('Help')

#

# # Version:      8.0

#

######################################################################



include('db_connect.php');

include('session_handler.inc');



# retrieve the template

	$area = 'member';





?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo HELP_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><?php $help_text = getPageTemplate('help_text')?>
         <?php 
        eval("\$help_text = \"$help_text\";");
        echo $help_text; ?>      

    </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>