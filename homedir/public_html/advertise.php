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
# Name:                 advertise.php
#
# Description:  Members create advert page ('Place Advert')
#
# Version:                7.2
#
######################################################################

include('db_connect.php');
include_once('validation_functions.php'); 
include('session_handler.inc');
include('functions.php');
include('pop_lists.inc');
# retrieve the template
if(isset($_SESSION['Sess_JustRegistered']))
        $area = 'guest';
else
        $area = 'member';

if (isset($_POST['avat'])) {
    $avatar_id = sanitizeData($_POST['avat'], 'xss_clean');    
} elseif (isset($_SESSION['post']['avatar'])) {
    $avatar_id = $_SESSION['post']['avatar'];
}

$conSting=@mysqli_connect(__CONST_DB_HOST,__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME);

# check no advert exists
$query="SELECT adv_userid FROM adverts WHERE adv_userid = '$Sess_UserId'";
$retval=mysqli_query($conSting,$query) or die(mysqli_error());
$result=mysqli_num_rows($retval);
# if advert exists go to amend page
if ($result > 0) {
        header("Location: $CONST_LINK_ROOT/prgamendad.php");
        exit;
}
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo ADVERTISE_SECTION_NAME ?></td>
  </tr>

  <tr>
    <td>
        <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form  enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/prgadvertise.php' name="FrmAdvert" onSubmit="return Validate_FrmAdvert('create')">
          <input type="hidden" name="mode" value="create">
                  <tr >
                      <td colspan="4" align="left" class="tdhead"><?php  echo 'ADVERTISE_MESSAGE'?></td>
          </tr>
          <?php if ($GEOGRAPHY_JAVASCRIPT) {
                 if ($GEOGRAPHY_AJAX) { ?>
<script src="<?=CONST_LINK_ROOT?>/moo.ajax/prototype.lite.js"></script>
<script src="<?=CONST_LINK_ROOT?>/moo.ajax/moo.ajax.js"></script>
<script type="text/javascript">

function getObj(name){
  if (document.getElementById)
  {
      this.obj = document.getElementById(name);
    this.style = document.getElementById(name).style;
  }
  else if (document.all)
  {
    this.obj = document.all[name];
    this.style = document.all[name].style;
  }
  else if (document.layers)
  {
       this.obj = document.layers[name];
       this.style = document.layers[name];
  }
}

// To avoid DOM incompatibilities in IE4.

function sendStateRequest(countryID, selectedStateID){
  //  var stateList = new getObj('stateList').obj;
    var lstState = new getObj('lstState').obj;
    lstState.options.length = 0;
    var objOption = new Option('--- Loading... ---',0);
    lstState.options.add(objOption);
    lstState.disabled=true;
   
    new ajax(
            '<?=CONST_LINK_ROOT?>/ajax_state.php',
            {
                method: 'post',
                postBody: 'mode=0&countryID='+countryID+'&selectedStateID='+selectedStateID,
               // update: stateList,
                onComplete: function (transport) {
                    if (transport.responseText == '') {
                        lstCity.options.length = 0;
                        var objOption = new Option('--- Select Country ---',0);
                        lstCity.options.add(objOption);
                        lstCity.disabled=true;
                    }
                    else
                    {
                         if (transport.responseText == '') {
        lstState.options.length = 0;
        var objOption = new Option('--- Select Country ---',0);
        lstState.options[lstState.options.length] = objOption;
        lstState.disabled=true;
    } else {  
                var arrState = eval(transport.responseText);
                lstState.options.length = 0;
                for(i=0;i<arrState.length;i++) {
                    var objOption = new Option(arrState[i].value,arrState[i].id);
                    if(arrState[i].id == selectedStateID) objOption.selected = true;
                    lstState.options[lstState.options.length] = objOption;

                }
                if (arrState.length > 1) {
                    lstState.disabled=false;
                } else {
                    lstState.disabled=true;
                }
    }
                    }
                }
            }
        );
}

function get_option(id,value){
    this.id=id;
    this.value=value;
}

function sendCityRequest(countryID, stateID, selectedCityID){
  //  var cityList = new getObj('cityList').obj;
    var lstCity = new getObj('lstCity').obj;
    lstCity.options.length = 0;
    var objOption = new Option('--- Loading... ---',0);
    lstCity.options.add(objOption);
    lstCity.disabled=true;
    new ajax(
            '<?=CONST_LINK_ROOT?>/ajax_city.php',
            {
                method: 'post',
                postBody: 'mode=0&countryID='+countryID+'&stateID='+stateID+'&selectedCityID='+selectedCityID,
              //  update: cityList,
                onComplete: function (transport) {
                    if (transport.responseText == '') {
                        lstCity.options.length = 0;
                        var objOption = new Option('--- Select State ---',0);
                        lstCity.options.add(objOption);
                        lstCity.disabled=true;
                    }
                    else
                    {
                         var arrCity = eval(transport.responseText);
                        lstCity.options.length = 0;
                        for(i=0;i<arrCity.length;i++) {
                            var objOption = new Option(arrCity[i].value,arrCity[i].id);
                            if(arrCity[i].id == selectedCityID) objOption.selected = true;
                            lstCity.options[lstCity.options.length] = objOption;

                            lstCity.disabled=false;
                        }
                        if (arrCity.length > 1) {
                            lstCity.disabled=false;
                        } else {
                            lstCity.disabled=true;
                        }
                    }
                }
            }
        );
}

/////-->
</script>
          <tr class="tdodd" >
            <td  align="left"><?php echo GENERAL_COUNTRY?></td>
            <td > <select class="input" name="lstCountry" id="lstCountry" size="1"  tabindex='13' onchange="sendStateRequest(this.options[this.selectedIndex].value);sendCityRequest(this.options[this.selectedIndex].value,0); return false;">
                <option value="0">-- <?php echo GENERAL_CHOOSE?> --</option>
                <option value=""></option>
<?php
include_once __INCLUDE_CLASS_PATH."/class.Geography.php";
$CountriesList = Geography::getCountriesList();
foreach ($CountriesList as $countryrow)
{
    $selected = ($country == $countryrow->gcn_countryid)?' selected':'';
    echo '<option value='.$countryrow->gcn_countryid.$selected.'>'.htmlspecialchars($countryrow->gcn_name).'</option>';
}
?>
              </select> </td>
            <td width="11%" align="left"><?php echo GENERAL_STATE?></td>
            <td width="62%"> <select disabled class="input" name="lstState" id="lstState" size="1"  tabindex='14' 
                                     onchange="sendCityRequest(document.getElementById('lstCountry').value,this.value,0)"
                                   >
                    <!--  onchange="delCityOption('lstCity');xajax_addCities('lstCity',this.value, document.getElementById('lstCountry').value); return false;"-->
                <option value="0">- <?php echo GENERAL_CHOOSE?> -</option>
              </select> </td>
          </tr>
          <tr class="tdeven" >
            <td  align="left"><?php echo GENERAL_CITY?></td>
            <td colspan="3" > <select disabled class="input" name="lstCity" id="lstCity" size="1"   tabindex='15'>
                <option value="0">- <?php echo GENERAL_CHOOSE?> -</option>
              </select> </td>
          </tr>
              <?php } else { ?>
<script language="javascript" src="geography.js"></script>
          <tr class="tdodd" >
            <td width="7%" align="left"><?php echo GENERAL_COUNTRY?></td>
            <td width="20%"> <select class="input" name="lstCountry" id="lstCountry" size="1" tabindex="1" onchange="onCountryListChange('FrmAdvert', 'lstCountry', 'lstState', 'lstCity');">
                <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <option value=""></option>
              </select> </td>
            <td width="11%" align="left"><?php echo GENERAL_STATE?></td>
            <td width="62%"> <select class="input" name="lstState" id="lstState" size="1" tabindex="2" onchange="onStateListChange('FrmAdvert', 'lstCountry', 'lstState', 'lstCity');">
                <option value="0" selected></option>
              </select> </td>
          </tr>
          <tr class="tdeven" >
            <td align="left"><?php echo GENERAL_CITY?></td>
            <td colspan="3"> <select class="input" name="lstCity" id="lstCity" size="1" tabindex="3" onchange="onCityListChange('FrmAdvert', 'lstCity');">
                <option value="0" selected></option>
              </select>  </td>
          </tr>
          <script language="javascript">
                        initialize('FrmAdvert', 'lstCountry', 'lstState', 'lstCity');
          </script>
              <?php  } ?>
        <?php  } else { ?>
          <tr class="tdodd" >
            <td  align="left"><?php echo GENERAL_COUNTRY?>/<?php echo GENERAL_STATE?></td>
            <td >
                <select class="input" name="lstCountry" id="lstCountry" size="1" tabindex="4" style="width:auto;">
                    <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
                    <?= country_state_list($_SESSION['post']['lstCountryState']);?>
                </select>
            </td>
            <td  align="left"><?php echo GENERAL_CITY?></td>
            <td >
                <input type=text class="input" name="txtLocation" value="<?=$_SESSION['post']['txtLocation']?>" tabindex="5">
            </td>
          </tr>

        <?php  } ?>
          <tr class="tdodd" >
            <td align="left"><?php echo ADVERTISE_SEEKING?></td>
            <td> <select name="lstSeeking" size="1" tabindex="6" class="input" >
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('SKG','base','adv',''); ?>
              </select></td>
            <td align="left"><?php echo ADVERTISE_BODY_TYPE?></td>
            <td> <select name="lstBodyType" size="1" tabindex="7" class="input">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('BDY','base','adv',''); ?>
              </select></td>
          </tr>
          <tr class="tdeven" >
            <td align="left"><?php echo ADVERTISE_HEIGHT?></td>
            <td> <select name="lstHeight" size="1" tabindex="8" class="input">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <option value="Not stated"><?php echo GENERAL_NOT_STATE?></option>
                <?php
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

            <td align="left"><?php echo ADVERTISE_CHILDREN?></td>
            <td> <select name="lstChildren" size="1" tabindex="9" class="input">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('CHL','base','adv',''); ?>
              </select></td>
          </tr>
          <tr class="tdodd" >
            <td  align="left"><?php echo OPTION_EYE_COLOR?></td>
            <td > <select name="lstEyecolor" size="1" class="input" tabindex="10">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('EYE','base','adv',''); ?>
              </select></td>
            <td  align="left"><?php echo OPTION_HAIR_COLOR?></td>
            <td > <select name="lstHaircolor" size="1" class="input" tabindex="11">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('HAR','base','adv',''); ?>
              </select></td>
          </tr>
          <tr class="tdodd" >
            <td align="left"><?php echo ADVERTISE_SMOKER?></td>
            <td> <select name="lstSmoker" size="1" tabindex="12" class="input">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('SMK','base','adv',''); ?>
              </select></td>
            <td align="left"><?php echo ADVERTISE_RELIGION?></td>
            <td> <select name="lstReligion" size="1" tabindex="13" class="input">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('RLG','base','adv',''); ?>
              </select></td>
          </tr>
          <tr class="tdeven" >
            <td align="left"><?php echo ADVERTISE_MARITAL?></td>
            <td> <select name="lstMarital" size="1" tabindex="14" class="input">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('MRT','base','adv',''); ?>
              </select></td>
            <td align="left"><?php echo ADVERTISE_ETHN?></td>
            <td> <select name="lstEthnicity" size="1" tabindex="15" class="input">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('ETH','base','adv',''); ?>
              </select></td>
          </tr>
          <tr class="tdodd" >
            <td align="left"><?php echo ADVERTISE_EDU?></td>
            <td> <select class="input" name="lstEducation" size="1" tabindex="16">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('EDU','base','adv',''); ?>
              </select></td>
            <td align="left"><?php echo ADVERTISE_EPL?></td>
            <td> <select class="input" name="lstEmployment" size="1" tabindex="17">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('EMP','base','adv',''); ?>
              </select></td>
          </tr>
          <tr class="tdeven" >
            <td align="left"><?php echo ADVERTISE_INCOME?></td>
            <td> <select class="input" name="lstIncome" size="1" tabindex="18">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('INC','base','adv',''); ?>
              </select></td>
            <td  align="left"><?php echo ADVERTISE_DRINKING?></td>
            <td > <select class="input" name="lstDrink" size="1" tabindex="19">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <?php populate_lists('DNK','base','adv',''); ?>
              </select></td>
          </tr>
          <?php
        if ($CONST_ZIPCODES=='Y') {
          print("<tr class='tdodd'>
        <td  align='left'>".ADVERTISE_ZIPCODE."</td>
        <td  colspan='3'><input type='text' name='txtZipcode' size='15' class='input' maxlength='5' tabindex='20'> (".ADVERTISE_USA.")</td>
      </tr>");
          }
         ?>
          <tr >
            <td colspan="4" align="center" class="tdfoot"">&nbsp;</td>
          </tr>
          <tr align="center" >
            <td colspan="4" class="tdhead""> <input type="checkbox" name="chkSeekmen" value="men" tabindex="21">
              <?php echo ADVERTISE_SEEK_M?>&nbsp; <input type="checkbox" name="chkSeekwmn" value="wmn" tabindex="22">
              <?php echo ADVERTISE_SEEK_W?>&nbsp; <input type="hidden" name="chkSeekcpl" value="cpl" tabindex="23">
              &nbsp; </td>
          </tr>
<?php /*
          <?php if ($CONST_AVATARS_GALLERY == "Y"){?>
          <tr class="tdodd" >
            <td colspan="3" align="left">
                    <a href="#" onClick="javascript:window.open('<?php echo $CONST_LINK_ROOT?>/picture_gallery.php', 'Pictures', 'dependent=yes,resizable=no, width=400')"><?php echo AVATAR_SELECT ?></a>
            </td>
            <td>
                <?php if (!empty($avatar_id)) {
                    $res_avatar = mysqli_query($conSting,$query_avat = "SELECT * FROM avatars AS a
                    INNER JOIN pictures AS p
                        ON (a.pic_id = p.pic_id)
                    WHERE
                        a.avatar_id = $avatar_id
                    ");

                    $oAvatar = mysqli_fetch_object($res_avatar);

                    $thumb_pic = $CONST_LINK_ROOT.str_replace("/members/", "/thumbs/large-", $oAvatar->pic_picture);
//                    $avatar_path = $CONST_LINK_ROOT.'/avatars_thumbs/large-'.$oAvatar->avatar_id.'.jpg';?>
                    <img src="<?=$thumb_pic?>" border="0">
                <?} else {?>
                &nbsp;
                <?}?>
            </td>
          </tr>
          <?}?>

*/?>
          <tr class="tdodd" >
            <td align="left" class="tdeven"><?php echo ADVERTISE_TITLE?></td>
            <td colspan="3" align="left" class="tdeven"> <input type="text" class="inputl" name="txtTitle" size="30" tabindex="25" maxlength='30'>
            </td>
          </tr>
          <tr class="tdeven" >
            <td align="left" valign="top" class="tdodd"><?php echo ADVERTISE_MES?><br>
              <br>
              <I><?php echo ADVERTISE_MES_DESC?></I></td>
            <td colspan="3" align="left" class="tdodd"><textarea  class="inputl" rows="8" name="txtComment" cols="50" tabindex="26" onKeyDown="textCounter(this.form.txtComment,this.form.remLentext);" onKeyUp="textCounter(this.form.txtComment,this.form.remLentext);"></textarea>
            </td>
          </tr>
          <tr >
            <td class="tdodd">&nbsp;</td>
            <td colspan="3" class="tdodd"><em>
              <input type="box" readonly name="remLentext" size="5" value="0" class="inputf">
              <?php echo ADVERTISE_TYPED?></em></td>
          </tr>
          <tr >
            <td colspan="4" class="tdfoot">&nbsp;</td>
          </tr>
          <tr >
            <td colspan="4" class="tdhead"><b><?php echo PRGRETUSER_MYMATCH?></b></td>
          </tr>
          <tr>
            <td class="tdodd"><?php echo PRGRETUSER_GENDER?></td>
            <td colspan="3" class="tdodd"> <select size="1" name="lstMySex"  tabindex="27" class="input">
                <option selected>- <?php echo GENERAL_CHOOSE?> -</option>
                <option value="M"><?php echo SEX_MALE ?></option>
                <option value="F"><?php echo SEX_FEMALE ?></option>
              </select></td>
          </tr>
          <tr>
            <td class="tdeven">
              <?=PRGRETUSER_AGES?>
            </td>
            <td colspan="3" class="tdeven"> <select size="1" name="txtMyFromAge"  tabindex="28" class="inputs">
                <option value='18' selected>18</option>
                <?php
                        for ($i=19; $i < 100; $i++) {
                                print("<option value='$i'>$i</option>");
                        }
                ?>
              </select>
              -
              <select class="inputs" size="1" name="txtMyToAge"  tabindex="29">
                <?php
                        for ($i=18; $i < 99; $i++) {
                                print("<option value='$i'>$i</option>");
                        }
                ?>
                <option value='99' selected>99</option>
              </select></td>
          </tr>
          <tr>
            <td class="tdodd">
              <?=PRGRETUSER_HEIGHT?>
            </td>
            <td colspan="3" class="tdodd"> <select class="inputs" name="lstMyMinHeight" size="1"tabindex="30">
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
              <select class="inputs" name="lstMyMaxHeight" size="1"tabindex="31">
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
          <tr class="tdeven">
            <td>
              <?=PRGRETUSER_SMOKER?>
            </td>
            <td> <select name="lstMySmoker" size="1" tabindex="32" class="input">
                <option value="- Any -" selected>
                <?=SEARCH_ANY?>
                </option>
                <?php populate_lists('SMK','base','adv',''); ?>
              </select></td>
            <td>
              <?=PRGRETUSER_BODYTYPE?>
            </td>
            <td> <select class="input" name="lstMyBodyType" size="1" tabindex="33">
                <option value="- Any -" selected>
                <?=SEARCH_ANY?>
                </option>
                <?php populate_lists('BDY','base','adv',''); ?>
              </select> </td>
          </tr>
          <tr>
            <td class="tdodd">
              <?=PRGRETUSER_RELATIONSHIP?>
            </td>
            <td colspan="3" class="tdodd"> <select class="input" name="lstMySeeking" size="1" tabindex="34">
                <option value="- Any -" selected>
                <?=SEARCH_ANY?>
                </option>
                <?php populate_lists('SKG','base','adv',''); ?>
              </select> </td>
          </tr>
          <tr>
            <td valign="top" class="tdeven">
              <?=PRGRETUSER_COMMENT?>
            </td>
            <td colspan="3" class="tdeven"> <textarea  class="inputl" rows="8" name="txtMyComment" cols="50" tabindex="35"></textarea>
            </td>
          </tr>
          <tr>
            <td colspan="4" align="left" valign="top" class="tdfoot"> <center>
                <input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button">
              </center></td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>