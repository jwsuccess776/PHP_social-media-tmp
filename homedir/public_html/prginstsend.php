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
# Name: 		prginstsend.php
#
# Description:
#
# # Version:      8.0
#
######################################################################

session_cache_limiter('nocache, must-revalidate');
session_start();

include ('../db_connect.inc');

$txtMessage = preg_replace ("/\n/", "<BR>", $txtMessage);
$txtMessage = preg_replace ("/\n\n/", "<P>", $txtMessage);

$txtMessage = preg_replace ("/:-)/", "<img border='0' src='smilies/smile.jpg' width='19' height='19'>", $txtMessage);
$txtMessage = preg_replace ("/:-D/", "<img border='0' src='smilies/laugh.jpg' width='19' height='19'>", $txtMessage);
$txtMessage = preg_replace ("/:-P/", "<img border='0' src='smilies/tongue.jpg' width='19' height='19'>", $txtMessage);
$txtMessage = preg_replace ("/:-O/", "<img border='0' src='smilies/surprise.jpg' width='19' height='19'>", $txtMessage);
$txtMessage = preg_replace ("/;-)/", "<img border='0' src='smilies/wink.jpg' width='19' height='19'>", $txtMessage);
$txtMessage = preg_replace ("/:-S/", "<img border='0' src='smilies/queer.jpg' width='19' height='19'>", $txtMessage);
$txtMessage = preg_replace ("/\(H)/", "<img border='0' src='smilies/cool.jpg' width='19' height='19'>", $txtMessage);
$txtMessage = preg_replace ("/:-\|/", "<img border='0' src='smilies/shock.jpg' width='19' height='19'>", $txtMessage);
$txtMessage = preg_replace ("/:\*/", "<img border='0' src='smilies/bashful.jpg' width='19' height='19'>", $txtMessage);
$txtMessage = preg_replace ("/:-\(/", "<img border='0' src='smilies/sad.jpg' width='19' height='19'>", $txtMessage);
$txtMessage = preg_replace ("/:\'\(/", "<img border='0' src='smilies/cry.jpg' width='19' height='19'>", $txtMessage);
$txtMessage = preg_replace ("/:-@/", "<img border='0' src='smilies/angry.jpg' width='19' height='19'>", $txtMessage);

$txtMessage=addslashes($txtMessage);
$query="INSERT INTO imessage (ims_message, ims_senderid ,ims_sendername, ims_recipientid) VALUES ('$txtMessage', $Sess_UserId, '$Sess_UserName', $send_to)";
$retval=mysql_query($query,$link) or die(mysql_error()+$query);

header ("Location: instsend.php?send_to=$send_to");

?>
