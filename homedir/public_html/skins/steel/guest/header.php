<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>
<?=$CONST_COMPANY?>
</title>
<meta name="Keywords" content="dating, online dating, internet dating, romance, relationships, marriage, free personals, online chat, chat, chat room, single men, dating agency" />
<meta name="Description" content="Looking for new ways to find a date?  Try a Keyword Search to find people with similar interests or a Custom Search to describe exactly what you want in a dream date." />
<meta name="ROBOTS" content="INDEX, FOLLOW" />
<meta name="language" content="en" />
<? include CONST_INCLUDE_ROOT."/lightbox/scripts.php"?>
<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>template.css' />
<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>core.css' />
<script language="JavaScript" src="<?=$CONST_LINK_ROOT?>/jscript_lib.js.php" type="text/javascript"></script>
</head>
<body>
<? include CONST_INCLUDE_ROOT."/csslogin.php"?>
<div id="wrapper">
  <div id="wrapper_inner">
    <div id="wrapper_content">
      <!-- Start Header -->
      <div id="header">
        <div id="header_left"><?php if (isset($_SESSION['Sess_UserId'])){   ?>
          <a href="<?=$CONST_LINK_ROOT?>/home.php"><img src="<?=$CONST_IMAGE_ROOT?>logo.gif" alt="<?=$CONST_COMPANY?>" width="350" height="95" border="0" /></a>
          <?php } else { ?>
          <a href="<?=$CONST_LINK_ROOT?>/index.php"><img src="<?=$CONST_IMAGE_ROOT?>logo.gif" alt="<?=$CONST_COMPANY?>" width="350" height="95" border="0" /></a>
          <?php }   ?>
        </div>
        <div id="header_right">
          <?php if (isset($_SESSION['Sess_UserId'])){   ?>
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
          </a> | <a href="#" onClick="openbox('', 1,'box')">
          <?=$MENU_LOGIN?>
          </a> |
          <?php }   ?>
          <a href="<?=$CONST_LINK_ROOT?>/about.php">
          <?=$MENU_ABOUT?>
          </a> | <a href="<?=$CONST_LINK_ROOT?>/news_list.php">
          <?=$MENU_NEWS?>
          </a> | <a href="<?=$CONST_LINK_ROOT?>/stories_list.php">
          <?=$MENU_STORIES?>
          </a> | <a href="<?=$CONST_LINK_ROOT?>/help.php">
          <?=$MENU_HELP?>
          </a> </div>
      </div>
      <!-- End Header - Start Nav -->
      <div id="nav">
        <?php
        require_once __INCLUDE_CLASS_PATH."/class.MenuManager.php";
        $menu = new MenuManager('guest');
        $menu->outputMenu();
      ?>
      </div>
      <!-- End Nav - Start Content -->
      <div id="content">
        <div id="content_inner">