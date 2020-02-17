<div id="filter"></div>
<div id="box">
		<form method="post" action="<?php echo $CONST_LINK_ROOT?>/prglogin.php" name="FrmLogin" onSubmit="return Validate_FrmLogin()">
  <div id="boxtitle"></div>
		<table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
          <tr >
            <td colspan="3" align="right"><img src="<?=$CONST_LINK_ROOT?>/lightbox/images/x_close.gif" onclick="closebox('box')" hspace="1" vspace="1" style="cursor:pointer;"></td>
          </tr>
          <?php if ($is_speeddating) { ?>
          <input type="hidden" name="speeddating" value="1">
          <?php } ?>
          <tr >
            <td colspan="3" align="left" class="tdhead"><?php echo LOGIN_IF_YOU_MEMBER_HEAD ?>&nbsp;<a href="<?php echo $CONST_LINK_ROOT?>/register.php" tabindex="5"><?php echo LOGIN_CLICK_HERE ?></a>
            </td>
          </tr>
          <tr >
            <td align="left" class="tdodd"><?php echo LOGIN_USERNAME?></td>
            <td colspan="2" align="left" class="tdodd" > <input type="text" class="input" name="txtHandle" size="20" tabindex="1" value="<?=$_COOKIE[txtHandle_c]?>">
            </td>
          </tr>
          <tr >
            <td align="left" class="tdeven"><?php echo LOGIN_PASSWORD ?></td>
            <td colspan="2" align="left" class="tdeven" > <input name="txtPassword" type="password" class="input" tabindex="2" value="<?=$_COOKIE[txtPassword_c]?>" size="20">
            </td>
          </tr>
          <tr >
            <td colspan="3" align="left" class="tdodd"><?php echo LOGIN_LOG_AUTOMATICALY ?>
              <input type=checkbox name="save"<?php if(isset($_COOKIE[txtHandle_c])) echo ' checked' ?>>
            </td>
          </tr>
          <tr >
            <td colspan="3" align="center" class="tdfoot"> <input name="submit" type='submit' class="button"  value='<?= BUTTON_LOGIN ?>'>
            </td>
          </tr>
          <tr >
            <td colspan="3" align="left" ><?php echo LOGIN_LOST_PASSWORD_HEAD ?>
              <a href="#" onClick="closebox('box'); openbox('', 1, 'box2')"><br>
              <?php echo LOGIN_CLICK_HERE ?></a> <?php echo LOGIN_LOST_PASSWORD_TAIL ?>
            </td>
          </tr>
      </table> 
		</form>
</div>
<div id="box2">
		<form method="post" action="<?php echo $CONST_LINK_ROOT?>/prgresend.php" name="FrmResend">
  <div id="boxtitle"></div>
		<table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" style="vertical-align:middle ">
          <tr >
            <td colspan="3" align="right"><img src="<?=$CONST_LINK_ROOT?>/lightbox/images/x_close.gif" onclick="closebox('box2')" hspace="1" vspace="1" style="cursor:pointer;"></td>
          </tr>
          <tr >
            <td colspan="3" align="left">&nbsp;</td>
          </tr>
          <tr class="tdodd" >
            <td>
              <?=RESEND_EMAIL?>
            </td>
            <td> <input type="text" class="input" name="txtEmail" size="28" tabindex="1">
              <a href="javascript:MDM_openWindow('<?php echo $CONST_LINK_ROOT?>/help/hresend1.php','Help','width=250,height=375')"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?php echo $CONST_IMAGE_LANG ?>/help_but.gif'></a></td>
          </tr>
          <tr>
            <td colspan="2" align="center" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_RESEND ?>" class="button"></td>
          </tr>
          <tr>
            <td colspan="2" align="left"><?=RESEND_NOTE?></td>
          </tr>
		  </table> 
		</form>
</div>
