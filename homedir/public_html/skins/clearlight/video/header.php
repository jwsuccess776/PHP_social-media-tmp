<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>
<?=$CONST_COMPANY?>
</title>
<meta name="language" content="en" />
<script language="javascript"> var __FULL_PATH = "<?=$CONST_LINK_ROOT?>";</script>
<script type="text/javascript" src="<?=$CONST_LINK_ROOT?>/lightbox/js/prototype.js"></script>
<script type="text/javascript" src="<?=$CONST_LINK_ROOT?>/lightbox/js/scriptaculous.js?load=effects"></script>
<script type="text/javascript" src="<?=$CONST_LINK_ROOT?>/lightbox/js/lightbox.js"></script>
<link rel="stylesheet" href="<?=$CONST_LINK_ROOT?>/lightbox/css/lightbox.css" type="text/css" media="screen" />
<? include CONST_INCLUDE_ROOT."/notifications/onLoad.php"?>
<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>template.css' />
<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>core.css' />
<LINK href="<?=$CONST_LINK_ROOT.$skin->Path?>calendar.css" type=text/css rel=StyleSheet>
<script language="JavaScript" src="<?=$CONST_LINK_ROOT?>/jscript_lib.js.php" type="text/javascript"></script>
</head>
<body>
<body onLoad="globalInit()">
<? include CONST_INCLUDE_ROOT."/notifications/ext_notification.php"?>
<?=$generatedUserPLane?>
<div id="wrapper">
  <!-- Start Header -->
  <div id="header">
    <div id="header_left">
      <?php if (isset($_SESSION['Sess_UserId'])){	?>
      <a href="<?=$CONST_LINK_ROOT?>/home.php"><img src="<?=$CONST_IMAGE_ROOT?>logo.gif" alt="<?=$CONST_COMPANY?>" width="350" height="95" border="0" /></a>
      <?php } else { ?>
      <a href="<?=$CONST_LINK_ROOT?>/index.php"><img src="<?=$CONST_IMAGE_ROOT?>logo.gif" alt="<?=$CONST_COMPANY?>" width="350" height="95" border="0" /></a>
      <?php }	?>
    </div>
    <div id="header_right">
      <?php if (isset($_SESSION['Sess_UserId'])){	?>
      <a href="<?=$CONST_LINK_ROOT?>/home.php">
      <?=$MENU_HOME?>
      </a> | <a href="<?=$CONST_LINK_ROOT?>/get_premium.php">
      <?=$MENU_UPGRADE?>
      </a> | <a href="<?=$CONST_LINK_ROOT?>/logoff.php">
      <?=$MENU_LOGOUT?>
      </a> |
      <?php } else { ?>
      <a href="<?=$CONST_LINK_ROOT?>/index.php">
      <?=$MENU_HOME?>
      </a> | <a href="<?=$CONST_LINK_ROOT?>/register.php">
      <?=$MENU_REGISTER?>
      </a> | <a href="<?=$CONST_LINK_ROOT?>/login.php">
      <?=$MENU_LOGIN?>
      </a> |
      <?php }	?>
      <a href="<?=$CONST_LINK_ROOT?>/about.php">
      <?=$MENU_ABOUT?>
      </a> | <a href="<?=$CONST_LINK_ROOT?>/news_list.php">
      <?=$MENU_NEWS?>
      </a> | <a href="<?=$CONST_LINK_ROOT?>/stories_list.php">
      <?=$MENU_STORIES?>
      </a> | <a href="<?=$CONST_LINK_ROOT?>//help.php">
      <?=$MENU_HELP?>
      </a> </div>
  </div>
  <!-- End Header - Start body -->
  <div id="shadow_m">
    <div id="shadow_t">
      <div id="shadow_b">
        <!--  Start Nav -->
        <div id="nav">
          <?php
        require_once __INCLUDE_CLASS_PATH."/class.MenuManager.php";
        $menu = new MenuManager('member');
        $menu->outputMenu();
		
      ?>
        </div>
        <!-- End Nav - Start Content -->
        <div id="content"> 