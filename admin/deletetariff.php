<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Karachi");

require 'includes/conn.php';
if (isset($_GET['tariff_id']) && !empty($_GET['tariff_id'])) {
    $tariff_id = $_GET['tariff_id'];
    mysqli_query($con, "DELETE FROM tariff WHERE id='" . $tariff_id . "' ");
    mysqli_query($con, "DELETE FROM tariff_cities WHERE tariff_id='" . $tariff_id . "' ");
    mysqli_query($con, "DELETE FROM tariff_detail WHERE tariff_id='" . $tariff_id . "' ");
} else {
}
header("Location:" . $_SERVER['HTTP_REFERER']);