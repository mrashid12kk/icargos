<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(0);
session_start();
// if(!isset($_GET['shop'])){
// 	echo json_encode("Unable to access this page!");
// }
require_once("inc/conn.php");
require_once("inc/constants.php");
require_once("inc/functions.php");
$shopify = $_GET;
$shop = $shopify['shop'];
// $url = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
// $url_explode = explode('.', $url);
// $shop_name = $url_explode[1];
// $shop = $shop_name . '.myshopify.com';
// // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
// // $shop = 'placourier.myshopify.com';
$check_install = mysqli_query($con, "SELECT * FROM preferences WHERE `shop_url`='" . $shop . "' ");

if (mysqli_num_rows($check_install) < 1) {
        if(!isset($shop) && $shop == ''){
            include_once("url.php");
        }else{
            header("Location:install.php?shop=" . $shop);
            exit();
        }
} else {
	$result = mysqli_fetch_array($check_install);
	$token = $result['access_token'];
	$_SESSION['access_token'] = $result['access_token'];
	$_SESSION['shop_url'] = $result['shop_url'];
        $shop_api = str_replace('.myshopify.com', '', $shop);
        $shop_details = shopify_call($token, $shop_api, "/admin/api/2021-07/shop.json", array(), 'GET');
        $response = json_decode($shop_details['response'], TRUE);
        if(isset($response['errors'])){
            header("Location:install.php?shop=" . $shop);
            exit();
        }else{
            $url = parse_url('https://' . $result['shop_url']);
            $host = explode('.', $url['host']);
            $shop  = $host[0];
            $webhook = shopify_call($token, $shop, "/admin/api/2020-07/webhooks.json", array(), 'GET');
            $webhook = json_decode($webhook['response'], JSON_PRETTY_PRINT);
            header("Location:dashboard.php");
            //echo "<script>top.location.href='http://shopify.tezzpk.com/</script>";
        }

}