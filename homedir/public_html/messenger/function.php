<?
/*****************************************************
* © copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:         function.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:      7.2
#
####################################################################
//include('../db_connect.php');
function dump_page($var) {
    if (function_exists('debug_backtrace')) {
        $Tmp1 = debug_backtrace();
    } else {
        $Tmp1 = array(
            'file' => 'UNKNOWN FILE',
            'line' => 'UNKNOWN LINE',
        );
    }
    echo "<FIELDSET STYLE=\"font:normal 12px helvetica,arial; margin:10px;\"><LEGEND STYLE=\"font:bold 14px helvetica,arial\">Dump - ".$Tmp1[0]['file']." : ".$Tmp1[0]['line']."</LEGEND><PRE>\n";
    print_r($var);
    echo "</PRE></FIELDSET>\n\n";
    flush();
}

function getAllRows ($sSQL) {
    if (!empty($sSQL)) {
        $aResult = array();
        $result = mysql_query($sSQL) or die(mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
            $aResult[] = $row;
        }

        return $aResult;
    } else {
        return false;
    }
}

function getRows($sSQL) {
    if (!empty($sSQL)) {
        $aResult = array();

        $result = mysql_query($sSQL);
        if (!empty($result)) {
        while ($row = mysql_fetch_assoc($result)) {
            $aResult = $row;
        }
        return $aResult;
        }
    } else {
        return false;
    }
}

function gRow ($sSQL) {
    $result = mysql_query($sSQL);
    $row = mysql_fetch_assoc($result);
    return $row;
}

function im_dump($var) {
    $handle = fopen("dump.txt", 'a+');

        if (function_exists('debug_backtrace')) {
            $Tmp1 = debug_backtrace();
        } else {
            $Tmp1 = array(
                'file' => 'UNKNOWN FILE',
                'line' => 'UNKNOWN LINE',
            );
        }
        fwrite($handle, "Dump - ".$Tmp1[0]['file']." : ".$Tmp1[0]['line']."\n");

        if (is_array($var)) {
            foreach ($var as $k => $v) {
                fwrite($handle, $k." => ".$v."\n");
                if (is_array($v)) {
                    foreach ($v as $kkk => $vvv) {
                        fwrite($handle, "      ".$kkk." => ".$vvv."\n");
                    }
                }
            }
        } else {
            fwrite($handle, $var."\n");
        }
        fwrite($handle, "\n");

    fclose($handle);

    flush();

}

function addQuotes($fp_String, $fp_isDisableHtmlEntities = false, $fp_Length_Max = 0) {
    if (is_array($fp_String)) {
        foreach ($fp_String as $k => $v) {
            $fp_String[$k] = addQuotes($v, $fp_Length_Max, $fp_isDisableHtmlEntities);
        }
    }
    if ($fp_Length_Max > 0 && is_string($fp_String)) {
        $fp_String = substr($fp_String, 0, $fp_Length_Max);
    }
    if (is_string($fp_String) && !$fp_isDisableHtmlEntities) {
        $fp_String = htmlentities($fp_String, ENT_NOQUOTES);
    }
    if (is_string($fp_String) && get_magic_quotes_gpc() != 1) {
        return addslashes($fp_String);
    } else {
        return $fp_String;
    }
}

function stripQuotes($fp_String) {
    if (is_array($fp_String)) {
        foreach ($fp_String as $k => $v) {
            $fp_String[$k] = stripQuotes($v);
        }
    }
    if (is_string($fp_String) && get_magic_quotes_gpc() == 1) {
        return stripslashes($fp_String);
    } else {
        return $fp_String;
    }
}

function changeStatusOn ($fp_uid) {
    mysql_query("DELETE FROM online WHERE uid='".$fp_uid."'");
    mysql_query("INSERT INTO online (uid, tstamp) VALUES ('".$fp_uid."', unix_timestamp(now())+10)");
}

function getStatus ($fp_uid) {
    $result=mysql_query("SELECT * FROM online WHERE uid='".$fp_uid."'");
    $aResult = mysql_fetch_array($result);

    if (!empty($aResult[0])) {
        return true;
    }
}

function addFriends ($fp_uid, $fp_cuid) {

    $result = mysql_query("SELECT mem_username FROM members WHERE mem_username ='".htmlentities($fp_cuid)."'");
    $userName = mysql_fetch_array($result);

    if (!empty($userName)) {
        $fp_cuid = $userName['mem_username'];
        $result=mysql_query("SELECT * FROM my_friends WHERE uid='".$fp_uid."' and friend_uid='".$fp_cuid."'");
        $aResult = mysql_fetch_array($result);
        if (empty($aResult)) {
            $query = "INSERT INTO my_friends (uid, friend_uid) VALUES ('".$fp_uid."', '".$fp_cuid."');";
            mysql_query($query);

//            $handle = fopen("action.txt", 'a+');
//            fwrite($handle, "action=addFriends");
//            fwrite($handle, "\n");
//            fclose($handle);
        } else {
            $SQL = "UPDATE my_friends SET is_deleted='F' WHERE uid='$fp_uid' AND friend_uid='".$fp_cuid."'";
            mysql_query($SQL);
        }
    }
}

function deleteFriends ($fp_uid, $fp_cuid) {
    $result=mysql_query("SELECT * FROM my_friends WHERE uid='".$fp_uid."' and friend_uid='".$fp_cuid."'");
    $aResult = mysql_fetch_array($result);
    if (!empty($aResult)) {
        $SQL = "UPDATE my_friends SET is_deleted='T' WHERE uid='$fp_uid' AND friend_uid='".$fp_cuid."'";
        mysql_query($SQL);
    }
}

function blockUnblock ($fp_uid, $fp_cuid) {
    $result=mysql_query("SELECT * FROM my_friends WHERE uid='".$fp_uid."' and friend_uid='".$fp_cuid."'");
    $aResult = mysql_fetch_array($result);
    if ($aResult['status'] == "A") {
        $SQL = "UPDATE my_friends SET status='B' WHERE uid='$fp_uid' AND friend_uid='".$fp_cuid."'";
    } else {
        $SQL = "UPDATE my_friends SET status='A' WHERE uid='$fp_uid' AND friend_uid='".$fp_cuid."'";
    }
    mysql_query($SQL);
}

function getOnlineImUser ($fp_cuid) {
    $result=mysql_query("SELECT * FROM online WHERE uid='".$fp_cuid."'");
        $aResult = mysql_fetch_array($result);

    if (!empty($aResult)) {
        $userTime = (int)$aResult['tstamp'];
        $curTime = time() - 10;
        if ($curTime > $userTime) {
            return "F";
        } else {
            return "T";
        }
    } else {
        return "F";
    }
}

function getList ($fp_uid) {
    $sSQL = "SELECT * FROM my_friends WHERE uid='".$fp_uid."' and is_deleted = 'F'";
    $aResultList = getAllRows($sSQL);
    $amount = count($aResultList);
    $amount--;
    $sResult = "amount=".$amount."&";
    $i=0;

    foreach ($aResultList as $k => $v) {

        $sResult .= "friend_name".$i."=".$v['friend_uid']."&";
        $sResult .= "online".$i."=".getOnlineImUser($v['friend_uid'])."&";
        $sResult .= "status".$i."=".$v['status']."&";

        $i++;
    }
    $sResult .= "uid=".$fp_uid;
    $phpTime = time();
    $sSQL = "SELECT uid FROM iwannatalk WHERE cid='".$fp_uid."' and status='F' or (cid='".$fp_uid."' and status='T' and tstamp < '".$phpTime."')";
//    $sSQL = "SELECT uid FROM iwannatalk WHERE cid='".$fp_uid."' and status='F' or (cid='".$fp_uid."' and status='T' and tstamp < '".time()."')";
//    $sSQL = "SELECT uid FROM iwannatalk WHERE cid='".$fp_uid."' and status='F' or cid='".$fp_uid."' and status='T' and tstamp < '".time()."'";
//    echo $sSQL;
    $result=mysql_query($sSQL);
    $openPage = "";
    $i=0;
    while ($aUsersTalk = mysql_fetch_assoc($result)){
        if (getOnlineImUser($aUsersTalk['uid']) == "T") {
            $openPage .= "&userTalk".$i."=".$aUsersTalk['uid'];
        }
        $i++;
    }
        if (empty($openPage)) {
            $sResult .= "&userTalk=F";
        } else {
            $sResult .= $openPage."&amountTalk=".$i;
        }
    echo $sResult;
//    $handle = fopen("!!!!!!!!!!!!!!!!!!!!!!11.txt", 'a+');
//    fwrite($handle, "sql => ".$sResult."\n");
//    fclose($handle);
}

function getFullUsersList ($fp_uid) {
    $curTime = time()-10;
    $sSQL = "SELECT uid FROM online WHERE uid !='".$fp_uid."' AND tstamp >= '".$curTime."'";
    $aResult = getAllRows($sSQL);
    $sSQL = "SELECT friend_uid, is_deleted FROM my_friends WHERE uid = '".$fp_uid."'";
    $aMyFriend = getAllRows($sSQL);

    $is_friend = false;
    $sString = "";

    $i = 0;
    foreach ($aResult as $k => $v) {
        foreach ($aMyFriend as $kk => $vv) {
            if ($v['uid'] == $vv['friend_uid']) {
                $is_friend = true;
                if ($vv['is_deleted'] == "T") {
                   $is_friend = false;
                }
            }
        }
        $sString .= "user".$i."=".$v['uid']."&friend".$i."=";

        if ($is_friend) {
            $sString .= "T";
        } else {
            $sString .= "F";
        }
        $sString .="&";

        $is_friend = false;
        $i++;
    }
    $amount = $i-1;
    $sString .= "uid=".$fp_uid."&amount=".$amount."&date=".urlencode(date('F j, Y, g:i:s A'))."&poyas=".date('T');

//    dump_page($aMyFriend);
//    dump_page($aResult);

    echo $sString;
}

function getInfoChat($fp_cuid, $fp_uid) {
    $sSQL = "SELECT status FROM my_friends WHERE uid='".$fp_cuid."' and friend_uid='".$fp_uid."'";
    $aResult = getRows($sSQL);
//    return $aResult;
    return "returntoPage=yra&blya=weqweq";
}

function logout($fp_uid) {
    $sSQL = "DELETE FROM iwannatalk WHERE uid='".$fp_uid."'";
    mysql_query($sSQL);
    $sSQL = "DELETE FROM online WHERE uid='".$fp_uid."'";
    mysql_query($sSQL);
    return "logout=t";
}
?>