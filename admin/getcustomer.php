<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();

require 'includes/conn.php';
if (isset($_POST['customer_id'])) {
	$customer_id = $_POST['customer_id'];
	$q = mysqli_query($con, "SELECT * FROM customers WHERE id ='" . $customer_id . "' ");
	$res = mysqli_fetch_array($q);
	echo json_encode($res);
	exit();
}
if (isset($_POST['get_customer_name_code'])) {
	$customer_code = $_POST['customer_code'];
	$q = mysqli_query($con, "SELECT fname,id,client_code,city FROM customers WHERE client_code ='" . $customer_code . "' ");
	$res = mysqli_fetch_array($q);
	echo json_encode($res);
	exit();
}
if (isset($_POST['get_customer_name'])) {
	$customer_name = $_POST['customer_name'];
	$q = mysqli_query($con, "SELECT fname,id,client_code,city FROM customers WHERE fname LIKE '%" . $customer_name . "%'  LIMIT 5");
	$customer_data = '';
	$rowscount = mysqli_affected_rows($con);
	if ($rowscount > 0) {
		$customer_data .= "<ul class='customer_data'>";
		while ($res = mysqli_fetch_array($q)) {
			$customer_data .= "<li class='select_customer' data-id='" . $res['id'] . "' data-code='" . $res['client_code'] . "' data-code='" . $res['city'] . "'>" . $res['fname'] . "</li>";
		}
		$customer_data .= "</ul>";
	}
	echo json_encode($customer_data);
	exit();
}
if (isset($_POST['getcustomer_city'])) {
	$customer_id = $_POST['customer_id_city'];
	$q = mysqli_query($con, "SELECT cities.city_name,cities.area_code,cities.id FROM customers INNER JOIN cities on customers.city=cities.id WHERE customers.id=" . $customer_id);
	$res = mysqli_fetch_assoc($q);
	$rowscount = mysqli_affected_rows($con);
	if ($rowscount > 0) {
		echo json_encode($res);
		exit();
	} else {
		$q = mysqli_query($con, "SELECT cities.city_name,cities.area_code,cities.id FROM customers INNER JOIN cities on customers.city=cities.city_name WHERE customers.id=" . $customer_id);
		$res = mysqli_fetch_assoc($q);
		echo json_encode($res);
		exit();
	}
}
if (isset($_POST['getcustomer_cn'])) {
	$customer_id = $_POST['customer_id_cn'];
	$q = mysqli_query($con, "SELECT * FROM cn_allocation WHERE customer_id='" . $customer_id . "' ORDER By id DESC");
	$res = mysqli_fetch_assoc($q);
	$res = isset($res['to']) ? $res['to'] : '';
	echo json_encode($res);
	exit();
}
// if(isset($_POST['getorigin'])){
// 		$origin = $_POST['origin'];
// 		$customer_id = $_POST['active_customer_id'];
// 		$q_c = mysqli_query($con,"SELECT * FROM customers WHERE id ='".$customer_id."' ");
// 		$customer_data = mysqli_fetch_array($q_c);
// 		$q = mysqli_query($con,"SELECT * FROM cities WHERE city_name ='".$origin."' ");
// 		$res = mysqli_fetch_array($q);

// 		$gst_percentage = 0;
// 		if(isset($customer_data['is_saletax']) && $customer_data['is_saletax'] == 1)
// 		{
// 			$gst_percentage = isset($res['gst']) ? $res['gst']:0;
// 		}
// 		echo $gst_percentage; exit();
// 	}
if (isset($_POST['getorigin'])) {
	$origin = $_POST['origin'];
	$customer_id = $_POST['active_customer_id'];
	$q = mysqli_query($con, "SELECT * FROM cities WHERE city_name ='" . $origin . "' ");
	$res = mysqli_fetch_array($q);
	$state_id = isset($res['state_id']) ? $res['state_id'] : '';
	$gst_percentage = 0;
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	if (isset($customer_data['is_saletax']) && $customer_data['is_saletax'] == 1) {
		if (isset($state_id) && !empty($state_id)) {
			$stateQ = mysqli_query($con, "SELECT tax FROM state WHERE id =" . $state_id);
			$stateResult = mysqli_fetch_array($stateQ);
			$gst_percentage = isset($stateResult['tax']) ? $stateResult['tax'] : '';
		}
		echo $gst_percentage;
		exit();
	}
}
