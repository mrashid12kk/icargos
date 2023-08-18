<?php
require_once("inc/constants.php");
// Set variables for our request
$shop = $_GET['shop'];
// $api_key = "ddc1afc8d8a778277607d2897d182801";
$api_key = SHOPIFY_API_KEY;
$scopes = "read_orders,write_products,write_draft_orders,write_orders";
$redirect_uri = BASE_URL . "generate_token.php";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

// Redirect
header("Location: " . $install_url);
die();


// /Set variables for our request  
//$shop = "primary-skincare";
//$api_key = "ddc1afc8d8a778277607d2897d182801";
//$scopes = "read_orders,write_products,write_draft_orders";
//$redirect_uri = "https://itvisionstore.shopify.tezzpk.com/generate_token.php";

// Build install/approval URL to redirect to
//$install_url = "https://" . $shop . ".myshopify.com/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

// Redirect
//header("Location: " . $install_url);
//die();