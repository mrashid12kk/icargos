<?php
session_start();
$_SESSION['abc'] ='asdds';
header("Location:../dashboard.php");
exit();
 ?>