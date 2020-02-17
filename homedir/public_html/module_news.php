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
# Name:                 module_news.php
#
# Description:  Home page destination for traffic sent by affiliates
#
# Version:               7.2
#
######################################################################
$query="SELECT * FROM news";
$result=mysql_query($query,$link) or die(mysql_error());

$out = '<marquee height="40" scrolldelay="50" scrollamount="1" direction="up" loop="-1">';

while ($cur_news = mysql_fetch_object($result)) {
//    print_r($cur_news);
    $out .= '<b>'.$cur_news->title.'</b><p>'.$cur_news->body.'</p>';
}

$out .= '</marquee>';

echo $out;

?>