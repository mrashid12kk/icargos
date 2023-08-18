<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// date_default_timezone_set("Asia/Karachi");
if ($_SERVER['SERVER_NAME']=="localhost")
{
	$con=mysqli_connect('localhost','root','','aicargos_v75');
}else{
	$con=mysqli_connect('localhost','aicargos_v75','w-sl^bWP5hiZ','aicargos_v75');	
}
//$con = mysqli_connect('localhost', 'logexica_PortalV7', 'yaqPDcmlVm,V', 'logexica_PortalV7');
$timezone = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='timezone' "));
date_default_timezone_set($timezone['value']);
include_once '../constants.php';
include_once 'custom_functions.php';
?>