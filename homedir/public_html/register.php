<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:                 register.php
#
# Description:  member registration form
#
# Version:               7.5
#
######################################################################
require_once ( 'db_connect.php' );
require_once ( 'pop_lists.inc' );
require_once ( 'functions.php' );
require_once ( __INCLUDE_CLASS_PATH . '/securityImageClass.php' );
include_once 'validation_functions.php';

$si = new securityImage();
$rows=($option_manager->GetValue('skype'))?12:10;

//Upload Standart Image
if (isset($_POST['avat'])) {
    $avatar_id =sanitizeData(trim($_POST['avat']), 'xss_clean');   
} elseif (isset($_SESSION['post']['avatar'])) {
    $avatar_id = $_SESSION['post']['avatar'];
}
//*Upload Standart Image

# retrieve the template
$area = 'guest';
?>

<?=$skin->ShowHeader($area)?>
<?php if ($CONST_AVATARS_GALLERY == "Y") {?>
    <form action="<?php echo $CONST_LINK_ROOT?>/register.php" name="gallery" method="post">
        <input type="hidden" name="avat" id="avat" value="">
    </form>
<?php 

}?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    </tr>
    <tr>

    <td class="pageheader"><?php echo REGISTER_SECTION_NAME ?></td>
    </tr>
    <tr>
      <td>
<script language=javascript>
    country = getCookie('lstCountry');
    state = getCookie('lstState');
    city = getCookie('lstCity');
</script>
<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <form method="post" enctype='multipart/form-data' action="<?php echo $CONST_LINK_ROOT?>/prgregister.php?mode=create" name="FrmRegister" onSubmit="return Validate_FrmRegister('create')" >
          <?php if ($CONST_AVATARS_GALLERY == "Y") {?>
          <input type="hidden" name="avatar" id="avatar" value="<?=$avatar_id?>">
          <?php } ?>
          <tr >
            <td colspan="4" align="left" ><b><?php echo REGISTER_IF_YOU_MEMBER?></b>
              <a href="<?php echo $CONST_LINK_ROOT?>/login.php"><?php echo REGISTER_LOG_IN_HERE?></a></td>
          </tr>
          <tr >
            <td colspan="4" align="left" class="join_head"><?=ADVERTISE_MESSAGE1?></td>
          </tr>
          <tr class="tdodd" >
            <td width="20%"  align="left" class="tdodd"><?php echo REGISTER_USERNAME?>:  <span class=mandatory>*</span></td>
            <td width="20%"  colspan="2" align="left" class="tdodd"> <input type="text"  name="txtHandle" size="20" maxlength='25' class="input" value="<?=$_SESSION['post']['txtHandle'];?>" >
              <a href="javascript:MDM_openWindow('<?php echo $CONST_LINK_ROOT?>/help/hregister1.php','<?php echo REGISTER_HELP?>','width=250,height=375')"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?= $CONST_IMAGE_LANG ?>/help_but.gif'></a>
            </td>
             
                <td width="50%" colspan="1" rowspan="<?php echo $rows ?>" align="right" valign="top" class="tdcontent">
                    <img src="<?php echo $CONST_LINK_ROOT?>/images/register-page-graphic.jpg"/>
                </td>
          </tr>
          <tr >
            <td  align="left" class="tdeven"><?php echo REGISTER_PASSWORD?>  <span class=mandatory>*</span></td>
            <td colspan="2"  align="left" class="tdeven"> <input name="txtPassword" type="password" class="input" id="txtPassword" size="20" maxlength="10" value="<?=$_SESSION['post']['txtPassword'];?>" >
            </td>
          </tr>
          <tr >
            <td  align="left" class="tdodd"><?php echo REGISTER_CONFIRM?>  <span class=mandatory>*</span></td>
            <td colspan="2"  align="left" class="tdodd"> <input name="txtConfirm" type="password" class="input" id="txtConfirm" size="20" maxlength="10" value="<?=$_SESSION['post']['txtConfirm'];?>" >
            </td>
          </tr>
          <tr >
            <td  align="left" class="tdeven"><?php echo REGISTER_LAST_NAME?>  <span class=mandatory>*</span></td>
            <td colspan="2"  align="left" class="tdeven"> <input type="text" class="input" name="txtSurname" size="20" maxlength='25' value="<?=$_SESSION['post']['txtSurname'];?>" >
            </td>
          </tr>
          <tr >
            <td  align="left" class="tdodd"><?php echo REGISTER_FIRST_NAME?>  <span class=mandatory>*</span></td>
            <td colspan="2"  align="left" class="tdodd"> <input type="text" class="input" name="txtForename" size="20" maxlength='25' value="<?=$_SESSION['post']['txtForename'];?>" >
            </td>
          </tr>
          <tr >
            <td  align="left" class="tdeven"><?php echo REGISTER_BIRTHDAY?>  <span class=mandatory>*</span></td>
            <td colspan="2"  align="left" class="tdeven"> <select class="inputf" size="1" name="lstDay" style="width:auto;" >
                <option selected>...</option>
                <?
                $out = "";
                for ($i=1; $i<=31; $i++) {
                    $cur_i = sprintf("%02d", $i);
                    $selected =  ($cur_i == $_SESSION['post']['lstDay']) ? " SELECTED" : "";
                    echo '<option  value="'.$cur_i.'" '.$selected.'>'.$cur_i.'</option>';
                }
                echo $out;
                ?>
              </select>
              -
              <select class="inputf" size="1" name="lstMonth" style="width:auto;" >
                <option selected>...</option>
                <option value="01" <?php if('01'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_JAN?></option>
                <option value="02" <?php if('02'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_FEB?></option>
                <option value="03" <?php if('03'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_MAR?></option>
                <option value="04" <?php if('04'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_APR?></option>
                <option value="05" <?php if('05'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_MAY?></option>
                <option value="06" <?php if('06'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_JUN?></option>
                <option value="07" <?php if('07'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_JUL?></option>
                <option value="08" <?php if('08'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_AUG?></option>
                <option value="09" <?php if('09'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_SEP?></option>
                <option value="10" <?php if('10'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_OCT?></option>
                <option value="11" <?php if('11'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_NOV?></option>
                <option value="12" <?php if('12'==$_SESSION['post']['lstMonth']) {echo "SELECTED";}?>><?php echo MONTH_DEC?></option>
              </select>

              <select name="txtYear" class="inputf" size="1" style="width:auto;" >
                <option selected>...</option>
			  <?php
			  	for ($count=date("Y")-18; $count >= 1900; $count--) { ?>
					<option value='<?=$count?>' <?php if($_SESSION['post']['txtYear']==$count) echo 'selected'; ?>)><?=$count?></option>
				<?php }  ?>
			  </select>
          </tr>
          <tr >
            <td  align="left" class="tdodd"><?php echo REGISTER_SEX?>  <span class=mandatory>*</span></td>
            <td colspan="2"  align="left" class="tdodd"> <select class="input" size="1" name="lstSex" >
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <option value="M" <?php if('M'==$_SESSION['post']['lstSex']) {echo " SELECTED";}?>><?php echo SEX_MALE ?></option>
                <option value="F" <?php if('F'==$_SESSION['post']['lstSex']) {echo " SELECTED";}?>><?php echo SEX_FEMALE ?></option>
              </select> </td>
          </tr>
          <tr >
            <td  align="left" class="tdeven"><?php echo REGISTER_EMAIL?>  <span class=mandatory>*</span></td>
            <td colspan="2"  align="left" class="tdeven"> <input type="text" class="input" name="txtEmail" size="25" maxlength='70' value="<?=$_SESSION['post']['txtEmail'];?>"  >
            </td>
          </tr>
              <!-- SKYPE -->
<?php if ($option_manager->GetValue('skype')){?>
          <tr >
            <td align="left" class="tdodd"><?php echo SKYPE_NAME ?></td>
            <td colspan="2"  align="left" class="tdodd"> <input type="text" class="input" name="txtSkypename" size="20" maxlength='45' value="<?=$_SESSION['post']['txtSkypename'];?>" >
            </td>
          </tr>
          <tr >
            <td align="left" class="tdeven"><?php echo SKYPE_SETTINGS ?></td>
            <td colspan="2"  align="left" class="tdeven">
              <select class="input" name="lstSkypeSettings" id="lstSkypeSettings" size="1"  style="width:auto;">
                <option value="0" >- <?php echo GENERAL_CHOOSE?> -</option>
                <option value="ALL" <?php if('ALL'==$_SESSION['post']['lstSkypeSettings']) {echo " SELECTED";}?>><?php echo SKYPE_ALL ?></option>
                <option value="HOTLIST" <?php if('HOTLIST'==$_SESSION['post']['lstSkypeSettings']) {echo " SELECTED";}?>><?php echo SKYPE_HOTLIST ?></option>
              </select>
            </td>
          </tr>
              <?php } ?>
              <!-- SKYPE -->
              <!-- SMS -->
<?php if ($option_manager->GetValue('sms')=='Y'){
include_once __INCLUDE_CLASS_PATH."/class.SMS.php";
?>
          <tr >
            <td align="left" class="tdodd"><?php echo MOBILE_PHONE?></td>
            <td colspan="2"  align="left" class="tdodd"> <input type="text" class="input" name="txtMobile" size="20" maxlength='45' value="<?=$_SESSION['post']['txtMobile'];?>" >
            </td>
          </tr>
          <tr >
            <td align="left" class="tdeven"><?php echo SMS_CARRIER?></td>
            <td colspan="2"  align="left" class="tdeven">
              <select class="input" name="lstSmsCarrier" id="lstSmsCarrier" size="1"  style="width:auto;">
                <option value="0" >- <?php echo GENERAL_CHOOSE?> -</option>
<?php
                $SMSLink= new SMS();
                $listA=$SMSLink->getList(new stdClass);
                    //foreach (SMS::getList(new stdClass) as $sms)
                foreach ($listA as $sms)
    { ?>
                <option value="<?=$sms->id?>" <?php if($sms->id==$_SESSION['post']['lstSmsCarrier']) {echo " SELECTED";}?>><?=$sms->title?></option>
<?php 

}
?>
              </select>
            </td>
          </tr>
              <?php 
              
}
?>
              <!-- SMS -->
          <tr >
            <td  align="left" class="tdodd"><?php echo REGISTER_NEWSLETER?></td>
            <td colspan="2"  align="left" class="tdodd"> <input type="checkbox" name="chkNews" value="1" <?php if(!isset($_SESSION['post']) || isset($_SESSION['post']['chkNews'])) {echo " CHECKED";}?>  >
            </td>
          </tr>
          <tr >
            <td  align="left" class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/disclaimer.php" target="_blank"><?php echo REGISTER_DISCLAIMER?></a></td>
            <td colspan="2"  align="left" class="tdeven"> <input type="checkbox" name="chkDisclaimer" value="1" <?php if(isset($_SESSION['post']['chkDisclaimer'])) {echo " CHECKED";}?>  >
              <?php echo REGISTER_I_AGREE?></td>
          </tr>
          <tr>
            <td colspan="4"  align="left" class="tdfoot">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4"  align="left" class="join_head"><?php echo ADVERTISE_MESSAGE2?>
            </td>
          </tr>
          <?php if ($GEOGRAPHY_JAVASCRIPT) {
                 if ($GEOGRAPHY_AJAX) { ?>
<script src="<?=CONST_LINK_ROOT?>/moo.ajax/moo.ajax.js"></script>
<script src="<?=CONST_LINK_ROOT?>/ajax_lib.js.php"></script>
          <tr class="tdodd" >
            <td  align="left"><?php echo GENERAL_COUNTRY?>  <span class=mandatory>*</span></td>
            <td > <select class="input" name="lstCountry" id="lstCountry" size="1"   onchange="sendStateRequest(this.options[this.selectedIndex].value);sendCityRequest(this.options[this.selectedIndex].value,0); return false;">
                <option value="0" selected>-- <?php echo GENERAL_CHOOSE?> --</option>
                <option value=""></option>
<?php
    include_once __INCLUDE_CLASS_PATH."/class.Geography.php";
    $GeographyLink=new Geography();
    $CountriesList=$GeographyLink->getCountriesList();
    //$CountriesList = Geography::getCountriesList();
    foreach ($CountriesList as $countryrow)
    {
        echo '<option value="'.$countryrow->gcn_countryid.'">'.htmlspecialchars($countryrow->gcn_name).'</option>';
    }
?>
              </select> </td>
            <td  align="left"><?php echo GENERAL_STATE?>  <span class=mandatory>*</span></td>
            <td >
              <select disabled class="input" name="lstState" id="lstState" size="1"   onchange="sendCityRequest(document.getElementById('lstCountry').value,this.value); return false;">
                <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
              </select>
            </td>
          </tr>
          <tr class="tdeven" >
            <td  align="left"><?php echo GENERAL_CITY?>  <span class=mandatory>*</span></td>
            <td colspan="3" >
              <select disabled class="input" name="lstCity" id="lstCity" size="1"   >
                <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
              </select>
            </td>
          </tr>
<script language="javascript">
function initialize(SelectedCountry, SelectedState, SelectedCity)
{
    var lstCountry = new getObj('lstCountry').obj;
    if(SelectedCountry)
    {
        for(iOption = 0; iOption < lstCountry.options.length; iOption++)
            if(lstCountry.options[iOption].value == SelectedCountry) {
                lstCountry.options[iOption].selected = true;
            }
        sendStateRequest(SelectedCountry,SelectedState);
        sendCityRequest(SelectedCountry,SelectedState,SelectedCity);
    }
}

initialize(country, state, city);
</script>
              <?php } else { ?>
          <script language="javascript" src="geography.js"></script>
          <tr class="tdodd" >
            <td  align="left"><?php echo GENERAL_COUNTRY?>  <span class=mandatory>*</span></td>
            <td > <select class="input" name="lstCountry" id="lstCountry" size="1"   onchange="onCountryListChange('FrmRegister', 'lstCountry', 'lstState', 'lstCity');">
                <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <option value=""></option>
              </select> </td>
            <td  align="left"><?php echo GENERAL_STATE?>  <span class=mandatory>*</span></td>
            <td > <select class="input" name="lstState" id="lstState" size="1"   onchange="onStateListChange('FrmRegister', 'lstCountry', 'lstState', 'lstCity');">
                <option value="0" selected></option>
              </select> </td>
          </tr>
          <tr class="tdeven" >
            <td  align="left"><?php echo GENERAL_CITY?> <span class=mandatory>*</span></td>
            <td colspan="3" > <select class="input" name="lstCity" id="lstCity" size="1"    onchange="onCityListChange('FrmRegister', 'lstCity');">
                <option value="0" selected></option>
              </select> </td>
          </tr>
          <script language="javascript">
                        initialize('FrmRegister', 'lstCountry', 'lstState', 'lstCity', new Array(country), new Array(state), new Array(city));
          </script>
              <?php } ?>
          <?php } else { ?>
          <tr class="tdodd" >
            <td  align="left"><?php echo GENERAL_COUNTRY?>/<?php echo GENERAL_STATE?>  <span class=mandatory>*</span></td>
            <td > <select class="input" name="lstCountry" id="lstCountry" size="1"  style="width:auto;">
                <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?= country_state_list($_SESSION['post']['lstCountryState']);?>
              </select> </td>
            <td  align="left"><?php echo GENERAL_CITY?>  <span class=mandatory>*</span></td>
            <td > <input type=text class="input" name="txtLocation" value="<?=$_SESSION['post']['txtLocation']?>"  >
            </td>
          </tr>
          <?php } ?>
          <?php
        if ($CONST_ZIPCODES=='Y') {
          print("<tr class='tdeven'>
        <td  align='left'>".ADVERTISE_ZIPCODE."</td>
        <td ><input type='text' name='txtZipcode' size='15' maxlength='5'  class='input' value='".$_SESSION['post']['txtZipcode']."'></td><td colspan='2'>(".ADVERTISE_USA.")</td>
      </tr>");
          }
?>
          <tr class="tdodd" >
            <td  align="left" nowrap><?php echo ADVERTISE_SEEKING?></td>
            <td > <select name="lstSeeking" size="1" class="input" >
                <?php populate_lists('SKG','base','adv',$_SESSION['post']['lstSeeking']); ?>
              </select></td>
            <td  align="left" nowrap><?php echo ADVERTISE_BODY_TYPE?></td>
            <td > <select name="lstBodyType" size="1" class="input" >
                <?php populate_lists('BDY','base','adv',$_SESSION['post']['lstBodyType']); ?>
              </select></td>
          </tr>
          <tr class="tdeven" >
            <td  align="left" nowrap><?php echo ADVERTISE_HEIGHT?></td>
            <td > <select name="lstHeight" size="1" class="input" >
                <option value="Not stated"><?php echo GENERAL_NOT_STATE?></option>
                <?
                $out = "";
                $prev = "";
                for ($cm=122; $cm<=230; $cm++) {
                    $in_inches = round($cm/2.54);
                    $in_feets = floor($cm/30.48);
                    if ($in_inches-$in_feets*12 == 12) $in_feets++;
                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";
                    $selected = ($cm == $_SESSION['post']['lstHeight']) ? " SELECTED" : "";
                    if ($prev != $cur_i) $out .= '<option value="'.$cm.'"'.$selected.'>'.$cur_i.'('.$cm.ADVERTISE_CM.')'.'</option><br>';
                    $prev = $cur_i;
                }
                echo $out;
                ?>
              </select></td>
            <td  align="left" nowrap><?php echo ADVERTISE_CHILDREN?></td>
            <td > <select name="lstChildren" size="1" class="input" >
                <?php populate_lists('CHL','base','adv',$_SESSION['post']['lstChildren']); ?>
              </select></td>
          </tr>
          <tr class="tdodd" >
            <td  align="left" nowrap><?php echo ADVERTISE_EYE?></td>
            <td > <select class="input" name="lstEyecolor" size="1" >
                <?php populate_lists('EYE','base','adv',$_SESSION['post']['lstEyecolor']); ?>
              </select></td>
            <td  align="left" nowrap><?php echo ADVERTISE_HAIR?></td>
            <td > <select class="input" name="lstHaircolor" size="1" >
                <?php populate_lists('HAR','base','adv',$_SESSION['post']['lstHaircolor']); ?>
              </select></td>
          </tr>
          <tr class="tdodd" >
            <td  align="left" nowrap><?php echo ADVERTISE_SMOKER?></td>
            <td > <select name="lstSmoker" size="1" class="input" >
                <?php populate_lists('SMK','base','adv',$_SESSION['post']['lstSmoker']); ?>
              </select></td>
            <td  align="left" nowrap><?php echo ADVERTISE_RELIGION?></td>
            <td > <select name="lstReligion" size="1" class="input" >
                <?php populate_lists('RLG','base','adv',$_SESSION['post']['lstReligion']); ?>
              </select></td>
          </tr>
          <tr class="tdeven" >
            <td  align="left" nowrap><?php echo ADVERTISE_MARITAL?></td>
            <td > <select name="lstMarital" size="1" class="input" >
                <?php populate_lists('MRT','base','adv',$_SESSION['post']['lstMarital']); ?>
              </select></td>
            <td  align="left" nowrap><?php echo ADVERTISE_ETHN?></td>
            <td > <select name="lstEthnicity" size="1" class="input" >
                <?php populate_lists('ETH','base','adv',$_SESSION['post']['lstEthnicity']); ?>
              </select></td>
          </tr>
          <tr class="tdodd" >
            <td  align="left" nowrap><?php echo ADVERTISE_EDU?></td>
            <td > <select class="input" name="lstEducation" size="1" >
                <?php populate_lists('EDU','base','adv',$_SESSION['post']['lstEducation']); ?>
              </select></td>
            <td  align="left" nowrap><?php echo ADVERTISE_EPL?></td>
            <td > <select class="input" name="lstEmployment" size="1" >
                <?php populate_lists('EMP','base','adv',$_SESSION['post']['lstEmployment']); ?>
              </select></td>
          </tr>
          <tr class="tdeven" >
            <td  align="left" nowrap><?php echo ADVERTISE_INCOME?></td>
            <td > <select class="input" name="lstIncome" size="1" >
                <?php populate_lists('INC','base','adv',$_SESSION['post']['lstIncome']); ?>
              </select></td>
            <td  align="left" nowrap><?php echo ADVERTISE_DRINKING?></td>
            <td > <select class="input" name="lstDrink" size="1" >
                <?php populate_lists('DNK','base','adv',$_SESSION['post']['lstDrink']); ?>
              </select></td>
          </tr>
          <tr class="tdodd" >
            <td align="left"><?=SEX?> <span class=mandatory>*</span></td>
            <td colspan="3"><input type="checkbox" name="chkSeekmen" value="men"  <?php if('men'==$_SESSION['post']['chkSeekmen']) {echo " CHECKED";}?>>
              <?php echo ADVERTISE_SEEK_M?>&nbsp; <input type="checkbox" name="chkSeekwmn" value="wmn"  <?php if('wmn'==$_SESSION['post']['chkSeekwmn']) {echo " CHECKED";}?>>
              <?php echo ADVERTISE_SEEK_W?>&nbsp; <input type="hidden" name="chkSeekcpl" value="cpl"  <?php if('cpl'==$_SESSION['post']['chkSeekcpl']) {echo " CHECKED";}?>>
            </td>
          </tr>
          <tr >
            <td colspan="4" class="tdfoot" >&nbsp;</td>
          </tr>
          <tr >
            <td colspan="4" class="join_head" ><?php echo ADVERTISE_MESSAGE3?></td>
          </tr>
          <tr class="tdeven" >
            <td align="left" ><?php echo ADVERTISE_TITLE?> <span class=mandatory>*</span></td>
            <td colspan="3" align="left"> <input type="text" class="inputl" name="txtTitle" size="30"  maxlength='30' value="<?=$_SESSION['post']['txtTitle'];?>">
            </td>
          </tr>
          <tr class="tdodd" >
            <td align="left" valign="top"><?php echo ADVERTISE_MES?> <span class=mandatory>*</span>
              <p><I><?php echo ADVERTISE_MES_DESC?></I> <br>
                <br>
                <em> </em></p></td>
            <td colspan="3" align="left"> <textarea  class="inputl" rows="10" name="txtComment" cols="59"  onKeyDown="textCounter(this.form.txtComment,this.form.remLentext);" onKeyUp="textCounter(this.form.txtComment,this.form.remLentext);"><?=$_SESSION['post']['txtComment'];?></textarea>
            </td>
          </tr>
          <tr class="tdodd" >
            <td align="left" valign="top" >&nbsp;</td>
            <td colspan="3" align="left"><em>
              <input type="box" readonly name="remLentext" size="5" value="0" class="inputf" >
              <?php echo ADVERTISE_TYPED?></em></td>
          </tr>
          <tr >
            <td colspan="4" class="tdfoot" >&nbsp;</td>
          </tr>
          <tr >
            <td colspan="4" class="join_head" ><?=ADVERTISE_MESSAGE4?></td>
          </tr>
          <tr class="tdodd">
            <td ><?php echo PRGRETUSER_GENDER?></td>
            <td>
              <?
            ?>
              <select name="lstMySex" size="1" class="input"  >
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <option value="M" <?php if('M'==$_SESSION['post']['lstMySex']) {echo " SELECTED";}?>><?php echo SEX_MALE ?></option>
                <option value="F" <?php if('F'==$_SESSION['post']['lstMySex']) {echo " SELECTED";}?>><?php echo SEX_FEMALE ?></option>
              </select></td>
            <td>
              <?=PRGRETUSER_AGES?>
            </td>
            <td> <select name="txtMyFromAge" size="1" class="inputf"  >
                <?php
                        for ($i=18; $i < 100; $i++) {
                          $selected = ($i == $_SESSION['post']['txtMyFromAge']) ? " SELECTED" : "";
                          print("<option value='$i' $selected>$i</option>");
                        }
                ?>
              </select>
              -
              <select class="inputf" size="1" name="txtMyToAge"  >
                <?php
                          for ($i=18; $i < 100; $i++) {
                            if (isset($_SESSION['post']['txtMyToAge'])) {
                          $selected = ($i == $_SESSION['post']['txtMyToAge']) ? " SELECTED" : "";
                            }
                            else {
                                $selected = ($i == 99) ? " SELECTED" : "";
                            }

                          print("<option value='$i' $selected>$i</option>");
                        }
                ?>
              </select></td>
          </tr>
          <tr class="tdeven">
            <td >
              <?=PRGRETUSER_HEIGHT?>
            </td>
            <td colspan="3"> <select class="inputs" name="lstMyMinHeight" size="1" >
                <?
                $out = "";
                $prev = "";
                for ($cm=122; $cm<=232; $cm++) {
                    $in_inches = round($cm/2.54);
                    $in_feets = floor($cm/30.48);
                    if ($in_inches-$in_feets*12 == 12) $in_feets++;
                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";
                    $selected = ($cm == $_SESSION['post']['lstMyMinHeight']) ? " SELECTED" : "";
                    if ($prev != $cur_i) $out .= '<option value="'.$cm.'"'.$selected.'>'.$cur_i.'('.$cm.ADVERTISE_CM.')'.'</option><br>';
                    $prev = $cur_i;
                }
                echo $out;
                ?>
              </select>
              -
              <select class="inputs" name="lstMyMaxHeight" size="1">
                <?
                $out = "";
                $prev = "";
                for ($cm=122; $cm<=230; $cm++) {
                    $in_inches = round($cm/2.54);
                    $in_feets = floor($cm/30.48);
                    if ($in_inches-$in_feets*12 == 12) $in_feets++;
                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";
                    if (isset($_SESSION['post']['lstMyMaxHeight'])) {
                        $selected = ($cm == $_SESSION['post']['lstMyMaxHeight']) ? " SELECTED" : "";
                    }
                    else {
                        $selected = ($cm == 230) ? " SELECTED" : "";
                    }
                    if ($prev != $cur_i) $out .= '<option value="'.$cm.'"'.$selected.'>'.$cur_i.'('.$cm.ADVERTISE_CM.')'.'</option><br>';
                    $prev = $cur_i;
                }
                echo $out;
                ?>
              </select> </td>
          </tr>
          <tr class="tdodd">
            <td >
              <?=PRGRETUSER_SMOKER?>
            </td>
            <td > <select name="lstMySmoker" size="1" class="input" >
                <option value="- Any -" selected>
                <?=SEARCH_ANY?>
                </option>
                <?php populate_lists('SMK','base','adv',$_SESSION['post']['lstMySmoker']); ?>
              </select></td>
            <td >
              <?=PRGRETUSER_BODYTYPE?>
            </td>
            <td > <select class="input" name="lstMyBodyType" size="1" >
                <option value="- Any -" selected>
                <?=SEARCH_ANY?>
                </option>
                <?php populate_lists('BDY','base','adv',$_SESSION['post']['lstMyBodyType']); ?>
              </select> </td>
          </tr>
          <tr class="tdeven">
            <td >
              <?=PRGRETUSER_RELATIONSHIP?>
            </td>
            <td colspan="3"> <select class="input" name="lstMySeeking" size="1" >
                <option value="- Any -" selected>
                <?=SEARCH_ANY?>
                </option>
                <?php populate_lists('SKG','base','adv',$_SESSION['post']['lstMySeeking']); ?>
              </select> </td>
          </tr>
          <tr class="tdodd">
            <td  valign="top">
              <?=PRGRETUSER_COMMENT?>
            </td>
            <td colspan="3"> <textarea  class="inputl"rows="10" name="txtMyComment" cols="59" ><?=$_SESSION['post']['txtMyComment'];?></textarea>
            </td>
          </tr>
		  <?php if ($SECURITY_REGISTRATION){?>
          <tr>
            <td align="left" class="tdodd"> <span class=mandatory>*</span>
              <?=REGISTER_SECURITY?></td>
             <td colspan="3" ><input type="text" class="input" name="security" size="5" maxlength='5' >
              <?
                $time = time();
                ?>
              <img border=0 align=absmiddle src="<?=$CONST_LINK_ROOT?>/s_image.php?<?=$time?>">
            </td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="4" align="center" class="tdfoot"><input type="submit" name="Submit" value="<?php echo BUTTON_REGNOW ?>" class="button">
            </td>
          </tr>
        </form>
      </table>

</td>
    </tr>
  </table>
<script language="Javascript">
//    alert(document.getElementById('avat').value);
//    alert(document.getElementById('avatar').value);
//    document.forms['gallery'].submit();
</script>
<?=$skin->ShowFooter($area)?>