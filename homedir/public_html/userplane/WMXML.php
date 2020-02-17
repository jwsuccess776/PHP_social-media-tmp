<?php
    // This is the primary method of communication between Userplane and your servers.  See the ICXML.doc file
    // for detailed specs on how this file works and how to get it integrated with Userplane
    include('../db_connect.php');

    header( "Content-Type: text/xml" );

    echo( "<?xml version='1.0' encoding='iso-8859-1'?>" );
    echo( "<!-- COPYRIGHT Userplane 2004 (http://www.userplane.com) -->" );
    echo( "<!-- IC version 1.8.5 -->" );
    echo( "<icappserverxml>" );

    $strDomainID = isset($_GET['domainID']) ? $_GET['domainID'] : null;
    $strFunction = isset($_GET['function']) ? $_GET['function'] : (isset($_GET['action']) ? $_GET['action'] : null);

    if( $strFunction != null && $strDomainID != null )
    {
        $strSessionGUID = isset($_GET['sessionGUID']) ? $_GET['sessionGUID'] : null;
        $strKey = isset($_GET['key']) ? $_GET['key'] : null;
        $strUserID = isset($_GET['memberID']) ? $_GET['memberID'] : null;
        $strTargetUserID = isset($_GET['targetMemberID']) ? $_GET['targetMemberID'] : null;

        if( $strFunction == "getDomainPreferences" )
        {
            // get the value from your database

            echo( "<characterlimit>200</characterlimit>" );
            echo( "<forbiddenwordslist>ass,bitch</forbiddenwordslist>" );
            echo( "<smileys>" );
/*
                echo( "<smiley>" );
                    echo( "<name>Ultra Angry</name>" );
                    echo( "<image>http://images.$CONST_USERPLANE_DOMAIN.userplane.com/images/smiley/UltraAngry.jpg</image>" );
                    echo( "<codes>" );
                        echo( "<code><![CDATA[>>:O]]></code>" );
                        echo( "<code><![CDATA[>>:-O]]></code>" );
                    echo( "</codes>" );
                echo( "</smiley>" );

                echo( "<smiley>" );
                    echo( "<name>Angry</name>" );
                    echo( "<image>DELETE</image>" );
                echo( "</smiley>" );
*/
            echo( "</smileys>" );
            echo( "<maxvideobandwidth>20000</maxvideobandwidth>" );
            echo( "<domainlogolarge>$CONST_LINK_ROOT/".$skin->ImagePath."watermark.jpg</domainlogolarge>" );
            echo( "<line1>Age</line1>" );
            echo( "<line2>Sex</line2>" );
            echo( "<line3>Location</line3>" );
            echo( "<line4></line4>" );
            echo( "<avEnabled>true</avEnabled>" );
            echo( "<clickableUserName>true</clickableUserName>" );
            echo( "<printButton>false</printButton>" );
            echo( "<buddyListButton>true</buddyListButton>" );
            echo( "<preferencesButton>true</preferencesButton>" );
            echo( "<smileyButton>true</smileyButton>" );
            echo( "<addBuddyEnabled>true</addBuddyEnabled>" );
            echo( "<connectionTimeout>60</connectionTimeout>" );
            echo( "<sendTextToImages>false</sendTextToImages>" );
            echo( "<systemMessages>" );
                echo( "<waiting>true</waiting>" );
                echo( "<open>true</open>" );
                echo( "<closed>true</closed>" );
                echo( "<blocked>true</blocked>" );
                echo( "<away>true</away>" );
                echo( "<nonDeliveryTimeout>30</nonDeliveryTimeout>" );
                echo( "<nonDeliveryMessage>If [[DISPLAYNAME]] doesn't receive this message, they will be emailed when you close this window</nonDeliveryMessage>" );
            echo( "</systemMessages>" );
        }
        else if( $strFunction == "getMemberID" )
        {
            if( $strSessionGUID != null && $strSessionGUID != "" )
            {
                // get the value from your database
                $res = mysql_query("SELECT * FROM members WHERE mem_userid='$strSessionGUID'");
                $strSessionGUID =  ($row = mysql_fetch_object($res)) ? $row->mem_userid : 'INVALID';
                echo( "<memberid>" . $strSessionGUID . "</memberid>" );
            }
        }
        else if( $strFunction == "startIC" )
        {
            if( $strUserID != null && $strUserID != "" && $strTargetUserID != null && $strTargetUserID != "" )
            {
                // now that the target user's window is open, we can remove the request from the db
                // the values are reversed because this call happens from the other direction
                $res = mysql_query("SELECT *
                                    FROM adverts
                                        INNER JOIN members
                                            ON mem_userid=adv_userid
                                    WHERE adv_userid='$strUserID'");
                $user = mysql_fetch_object($res);

                include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
                $adv_user = new Adverts();
                $adv_user->InitByObject($user);
                $adv_user->SetImage('small');

                $res = mysql_query("
                                    SELECT
                                        *,
                                        (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age,
                                        mem_lastvisit
                                    FROM adverts
                                    LEFT JOIN members ON (adv_userid=mem_userid)
                                    LEFT JOIN geo_country ON (adv_countryid = gcn_countryid)
                                    LEFT JOIN geo_city ON (adv_cityid = gct_cityid)
                                    LEFT JOIN geo_state ON (adv_stateid = gst_stateid)
                                    WHERE adv_userid = '$strTargetUserID'");
                $targetuser = mysql_fetch_object($res);

                $strSessionGUID =  ($row = mysql_fetch_object($res)) ? $row->mem_username : 'INVALID';

                include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
                $adv_tuser = new Adverts();
                $adv_tuser->InitByObject($targetuser);
                $adv_tuser->SetImage('small');

                $query = "DELETE FROM userplane_pending_ic WHERE originatingUserID = $strTargetUserID AND destinationUserID = $strUserID";
                mysql_query( $query );

                echo( "<member>" );
                    echo( "<displayname>$user->adv_username</displayname>" );
                    echo( "<imagepath>$CONST_LINK_ROOT".$adv_user->adv_picture->Path."</imagepath>" );
                    echo( "<avEnabled>true</avEnabled>" );
                    echo( "<kissSmackEnabled>true</kissSmackEnabled>" );
                    echo( "<showerrors>true</showerrors>" );
                    echo( "<sound>true</sound>" );
                    echo( "<focus>true</focus>" );
                    echo( "<autoOpenAV>false</autoOpenAV>" );
                    echo( "<autoStartAudio>false</autoStartAudio>" );
                    echo( "<autoStartVideo>false</autoStartVideo>" );
                echo( "</member>" );
                if ($targetuser->adv_sex == 'F') $sex = GENDER_W;
                if ($targetuser->adv_sex == 'M') $sex = GENDER_M;
                if ($targetuser->adv_sex == 'C') $sex = GENDER_C;
                if($targetuser->gcn_countryid == 0)
                    $location = Unknown;
                else
                {
                    $location = $targetuser->gcn_name;
                    if($targetuser->gst_stateid != 0)
                        $location = "$targetuser->gst_name, $location";
                    if($targetuser->gct_cityid != 0)
                        $location = "$targetuser->gct_name, $location";
                }
                $block_row = $db->get_row("SELECT *
                                    FROM userplane_blocked
                                    WHERE userid = $strUserID
                                        AND targetuserid = $strTargetUserID");
                $blocked = ($block_row) ? "true" : "false";
                echo( "<targetMember>" );
                    echo( "<displayname>$targetuser->adv_username</displayname>" );
                    echo( "<line1>$targetuser->age</line1>" );
                    echo( "<line2>$sex</line2>" );
                    echo( "<line3>$location</line3>" );
                    echo( "<line4></line4>" );
                    echo( "<imagepath>$CONST_LINK_ROOT".$adv_tuser->adv_picture->Path."</imagepath>" );
                    echo( "<avEnabled>false</avEnabled>" );
                    echo( "<blocked>$blocked</blocked>" );
                echo( "</targetMember>" );
            }
        }
        else if( $strFunction == "addFriend" )
        {
            if( $strUserID != null && $strUserID != "" && $strTargetUserID != null && $strTargetUserID != "" )
            {
                    mysql_query("   REPLACE userplane_friends
                                    SET
                                        userid = $strUserID,
                                        targetuserid = $strTargetUserID");
            }
        }
        else if( $strFunction == "setBlockedStatus" )
        {
            if( $strUserID != null && $strUserID != "" && $strTargetUserID != null && $strTargetUserID != "" )
            {
                $bBlocked = isset($_GET['trueFalse']) ? $_GET['trueFalse'] : null;
                $bBlocked = $bBlocked == "true" || $bBlocked == "1";

                if ($bBlocked) {
                    mysql_query("   REPLACE userplane_blocked
                                    SET
                                        userid = $strUserID,
                                        targetuserid = $strTargetUserID");
                }
                else
                {
                    mysql_query("   DELETE FROM userplane_blocked
                                    WHERE userid = $strUserID AND targetuserid = $strTargetUserID");
                }
            }
        }
        else if( $strFunction == "startConversation" )
        {
            if( $strUserID != null && $strUserID != "" && $strTargetUserID != null && $strTargetUserID != "" )
            {
                // check to see if there is already a request to open a window in the db
                $query = "SELECT count(*) AS rowExists FROM userplane_pending_ic WHERE originatingUserID = $strUserID AND destinationUserID = $strTargetUserID";
                $rsArray = mysql_fetch_array( mysql_query( $query ) );
                if( $rsArray["originatingUserID"] == 0 )
                {
                    // if not, insert a request to have a window opened up on the target user's machine
                    $query = "INSERT INTO userplane_pending_ic ( originatingUserID, destinationUserID, openedWindowAt, insertedAt ) VALUES ( $strUserID, $strTargetUserID, NULL, Now() )";
                    mysql_query( $query );
                }
            }
        }
        else if( $strFunction == "notifyConnectionClosed" )
        {
            if( $strUserID != null && $strUserID != "" && $strTargetUserID != null && $strTargetUserID != "" )
            {
                // since the orginating user is closing their window, don't open a window on the target user anymore
                $query = "DELETE FROM userplane_pending_ic WHERE originatingUserID = $strUserID AND destinationUserID = $strTargetUserID";
                mysql_query( $query );

                // in addition, you can also use the strXmlData variable to get any messages that were never delivered to the targetUser.
                $strXmlData = isset($_POST['xmlData']) ? $_POST['xmlData'] : null;
                if ($strXmlData)  {
                    $text = strip_tags(preg_replace_callback('/<message><!\[CDATA\[(.*?)\]\]><\/message>/',create_function('$matches','return "Message: $matches[1]\n";'),$strXmlData,-1));
                }
                if ($text) {
                     include_once __INCLUDE_CLASS_PATH."/class.Messages.php";
                     include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

                     $user = new Adverts();
                     $user->InitById($strUserID);

                     $messages = new Messages();
                     $messages->send($strUserID,$strTargetUserID,$user->adv_username,"Undelivered IM messages",$text);
                }

            }
        }
    }

    echo( "</icappserverxml>" );
?>