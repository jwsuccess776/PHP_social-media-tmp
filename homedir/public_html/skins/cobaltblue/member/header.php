<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>
<?=$CONST_COMPANY?>
</title>
<meta name="language" content="en" />
<meta name="Keywords" content="dating, online dating, internet dating, romance, relationships, marriage, free personals, online chat, chat, chat room, single men, dating agency" />
<meta name="Description" content="Looking for new ways to find a date?  Try a Keyword Search to find people with similar interests or a Custom Search to describe exactly what you want in a dream date." />
<? include CONST_INCLUDE_ROOT."/lightbox/scripts.php"?>
<? include CONST_INCLUDE_ROOT."/notifications/onLoad.php"?>
<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>template.css' />
<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>core.css' />
<script language="JavaScript" src="<?=$CONST_LINK_ROOT?>/jscript_lib.js.php" type="text/javascript"></script>
</head>
<body onLoad="globalInit()">
<div style="width: 0px; height: 0px; background-image: url('<?=$CONST_IMAGE_ROOT?>progress.gif')"></div>
<? include CONST_INCLUDE_ROOT."/notifications/ext_notification.php"?>
<?=$generatedUserPLane?>
<div id="wrapper">
  <!-- Start Header -->
   <div class="container">
    <div class="row">
      <div class="grid_12">
        <div class="header_links color3">
          <a href="logoff.php">Logout</a>
        </div>
      </div>
    </div>
  </div>
  <div id="header">
      <div id="wrapperin">
    <div id="header_left">
      <?php if (isset($_SESSION['Sess_UserId'])){	?>
      <a  href="<?=$CONST_LINK_ROOT?>/home.php"><img src="<?=$CONST_IMAGE_ROOT?>logo.png" alt="<?=$CONST_COMPANY?>" width="350" height="95" border="0" /></a>
      <?php } else { ?>
      <a href="<?=$CONST_LINK_ROOT?>/index.php"><img src="<?=$CONST_IMAGE_ROOT?>logo.png" alt="<?=$CONST_COMPANY?>" width="350" height="95" border="0" /></a>
      <?php }	?>
    </div>
    <div id="header_right">
     <a href="<?=$CONST_LINK_ROOT?>/home.php">
      <?=$MENU_HOME?>
      </a>
           <?php
        require_once __INCLUDE_CLASS_PATH."/class.MenuManager.php";
        $menu = new MenuManager('member');
        $menu->outputMenu();
		
      ?></div>
          </div>
  </div>
  <!-- End Header - Start body -->
  <div id="shadow_m">
    <div id="shadow_t">
      <div id="shadow_b">
        <!--  Start Nav -->
        <!--<div id="nav">
          <?php
        require_once __INCLUDE_CLASS_PATH."/class.MenuManager.php";
        $menu = new MenuManager('member');
        $menu->outputMenu();
		
      ?>
        </div>-->
        <!-- End Nav - Start Content -->
        <div id="content"> 
            <div id="wrapperin">