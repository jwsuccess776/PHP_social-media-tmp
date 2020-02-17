<?php



include ('../db_connect.php');



include('../session_handler.inc');



include('../functions.php');

include_once('../validation_functions.php');






$payment_id = sanitizeData($_REQUEST['payment_id'], 'xss_clean') ;  



if (!$payment_id) die('Incorrect payment_id');







$query = "SELECT * FROM payments WHERE pay_paymentid = $payment_id";



$res=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());



$payment = mysqli_fetch_object($res);



$payment->pay_params = unserialize($payment->pay_params);







# retrieve the template



switch ($payment->pay_service) {



    case 'premium' : $area = 'member'; break;



    case 'sd_ticket': $area = 'speeddating'; break;



}







$all_pay = get_allow_payments($payment->pay_service,$payment->pay_samount);







$query = "  SELECT *



            FROM payment_service_params



            WHERE psp_service = '$payment->pay_service'";



$res = mysqli_query($globalMysqlConn,$query);



$service = mysqli_fetch_object($res);







?>



<?=$skin->ShowHeader($area)?>



<script>



function send_form() {



    name = document.getElementById("pay_name").value;



    if ("" == name) {



        alert("<?=SELECT_OPERATOR?>");



    }



    else {



        document.forms[name].submit();



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



    <td class="pageheader"><?php echo PAYMENTS_SECTION_NAME?></td>



  </tr>



<?



foreach ($all_pay as $cur_pay) {



    require_once($CONST_INCLUDE_ROOT.'/payments/'.$cur_pay->ps_prefix."_form.php");



}



?>



  <tr>



    <td>



 <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">







    <tr>



          <td class="tdhead" colspan="2"><?php echo PAY_MESSAGE_1 ?></td>



    </tr>



    <tr class="tdodd">



        <td width=30%><?php echo PAY_MESSAGE_2 ?></td>



        <td><?=$CONST_SYMBOL.number_format(round($payment->pay_samount,2), 2);?></td>



    </tr>



    <tr class="tdodd">



        <td><?php echo PAY_MESSAGE_3 ?></td>



        <td>



        <?if (count($all_pay) > 1){?>



            <select id="pay_name" class="inputf">



                <?



                $out = '<option value="">'.SELECT_METHOD;







                foreach ($all_pay as $cur_pay) {



                        $out .= '<option value='.$cur_pay->ps_prefix.'>'.$cur_pay->ps_title;



                }



                echo $out;



                ?>



            </select>



        <?} else {?>



            <?=$cur_pay->ps_title?>



            <input type=hidden id="pay_name" value="<?=$cur_pay->ps_prefix?>">



        <?}?>



        </td>



    </tr>



    <tr>



        <td class="tdfoot" colspan="2" align="center">



            <form style="display:inline;" action="<?php echo $CONST_LINK_ROOT ?>/payments/cancel.php" method="post" name="frmPay" ><input type="hidden" name="pay_id" value="<?php echo $payment_id ?>"><input type="submit" value="Cancel" class="button"></form>&nbsp;



            <input type="button" value="<?=BUTTON_CHECKOUT?>" class="button" onclick="send_form();">



        </td>



    </tr>



    </table>



     </td>



 </tr>



</table>



<?=$skin->ShowFooter($area)?>