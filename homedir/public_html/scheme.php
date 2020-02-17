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
# Name:         scheme.php
#
# Description:  Displays details of the affiliate scheme
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');

# retrieve the template
if (isset($_SESSION['Sess_UserId']))
    $area = 'member';
else
    $area = 'guest';

$month1 = $option_manager->GetValue('1month');
$month3 = $option_manager->GetValue('3month');
$month6 = $option_manager->GetValue('6month');
$month12 = $option_manager->GetValue('12month');
$initialreferal = $option_manager->GetValue('initialreferal');
$subsequentreferal = $option_manager->GetValue('subsequentreferal');

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">


  <tr>

    <td class="pageheader"><?php echo AFF_SECTION_NAME ?></td>
  </tr>

  <tr>

    <td><?php
    	$scheme = getPageTemplate('scheme_text');
    	$scheme = str_replace('CONST_LINK_ROOT', $GLOBALS['CONST_LINK_ROOT'], $scheme);
    	$scheme = str_replace('CONST_IMAGE_ROOT', $GLOBALS['CONST_IMAGE_ROOT'], $scheme);
    	$scheme = str_replace('CONST_IMAGE_LANG', $GLOBALS['CONST_IMAGE_LANG'], $scheme);
    	$scheme = str_replace('"', "'", $scheme);
    	eval("\$scheme = \"$scheme\";");
     	echo sprintf($scheme,number_format($month1,2),number_format($initialreferal), number_format($month1*($initialreferal/100),2),number_format($month3,2),number_format($initialreferal), number_format($month3*($initialreferal/100),2),number_format($month6,2),number_format($initialreferal),number_format($month6*$initialreferal/100,2),number_format($month12,2),number_format($initialreferal),number_format($month12*$initialreferal/100,2),number_format($month1,2),number_format($subsequentreferal),number_format($month1*$subsequentreferal/100,2),number_format($month3,2),number_format($subsequentreferal),number_format($month3*$subsequentreferal/100,2),number_format($month6,2),number_format($subsequentreferal),number_format($month6*$subsequentreferal/100,2),number_format($month12,2),number_format($subsequentreferal),number_format($month12*$subsequentreferal/100,2)); 
        ?>

    </td>
  </tr>

</table>
<?=$skin->ShowFooter($area)?>