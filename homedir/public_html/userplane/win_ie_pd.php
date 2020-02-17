<?
	// This page is only used by Windows IE so that page refreshes do not make a clicking sound.  It
	// checks your database to see if your users have any IC windows that need to be launched
	// Also, it updates your database telling your site that the user is currently on the site

	//This will need to be set to the userID of the currently logged in member
    include('../db_connect.php');
	$userID = $Sess_UserId;


	// clear out any old values (that were requested over 15 minutes ago)
	$query = "DELETE FROM userplane_pending_ic WHERE date_add(insertedAt, INTERVAL 15 MINUTE) < Now() AND date_add(openedWindowAt, INTERVAL 5 MINUTE) < Now()";
	mysql_query( $query );

	$bFoundPendingICs = false;

	if( $userID )
	{
		// update your database so that everyone knows this user is online right now
		$query = "UPDATE members SET mem_timeout = NOW() WHERE mem_userid = " . $userID;
		mysql_query( $query );

		// select a list of users who want to talk with the current user and we haven't opened a window for 5 mins
		$query = "SELECT originatingUserID FROM userplane_pending_ic WHERE destinationUserID = " . $userID . " AND ( openedWindowAt IS NULL OR date_add(openedWindowAt, INTERVAL 5 MINUTE) < Now() )";
		$bFoundPendingICs = mysql_num_rows( mysql_query( $query ) ) > 0;
	}

	if( $bFoundPendingICs )
	{
		// we need to launch IC windows so redirect to an image with a width of 2
		header( "Location:images/2PixelImage.jpg" );
	}
	else
	{
		header( "Location:images/1PixelImage.jpg" );
	}
?>

