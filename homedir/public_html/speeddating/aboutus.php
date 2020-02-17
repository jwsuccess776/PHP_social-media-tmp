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

# Name:                 aboutus.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('session_handler.inc');

include('../message.php');



//preparing for paging

include('../error.php');



# retrieve the template

$area = 'speeddating';



?>

<?=$skin->ShowHeader($area)?>



<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td class="pageheader">

        <?=ABOUT_SECTION_NAME?>

      </td>

    </tr>

    <tr>

      <td>

            <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <tr>

          <td height="20" align="left" class="tdhead" >&nbsp;</td>

        </tr>

        <tr>

          <td height="20" align="left" valign="top" class="tdodd">

            <?=$div_str_bottom;?>

          </td>

        </tr>

        <tr>

          <td height="20" align="left" class="tdeven" >Some about us information

          </td>

        </tr>

        <tr>

          <td height="20" align="left" class="tdfoot">&nbsp;</td>

        </tr>

      </table>

      </td>

    </tr>

  </table>



<?php //mysql_close( $link ); ?>

<?=$skin->ShowFooter($area)?>