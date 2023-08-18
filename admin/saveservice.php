<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require 'includes/conn.php';
if (isset($_POST['addservice'])) {
	$msg = '';
	$service_type = $_POST['service_type'];
	$service_code = $_POST['service_code'];
	$product_id = $_POST['product_id'];
	$is_pnd = isset($_POST['is_pnd']) ? $_POST['is_pnd'] : 0;
	$additional_charges = $_POST['additional_charges'];
	$icon = '';
	if (isset($_FILES["icon"]["name"]) && !empty($_FILES["icon"]["name"])) {
            $target_dir = "assets/services/";
            $target_file = $target_dir . uniqid() . basename($_FILES["icon"]["name"]);
            // $db_dir = "users/";
            // $db_file = $db_dir .uniqid(). basename($_FILES["icon"]["name"]);
            $extension = pathinfo($target_file, PATHINFO_EXTENSION);
            if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'jpeg' || $extension == 'JPEG') {
                if (move_uploaded_file($_FILES["icon"]["tmp_name"], $target_file)) {
                    // echo $target_file;

                    $icon = $target_file;
                }
            } else {
				$msg='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button>our Logo Image Type In Wrong</div>';
				$_SESSION['message_service'] = $msg;
				header("Location:servicelist.php");
                exit();
            }
        }
	mysqli_query($con, " INSERT INTO services(`service_type`,`service_code`,`product_id`,`additional_charges`,`icon`,`is_pnd`) VALUES('" . $service_type . "','" . $service_code . "','" . $product_id . "','" . $additional_charges . "','" . $icon . "','" . $is_pnd . "') ");
	$rowscount = mysqli_affected_rows($con);
	if ($rowscount > 0) {
		$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new Service successfully</div>';
	} else {
		$msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new Service unsuccessfully.</div>';
	}
	$_SESSION['message_service'] = $msg;
	header("Location:servicelist.php");
}