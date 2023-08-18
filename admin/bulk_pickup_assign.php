<?php
session_start();
require 'includes/conn.php';
function getBarCodeImage($text = '', $code = null, $index)
{
    require_once('../includes/BarCode.php');
    $barcode = new BarCode();
    $path = 'assets/barcodes/imagetemp' . $index . '.png';
    $barcode->barcode($path, $text);
    $folder_path = 'assets/barcodes/imagetemp' . $index . '.png';
    return $folder_path;
}
$message = '';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
if (isset($_POST['order_ids']) && !empty($_POST['order_ids']) && !empty($_POST['active_courier'])) {

    $date = date('Y-m-d H:i:s');
    $order_records = $_POST['order_ids'];
    $track_records = rtrim($order_records, ',');
    $track_no_array = explode(',', $track_records);
    $order_records = '';
    foreach ($track_no_array as $value) {
        $track_id = "'" . $value . "'";
        $order_records .= $track_id . ',';
    }
    $order_records = rtrim($order_records, ',');

    $pickup_driver_id = $_POST['active_courier'];
    $created_by = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';


    //////////////////
    $assignment_no = str_pad(rand(0, 999999), 5, "0", STR_PAD_LEFT);
    $check_query = mysqli_query($con, "SELECT assignment_no from assignments where assignment_no=" . $assignment_no);
    $assign_result = mysqli_fetch_array($check_query);
    $assign_check = isset($assign_result['assignment_no'])  ? $assign_result['assignment_no'] : '';
    if (isset($assign_check) && !empty($assign_check)) {
        $assignment_no = str_pad(rand(0, 999999999), 5, "0", STR_PAD_LEFT);
    }
    $business_sq = mysqli_query($con, "SELECT GROUP_CONCAT(DISTINCT customer_id SEPARATOR ',') as business_ids FROM orders WHERE track_no IN(" . $order_records . ") ");

    $business_ids_q = mysqli_fetch_array($business_sq);
    $business_ids = $business_ids_q['business_ids'];

    $barcode_image = getBarCodeImage($assignment_no, null, $assignment_no);
    $branch_id = 1;
    if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
    }
    mysqli_query($con, "INSERT INTO assignments(`assignment_no`,`assignment_type`,`business_ids`,`rider_id`,`assign_branch`,`branch_id`,`barcode_image`,`created_by`, `created_on`) VALUES('" . $assignment_no . "','Pickup','" . $business_ids . "','" . $pickup_driver_id . "','" . $branch_id . "'," . $branch_id . ", '$barcode_image', $created_by,'" . $date . "') ");

    $order_id_data = explode(',', $order_records);
    foreach ($order_id_data as $order_id) {

        $query   = mysqli_query($con, "SELECT track_no FROM orders WHERE track_no =" . $order_id . " ");
        $record  = mysqli_fetch_array($query);
        mysqli_query($con, "UPDATE orders SET status = 'Pick up in progress', status_reason ='', pickup_rider =" . $pickup_driver_id . ",assignment_no='" . $assignment_no . "' WHERE track_no = " . $order_id);

        mysqli_query($con, "INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`,`user_id`) VALUES ('" . $record['track_no'] . "', 'Pick up in progress','','" . $date . "','" . $user_id . "') ");
            // include "includes/sms_helper.php";
            // $sendSms = sendSmsMobileGateWay($order_id, 'Status Update');

        mysqli_query($con, "UPDATE orders set action_date='" . $date . "' WHERE track_no='" . $record['track_no'] . "'");
        $record_assignment = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM assignment_record WHERE order_num ='" . $record['track_no'] . "'  AND assignment_type= 1 "));
        $track_no = $record['track_no'];
        if (isset($_SESSION['branch_id']) and !empty($_SESSION['branch_id'])) {
            $upquery = "UPDATE orders SET current_branch = '" . $branch_id . "' WHERE track_no = " . $order_id;

            $query = mysqli_query($con, $upquery);
        } else {
            $upquery = "UPDATE orders SET current_branch = 1 WHERE track_no = " . $order_id;

            $query = mysqli_query($con, $upquery);
        }
        if (empty($record_assignment)) {
            mysqli_query($con, "INSERT INTO assignment_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`,`created_on`) VALUES ('" . $record['track_no'] . "', '" . $pickup_driver_id . "', '" . $date . "', 2, 1 , 1,'" . $date . "') ");
        } else {
            mysqli_query($con, "UPDATE assignment_record SET user_id = '" . $pickup_driver_id . "', status_update_time ='" . $date . "'  WHERE id = '" . $record_assignment['id'] . "'");
        }


        if (isset($_POST['return_to']) && !empty($_POST['return_to'])) {
            $url = 'pickup_assignment_sheet.php?assignment_no=' . $assignment_no;
            echo "<script type='text/javascript'>window.open('" . $url . "');</script>";
            echo "<script type='text/javascript'>location.replace('" . $_POST['return_to'] . "?message=');</script>";
        } else {
            ///////////send Email 
            include_once "email/sendEmail/pickup_request.php";
            email_pickup_request($order_id);
            $url = 'pickup_assignment_sheet.php?assignment_no=' . $assignment_no;
            $_SESSION['print_url'] = $url;
            header("Location:pickup_run_sheet.php");
        }
    }
    //$url = 'pickup_assignment_sheet.php?assignment_no='.$assignment_no;

    //$_SESSION['print_url'] = $url;
    //header("Location:pickup_run_sheet.php");

} else {
    header('Location: pickup_run_sheet.php?message=' . $message);
}
