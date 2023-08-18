<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();

require 'includes/conn.php';
$msg = '';

if (isset($_POST['updateTariff'])) {
	// echo "<pre>";
	// print_r($_POST);
	// die;
	$tariff_id = $_POST['tariff_id'];
	$tariff_name = $_POST['tariff_name'];
	$pay_mode = isset($_POST['pay_mode']) ? $_POST['pay_mode'] : '';
	$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
	$tariff_mapping_id = isset($_POST['tariff_mapping_id']) ? $_POST['tariff_mapping_id'] : 0;
	$service_type = isset($_POST['service_type']) ? $_POST['service_type'] : 0;
	$weight_lower_limit = isset($_POST['weight_lower_limit']) ? $_POST['weight_lower_limit'] : 0;
	$weight_upper_limit = isset($_POST['weight_upper_limit']) ? $_POST['weight_upper_limit'] : 0;
	$division_factor = isset($_POST['division_factor']) ? $_POST['division_factor'] : 0;
	$rate = isset($_POST['rate']) ? $_POST['rate'] : 0;
	$additional_charges = isset($_POST['additional_charges']) ? $_POST['additional_charges'] : '';
	$mappingFor = isset($_POST['mappingFor']) ? $_POST['mappingFor'] : '';

	mysqli_query($con, "DELETE FROM tariff_cities WHERE `tariff_id`='" . $tariff_id . "' ");
	mysqli_query($con, "DELETE FROM tariff_detail WHERE `tariff_id`='" . $tariff_id . "' ");
	$tariffSql = 'UPDATE `tariff` SET `tariff_name`="' . $tariff_name . '",`pay_mode`="' . $pay_mode . '",`product_id`="' . $product_id . '",`tariff_mapping_id`="' . $tariff_mapping_id . '",`service_type`="' . $service_type . '",`weight_lower_limit`="' . $weight_lower_limit . '",`weight_upper_limit`="' . $weight_upper_limit . '",`division_factor`="' . $division_factor . '",`rate`="' . $rate . '",`additional_charges`="' . $additional_charges . '",`mappingFor`="' . $mappingFor . '" WHERE id=' . $tariff_id;

	mysqli_query($con, $tariffSql);


	if (!empty($_POST['origin'])) {
		foreach ($_POST['origin'] as $key => $row) {
			$origin = $row;
			$destination = $_POST['destination'][$key];

			mysqli_query($con, " INSERT INTO tariff_cities(`tariff_id`,`origin`,`destination`,`mappingFor`) VALUES('" . $tariff_id . "','" . $origin . "','" . $destination . "','" . $mappingFor . "') ");
			$rowscount = mysqli_affected_rows($con);
			if ($rowscount > 0) {
				$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a new Tariff successfully</div>';
			} else {
				$msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not updated a new Tariff unsuccessfully.</div>';
			}

			$_SESSION['zone_msg'] = $msg;
		}
	}
	if (!empty($_POST['start_range'])) {
		foreach ($_POST['start_range'] as $key => $row) {
			$start_range = $_POST['start_range'][$key];
			$end_range = $_POST['end_range'][$key];
			$rate = isset($_POST['rate'][$key]) ? $_POST['rate'][$key] : 0;

			mysqli_query($con, " INSERT INTO `tariff_detail`(`tariff_id`, `product_id`, `start_range`, `end_range`,`rate`) VALUES (" . $tariff_id . "," . $product_id . ",'" . $start_range . "','" . $end_range . "','" . $rate . "') ");
		}
	}


	header("Location:tariff-list.php");
}