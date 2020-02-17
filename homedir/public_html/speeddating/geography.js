

    function get_option(id,value){

    this.id=id;

    this.value=value;

    }

    var arrStateOptions = new Array();var arrCityOptions = new Array();arrCityOptions[204] = new Array();
arrCityOptions[204][3] = new Array(new get_option("3624","Buckeye"));
arrStateOptions[204] = new Array(new get_option("3","Arizona"));
var strCountryOptions = new Array(new get_option("204","United States of America"));

//<script>

Function.prototype.method = function (name, func) {

    this.prototype[name] = func;

    return this;

};



Function.method('inherits', function (parent) {

    var d = 0, p = (this.prototype = new parent());

    this.method('uber', function uber(name) {

        var f, r, t = d, v = parent.prototype;

        if (t) {

            while (t) {

                v = v.constructor.prototype;

                t -= 1;

            }

            f = v[name];

        } else {

            f = p[name];

            if (f == this[name]) {

                f = v[name];

            }

        }

        d += 1;

        r = f.apply(this, Array.prototype.slice.apply(arguments, [1]));

        d -= 1;

        return r;

    });

    return this;

});



function isFunction(a) {

    return typeof a == 'function';

}

function isNumber(a) {

    return typeof a == 'number' && isFinite(a);

}

function isObject(a) {

    return (a && typeof a == 'object') || isFunction(a);

}

function isUndefined(a) {

    return typeof a == 'undefined';

}



if (!isFunction(Function.apply)) {

    Function.method('apply', function (o, a) {

        var r, x = '____apply';

        if (!isObject(o)) {

            o = {};

        }

        o[x] = this;

        switch ((a && a.length) || 0) {

        case 0:

            r = o[x]();

            break;

        case 1:

            r = o[x](a[0]);

            break;

        case 2:

            r = o[x](a[0], a[1]);

            break;

        case 3:

            r = o[x](a[0], a[1], a[2]);

            break;

        case 4:

            r = o[x](a[0], a[1], a[2], a[3]);

            break;

        case 5:

            r = o[x](a[0], a[1], a[2], a[3], a[4]);

            break;

        case 6:

            r = o[x](a[0], a[1], a[2], a[3], a[4], a[5]);

            break;

        default:

            alert('Too many arguments to apply.');

        }

        delete o[x];

        return r;

    });

}

if (!isFunction(Array.prototype.push)) {

    Array.method('push', function () {

        this.splice.apply(this,

            [this.length, 0].concat(Array.prototype.slice.apply(arguments)));

        return this.length;

    });

}

if (!isFunction(Array.prototype.splice)) {

    Array.method('splice', function (s, d) {

        var max = Math.max,

            min = Math.min,

            a = [], // The return value array

            e,  // element

            i = max(arguments.length - 2, 0),   // insert count

            k = 0,

            l = this.length,

            n,  // new length

            v,  // delta

            x;  // shift count



        s = s || 0;

        if (s < 0) {

            s += l;

        }

        s = max(min(s, l), 0);  // start point

        d = max(min(isNumber(d) ? d : l, l - s), 0);    // delete count

        v = i - d;

        n = l + v;

        while (k < d) {

            e = this[s + k];

            if (!isUndefined(e)) {

                a[k] = e;

            }

            k += 1;

        }

        x = l - s - d;

        if (v < 0) {

            k = s + i;

            while (x) {

                this[k] = this[k - v];

                k += 1;

                x -= 1;

            }

            this.length = n;

        } else if (v > 0) {

            k = 1;

            while (x) {

                this[n - k] = this[l - k];

                k += 1;

                x -= 1;

            }

        }

        for (k = 0; k < i; ++k) {

            this[s + k] = arguments[k + 2];

        }

        return a;

    });

}



    function clearOptions(oSelect)

    {

        while (oSelect.options.length)

        {

            oSelect.options[0]=null;

        }

    }



    function getSelectedOptions(oSelect)

    {

        var arrSelected = new Array();

        for(var i = 0; i < oSelect.options.length; i++)

            if(oSelect.options[i].selected)

                arrSelected.push(oSelect.options[i]);

        return arrSelected;

    }



    function loadCountries(lstCountry)

    {

        for(i=0;i<strCountryOptions.length;i++) {

            var oOption = document.createElement("OPTION");

            oOption.innerHTML = strCountryOptions[i].value;

            oOption.value = strCountryOptions[i].id;

            lstCountry.appendChild(oOption);

        }

    }

    function loadStates(lstCountry, lstState)

    {

        lstState.disabled = true;

        clearOptions(lstState);



        if(lstCountry.selectedIndex < 0 ||

           isNaN(lstCountry.options[lstCountry.selectedIndex].value) ||

           lstCountry.options[lstCountry.selectedIndex].value == 0)

        {

//            lstState.selectedIndex = 0;

        }

        else

        {



            arrSelectedCountry = getSelectedOptions(lstCountry);



            for(var iCountry = 0; iCountry < arrSelectedCountry.length; iCountry++)

            {

                if(arrStateOptions[arrSelectedCountry[iCountry].value])

                {

                    var oOption = document.createElement("OPTION");

                    oOption.innerHTML = '-- '+arrSelectedCountry[iCountry].innerHTML+ ' --';

                    oOption.value = -1;

                    lstState.appendChild(oOption);



                    for(i=0;i<arrStateOptions[arrSelectedCountry[iCountry].value].length;i++) {

                        var oOption = document.createElement("OPTION");

                        oOption.innerHTML = arrStateOptions[arrSelectedCountry[iCountry].value][i].value;

                        oOption.value = arrStateOptions[arrSelectedCountry[iCountry].value][i].id;

                        oOption.cnt = arrSelectedCountry[iCountry].value;

                        lstState.appendChild(oOption);

                    }

                }

            }

        }

        if (!lstState.options.length)

        {

            var oOption = document.createElement("OPTION");

            oOption.innerHTML = '--- All States ---';

            oOption.value = 0;

            lstState.appendChild(oOption);

        } else {

            lstState.disabled=false;

        }

    }





    function loadCities(lstCountry, lstState, lstCity)

    {

        lstCity.disabled = true;

        clearOptions(lstCity);



        if ((lstCountry.selectedIndex < 0 ||

             isNaN(lstCountry.options[lstCountry.selectedIndex].value) ||

             lstCountry.options[lstCountry.selectedIndex].value == 0) &&

            (lstState.selectedIndex < 0 ||

             isNaN(lstState.options[lstState.selectedIndex].value) ||

             lstState.options[lstState.selectedIndex].value == 0))

        {

//            lstCity.selectedIndex = 0;

        }

        else

        {



            arrSelectedCountry = getSelectedOptions(lstCountry);

            arrSelectedState = getSelectedOptions(lstState);



            for(var iCountry = 0; iCountry < arrSelectedCountry.length; iCountry++)

            {

                if(arrStateOptions[arrSelectedCountry[iCountry].value])

                {

                    var oOption = document.createElement("OPTION");

                    oOption.innerHTML = '-- '+arrSelectedCountry[iCountry].innerHTML+ ' --';

                    oOption.value = -1;

                    lstCity.appendChild(oOption);



                    var bHasStatesSelected=false;



                    for(var iState = 0; iState < arrSelectedState.length; iState++)

                    {

                        for(j=0;j<arrStateOptions[arrSelectedCountry[iCountry].value].length;j++)

                        {

                            if(arrStateOptions[arrSelectedCountry[iCountry].value][j].id == arrSelectedState[iState].value)

                            {

                                bHasStatesSelected = true;

                                if (arrCityOptions[arrSelectedCountry[iCountry].value][arrSelectedState[iState].value])

                                {

                                    var oOption = document.createElement("OPTION");

                                    oOption.innerHTML = '- '+arrSelectedState[iState].innerHTML+ ' -';

                                    oOption.value = -1;

                                    lstCity.appendChild(oOption);



                                    for(i=0;i<arrCityOptions[arrSelectedCountry[iCountry].value][arrSelectedState[iState].value].length;i++)

                                    {

                                        var oOption = document.createElement("OPTION");

                                        oOption.innerHTML = arrCityOptions[arrSelectedCountry[iCountry].value][arrSelectedState[iState].value][i].value;

                                        oOption.value = arrCityOptions[arrSelectedCountry[iCountry].value][arrSelectedState[iState].value][i].id;

                                        oOption.cnt = arrSelectedState[iState].value;

                                        lstCity.appendChild(oOption);

                                    }

                                }

                            }

                        }

                    }

                    if (!bHasStatesSelected) {

                        var oOption = document.createElement("OPTION");

                        oOption.innerHTML = '- (Select State) -';

                        oOption.value = -1;

                        lstCity.appendChild(oOption);

                    }

                }

                else

                {

                    if(arrCityOptions[arrSelectedCountry[iCountry].value].length)

                    {

                        var oOption = document.createElement("OPTION");

                        oOption.innerHTML = '-- '+arrSelectedCountry[iCountry].innerHTML+ ' --';

                        oOption.value = -1;

                        lstCity.appendChild(oOption);

                        for(i=0;i<arrCityOptions[arrSelectedCountry[iCountry].value].length;i++) {

                            var oOption = document.createElement("OPTION");

                            oOption.innerHTML = arrCityOptions[arrSelectedCountry[iCountry].value][i].value;

                            oOption.value = arrCityOptions[arrSelectedCountry[iCountry].value][i].id;

                            oOption.cnt = arrSelectedCountry[iCountry].value;

                            lstCity.appendChild(oOption);

                        }

                    }

                }

            }

        }

        if (!lstCity.options.length)

        {

            var oOption = document.createElement("OPTION");

            oOption.innerHTML = '--- All Cities ---';

            oOption.value = 0;

            lstCity.appendChild(oOption);

        } else {

            lstCity.disabled=false;

        }

    }







    function onCountryListChange(strFormName, strCountrySelectName, strStateSelectName, strCitySelectName)

    {

        var lstCountry = document.getElementById(document.forms[strFormName][strCountrySelectName].id);

        var lstState = document.getElementById(document.forms[strFormName][strStateSelectName].id);

        var lstCity = document.getElementById(document.forms[strFormName][strCitySelectName].id);

        for(var i = 0; i < lstCountry.options.length; i++)

            if((lstCountry.options[i].value === '' || lstCountry.options[i].value == -1) && lstCountry.options[i].selected)

                lstCountry.options[i].selected = false;

        if((lstCountry.selectedIndex < 0 || lstCountry.options[lstCountry.selectedIndex].value == 0) && lstCountry.options[0].value == 0)

        {

//            lstCountry.selectedIndex = 0;

        }

        loadStates(lstCountry, lstState);

        if(eval(strFormName + '_' + strCitySelectName.replace(/[\[\]]/g,'') + '_initialized'))

            onStateListChange(strFormName, strCountrySelectName, strStateSelectName, strCitySelectName);

    }



    function onStateListChange(strFormName, strCountrySelectName, strStateSelectName, strCitySelectName)

    {

        var lstCountry = document.getElementById(document.forms[strFormName][strCountrySelectName].id);

        var lstState = document.getElementById(document.forms[strFormName][strStateSelectName].id);

        var lstCity = document.getElementById(document.forms[strFormName][strCitySelectName].id);

        if (lstState.multiple)

        {

        for(var i = 0; i < lstState.options.length; i++)

            if((lstState.options[i].value === '' || lstState.options[i].value == -1) && lstState.options[i].selected)

                lstState.options[i].selected = false;

        }

        if((lstState.selectedIndex < 0 || isNaN(lstState.options[lstState.selectedIndex].value)) && lstState.options[0].value == 0)

        {

//            lstState.selectedIndex = 0;

        }

        loadCities(lstCountry, lstState, lstCity);

        onCityListChange(strFormName, strCitySelectName);

    }



    function onCityListChange(strFormName, strCitySelectName)

    {

        var lstCity = document.getElementById(document.forms[strFormName][strCitySelectName].id);

        for(var i = 0; i < lstCity.options.length; i++)

            if((lstCity.options[i].value === '' || lstCity.options[i].value == -1) && lstCity.options[i].selected)

            {

                if(lstCity.multiple)

                    lstCity.options[i].selected = false;

                else

                {

//                    lstCity.selectedIndex = 0;

                }

            }

        if((lstCity.selectedIndex < 0 || lstCity.options[lstCity.selectedIndex].value == 0) && lstCity.options[0].value == 0)

        {

//            lstCity.selectedIndex = 0;

        }

    }



    function initialize(strFormName, strCountrySelectName, strStateSelectName, strCitySelectName, arrSelectedCountry, arrSelectedState, arrSelectedCity)

    {

        eval(strFormName + '_' + strCitySelectName.replace(/[\[\]]/g,'') + '_initialized = false');

        var lstCountry = document.getElementById(document.forms[strFormName][strCountrySelectName].id);

        loadCountries(lstCountry);

        var lstCountry = document.getElementById(document.forms[strFormName][strCountrySelectName].id);

        if(arrSelectedCountry)

        {

            for(iOption = 0; iOption < lstCountry.options.length; iOption++)

                for(iSelected = 0; iSelected < arrSelectedCountry.length; iSelected++)

                    if(lstCountry.options[iOption].value == arrSelectedCountry[iSelected])

                        lstCountry.options[iOption].selected = true;

        }

        onCountryListChange(strFormName, strCountrySelectName, strStateSelectName, strCitySelectName);

        var lstState = document.getElementById(document.forms[strFormName][strStateSelectName].id);

        if(arrSelectedState)

        {

            if(lstState.options[0].value == '0') lstState.options[0].selected = false;

            for(iOption = 0; iOption < lstState.options.length; iOption++)

                for(iSelected = 0; iSelected < arrSelectedState.length; iSelected++)

                    if(lstState.options[iOption].value == arrSelectedState[iSelected])

                        lstState.options[iOption].selected = true;

        }

        onStateListChange(strFormName, strCountrySelectName, strStateSelectName, strCitySelectName);

        var lstCity = document.getElementById(document.forms[strFormName][strCitySelectName].id);

        if(arrSelectedCity)

        {

            if(lstCity.options[0].value == '0') lstCity.options[0].selected = false;

            for(iOption = 0; iOption < lstCity.options.length; iOption++)

                for(iSelected = 0; iSelected < arrSelectedCity.length; iSelected++)

                    if(lstCity.options[iOption].value == arrSelectedCity[iSelected])

                        lstCity.options[iOption].selected = true;

        }

        onCityListChange(strFormName, strCitySelectName);

        eval(strFormName + '_' + strCitySelectName.replace(/[\[\]]/g,'') + '_initialized = true');

    }

//</script>

    