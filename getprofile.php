<?php
date_default_timezone_set("Asia/Karachi");
include_once "includes/conn.php"; 
if(isset($_POST['settle'])){
	$profile_id = $_POST['profile_id'];
	$get = mysqli_query($con,"SELECT * FROM profiling WHERE id=".$profile_id." ");
	$rec = mysqli_fetch_array($get);
	echo json_encode($rec); exit();
}
?>