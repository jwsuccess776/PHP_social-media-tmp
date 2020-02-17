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
# Name:                 picture_gallery.php
#
# Description:
#
# Version:                7.3
#
######################################################################
//ADM_STANDART_PICTURE_GALLERY_SECTION_NAME
include('db_connect.php');
//include('session_handler.inc');
//include('error.php');
//include('functions.php');
//include('message.php');

# retrieve the template
$query_avat = "SELECT * FROM avatars AS a
                    INNER JOIN pictures AS p
                        ON (a.pic_id = p.pic_id)
                    WHERE
                        a.avatar_id > 0
              ";

$sql_result = mysql_query($query_avat, $link);

?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Avatar</title>
</head>
<body >
<table  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

    <tr align=left class="tdodd">
    <?php
    $k = 1;
    $size = mysql_num_rows($sql_result);
    while($avatar = mysql_fetch_object($sql_result)) {
        //window.close(self);
        $thumb_pic = $CONST_LINK_ROOT.str_replace("/members/", "/thumbs/large-", $avatar->pic_picture);
    ?>
          <td>
            <a href="#" onClick="window.opener.document.getElementById('avat').value=<?=$avatar->avatar_id?>;window.opener.document.getElementById('avatar').value=<?=$avatar->avatar_id?>;window.opener.document.forms['gallery'].submit();window.close(self);">
                <img src="<?=$thumb_pic?>" border="0">
            </a>
          </td>

    <?php
        $rem = $k%3;
        switch ($rem) {
            case '0':
                if ($k == $size) {
                    echo '</tr>';
                } else {
                    echo '</tr><tr>';
                }
            break;
            case '1':
                if ($k == $size) {
                    echo '<td>&nbsp;</td><td>&nbsp;</td></tr>';
                }
            break;
            case '2':
                if ($k == $size) {
                    echo '<td>&nbsp;</td></tr>';
                }
            break;
        }

        $k++;
    } ?>

	<tr>
      <td align="center" class="tdfoot">&nbsp; </td>
    </tr>
</table>
<?php mysql_close( $link ); ?>
</body>

</html>