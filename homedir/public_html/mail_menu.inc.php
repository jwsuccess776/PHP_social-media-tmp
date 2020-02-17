 <div id="mail"> 
  <ul id="mailnav">
    <li><a href='<?php echo $CONST_LINK_ROOT?>/myemail.php' <?if ($SCRIPT_NAME == 'myemail.php') {?>id='current'<?}?>> 
      <?php echo MYEMAIL_INBOX_SECTION_NAME ?></a></li>
    <li><a href='<?php echo $CONST_LINK_ROOT?>/mysentmail.php' <?if ($SCRIPT_NAME == 'mysentmail.php') {?>id='current'<?}?>> 
      <?php echo MYEMAIL_SENT_SECTION_NAME ?></a></li>
    <li><a href='<?php echo $CONST_LINK_ROOT?>/mysendmail.php' <?if ($SCRIPT_NAME == 'mysendmail.php') {?>id='current'<?}?>> 
      <?php echo MYEMAIL_COMPOSE_SECTION_NAME ?></a></li>
    <li><a href='<?php echo $CONST_LINK_ROOT?>/mydelmail.php' <?if ($SCRIPT_NAME == 'mydelmail.php') {?>id='current'<?}?>> 
      <?php echo  MYEMAIL_TRASH_SECTION_NAME ?></a></li>
  </ul>
</div>

