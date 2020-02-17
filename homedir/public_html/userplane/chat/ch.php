<?php
    /*
     *  You need to set these variables to be appropriate for your site.  You
     *  can either do this here or in the page that includes this one.
     */
    include "../../db_connect.php";
    include($CONST_INCLUDE_ROOT.'/session_handler.inc');
    $strFlashcomServer = "flashcom.".$CONST_USERPLANE_DOMAIN.".userplane.com";
    $strDomainID = $CONST_USERPLANE_DOMAIN_FULL;
    $strSessionGUID = $Sess_UserId;         // The session identified for the currently logged in user
    $strKey = "";                   // Addionaly login information you may need passed
?>
<html>
<head>
    <meta http-equiv=Content-Type content="text/html;  charset=ISO-8859-1">
    <title>Userplane AV WebChat</title>

    <script language="JavaScript">
    <!--
        function csEvent( strEvent, strParameter1, strParameter2 )
        {
            if( strEvent == "InstantCommunicator.StartConversation" )
            {
                var strUserID = strParameter1;
                // open up an InstantCommunicator window.  For example:
                launchIC( "<?php echo( $strSessionGUID ); ?>", strUserID );
            }
            else if( strEvent == "User.ViewProfile" )
            {
                var strUserID = strParameter1;
                window.open("<?=$CONST_LINK_ROOT?>/prgretuser.php?userid="+strUserID);
            }
            else if( strEvent == "User.Block" )
            {
                var strBlockedUserID = strParameter1;
                var bBlocked = strParameter2;
            }
            else if( strEvent == "User.AddFriend" )
            {
                var strFriendUserID = strParameter1;
                var bFriend = strParameter2;
            }
            else if( strEvent == "Chat.Help" )
            {
            	window.open("<?=$CONST_LINK_ROOT?>/userplane/chat/WebchatHelp.pdf");
            }
            else if( strEvent == "User.NoTextEntry" )
            {
            }
        }

        function launchIC( userID, destinationUserID )
        {
            var popupWindowTest = window.open( "<?=$CONST_USERPLANE_LINK_ROOT?>/wm.php?strDestinationUserID=" + destinationUserID, "ICWindow_" + replaceAlpha(userID) + "_" + replaceAlpha(destinationUserID), "width=360,height=420,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=0" );
            if( popupWindowTest == null )
            {
                if( confirm( "Your popup blocker stopped an InstantCommunicator window from opening\nPlease disable it and then click 'ok'" ) )
                {
                    launchIC( userID, destinationUserID );
                }
            }
        }

        function replaceAlpha( strIn )
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
    //-->
    </script>
</head>
<body bgcolor="#ffffff" bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" topmargin="0">
<?php
    // Do not change anything below here
    $strSwfServer = "swf.userplane.com";
    $strApplicationName = "CommunicationSuite";
    $strLocale = "english";
?>

    <script type="text/javascript" src="<?=$CONST_USERPLANE_LINK_ROOT?>/flashobject.js"></script>

<?php
//  The content of this div should hold whatever HTML you would like to show in the case that the
//  user does not have Flash installed.  Its contents get replaced with the Flash movie for everyone
//  else.
?>
<div id="flashcontent">
    <strong><a href="http://www.macromedia.com/go/getflash/" target="_blank">You need to upgrade your Flash Player by clicking this link</a></strong>
</div>
<script type="text/javascript">
    // <![CDATA[

    var fo = new FlashObject("http://<?php echo( $strSwfServer ); ?>/<?php echo( $strApplicationName ); ?>/ch.swf", "ch", "100%", "100%", "6", "#ffffff", false, "best");
    fo.addParam("scale", "noscale");
    fo.addParam("menu", "false");
    fo.addParam("salign", "LT");
    fo.addVariable("strServer", "<?php echo( $strFlashcomServer ); ?>");
    fo.addVariable("strSwfServer", "<?php echo( $strSwfServer ); ?>");
    fo.addVariable("strApplicationName", "<?php echo( $strApplicationName ); ?>");
    fo.addVariable("strDomainID", "<?php echo( $strDomainID ); ?>");
    fo.addVariable("strSessionGUID", "<?php echo( $strSessionGUID ); ?>");
    fo.addVariable("strKey", "<?php echo( $strKey ); ?>");
    fo.addVariable("strLocale", "<?php echo( $strLocale ); ?>");
    fo.addVariable("strInitialRoom", "<?php echo( $strInitialRoom ); ?>");
	fo.addParam("allowScriptAccess","always");
    fo.write("flashcontent");

    // COPYRIGHT Userplane 2006 (http://www.userplane.com)
    // CS version 1.8.9

    // ]]>
</script>


</body>
</html>
