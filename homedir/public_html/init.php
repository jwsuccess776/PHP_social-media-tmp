<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:                 init.php
#
# Description:  Home page destination for traffic sent by affiliates
#
# Version:               7.2
#
######################################################################

if ( ! defined ('___INIT_PHP_INCLUDED') ) {
    define ( '___INIT_PHP_INCLUDED' , '___INIT_PHP_INCLUDED' );

    define("CONST_INCLUDE_ROOT",$CONST_INCLUDE_ROOT);
    define("CONST_LINK_ROOT",$CONST_LINK_ROOT);

    // require_once(__INCLUDE_CLASS_PATH."/lib.ErrorHandler.php");
    require_once("/lib.ErrorHandler.php");
    include_once __INCLUDE_CLASS_PATH."/lib.Functions.php";
    include_once __INCLUDE_CLASS_PATH."/class.Microtime.php";
    include_once __INCLUDE_CLASS_PATH."/class.ItemManager.php";
    $te  = new Microtime;
    $_time  =& $te->getInstance();
    $_time->dumpTime('start');
    require_once(__INCLUDE_CLASS_PATH."/class.DB.php");
    //include_once __INCLUDE_CLASS_PATH."/lib.DbSession.php";
    include_once __INCLUDE_CLASS_PATH."/class.Language.php";
    include_once __INCLUDE_CLASS_PATH."/class.Pager.php";
    include_once __INCLUDE_CLASS_PATH."/class.Skin.php";
    include_once __INCLUDE_CLASS_PATH."/class.Banner.php";
    $_time->dumpTime('stop include');
	
    $CONST_ADMIN_LINK_ROOT = $CONST_LINK_ROOT."/admin";
    $CONST_BLOG_LINK_ROOT = $CONST_LINK_ROOT."/blog";
    define('CONST_BLOG_LINK_ROOT', $CONST_BLOG_LINK_ROOT);
    $CONST_FORUM_LINK_ROOT = $CONST_LINK_ROOT."/forum";
    define('CONST_FORUM_LINK_ROOT', $CONST_FORUM_LINK_ROOT);
    $CONST_GROUPS_LINK_ROOT = $CONST_LINK_ROOT."/groups";
    define('CONST_GROUPS_LINK_ROOT', $CONST_GROUPS_LINK_ROOT);
    $CONST_GALLERY_LINK_ROOT = $CONST_LINK_ROOT."/gallery";

    $CONST_NETWORK_LINK_ROOT = $CONST_LINK_ROOT."/network";
    define("CONST_NETWORK_LINK_ROOT",$CONST_NETWORK_LINK_ROOT);
    $CONST_NETWORK_INCLUDE_ROOT = $CONST_INCLUDE_ROOT."/network";

    $CONST_DIRECTORY_LINK_ROOT = $CONST_LINK_ROOT."/directory";
    $CONST_DIRECTORY_INCLUDE_ROOT = $CONST_INCLUDE_ROOT."/directory";
    $CONST_DIRECTORY_LINK_GENERATE = $CONST_DIRECTORY_LINK_ROOT.'/sites';
    $CONST_DIRECTORY_INCLUDE_GENERATE = $CONST_DIRECTORY_INCLUDE_ROOT.'/sites';

    $CONST_USERPLANE_LINK_ROOT = $CONST_LINK_ROOT."/userplane";

    $CONST_MEDIA_LINK_ROOT = $CONST_LINK_ROOT;
    
    include_once('validation_functions.php'); 

//    define("CONST_INCLUDE_ROOT",$CONST_INCLUDE_ROOT);

    $_time->dumpTime('stop include');
    $dblink=new db();
    $db =$dblink->getInstance();
   //$db =& db::getInstance();
    $_time->dumpTime('create db');
    $OptionManagerLink=new OptionManager();
    $option_manager=$OptionManagerLink->GetInstance();
    
    //$option_manager =& OptionManager::GetInstance();
    $_time->dumpTime('create options');
    
    $LanguageLink=new Language();
    $language=$LanguageLink->GetInstance();
    //$language =& Language::GetInstance();
    $_time->dumpTime('create language');

    //$skin =& Skin::GetInstance();
    $SkinLink=new Skin();
    $skin= $SkinLink->GetInstance();
    
    $_time->dumpTime('create skin');

    $db->connect(__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME,__CONST_DB_HOST);
    //$link=mysqli_connect(__CONST_DB_HOST, __CONST_DB_USER, __CONST_DB_PASS);
    //if (!$link) die ("Database connection failure");
    //mysqli_select_db($link,__CONST_DB_NAME) or die("Failure in connection ".mysqli_error() );
    $_time->dumpTime('connect to db');

     session_cache_limiter('private,must-revalidate');
     session_start();
    //$_SESSION['private'] = "must-revalidate";
    $Sess_UserId=$_SESSION['Sess_UserId'];
    $_time->dumpTime('start session');

    $temp_res = explode('/',$_SERVER["SCRIPT_NAME"]);
    $SCRIPT_NAME = array_pop($temp_res);
    unset($temp_res);

################################################################
##  Check user IP                                             ##

    if (isset($_SERVER)) {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $IP = $_SERVER["HTTP_X_FORWARDED_FOR"];
            $proxy  = $_SERVER["REMOTE_ADDR"];
        } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $IP = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $IP = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
            $IP = getenv( 'HTTP_X_FORWARDED_FOR' );
            $proxy = getenv( 'REMOTE_ADDR' );
        } elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
            $IP = getenv( 'HTTP_CLIENT_IP' );
        } else {
            $IP = getenv( 'REMOTE_ADDR' );
        }
    }
    $RemoteInfo=(!empty($proxy))?$proxy.",".$IP:$IP;
    if ($_SESSION["USER_CURRENT_IP"]) {
        if ($_SESSION["USER_CURRENT_IP"] != $RemoteInfo) {
            //header("Location: $CONST_LINK_ROOT/login.php");
            //exit;
        }
    } else {
        $_SESSION["USER_CURRENT_IP"] = $RemoteInfo;
    }

################################################################
##  Set online time                                           ##
    $_time->dumpTime('check IP');

    if (isset ($Sess_UserId)) {
        $db->query("UPDATE members SET mem_timeout=NOW() WHERE mem_userid='$Sess_UserId'");
    }

################################################################
##  Set user language                                         ##

    if ( (isset($_GET['lang_id'])) && ($_GET['lang_id']) ) {
        $_SESSION['lang_id'] =sanitizeData($_GET['lang_id'], 'xss_clean');   
    } elseif($_COOKIE['lang_id']) {
        $_SESSION['lang_id'] = $_COOKIE['lang_id'];
    } elseif(isset ($Sess_UserId)) {
        $query="SELECT * FROM members WHERE mem_userid='$Sess_UserId'";
        $row = $db->get_row($query);
        $_SESSION['lang_id'] = $row->lang_id;
    }

    $_time->dumpTime('get language');

    $language->Init($_SESSION['lang_id']);
    $_time->dumpTime('init language');

    $_SESSION['lang_id'] = $language->LangID;

    setcookie("lang_id", $_SESSION['lang_id'],time()+3600*24*30*12*2,'/');

    if ( (isset($_GET['lang_id'])) && ($_GET['lang_id'] && isset ($Sess_UserId)) ) {
        $query="UPDATE members SET lang_id='$_SESSION[lang_id]' WHERE mem_userid='$Sess_UserId'";
        $db->query($query);
    }

    $CONST_IMAGE_LANG = $language->LangID;
    $CONST_LANG_CHARSET = $language->Charset;
    $CONST_FILE_LANG = $language->FileName;

##################################################################
##  Loading text constants                                      ##
    $_time->dumpTime('start options list');

    include $CONST_INCLUDE_ROOT."/languages/{$language->FileName}_admin.php";

    $option_manager->Clear('default_language');
    $option_manager->GetList();
    $_time->dumpTime('end get options list');

//dump($option_manager)    ;
    $CONST_FREE               = $option_manager->GetValue('free');
    $SECURITY_REGISTRATION    = $option_manager->GetValue('security_registration');
    $GEOGRAPHY_JAVASCRIPT     = $option_manager->GetValue('geography_javascript');
    $GEOGRAPHY_AJAX           = $option_manager->GetValue('geography_ajax');
    $CONST_ZIPCODES           = $option_manager->GetValue('zipcodes');
    $CONST_THUMBS             = $option_manager->GetValue('thumbs');
    $CONST_VIDEOS             = $option_manager->GetValue('videos');
    $CONST_AUDIOS             = $option_manager->GetValue('audios');
    $CONST_FLIRT              = $option_manager->GetValue('flirt');
    $CONST_RATING             = $option_manager->GetValue('rating');

    $CONST_SPAM_ON            = $option_manager->GetValue('spam');
    $CONST_EMAIL_CONFIRM      = $option_manager->GetValue('email_confirm');
    $CONST_AVATARS_GALLERY    = $option_manager->GetValue('avatars_gallery');

    $CONST_COMPANY            = $option_manager->GetValue('company');
    $CONST_AFFMAIL            = $option_manager->GetValue('affmail');
    $CONST_ADDR1              = $option_manager->GetValue('addr1');
    $CONST_ADDR2              = $option_manager->GetValue('addr2');
    $CONST_ADDR3              = $option_manager->GetValue('addr3');
    $CONST_ADDR4              = $option_manager->GetValue('addr4');
    $CONST_MAIL               = $option_manager->GetValue('mail');
    $CONST_SUPPMAIL           = $option_manager->GetValue('suppmail');
    $CONST_URL                = $option_manager->GetValue('url');

    $CONST_DEFAULT_LANGUAGE   = $option_manager->GetValue('default_language');
    $CONST_CURRENCY           = $option_manager->GetValue('currency');
    $CONST_SYMBOL             = $option_manager->GetValue('currency_symbol');
    $CONST_SPAM_TOLERANCE     = $option_manager->GetValue('spam_tolerange');
    $CONST_FLIRTMAIL          = $option_manager->GetValue('flirtmail');
    $CONST_IMAGE_COUNT        = $option_manager->GetValue('image_count');
    $CONST_FORMAT_DATE_SHORT  = $option_manager->GetValue('format_date_short');
    $CONST_FORMAT_TIME_SHORT  = $option_manager->GetValue('format_time_short');

    $USERPLANE_CHAT           = $option_manager->GetValue('userplane_chat');
    $USERPLANE_IM             = $option_manager->GetValue('userplane_im');
    $USERPLANE_IM_FREE        = $option_manager->GetValue('userplane_im_free');
    $USERPLANE_CHAT_FREE      = $option_manager->GetValue('userplane_chat_free');
    if ($USERPLANE_IM || $USERPLANE_CHAT) {
        $CONST_USERPLANE_DOMAIN_FULL = $option_manager->GetValue('userplane_domain_full');
        $CONST_USERPLANE_DOMAIN     = $option_manager->GetValue('userplane_domain');
    }  elseif ($USERPLANE_IM_FREE || $USERPLANE_CHAT_FREE) {
        $CONST_USERPLANE_DOMAIN_FULL = $option_manager->GetValue('userplane_domain_full_free');
        $CONST_USERPLANE_DOMAIN     = $option_manager->GetValue('userplane_domain_free');
    }
    //$CONST_USERPLANE_FREECHAT_ID = $option_manager->GetValue('userplane_freechat_domainid');
    $CONST_USERPLANE_TEXT_ZONE_ID = $option_manager->GetValue('userplane_text_zone_id');
    $CONST_USERPLANE_LEADER_BOARD_ID = $option_manager->GetValue('userplane_leader_board_id');
    $CONST_USERPLANE_FULL_BANNER_ID = $option_manager->GetValue('userplane_full_banner_id');

//    $CONST_USERPLANE_FLASH_SERVER = $option_manager->GetValue('userplane_flash');
//    $CONST_USERPLANE_IMAGE_SERVER = $option_manager->GetValue('userplane_image');

    $GROUPS_AUTOAPPROVE       = $option_manager->GetValue('groups_autoapprove');

    $CONST_TABLE_WIDTH        = $option_manager->GetValue('table_width');
    $CONST_TABLE_CELLPADDING  = $option_manager->GetValue('table_cellpadding');
    $CONST_TABLE_CELLSPACING  = $option_manager->GetValue('table_cellspacing');
    $CONST_MEMIMAGE_HEIGHT    = $option_manager->GetValue('memimage_height');
    $CONST_MEMIMAGE_WIDTH     = $option_manager->GetValue('memimage_width');
    $CONST_SUBTABLE_CELLSPACING = $option_manager->GetValue('subtable_cellspacing');
    $CONST_SUBTABLE_CELLPADDING = $option_manager->GetValue('subtable_cellpadding');
    $CONST_TABLE_ALIGN        = $option_manager->GetValue('table_align');
    $CONST_STORYIMAGE_HEIGHT  = $option_manager->GetValue('story_image_height');
    $CONST_STORYIMAGE_WIDTH   = $option_manager->GetValue('story_image_width');
    $CONST_STORYIMAGE_WEIGHT  = $option_manager->GetValue('story_image_weight');
    $CONST_COMMENT_WIDTH      = $option_manager->GetValue('comment_width');

    $_time->dumpTime('set options');

    include $CONST_INCLUDE_ROOT."/languages/{$language->FileName}.php";

    define("CONST_THUMBS_SMALL_W","70");
    define("CONST_THUMBS_SMALL_H","90");
    define("CONST_THUMBS_MEDIUM_W","120");
    define("CONST_THUMBS_MEDIUM_H","160");
    define("CONST_THUMBS_LARGE_W","450");
    define("CONST_THUMBS_LARGE_H","600");

    $skin_color = ($_GET['color']) ? $_GET['color'] : ($_COOKIE['color'] ? $_COOKIE['color'] : $option_manager->GetValue('skin'));
    setcookie("color", $skin_color, time()+3600*24*30*12*2,'/');

    $skin->Init($skin_color);
    define("CONST_IMAGE_ROOT",$CONST_LINK_ROOT.$skin->ImagePath);
    $CONST_IMAGE_ROOT = CONST_IMAGE_ROOT;
    
	$CONST_SKIN_ROOT = rtrim($CONST_INCLUDE_ROOT,"/").$skin->Path;
    define("CONST_SKIN_ROOT",$CONST_SKIN_ROOT);

    $_time->dumpTime('init skin');

    define("CONST_EMOTIONS_PATH",CONST_IMAGE_ROOT."smilies/");

    $CONST_LINK_LANG_SWITCHER = include $CONST_INCLUDE_ROOT."/langswitcher.inc.php";
    $_time->dumpTime('include lanswitcher');
    include $CONST_INCLUDE_ROOT.'/nav_history.php';
    $_time->dumpTime('include history');


    $page = FormGet('page');
    $pagesize = FormGet('SHOWNUM');
    $pager = new Pager($page,$pagesize);

    header("Content-Type: text/html; charset=$CONST_LANG_CHARSET");
################################################################
##  Handling extensions                                       ##

    $extensionsList = array(
        "generatedEvents"=>"/speeddating/ext_events.php",
        "generatedStories"=>"/speeddating/ext_stories.php",
        "generatedLogin"=>"/speeddating/ext_login.php",
        "generatedUserPLane"=>"/ext_userplane.php",
        //"generatedChat"=>"/ext_chat.php",
    );
    $_time->dumpTime('init extention list');

    foreach ($extensionsList as $extension=>$generator) {
        // sns commented if (file_exists("$CONST_INCLUDE_ROOT/$generator"))
          //  $ext_arr[$extension] = ${$extension} = include($CONST_INCLUDE_ROOT."/$generator");
    }
    $_time->dumpTime('Load extentions');

  //dump($_time);

  foreach ( $_SESSION as $__getVarKey=>$__getVarName ) {
    $$__getVarKey = $__getVarName;
  }
  foreach ( $_GET as $__getVarKey=>$__getVarName ) {
    $$__getVarKey = $__getVarName;
  }
  foreach ( $_POST as $__postVarKey=>$__postVarName ) {
    $$__postVarKey = $__postVarName;
  }
    /**
     * define constants for FCKeditor
     */
     // main url path for fck
        define ("__URL_FOR_FCK",$CONST_LINK_ROOT."/FCKeditor/");
    // main include path
        define ("__BASE_PATH",$CONST_INCLUDE_ROOT);
    // start init define constants for news
        define("__NEWS_CustomFullPathForUpload",__BASE_PATH."news/");
        define("__NEWS_CustomRelPathForUpload", "news");
        define("__NEWS_CustomFromRootPathForUpload", $__SITE_REL.__NEWS_CustomRelPathForUpload.'/');
    //
    // start init define constants for stories
        define("__STORIES_CustomFullPathForUpload",__BASE_PATH."stories/");
        define("__STORIES_CustomRelPathForUpload", "stories");
        define("__STORIES_CustomFromRootPathForUpload", $__SITE_REL.__STORIES_CustomRelPathForUpload.'/');
    //
    // start init define constants for pageTemplates
        define("__PTEMPL_CustomFullPathForUpload",__BASE_PATH."images/pageTemplates/");
        define("__PTEMPL_CustomRelPathForUpload", "images/pageTemplates");
        define("__PTEMPL_CustomFromRootPathForUpload", $__SITE_REL.__PTEMPL_CustomRelPathForUpload.'/');
    //


}
?>