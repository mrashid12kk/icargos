<?php 

$url = 'https://fxcs.itvision.pk/portal1/API/CreateOrder.php';
$order_data = array(
	'service_type'       => 1,
	'order_date'      	 => Date('Y-m-d'),
	'Pick_location'      => 'Test Location',
	'Pick_time'          => '',
	'origin'             => 'Lahore',
	'destination'        => 'Lahore',
	'receiver_name'   	 => '123456', 
	'receiver_phone'     => '33', 
	'receiver_email'     => 'xyz@gmail.com',  //////
	'receiver_address'   => '123',
	'Receiver_lat'       => '1',
	'Receiver_lng'       => '1',
	'pieces'             => '1',
	'weight'             => '1', //////
	'collection_amount'  => 2,
	'product_description'  => 'instruction', 
	'Cust_ref'           => '1',
	'is_fragile'         => '1', 
	'special_instruction' => '1', 
	'auth_key'           => 'a92e389e-360d-4d6f-84c9-8210474fc17b',
);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
// curl_setopt($ch, CURLOPT_USERPWD,  'username:password');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: a92e389e-360d-4d6f-84c9-8210474fc17b'));
$result = curl_exec($ch);
curl_close($ch);

echo "<pre>";
print_r($result);
die();

?>