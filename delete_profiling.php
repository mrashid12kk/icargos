<?php
session_start();
date_default_timezone_set("Asia/Karachi");
	include_once "includes/conn.php";
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		mysqli_query($con,"DELETE FROM profiling WHERE id='".$id."' ");
		header("Location:".$_SERVER['HTTP_REFERER']);
	}
 ?>