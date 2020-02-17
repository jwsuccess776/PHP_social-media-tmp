<?
	// This page checks your database to see if your users have any IC windows that need to be launched
	// Also, it updates your database telling your site that the user is currently on the site

	// This will need to be set to the userID of the currently logged in member
    include('../db_connect.php');
    $userID = $Sess_UserId;


	$iRefreshInterval = isset($_GET['iRefreshInterval']) ? $_GET['iRefreshInterval'] : 5;
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<?php
		if( $iRefreshInterval > 0 )
		{
		?>
			<meta http-equiv="Refresh" content="<?php echo($iRefreshInterval) ?>;URL=<?php echo($_SERVER['SCRIPT_NAME']) ?>?iRefreshInterval=<?php echo($iRefreshInterval) ?>">
		<?php
		}

		// clear out any old values (that were requested over 15 minutes ago)
		$query = "DELETE FROM userplane_pending_ic WHERE date_add(insertedAt, INTERVAL 15 MINUTE) < Now() AND ( openedWindowAt IS NULL OR date_add(openedWindowAt, INTERVAL 5 MINUTE) < Now() )";
		mysql_query( $query );

		if( $userID )
		{
			// update your database so that everyone knows this user is online right now
			$query = "UPDATE members SET mem_timeout = NOW() WHERE mem_userid  = " . $userID;
			mysql_query( $query );

			// select a list of users who want to talk with the current user and we haven't opened a window for 5 mins
			// join it with your existing users table so you can get their display name
			$query = "SELECT
					upic.originatingUserID
				,	u.mem_username AS displayName
				FROM
					userplane_pending_ic upic
					LEFT OUTER JOIN members u ON u.mem_userid = upic.originatingUserID
				WHERE
					upic.destinationUserID = " . $userID . "
					AND ( upic.openedWindowAt IS NULL OR date_add(upic.openedWindowAt, INTERVAL 5 MINUTE) < Now() )";
			$rs = mysql_query( $query );

			// loop through all of them and open up a window for each
			echo( "<script language=\"javascript\"><!--\n" );
			while( $rsArray = mysql_fetch_array( $rs ) )
			{
				echo( "window.parent.up_launchIC( '" . $userID . "', '" . $rsArray["originatingUserID"] . "', '" . $rsArray["displayName"] . "' );\n" );
				// if you cannot get their names, adjust the query above and use the following line:
				// echo( "window.parent.up_launchIC( '" . $userID . "', '" . $rsArray["originatingUserID"] . "' );\n" );
			}
			echo( "//--></script>" );
		}

	?>
</head>

<body>
</body>
</html>



