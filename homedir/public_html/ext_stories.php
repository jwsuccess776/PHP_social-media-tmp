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
# Name:         ext_stories.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################


$result=mysql_query("SELECT * FROM sd_stories LIMIT 3");
ob_start();
if (mysql_num_rows($result) > 0) {
?>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="0">
<?php
while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
?>
              <tr>
                <td align="left" class="tdbotwh">
                 <div id="story_title">
                    <a class="lm" href="stories_list.php?sd_storyid=<?=$row["sd_storyid"]?>"><?=cutString(addslashes($row["sd_title"]),30) ?></a>
                 </div>
              </td>
              </tr>
<?php }

?>
              <tr>
                <td align="left" class="tdbotwh">
                    <a href="<?=$CONST_LINK_ROOT?>/speeddating/stories_list.php" class="memlogin">More Stories</a>
                </td>
              </tr>
            </table>
<?php
}
$content = ob_get_contents();
ob_end_clean();

return $content;

function cutString($string,$length,$end="...") {
    if (strlen($string) > $length)
        $string=substr($string,0,strrpos(substr($string,0,$length)," ")).$end;
    return $string;
}
?>