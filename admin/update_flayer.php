<?php
session_start();
require 'includes/conn.php';
if(isset($_POST['update'])){
	$flayer_name   = $_POST['flayer_name'];
	$flayer_price  = $_POST['flayer_price'];
	$flayer_id     = $_POST['flayer_id'];

	$q = "UPDATE `flayers` set `flayer_name`= '".$flayer_name."' ,`flayer_price`= ".$flayer_price." WHERE id = ".$flayer_id." ";
	 
	mysqli_query($con,$q);
	header("Location:".$_SERVER['HTTP_REFERER']);
} 
?>