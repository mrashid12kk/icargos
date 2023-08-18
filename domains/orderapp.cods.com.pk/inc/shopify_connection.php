<?php
session_start();
if(!isset($_GET['shop'])){
	echo json_encode("Unable to access this page!");
}
require_once("inc/conn.php");
$shopify = $_GET;
$shop = $shopify['shop'];
$check_install = mysqli_query($con,"SELECT * FROM preferences WHERE `shop_url`='".$shop."' ");
if(mysqli_num_rows($check_install) <1){
header("Location:install.php?shop=".$shop);
exit();
}
?>