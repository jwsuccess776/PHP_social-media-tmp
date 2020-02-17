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

# Name:     disclaimer.php

#

# Description:  Page containing the member side disclaimer ('Disclaimer')

#

# Version:    7.2

#

######################################################################



include('db_connect.php');

//include('session_handler.inc');

# retrieve the template

if (isset($_SESSION['Sess_UserId'])){

  $area = 'member';

} else {

  $area = 'guest';

}

?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo DISCLAIMER_SECTION_NAME ?></td>

    </tr>

    <tr>

    <td>
      <?php $disclaime = getPageTemplate('aff_disclaimer_text'); ?>
      <?php 
        eval("\$disclaime = \"$disclaime\";");
        echo $disclaime; ?></td> 
    </tr>

  </table>



<?=$skin->ShowFooter($area)?>