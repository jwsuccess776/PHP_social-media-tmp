<?

include("../db_connect.php");

if (!$Sess_UserId) {

    exit;

}

include_once(__INCLUDE_CLASS_PATH."/class.Notifications.php");

$n = new Notifications($Sess_UserId);

echo $n;

//echo "Hello";

// mysql_close($link);

?>

