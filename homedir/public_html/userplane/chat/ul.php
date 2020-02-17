<?php
    /*
     *	You need to set these variables to be appropriate for your site.  You
     *	can either do this here or in the page that includes this one.
     */
    include("../db_connect.php");
    $strFlashcomServer = "flashcom.$CONST_USERPLANE_DOMAIN.userplane.com";
    $strDomainID = $CONST_USERPLANE_DOMAIN_FULL;
    $strSessionGUID = $Sess_UserId;			// The session identified for the currently logged in user
    $strKey = "";					// Addionaly login information you may need passed
?>

<html>
<head>
    <meta http-equiv=Content-Type content="text/html;  charset=ISO-8859-1">
    <title>Userplane AV UserList</title>

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
        }

        function launchIC( userID, destinationUserID )
        {
            var popupWindowTest = window.open( "ic.php?strDestinationMemberID=" + destinationUserID, "ICWindow_" + replaceAlpha(userID) + "_" + replaceAlpha(destinationUserID), "width=360,height=420,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=0" );
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

    $strFlashVars = "strServer=" . $strFlashcomServer . "&strSwfServer=" . $strSwfServer . "&strDomainID=" . $strDomainID . "&strSessionGUID=" . $strSessionGUID . "&strKey=" . $strKey . "&strLocale=" . $strLocale;
?>

<object
    classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
    codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
    width="100%"
    height="100%"
    name="ul"
    id="ul"
    align="">
    <param name="movie" value="http://<?php echo( $strSwfServer ); ?>/<?php echo( $strApplicationName ); ?>/ul.swf">
    <param name="quality" value="best">
    <param name="scale" value="noscale">
    <param name="bgcolor" value="#FFFFFF">
    <param name="menu" value="0">
    <param name="salign" value="LT">
    <param name="FlashVars" value="<?php echo( $strFlashVars ); ?>">
    <embed
        src="http://<?php echo( $strSwfServer ); ?>/<?php echo( $strApplicationName ); ?>/ul.swf"
        quality="best"
        scale="noscale"
        bgcolor="#FFFFFF"
        menu="0"
        width="100%"
        height="100%"
        name="ul"
        align=""
        salign="LT"
        type="application/x-shockwave-flash"
        pluginspage="http://www.macromedia.com/go/getflashplayer"
        flashvars="<?php echo( $strFlashVars ); ?>">
    </EMBED>
</object>
<!-- COPYRIGHT Userplane 2004 (http://www.userplane.com) -->
<!-- CS version 1.7.0 -->

</body>
</html>
