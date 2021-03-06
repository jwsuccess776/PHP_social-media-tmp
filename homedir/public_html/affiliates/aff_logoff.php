<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name: 		aff_logoff.php
#
# Description:  destroys affiliate session
#
# # Version:      8.0
#
######################################################################
include('../db_connect.php');
include('aff_session_handler.inc');


// delete the cookie
session_destroy();
header("Location: $CONST_LINK_ROOT/affiliates/index.php");
exit;

?>