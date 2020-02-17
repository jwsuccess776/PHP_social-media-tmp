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
# Name:         langswitcher.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################
    $aLanguages = $language->GetActiveList();
    $switcher = '';
    $req_uri=$_SERVER['REQUEST_URI'];
	if (strpos($req_uri,'lang_id=')) $req_uri=substr($req_uri,0,-11);
	$req_ext=(strpos($req_uri,'?'))? '&lang_id=' : '?lang_id=';
	$req_urlc=$req_uri.$req_ext;
	if (count($aLanguages)>1)
        foreach($aLanguages as $row){
            $switcher .= "<a href='$req_urlc".$row->LangID."'><img src='".CONST_IMAGE_ROOT."icons/flags/$row->ImageName' alt='$row->lang_name' border=0></a>&nbsp;";
        }
    return   $switcher;


?>