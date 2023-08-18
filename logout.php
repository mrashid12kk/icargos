<?php

session_start();
include_once "includes/conn.php";
if(isset($_SESSION['customers'])) {
	mysqli_query($con, "UPDATE customers SET is_online = 0 WHERE id =".$_SESSION['customers']);
}
unset($_SESSION['customers']);
unset($_SESSION['user_customer_id']);

header("location:index.php");



?>