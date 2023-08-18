<?php
require_once('../../admin/includes/conn.php');
// include("../../admin/includes/BarCode.php");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data_post = (array)json_decode(file_get_contents("php://input"));
function getBarCodeImageAssignmentRider($text = '', $code = null, $index)
{
    // $barcode = new BarCode();
    // $path = 'assets/barcodes/imagetemp' . $index . '.png';
    // $barcode->barcode($path, $text);
    $folder_path = 'assets/barcodes/imagetemp' . $index . '.png';
    return $folder_path;
}
$date = date('Y-m-d H:i:s');
$active_status = $data_post['status'];
$rider_id = $data_post['rider_id'];
$rider_type = $data_post['rider_type'];
$date = date('Y-m-d H:i:s');
$active_reason = isset($data_post['reason']) ? $data_post['reason'] : '';
$response = 1;
$message = '';
$trackArray  = explode(',', $data_post['track_no']);
// print_r($trackArray);
// die;
if($active_status=='Picked up'){
    $order_records = $data_post['track_no'];
    $track_records = rtrim($order_records, ',');
    $track_no_array = explode(',', $track_records);
    $order_records = '';
    foreach ($track_no_array as $value) {
        $track_id = "'" . $value . "'";
        $order_records .= $track_id . ',';
    }
    $order_records = rtrim($order_records, ',');
    
    $pickup_driver_id = $rider_id;
    $assignment_no = str_pad(rand(0, 999999), 5, "0", STR_PAD_LEFT);
    $check_query = mysqli_query($con, "SELECT assignment_no from assignments where assignment_no=" . $assignment_no);
    $assign_result = mysqli_fetch_array($check_query);
    $assign_check = isset($assign_result['assignment_no'])  ? $assign_result['assignment_no'] : '';
    if (isset($assign_check) && !empty($assign_check)) {
        $assignment_no = str_pad(rand(0, 999999999), 5, "0", STR_PAD_LEFT);
    }
    $main_assignment_no = $assignment_no;
    $business_sq = mysqli_query($con, "SELECT GROUP_CONCAT(DISTINCT customer_id SEPARATOR ',') as business_ids FROM orders WHERE track_no IN(" . $order_records . ") ");

    $business_ids_q = mysqli_fetch_array($business_sq);
    $business_ids = $business_ids_q['business_ids'];
    $barcode_image = getBarCodeImageAssignmentRider($assignment_no, null, $assignment_no);
    $branch_id = 1;
    mysqli_query($con, "INSERT INTO assignments(`assignment_no`,`assignment_type`,`business_ids`,`rider_id`,`assign_branch`,`branch_id`,`barcode_image`,`created_by`, `created_on`) VALUES('" . $assignment_no . "','Pickup','" . $business_ids . "','" . $pickup_driver_id . "','" . $branch_id . "'," . $branch_id . ", '$barcode_image', 1,'" . $date . "') ");

}
foreach ($trackArray as $key => $order_id) {
    $initialQuery = mysqli_query($con, "SELECT  status FROM orders WHERE track_no ='" . $order_id . "'");
    $initialRecord = mysqli_fetch_array($initialQuery);
    $initialStatus = $initialRecord['status'];
    $track_no = $order_id;
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
            if($active_status=='Picked up'){
                mysqli_query($con, "INSERT INTO assignment_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`,`created_on`) VALUES ('" . $order_id . "', '" . $pickup_driver_id . "', '" . $date . "', 2, 1 , 1,'" . $date . "') ");

                include('../../admin/assets/pdf/new/pdf.php');
                $file_name = '../../admin/assets/pdf/'.md5(rand()) . '.pdf';
                $pdf = new Pdf();
                $pdf->load_html(BASE_URL."portal/admin/pickup_assignment_sheet.php");
                $pdf->render();
                $file = $pdf->output();
                file_put_contents($file_name, $file);
                $data['email'] = 'fakharabbas2f@gmail.com';
                $customer_name = "My Name!";
                $message['subject'] = 'Invoice Created';
                $message['attachment'] = $file_name;
                $message['body'] = 'Please download your pickup run sheet!';
                require_once '../../admin/includes/functions.php';
                $isSent = sendEmail_pdf($data, $message);
                // echo $isSent;
                // die;
                // mysqli_query($con, "UPDATE orders SET pickup_rider =" . $pickup_driver_id . ",assignment_no='" . $main_assignment_no . "' WHERE track_no = '" . $order_id."'");
            }
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
                $status_received_by = $active_status;

                $active_status = $data_post['status'];
                if (isset($active_reason) and !empty($active_reason)) {
                    mysqli_query($con, "UPDATE orders SET status_reason ='" . $active_reason . "' WHERE track_no = '" . $track_no . "' ");
                    $status_received_by .= ' ( ' . $data_post['reason'] . ' ) ';
                }
                if (isset($data_post['received_by']) and !empty($data_post['received_by'])) {
                    $status_received_by .= ' ( Received By ' . $data_post['received_by'] . ' ) ';
                    $reason_enable = $data_post['received_by'];
                    mysqli_query($con, "UPDATE orders SET received_by ='" . $reason_enable . "' WHERE track_no = '" . $order_id . "' ");
                }

                // Upload Reason Image
                if (!file_exists("../admin/images/reason_image/" . $order_id . "/")) {
                    mkdir("../admin/images/reason_image/" . $order_id . "/");
                }
                $target_dir = "../admin/images/reason_image/" . $order_id . "/";
                $file = isset($data_post['reason_image']) ? $data_post['reason_image'] : '';
                $pathImage = '';
                if (isset($file) && !empty($file)) {
                    $pos = strpos($file, ';');
                    $type = explode(':', substr($file, 0, $pos))[1];
                    $mime = explode('/', $type);
                    $pathImage = "../admin/images/reason_image/" . $order_id . "/" . time() . '.' . $mime[1];
                    $file = substr($file, strpos($file, ',') + 1, strlen($file));
                    $dataBase64 = base64_decode($file);
                    file_put_contents($pathImage, $dataBase64);
                    $uploadSql = "UPDATE orders SET reason_image='" . $pathImage . "' WHERE `track_no`='" . $order_id . "' ";
                    mysqli_query($con, $uploadSql);
                }

                if ($active_status == "Delivered") {
                    updateRiderWalletBalance($order_id, $rider_id);
                }
            }
            mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`reason_image`,`created_on`) VALUES ('" . $order_id . "', '" . $status_received_by . "','" . $pathImage . "','" . $date . "') ");
            mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" . $order_id . "'");
        } else if ($initialStatus == 'New Booked' || $initialStatus == 'Ready for Pickup') {

            $q = mysqli_query($con, "UPDATE orders SET status ='" . $active_status . "' , pickup_rider = " . $rider_id . " WHERE track_no = '" . $order_id . "'");
            $assignSql = "INSERT INTO `assignment_record`(`rider_status_done_no`,  `order_num`, `user_id`, `assign_data_time`, `status_update_time`, `status_submitted`, `assignment_status`, `assignment_type`, `created_on`) VALUES (1,'" . $order_id . "','" . $rider_id . "','" . $date . "','" . $date . "',2,1,1,'" . $date . "')";
            mysqli_query($con, $assignSql);
            $message .= "Order " . $track_no . " updated as " . $active_status . " - ";
            mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`created_on`) VALUES ('" . $order_id . "', '" . $active_status . "','" . $date . "') ");
            mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" . $order_id . "'");
        } else {
            $message .= "Order " . $order_id . " is not assigned to you.";
            $response = 0;
        }
    }
}
if ($response == 1) {
    http_response_code(201);
    echo json_encode(array("response" => 1, "message" => $message));
    exit();
} else {
    http_response_code(200);
    echo json_encode(array("response" => 0, "message" => $message));
    exit();
}

exit();