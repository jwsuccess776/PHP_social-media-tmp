<?php
    /*
     *	You need to set these variables to be appropriate for your site.  You
     *	can either do this here or in the page that includes this one.
     */
    include "../db_connect.php";
    $strDomainID = $CONST_USERPLANE_DOMAIN_FULL;
    $strSessionGUID = $Sess_UserId;
    $strEvent = "";				// The event to send to the Flash Communication Server (see CSClient.doc)
    $strParameter = "";			// Any additonal data the message needs to pass (see CSClient.doc)







    // Do not change anything below here
    $strSwfServer = "swf.userplane.com";
    $strApplicationName = "CommunicationSuite";
    $strLocale = "english";

    $strFlashVars = "strDomainID=" . $strDomainID . "&strSessionGUID=" . $strSessionGUID . "&strEvent=" . $strEvent . "&strParameter=" . $strParameter . "&strLocale=" . $strLocale;
?>
<object
    classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
    codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
    width="1"
    height="1"
    name="cmd"
    id="cmd"
    align="">
    <param name="movie" value="http://<?php echo( $strSwfServer ); ?>/<?php echo( $strApplicationName ); ?>/cmd.swf">
    <param name="quality" value="best">
    <param name="scale" value="noborder">
    <param name="bgcolor" value="#FFFFFF">
    <param name="menu" value="0">
    <param name="salign" value="LT">
    <param name="FlashVars" value="<?php echo( $strFlashVars ); ?>">
    <embed
        src="http://<?php echo( $strSwfServer ); ?>/<?php echo( $strApplicationName ); ?>/cmd.swf"
        quality="best"
        scale="noborder"
        bgcolor="#FFFFFF"
        menu="0"
        width="1"
        height="1"
        name="cmd"
        align=""
        salign="LT"
        type="application/x-shockwave-flash"
        pluginspage="http://www.macromedia.com/go/getflashplayer"
        flashvars="<?php echo( $strFlashVars ); ?>">
    </EMBED>
</object>
<!-- COPYRIGHT Userplane 2004 (http://www.userplane.com) -->
<!-- CS version 1.7.0 -->
