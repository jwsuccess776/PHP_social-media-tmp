<html>
<head>
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
<meta name="Author" content="Dylan Fox 2000 - 2007">
<title>
<?=$CONST_COMPANY?>
</title>
<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>template.css' />
<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>core.css' />
<LINK href="<?=$CONST_LINK_ROOT.$skin->Path?>directory/style.css" type=text/css rel=StyleSheet>
<SCRIPT language="JavaScript" src="<?=$CONST_DIRECTORY_LINK_ROOT?>/search.js"></SCRIPT>
<SCRIPT language="JavaScript" src="<?=$CONST_DIRECTORY_LINK_ROOT?>/validate_addurl.js"></SCRIPT>
</head>
<body>
<table width="770" border="0" align="center" cellpadding="0" cellspacing="0" class="headertable">
  <tr>
    <td width="350" background="<?=$CONST_IMAGE_ROOT?>back.gif" class="logo"><a href="<?php echo $CONST_LINK_ROOT?>/home.php"><img src="<?=$CONST_IMAGE_ROOT?>logo.gif" alt="" width="350" height="70" border="0"></a></td>
    <td width="420" align="right" valign="bottom" background="<?=$CONST_IMAGE_ROOT?>back.gif" class="topnav">
      <a href="<?=$CONST_DIRECTORY_LINK_ROOT?>/index.php">Home</a> | <a href="mailto:<?=$CONST_SUPPMAIL?>">Contact
      Us</a> </td>
  </tr>
</table>
<table width="770" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="nav"><a href="<?=$CONST_DIRECTORY_LINK_ROOT?>/articles.php">Articles</a>
      <a href="<?=$CONST_DIRECTORY_LINK_ROOT?>/advertise.php">Advertise</a> <a href="<?=$CONST_DIRECTORY_LINK_ROOT?>/add_site.php">Add
      URL</a> <a href="<?=$CONST_DIRECTORY_LINK_ROOT?>/rules.php">Rules</a> <a href="<?=$CONST_DIRECTORY_LINK_ROOT?>/aboutus.php">About
      Us</a> <a href="javascript:MDM_openWindow('<?=$CONST_DIRECTORY_LINK_ROOT?>/search_help.php','Search','width=375,height=375')" onMouseOver="window.status=''; return true" onMouseOut="window.status='';return true">Search
      help</a> </td>
  </tr>
</table>
<table width="770"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <?  if (!$STOP_MENU) {?>
      <td valign="top" width="20%" class="side"> <table width="100%"   border="0" cellspacing="0" cellpadding="5">

          <tr>
            <td valign="top">
              <?

            # directory menu

            $categories=mysql_query("SELECT DISTINCT(cat_parent) FROM dir_categories",$link);
            while ($directory_menu_top=mysql_fetch_object($categories)) {

                print("<div class='menuhead'>$directory_menu_top->cat_parent</div>");



                $sub_categories=mysql_query("SELECT cat_child, cat_id FROM dir_categories WHERE cat_parent = '$directory_menu_top->cat_parent'",$link);

                while ($directory_menu=mysql_fetch_object($sub_categories)) {

                    if ($directory_menu->cat_child) {

                        $link_res=mysql_query("SELECT COUNT(*) AS total FROM dir_site_list WHERE cat_id=$directory_menu->cat_id AND site_sponsor='N'",$link);

                        $site_list=mysql_fetch_object($link_res);

                        $sub_category.="<a href='$CONST_DIRECTORY_LINK_ROOT/directory.php?cat=$directory_menu->cat_id'>$directory_menu->cat_child({$site_list->total})</a><br>";

                    }

                }

                print("<div class='sideNavBody'>$sub_category</div>");

                $sub_category="";

            }

        ?>
            </td>
          </tr>
        </table></td>
      <?}?>

      <td class="main" valign="top" width="80%" > <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" class="content">
<table border="0" cellpadding="0" cellspacing="0" >
                <form method="post" action="<?=$CONST_LINK_ROOT?>/directory/search.php" name="frmSearch">
                <tbody>
                  <tr>
                    <td valign="top"><input name="txtSearch" type="text" class="input">
                      <br>
                    </td>
                    <td>
                    <input type="submit" name="Submit" value="Search" class="button">
                      </form></td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td >&nbsp;</td>
          </tr>
          <tr>
            <td class="content"><p><?php echo $banner_text; ?></p>