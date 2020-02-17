<? if ($option_manager->GetValue('sms') =='Y') { 
	if ($sql_array->mem_sms == 1 && !empty($sql_array->mem_mobile) && $sql_array->mem_carrier > 0){
?>
	<a href='#' onClick="window.open('<?=$CONST_LINK_ROOT?>/send_sms.php?userid=<?=$sql_array->mem_userid?>','','toolbar=no,menubar=no,height=160,width=320,left='+(screen.width/2-160)+',top='+(screen.height/2-80)+'');return false;" title="<?=PRGRETUSER_TEXT11?>"><img src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/sendsms.gif' hspace="2" border='0' align=absmiddle></a>
<?  } ?>
<? } ?>
