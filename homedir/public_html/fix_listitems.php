<?php
include('../db_connect.php');

$result=mysql_query("SELECT vl.lst_recid, vl.lst_type, vl.lst_base, vl.lst_adult, vl.lst_order, COALESCE(vlv.lst_value, vl.lst_value) lst_value,vl.lst_value opt_base
        FROM vlistbox vl LEFT OUTER JOIN vlistbox_values vlv ON vl.lst_recid = vlv.lst_recid AND vlv.lang_id = 'FR'
        WHERE vl.lst_type='LIW' ORDER BY lst_order ASC");
$num_rows = mysql_num_rows($result);

if ($num_rows > 0) {
	echo "There are ".$num_rows." records to be processed"."<br><br>";
	echo "These are: "."<br>";
	
	while($value=mysql_fetch_object($result)) {
		echo $value->opt_base." / ".$value->lst_value."<br>";
	}
}

$result=mysql_query("SELECT vl.lst_recid, vl.lst_type, vl.lst_base, vl.lst_adult, vl.lst_order, COALESCE(vlv.lst_value, vl.lst_value) lst_value,vl.lst_value opt_base
        FROM vlistbox vl LEFT OUTER JOIN vlistbox_values vlv ON vl.lst_recid = vlv.lst_recid AND vlv.lang_id = 'FR'
        WHERE vl.lst_type='LIW' ORDER BY lst_order ASC");
$num_rows = mysql_num_rows($result);

if ($num_rows > 0) {
	echo "Processing..."."<br><br>";

$sql_array=mysql_query("SELECT adv_userid, adv_living_with FROM adverts");

	while($value=mysql_fetch_object($result)) {
		echo $value->opt_base." / ".$value->lst_value."<br>";
	}
}

?>
