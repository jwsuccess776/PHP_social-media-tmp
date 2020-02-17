<?php
$account = '';
foreach(get_payment_params('ibill', $payment->pay_service) as $param)
{
	if(substr($param->psp_name, 0, strlen('ACCOUNT')) == 'ACCOUNT')
	{
		$values = explode(';', $param->psp_value);
		if($values[1])
			if( ((int)($values[1])) == ((int)$payment->pay_samount) )
			{
				$account = $values[0];
				break;
			}
	}
}
?>
<?php if ($service->psp_type == 'onetime'){?>
    <form action="https://secure.ibill.com/cgi-win/ccard/ccard.exe" method="POST" name="ibill">
    <input type="hidden" name="REQTYPE" value="secure">
    <input type="hidden" name="ACCOUNT" value="<?=$account?>">
    <input type="hidden" name="REF1" value="<?=$payment->pay_paymentid?>">
    </form>
<?php } elseif ($service->psp_type == 'recurring') {?>
    <form action="https://secure.ibill.com/cgi-win/ccard/ccard.exe" method="POST" name="ibill">
    <input type="hidden" name="REQTYPE" value="secure">
    <input type="hidden" name="ACCOUNT" value="<?=$account?>">
    <input type="hidden" name="REF1" value="<?=$payment->pay_paymentid?>">
    </form>
<?php } ?>    