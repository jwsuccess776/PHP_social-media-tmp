<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$CONST_COMPANY?></title>
<meta name="ROBOTS" content="INDEX, FOLLOW" />
<meta name="language" content="en" />
<meta name="viewport" content="width=325; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>template.css' />
<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>mobile/mobile.css' />
<script language="JavaScript" src="<?=$CONST_LINK_ROOT?>/jscript_lib.js.php" type="text/javascript"></script>
</head>
<body style="background-color:#FFFFFF;">
<div id="nav">
	<a href="<?=$CONST_LINK_MOB_ROOT?>/mobhome.php"><img src="images/home.png" height="30" width="30" hspace="1" vspace="10" border="0"/ ></a>
	<a href="<?=$CONST_LINK_MOB_ROOT?>/mobmail.php"><img src="images/message.png" height="30" width="30" hspace="1" vspace="10" border="0"/></a>
	<a href="<?=$CONST_LINK_MOB_ROOT?>/mobsearch.php"><img src="images/search.png" height="30" width="30" hspace="1" vspace="10" border="0"/></a>
	<a href="<?=$CONST_LINK_MOB_ROOT?>/mobhotlist.php"><img src="images/profile.png" height="30" width="30" hspace="1" vspace="10" border="0"/></a>
<? if (!isset($_SESSION['Sess_UserId'])) { ?>
    	<a href="<?=$CONST_LINK_MOB_ROOT?>"><img src="images/logon.png" height="30" width="30" hspace="1" vspace="10" border="0"/></a>
<? } else { ?>
    	<a href="<?=$CONST_LINK_MOB_ROOT?>/moblogoff.php"><img src="images/logoff.png" height="30" width="30" hspace="1" vspace="10" border="0"/></a>
<? } ?>
</div>
<div style="text-align:left; margin: 0 5px; width: 320px;">
