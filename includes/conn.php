<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include './constants.php';
// include 'tarif_constant.php';
if ($_SERVER['SERVER_NAME']=="localhost")
{
	$con = mysqli_connect('localhost', 'root', '', 'aicargos_v75');	
}else{
	$con = mysqli_connect('localhost', 'aicargos_v75', 'w-sl^bWP5hiZ', 'aicargos_v75');
}
$timezone = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='timezone' "));
define('DATE_FORMAT', 'd/m/Y');
date_default_timezone_set($timezone['value']);
include 'custom_functions.php';