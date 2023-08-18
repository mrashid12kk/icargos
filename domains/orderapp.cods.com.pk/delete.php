<?php
require_once("inc/conn.php");
require_once("inc/functions.php");
$fp = fopen("myText.txt", "w");
fwrite($fp, $content); 
fclose($fp);
define('SHOPIFY_APP_SECRET', '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'); // Replace with your SECRET KEY

function verify_webhook($data, $hmac_header)
{
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
  return hash_equals($hmac_header, $calculated_hmac);
}

$res = '';
$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$topic_header = $_SERVER['HTTP_X_SHOPIFY_TOPIC'];
$shop_header = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
$data = file_get_contents('php://input');
$decoded_data = json_decode($data, true);

$verified = verify_webhook($data, $hmac_header);

if( $verified == true ) {
  if( $topic_header == 'app/uninstalled' || $topic_header == 'shop/update') {
    if( $topic_header == 'app/uninstalled' ) {

      $sql = "DELETE FROM preferences WHERE store_url='".$shop_header."' LIMIT 1";

      $result = mysqli_query($con, $sql);

      $response->shop_domain = $decoded_data['shop_domain'];

      $res = $decoded_data['shop_domain'] . ' is successfully deleted from the database';
    } else {
      $res = $data;
    }
  }
} else {
  $res = 'The request is not from Shopify';
}

error_log('Response: '. $res); //check error.log to see the result


?>