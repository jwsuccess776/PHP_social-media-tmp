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

# Name:                 ext_stories.php

#

# Description:

#

# Version:                7.2

#

######################################################################

$result=mysqli_query($globalMysqlConn,"SELECT * FROM sd_stories LIMIT 3");

ob_start();

if (mysqli_num_rows($result) > 0) {

?>

<?php

while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {

?>

<a class="lm" href="stories_list.php?sd_storyid=<?=$row["sd_storyid"]?>"><?=cutString(addslashes($row["sd_title"]),30) ?></a>

<br />

<?php } ?>

<a href="<?=$CONST_LINK_ROOT?>/speeddating/stories_list.php" class="memlogin"><?=MORE_STORIES?></a>       

<?php

}

else {

    echo NO_STORIES;

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