<?php
session_start();
require 'includes/conn.php';
if(isset($_POST['submit'])){
	$flayer_name = $_POST['flayer_name'];
	$flayer_price = $_POST['flayer_price'];
	mysqli_query($con," INSERT INTO flayers(`flayer_name`,`flayer_price`) VALUES('".$flayer_name."','".$flayer_price."') ");
	header("Location:".$_SERVER['HTTP_REFERER']);
} 
?>