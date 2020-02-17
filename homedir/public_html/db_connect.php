<?php
/****************************************************
* copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:         db_connect.php
#
# Description:  Admin tool to send latest matches to members by mail
#
# Version:      8.0
#
######################################################################

 	ini_set ( 'session.bug_compat_warn' , '0' );
   //sns comment if(DB_CONNECT == 1) return;
    define("DB_CONNECT",1);

    $DEMO = false;

    $CONST_INCLUDE_ROOT = "/home/knowled5/public_html/";
    $CONST_LINK_ROOT = 'https://www.sugardaddylink.com';

    // define("__CONST_DB_HOST","localhost");
    // define("__CONST_DB_NAME","knowled5_maindb");
    // define("__CONST_DB_USER","knowled5_dbuser");
    // define("__CONST_DB_PASS","U=9IAx+ngxZ5");

    define("__CONST_DB_HOST","localhost");
    define("__CONST_DB_NAME","knowled5_maindb");
    define("__CONST_DB_USER","root");
    define("__CONST_DB_PASS","");


    $phpVerStr = phpversion ();
    $phpVerArr = explode ( '.' , $phpVerStr );
    $phpVer = $phpVerArr [0];


    $CONST_SD_URL = "$CONST_LINK_ROOT/speeddating";          // Site URL www.sitename.com

    if ( $phpVer == '5' ) {
        define( '__INCLUDE_CLASS_PATH' ,$CONST_INCLUDE_ROOT."/lib/php5");
        date_default_timezone_set ( 'Europe/London' );
    } else {
        putenv("TZ=Europe/London");
        define("__INCLUDE_CLASS_PATH",$CONST_INCLUDE_ROOT."/lib");
    }

    define("BLOCK_PERIOD_AVAILABLE", 300);//in seconds
    define("ONLINE_TIMEOUT_PERIOD", 3);//in minutes

    $CONST_SPEED_MATCH = 60*60*24*5;  //how many time have users to define their matches after speed dating
    $CONST_SPEED_SELECT = 2;
    $CONST_INITIAL_REFERAL = 2;
    $CONST_SUBSEQUENT_REFERAL = 1;
    $CONST_PAYFLOW_ROOT = "/content/StartupHostPlus/i/n/www.idatemedia.com/web/payflow/bin";

    $CONST_INIT_FILE = $CONST_INCLUDE_ROOT."/init.php";
    include $CONST_INIT_FILE;

    ini_set('memory_limit', '32M');

// Database Connection
$globalMysqlConn = mysqli_connect(__CONST_DB_HOST,__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME);


?>