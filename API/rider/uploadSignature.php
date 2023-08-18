<?php

require_once('../../admin/includes/conn.php');

header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Max-Age: 3600");

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data_post = (array)json_decode(file_get_contents("php://input"));

// $data_post = $_POST;

$track_no = isset($data_post['track_no']) ? $data_post['track_no'] : '';

$rider_id = isset($data_post['rider_id']) ? $data_post['rider_id'] : '';

$order_id = isset($data_post['track_no']) ? $data_post['track_no'] : '';

$active_status = isset($data_post['status']) ? $data_post['status'] : '';

$active_reason = isset($data_post['reason']) ? $data_post['reason'] : '';

$receiver_name = isset($data_post['receiver_name']) ? $data_post['receiver_name'] : '';

$receiver_cnic = isset($data_post['receiver_cnic']) ? $data_post['receiver_cnic'] : '';

$response = 1;

$message = '';

$rider_type = $data_post['rider_type'];

$date = date('Y-m-d H:i:s');

$configCnicReq = getConfig('receiver_cnic_required');

if (isset($configCnicReq) && $configCnicReq == 'yes' && empty($receiver_cnic)) {

    http_response_code(200);

    echo json_encode(array("response" => 0, "message" => "Receiver CNIC is required!"));

    exit();

}

mysqli_query($con, 'UPDATE orders set received_by = "' . $receiver_name . '" ,receiver_cnic = "' . $receiver_cnic . '" WHERE track_no = ' . $track_no . ' ');



// Set Reason

if (isset($active_reason) and !empty($active_reason)) {

    mysqli_query($con, "UPDATE orders SET status_reason ='" . $active_reason . "' WHERE track_no = '" . $track_no . "' ");

}



$query = mysqli_query($con, "SELECT orders.status,allowed_status FROM orders LEFT JOIN order_status ON orders.status=order_status.status WHERE  orders.track_no ='$track_no'");

$record = mysqli_fetch_array($query);

$allowed_status = explode(',', $record['allowed_status']);

$check_status  = mysqli_query($con, "SELECT sts_id,marked_done FROM order_status WHERE status ='" . $active_status . "'   ");

$status_record = mysqli_fetch_array($check_status);

$id_check = $status_record['sts_id'];

if (!in_array($id_check, $allowed_status)) {

    $message .= "Order " . $track_no . " can't be assigned as " . $active_status . " - ";

    $response = 0;

} else {

    $can_be_marked_done = 1;

    if ($status_record['marked_done'] == 1) {

        $can_be_marked_done = 2;

    }

    $query = '';

    if (isset($rider_type) and $rider_type == 'pickup_rider') {

        $query = mysqli_query($con, "SELECT * FROM orders WHERE track_no ='" . $order_id . "' and pickup_rider=" . $rider_id);

    } else if (isset($rider_type) and $rider_type == 'delivery_rider') {

        $query = mysqli_query($con, "SELECT * FROM orders WHERE track_no ='" . $order_id . "' and delivery_rider=" . $rider_id);

    }

    $record = mysqli_fetch_array($query);



    if (!empty($record)) {



        if ($rider_type == 'delivery_rider') {

            $assignment_no = $record['delivery_assignment_no'];

        }

        if ($rider_type == 'pickup_rider') {

            $assignment_no = $record['assignment_no'];

        }

        $check_rider = mysqli_query($con, "SELECT * FROM assignments WHERE rider_id = " . $rider_id . "  and assignment_no='" . $assignment_no . "'");



        if ($check_rider->num_rows > 0) {



            $q = mysqli_query($con, "UPDATE orders SET status ='" . $active_status . "'  WHERE track_no = '" . $order_id . "'");

            $message .= "Order " . $track_no . " updated as " . $active_status . " - ";

            $check_for = '';



            if (isset($rider_type) and $rider_type == 'delivery_rider') {

                $check_for = ' AND  assignment_type = 2 ';

            } else if (isset($rider_type) and $rider_type == 'pickup_rider') {

                $check_for = ' AND  assignment_type = 1 ';

            }

            $rider_status_done = '';

            if ($can_be_marked_done == 2) {

                $rider_status_done = " rider_status_done_no = '1', ";

            }

            mysqli_query($con, "UPDATE assignment_record SET $rider_status_done status_update_time ='" . $date . "' WHERE order_num = '" . $order_id . "' $check_for");



            $active_status = $data_post['status'];

            if (isset($data_post['reason_enable']) and !empty($data_post['reason_enable'])) {

                $active_status .= ' ( ' . $data_post['reason_enable'] . ' ) ';

                $reason_enable = $data_post['reason_enable'];

                mysqli_query($con, "UPDATE orders SET status_reason ='" . $reason_enable . "' WHERE track_no = '" . $order_id . "' ");

            }



            $status_received_by = $active_status;



            if (isset($receiver_name) and !empty($receiver_name)) {

                $status_received_by .= ' ( Received By ' . $receiver_name . ' ) ';

                mysqli_query($con, "UPDATE orders SET received_by ='" . $status_received_by . "' WHERE track_no = '" . $order_id . "' ");

            }





            if ($active_status == "Delivered") {

                updateRiderWalletBalance($order_id, $rider_id);

            }

        }

        mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`created_on`) VALUES ('" . $order_id . "', '" . $status_received_by . "','" . $date . "') ");

        mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" . $order_id . "'");

        // Upload Signature

        if (!file_exists("../../admin/images/order_signature/" . $order_id . "/")) {

            mkdir("../../admin/images/order_signature/" . $order_id . "/");

        }

        $target_dir = "../../admin/images/order_signature/" . $order_id . "/";

        $file = $data_post['order_signature'];

        $pos = strpos($file, ';');

        $type = explode(':', substr($file, 0, $pos))[1];

        $mime = explode('/', $type);





        $pathImage = "../../admin/images/order_signature/" . $order_id . "/" . time() . '.' . $mime[1];
        $savepath = "../admin/images/order_signature/" . $order_id . "/" . time() . '.' . $mime[1];

        $file = substr($file, strpos($file, ',') + 1, strlen($file));

        $dataBase64 = base64_decode($file);

        file_put_contents($pathImage, $dataBase64);

        $uploadSql = "UPDATE orders SET order_signature='" . $savepath . "' WHERE `track_no`='" . $order_id . "' ";

        mysqli_query($con, $uploadSql);















        // Upload CNIC

        if (!file_exists("../../admin/images/receiver_cnic_pic/" . $order_id . "/")) {

            mkdir("../../admin/images/receiver_cnic_pic/" . $order_id . "/");

        }

        $target_dir = "../../admin/images/receiver_cnic_pic/" . $order_id . "/";

        $file = $data_post['receiver_cnic_pic'];

        $pos = strpos($file, ';');

        $type = explode(':', substr($file, 0, $pos))[1];

        $mime = explode('/', $type);





        $pathImage = "../../admin/images/receiver_cnic_pic/" . $order_id . "/" . time() . '.' . $mime[1];
        $savepath = "../admin/images/receiver_cnic_pic/" . $order_id . "/" . time() . '.' . $mime[1];

        $file = substr($file, strpos($file, ',') + 1, strlen($file));

        $dataBase64 = base64_decode($file);

        file_put_contents($pathImage, $dataBase64);

        $uploadSql = "UPDATE orders SET receiver_cnic_pic='" . $savepath . "' WHERE `track_no`='" . $order_id . "' ";

        mysqli_query($con, $uploadSql);

    } else {

        $message .= "Order " . $track_no . " is not assigned to you.";

        $response = 0;

    }

}





if ($response == 1) {

    http_response_code(201);

    echo json_encode(array("response" => 1, "message" => "Order Status updated successfully."));

    exit();

} else {

    http_response_code(200);

    echo json_encode(array("response" => 0, "message" => $message));

    exit();

}







//if (isset($_FILES["order_signature"]["name"]) and !empty($_FILES["order_signature"]["name"])) {



//     if (!file_exists("../admin/images/order_signature/" . $order_id . "/")) {

//         mkdir("../admin/images/order_signature/" . $order_id . "/");

//     }

//     $target_dir = "../admin/images/order_signature/$order_id/";



//     $target_file = $target_dir . uniqid() . basename($_FILES["order_signature"]["name"]);



//     $extension = pathinfo($target_file, PATHINFO_EXTENSION);



//     if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'JOEG') {

//         if (move_uploaded_file($_FILES["order_signature"]["tmp_name"], $target_file)) {



//             mysqli_query($con, "UPDATE orders SET order_signature='" . $target_file . "' WHERE `track_no`='" . $order_id . "' ");

//         }

//     }

// }

// if (isset($_FILES["receiver_cnic_pic"]["name"]) and !empty($_FILES["receiver_cnic_pic"]["name"])) {



//     if (!file_exists("../admin/images/receiver_cnic/" . $order_id . "/")) {

//         mkdir("../admin/images/receiver_cnic/" . $order_id . "/");

//     }

//     $target_dir = "../admin/images/receiver_cnic/$order_id/";



//     $target_file = $target_dir . uniqid() . basename($_FILES["receiver_cnic_pic"]["name"]);



//     $extension = pathinfo($target_file, PATHINFO_EXTENSION);



//     if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'JOEG') {

//         if (move_uploaded_file($_FILES["receiver_cnic_pic"]["tmp_name"], $target_file)) {



//             mysqli_query($con, "UPDATE orders SET receiver_cnic_pic='" . $target_file . "' WHERE `track_no`='" . $order_id . "' ");

//         }

//     }

// }

exit();