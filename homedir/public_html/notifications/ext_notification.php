<?if ($option_manager->GetValue("js_notify")){?>

<style>

#notify_box A {

    font-size: 11px;

    font-weight: bold;

    padding: 2px 3px 1px 3px;

    font-family: sans-serif;

    height: 12px;

    width: 14px;

    border: 1px solid #aeaeae;

    display: block;

    color: black;

    text-decoration: none;

    float: right;

    text-align: center;

    margin: 2px 1px 2px 0;

    line-height: 11px;

}

#notify_box {
    display: none !important;

    width: 120px;

    border: 1px solid #cecece;

    background-color: #efefef;

    text-align: right;

    padding 0 0 20px 0;

    margin: 0px 0px 0px 0px;

}

#notify_inner_box {

    width: 120px;

    margin: 0px 0px 4px 0px;

    text-align: center;

    clear: both;

}

#notify_inner_box div {

    font-size: 11px;

    margin: 4px 2px 4px 2px;

    border: 1px solid #ccc;

    text-align: center;

    padding: 0px 0px 0px 0px;

    clear: both;

    height: 80px;

}

#notify_inner_box td {

    height: 100%;

    text-align: center;

}

#notify_inner_box table {

    width: 116px;

    height: 100%;

    margin: 0 0;

    padding: 0 0;

    cursor: pointer;

    background-color:white;

}



</style>

<div id=notify_box style="position:fixed; display:none; bottom:0; right:0;">

    <a href="#" onClick="hideNotifyBox()">X</a>

    <div id="notify_inner_box"></div>

</div>



<script src="<?=CONST_LINK_ROOT?>/moo.ajax/moo.ajax.js"></script>



<script language=javascript>

divId = 1;

divCnt=0;

function sendRequest(){

    new ajax(

            '<?=CONST_LINK_ROOT?>/notifications/notifications.php?>',

            {

                method: 'post',

                onComplete: function (transport) {

                    if (transport.responseText != '') {

                        try {

                            document.getElementById('notify_inner_box').insertBefore(addNewMessage(transport.responseText),document.getElementById('notify_inner_box').firstChild);

                        } catch(E) {

                            document.getElementById('notify_inner_box').appendChildFirst(addNewMessage(transport.responseText));

                        }

				    	document.getElementById('notify_box').style.display = 'block';

				    	document.getElementById('notify_box').style.right = 0;

                    }

                }

            }

        );

}



function addNewMessage(text) {

    newDiv = document.createElement("div");

    newDiv.innerHTML = '<table><tr><td>'+text+'</td></tr></table>';

    newDiv.onmousedown = function(ev) {hideMessageBox(this.id)}

    newDiv.id = "divId" + divId++;

    newDiv.style.display = 'block';

    setTimeout("hideMessageBox('"+newDiv.id+"')", 8000);

    divCnt++;

    return newDiv;

}



function hideNotifyBox() {

    document.getElementById('notify_box').style.display = 'none';

    document.getElementById('notify_inner_box').innerHTML = '';

    divCnt = 0;

}



function hideMessageBox(id) {

    if(document.getElementById(id) && (document.getElementById(id).style.display == 'block')){

        divCnt--;

        document.getElementById(id).style.display = 'none';

        document.getElementById(id).innerHTML = '';

        if (divCnt == 0 ) {

            hideNotifyBox();

        }

    }

}



//var curIObj  = null;



/**

* Calculate absolute Y coordinate of the specified element

*

* @param element object the object to calculate Y coordinate for

* @return int Y coordinate of the object

*/

function calcAbsTop(element) {

    theTop = 0;

    while(element != null) {

        theTop += element.offsetTop;

        element = element.offsetParent;

    }



    return theTop;

}



function InitBox()

{

//    curIObj = document.getElementById("notify_box");

    onTimer();

}



function onTimer()

{

    setTimeout("onTimer()", 100);

}



addToOnLoad("setInterval('sendRequest( )', 10000);InitBox();");

</script>

<?}?>