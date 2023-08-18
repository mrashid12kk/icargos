<?php
require 'includes/conn.php';
// mysqli_query($con,"UPDATE third_party_api_status_mapping SET api_provider_id = 'Movex' WHERE api_provider_id = '4'");
// die;
// $query=mysqli_query($con,"SELECT * FROM orders ORDER BY id DESC");
// while ($row=mysqli_fetch_array($query)) {
//   echo "<pre>";
//   print_r($row);die;
// }

// $query=mysqli_query($con,"SELECT * FROM branches");
// while ($row=mysqli_fetch_array($query)) {
//   echo "<pre>";
//   print_r($row);die;
// }


$chat = [
  "secret" => "93c3b9c86202e05f8b8f77c1315317635b1b11f6",
  "account" => 38,
  "recipient" => "+923037492694",
  // "recipient" => "+923052219792",
  "type" => "text",
  "message" => "Hello World! from watilo sms api"
];

$cURL = curl_init("https://watilio.com/api/send/whatsapp");
curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cURL, CURLOPT_POSTFIELDS, $chat);
$response = curl_exec($cURL);
curl_close($cURL);
$result = json_decode($response, true);

// do something with response
print_r($result);