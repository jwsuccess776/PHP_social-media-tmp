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
# Name:         aff_session_handler.php
#
# Description:  authorise affiliate
#
# Version:      7.2
#
######################################################################

//session_cache_limiter('private, must-revalidate');
session_start();

if (!isset($_SESSION['Sess_AffUserId'])) {
	header("Location: $CONST_LINK_ROOT/affiliates/index.php");
	exit;
}

$Sess_AffUserId=$_SESSION['Sess_AffUserId']; 

?>