<?php
date_default_timezone_set("Asia/Karachi");
include_once "../../includes/conn.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$content = (array)json_decode(file_get_contents("php://input"));

$auth_key = trim($content['auth_key']);
$auth_query = mysqli_query($con, "SELECT * FROM customers WHERE auth_key ='" . $auth_key . "' AND api_status=1 ");
$count = mysqli_num_rows($auth_query);
if ($count == 0) {
	$error_msg = "Invalid Authentication Key";
	echo json_encode($error_msg);
	exit();
} else {
	$customer_data = mysqli_fetch_array($auth_query);
}
$customer_id = $customer_data['id'];
$c_tarif_sql = "SELECT * FROM customer_tariff_detail WHERE customer_id = $customer_id";
$tarifCust_query = mysqli_query($con, $c_tarif_sql);
$customer_tariff_ids = '';
while ($custRes = mysqli_fetch_assoc($tarifCust_query)) {
	$customer_tariff_ids .= $custRes['tariff_id'] . ',';
}
$customer_tariff_ids = rtrim($customer_tariff_ids, ',');


$c_mapping_id_sql = "SELECT product_id, service_type FROM tariff WHERE id IN ($customer_tariff_ids)";
$mapping_query = mysqli_query($con, $c_mapping_id_sql);
$customer_product_ids = '';
$customer_service_ids = '';
while ($mapRes = mysqli_fetch_assoc($mapping_query)) {
	$customer_product_ids .= $mapRes['product_id'] . ',';
	$customer_service_ids .= $mapRes['service_type'] . ',';
}
$customer_product_ids = rtrim($customer_product_ids, ',');
$customer_service_ids = rtrim($customer_service_ids, ',');

$product_query = mysqli_query($con, "SELECT * FROM products WHERE id IN ($customer_product_ids) ");
$service_query = mysqli_query($con, "SELECT * FROM services WHERE id IN ($customer_service_ids) ");
$count = mysqli_num_rows($product_query);
$products_data = array();
$services_data = array();
if ($count == 0) {
	$error_msg = "No Record found for this customer";
	echo json_encode($error_msg);
	exit();
} else {
	while ($row = mysqli_fetch_assoc($product_query)) {
		$data = array(
			'id' => $row['id'],
			'product_code' => $row['product_code'],
			'product_type' => $row['product_type'],
			'name' => $row['name'],
			'price_type' => $row['price_type']
		);
		array_push($products_data, $data);
	}
	while ($fetch = mysqli_fetch_assoc($service_query)) {

		$service_Data = array(
			'id' => $fetch['id'],
			'service_type' => $fetch['service_type'],
			'service_code' => $fetch['service_code'],
			'product_id' => $fetch['product_id'],
			'icon' => $fetch['icon']
		);
		array_push($services_data, $service_Data);
	}
}
$citiesList = array();

    $query1 = mysqli_query($con,"SELECT id,city_name FROM cities WHERE 1 order by city_name asc ");
    while ($citieyList=mysqli_fetch_assoc($query1)) {
        array_push($citiesList, $citieyList);
    }
if (!empty($products_data)) {
	echo json_encode(array('products' => $products_data, 'services' => $services_data,'cities'=>$citiesList));
	exit();
}