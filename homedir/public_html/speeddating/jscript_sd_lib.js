// main functions created by Oleg Velychko
var spaces = " \t\r\n"+String.fromCharCode(160);

function isSpaceCharacter(ch) {
  return spaces.indexOf(ch) >-1;
}

function trim(str) {
   var s = new String(str);

   while (s.length>0 && isSpaceCharacter("" + s.charAt(s.length-1))) {
     s = s.substring(0,s.length-1);
   }

   while (s.length>0 && isSpaceCharacter(""+s.charAt(0))){
     s = s.substring(1);
   }

   return s
 }

function isEmpty(elem){
  return trim(elem.value).length==0;
}

function selectedRadio(sel){
  if(!sel || !sel.length){
     return null;
  }
  for(var i = 0; i<sel.length; i++){
     if(sel[i].checked){
        return sel[i].value;
     }
  }
  return null;
}

function isNumber(obj){
        return (obj.value.search("[^0-9 ]") == -1);
}

function isFloat(obj, prec){
//    alert(obj.value.search("^[0-9]*\.[0-9]{"+prec+"}$") != -1);
    if ((obj.value.search("^[0-9]*\.[0-9]{"+prec+"}$") != -1) || (obj.value.search("^[0-9]*$") != -1)) {
        return true;
    }
    else {
        return false;
    }
}

function isEmail(str) {
    if (str.search("[\\w\\._-]+@[\\w\\._-]+\\.[\\w\\._-]") == 0) {
    	return true;
    } else {
    	return false;
    }
}

function interrogate(what) {
//alert(what);
    var output = '';
    for (var i in what)
        output += i + ' = ' + what.i + " ; ";
    alert(output);
//    param.innerHTML = output;
}

function selectedRadio(sel){
  if(!sel || !sel.length){
     return null;
  }
  for(var i = 0; i<sel.length; i++){
     if(sel[i].checked){
        return sel[i].value;
     }
  }
  return null;
}

function checkAll(b, first_part){
   var elems = b.form.elements;
   for(var i = 0; i < elems.length; i++){
      if(elems[i].type == "checkbox" && elems[i].name.indexOf(first_part) == 0) {
         if (!elems[i].disabled) {
             elems[i].checked = b.checked;
         }
      }
   }
}
// end main functions

function sd_event_edit(forma) {
    if(isEmpty(forma.sde_name)) {
        alert("Please enter Name");
        forma.sde_name.focus();
        return false;
    }
    if(forma.sde_year.value === "") {
        alert("Please select Year");
        forma.sde_year.focus();
        return false;
    }
    if(forma.sde_month.value === "") {
        alert("Please select Month");
        forma.sde_month.focus();
        return false;
    }
    if(forma.sde_day.value === "") {
        alert("Please select Day");
        forma.sde_day.focus();
        return false;
    }
    var data = new Date(forma.sde_year.value, forma.sde_month.value-1, forma.sde_day.value);
//    alert(data.getMonth() != forma.sde_month.value-1);
    if(data.getMonth() != (forma.sde_month.value - 1)) {
        alert("This month doesn't have such day");
        forma.sde_day.focus();
        return false;
    }
    if(forma.sde_hour.value === "") {
        alert("Please select Hour");
        forma.sde_hour.focus();
        return false;
    }
    if(forma.sde_minute.value === "") {
        alert("Please select Minute");
        forma.sde_minute.focus();
        return false;
    }
    if(forma.sde_age_from.value === "") {
        alert("Please select Age From");
        forma.sde_age_from.focus();
        return false;
    }
    if(forma.sde_age_to.value === "") {
        alert("Please select Age To");
        forma.sde_age_to.focus();
        return false;
    }
    if(parseInt(forma.sde_age_from.value) > parseInt(forma.sde_age_to.value)) {
        alert("Age From cann't be bigger than Age To");
        forma.sde_age_to.focus();
        return false;
    }

    if(!isNumber(forma.sde_gender1_places) || forma.sde_gender1_places.value === "") {
        alert("Number of places must be a number")
        forma.sde_gender1_places.focus();
        return false;
    }
    if(!isNumber(forma.sde_gender2_places) || forma.sde_gender2_places.value === "") {
        alert("Number of places must be a number")
        forma.sde_gender2_places.focus();
        return false;
    }
    if(!isFloat(forma.sde_price, 2)) {
        alert("Price must be a number")
        forma.sde_price.focus();
        return false;
    }
    if(forma.sde_price.value == 0 ) {
        alert("Price can't be equal zero")
        forma.sde_price.focus();
        return false;
    }
    return true;
}

function sd_venue_edit(forma,mode) {
	if (mode == 'update' || mode == 'create'){
	    if(isEmpty(forma.name)) {
	        alert("Please enter Name");
	        forma.name.focus();
	        return false;
	    }
	    if(forma.country.value <= 0 ) {
	        alert("Please select Country");
	        forma.country.focus();
	        return false;
	    }
	    if(forma.city.value <= 0) {
	        alert("Please select city");
	        forma.city.focus();
	        return false;
	    }
	    if(forma.address.value === "") {
	        alert("Please enter Address");
	        forma.address.focus();
	        return false;
	    }
	    if(forma.phone.value === "") {
	        alert("Please enter Phone");
	        forma.phone.focus();
	        return false;
	    }
	    if(forma.description.value === "") {
	        alert("Please enter Description");
	        forma.description.focus();
	        return false;
	    }
	    if(forma.directions.value === "") {
	        alert("Please enter Directions");
	        forma.directions.focus();
	        return false;
	    }
	}
    return true;
}