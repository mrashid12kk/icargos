<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
date_default_timezone_set("Asia/Karachi");
require 'includes/conn.php';
if (isset($_POST['addTariff'])) {
	// echo "<pre>";
	// print_r($_POST);
	// die;
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
	$query = mysqli_query($con, "SELECT * FROM tariff WHERE `tariff_name`='" . $tariff_name . "' ");

	if (mysqli_num_rows($query) > 0) {
		$tarif_data = mysqli_fetch_array($query);
		$tariff_id = $tarif_data['id'];
		mysqli_query($con, "DELETE FROM tariff WHERE `id`='" . $tariff_id . "' ");
		mysqli_query($con, "DELETE FROM tariff_cities WHERE `tariff_id`='" . $tariff_id . "' ");
		mysqli_query($con, "DELETE FROM tariff_detail WHERE `tariff_id`='" . $tariff_id . "' ");
	}
	mysqli_query($con, "INSERT INTO tariff(`tariff_name`,`pay_mode`,`product_id`,`tariff_mapping_id`,`service_type`,`weight_lower_limit`,`weight_upper_limit`,`division_factor`,`rate`,`additional_charges`,`mappingFor`) VALUES('" . $tariff_name . "','" . $pay_mode . "','" . $product_id . "','" . $tariff_mapping_id . "','" . $service_type . "','" . $weight_lower_limit . "','" . $weight_upper_limit . "','" . $division_factor . "','" . $rate . "','" . $additional_charges . "','" . $mappingFor . "') ");
	$tariff_id = mysqli_insert_id($con);
	if ($tariff_id > 0 && $tariff_mapping_id == 4) {
		$selec_cities_query = mysqli_query($con, "SELECT city_name FROM cities");
		while ($r_single = mysqli_fetch_array($selec_cities_query)) {
			$from_city = isset($r_single['city_name']) ? $r_single['city_name'] : '';
			$to_city = isset($r_single['city_name']) ? $r_single['city_name'] : '';
			$query12 = mysqli_query($con, "SELECT * FROM city_to_city_mapping WHERE `tarif_id`='" . $tariff_id . "' AND `from_city`='" . $from_city . "' AND `to_city`='" . $to_city . "'");
			$countrow = mysqli_num_rows($query12);
			if ($countrow > 0) {
				$countrow_result = mysqli_fetch_array($query12);
				$countrow_id = isset($countrow_result['id']) ? $countrow_result['id'] : '';
				mysqli_query($con, "UPDATE city_to_city_mapping SET from_city='" . $from_city . "',to_city='" . $to_city . "' WHERE id='" . $countrow_id . "'");
			} else {
				mysqli_query($con, "INSERT INTO city_to_city_mapping(`tarif_id`,`from_city`,`to_city`) VALUES('" . $tariff_id . "','" . $from_city . "','" . $to_city . "')");
			}
		}
	}
	if (!empty($_POST['origin'])) {
		foreach ($_POST['origin'] as $key => $row) {
			$origin = $row;
			$destination = $_POST['destination'][$key];

			mysqli_query($con, " INSERT INTO tariff_cities(`tariff_id`,`origin`,`destination`,`mappingFor`) VALUES('" . $tariff_id . "','" . $origin . "','" . $destination . "','" . $mappingFor . "') ");
			$rowscount = mysqli_affected_rows($con);
			if ($rowscount > 0) {
				$msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you Added a new Tariff successfully</div>';
			} else {
				$msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Added a new Tariff unsuccessfully.</div>';
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
if (isset($_GET['zone_id'])) {
	$zone_id = $_GET['zone_id'];
	mysqli_query($con, "DELETE FROM zones WHERE id=" . $zone_id . " ");
	header("Location:addzone.php");
}
if (isset($_POST['save_zone'])) {
	$customer_id = $_POST['customer_id'];
	$pickup_zone = $_POST['pickup_zone'];
	$delivery_zone = $_POST['delivery_zone'];
	$order_id = $_POST['order_id'];
	mysqli_query($con, "UPDATE orders SET pickup_zone=" . $pickup_zone . ", delivery_zone=" . $delivery_zone . " WHERE customer_id=" . $customer_id . " AND id='" . $order_id . "' ");
	header("Location:" . $_SERVER['HTTP_REFERER']);
}
if (isset($_POST['assign_orders'])) {
	$orders = json_decode($_POST['assign_orders']);
	$rider = $_POST['rider_id'];
	foreach ($orders as $order) {
		mysqli_query($con, "UPDATE orders SET pickup_rider='" . $rider . "' WHERE id=" . $order . " ");
	}
	header("Location:" . $_SERVER['HTTP_REFERER']);
}
if (isset($_POST['assign_delivery_orders'])) {
	$date = date('Y-m-d H:i:s');
	$orders = json_decode($_POST['assign_delivery_orders']);
	$rider = $_POST['rider_id'];
	foreach ($orders as $order) {
		$query = mysqli_query($con, "SELECT * FROM orders WHERE id =" . $order . " ");
		$record = mysqli_fetch_array($query);
		mysqli_query($con, "UPDATE orders SET assign_driver='" . $rider . "', status='assigned' WHERE id=" . $order . " ");
		mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('" . $record['track_no'] . "', 'Assigned to Delivery Rider.','','" . $date . "') ");
	}
	header("Location:" . $_SERVER['HTTP_REFERER']);
}

if (isset($_POST['assign_pickup_zone'])) {
	$order_id = $_POST['order_id'];
	$rider_id = $_POST['rider_id'];
	mysqli_query($con, "UPDATE orders SET pickup_rider='" . $rider_id . "' WHERE id=" . $order_id . " ");
	header("Location:" . $_SERVER['HTTP_REFERER']);
}