<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Karachi");

require 'includes/conn.php';
if(isset($_GET['zone_id']) && !empty($_GET['zone_id'])){
$zone_id = $_GET['zone_id'];
mysqli_query($con,"DELETE FROM zone WHERE id='".$zone_id."' ");
mysqli_query($con,"DELETE FROM zone_cities WHERE zone='".$zone_id."' ");
}else{

}
header("Location:".$_SERVER['HTTP_REFERER']);
	?>
