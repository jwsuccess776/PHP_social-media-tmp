<div id="mail"> 
  <ul id="mailnav">
    <li><a href='<?php echo $CONST_BLOG_LINK_ROOT?>/myaddblog.php' <?if ($SCRIPT_NAME == 'myaddblog.php') {?>id='current'<?}?>><?php echo MYADDBLOGS_SECTION_NAME ?></a></li>
    <li><a href='<?php echo $CONST_BLOG_LINK_ROOT?>/myblogs.php' <?if ($SCRIPT_NAME == 'myblogs.php') {?>id='current'<?}?>><?php echo MYBLOGS_SECTION_NAME ?></a></li>
  </ul>
</div>