<?php
include ('../db_connect.php');
include('../session_handler.inc');
include_once('../validation_functions.php');


$payment_id = sanitizeData($_REQUEST['payment_id'], 'xss_clean') ;  
if (!$payment_id) die('Incorrect payment_id');
?>
<html>
<head>
</head>
<body>
<form name='frmPayPost' method='POST' action='<?php echo $CONST_LINK_ROOT ?>/payments/payment.php'>
		<input type='hidden' name='payment_id' value='<?php echo $payment_id ?>'>
</form>
<script language="javascript">
 document.forms['frmPayPost'].submit();
 </script>
</body>
</html>     
