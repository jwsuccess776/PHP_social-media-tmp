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

# Name:              adm_mailtemplates_edit

#

# Description:

#

# Version:              7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../functions.php');

include('../message.php');

include('../error.php');

include('permission.php');



$Name = FormGet('Name');

$LANG_ID = FormGet('LANG_ID');

$Type = FormGet('Type');

if ($Type=="text/html") {

	$Body = FormGet('BodyHTML');

}

else {

	$Body = FormGet('BodyPLAIN');

}

$act = FormGet('act');


$mtm = new MTemplateManager;
$mailtemplate = $mtm->getInstance();

$m_template = $mailtemplate->Get($Name);



if($act == 'save') {

    restrict_demo();

    $res = $m_template->Save($Body,$Type,$LANG_ID);

    if ($res===null ) {

        error_page(join("<br>",$m_template->error),GENERAL_USER_ERROR);

    } else {

         header("Location: $CONST_LINK_ROOT/admin/adm_mailtemplates.php");

        exit;

    }

}

# retrieve the template

$area = 'member';



?>

<?=$skin->ShowHeader($area)?>

<script>

	function GetFCK(fckeditorvar)

	{

		try {

		// Get the editor instance that we want to interact with.

		var oEditor = FCKeditorAPI.GetInstance(fckeditorvar) ;

		return oEditor;

		}

		catch(e) {

			return false;

		}

	}

	function setVisibleFCK(name,mode) {

		var plainBody=document.getElementById("plainMailTempl");

		var oFCK= GetFCK(name);

		if ( mode ) {

			document.getElementById(name+"___Frame").style.display="";



		}

		else {

			document.getElementById(name+"___Frame").style.display="none";

		}



	}

	function showBodyMailTempl(name,value) {

		var plainBody=document.getElementById("plainMailTempl");

		var htmlBody=document.getElementById("htmlMailTempl");

		if (value=="text/html") {

			plainBody.style.display="none";

			htmlBody.style.display="";

		}

		else {

			plainBody.style.display="";

			htmlBody.style.display="none";

		}

	}

</script>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

    <td class="pageheader">

      <?= ADM_MAILTEMPLATES_EDIT_SECTION_NAME?>

    </td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_mailtemplates_edit.php" enctype="multipart/form-data">

          <input type="hidden" name="act" value="save">

          <input type="hidden" name="Name" value="<?=$Name?>">

          <input type="hidden" name="LANG_ID" value="<?=$LANG_ID?>">

          <tr>

            <td align="center">

    	    <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

                <tr>

                  <td colspan="2" class="tdhead">&nbsp;</td>

                </tr>

                <tr class="tdodd">

                  <td>

                    <?=ADM_MAILTEMPLATES_DESCRIPTION?>

                  </td>

                  <td height="25"> <?= $m_template->comments?></td>

                </tr>

                <tr class="tdodd">

                  <td>

                    <?=ADM_MAILTEMPLATES_VARIABLES?>

                  </td>

                  <td height="25"> <?= join(", ",$m_template->config)?></td>

                </tr>

                <tr class="tdodd">

                  <td>

                    <?=ADM_MAILTEMPLATES_TYPE?>

                  </td>

                  <td height="25">

                  <select name="Type" class="input" onchange="showBodyMailTempl('BodyHTML',this.value);">

                    <option value="text/html" <? if ($m_template->type == "text/html"){?>selected<?}?>>Text/Html

                    <option value="text/plain" <? if ($m_template->type == "text/plain"){?>selected<?}?>>Text/Plain

                  </select>

                  </td>

                </tr>

                <tr class="tdeven" id="htmlMailTempl">

                  <td>

                    <?=ADM_MAILTEMPLATES_MESSAGE?>

                  </td>

                  <td>

	                  		<?

								//$fck = createFCKEditor( "additional_images", 'BodyHTML', $m_template->value[$LANG_ID] , 'mailTempl', 500, 390); //html_entity_decode(stripslashes($news->body))

								//$fck->Create() ;

	                  		?>

                        <textarea name="BodyPLAIN" id="editor_mailtemp_edit1"><?php echo $m_template->value[$LANG_ID]; ?></textarea>

	               </td>

	             </tr>

                <tr class="tdeven" id="plainMailTempl">

                  <td>

                    <?=ADM_MAILTEMPLATES_MESSAGE?>

                  </td>

                  <td>

		                  	<!-- <textarea style="width=500px;" name="BodyPLAIN" cols="80" rows="15" wrap="soft" class="inputl"><?php //echo $m_template->value[$LANG_ID]; ?></textarea> -->

                        <textarea name="BodyPLAIN" id="editor_mailtemp_edit2"><?php echo $m_template->value[$LANG_ID]; ?></textarea>

	               </td>

	             </tr>

		               <? if ($m_template->type!="text/html") { ?>

		               	<script>

							var htmlBody=document.getElementById("htmlMailTempl");

//							htmlBody.style.visibility="hidden";

//							htmlBody.style.zIndex="-1";

							htmlBody.style.display="none";

		               	</script>

		               <? } ?>

		               <? if ($m_template->type!="text/plain") { ?>

		               	<script>

							var plainBody=document.getElementById("plainMailTempl");

//							plainBody.style.visibility="hidden";

//							plainBody.style.zIndex="-1";

							plainBody.style.display="none";

		               	</script>

		               <? } ?>

                <tr>

                  <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class="button" value="<?=GENERAL_SAVE?>">

                    <input name="button" type="button" class="button" onclick="window.location = '<?=$CONST_LINK_ROOT?>/admin/adm_mailtemplates.php'" value="<?=GENERAL_CANCEL?>">

                  </td>

                </tr>

              </table></td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>

<script>

//	var htmlBody=document.getElementById("htmlMailTempl");

//	htmlBody.style.display="none";

</script>
<script>
    window.onload = function() {
        CKEDITOR.replace( 'editor_mailtemp_edit1' );
        CKEDITOR.replace( 'editor_mailtemp_edit2' );
    };
</script>
<?=$skin->ShowFooter($area)?>

