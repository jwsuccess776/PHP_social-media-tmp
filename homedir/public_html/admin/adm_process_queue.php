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
# Name:         report_payments.php
#
# Description:  destroys affiliate session
#
# Version:      7.2
#
######################################################################

include('../db_connect.php');
include('../session_handler.inc');
include('permission.php');

# retrieve the template
$area = 'member';

?>

<?=$skin->ShowHeader($area)?>
<script src="<?=CONST_LINK_ROOT?>/moo.ajax/prototype.lite.js"></script>
<script src="<?=CONST_LINK_ROOT?>/moo.ajax/moo.ajax.js"></script>

<script language=javascript>
function start_process(){
	process();
	document.getElementById('progress').style.display = '';
	document.getElementById('finish').style.display = 'none';
	document.getElementById('result').innerHTML = 0;
}

var timeoutID;
function process(){
    timeoutID = setTimeout("process()", 1000*60*document.getElementById('period').value);
    new ajax(
            '<?=$CONST_ADMIN_LINK_ROOT?>/cron_mail_queue.php?>',
            {
                method: 'post',
                postBody: 'count='+document.getElementById('portion').value,
                onComplete: function (transport) {
                    if (transport.responseText != '') {
                        try {
                            if (transport.responseText.indexOf("END") != -1) {
	                            clearTimeout(timeoutID);
	                            document.getElementById('finish').style.display='';
	                            document.getElementById('progress').style.display='none';
                            } else {
	                            document.getElementById('result').innerHTML = document.getElementById('result').innerHTML*1 + parseInt(transport.responseText);
                            }
                        } catch(E) {
                            var a=1;
                        }
                    }
                }
            }
        );
}
</script>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
		<?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>
    <td class="pageheader"><?=ADM_MAIL_PROCESS_SECTION_NAME?></td>
    </tr>
   <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
   <tr>
      <td><table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <form method='post' action='<?php echo $CONST_ADMIN_LINK_ROOT ?>/adm_process_queue.php' name=''>
          <tr>
            <td colspan="5" align='left' valign='top' class="tdhead">&nbsp;</td>
          </tr>
          <tr class="tdtoprow">
            <td colspan=3 align='left' valign='top' class="tdodd"> <b>
				<?=QUEUE_SEND?> <select class="inputf" size="1" id="portion">
              <?for ($i=1;$i<=500;$i=$i+5){?>
                <option value="<?=$i?>"><?=$i?></option>
               <?}?>
              </select>
              <?=QUEUE_NUMBER?>
              <select class="inputf" size="1" id="period">
              <?for ($i=1;$i<=60;$i+=5){?>
                <option value="<?=$i?>"><?=$i?></option>
               <?}?>
              </select> <?=QUEUE_MINUTE?>
              </b> </td>
            <td align='left' valign='top' class="tdodd"> <input name="btnSubmit" type="button" onClick="start_process();" class="button" value="Start process">
            </td>
          </tr>
          <tr >
            <td colspan="5" align='center' valign='top'>&nbsp;</td>
          </tr>
          <tr >
            <td colspan="5" align='center' valign='top' class="pageheader">
				<?=QUEUE_PROCESSED?>:
				<span id="result"></span>&nbsp;
				<span id="finish" style="display:none;"><?=QUEUE_FINISHED?></span>
				<div id="progress" style="display:none;"><img src="<?=$CONST_IMAGE_ROOT?>/progress.gif" align="absmiddle" border=0></div>

			</td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>

