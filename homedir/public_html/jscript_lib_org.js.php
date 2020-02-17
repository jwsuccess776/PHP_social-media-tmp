<?
include "db_connect.php";
?>
<!--
isAlertedNotCompatible=false;
 // textarea counter on adverts form
  function textCounter(field, countfield) {
      {countfield.value =  field.value.length;}
  }

function Validate_FrmReports() {

  if (document.FrmReports.lstPeriod.selectedIndex == 0)
   {
               alert("<?=addslashes(ENTER_PERIOD)?>");
               document.FrmReports.lstPeriod.focus();
               return (false);
   }
  if (document.FrmReports.lstYear.selectedIndex == 0)
   {
               alert("<?=addslashes(ENTER_YEAR)?>");
               document.FrmReports.lstYear.focus();
               return (false);
   }
   document.FrmReports.hdnyear.value=document.FrmReports.lstYear.value;
   document.FrmReports.hdnperiod.value=document.FrmReports.lstPeriod.value;
  return (true);
 }

 function Validate_FrmPersonal() {  if (document.FrmPersonal.lstPerson1.selectedIndex == 0)
    {
                alert("<?=addslashes(ENTER_PERSONALITY)?>");
                document.FrmPersonal.lstPerson1.focus();
                return (false);
    }  if (document.FrmPersonal.lstPhilos1.selectedIndex == 0)
    {
                alert("<?=addslashes(ENTER_PHILOSOPHY)?>");
                document.FrmPersonal.lstPhilos1.focus();
                return (false);
    }

   if (document.FrmPersonal.lstSocial1.selectedIndex == 0)
    {
                alert("<?=addslashes(ENTER_SOCIAL)?>");
                document.FrmPersonal.lstSocial1.focus();
                return (false);
    }

   if (document.FrmPersonal.lstGoal1.selectedIndex == 0)
    {
                alert("<?=addslashes(ENTER_GOAL)?>");
                document.FrmPersonal.lstGoal1.focus();
                return (false);
    }

   if (document.FrmPersonal.lstHobby1.selectedIndex == 0)
    {
                alert("<?=addslashes(ENTER_HOBBIES)?>");
                document.FrmPersonal.lstHobby1.focus();
                return (false);
    }

   if (document.FrmPersonal.lstSport1.selectedIndex == 0)
    {
                alert("<?=addslashes(ENTER_SPORTS)?>");
                document.FrmPersonal.lstSport1.focus();
                return (false);
    }

   if (document.FrmPersonal.lstMusic1.selectedIndex == 0)
    {
                alert("<?=addslashes(ENTER_MUSIC)?>");
                document.FrmPersonal.lstMusic1.focus();
                return (false);
    }

   if (document.FrmPersonal.lstFood1.selectedIndex == 0)
    {
                alert("<?=addslashes(ENTER_FOOD)?>");
                document.FrmPersonal.lstFood1.focus();
                return (false);
    }

   return (true);
 }
function Validate_FrmSendMail() {
  if (document.FrmSendMail.txtSubject.value.length < 2)
  {
    alert("<?=addslashes(ENTER_SUBJECT)?>");
    document.FrmSendMail.txtSubject.focus();
    return (false);
  }
  if (document.FrmSendMail.txtMessage.value.length < 20)
  {
    alert("<?=addslashes(ENTER_MESSAGE)?>");
    document.FrmSendMail.txtMessage.focus();
    return (false);
  }

  return (true);
 }

 function Validate_FrmLogin() {

   if (document.FrmLogin.txtHandle.value.length < 6)
   {
     alert("<?=addslashes(SHORT_USERNAME)?>");
     document.FrmLogin.txtHandle.focus();
     return (false);
   }

   if (document.FrmLogin.txtHandle.value.length > 25)
   {
     alert("<?=addslashes(LONG_USERNAME)?>");
     document.FrmLogin.txtHandle.focus();
     return (false);
   }

   if (document.FrmLogin.txtPassword.value.length < 6)
   {
     alert("<?=addslashes(SHORT_PASSWORD)?>");
     document.FrmLogin.txtPassword.focus();
     return (false);
   }

   if (document.FrmLogin.txtPassword.value.length > 10)
   {
     alert("<?=addslashes(LONG_PASSWORD)?>");
     document.FrmLogin.txtPassword.focus();
     return (false);
   }

   return (true);
 }

 function Validate_FrmRegister(mode) {

   if (mode == 'create') {

           if (document.FrmRegister.txtHandle.value == "")
           {
             alert("<?=addslashes(SHORT_USERNAME)?>");
             document.FrmRegister.txtHandle.focus();
             return (false);
           }
           if (document.FrmRegister.txtHandle.value.length > 25)
           {
             alert("<?=addslashes(LONG_USERNAME)?>");
             document.FrmRegister.txtHandle.focus();
             return (false);
           }
           if (document.FrmRegister.txtHandle.value.length < 6)
           {
             alert("<?=addslashes(SHORT_USERNAME)?>");
             document.FrmRegister.txtHandle.focus();
             return (false);
           }
           var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz����������������������������������������������������������������������1234567890-\t\r\n\f";
           var checkStr = document.FrmRegister.txtHandle.value;
           var allValid = true;
           for (i = 0;  i < checkStr.length;  i++)
           {
             ch = checkStr.charAt(i);
             for (j = 0;  j < checkOK.length;  j++)
               if (ch == checkOK.charAt(j))
                 break;
             if (j == checkOK.length)
             {
               allValid = false;
               break;
             }
           }
           if (!allValid)
           {
             alert("<?=addslashes(ALPHABET_USERNAME)?>");
             document.FrmRegister.txtHandle.focus();
             return (false);
           }
   }
   if (document.FrmRegister.txtPassword.value.length < 6)
   {
     alert("<?=addslashes(LENGTH_PASSWORD)?>");
     document.FrmRegister.txtPassword.focus();
     return (false);
   }

   var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz����������������������������������������������������������������������1234567890";
   var checkStr = document.FrmRegister.txtPassword.value;
   var allValid = true;
   for (i = 0;  i < checkStr.length;  i++)
   {
     ch = checkStr.charAt(i);
     for (j = 0;  j < checkOK.length;  j++)
       if (ch == checkOK.charAt(j))
         break;
     if (j == checkOK.length)
     {
       allValid = false;
       break;
     }
   }
   if (!allValid)
   {
     alert("<?=addslashes(ALPHABET_PASSWORD)?>");
     document.FrmRegister.txtPassword.focus();
     return (false);
   }

   if (document.FrmRegister.txtPassword.value.length > 10)
    {
      alert("<?=addslashes(LONG_PASSWORD)?>");
      document.FrmRegister.txtPassword.focus();
      return (false);
   }

   if (document.FrmRegister.txtConfirm.value.length < 6)
   {
     alert("<?=addslashes(LENGTH_CONFIRM)?>");
     document.FrmRegister.txtConfirm.focus();
     return (false);
   }

   var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz����������������������������������������������������������������������1234567890";
   var checkStr = document.FrmRegister.txtConfirm.value;
   var allValid = true;
   for (i = 0;  i < checkStr.length;  i++)
   {
     ch = checkStr.charAt(i);
     for (j = 0;  j < checkOK.length;  j++)
       if (ch == checkOK.charAt(j))
         break;
     if (j == checkOK.length)
     {
       allValid = false;
       break;
     }
   }
   if (!allValid)
   {
     alert("<?=addslashes(ALPHABET_CONFIRM)?>");
     document.FrmRegister.txtConfirm.focus();
     return (false);
   }

   if (document.FrmRegister.txtConfirm.value.length > 10)
    {
      alert("<?=addslashes(LONG_CONFIRM)?>");
      document.FrmRegister.txtConfirm.focus();
      return (false);
   }
   if (document.FrmRegister.txtPassword.value != document.FrmRegister.txtConfirm.value)
    {
      alert("<?=addslashes(PASSWORD_DOSNT_MATCH)?>");
      document.FrmRegister.txtPassword.focus();
      return (false);
   }
   if (document.FrmRegister.txtSurname.value.length < 2)
   {
     alert("<?=addslashes(ENTER_LAST_NAME)?>");
     document.FrmRegister.txtSurname.focus();
     return (false);
   }

   var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz����������������������������������������������������������������������- \t\r\n\f";
   var checkStr = document.FrmRegister.txtSurname.value;
   var allValid = true;
   for (i = 0;  i < checkStr.length;  i++)
   {
     ch = checkStr.charAt(i);
     for (j = 0;  j < checkOK.length;  j++)
       if (ch == checkOK.charAt(j))
         break;
     if (j == checkOK.length)
     {
       allValid = false;
       break;
     }
   }
   if (!allValid)
   {
     alert("<?=addslashes(ALPHABET_LAST_NAME)?>");
     document.FrmRegister.txtSurname.focus();
     return (false);
   }

   if (document.FrmRegister.txtSurname.value.length > 25)
    {
      alert("<?=addslashes(LONG_LAST_NAME)?>");
      document.FrmRegister.txtSurname.focus();
      return (false);
   }

   if (document.FrmRegister.txtForename.value.length > 25)
    {
      alert("<?=addslashes(LONG_FIRST_NAME)?>");
      document.FrmRegister.txtForename.focus();
      return (false);
   }

   if (document.FrmRegister.txtForename.value.length < 2)
   {
     alert("<?=addslashes(ENTER_FIRST_NAME)?>");
     document.FrmRegister.txtForename.focus();
     return (false);
   }

   var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz����������������������������������������������������������������������- \t\r\n\f";
   var checkStr = document.FrmRegister.txtForename.value;
   var allValid = true;
   for (i = 0;  i < checkStr.length;  i++)
   {
     ch = checkStr.charAt(i);
     for (j = 0;  j < checkOK.length;  j++)
       if (ch == checkOK.charAt(j))
         break;
     if (j == checkOK.length)
     {
       allValid = false;
       break;
     }
   }
   if (!allValid)
   {
     alert("<?=addslashes(ALPHABET_FIRST_NAME)?>");
     document.FrmRegister.txtForename.focus();
     return (false);
   }

   if (mode=='create' && document.FrmRegister.lstDay.selectedIndex == 0) {
               alert("<?=addslashes(SELECT_DAY)?>");
               document.FrmRegister.lstDay.focus();
               return (false);
   }

   if (mode=='create' && document.FrmRegister.lstMonth.selectedIndex == 0) {
               alert("<?=addslashes(SELECT_MONTH)?>");
               document.FrmRegister.lstMonth.focus();
               return (false);
   }

   if (mode=='create' && document.FrmRegister.txtYear.selectedIndex == 0) {
               alert("<?=addslashes(ENTER_YEAR)?>");
               document.FrmRegister.txtYear.focus();
               return (false);
   }

   if (mode=='create' && document.FrmRegister.lstSex.selectedIndex == 0) {
             alert("<?=addslashes(SELECT_GENDER)?>");
             document.FrmRegister.lstSex.focus();
             return (false);
   }

   if (document.FrmRegister.txtEmail.value.length < 5)
   {
     alert("<?=addslashes(ENTER_EMAIL)?>");
     document.FrmRegister.txtEmail.focus();
     return (false);
   }

   if (document.FrmRegister.txtEmail.value.indexOf("@") < 0 || document.FrmRegister.txtEmail.value.indexOf(".") < 0)
   {
     alert("<?=addslashes(INVALID_EMAIL)?>");
     document.FrmRegister.txtEmail.focus();
     return (false);
   }

   if (mode=='create' && document.FrmRegister.chkDisclaimer.checked == false)
   {
      alert("<?=addslashes(READ_DISCLAIMER)?>");
      document.FrmRegister.chkDisclaimer.focus();
      return (false);
   }

   if (document.FrmRegister.lstCountry &&
       document.FrmRegister.lstCountry.value <= 0)
    {
        alert("<?=addslashes(ENTER_COUNTRY)?>");
        document.FrmRegister.lstCountry.focus();
        return (false);
    }
<? if($GEOGRAPHY_JAVASCRIPT){    ?>
   if (document.getElementById("lstState").disabled == false) {
	 if (document.FrmRegister.lstState &&
		   document.FrmRegister.lstState.value <= 0)
		{
			alert("<?=addslashes(ENTER_STATE)?>");
			try {
				document.FrmRegister.lstState.focus();
			} catch(E) {
			}
			return (false);
		}
   }
   if (document.FrmRegister.lstCity &&
       document.FrmRegister.lstCity.value <= 0)
    {
        alert("<?=addslashes(ENTER_CITY)?>");
        try {
            document.FrmRegister.lstCity.focus();
        } catch(E) {
        }
        return (false);
    }

<? }else{ ?>
   if (document.FrmRegister.txtLocation )
       if (document.FrmRegister.txtLocation.value.length < 2 || document.FrmRegister.txtLocation.value.length > 30)
       {
           alert("<?=addslashes(LENGTH_LOCATION)?>");
           document.FrmRegister.txtLocation.focus();
           return (false);
       }
<?}?>
   if (mode=='create' && document.FrmRegister.chkSeekmen.checked == false && document.FrmRegister.chkSeekwmn.checked == false && document.FrmRegister.chkSeekcpl.checked == false)
   {
     alert("<?=addslashes(SELECT_SEEKING_GENDER)?>");
     document.FrmRegister.chkSeekmen.focus();
     return (false);
   }

   if (mode=='create' && (document.FrmRegister.txtTitle.value.length < 5 || document.FrmRegister.txtTitle.value.length > 30))
   {
       alert("<?=addslashes(LENGTH_MESSAGE_TITLE)?>");
       document.FrmRegister.txtTitle.focus();
       return (false);
   }

   if (mode=='create' && (document.FrmRegister.txtComment.value.length < 120 || document.FrmRegister.txtComment.value.length > 4000))
   {
     alert("<?=addslashes(LENGTH_MESSAGE)?>");
     document.FrmRegister.txtComment.focus();
     return (false);
   }

   return (true);
 }

 function Validate_FrmAffiliate() {

   if (document.FrmAffiliate.txtUsername.value.length > 25)
   {
     alert("<?=addslashes(LONG_USERNAME)?>");
     document.FrmAffiliate.txtUsername.focus();
     return (false);
   }

   if (document.FrmAffiliate.txtUsername.value.length < 6)
   {
     alert("<?=addslashes(SHORT_USERNAME)?>");
     document.FrmAffiliate.txtUsername.focus();
     return (false);
   }

   var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz����������������������������������������������������������������������1234567890-\t\r\n\f";
   var checkStr = document.FrmAffiliate.txtUsername.value;
   var allValid = true;
   for (i = 0;  i < checkStr.length;  i++)
   {
     ch = checkStr.charAt(i);
     for (j = 0;  j < checkOK.length;  j++)
       if (ch == checkOK.charAt(j))
         break;
     if (j == checkOK.length)
     {
       allValid = false;
       break;
     }
   }
   if (!allValid)
   {
     alert("<?=addslashes(ALPHABET_USERNAME)?>");
     document.FrmAffiliate.txtUsername.focus();
     return (false);
   }

   if (document.FrmAffiliate.txtSurname.value.length < 2)
   {
     alert("<?=addslashes(ENTER_LAST_NAME)?>");
     document.FrmAffiliate.txtSurname.focus();
     return (false);
   }

   var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz����������������������������������������������������������������������- \t\r\n\f";
   var checkStr = document.FrmAffiliate.txtSurname.value;
   var allValid = true;
   for (i = 0;  i < checkStr.length;  i++)
   {
     ch = checkStr.charAt(i);
     for (j = 0;  j < checkOK.length;  j++)
       if (ch == checkOK.charAt(j))
         break;
     if (j == checkOK.length)
     {
       allValid = false;
       break;
     }
   }
   if (!allValid)
   {
     alert("<?=addslashes(ALPHABET_LAST_NAME)?>");
     document.FrmAffiliate.txtSurname.focus();
     return (false);
   }

   if (document.FrmAffiliate.txtSurname.value.length > 25)
    {
      alert("<?=addslashes(LONG_LAST_NAME)?>");
      document.FrmAffiliate.txtSurname.focus();
      return (false);
   }

   if (document.FrmAffiliate.txtForename.value.length > 25)
    {
      alert("<?=addslashes(LONG_FIRST_NAME)?>");
      document.FrmAffiliate.txtForename.focus();
      return (false);
   }

   if (document.FrmAffiliate.txtForename.value.length < 2)
   {
     alert("<?=addslashes(ENTER_FIRST_NAME)?>");
     document.FrmAffiliate.txtForename.focus();
     return (false);
   }

   var checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz����������������������������������������������������������������������- \t\r\n\f";
   var checkStr = document.FrmAffiliate.txtForename.value;
   var allValid = true;
   for (i = 0;  i < checkStr.length;  i++)
   {
     ch = checkStr.charAt(i);
     for (j = 0;  j < checkOK.length;  j++)
       if (ch == checkOK.charAt(j))
         break;
     if (j == checkOK.length)
     {
       allValid = false;
       break;
     }
   }
   if (!allValid)
   {
     alert("<?=addslashes(ALPHABET_FIRST_NAME)?>");
     document.FrmAffiliate.txtForename.focus();
     return (false);
   }

   if (document.FrmAffiliate.txtBusiness.value == "")
   {
     alert("<?=addslashes(ENTER_BUSSINES_NAME)?>");
     document.FrmAffiliate.txtBusiness.focus();
     return (false);
   }

   if (document.FrmAffiliate.txtAddress.value == "")
   {
     alert("<?=addslashes(ENTER_ADDRESS)?>");
     document.FrmAffiliate.txtAddress.focus();
     return (false);
   }

   if (document.FrmAffiliate.txtStreet.value == "")
   {
     alert("<?=addslashes(ENTER_STREET)?>");
     document.FrmAffiliate.txtStreet.focus();
     return (false);
   }

   if (document.FrmAffiliate.txtTown.value == "")
   {
     alert("<?=addslashes(ENTER_TOWN)?>");
     document.FrmAffiliate.txtTown.focus();
     return (false);
   }

   if (document.FrmAffiliate.txtState.value == "")
   {
     alert("<?=addslashes(ENTER_STATE)?>");
     document.FrmAffiliate.txtState.focus();
     return (false);
   }

   if (document.FrmAffiliate.txtZip.value == "")
   {
     alert("<?=addslashes(ENTER_ZIP)?>");
     document.FrmAffiliate.txtZip.focus();
     return (false);
   }

   if (document.FrmAffiliate.lstCountry.selectedIndex == 0)
    {
                alert("<?=addslashes(ENTER_COUNTRY)?>");
                document.FrmAffiliate.lstCountry.focus();
                return (false);
    }

   if (document.FrmAffiliate.txtEmail.value.length < 5)
   {
     alert("<?=addslashes(ENTER_EMAIL)?>");
     document.FrmAffiliate.txtEmail.focus();
     return (false);
   }

   if (document.FrmAffiliate.txtEmail.value.indexOf("@") < 0 || document.FrmAffiliate.txtEmail.value.indexOf(".") < 0)
   {
     alert("<?=addslashes(INVALID_EMAIL)?>");
     document.FrmAffiliate.txtEmail.focus();
     return (false);
   }

    if (document.FrmAffiliate.txtWebsite.value == "http://")
    {
      alert("<?=addslashes(ENTER_WEBSITE)?>");
      document.FrmAffiliate.txtWebsite.focus();
      return (false);
    }


    if (document.FrmAffiliate.txtPayable.value == "")
    {
      alert("<?=addslashes(ENTER_PAYABLE)?>");
      document.FrmAffiliate.txtPayable.focus();
      return (false);
    }


   return (true);
 }

 function Validate_FrmAdvert(mode) {

   if (document.FrmAdvert.lstCountry &&
       document.FrmAdvert.lstCountry.value <= 0)
    {
        alert("<?=addslashes(ENTER_COUNTRY)?>");
        document.FrmAdvert.lstCountry.focus();
        return (false);
    }

   if (document.FrmAdvert.lstCity &&
       document.FrmAdvert.lstCity.value <= 0)
    {
        alert("<?=addslashes(ENTER_CITY)?>");
        try {
            document.FrmAdvert.lstCity.focus();
        } catch(E) {
        }
        return (false);
    }

   if (document.FrmAdvert.chkSeekmen.checked == false && document.FrmAdvert.chkSeekwmn.checked == false && document.FrmAdvert.chkSeekcpl.checked == false)
   {
     alert("<?=addslashes(SELECT_SEEKING_GENDER)?>");
     document.FrmAdvert.chkSeekmen.focus();
     return (false);
   }


   if (document.FrmAdvert.txtTitle.value.length < 5 || document.FrmAdvert.txtTitle.value.length > 30)
   {
       alert("<?=addslashes(LENGTH_MESSAGE_TITLE)?>");
       document.FrmAdvert.txtTitle.focus();
       return (false);
   }

   if (document.FrmAdvert.txtComment.value.length < 120 || document.FrmAdvert.txtComment.value.length > 4000 )
   {
     alert("<?=addslashes(LENGTH_MESSAGE)?>");
     document.FrmAdvert.txtComment.focus();
     return (false);
   }

   return (true);
 }

function delete_alert_general() {
        if (window.confirm("<?=addslashes(ALERT_DELETE_GENERAL)?>")) {
                return true;
        } else {
                return false;
        }
}
function delete_alert() {
        if (window.confirm("<?=addslashes(ALERT_DELETE)?>")) {
                return true;
        } else {
                return false;
        }
}
function delete_alert2() {
        if (window.confirm("<?=addslashes(ALERT_DELETE2)?>")) {
                return true;
        } else {
                return false;
        }
}
function delete_alert3() {
        if (window.confirm("<?=addslashes(ALERT_DELETE3)?>")) {
                return true;
        } else {
                return false;
        }
}
function delete_alert4() {
        if (window.confirm("<?=addslashes(ALERT_DELETE4)?>")) {
                return true;
        } else {
                return false;
        }
}
function delete_alert5() {
        if (window.confirm("<?=addslashes(ALERT_DELETE5)?>")) {
                return true;
        } else {
                return false;
        }
}
function delete_alert6() {
        if (window.confirm("<?=addslashes(ALERT_DELETE6)?>")) {
                return true;
        } else {
                return false;
        }
}

function delete_alert7() {
        if (window.confirm("<?=addslashes(ALERT_DELETE7)?>")) {
                return true;
        } else {
                return false;
        }
}

function delete_alert8() {
        if (window.confirm("<?=addslashes(ALERT_DELETE8)?>")) {
                return true;
        } else {
                return false;
        }
}

function skip_alert() {
        if (window.confirm("<?=addslashes(ALERT_SKIP)?>")) {
                return true;
        } else {
                return false;
        }
}
function MDM_openWindow(theURL,winName,features) {
//        features += ',location=yes,status=yes,resizeable=yes';
        var _W=window.open(theURL,winName,features);
        _W.focus();
        //_W.moveTo(50,30);
}

function switchBlock(id,show){
    if (document.getElementById(id).disabled){
        document.getElementById(id).style.display=show;
        document.getElementById(id).disabled=false;
        document.getElementById(id+'_up').style.display='none';
        document.getElementById(id+'_down').style.display=show;
    }else{
        document.getElementById(id).style.display='none';
        document.getElementById(id).disabled=true;
        document.getElementById(id+'_up').style.display=show;
        document.getElementById(id+'_down').style.display='none';
    }
}
function uncheck_other(el,name){
        f = el.form;
    n = f.elements.length;
    j = 0;
    for (i = h = c = 0; i < n; i++) {
        if (f.elements[i].type == 'checkbox') {
//          if (f.elements[i].name == name && f.elements[i].value != '- Any -' ) f.elements[i].checked = false;
            if (f.elements[i].name == name && j++>=1) f.elements[i].checked = false;
        }
    }
}
function uncheck_first(el,name){
        f = el.form;
    n = f.elements.length;
    for (i = h = c = 0; i < n; i++) {
        if (f.elements[i].type == 'checkbox') {
//          if (f.elements[i].name == name && f.elements[i].value == '- Any -'){
            if (f.elements[i].name == name){
                f.elements[i].checked = false;
                return;
            }
        }
    }
}

function uncheck_seek(el,name){
    f = el.form;
    n = f.elements.length;
    j = 0;
    for (i = h = c = 0; i < n; i++) {
        if (f.elements[i].type == 'checkbox') {
            if (f.elements[i].name == name) f.elements[i].checked = false;
        }
    }
}

function insertValueToArea(list,area) {
    var myArea = area;
    var myList = list;
    var myForm = list.form;

    if(myList.options.length > 0) {
        var chaineAj = "";
        var NbSelect = 0;
        for(var i=0; i < myList.options.length; i++) {
            if (myList.options[i].selected){
                NbSelect++;
                if (NbSelect > 1)
                    chaineAj += ", ";
                chaineAj += myList.options[i].value;
            }
        }

        //IE support
        if (document.selection) {
            myArea.focus();
            sel = document.selection.createRange();
            sel.text = chaineAj;
            //myForm.insert.focus();
        }
        //MOZILLA/NETSCAPE support
        else if (myArea.selectionStart || myArea.selectionStart == "0") {
            var startPos = myArea.selectionStart;
            var endPos = myArea.selectionEnd;
            var chaineSql = myArea.value;

            myArea.value = chaineSql.substring(0, startPos) + chaineAj + chaineSql.substring(endPos, chaineSql.length);
        } else {
            myArea.value += chaineAj;
        }
    }
}

function admin_mail(strObject, sd) {

    switch (strObject) {
        case "txtAddress":
            if (document.mailForm.txtAddress.value.length > 0) {
                document.mailForm.chkAffiliates.disabled=true;
                document.mailForm.chkFile.disabled=true;
                document.mailForm.chkAllusers.disabled=true;
                document.mailForm.chkSpeeddating.disabled=true;
            } else {
                document.mailForm.chkAffiliates.disabled=false;
                document.mailForm.chkAllusers.disabled=false;
                document.mailForm.chkFile.disabled=false;
                document.mailForm.chkSpeeddating.disabled=false;
            }
            break;
        case "chkAllusers":
            if (document.mailForm.chkAllusers.checked==true) {
                document.mailForm.chkAffiliates.disabled=true;
                document.mailForm.chkFile.disabled=true;
                document.mailForm.txtAddress.disabled=true;
                document.mailForm.chkSpeeddating.disabled=true;
            } else {
                document.mailForm.chkAffiliates.disabled=false;
                document.mailForm.chkFile.disabled=false;
                document.mailForm.txtAddress.disabled=false;
                document.mailForm.chkSpeeddating.disabled=false;
            }
            break;
        case "chkAffiliates":
            if (document.mailForm.chkAffiliates.checked==true) {
                document.mailForm.chkAllusers.disabled=true;
                document.mailForm.chkFile.disabled=true;
                document.mailForm.txtAddress.disabled=true;
                document.mailForm.chkSpeeddating.disabled=true;
            } else {
                document.mailForm.chkAllusers.disabled=false;
                document.mailForm.chkFile.disabled=false;
                document.mailForm.txtAddress.disabled=false;
                document.mailForm.chkSpeeddating.disabled=false;
            }
            break;
        case "chkFile":
            if (document.mailForm.chkFile.checked==true) {
                document.mailForm.chkAffiliates.disabled=true;
                document.mailForm.chkAllusers.disabled=true;
                document.mailForm.txtAddress.disabled=true;
                document.mailForm.chkSpeeddating.disabled=true;
            } else {
                document.mailForm.chkAffiliates.disabled=false;
                document.mailForm.chkAllusers.disabled=false;
                document.mailForm.txtAddress.disabled=false;
                document.mailForm.chkSpeeddating.disabled=false;
            }
            break;
        case "chkSpeeddating":
            if (document.mailForm.chkSpeeddating.checked==true) {
                document.mailForm.chkAffiliates.disabled=true;
                document.mailForm.chkAllusers.disabled=true;
                document.mailForm.txtAddress.disabled=true;
                document.mailForm.chkFile.disabled=true;
            } else {
                document.mailForm.chkAffiliates.disabled=false;
                document.mailForm.chkAllusers.disabled=false;
                document.mailForm.txtAddress.disabled=false;
                document.mailForm.chkFile.disabled=false;
            }
            break;  }

    return true;

}

function mozWrap(txtarea, open)
{
    var selLength = txtarea.textLength;
    var selStart = txtarea.selectionStart;
    var selEnd = txtarea.selectionEnd;
    if (selEnd == 1 || selEnd == 2)
            selEnd = selLength;

    var s1 = (txtarea.value).substring(0,selStart);
    var s3 = (txtarea.value).substring(selEnd, selLength);
    txtarea.value = s1 + open + s3;
    return;
}

function insertTexta ( texta , textEl ) {
    if (textEl.createTextRange)
    {
        textEl.focus();
        caretPos = document.selection.createRange();
        caretPos.text = texta;
        caretPos.select();
    }
    else
    {
        mozWrap(textEl,texta);
    }
}

function emoticon(id,text) {
    texta = " "+text+" ";
    textEl = document.getElementById(id);
    insertTexta ( texta , textEl );
}

function selected(oRow) {
    oRow.style.backgroundColor='#dddddd';
}
function deselected(oRow,oColor) {
    oRow.style.backgroundColor='#f0f0f0';
}

function selectAll(frm_name,fld_name,selected){
  var elms = document.forms[frm_name].elements;
  for(var i=0; i < elms.length; i++){
    if (elms[i].name == fld_name) {
      elms[i].selected = selected;
      elms[i].checked = selected;
    }
  }
}

function showToolTip(e,text){
      document.all.ToolTip.innerHTML="<table><tr><td class=ToolTipTD>"+text+"</td></tr></table>";
      ToolTip.style.pixelLeft=(e.x+15+document.body.scrollLeft);
      ToolTip.style.pixelTop=(e.y+document.body.scrollTop);
      ToolTip.style.visibility="visible";
}
function hideToolTip(){
      ToolTip.style.visibility="hidden";
}

function getCookie(name){
   var result = null;
   var myCookie = " " + document.cookie + ";";
   var searchName = " " + name + "=";
   var startOfCookie = myCookie.indexOf(searchName);
   var endOfCookie;

   if (startOfCookie != -1){
      startOfCookie += searchName.length;
      endOfCookie = myCookie.indexOf(";",startOfCookie);
      result = unescape(myCookie.substring(startOfCookie,endOfCookie));
   }
   return result;
}

function showPopup(_w, _h, text){
    var sAgent=navigator.userAgent.toLowerCase();
    IsIE       = sAgent.indexOf("msie")!=-1
    var z_index = 1001;
    div = document.createElement('div');
    div.id = "popup";
    document.body.appendChild(div);
    var cur_popup = document.getElementById('popup');
    cur_popup.style.width = _w+'px'
    if (_h == 0) {
        _h = 400;
    } else {
        cur_popup.style.height = _h+'px';
    }
    cur_popup.style.position = 'absolute';

    cur_popup.style.zIndex = z_index+1;
    if (IsIE) {
        var _top = document.body.clientHeight/2 + document.body.scrollTop;
        var _left = document.body.clientWidth/2 + document.body.scrollLeft;
    } else {
        var _left = window.innerWidth/2+window.pageXOffset;
        var _top = window.innerHeight/2+window.pageYOffset;
    }
    if (_left<_w/2)
        _left = _w/2;
    if (_top<_h/2)
        _top=_h/2;

    cur_popup.style.left = parseInt(_left) + 'px';
    cur_popup.style.top = parseInt(_top) + 'px';
    var _ml = parseInt(-_w/2);
    var _mt = parseInt(-_h/2);
    cur_popup.style.marginLeft = _ml+'px';
    cur_popup.style.marginTop = _mt+'px';
    cur_popup.style.border = '3px solid #666666';
    cur_popup.style.backgroundColor =  '#fff';
    cur_popup.style.color =  '#000';
    cur_popup.style.display = 'none';
//    cur_popup.style.overflow = 'show';
    cur_popup.style.fontSize = '12px';
    cur_popup.style.textAlign = 'center';
    cur_popup.style.padding = '10px';

    cur_popup.innerHTML = text;
    cur_popup.style.display = 'block';
    return;
}

function showUploadingPopup (){
    var text = '<p>Your request is in progress....</p><br>';
    text = text + '<center><div align="center" class="progress">&nbsp;</div></center><br>';
    text = text + "<br>This may take awhile depending on your internet connection<br>";
    text = text + "<br>";
    showPopup(350, 130, text);
}

 function Validate_VideoUpload() {

   if (document.FrmPicture.title.value.length < 2)
   {
     alert("<?=addslashes(SHORT_VIDEO_TITLE)?>");
     document.FrmPicture.title.focus();
     return (false);
   }
   if (document.FrmPicture.description.value.length < 2)
   {
     alert("<?=addslashes(SHORT_VIDEO_DESCRIPTION)?>");
     document.FrmPicture.description.focus();
     return (false);
   }
   return (true);
 }

var Base64 = {
 
	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
 
	// public method for encoding
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;
 
//		input = Base64._utf8_encode(input);
 
		while (i < input.length) {
 
			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);
 
			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;
 
			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}
 
			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
 
		}
 
		return output;
	},
 
	// public method for decoding
	decode : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;
 
		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
 
		while (i < input.length) {
 
			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));
 
			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;
 
			output = output + String.fromCharCode(chr1);
 
			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}
 
		}
 
//		output = Base64._utf8_decode(output);
 
		return output;
 
	},
 
	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
 
		for (var n = 0; n < string.length; n++) {
 
			var c = string.charCodeAt(n);
 
			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
 
		}
 
		return utftext;
	},
 
	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
 
		while ( i < utftext.length ) {
 
			c = utftext.charCodeAt(i);
 
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
 
		}
 
		return string;
	}
 
}


<? include CONST_INCLUDE_ROOT."/rating/js.inc.php"?>
-->



