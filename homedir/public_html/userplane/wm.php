<?php
//if ($strSessionID) session_id($strSessionID);
include "../db_connect.php";
// You need to set this variable to be appropriate for your site, you will receive these from Userplane during account setup
    // If you have not received these values yet please contact Userplane at support@userplane.com or call (323) 938-4401
    $strDomainID = $CONST_USERPLANE_DOMAIN_FULL;
    $strFlashcomServer = "flashcom.$CONST_USERPLANE_DOMAIN.userplane.com";

    // You replace this with the sessionGUID or userID of the currently logged in user

    $strEncryptedUserID = isset($_GET['strEncryptedUserID']) ? $_GET['strEncryptedUserID'] : null;
    if( $strEncryptedUserID === null)
    {
        include($CONST_INCLUDE_ROOT.'/session_handler.inc');
        $strSessionGUID = $Sess_UserId;
    }
    else
    {
        $strSessionGUID = up_getDecrypted($option_manager->GetValue('userplane_presence_password'),$strEncryptedUserID);
    }

    $strKey = "";

    // You will need to work on the sendCommand JavaScript function a few lines down to respond to any user clicks as you deem necessary

    $strDestinationUserID = $_GET["strDestinationUserID"];
//    if (!$strDestinationUserID) $strDestinationUserID = $_REQUEST['?strDestinationUserID'];

?>

<html>
<head>
    <meta http-equiv=Content-Type content="text/html;  charset=ISO-8859-1">
    <title>Userplane AV Webmessenger</title>

    <script language="JavaScript">
    <!--
        function sendCommand( commandIn, valueIn )
        {
            if( commandIn == "focus" )
            {
                // DO NOT EDIT

                var wmObject = getWMObject();
                // only do the focus if we are sure it is not going remove focus from typing area
                if( wmObject != null && ( wmObject.focus != undefined || ( navigator.userAgent.indexOf( "MSIE" ) >= 0 && navigator.userAgent.indexOf( "Mac" ) >= 0 ) ) )
                {
                    window.focus();
                    wmObject.focus();
                }
            }
            else
            {
                // EDIT HERE: you will need to handle the following commands from the wm client
                if( commandIn == "viewProfile" )
                {
                    if( valueIn == "-1" )
                    {
                        window.open("<?=$CONST_LINK_ROOT?>/view_profile.php");// view their own profile
                    }
                    else
                    {
                        var userID = valueIn;
                        window.open("<?=$CONST_LINK_ROOT?>/prgretuser.php?userid="+userID);// view their own profile
                        // view userID's profile
                    }
                }
                else if( commandIn == "help" )
                {
                    // view the help
                }
                else if( commandIn == "buddyList" )
                {
                    // view their buddy list
                }
                else if( commandIn == "preferences" )
                {
                    // view the preferences
                }
                else if( commandIn == "addBuddy" )
                {
                    var userID = valueIn;
                    // respond to an add buddy click (XML has also been notified)
                }
                else if( commandIn == "block" )
                {
                    // they blocked the user
                }
                else if( commandIn == "unblock" )
                {
                    // they unblocked the user
                }
                else if( commandIn == "Connection.Success" )
                {
                    // client successfully connected to server
                }
                else if( commandIn == "Connection.Failure" )
                {
                    // client was disconnected from server
                }
            }
        }

        function focusIt()
        {
            window.focus();

            var wmObject = getWMObject();

            if( wmObject != null && wmObject.focus != undefined )
            {
                wmObject.focus();
            }
        }

        function getWMObject()
        {
            if(document.all)
            {
                return document.all["wm"];
            }
            else if(document.layers)
            {
                return document.wm;
            }
            else if(document.getElementById)
            {
                return document.getElementById("wm");
            }

            return null;
        }

        function wm_DoFSCommand( command, args )
        {
        }
    //-->
    </script>

    <script language="VBScript">
    <!--
        //  Map VB script events to the JavaScript method - Netscape will ignore this...
        //  Since FSCommand fires a VB event under ActiveX, we respond here
        Sub wm_FSCommand(ByVal command, ByVal args)
            call wm_DoFSCommand(command, args)
        end sub
    -->
    </script>
</head>
<body onLoad="javascript: focusIt();" bgcolor="#ffffff" bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" topmargin="0">

    <?php
        if( $strDestinationUserID != "" )
        {
            $strSwfServer = "swf.userplane.com";
            $strApplicationName = "Webmessenger";
            $strLocale = "english";
            ?>

            <script type="text/javascript" src="<?=$CONST_USERPLANE_LINK_ROOT?>/flashobject.js"></script>

            <!---
                The content of this div should hold whatever HTML you would like to show in the case that the
                user does not have Flash installed.  Its contents get replaced with the Flash movie for everyone
                else.
            --->
            <div id="flashcontent">
                <strong>You need to upgrade your Flash Player by clicking <a href="http://www.macromedia.com/go/getflash/" target="_blank">this link</a>.</strong><br><br><strong>If you see this and have already upgraded we suggest you follow <a href="http://www.adobe.com/cfusion/knowledgebase/index.cfm?id=tn_14157" target="_blank">this link</a> to uninstall Flash and reinstall again.</strong>
            </div>

            <script type="text/javascript">
                // <![CDATA[

                var fo = new FlashObject("http://<?php echo( $strSwfServer ); ?>/<?php echo( $strApplicationName ); ?>/ic.swf", "wm", "100%", "100%", "6", "#ffffff", false, "best");
                fo.addParam("scale", "noscale");
                fo.addParam("menu", "false");
                fo.addParam("salign", "LT");
                fo.addVariable("server", "<?php echo( $strFlashcomServer ); ?>");
                fo.addVariable("swfServer", "<?php echo( $strSwfServer ); ?>");
                fo.addVariable("applicationName", "<?php echo( $strApplicationName ); ?>");
                fo.addVariable("domainID", "<?php echo( $strDomainID ); ?>");
                fo.addVariable("sessionGUID", "<?php echo( $strSessionGUID ); ?>");
                fo.addVariable("key", "<?php echo( $strKey ); ?>");
                fo.addVariable("locale", "<?php echo( $strLocale ); ?>");
                fo.addVariable("destinationMemberID", "<?php echo( $strDestinationUserID ); ?>");
                fo.addVariable("resizable", "true");
                fo.write("flashcontent");

                // COPYRIGHT Userplane 2006 (http://www.userplane.com)
                // WM version 1.8.13

                // ]]>
            </script>

            <?php
        }
    ?>

</div>

</body>
</html>











