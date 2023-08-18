<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once('conn.php');
include(__DIR__ . '/../../price_calculation.php');
// include '../../price_calculation.php';
if (isset($_POST['is_calculate']) && $_POST['is_calculate'] == 1) {
	$data = [];
	$net_amount = 0;
	$delivery_charges = 0;
	$fuel_surcharge = 0;
	$pft_amount = 0;
	$fuelsurcharge_percent = 0;
	$gst_percent = 0;
	$track_no = $_POST['track_no'];
	$delivery_charges = isset($_POST['delivery_charges']) ? $_POST['delivery_charges'] : 0;
	$weight = isset($_POST['weight']) ? $_POST['weight'] : 0;
	$order_detail  = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM orders WHERE track_no = '" . $track_no . "'"));
	$special_charges = isset($order_detail['special_charges']) ? $order_detail['special_charges'] : 0;
	$insured_premium = isset($order_detail['insured_premium']) ? $order_detail['insured_premium'] : 0;
	$extra_charges = isset($order_detail['extra_charges']) ? $order_detail['extra_charges'] : 0;
	$customer_id = isset($order_detail['customer_id']) ? $order_detail['customer_id'] : '';
	$order_type = isset($order_detail['order_type']) ? $order_detail['order_type'] : '';
	$origin = isset($order_detail['origin']) ? $order_detail['origin'] : '';
	$product_type_id = isset($order_detail['product_type_id']) ? $order_detail['product_type_id'] : '';
	$destination = isset($order_detail['destination']) ? $order_detail['destination'] : '';
	if ($delivery_charges == 0) {
		$delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_type_id);
		$delivery_charges = $delivery;
	}
	$total_charges = ($delivery_charges + $special_charges + $extra_charges + $insured_premium);
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
	$customer_data = mysqli_fetch_array($customer_query);
	if (isset($customer_data['is_fuelsurcharge']) && $customer_data['is_fuelsurcharge'] == 1) {
		$fuelsurcharge_percent   = getconfig('fuel_surcharge');
	}
	// if (isset($customer_data['is_saletax']) && $customer_data['is_saletax'] == 1) {
	// 	$q = mysqli_query($con, "SELECT * FROM cities WHERE city_name ='" . $origin . "' ");
	// 	$res = mysqli_fetch_array($q);
	// 	$gst_percent   = isset($res['gst']) ? $res['gst'] : 0;
	// 	// $gst_percent   = getconfig('gst');
	// }
	// $fuel_surcharge = ($total_charges / 100 * $fuelsurcharge_percent);
	// $net_amount = ($total_charges + $fuel_surcharge);
	// $pft_amount = ($net_amount / 100 * $gst_percent);
	// $net_amount = ($net_amount + $pft_amount);
	$net_amount = $delivery_charges;
	$fuel_surcharge_percent = getConfig('fuel_surcharge');
	$fuel_surcharge = ($net_amount / 100) * $fuel_surcharge_percent;
	$net_amount = $delivery_charges + $fuel_surcharge;
	$q = mysqli_query($con, "SELECT * FROM cities WHERE city_name ='" . $origin . "' ");
	$res = mysqli_fetch_array($q);
	$state_id = isset($res['state_id']) ? $res['state_id'] : '';
	$gst_percentage = 0;
	if (isset($state_id) && !empty($state_id)) {
		$stateQ = mysqli_query($con, "SELECT tax FROM state WHERE id =" . $state_id);
		$stateResult = mysqli_fetch_array($stateQ);
		$gst_percentage = isset($stateResult['tax']) ? $stateResult['tax'] : '';
	}
	$gst_percentage =  $gst_percentage;

	$pft_amount = ($net_amount / 100) * $gst_percentage;
	$net_amount = $delivery_charges + $pft_amount + $fuel_surcharge;
	$data['net_amount'] = $net_amount;
	$data['delivery_charges'] = $delivery_charges;
	$data['special_charges'] = $special_charges;
	$data['insured_premium'] = $insured_premium;
	$data['total_charges'] = $total_charges;
	$data['fuel_surcharge'] = $fuel_surcharge;
	$data['pft_amount'] = $pft_amount;
	$query = mysqli_query($con, "UPDATE orders SET weight='" . $weight . "', price='" . $delivery_charges . "',pft_amount='" . $pft_amount . "',grand_total_charges='" . $total_charges . "',fuel_surcharge='" . $fuel_surcharge . "',net_amount='" . $net_amount . "' WHERE track_no='" . $track_no . "'") or die(mysqli_error($con));
	echo json_encode($data);
	exit();
}
if (isset($_POST['is_all_cn_no']) && $_POST['is_all_cn_no'] == 1) {
	$all_cn_no = isset($_POST['all_cn_no']) ? $_POST['all_cn_no'] : '';
	$response = [];
	if (!empty($all_cn_no)) {
		foreach ($all_cn_no as $key => $row) {
			$data = [];
			$track_no = $row;
			$net_amount = 0;
			$delivery_charges = 0;
			$fuel_surcharge = 0;
			$pft_amount = 0;
			$fuelsurcharge_percent = 0;
			$gst_percent = 0;
			$weight = isset($_POST['weight']) ? $_POST['weight'] : 0;
			$order_detail  = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM orders WHERE track_no = '" . $track_no . "'"));
			$special_charges = isset($order_detail['special_charges']) ? $order_detail['special_charges'] : 0;
			$insured_premium = isset($order_detail['insured_premium']) ? $order_detail['insured_premium'] : 0;
			$extra_charges = isset($order_detail['extra_charges']) ? $order_detail['extra_charges'] : 0;
			$customer_id = isset($order_detail['customer_id']) ? $order_detail['customer_id'] : '';
			$order_type = isset($order_detail['order_type']) ? $order_detail['order_type'] : '';
			$origin = isset($order_detail['origin']) ? $order_detail['origin'] : '';
			$product_type_id = isset($order_detail['product_type_id']) ? $order_detail['product_type_id'] : '';
			$destination = isset($order_detail['destination']) ? $order_detail['destination'] : '';
			$delivery = delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_type_id);
			$delivery_charges = $delivery;
			$total_charges = ($delivery_charges + $special_charges + $extra_charges + $insured_premium);
			$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
			$customer_data = mysqli_fetch_array($customer_query);
			if (isset($customer_data['is_fuelsurcharge']) && $customer_data['is_fuelsurcharge'] == 1) {
				$fuelsurcharge_percent   = getconfig('fuel_surcharge');
			}
			// if (isset($customer_data['is_saletax']) && $customer_data['is_saletax'] == 1) {
			// 	$q = mysqli_query($con, "SELECT * FROM cities WHERE city_name ='" . $origin . "' ");
			// 	$res = mysqli_fetch_array($q);
			// 	$gst_percent   = isset($res['gst']) ? $res['gst'] : 0;
			// 	// $gst_percent   = getconfig('gst');
			// }
			// $fuel_surcharge = ($total_charges / 100 * $fuelsurcharge_percent);
			// $net_amount = ($total_charges + $fuel_surcharge);
			// $pft_amount = ($net_amount / 100 * $gst_percent);
			// $net_amount = ($net_amount + $pft_amount);

			$net_amount = $delivery_charges;
			$fuel_surcharge_percent = getConfig('fuel_surcharge');
			$fuel_surcharge = ($net_amount / 100) * $fuel_surcharge_percent;
			$net_amount = $delivery_charges + $fuel_surcharge;
			$q = mysqli_query($con, "SELECT * FROM cities WHERE city_name ='" . $origin . "' ");
			$res = mysqli_fetch_array($q);
			$state_id = isset($res['state_id']) ? $res['state_id'] : '';
			$gst_percentage = 0;
			if (isset($state_id) && !empty($state_id)) {
				$stateQ = mysqli_query($con, "SELECT tax FROM state WHERE id =" . $state_id);
				$stateResult = mysqli_fetch_array($stateQ);
				$gst_percentage = isset($stateResult['tax']) ? $stateResult['tax'] : '';
			}
			$gst_percentage =  $gst_percentage;

			$pft_amount = ($net_amount / 100) * $gst_percentage;
			$net_amount = $delivery_charges + $pft_amount + $fuel_surcharge;


			$query = mysqli_query($con, "UPDATE orders SET weight='" . $weight . "', price='" . $delivery_charges . "',pft_amount='" . $pft_amount . "',grand_total_charges='" . $total_charges . "',fuel_surcharge='" . $fuel_surcharge . "',net_amount='" . $net_amount . "' WHERE track_no='" . $track_no . "'") or die(mysqli_error($con));
			$response['response'] = 1;
		}
	}
	echo json_encode($response);
	exit();
}
if (isset($_POST['update_value']) && $_POST['update_value'] == 1) {
	$msg = '';
	$list = '';
	$response = array();
	$track_no = isset($_POST['track_no']) ? $_POST['track_no'] : '';
	$weight = isset($_POST['weight']) ? $_POST['weight'] : '';
	$delivery_charges = isset($_POST['delivery_charges']) ? $_POST['delivery_charges'] : '';
	$pft_amount = isset($_POST['pft_amount']) ? $_POST['pft_amount'] : 0;
	$net_amount = isset($_POST['net_amount']) ? $_POST['net_amount'] : 0;
	$dimensional_weight = isset($_POST['dimensional_weight']) ? $_POST['dimensional_weight'] : '';
	$total_charges = isset($_POST['total_charges']) ? $_POST['total_charges'] : '';
	$fuel_surcharge = isset($_POST['fuel_surcharge']) ? $_POST['fuel_surcharge'] : '';
	$status = $_POST['status'];
	$query = mysqli_query($con, "UPDATE orders SET weight='" . $weight . "', dimensional_weight= '" . $dimensional_weight . "', price='" . $delivery_charges . "',pft_amount='" . $pft_amount . "',grand_total_charges='" . $total_charges . "',fuel_surcharge='" . $fuel_surcharge . "',net_amount='" . $net_amount . "' WHERE track_no='" . $track_no . "'") or die(mysqli_error($con));
	if (mysqli_affected_rows($con) > 0) {
		$response['response'] = 1;
		echo json_encode($response);
		exit();
	} else {
		$response['response'] = 0;
		echo json_encode($response);
		exit();
	}
}
function backendCalculations($delivery_charges = null, $customer_id = null, $order_id = null)
{
	global $con;
	if ($delivery_charges != null && $customer_id != null) {
		$gst_percent = 0;
		$fuelsurcharge_percent = 0;
		$special_charges = 0;
		$total_charges = 0;
		$net_amount = 0;
		$order_query = mysqli_query($con, "SELECT * FROM orders WHERE id=" . $order_id . " ");
		$order_data = mysqli_fetch_array($order_query);
		$origin = isset($order_data['origin']) ? $order_data['origin'] : '';
		$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
		$customer_data = mysqli_fetch_array($customer_query);
		$admin_other_charges   = getconfig('admin_other_charges');
		if (isset($customer_data['is_fuelsurcharge']) && $customer_data['is_fuelsurcharge'] == 1) {
			$fuelsurcharge_percent   = getconfig('fuel_surcharge');
			$customer_fuel_charge = getconfig('customer_fuel_charge');
			if (isset($customer_fuel_charge) && $customer_fuel_charge == 1) {
				$customer_wise_charges_query = mysqli_query($con, "SELECT * FROM customer_wise_charges WHERE charge_name = 'fuel_surcharge' AND customer_id=" . $customer_id . " ");
				$customer_wise_charges_data = mysqli_fetch_array($customer_wise_charges_query);
				$fuelsurcharge_percent = isset($customer_wise_charges_data['charge_value']) ? $customer_wise_charges_data['charge_value'] : '';
			}
		}
		$gst_percent   = getGst($origin, $customer_id);
		if ($admin_other_charges == 1) {
			$charges_q = mysqli_query($con, "SELECT * FROM charges");
			while ($row = mysqli_fetch_array($charges_q)) {
				if (isset($row['charge_value']) && $row['charge_value']) {
					$special_charges += $row['charge_value'];
					$charges_id = isset($row['id']) ? $row['id'] : '';
					$charges_type = isset($row['charge_type']) ? $row['charge_type'] : '';
					$charges_amount = isset($row['charge_value']) ? $row['charge_value'] : '';
					$last_inserted_id = mysqli_query($con, "INSERT INTO  `order_charges`(`charges_id`,`charges_type`,`charges_amount`,`order_id`) VALUES('" . $charges_id . "','" . $charges_type . "','" . $charges_amount . "','" . $order_id . "')");
				}
			}
		}
		$total_charges = ($delivery_charges + $special_charges);
		$fuel_surcharge = ($total_charges / 100 * $fuelsurcharge_percent);
		$net_amount = ($total_charges + $fuel_surcharge);
		$pft_amount = ($net_amount / 100 * $gst_percent);
		$net_amount = ($net_amount + $pft_amount);
		mysqli_query($con, "UPDATE orders SET net_amount = '" . $net_amount . "',grand_total_charges = '" . $total_charges . "',special_charges = '" . $special_charges . "', pft_amount = '" . $pft_amount . "', fuel_surcharge = " . $fuel_surcharge . " WHERE id = $order_id ");
	}
}
function getGst($origin, $customer_id)
{
	global $con;
	$origin = $origin;
	$q = mysqli_query($con, "SELECT * FROM cities WHERE city_name ='" . $origin . "' ");
	$res = mysqli_fetch_array($q);
	$state_id = isset($res['state_id']) ? $res['state_id'] : '';
	$gst_percentage = 0;
	if (isset($state_id) && !empty($state_id)) {
		$stateQ = mysqli_query($con, "SELECT tax FROM state WHERE id =" . $state_id);
		$stateResult = mysqli_fetch_array($stateQ);
		$gst_percentage = isset($stateResult['tax']) ? $stateResult['tax'] : '';
	}
	return $gst_percentage;
}