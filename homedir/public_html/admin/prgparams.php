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
# Name: 		prgparams.php
#
# Description:  Updates parameters (from params.php)
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');
include('../session_handler.inc');
include('../error.php');
include('../message.php');
include('permission.php');

restrict_demo();

$aOptions = FormGet('options');
$manager = &OptionManager::GetInstance();

foreach ($aOptions as $name => $value){
    $option = $manager->Get($name);
    if ($option->Save($value) === null){
        error_page(join("<br>",$option->error),GENERAL_USER_ERROR);
    }
}
$group= FormGet('group');
header("Location: $CONST_LINK_ROOT/admin/params.php?group=$group");
exit;
?>
