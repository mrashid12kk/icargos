<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$access_token = $_SESSION['access_token'];
include_once("inc/conn.php");
$get_pref = mysqli_query($con, "SELECT * FROM  preferences WHERE `access_token`='" . $access_token . "' ");
$pref_res = mysqli_fetch_array($get_pref);
$shop_url = $pref_res['shop_url'] ? $pref_res['shop_url'] : '';
$shopUrlArray = explode(".", $shop_url);
$shop_name = $shopUrlArray[0];// this veriable gets shop name from db and defines itvisionstore as shop name then url becomes  : https://itvisionstore/admin/api/2021-10/orders/4299796971714.json ====> this was working earlier
//$shop_name = $shop_url;// if we define url like this then url becomes itvisionsote.myshopify.com and then it will work fine : https://itvisionstore.myshopify.com/admin/api/2021-10/orders/4299796971714.json 
define('BASE_URL', 'https://orderapp.cods.com.pk/');
define('COURIER_URL', 'https://cods.com.pk/');
define('SHOPIFY_SHARED_SECRET', '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');
define('SHOPIFY_API_KEY', '1c502a55f51a97be459568f604af03c1');
define('API_DATE', '2021-10');
define('SHOP_NAME', $shop_name);
define('COURIER_COMPANY', 'CODS Courier');
define('ORDER_TRACK_URL', 'https://cods.com.pk/track-details.php');