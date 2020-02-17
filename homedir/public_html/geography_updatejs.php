<?php
/*****************************************************
* © copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
****************************************************/

######################################################################
#
# Name:         geography_updatejs.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('geography.php');
include('permission.php');

$f = fopen('geography.js', 'w');
fwrite($f, get_geography_js());
fclose($f);
echo 'Success!';
?>