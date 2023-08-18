<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
$language = $data_post['language'];
$lang_query = mysqli_query($con, "SELECT * FROM portal_language WHERE language = '" . $language . "'");
$get_lang = mysqli_fetch_assoc($lang_query);
$langid = isset($get_lang['id']) ? $get_lang['id']  : 1;
$trns_query = mysqli_query($con, "SELECT keyword,translation FROM language_translator WHERE language_id = '" . $langid . "'");
$get_trans_array = array();
while ($get_trans = mysqli_fetch_assoc($trns_query)) {

    array_push($get_trans_array, $get_trans);
}

http_response_code(201);
echo json_encode(array("response" => 1, 'data' => $get_trans_array));
exit();


exit();