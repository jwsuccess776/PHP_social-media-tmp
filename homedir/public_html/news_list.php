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

# Name:                 news_list.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('db_connect.php');

include('message.php');

include('error.php');





if (isset($Sess_UserId)) {

	$area = 'member';

}	else {

    $area = 'guest';

}

$query="SELECT * FROM news ORDER BY news_id DESC";

$news = mysqli_query($globalMysqlConn, $query) or die (mysqli_error());





?>

<?=$skin->ShowHeader($area)?>

<script language="javascript">

//<!--

    function changeType(id)

    {

        el = document.getElementById(id);

        if (el.style.display == 'block'){

            document.getElementById(id).style.display='none';

        } else {

            document.getElementById(id).style.display='block';

        }

    }

//-->

</script>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td class="pageheader">

      <?=NEWS_SECTION_NAME?>

    </td>

  </tr>

  <tr>

    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

          <?php  while ($news_row = mysqli_fetch_array($news,MYSQLI_ASSOC)) {  ?>

        <tr>

          <td  align="left" class="tdhead">

         <div><a href="#" onClick="changeType('<?=$news_row["news_id"]?>');return false;"><?=$news_row["title"]?></div>

         </td>

        </tr>

		 <tr>

          <td  align="left"  class="tdodd"><div id=<?=$news_row["news_id"]?> style="display:none"><p><?=nl2br($news_row["body"])?></p></div></td>

        </tr>

		<?php } ?>

        <tr>

          <td valign="top" align="left" >

            <?=$div_str_bottom;?>

          </td>

        </tr>

      </table> </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>