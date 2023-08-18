<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$access_token = $_SESSION['access_token'];
$revoke_url   = "https://" . SHOP_NAME . ".myshopify.com/admin/api_permissions/current.json";

$headers = array(
    "Content-Type: application/json",
    "Accept: application/json",
    "Content-Length: 0",
    "X-Shopify-Access-Token: " . $access_token
);

$handler = curl_init($revoke_url);
curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handler, CURLOPT_HTTPHEADER, $headers);

curl_exec($handler);
if (!curl_errno($handler)) {
    $info = curl_getinfo($handler);
    // $info['http_code'] == 200 for success
}

curl_close($handler);