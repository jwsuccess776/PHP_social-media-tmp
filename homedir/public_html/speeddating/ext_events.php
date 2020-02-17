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
# Name:                 ext_event.php
#
# Description:
#
# Version:                7.2
#
######################################################################

/*$result=mysql_query("SELECT sde_eventid ,sde_name 
                       FROM sd_events 
                      WHERE sde_is_special = 'yes' && 
                            sde_date > NOW()
                   ORDER BY sde_date ASC");
$genetaredEvents=array();
while ($row = mysql_fetch_array($result,MYSQL_ASSOC))
    $genetaredEvents[]="<a href=\"$CONST_LINK_ROOT/speeddating/event_info.php?sde_eventid=".$row[sde_eventid]."\" class=\"lm\">".$row["sde_name"]."</a><br>";

if (count($genetaredEvents))
    $genetaredEvents=implode("",$genetaredEvents);
else
    $genetaredEvents=SD_INDEX_NO_SPECIAL;

return $genetaredEvents;*/
?>