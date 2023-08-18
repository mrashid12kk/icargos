<?php
session_start();
date_default_timezone_set("Asia/Karachi");
require 'includes/conn.php';
$message = '';



if(isset($_POST['order_ids']) && !empty(json_decode($_POST['order_ids'])) && !empty($_POST['active_courier'])){

    // echo "<pre>";
    // print_r($_POST);
    // die();
    $date=date('Y-m-d H:i:s');
    $order_id_data = json_decode($_POST['order_ids']);
    $pickup_driver_id = $_POST['active_courier'];


    //////////////////
    $assignment_no = str_pad(rand(0,999999), 5, "0", STR_PAD_LEFT);
    $imp_ids = implode(",", $order_id_data);
    $business_sq = mysqli_query($con,"SELECT GROUP_CONCAT(DISTINCT customer_id SEPARATOR ',') as business_ids FROM orders WHERE id IN(".$imp_ids.") ");
    $business_ids_q = mysqli_fetch_array($business_sq);
    $business_ids = $business_ids_q['business_ids'];
    // mysqli_query($con,"INSERT INTO assignments(`assignment_no`,`assignment_type`,`business_ids`,`rider_id`) VALUES('".$assignment_no."','Pickup','".$business_ids."','".$pickup_driver_id."') ");

        if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
            mysqli_query($con,"INSERT INTO assignments(`assignment_no`,`assignment_type`,`business_ids`,`rider_id`,`assign_branch`,`branch_id`) VALUES('".$assignment_no."','Pickup','".$business_ids."','".$pickup_driver_id."','".$_SESSION['branch_id']."',".$_SESSION['branch_id'].") ");
        }else{
            mysqli_query($con,"INSERT INTO assignments(`assignment_no`,`assignment_type`,`business_ids`,`rider_id`) VALUES('".$assignment_no."','Pickup','".$business_ids."','".$pickup_driver_id."') ");
        }
    /////////////////

    foreach($order_id_data as $order_id)
    {

        $query   = mysqli_query($con,"SELECT track_no FROM orders WHERE id =".$order_id." ");
        $record  = mysqli_fetch_array($query);

        // mysqli_query($con, "UPDATE orders SET status = 'Pick up in progress', status_reason ='', pickup_rider =".$pickup_driver_id.",assignment_no='".$assignment_no."' WHERE id = $order_id");
        mysqli_query($con, "UPDATE orders SET status_reason ='', pickup_rider =".$pickup_driver_id.",assignment_no='".$assignment_no."' WHERE id = $order_id");
        // mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`location`,`created_on`) VALUES ('".$record['track_no']."', 'Pick up in progress','','".$date."') ");

        $record_assignment = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM assignment_record WHERE order_num ='".$record['track_no']."'  AND assignment_type= 1 "));

        if (empty($record_assignment)) {
            mysqli_query($con,"INSERT INTO assignment_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`) VALUES ('".$record['track_no']."', '".$pickup_driver_id."', '".$date."', 2, 1 , 1) ");
        }else{
            mysqli_query($con,"UPDATE assignment_record SET user_id = '".$pickup_driver_id."', status_update_time ='".$date."'  WHERE id = '".$record_assignment['id']."'   ");
        }


//  echo "<pre>";
// print_r("INSERT INTO assignment_record(`order_num`,`user_id`,`assign_data_time`,`status_submitted`,`assignment_status`,`assignment_type`) VALUES ('".$record['track_no']."', '".$pickup_driver_id."', '".$date."', 2, 1 , 1) ");
// print_r($_POST);
// die();

    }

    $url = 'customer_pickup_sheet.php?assignment_no='.$assignment_no;

    $_SESSION['print_url'] = $url;
    header("Location:pickup_status_assign.php");

}else{
     header('Location: generate_run_sheet.php?message='.$message);
}
?>
