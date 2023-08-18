<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Karachi");

require 'includes/conn.php';
if(isset($_GET['service_id']) && !empty($_GET['service_id'])){
$service_id = $_GET['service_id'];
mysqli_query($con,"DELETE FROM services WHERE id='".$service_id."' ");
}else{

}
header("Location:".$_SERVER['HTTP_REFERER']);
	?>
