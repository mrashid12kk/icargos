<?php
    date_default_timezone_set("Asia/Karachi");
    include_once "../admin/includes/conn.php";
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    // header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $REQUEST=file_get_contents("php://input");
    $data_post = (array)json_decode(file_get_contents("php://input"));
    $track_no= $data_post['track_no'];
    $active_status= isset($data_post['order_status']) ? $data_post['order_status'] : 'Picked up';
    $order_id = $data_post['track_no'];


    $query = mysqli_query($con,"SELECT orders.status,allowed_status FROM orders LEFT JOIN order_status ON orders.status=order_status.status WHERE  orders.track_no ='".$track_no."'");
    $record = mysqli_fetch_assoc($query);
    $allowed_status = explode(',', $record['allowed_status']);
    $check_status  = mysqli_query($con,"SELECT sts_id FROM order_status WHERE status ='".$active_status."'");
    $status_record = mysqli_fetch_array($check_status);
    $id_check = $status_record['sts_id'];
    if (!in_array($id_check, $allowed_status))
    {
        http_response_code(200);
        echo json_encode(array("response"=>0, "message" => "Order ".$track_no." can't be assigned as ".$active_status));
        exit();
    }else{
        $date = date('Y-m-d H:i:s');
        mysqli_query($con,"UPDATE orders SET status = '".$active_status."' WHERE track_no = '".$order_id."'");
        if ($active_status=='Picked up') {
            mysqli_query($con, "UPDATE assignment_record SET rider_status_done_no = '1', status_update_time ='".$date."' WHERE order_num = '".$order_id."'  AND  assignment_type = 1");
        }
        mysqli_query($con,"INSERT INTO `order_logs`(`order_no`, `order_status`,`created_on`) VALUES ('".$order_id."','".$active_status."','".$date."')");
    }
    http_response_code(201);
        echo json_encode(array("response"=>1, "message" => "Order ".$track_no." updated successfuly "));
        exit();
?>
