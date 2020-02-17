<?php
    // You need to set this variable to be appropriate for your site, you will receive these from Userplane during account setup
    // If you have not received these values yet please contact Userplane at support@userplane.com or call (323) 938-4401
    include "../db_connect.php";
    include($CONST_INCLUDE_ROOT.'/session_handler.inc');
    $strDomainID = $CONST_USERPLANE_DOMAIN_FULL;
    $strFlashcomServer = "flashcom.$CONST_USERPLANE_DOMAIN.userplane.com";

    // You replace this with the sessionGUID or userID of the currently logged in user
    $strSessionGUID = $Sess_UserId;
    $strKey = "";

    // You will need to work on the sendCommand JavaScript function a few lines down to respond to any user clicks as you deem necessary

    $strDestinationUserID = $_GET["strDestinationUserID"];
?>

<html>
<head>
    <meta http-equiv=Content-Type content="text/html;  charset=ISO-8859-1">
    <title>Userplane AV InstantCommunicator</title>

    <script language="JavaScript">
    <!--
        function sendCommand( commandIn, valueIn )
        {
            if( commandIn == "focus" )
            {
                // DO NOT EDIT

                var icObject = getICObject();
                // only do the focus if we are sure it is not going remove focus from typing area
                if( icObject != null && ( icObject.focus != undefined || ( navigator.userAgent.indexOf( "MSIE" ) >= 0 && navigator.userAgent.indexOf( "Mac" ) >= 0 ) ) )
                {
                    window.focus();
                    icObject.focus();
                }
            }
            else if( commandIn == "print" )
            {
                // DO NOT EDIT

                openIMPrintWindow( valueIn );
            }
            else
            {
                // EDIT HERE: you will need to handle the following commands from the ic client
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
            }
        }

        function focusIt()
        {
            window.focus();

            var icObject = getICObject();

            if( icObject != null && icObject.focus != undefined )
            {
                icObject.focus();
            }
        }

        function getICObject()
        {
            if(document.all)
            {
                return document.all["ic"];
            }
            else if(document.layers)
            {
                return document.ic;
            }
            else if(document.getElementById)
            {
                return document.getElementById("ic");
            }

            return null;
        }

        function ic_DoFSCommand( command, args )
        {
            if( command == "print" )
            {
                openIMPrintWindow( args );
            }
            else if( command == "testFS" )
            {
                var icObject = document.getElementById("ic")
                //alert( icObject );
                icObject.setVariable( "fsCommandIsAvailable", "true" );
            }
            else
            {
                alert( "Command (fs): " + command + "\nValue: " + args );
            }
        }

        function openIMPrintWindow( strContents )
        {
            var newWindow = window.open("","imPrint","width=550,height=480,scrollbars=yes,resizable=yes,menubar=yes,location=no,status=no,directories=no,toolbar=no");
            newWindow.opener = self;
            newWindow.document.open();
            newWindow.document.write( strContents );
            newWindow.document.close();
            newWindow.print();
            newWindow.close();
        }
    //-->
    </script>

    <script language="VBScript">
    <!--
        //  Map VB script events to the JavaScript method - Netscape will ignore this...
        //  Since FSCommand fires a VB event under ActiveX, we respond here
        Sub ic_FSCommand(ByVal command, ByVal args)
            call ic_DoFSCommand(command, args)
        end sub
    -->
    </script>
</head>

<body onLoad="javascript: focusIt();" bgcolor="#ffffff" bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" topmargin="0">

    <?php
        if( $strDestinationUserID != "" )
        {
            $strSwfServer = "swf.userplane.com";
            $strApplicationName = "InstantCommunicator";
            $strLocale = "english";

            ?>
            <script type="text/javascript" src="<?=$CONST_USERPLANE_LINK_ROOT?>/flashobject.js"></script>

            <div id="flashcontent">
                <strong><a href="http://www.macromedia.com/go/getflash/" target="_blank">You need to upgrade your Flash Player by clicking this link</a></strong>
            </div>

            <script type="text/javascript">
                // <![CDATA[

                var fo = new FlashObject("http://<?php echo( $strSwfServer ); ?>/<?php echo( $strApplicationName ); ?>/ic.swf", "ic", "100%", "100%", "6", "#ffffff", false, "best");
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
                // WM version 1.8.12

                // ]]>
            </script>
    <?php } ?>

</div>
</body>
</html>
