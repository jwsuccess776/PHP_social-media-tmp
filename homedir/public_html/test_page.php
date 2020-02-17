<?php
include('db_connect.php');
include_once __INCLUDE_CLASS_PATH."/class.ConstantChecker.php";

$checker=new ConstantChecker("/home/dateso2/public_html/demos/vplus/");
$checker->FindConstants("/home/dateso2/public_html/demos/vplus/");
$checker->DisplayResults("FULL");

?>
