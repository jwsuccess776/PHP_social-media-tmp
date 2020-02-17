<?php include('../db_connect.php'); ?>
/*
 *  It is of critical importance that this file is referenced inside the <body> tag of every page
 *  on your site.  For example:
 *
 *  <body>
 *  <script src="/userplane/functions.js" type="text/javascript" language="javascript"></script>
 *
 *  !!! WE HIGHLY RECOMMEND YOU LEAVE up_bDoPresence TO true AND TO IMPLEMENT THE CODE IN ALL php
 *  PAGES IN THIS FOLDER !!!
 *
 *  Set up_bDoPresence to false if you have your own presence detection and will check for pending WM
 *  windows and call up_launchWM on your own.  If so, you will need to call up_launchWM for every
 *  pending WM window every time the user changes pages.  Also, you will need to implement the logic
 *  in the wmWindowOpened.php page to let you know when requests have been denied and also when windows
 *  have successfully popped up
 */
var up_bDoPresence = true;
/*
 *  If you have up_bDoPresence set to true, this is how often (in seconds) we will connect to your
 *  server via wmLauncher.php or win_ie_pd.php
 */
var up_iCheckSeconds = 10;

// PLEASE DO NOT CHANGE ANY CODE BELOW THIS LINE

var userplane_path = '<?=$CONST_USERPLANE_LINK_ROOT?>';


function up_launchWM( userID, destinationUserID, destinationName )
{
    up_localUserID = userID;

    var popupWindowTest = null;

    popupWindowTest = window.open( userplane_path+"/wm.php?strDestinationUserID=" + destinationUserID, "WMWindow_" + up_replaceAlpha(userID) + "_" + up_replaceAlpha(destinationUserID), "width=360,height=397,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=yes" );
    if( popupWindowTest == null )
    {
        up_showWMNotify( userID, destinationUserID, destinationName );
    }
    else
    {
        up_clearWMNotify( destinationUserID, false );
    }
}

function up_launchWM_free( userID, destinationUserID, destinationName )
{
    up_localUserID = userID;

    var popupWindowTest = null;

    popupWindowTest = window.open( userplane_path+"/frame_wm.php?strDestinationUserID=" + destinationUserID, "WMWindow_" + up_replaceAlpha(userID) + "_" + up_replaceAlpha(destinationUserID), "width=468,height=505,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=yes" );
    if( popupWindowTest == null )
    {
        up_showWMNotify( userID, destinationUserID, destinationName );
    }
    else
    {
        up_clearWMNotify( destinationUserID, false );
    }
}


function up_showWMNotify( userID, destinationUserID, destinationName )
{
    up_localUserID = userID;

    var bAdd = true;
    for( var i = 0 ; i < up_launchArray.length ; i++ )
    {
        if( up_launchArray[i].destID == destinationUserID )
        {
            if( destinationName != undefined )
            {
                up_launchArray[i].destName = "<strong>" + destinationName + "</strong>";
            }
            bAdd = false;
        }
    }

    destinationName = destinationName == undefined ? "A website member" : "<strong>" + destinationName + "</strong>";

    if( bAdd )
    {
        var userObj = new Object();
        userObj.destID = destinationUserID;
        userObj.destName = destinationName;
        up_launchArray.push( userObj );
    }

    up_showNotification();
}

function up_clearWMNotify( clearDestID, bForceClear )
{
    var tempArray = up_launchArray;
    up_launchArray = new Array();

    var bRemoved = false;

    while( tempArray.length > 0 )
    {
        var userObj = tempArray.pop();

        if( userObj.destID != clearDestID )
        {
            up_launchArray.push( userObj );
        }
        else
        {
            bRemoved = true;
        }
    }

    if( bRemoved || !bForceClear )
    {
        if( bForceClear )
        {
            frames['up_wmReject'].location.href = userplane_path + "/cmd.php?strDestinationUserID=" + clearDestID + "&refresh=" + Math.floor( Math.random() * 100000000000 );
        }
        frames['up_wmLauncher'].location.href = userplane_path + "/wmWindowOpened.php?destinationUserID=" + clearDestID + "&doPresence=" + ( up_bDoPresence && !up_is_win_ie ? "true" : "false" ) + "&forceClear=" + ( bForceClear ? "true" : "false" ) + "&iRefreshInterval=" + up_iCheckSeconds + "&refresh=" + Math.floor( Math.random() * 100000000000 );
    }

    up_showNotification();
}

function up_showNotification()
{
    var elem = document.getElementById( "up_wmNotifications" );

    if( up_launchArray.length > 0 )
    {
        if( up_displayedNotificationID != up_launchArray[0].destID )
        {
            elem.innerHTML = '<div class="up_mbox"><div class="up_mbox1"><div class="up_mbox2"></div></div><div class="up_mboxgut" style="text-align:center">' + ( up_is_win_ie ? '' : '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td align="center">' ) + '<table border="0" cellpadding="2" cellspacing="5"><tr><td nowrap align="center"><strong style="font-size:larger;">Incoming IM Message</strong></td></tr><tr><td align="center">' + up_launchArray[0].destName + ' wants to IM you.<br>Would you like to accept?</td></tr><tr><td nowrap align="center"><a style="font-size:larger;" href="" onClick="javascript: up_launchWM<?if ($option_manager->GetValue('userplane_im_free')){?>_free<?}?>( \'' + up_localUserID + '\', \'' + up_launchArray[0].destID + '\' ); return false;">Yes</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a style="font-size:larger;" href="" onClick="javascript: up_clearWMNotify( \'' + up_launchArray[0].destID + '\', true ); return false;">No</a></td></tr></table>' + ( up_is_win_ie ? '' : '</td></tr></table>' ) + '</div><div class="up_mbox3"><div class="up_mbox4"></div></div></div>';
            up_displayedNotificationID = up_launchArray[0].destID;
            up_animate( 30 );
        }
    }
    else
    {
        up_displayedNotificationID = "";
        up_animate( -200 );
    }
}

function up_animate( iWhereTo )
{
    var elem = document.getElementById( "up_wmNotifications" );
    if( up_iDivCurrentY != iWhereTo || up_launchArray.length > 0 )
    {
        if( up_iDivCurrentY != iWhereTo )
        {
            up_iDivCurrentY += iWhereTo < up_iDivCurrentY ? -10 : 10;
        }

        var px = up_iDivCurrentY + document.body.scrollTop + 'px';
        elem.style.top = px;

        clearTimeout( up_animate_timeoutID );
        up_animate_timeoutID = setTimeout( "up_animate(" + iWhereTo + ")", 33 );
    }
    else
    {
        elem.style.top = iWhereTo;
    }
}

function up_replaceAlpha( strIn )
{
    var strOut = "";
    for( var i = 0 ; i < strIn.length ; i++ )
    {
        var cChar = strIn.charAt(i);
        if( ( cChar >= 'A' && cChar <= 'Z' )
            || ( cChar >= 'a' && cChar <= 'z' )
            || ( cChar >= '0' && cChar <= '9' ) )
        {
            strOut += cChar;
        }
        else
        {
            strOut += "_";
        }
    }

    return strOut;
}

function up_checkWM()
{
    if( up_is_win_ie )
    {
        up_wmCheckImage = new Image();
        up_wmCheckImage.onLoad = up_onImageLoad();
        up_wmCheckImage.src = userplane_path + "/win_ie_pd.php?refresh=" + Math.floor( Math.random() * 100000000000 );
        clearTimeout( up_checkWM_timeoutID );
        up_checkWM_timeoutID = setTimeout("up_checkWM()", 1000 * up_iCheckSeconds);
    }
    else
    {
        frames['up_wmLauncher'].location.href = userplane_path + "/wmLauncher.php?iRefreshInterval=" + up_iCheckSeconds + "&refresh=" + Math.floor( Math.random() * 100000000000 );
    }
}

function up_onImageLoad()
{
    if (!up_wmCheckImage.complete)
    {
        clearTimeout( up_onImageLoad_timeoutID );
        up_onImageLoad_timeoutID = setTimeout("up_onImageLoad()", 250);
    }
    else
    {
        if( up_wmCheckImage.height == 2 )
        {
            frames['up_wmLauncher'].location.href = userplane_path + "/wmLauncher.php?iRefreshInterval=0&refresh=" + Math.floor( Math.random() * 100000000000 );
        }
        else
        {
            //alert( "Nothing to launch" );
        }

        clearTimeout( up_checkWM_timeoutID );
        up_checkWM_timeoutID = setTimeout("up_checkWM()", 1000 * up_iCheckSeconds);
    }
}

document.write( '<style type="text/css">' );
document.write( '.up_mbox { background: #ffffff url('+userplane_path+'/images/fbox.gif) repeat-y top right; border: 0; margin: 2px 0 6px 0; padding: 0; }' );
document.write( '.up_mbox1 { background: url('+userplane_path+'/images/fbox1.gif) no-repeat top right; height: 8px; font: normal 0px/0px Sans-serif; margin: 0; padding: 0; }' );
document.write( '.up_mbox2 { display: block; background: url('+userplane_path+'/images/fbox2.gif) no-repeat top left; height: 8px; width: 8px; font: normal 0px/0px Sans-serif; margin: 0; padding: 0; }' );
document.write( '.up_mbox3 { background: url('+userplane_path+'/images/fbox3.gif) no-repeat bottom right; height: 8px; font: normal 0px/0px Sans-serif; margin: 0; padding: 0; }' );
document.write( '.up_mbox4 { display: block; background: url('+userplane_path+'/images/fbox4.gif) no-repeat bottom left; height: 8px; width: 8px; font: normal 0px/0px Sans-serif; margin: 0; padding: 0; }' );
document.write( '.up_mboxgut { background: url('+userplane_path+'/images/fboxgut.gif) repeat-y top left; font: 11px/18px Verdana, Geneva, Arial, Helvetica, Helve, Sans-serif; margin: 0; padding: 0 10px 0 10px; word-wrap: break-word; }' );
document.write( '</style>' );
document.write( '<iframe name="up_wmLauncher" id="up_wmLauncher" style="position:absolute; top: -200px; z-index:9998; width:100px; height:100px; border: 0px" src=""></iframe>' );
document.write( '<iframe name="up_wmReject" id="up_wmReject" style="position:absolute; top: -200px; z-index:9999; width:100px; height:100px; border: 0px" src=""></iframe>' );
document.write( '<div id="up_wmNotifications" style="position:absolute; width:250px; z-index:9997; left: 30px; top: -200px;"></div>' );
var up_iDivCurrentY = -200;

var up_launchArray = new Array();
var up_localUserID = "";
var up_displayedNotificationID = "";

var up_wmCheckImage = null;
var up_checkWM_timeoutID = null;
var up_onImageLoad_timeoutID = null;
var up_animate_timeoutID = null;

// determine if is Windows IE (up_is_win_ie)
var up_agt          = navigator.userAgent.toLowerCase();
var up_appVer       = navigator.appVersion.toLowerCase();
var up_is_mac       = up_agt.indexOf('mac') != -1;
var up_is_safari    = up_agt.indexOf('safari') != -1 && up_is_mac;
var up_is_khtml     = up_is_safari || up_agt.indexOf('konqueror') != -1;
var up_is_ie        = up_appVer.indexOf('msie') != -1 && up_agt.indexOf("opera") == -1 && !up_is_khtml;
var up_is_win       = up_is_mac ? false : (up_agt.indexOf("win") != -1 || up_agt.indexOf("16bit") != -1);
var up_is_win_ie    = up_is_win && up_is_ie;

if( up_bDoPresence )
{
    up_checkWM();
}



