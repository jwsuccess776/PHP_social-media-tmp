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
# Name:         chatpage.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:      7.2
#
####################################################################
extract($_POST);
extract($_GET);

include('../db_connect.php');
require("function.php");

function handleURLs($content) {
   // $content = htmlentities(stripslashes(trim(urldecode($content))), ENT_QUOTES);
    $content = preg_replace("/(\015\012)|(\015)|(\012)/","<BR>", $content);
    $content = preg_replace("/(?:^|\b)((((http|https|ftp):\/\/)|(www\.))([\w\.]+)([,:%#&;\/?=\w+\.-]+))(?:\b|$)/is", "<font color=\"#0000FF\"><a href=\"$1\" target=\"_blank\">$1</a></font>", $content);$content = str_replace('<a href="www.', '<a href="http://www.', $content);
    $content = preg_replace("/([\w\.]+)(@)([\S\.]+)\b/i", "<font color=\"#0000FF\"><a href=\"mailto:$0\">$0</a></font>", $content);
    return $content;
}

if (!isset($_SESSION['Sess_UserName'])){
    exit("Incorrect Parameters");
} else {
    if (isset($action)) {
        if (isset($value)) {
            if ($action == "getInfoChat") {
                $sSQL = "SELECT status FROM my_friends WHERE uid='".$value."' and friend_uid='".$_SESSION['Sess_UserName']."'";
                $aResult = getRows($sSQL);
/*
                $handle = fopen("chat.txt", 'a+');
                fwrite($handle, "action => ".$action."\n");
                fwrite($handle, "cuid => ".$value."\n");
                fwrite($handle, "uid => ".$_SESSION['Sess_UserName']."\n");
                fwrite($handle, "sSQL => ".$sSQL."\n");
                fclose($handle);
*/

                if (empty($aResult) or $aResult['status'] == "A") {
                    echo "status=A";
                } else {
                    echo "status=B";
                }
            } else if ($action == "addMes") {
            /*
                $handle = fopen("chat.txt", 'a+');
                fwrite($handle, "action => ".$action."\n");
                fwrite($handle, "value => ".$value."\n");
                fwrite($handle, "font => ".$font."\n");
                fwrite($handle, "color => ".$color."\n");
                fwrite($handle, "text => ".$text."\n");
                fwrite($handle, "style_bold => ".$bold."\n");
                fwrite($handle, "style_italic => ".$italic."\n");
                fwrite($handle, "style_underline => ".$underline."\n");
             */
                $sString = "<b><font color=\"#000000\">".$_SESSION['Sess_UserName'].":&nbsp;&nbsp;</font></b>&nbsp;";
                $sendtxtfinal = "";

                $text = stripslashes(handleURLs($text));

                if ($bold == "T") {
                    $text = "<b>".$text."</b>";
                }
                if ($italic == "T") {
                    $text = "<i>".$text."</i>";
                }
                if ($underline == "T") {
                    $text = "<u>".$text."</u>";
                }
                $sendtxtfinal = $text;

                $sGetMes = "<b><font color=\"#0000CC\">".$_SESSION['Sess_UserName'].":&nbsp;&nbsp;</font></b>&nbsp;".'<font face="'.$font.'" color="#'.$color.'">'.$sendtxtfinal."</font>";
                $sendtxtfinal = $sString.'<font face="'.$font.'" color="#'.$color.'">'.$sendtxtfinal."</font>";

                $sendtxtfinal_save = base64_encode(urlencode(stripslashes($sendtxtfinal)));

                $sSQL = "INSERT INTO conversations (cid, uid, msgtext) VALUES ('".$cid."', '".$_SESSION['Sess_UserName']."', '".$sendtxtfinal_save."')";
//                fwrite($handle, "sql => ".$sSQL."\n\n");
                mysql_query($sSQL);

//                fwrite($handle, "final_result => ".$sendtxtfinal."\n\n");
//                fclose($handle);
                $sSQL = "SELECT status FROM iwannatalk WHERE uid='".$_SESSION['Sess_UserName']."' and cid='".$cid."'";
                $aResult = getRows($sSQL);
                if ($aResult['status'] == "C") {
                    $sSQL = "UPDATE iwannatalk SET status='F' WHERE uid='".$_SESSION['Sess_UserName']."' and cid='".$cid."'";
                    mysql_query($sSQL);
                }
                echo "mes=".urlencode($sGetMes);
            } else if ($action == "getMes") {
/*
                $handle = fopen("SQL.txt", 'a+');
                fwrite($handle, "action => ".$action."\n");
                fwrite($handle, "value => ".$value."\n");
                fwrite($handle, "uid => ".$_SESSION['Sess_UserName']."\n");
*/
                $sSQL = "SELECT status FROM iwannatalk WHERE uid='".$value."' and cid='".$_SESSION['Sess_UserName']."'";
                $aStatusFriend = getRows($sSQL);
                if ($aStatusFriend['status'] != "C") {
                    $phpCurTime = time()+15;
                    $sSQL_update = "UPDATE iwannatalk SET status='T', tstamp='".$phpCurTime."' WHERE uid='".$value."' and cid='".$_SESSION['Sess_UserName']."'";
                    mysql_query($sSQL_update);
                }
//                fwrite($handle, "sSQL_update => ".time()."-> $sSQL_update\n");

                $sSQL = "SELECT msgtext FROM conversations WHERE cid='".$_SESSION['Sess_UserName']."' and uid='".$value."'";
                $aResult = getAllRows($sSQL);
                $sResult = "mes=";
                if (!empty($aResult)) {
                    $i=0;
                    foreach ($aResult as $k => $v) {
                        if ($i == 0) {
                        $sResult .= stripslashes(base64_decode($v['msgtext']));
                        } else {
                            $sResult .= "<br>".stripslashes(base64_decode($v['msgtext']));
                        }
                        $i++;
                    }
                }
                $SQL_delete = "DELETE FROM conversations WHERE cid='".$_SESSION['Sess_UserName']."' AND uid='$value';";
                mysql_query($SQL_delete);
                if ($sResult != "mes=") {
                    $sResult .= "&sVisible=T";
                } else {
                    $sResult .= "&sVisible=F";
                }
                $phpTime= time()-10;
                $sSQL = "SELECT uid FROM online WHERE uid='".$value."' and tstamp > ".$phpTime;
                $aRes= getRows($sSQL);
//                fwrite($handle, "aRes => ".$aRes['uid']."\n");
//                fwrite($handle, "sSQL => ".$sSQL."\n");

                    if (empty($aRes)) {
                        $sResult .="&friendStatus=OffLine";
                    } else {
                        $sResult .="&friendStatus=OnLine";
                    }
                $sSQL_block = "SELECT status FROM my_friends WHERE uid='".$_SESSION['Sess_UserName']."' and friend_uid='".$value."'";
                $aBlock = getRows($sSQL_block);

                if ($aBlock['status'] == "B") {
                    $sResult .="&friendBlock=B";
                } else {
                    $sResult .="&friendBlock=A";
                }

                $sSQL_block = "SELECT status FROM my_friends WHERE uid='".$value."' and friend_uid='".$_SESSION['Sess_UserName']."'";
                $aBlock = getRows($sSQL_block);

                if ($aBlock['status'] == "B") {
                    $sResult .="&userBlock=B";
                } else {
                    $sResult .="&userBlock=A";
                }

//                fwrite($handle, "sResult => ".$sResult."\n");
//                fclose($handle);

                $sResult .="&phplmr=".urlencode('<font size="13">Last Message Check: '.date("F j, Y, g:i:s A").' <b>'.date("T").'</b></font>');
                echo $sResult;
            } else if ($action == "iwanttalk") {
                if ($value != "undefined") {
                    $sSQL = "SELECT * FROM iwannatalk WHERE (uid='".$_SESSION['Sess_UserName']."' and cid='".$value."')";
//                    $sSQL = "SELECT * FROM iwannatalk WHERE (uid='".$_SESSION['Sess_UserName']."' and cid='".$value."') or (cid='".$_SESSION['Sess_UserName']."' and uid='".$value."')";
                    $result=mysql_query($sSQL);
                    $aResult = mysql_fetch_array($result);

                    if (empty($aResult)) {
                        $phpTimer = time();
                        $sSQL = "SELECT * FROM iwannatalk WHERE (cid='".$_SESSION['Sess_UserName']."' and uid='".$value."')";
                        $result=mysql_query($sSQL);
                        $aResult = mysql_fetch_array($result);

                        if (!empty($aResult)) {
                            $status = "T";
                        } else {
                            $status = "F";
                        }
                        $sSQL = "SELECT status FROM my_friends WHERE uid='".$value."' and friend_uid='".$_SESSION['Sess_UserName']."'";
                        $aBlokUser = getRows($sSQL);

//                        $handle = fopen("!!!status.txt", 'a+');
//                        fwrite($handle, "sql => ".$sSQL."\n");
//                        fwrite($handle, "status => ".$aBlokUser['status']."\n");
//                        fclose($handle);
//dump($aBlokUser);
                        if ($aBlokUser['status'] == "A") {
                            $sSQL = "INSERT INTO iwannatalk (cid, uid, status, tstamp) VALUES ('".$value."', '".$_SESSION['Sess_UserName']."', '".$status."', '".$phpTimer."')";
                            mysql_query($sSQL);
                        }
                    }
                    echo "mes=T";
                }
            } else if ($action == "close") {
                $sSQL = "UPDATE iwannatalk SET status='C' WHERE uid='".$value."' and cid='".$_SESSION['Sess_UserName']."'";
//                    $handle = fopen("SQL.txt", 'a+');
//                    fwrite($handle, "close sSQL => ".time()."-> $sSQL\n");
                mysql_query($sSQL);

                $sSQL = "SELECT status FROM iwannatalk WHERE uid='".$_SESSION['Sess_UserName']."' and cid='".$value."'";
//                fwrite($handle, "close sSQL--------00000000 => ".time()."-> $sSQL\n");

                $aResult = getRows($sSQL);
                if ($aResult['status'] == "C") {
                    $sSQL1 = "DELETE FROM iwannatalk WHERE uid='".$_SESSION['Sess_UserName']."' and cid='".$value."'";
                    mysql_query($sSQL1);
                    $sSQL2 = "DELETE FROM iwannatalk WHERE uid='".$value."' and cid='".$_SESSION['Sess_UserName']."'";
                    mysql_query($sSQL2);

//                    fwrite($handle, "close sSQL--------11111111 => ".time()."-> $sSQL1\n");
//                    fwrite($handle, "close sSQL--------22222222 => ".time()."-> $sSQL2\n");
                }
//                fclose($handle);

                echo "test=t";
            } else if ($action == "logout") {
                $sSQL = "DELETE FROM iwannatalk WHERE uid='".$_SESSION['Sess_UserName'];
                mysql_query($sSQL);
                $sSQL = "DELETE FROM online WHERE uid='".$_SESSION['Sess_UserName']."'";
                mysql_query($sSQL);
                echo "logout=t";
            }
        }
    }
}

?>