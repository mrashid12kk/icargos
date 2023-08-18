<?php
    date_default_timezone_set("Asia/Karachi");
    include_once "../admin/includes/conn.php";
    // header("Access-Control-Allow-Origin: *");
    // header("Content-Type: application/json; charset=UTF-8");
    // // header("Access-Control-Allow-Methods: POST");
    // header("Access-Control-Max-Age: 3600");
    // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));

    // $auth_key= $data_post['auth_key'];

    // $auth_query = mysqli_query($con,"SELECT * FROM users  WHERE auth_key ='".$auth_key."' ");
    // $count = mysqli_num_rows($auth_query);

    $citiesList = array();

    $query1 = mysqli_query($con,"SELECT id,city_name FROM cities WHERE 1 order by id desc ");
    while ($citieyList=mysqli_fetch_assoc($query1)) {
        array_push($citiesList, $citieyList);
    }

    if(mysqli_affected_rows($con) > 0){
        http_response_code(201);
        echo json_encode(array("response"=>1,'data'=>$citiesList, "message" => "All cities list."));
        exit();
    }else{
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "No record found"));
        exit();
    }
?>
