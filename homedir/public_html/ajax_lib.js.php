<?
include "db_connect.php";
?>
<!--
var selectedStateID = "";
var selectedCityID = "";

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

function get_option(id,value){
    this.id=id;
    this.value=value;
}

function StateResponse(transport) {
    var lstState = new getObj('lstState').obj;
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

function sendStateRequest(countryID, selectedState){
    try	{
        if (selectedState) selectedStateID = selectedState;
    } catch(E) {
    }
    var lstState = new getObj('lstState').obj;
    lstState.options.length = 0;
    var objOption = new Option('--- Loading... ---',0);
    lstState.options[lstState.options.length] = objOption;

    lstState.disabled=true;
    if (countryID) {
        new ajax(
            '<?=CONST_LINK_ROOT?>/ajax_state.php',
            {
                method: 'post',
                postBody: 'countryID='+countryID,
                onComplete: StateResponse
            }
        );
    } else {
        country_a = '';
        var lstCountry = new getObj('lstCountry').obj;
        for(iOption = 0; iOption < lstCountry.options.length; iOption++) {
            if(lstCountry.options[iOption].selected == true) {
                country_a = country_a + '_' + lstCountry.options[iOption].value;
            }
        }
        new ajax(
            '<?=CONST_LINK_ROOT?>/ajax_state.php',
            {
                method: 'post',
                postBody: 'country_a='+country_a,
                onComplete: StateResponse
            }
        );
    }
}

function CityResponse(transport) {
    var lstCity = new getObj('lstCity').obj;
    if (transport.responseText == '') {
        lstCity.options.length = 0;
        var objOption = new Option('--- Select State ---',0);
        lstCity.options[lstCity.options.length] = objOption;

        lstCity.disabled=true;
    } else { 
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

function sendCityRequest(countryID, stateID, selectedCity){
    try	{
        if (selectedCity) selectedCityID = selectedCity;
    } catch(E) {
    }
    var lstCity = new getObj('lstCity').obj;
    lstCity.options.length = 0;
    var objOption = new Option('--- Loading... ---',0);
    lstCity.options[lstCity.options.length] = objOption;

    lstCity.disabled=true;

    if (countryID) {
        new ajax(
            '<?=CONST_LINK_ROOT?>/ajax_city.php',
            {
                method: 'post',
                postBody: 'countryID='+countryID+'&stateID='+stateID,
                onComplete: CityResponse
            }
        );
    } else {
        country_a = '';
        state_a = '';
        var lstCountry = new getObj('lstCountry').obj;
        for(iOption = 0; iOption < lstCountry.options.length; iOption++) {
            if(lstCountry.options[iOption].selected == true) {
                country_a = country_a + '_' + lstCountry.options[iOption].value;
            }
        }
        var lstState = new getObj('lstState').obj;
        for(iOption = 0; iOption < lstState.options.length; iOption++) {
            if(lstState.options[iOption].selected == true) {
                state_a = state_a + '_' + lstState.options[iOption].value;
            }
        }
        new ajax(
            '<?=CONST_LINK_ROOT?>/ajax_city.php',
            {
                method: 'post',
                postBody: 'country_a='+country_a+'&state_a='+state_a,
                onComplete: CityResponse
            }
        );
    }
}
//-->
