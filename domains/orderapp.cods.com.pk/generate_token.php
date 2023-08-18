<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// Get our helper functions
require_once("inc/conn.php");
require_once("inc/constants.php");
require_once("inc/functions.php");
// if (!isset($_SESSION['access_token'])) {
// 	header("Location: /");
// }

$api_key = SHOPIFY_API_KEY;
$shared_secret = SHOPIFY_SHARED_SECRET;

$params = $_GET; // Retrieve all request parameters
// echo '<pre>',print_r($params),'</pre>';die();
$hmac = $_GET['hmac']; // Retrieve HMAC request parameter

$params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
ksort($params); // Sort params lexographically
$shop = $params['shop'];

$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);

// Use hmac data to check that the response is from Shopify or not
if (hash_equals($hmac, $computed_hmac)) {

	// Set variables for our request
	$query = array(
		"client_id" => $api_key, // Your API key
		"client_secret" => $shared_secret, // Your app credentials (secret key)
		"code" => $params['code'] // Grab the access key from the URL
	);

	// Generate access token URL
	$access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";
	// Configure curl client and execute request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $access_token_url);
	curl_setopt($ch, CURLOPT_POST, count($query));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
	$result = curl_exec($ch);
	curl_close($ch);

	// Store the access token
	$result = json_decode($result, true);
	$access_token = $result['access_token'];
	$k = mysqli_query($con, " SELECT * FROM preferences WHERE shop_url='" . $params['shop'] . "' ");
	if (mysqli_num_rows($k) > 0) {
		$q = mysqli_query($con, " UPDATE preferences SET  access_token='" . $access_token . "' WHERE shop_url='" . $params['shop'] . "' ");
	} else {
		$q = mysqli_query($con, "INSERT INTO preferences(`shop_url`,`access_token`) VALUES('" . $params['shop'] . "','" . $access_token . "') ");
	}
	if ($q) {
		$shop_url = $params['shop'];
		$shop_domain = $params['shop'];
		registerShopifyAppUninstallWebhook($shop_domain, $access_token);
                
                $url_param_arr = array('webhook' => array(
                        'topic' => 'orders/updated',
                        'address' => BASE_URL . 'order_update.php',
                        'format' => 'json'
                ));
                $shop_api = str_replace('.myshopify.com', '', $shop);
                shopify_call($access_token, $shop_api, "/admin/api/2021-10/webhooks.json", $url_param_arr, 'POST');
                
                $url_param_arr = array('webhook' => array(
                        'topic' => 'orders/fulfilled',
                        'address' => BASE_URL . 'order_fulfilled.php',
                        'format' => 'json'
                ));
                $shop_api = str_replace('.myshopify.com', '', $shop);
                shopify_call($access_token, $shop_api, "/admin/api/2021-10/webhooks.json", $url_param_arr, 'POST');
                
                $url_param_arr = array('webhook' => array(
                        'topic' => 'orders/edited',
                        'address' => BASE_URL . 'order_edited.php',
                        'format' => 'json'
                ));
                $shop_api = str_replace('.myshopify.com', '', $shop);
                shopify_call($access_token, $shop_api, "/admin/api/2021-10/webhooks.json", $url_param_arr, 'POST');

		//header("Location:" . "https://" . $params['shop'] . "/admin/apps");
		header("Location:index.php?shop=" . $shop);
	} else {
		die('Error !');
	}
	// Show the access token (don't do this in production!)
	// echo $access_token;

} else {
	// Someone is trying to be shady!
	die('This request is NOT from Shopify!');
}

function registerShopifyAppUninstallWebhook($shop_domain, $access_token)
{

	$API_KEY = SHOPIFY_API_KEY;
	$SECRET = SHOPIFY_SHARED_SECRET;
	$TOKEN = $access_token;
	$STORE_URL = $shop_domain;

	$url = 'https://' . $STORE_URL . '/admin/webhooks.json';

	$params = '{"webhook": {
"topic": "app/uninstalled",
"address": "' . BASE_URL . 'delete.php?shop=' . $STORE_URL . '",
"format": "json"
}}';

	$array = array(
		'webhook' => array(
			'topic' => 'app/uninstalled',
			'address' => BASE_URL . 'delete.php?shop=' . $STORE_URL,
			'format' => 'json'
		)
	);
	$session = curl_init();

	curl_setopt($session, CURLOPT_URL, $url);
	curl_setopt($session, CURLOPT_POST, 1);
	// Tell curl that this is the body of the POST
	curl_setopt($session, CURLOPT_POSTFIELDS, stripslashes(json_encode($array)));
	curl_setopt($session, CURLOPT_HEADER, false);
	curl_setopt($session, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', 'X-Shopify-Access-Token: ' . $TOKEN));
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	if (preg_match("/^(https)/", $url)) {
		curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
	}


	$response = curl_exec($session);
	curl_close($session);

	$body = json_decode($response);
	// echo "<pre>"; print_r($body); exit();
	if ($body) {
		return $body;
	} else {
		return "0";
	}
}