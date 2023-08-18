<?php

require_once('../../admin/includes/conn.php');

header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Max-Age: 3600");

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data_post = (array)json_decode(file_get_contents("php://input"));



$query = mysqli_query($con, "SELECT * from config where name ='companyname' ") or die(mysqli_error($con));

$fetch = mysqli_fetch_assoc($query);

$companyName = $fetch['value'];
$map_api = getConfig('map_api');
$api_key = getConfig('api_key');



$logoQuery = mysqli_query($con, "SELECT * from config where name ='logo' ") or die(mysqli_error($con));

$fetch = mysqli_fetch_assoc($logoQuery);

$companyLogo = 'https://cods.com.pk/admin/' . $fetch['value'];

$currency = getConfig('currency');

$weight = getConfig('weight');

$rider_cnic_tite = getConfig('rider_cnic_tite');



if (isset($companyName) && !empty($companyName)) {

    echo json_encode(array("response" => 1, "companyName" => $companyName, "companyLogo" => $companyLogo, "weight" => $weight, "currency" => $currency, "label_on_delivery_screen" => $rider_cnic_tite, "map_api" => $map_api, "map_api_key" => $api_key));
} else {

    echo json_encode(array("response" => 0, "message" => "Error Occured!"));
}



exit();