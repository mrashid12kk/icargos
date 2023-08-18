<?php
    date_default_timezone_set("Asia/Karachi");
    include_once "../includes/conn.php";
    $rider_id = $_GET['rider_id'];
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $data_post = (array)json_decode(file_get_contents("php://input"));

    $riderData = array();
    $query = mysqli_query($con, "SELECT * FROM rider_wallet_ballance_log where rider_id = ".$rider_id);
    while ($row= mysqli_fetch_assoc($query))
    {
       array_push($riderData, $row);
    }
    $fetch=mysqli_fetch_assoc($query);
    http_response_code(201);
    echo json_encode(array("response"=>1,'data'=>$riderData,"message" => "Rider Details."));
    exit();
?>
