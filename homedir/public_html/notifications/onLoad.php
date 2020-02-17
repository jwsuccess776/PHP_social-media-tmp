<script type="text/javascript" language="JavaScript">

    // Add javascript code as an element to the array to be executed on the page been loaded.

    var globalInitFunctionsList = new Array();

    function addToOnLoad(jsCode) {

        var l_length = globalInitFunctionsList.length;

        var l_localNewArray = new Array(l_length+1);

        for(var i=0; i<l_length;i++){

            l_localNewArray[i]=globalInitFunctionsList[i];

        }



        l_localNewArray[l_length] = jsCode;



        globalInitFunctionsList = l_localNewArray;

    }



    function globalInit() {

        for (i = 0; i < globalInitFunctionsList.length; i++) {

            eval(globalInitFunctionsList[i]);

        }

    }

</script>