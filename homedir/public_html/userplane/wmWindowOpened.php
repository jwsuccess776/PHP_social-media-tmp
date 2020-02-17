<?
	// This page is used to tell you database that a IC window was launched up or that
	// they denied a IC window request.

	//This will need to be set to the userID of the currently logged in member
    include('../db_connect.php');
    $userID = $Sess_UserId;


	$bDoPresence = isset($_GET['doPresence']) ? $_GET['doPresence'] : "false";
	$bDoPresence = $bDoPresence == "true" || $bDoPresence == "1";

	$bForceClear = isset($_GET['forceClear']) ? $_GET['forceClear'] : "false";
	$bForceClear = $bForceClear == "true" || $bForceClear == "1";

	$iRefreshInterval = isset($_GET['iRefreshInterval']) ? $_GET['iRefreshInterval'] : 5;
	$destinationUserID = isset($_GET['destinationUserID']) ? $_GET['destinationUserID'] : "";

	if( $userID && $destinationUserID != "" )
	{
		if( $bForceClear )
		{
			// the user said 'no' so delete the request from the db
			$query = "DELETE FROM userplane_pending_ic WHERE destinationUserID = " . $userID . " AND originatingUserID = " . $destinationUserID;
		}
		else
		{
			// we opened up a IC window so update the db so we don't open again for a few minutes
			$query = "UPDATE userplane_pending_ic SET openedWindowAt = Now() WHERE destinationUserID = " . $userID . " AND originatingUserID = " . $destinationUserID;
		}
		mysql_query( $query );
	}

	if( $bDoPresence )
	{
        header( "Location:wmLauncher.php?iRefreshInterval=" . $iRefreshInterval );
	}
?>
