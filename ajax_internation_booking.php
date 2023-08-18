<?php
session_start();
include_once 'includes/conn.php';
include_once 'includes/role_helper.php';

if (!function_exists("getcountryid")){
	function getcountryid($id)
	{
		global $con;
		$result = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM country WHERE country_name ='".$id."'"));
		$output = '';
		if (isset($result['id']))
		{
			$output = $result['id'];
		}
		return $output;
	}
}

if (!function_exists("getstateid")){
	function getstateid($id)
	{
		global $con;
		$result = mysqli_fetch_assoc(mysqli_query($con, "SELECT id FROM state WHERE state_name ='".$id."'"));
		$output = '';
		if (isset($result['id']))
		{
			$output = $result['id'];
		}
		return $output;
	}
}
if (isset($_POST['add_areas_booking']) && !empty($_POST['add_areas_booking'])) {
	$country_id='';
	$country_id='';
	$data=array();
	$data['msg']='';
	if(isset($_POST['destination_country']) && !empty($_POST['destination_country']))
	{
		$country_id = getcountryid($_POST['destination_country']);
	}else{
		$data['msg']="<div class='alert alert-danger'>Please Select The Destination Country</div>";
		echo json_encode($data);die;
	}
	$state_id='';
	if (isset($_POST['destination_state']) && $_POST['destination_state'] != '') {
		$state_id = getstateid($_POST['destination_state']);
		$search_city = "AND state_id='$state_id'";
	}
	$area_name=$_POST['areas'];
	$query = mysqli_query($con, "SELECT id FROM `cities` WHERE city_name = '" . $_POST['areas'] . "' AND country_id=".$country_id." $search_city");
	$areas_response = mysqli_fetch_assoc($query);
	$areas_id = $areas_response['id'];
	if (isset($areas_id) && !empty($areas_id)) {
		$insert_id=$areas_id;
	}
	else{
		$query = mysqli_query($con, "INSERT INTO `cities`(`city_name`, `state_id`, `country_id`) VALUES ('".$area_name."','".$state_id."','".$country_id."') ");
		$insert_id=mysqli_insert_id($con);
	}
	$area_q = mysqli_query($con, "SELECT * FROM cities where country_id='$country_id' $search_city");
	$data['options']='<option value="">' . getLange("select") . '</option>';
	while ($row = mysqli_fetch_assoc($area_q)) {
		$selected=isset($insert_id) && $insert_id==$row["id"] ? 'selected' : '';
		$data['options'].='<option '.$selected.'>' . $row["city_name"] . '</option>';
	}
	echo json_encode($data);die;
}
//origin

if (isset($_POST['get_origin_country']) && $_POST['get_origin_country'] == '1') {
	$active_customer_id = $_POST['active_customer_id'];
	$order_id = $_POST['order_id_or'];
	$customer_query = mysqli_query($con, "SELECT * FROM orders WHERE id=" . $order_id . " ");
	// $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $active_customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['scity'];
	$customer_state = $customer_data['sstate'];
	$customer_country = $customer_data['origin'];
	$query = mysqli_query($con, "SELECT * FROM country");
	$rowcount = mysqli_affected_rows($con);
	$origin_country = '';
	if ($rowcount > 0) {
		while ($row = mysqli_fetch_array($query)) {
			
			$selected=isset($row['country_name']) && $row['country_name'] == $customer_country ? 'selected' : '';
			$origin_country .= "<option ".$selected.">" . $row['country_name'] . "</option>";
		}
	}
	if (isset($origin_country) && $origin_country != '') {
		echo $origin_country;
		exit();
	}
}
if (isset($_POST['get_origin_state']) && $_POST['get_origin_state'] == '1') {
	$id = getcountryid($_POST['origin_country']);
	$active_customer_id = $_POST['active_customer_id'];
	$order_id = $_POST['order_id_or'];
	$customer_query = mysqli_query($con, "SELECT * FROM orders WHERE id=" . $order_id . " ");
	// $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $active_customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['scity'];
	$customer_state = $customer_data['sstate'];
	$customer_country = $customer_data['origin'];
	$query = mysqli_query($con, "SELECT * FROM state where country_id='$id'");
	$rowcount = mysqli_affected_rows($con);
	$origin_state = '';
	if ($rowcount > 0) {
		while ($row = mysqli_fetch_array($query)) {

			$selected=isset($customer_state) && $row['state_name'] == $customer_state ? 'selected' : '';
			$origin_state .= "<option ".$selected.">" . $row['state_name'] . "</option>";
		}
	}
	if (isset($origin_state) && $origin_state != '') {
		echo $origin_state;
		exit();
	}
}
if (isset($_POST['get_origin_city']) && $_POST['get_origin_city'] == '1') {
	$country_id = getcountryid($_POST['origin_country']);
	$active_customer_id = $_POST['active_customer_id'];
	$order_id = $_POST['order_id_or'];
	$customer_query = mysqli_query($con, "SELECT * FROM orders WHERE id=" . $order_id . " ");
	// $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $active_customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['scity'];
	$customer_state = $customer_data['sstate'];
	$customer_country = $customer_data['origin'];
	$search_city = '';
	if (isset($_POST['origin_state']) && $_POST['origin_state'] != '') {
		$state_id = getstateid($_POST['origin_state']);
		$search_city = "AND state_id='$state_id'";
	}
	$query = mysqli_query($con, "SELECT * FROM cities where country_id='$country_id' $search_city");
	$rowcount = mysqli_affected_rows($con);
	$origin_city = '';
	$origin_city .= "<option value='' disabled selected>Select City</option>";
	if ($rowcount > 0) {
		while ($row = mysqli_fetch_array($query)) {
			// $selected=isset($customer_city) && $row['id'] == $customer_city ? 'selected' : '';
			$selected=isset($row['city_name']) && $row['city_name'] ==$customer_city  ? 'selected' : '';
			$origin_city .= "<option ".$selected.">" . $row['city_name'] . "</option>";
		}
	}
	if (isset($origin_city) && $origin_city != '') {
		echo $origin_city;
		exit();
	}
}
//origin

//destination

if (isset($_POST['get_destination_country']) && $_POST['get_destination_country'] == '1') {
	$active_customer_id = $_POST['active_customer_id'];
	$active_customer_id = isset($_SESSION['customers']) ? $_SESSION['customers'] : $active_customer_id;
	// $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $active_customer_id . " ");
	$order_id = $_POST['order_id_or'];
	$customer_query = mysqli_query($con, "SELECT * FROM orders WHERE id=" . $order_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['rcity'];
	$customer_state = $customer_data['rstate'];
	$customer_country = $customer_data['destination'];
	$query = mysqli_query($con, "SELECT * FROM country");
	$rowcount = mysqli_affected_rows($con);
	$origin_country = '';
	if ($rowcount > 0) {
		while ($row = mysqli_fetch_array($query)) {
			// $selected=isset($customer_country) && $row['id'] == $customer_country ? 'selected' : '';
			$selected=isset($row['country_name']) && $row['country_name'] == $customer_country ? 'selected' : '';
			$origin_country .= "<option ".$selected." >" . $row['country_name'] . "</option>";
		}
	}
	if (isset($origin_country) && $origin_country != '') {
		echo $origin_country;
		exit();
	}
}
if (isset($_POST['get_destination__state']) && $_POST['get_destination__state'] == '1') {
	$id = getcountryid($_POST['destination_country']);
	$active_customer_id = $_POST['active_customer_id'];
	$order_id = $_POST['order_id_or'];
	$customer_query = mysqli_query($con, "SELECT * FROM orders WHERE id=" . $order_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['rcity'];
	$customer_state = $customer_data['rstate'];
	$customer_country = $customer_data['destination'];
	$query = mysqli_query($con, "SELECT * FROM state where country_id='$id'");
	$rowcount = mysqli_affected_rows($con);
	$destination_state = '';
	if ($rowcount > 0) {
		while ($row = mysqli_fetch_array($query)) {
			var_dump($customer_state);
			var_dump($row['state_name']);

			$selected=isset($customer_state) && $row['state_name'] == $customer_state ? 'selected' : '';
			$destination_state .= "<option ".$selected.">" . $row['state_name'] . "</option>";
		}
	}
	if (isset($destination_state) && $destination_state != '') {
		echo $destination_state;
		exit();
	}
}
if (isset($_POST['get_destintion_city']) && $_POST['get_destintion_city'] == '1') {
	$country_id = getcountryid($_POST['destination_country']);
	$active_customer_id = $_POST['active_customer_id'];
	$order_id = $_POST['order_id_or'];
	$customer_query = mysqli_query($con, "SELECT * FROM orders WHERE id=" . $order_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['rcity'];
	$customer_state = $customer_data['rstate'];
	$customer_country = $customer_data['destination'];
	$search_city = '';
	if (isset($_POST['destination_state']) && $_POST['destination_state'] != '') {
		$state_id = getstateid($_POST['destination_state']);
		$search_city = "AND state_id='$state_id'";
	}
	$query = mysqli_query($con, "SELECT * FROM cities where country_id='$country_id' AND state_id='$state_id'");
	$rowcount = mysqli_affected_rows($con);
	$destination_city = '';
	$destination_city .= "<option value='' disabled selected>Select City</option>";
	if ($rowcount > 0) {
		while ($row = mysqli_fetch_array($query)) {
			$selected=isset($customer_city) && $row['city_name'] == $customer_city ? 'selected' : '';
			$destination_city .= "<option ".$selected.">" . $row['city_name'] . "</option>";
		}
	}
	if (isset($destination_city) && $destination_city != '') {
		echo $destination_city;
		exit();
	}
}
?>