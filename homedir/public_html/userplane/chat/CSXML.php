<?php

include "../../db_connect.php";



    header( "Content-Type: text/xml" );



    echo( "<?xml version='1.0' encoding='iso-8859-1'?>" );

    echo( "<!-- COPYRIGHT Userplane 2004 (http://www.userplane.com) -->" );

    echo( "<!-- CS version 2.0.2 -->" );

    echo( "<communicationsuite>" );



    echo( "<time>" . date("F d, Y h:i:s A") . "</time>" );



    $strDomainID = isset($_GET['domainID']) ? $_GET['domainID'] : null;

    $strFunction = isset($_GET['function']) ? $_GET['function'] : (isset($_GET['action']) ? $_GET['action'] : null);



        if( $strFunction != null && $strDomainID != null )

        {



        $strSessionGUID = isset($_GET['sessionGUID']) ? $_GET['sessionGUID'] : null;

        $strKey = isset($_GET['key']) ? $_GET['key'] : null;

        $strUserID = isset($_GET['userID']) ? $_GET['userID'] : null;

        $strRoomName = isset($_GET['roomName']) ? $_GET['roomName'] : null;

        $strBlockedUserID = isset($_GET['blockedUserID']) ? $_GET['blockedUserID'] : null;

        $strFriendUserID = isset($_GET['friendUserID']) ? $_GET['friendUserID'] : null;

        $bConnected = isset($_GET['connected']) ? $_GET['connected'] : null;

        $bConnected = $bConnected == "true" || $bConnected == "1";

        $bAdmin = isset($_GET['admin']) ? $_GET['admin'] : null;

        $bAdmin = $bAdmin == "true" || $bAdmin == "1";

        $bExists = isset($_GET['exists']) ? $_GET['exists'] : null;

        $bExists = $bExists == "true" || $bExists == "1";

        $bInRoom = isset($_GET['inRoom']) ? $_GET['inRoom'] : null;

        $bInRoom = $bInRoom == "true" || $bInRoom == "1";

        $bBlocked = isset($_GET['blocked']) ? $_GET['blocked'] : null;

        $bBlocked = $bBlocked == "true" || $bBlocked == "1";

        $bBanned = isset($_GET['banned']) ? $_GET['banned'] : null;

        $bBanned = $bBanned == "true" || $bBanned == "1";

        $bFriend = isset($_GET['friend']) ? $_GET['friend'] : null;

        $bFriend = $bFriend == "true" || $bFriend == "1";





        switch( $strFunction )

        {

            case "getDomainPreferences":

                echo( "<domain>" );



                    echo( "<domainPrefReloadInterval>-1</domainPrefReloadInterval>");

                    echo( "<maxUsers includeAdmins=\"true\">100</maxUsers>");

                    echo( "<domainInvalid>false</domainInvalid>");

                    echo( "<adminsRequired>false</adminsRequired>");

   					echo( "<conferenceCallEnabled>false</conferenceCallEnabled>");             

                    echo( "<avenabled>true</avenabled>" );

                    echo( "<forbiddenwordslist>crap,shit</forbiddenwordslist>" );

                    echo( "<smileys>" );

/*

                        echo( "<smiley>" );

                            echo( "<name>Ultra Angry</name>" );

                            echo( "<image>http://images.yourCompany.userplane.com/images/smiley/UltraAngry.jpg</image>" );

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

                    echo( "<userlist>" );

                        echo( "<labels>" );

                            echo( "<usercolumn>USER NAME</usercolumn>" );

                            echo( "<onlinecolumn>ONLINE</onlinecolumn>" );

                            echo( "<buddylist>BUDDY LIST</buddylist>" );

                            echo( "<onlinelist>ALL ONLINE USERS</onlinelist>" );

                            echo( "<nobuddys>No Buddies Added</nobuddys>" );

                            echo( "<nousers>No Users Online</nousers>" );

                            echo( "<additionalcolumns>" );

/*

                                echo( "<column>COMPANY</column>" );

                                echo( "<column>TITLE</column>" );

                                echo( "<column>LOCATION</column>" );

  */

                            echo( "</additionalcolumns>" );

                        echo( "</labels>" );

                    echo( "</userlist>" );

                    echo( "<chat>" );

                        echo( "<labels>" );

                            echo( "<userdata initiallines=\"0\">" );

                                echo( "<line>Age</line>" );

                                echo( "<line>Sex</line>" );

                                echo( "<line>Location</line>" );

                            echo( "</userdata>" );

                            echo( "<lobby><name>Waiting Room</name><description>Waiting Room</description></lobby>" );

                        echo( "</labels>" );

                        echo( "<characterlimit>200</characterlimit>" );

                        echo( "<userroomcreate>true</userroomcreate>" );

                        echo( "<roomemptytimeout>600</roomemptytimeout>" );

                        echo( "<gui>" );

                            echo( "<viewprofile>true</viewprofile>" );

                            echo( "<instantcommunicator>true</instantcommunicator>" );

                            echo( "<addfriend>true</addfriend>" );



							echo( "<reportAbuse textLines=\"5\" avEnabled=\"false\" avWebAccessible=\"false\" avSeconds=\"30\" avUserID=\"0\">false</reportAbuse>" );



                            echo( "<block>true</block>" );

                            echo( "<images>" );

                                echo( "<watermark alpha=\"50\">$CONST_LINK_ROOT/".$skin->ImagePath."watermark.jpg</watermark>" );

                            echo( "</images>" );

                            echo( "<initialinputlines>1</initialinputlines>" );

                        echo( "</gui>" );

                        echo( "<roomlist>" );

                            // Make as many as you want, these will always appear when the app reloads (even if deleted in the client)

                            $res = mysql_query("SELECT * FROM userplane_room_chat");

                            while ($room_row = mysql_fetch_object($res)) {

                                echo( "<room><name>$room_row->name</name><description>$room_row->description</description></room>" );

                            }

                        echo( "</roomlist>" );

                    echo( "</chat>" );

                echo( "</domain>" );

                break;



            case "getUser":

                if( $strSessionGUID != null || $strUserID != null )

                {

                    $user_id = $strUserID;

                    if( $strUserID == null || strlen(trim($strUserID)) == 0 )

                    {

                        $user_id = $strSessionGUID;

                        // Need to look up the userID from strSessionGUID and strKey.  If valid, set the value as so...

                    }

                    $res = mysql_query("

                                SELECT *,

                                    (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age,

                                    mem_lastvisit

                                FROM adverts

                                LEFT JOIN members ON (adv_userid=mem_userid)

                                LEFT JOIN geo_country ON (adv_countryid = gcn_countryid)

                                LEFT JOIN geo_city ON (adv_cityid = gct_cityid)

                                LEFT JOIN geo_state ON (adv_stateid = gst_stateid)

                                LEFT JOIN userplane_banned_chat ON (userid = adv_userid)

                                WHERE adv_userid = '$user_id'");

                    if ($user = mysql_fetch_object($res)) {

                        $strUserID = (!$user->userid) ? $user->adv_userid : null;

                    } else {

                        $strUserID = null;

                    }



                    if( $strUserID != null || strlen(trim($strUserID)) > 0 )

                    {

                        // Need to look up data for the specified userID

                        echo( "<user>" );

                            echo( "<userid>" . $strUserID . "</userid>" );

                            $admin = ($user->mem_type=='A') ? "true" : "false";

                            echo( "<admin>$admin</admin>" );

                            echo( "<displayname>$user->mem_username</displayname>" );

                            echo( "<avsettings>" );

                                echo( "<avenabled>true</avenabled>" );

                                echo( "<audioSend>true</audioSend>" );

                                echo( "<videoSend>true</videoSend>" );

                                echo( "<audioReceive>true</audioReceive>" );

                                echo( "<videoReceive>true</videoReceive>" );

                                echo( "<audiokbps>16</audiokbps>" );        // acceptable values: 10,16,22,44,88

                                echo( "<videokbps>100</videokbps>" );       // recommended range: 10 - 200

                                echo( "<videofps>15</videofps>" );          // acceptable range: 1 - 30

                            echo( "</avsettings>" );

                            echo( "<buddyviewableonly>false</buddyviewableonly>" );

                            echo( "<buddylist>" );

                            $res = mysql_query("SELECT * FROM userplane_friends_chat WHERE userid='$strUserID'");

                            while ($fr_row = mysql_fetch_object($res)) {

                                echo( "<userid>$fr_row->targetuserid</userid>" );

                            }

                            echo( "</buddylist>" );

                            echo( "<blocklist>" );

                            $res = mysql_query("SELECT * FROM userplane_blocked_chat WHERE userid='$strUserID'");

                            while ($bl_row = mysql_fetch_object($res)) {

                                echo( "<userid>$bl_row->targetuserid</userid>" );

                            }

                            echo( "</blocklist>" );

                            echo( "<images>" );

                                include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

                                $adv_user = new Adverts();

                                $adv_user->InitByObject($user);

                                $adv_user->SetImage('small');

                                $small_image = $adv_user->adv_picture->Path;

                                $adv_user->SetImage('medium');

                                $medium_image = $adv_user->adv_picture->Path;

                                $adv_user->SetImage('');

                                $full_image = $adv_user->adv_picture->Path;



                                echo( "<icon>$CONST_LINK_ROOT/$small_image</icon>" );

                                echo( "<thumbnail>$CONST_LINK_ROOT/$medium_image</thumbnail>" );

                                echo( "<fullsize>$CONST_LINK_ROOT/$full_image</fullsize>" );

                            echo( "</images>" );

                            echo( "<userlist>" );

                                echo( "<additionalcolumns>" );

                                /*

                                    echo( "<column>Milpitas, CA</column>" );

                                    echo( "<column>Honda of Milpitas</column>" );

                                    echo( "<column>2003 CBR F4</column>" );

                                    */

                                echo( "</additionalcolumns>" );

                            echo( "</userlist>" );

                            echo( "<chat>" );

                                	echo( "<userdatavalues>" );

                                    if ($user->adv_sex == 'F') $sex = GENDER_W;

                                    if ($user->adv_sex == 'M') $sex = GENDER_M;

                                    if ($user->adv_sex == 'C') $sex = GENDER_C;

                                    if($user->gcn_countryid == 0)

                                        $location = Unknown;

                                    else

                                    {

                                        $location = $user->gcn_name;

                                        if($user->gst_stateid != 0)

                                            $location = "$user->gst_name, $location";

                                        if($user->gct_cityid != 0)

                                            $location = "$user->gct_name, $location";

                                    }

                                    echo( "<line>$user->age</line>" );

                                    echo( "<line>$sex</line>" );

                                    echo( "<line>$location</line>" );

                                	echo( "</userdatavalues>" );

                                echo( "<gui>" );

                                    echo( "<viewprofile>true</viewprofile>" );

                                    echo( "<instantcommunicator>true</instantcommunicator>" );

                                echo( "</gui>" );

                                echo( "<notextentry>false</notextentry>" );

                                echo( "<invisible>false</invisible>" );

                                echo( "<userroomcreate>$admin</userroomcreate>" );

                                echo( "<adminrooms>" );

                                if ($admin == "true"){

                                $res = mysql_query("SELECT * FROM userplane_room_chat");

                                while ($room_row = mysql_fetch_object($res)) {

                                    echo( "<room createOnLogin='$admin'><name>$room_row->name</name><description>$room_row->name</description></room>" );

                                }

                                }

                                echo( "</adminrooms>" );

                                echo( "<initialroom></initialroom>" );

                            echo( "</chat>" );

                        echo( "</user>" );

                    }

                    else

                    {

                        // This means we weren't able to find it so they are invalid

                        echo( "<user>" );

                            echo( "<userid>INVALID</userid>" );

                        echo( "</user>" );

                    }

                }

                break;



            case "onRoomStatusChange":



                if( $strRoomName != null && $strUserID != null )

                {

                    // bExists is the true or false boolean that specifies whether the room exists or not

                    // bAdmin is also available (see docs)

                    if( $bExists )

                    {

                        mysql_query("INSERT INTO userplane_room_chat SET name='$strRoomName', userid='$strUserID'");

                    }

                    else

                    {

                        mysql_query("DELETE FROM userplane_room_chat WHERE name='$strRoomName'");

                    }

                    // Handle this event, no need to return anything else



                }

                break;



            case "onUserConnectionChange":

                if( $strUserID != null )

                {

                    // $bConnected is whether they are currently connected

                    if( $bConnected )

                    {

                    }

                    else

                    {

                    }

                    // Handle this event, no need to return anything else

                }

                break;



            case "onUserRoomChange":

                if( $strRoomName != null && $strUserID != null )

                {

                    // bInRoom is the true or false boolean that specifies whether they are in the room

                    if( $bInRoom )

                    {

                        mysql_query("INSERT INTO userplane_room_chat_member SET name='$strRoomName', userid='$strUserID'");

                    }

                    else

                    {

                        mysql_query("DELETE FROM userplane_room_chat_member WHERE name='$strRoomName' AND userid='$strUserID'");

                    }

                    // Handle this event, no need to return anything else

                }

                break;



            case "setBannedStatus":

                if( $strUserID != null )

                {

                    // bBanned is true or false whether userID has been banned by an admin

                    if( $bBanned )

                    {

                        mysql_query("   REPLACE userplane_banned_chat

                                        SET

                                        userid = $strUserID

                                    ");



                    }

                    else

                    {

                        mysql_query("   DELETE FROM userplane_banned_chat

                                        WHERE userid = $strUserID");



                    }

                    // Handle this event, no need to return anything else

                }

                break;



            case "setBlockedStatus":

                if( $strUserID != null && $strBlockedUserID != null )

                {

                    // bBlocked is the true or false boolean that specifies whether they are blocked

                    if( $bBlocked )

                    {

                        mysql_query("   REPLACE userplane_blocked_chat

                                        SET

                                            userid = $strUserID,

                                            targetuserid = $strBlockedUserID");

                    }

                    else

                    {

                        mysql_query("   DELETE FROM userplane_blocked_chat

                                        WHERE userid = $strUserID AND targetuserid = $strBlockedUserID");



                    }

                    // Handle this event, no need to return anything else

                }

                break;



            case "setFriendStatus":

                if( $strUserID != null && $strFriendUserID != null )

                {

                    // bFriend is a boolean true or false whether strUserID is adding or removing strFriendUserID from friend list

                    if( $bFriend )

                    {

                        mysql_query("   REPLACE userplane_friends_chat

                                        SET

                                            userid = $strUserID,

                                            targetuserid = $strFriendUserID");

                    }

                    else

                    {

                        mysql_query("   DELETE FROM userplane_friends_chat WHERE userid = $strUserID AND targetuserid = $strFriendUserID");

                    }

                    // Handle this event, no need to return anything else

                }

                break;



            default:

                break;

        }

    }



    echo( "</communicationsuite>" );

?>

