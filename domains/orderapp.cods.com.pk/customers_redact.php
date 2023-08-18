<?php
$webhook_payload = file_get_contents('php://input');
$webhook_payload = json_decode($webhook_payload,true);
$shop_id = $webhook_payload['shop_id'];
$shop_domain = $webhook_payload['shop_domain'];
$customer_id = $webhook_payload['customer']['id'];
http_response_code(200);
 ?>