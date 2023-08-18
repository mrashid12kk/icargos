<?php
session_start();
$access_token = $_SESSION['access_token'];
include_once("inc/conn.php");
require_once("inc/constants.php");
require_once("inc/functions.php");
// $token = "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";
// $shop = "it-vision-dev";
$token = $access_token;
$shop = SHOP_NAME;
$array = '';
if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
	$get_pref = mysqli_query($con, "SELECT * FROM  preferences WHERE `access_token`='" . $access_token . "' ");
	$pref_res = mysqli_fetch_array($get_pref);

	$user_auth = $pref_res['auth_key'];
	$client_code = $pref_res['client_code'];
	$order_id = $_POST['order_id'];
	 
	$shipper_info_Q = mysqli_query($con, "SELECT * FROM  shipper_info WHERE `client_code`='" . $client_code . "' ");
	$shipperInfo = mysqli_fetch_array($shipper_info_Q);

	if (!empty($order_id)) {
		$collects = shopify_call($token, $shop, "/admin/api/" . API_DATE . "/orders/" . $order_id . ".json", $array, 'GET');
		$orderInfo = json_decode($collects['response'], JSON_PRETTY_PRINT);
		$orderInfo = $orderInfo['order'];
		
		$shipping_detail = $orderInfo['shipping_address'];
		$line_items = $orderInfo['line_items'];
		// echo $shipping_detail;
		// die;
		$quantity = 0;
		$item_title = '';
		$sku = '';
		$product_id = '';
		if (!empty($line_items)) {
			foreach ($line_items as $key => $row) {
				
				$quantity += $row['quantity'];
				if ($key == 0) {
					$item_title = $row['title'];
					$sku = $row['sku'];
					$product_id = $row['product_id'];
				} else {
					$item_title .= ' ,' . $row['title'];
					$sku .= ',' . $row['sku'];
					$product_id .= ' ,' . $row['product_id'];
				}
			}
		}
		$total_weight = $orderInfo['total_weight'];
		
		$order_number_id = $orderInfo['order_number'];
		$financial_status = $orderInfo['financial_status'];
		$payment_status = $orderInfo['financial_status'];
		$order_number = 'Order No:#' . $order_number_id;
		$order_id = $orderInfo['id'];
		if ($total_weight <= 0) {
			$total_weight = 500;
		}
		if ($payment_status == 'paid') {
			$total_price = 0;
		} else {
			$total_price = $orderInfo['total_price'];
		}

		$url = COURIER_URL . 'API/CreateOrder.php';

		// $locations = shopify_call($token, $shop, "/admin/api/".API_DATE."/locations.json",'GET');
		// $locations = json_decode($locations['response']);
		// echo '<pre>',print_r($locations),'</pre>';exit();
		// $location_id = isset($locations->locations[0]->id) ? $locations->locations[0]->id:'';

		//$total_weight = 0.5;
		$shipperInfoService = isset($shipperInfo['service']) ? $shipperInfo['service'] : '';
		$post_service = isset($_POST['service_type']) ? $_POST['service_type'] : '';
		$product_description = '';
		if(isset($shipperInfo['is_item_name']) && $shipperInfo['is_item_name']==1){
			$product_description .= isset($item_title) ? rtrim($item_title,',') : '';
		}
		if(isset($shipperInfo['is_item_sku']) && $shipperInfo['is_item_sku']==1 && !empty($sku)){
			$product_description .= isset($sku) ? '['. rtrim($sku,',') .']' : '';
		}
		$weight_default = $total_weight;
		if(isset($shipperInfo['is_weight_default']) && $shipperInfo['is_weight_default']==1){
			$weight_default = 500;
		}
		if (mysqli_num_rows($get_pref) > 0) {
			$order_data = array(
				'auth_key' => $user_auth,
				'client_code' => $client_code,
				'service_type' => isset($post_service) ? $post_service : $shipperInfoService,
				'product' => isset($shipperInfo['product']) ? $shipperInfo['product'] : '',
				'profile_id' => isset($shipperInfo['profile_id']) ? $shipperInfo['profile_id'] : '',
				'origin' => isset($shipperInfo['origin']) ? $shipperInfo['origin'] : '',
				'tracking_no' => $order_id,
				'receiver_phone' => isset($shipping_detail['phone']) ? $shipping_detail['phone'] : '',
				'destination' => isset($shipping_detail['city']) ? $shipping_detail['city'] : '',
				'receiver_name' => $shipping_detail['first_name'] . ' ' . $shipping_detail['last_name'],
				'receiver_phone' => isset($shipping_detail['phone']) ?  $shipping_detail['phone'] : '',
				'receiver_email' => '',
				'receiver_address' => isset($shipping_detail['address1']) ? $shipping_detail['address1'] : '',
				'pieces' => isset($quantity) ? $quantity : '0',
				'weight' => $weight_default/1000,
				'order_date' => date('Y-m-d H:i:s'),
				'collection_amount' => isset($total_price) ? $total_price : '0',
				'product_description' => $product_description,
				'special_instruction' => isset($shipperInfo['special_instructions']) ? $shipperInfo['special_instructions'] : '',
				'order_id' => $order_number_id
			);
			// echo $url;
			// die;
			// echo "<pre>";
			// print_r($order_data);
			// die;
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			//    $ch = curl_init($url);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));
			// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			// $result = curl_exec($ch);
			// curl_close($ch);
			$response = json_decode($result);
			
			if (isset($response->tracking_no)) {
				$last_order_id = '';
				mysqli_query($con, "INSERT INTO `order_logs`(order_id) VALUES('" . $order_id . "') ");
				// $query = "INSERT INTO `shopify_orders`(`order_id`,`name`,`date`,`customer`,`title`,`total`,`shopify_status`,`last_order_id`,`is_proceed`) VALUES('" . $order_id . "','" . $shipping_detail['first_name'] . "','" . date('Y-m-d H:i:s') . "','" . $shipping_detail['first_name'] . "','" . $title . "','" . $total_price . "','" . $financial_status . "','" . $last_order_id . "',1) ";
				// mysqli_query($con, $query);
				mysqli_query($con, "UPDATE `shopify_orders` SET is_proceed = 1 WHERE order_id='" . $order_id . "' ");
				$locations = shopify_call($token, $shop, "/admin/api/" . API_DATE . "/locations.json", 'GET');
				$locations = json_decode($locations['response']);
				$location_id = isset($locations->locations[0]->id) ? $locations->locations[0]->id : '';

				$tracking_urls = ["" . ORDER_TRACK_URL . "?track_code=" . $response->tracking_no . ""];
				$json_data['fulfillment'] = array('location_id' => $location_id, 'tracking_company' => COURIER_COMPANY, 'tracking_number' => $response->tracking_no, 'tracking_urls' => $tracking_urls, 'notify_customer' => true);
				$getting_return = shopify_call_update_status($token, $shop, "/admin/api/" . API_DATE . "/orders/" . $order_id . "/fulfillments.json", json_encode($json_data), 'POST');

				$index = array_search($order_id, array_column($_SESSION['return_data'], 'order_id'));
				if (isset($_SESSION['return_data'][$index])) {
					unset($_SESSION['return_data'][$index]);
				}

				$response_arr = array();
				$response_arr['status'] = 1;
				$response_arr['msg'] = '<div class="alert alert-success">
					  <strong>Success!</strong> ' . $order_number . ' with tracking ' . $response->message . '
					</div>';
				echo json_encode($response_arr);
				exit();
			} else {
				$response_arr = array();
				$response_arr['status'] = 0;
				$response_arr['msg'] = '<div class="alert alert-danger">
				  <strong>Failed!</strong> ' . $order_number . ' ' . $response . '
				</div>';
				echo json_encode($response_arr);
				exit();
			}
		}
	}
}
  // echo "<pre>"; print_r($ordersList); exit();