<?php

$shop = isset($_SERVER['X-Shopify-Shop-Domain']) ? $_SERVER['X-Shopify-Shop-Domain'] : $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];

$hmac_header = isset($_SERVER['X-Shopify-Hmac-SHA256']) ? $_SERVER['X-Shopify-Hmac-SHA256'] : $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];

$webhook_payload = file_get_contents('php://input');

function verify_webhook($data, $hmac_header) {
    $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_SHARED_SECRET, true));
    return hash_equals($hmac_header, $calculated_hmac);
}

$verified = verify_webhook($webhook_payload, $hmac_header);
if ($verified) {
    $webhook_payload = json_decode($webhook_payload, true);
    $shop_id = $webhook_payload['shop_id'];
    $shop_domain = $webhook_payload['shop_domain'];
    $customer_id = $webhook_payload['customer']['id'];
    http_response_code(200);
} else {
    http_response_code(401);
}
?>