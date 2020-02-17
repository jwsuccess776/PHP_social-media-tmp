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
# Name:                 prem_func.inc.php
#
# Description:  Home page destination for traffic sent by affiliates
#
# Version:               7.2
#
######################################################################
$uri = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$conSting=@mysqli_connect(__CONST_DB_HOST,__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME);
$sql_query = "
        SELECT * FROM premium_func
        WHERE
                prf_uri = '".mysqli_escape_string($conSting,str_replace($CONST_LINK_ROOT, '', $uri))."'
                AND prf_active = 1";
$sql_result_prf = mysqli_query($conSting,$sql_query);
if(mysqli_num_rows($sql_result_prf) > 0 && $Sess_Userlevel!="gold" && $CONST_FREE != true)
{
		$func = mysqli_fetch_object($sql_result_prf);
        if($func->prf_app == 0)
        {
                include_once($CONST_INCLUDE_ROOT.'/prem_message.php');
                prem_page(PREMIUM_FUNC_ERROR_PAGE1,PREMIUM_FUNC_ERROR_PAGE2,GENERAL_USER_ERROR);
        }
        else
        {
                ?>
                <html>
                <head>
                        <script language="javascript">
                                function showError()
                                {
                                        window.alert('<?=str_replace("'", "\'", PREMIUM_FUNC_ERROR_MSG)?>');
                                        <?php if($func->prf_app == 1) { ?>
                                        window.close();
                                        <?php } else { ?>
                                        window.history.back(1);
                                        <?php } ?>
                                }
                        </script>
                </head>
                <body onload="showError()"></body>
                </html>
                <?php
                exit;
        }
}
?>