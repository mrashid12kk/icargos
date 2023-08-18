<?php
session_start();

    include_once 'includes/conn.php';
    include_once 'includes/role_helper.php';
    include_once "includes/sms_helper.php";
    // var_dump($_POST);
if (isset($_POST['manifest_no']) && !empty($_POST['manifest_no'])) {
        // $data=array();
    $branch_id = 1;
    if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
    }
    $date=date('Y-m-d H:i:s');
    $next_no = mysqli_query($con,"SELECT * from manifest_master order by id desc limit 1");
    $number = mysqli_fetch_assoc($next_no);
    $nextNumber = $_REQUEST['manifest_no'];
    $check_manifest=isset($_POST['check_manifest']) ? $_POST['check_manifest'] : '';
    $returnAway = array();
    // var_dump($_POST);
    // die();
    $delete = "DELETE FROM `manifest_master` where `manifest_no` = '".$nextNumber."' ";
    $delete_query = mysqli_query($con, $delete);
    if(true){
    $insertQ = "INSERT INTO `manifest_master`(`manifest_no`, `branch_id`, `date`, `type`, `bilty_no`, `service_by`, `transport_co`, `truck_no`,`sending_branch`,  `seal_no`, `origin`, `receiving_branch`, `destination`,`departure_date`, `mode`,`receiver_name`,`departure_time`,`arrival_date`,`arrival_time`,`remarks`,`pieces`,`weight`,`pick_via`,`created_at`,`user_id`,`check_manifest`,`status`) VALUES ('".$nextNumber."',".$branch_id.",'".$_POST['date']."','".$_POST['type']."','".$_POST['bilty_no']."','".$_POST['service_by']."','".$_POST['transport_company']."','".$_POST['truck_no']."','".$_POST['sending_branch']."','".$_POST['seal_no']."','".$_POST['city1']."','".$_POST['receiving_branch']."','".$_POST['city2']."','".$_POST['departure_date']."','".$_POST['mode']."','".$_POST['receiver_name']."','".$_POST['departure_time']."','".$_POST['arrival_date']."','".$_POST['arrival_time']."','".$_POST['remarks']."','".$_POST['pieces']."','".$_POST['weight']."','".$_POST['pick_via']."','".$date."','".$_SESSION['users_id']."','".$check_manifest."', '".$_POST['status']."')";
    // var_dump($insertQ);
    // die();
        // $updateQuery = "UPDATE `manifest_master` set `manifest_no` = '".$nextNumber."',  `branch_id` = '".$branch_id."' , `date` = '".$_POST['date']."' , `type` = '".$_POST['type']."' ,  `bilty_no` = '".$_POST['bilty_no']."' ,  `service_by` = '".$_POST['service_by']."', `transport_co` = '".$_POST['transport_company']."' ,  `truck_no` =  '".$_POST['truck_no']."' , `sending_branch` = '".$_POST['sending_branch']."' ,  `seal_no` = '".$_POST['seal_no']."' ,  `origin` = '".$_POST['city1']."' , `receiving_branch` = '".$_POST['receiving_branch']."' , `destination` = ";
    $insertQuery = mysqli_query($con,$insertQ);
    $last_id = mysqli_insert_id($con);
    if($last_id){
    $data['insert_id']=$last_id;
        foreach ($_POST['all_cn_no'] as $key => $value) {
            $inQ = "INSERT INTO `manifest_detail`(`manifest_id`, `track_no`, `manifest_no`) VALUES (".$last_id.",'".$value."','".$nextNumber."')";
            if (isset($_POST['status']) && !empty($_POST['status'])) {
                 $updateQuery = "UPDATE orders set status='".$_POST['status']."' WHERE track_no = '".$value."'";
                 $updatestatus = mysqli_query($con,$updateQuery);

            }
            $date = date('Y-m-d H:i:s');
            $order_logs_q = "INSERT INTO `order_logs`(`order_no`, `order_status`,`created_on`,`tracking_remarks`,`country`,`city`,`voucher_type`,`trans_number`) VALUES ('".$value."','".$_POST['status']."','".$date."','".$_POST['tracking_remarks']."','".$_POST['country']."','".$_POST['city']."', 'Manifest','".$nextNumber."' )";

            $order_log = mysqli_query($con,$order_logs_q);
            $insertQu = mysqli_query($con,$inQ);
            continue;
            $sendSms = sendSmsMobileGateWay($value, 'Status Update');
        }
        $returnAway['manifest_id'] = $nextNumber+1;
        $data['bilty_no']=$nextNumber+1;
        echo json_encode($data);


    }

}
}


 ?>
