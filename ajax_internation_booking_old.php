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

if (!function_exists("order_data")){
	function order_data($id)
	{
		global $con;
		$result = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM orders WHERE id =".$id));
		$output = '';
		if (isset($result) && !empty($result))
		{
			$output = $result;
		}
		return $output;
	}
}
$order_data=isset($_POST['order_id_or']) && !empty($_POST['order_id_or']) ? order_data($_POST['order_id_or']) : '';  
if (isset($_POST['add_areas_booking']) && !empty($_POST['add_areas_booking'])) {
	$country_id = getcountryid($_POST['destination_country']);
	$state_id = getstateid($_POST['destination_state']);
	$area_name=$_POST['areas'];
	$query = mysqli_query($con, "SELECT id FROM `cities` WHERE city_name = '" . $_POST['areas'] . "' AND country_id=".$country_id."  AND state_id=".$state_id);
	$areas_response = mysqli_fetch_assoc($query);
	$areas_id = $areas_response['id'];
	if (isset($areas_id) && !empty($areas_id)) {
		$insert_id=$areas_id;
	}
	else{
		$query = mysqli_query($con, "INSERT INTO `cities`(`city_name`, `state_id`, `country_id`) VALUES ('".$area_name."','".$state_id."','".$country_id."') ");
		$insert_id=mysqli_insert_id($con);
	}
	$area_q = mysqli_query($con, "SELECT * FROM cities where country_id='$country_id' AND state_id='$state_id' ");
	echo '<option value="">' . getLange("select") . '</option>';
	while ($row = mysqli_fetch_assoc($area_q)) {
		$selected=isset($insert_id) && $insert_id==$row["id"] ? 'selected' : '';
		echo '<option '.$selected.'>' . $row["city_name"] . '</option>';
	}
}
//origin

if (isset($_POST['get_origin_country']) && $_POST['get_origin_country'] == '1') {
	$active_customer_id = $_POST['active_customer_id'];
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $active_customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['city'];
	$customer_state = $customer_data['state_id'];
	$customer_country = $customer_data['country'];
	$query = mysqli_query($con, "SELECT * FROM country");
	$rowcount = mysqli_affected_rows($con);
	$origin_country = '';
	if ($rowcount > 0) {
		while ($row = mysqli_fetch_array($query)) {
			$selected=isset($customer_country) && $row['id'] == $customer_country ? 'selected' : '';
			$selected=isset($order_data) && $order_data['destination'] == $row['country_name'] ? 'selected' : $selected;
			// $origin_country .= "<option value='" . $row['id'] . "' ".$selected.">" . $row['country_name'] . "</option>";
			$origin_country .= "<option ".$selected." >" . $row['country_name'] . "</option>";
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
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $active_customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['city'];
	$customer_state = $customer_data['state_id'];
	$customer_country = $customer_data['country'];
	$query = mysqli_query($con, "SELECT * FROM state where country_id='$id'");
	$rowcount = mysqli_affected_rows($con);
	$origin_state = '';
	if ($rowcount > 0) {
		while ($row = mysqli_fetch_array($query)) {
			$selected=isset($customer_state) && $row['id'] == $customer_state ? 'selected' : '';
			$selected=isset($order_data) && $order_data['sstate'] == $row['state_name'] ? 'selected' : $selected;
			$origin_state .= "<option>" . $row['state_name'] . "</option>";
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
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $active_customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['city'];
	$customer_state = $customer_data['state_id'];
	$customer_country = $customer_data['country'];
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
			$selected=isset($customer_city) && $row['id'] == $customer_city ? 'selected' : '';
			$selected=isset($order_data) && $order_data['scity'] == $row['city_name'] ? 'selected' : $selected;
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
if (isset($_POST['get_destination__state']) && $_POST['get_destination__state'] == '1') {
	$id = getcountryid($_POST['destination_country']);
	$active_customer_id = $_POST['active_customer_id'];
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $active_customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['city'];
	$customer_state = $customer_data['state_id'];
	$customer_country = $customer_data['country'];
	$query = mysqli_query($con, "SELECT * FROM state where country_id='$id'");
	$rowcount = mysqli_affected_rows($con);
	$destination_state = '';
	if ($rowcount > 0) {
		while ($row = mysqli_fetch_array($query)) {
			$selected=isset($customer_state) && $row['id'] == $customer_state ? 'selected' : '';
			$selected=isset($order_data) && $order_data['rstate'] == $row['state_name'] ? 'selected' : $selected;
			$destination_state .= "<option>" . $row['state_name'] . "</option>";
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
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $active_customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['city'];
	$customer_state = $customer_data['state_id'];
	$customer_country = $customer_data['country'];
	$search_city = '';
	if (isset($_POST['destination_state']) && $_POST['destination_state'] != '') {
		$state_id = getstateid($_POST['destination_state']);
		$search_city = "AND state_id='$state_id'";
	}
	$query = mysqli_query($con, "SELECT * FROM cities where country_id='$country_id' $search_city");
	$rowcount = mysqli_affected_rows($con);
	$destination_city = '';
	$destination_city .= "<option value='' disabled selected>Select City</option>";
	if ($rowcount > 0) {
		while ($row = mysqli_fetch_array($query)) {
			$selected=isset($customer_city) && $row['id'] == $customer_city ? 'selected' : '';
			$selected=isset($order_data) && $order_data['rcity'] == $row['city_name'] ? 'selected' : $selected;
			$destination_city .= "<option ".$selected.">" . $row['city_name'] . "</option>";
		}
	}
	if (isset($destination_city) && $destination_city != '') {
		echo $destination_city;
		exit();
	}
}
?>