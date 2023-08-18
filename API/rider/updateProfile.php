<?php
require_once('../../admin/includes/conn.php');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
// $data_post = $_POST;
$rider_id = $data_post['rider_id'];
$full_name = $data_post['full_name'];
$phone = $data_post['phone'];
$email = $data_post['email'];
mysqli_query($con, 'UPDATE users set Name = "' . $full_name . '" ,phone = "' . $phone . '",email = "' . $email . '" Where id = ' . $rider_id . ' ');
$target_dir = "../admin/img/";
$file = $data_post['image'];
$pos = strpos($file, ';');
$type = explode(':', substr($file, 0, $pos))[1];
$mime = explode('/', $type);


$pathImage = "../admin/img/Rider_".time().'.'.$mime[1];
$file = substr($file, strpos($file, ',') + 1, strlen($file));
$dataBase64 = base64_decode($file);
file_put_contents($pathImage, $dataBase64);
$replaceName = str_replace('../admin/img','img',$pathImage);
$uploadSql = "UPDATE users SET image='" . $replaceName . "' WHERE id = $rider_id ";

mysqli_query($con, $uploadSql);
// $target_file = '';
// if (isset($_FILES["image"]["name"]) and !empty($_FILES["image"]["name"])) {
//     $image_name = uniqid() . basename($_FILES["image"]["name"]);
//     $target_file = $target_dir . $image_name;
//     $extension = pathinfo($target_file, PATHINFO_EXTENSION);
//     if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'JPEG ' || $extension == 'jpeg ') {
//         if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
//             mysqli_query($con, "UPDATE users SET image='img/" . $image_name . "' WHERE id = $rider_id ");
//         }
//     }
// }


if (mysqli_affected_rows($con) > 0) {
    http_response_code(201);
    echo json_encode(array("response" => 1, "message" => "Profile updated successfully."));
    exit();
} else {
    http_response_code(200);
    echo json_encode(array("response" => 0, "message" => "Error Occured!"));
    exit();
}

exit();