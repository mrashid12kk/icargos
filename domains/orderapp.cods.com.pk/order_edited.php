<?php

require_once("inc/conn.php");
require_once("inc/constants.php");
require_once("inc/functions.php");

function verify_webhook($data, $hmac_header)
{
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_SHARED_SECRET, true));
  return hash_equals($hmac_header, $calculated_hmac);
}

$shop = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$data = file_get_contents('php://input');
$verified = verify_webhook($data, $hmac_header);

if ($verified) {
  http_response_code(200);
} else {
  http_response_code(401);
}

?>