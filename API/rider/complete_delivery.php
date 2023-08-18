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
    $digital_sign= $data_post['order_signature'];
    $active_status= isset($data_post['order_status']) ? $data_post['order_status'] : 'Delivered';
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
        $status_received_by = $active_status;
        mysqli_query($con,"UPDATE orders SET status = '".$status_received_by."' WHERE track_no = '".$order_id."'");
        if ($active_status=='Delivered') {
            $date = date('Y-m-d H:i:s');
            $status_received_by .= ' ( Received By  Self )';
            mysqli_query($con, "UPDATE orders SET received_by ='".$status_received_by."' WHERE track_no = '".$order_id."'");
            $check_for = ' AND  assignment_type = 2 ';
            $rider_status_done = " rider_status_done_no = '1', ";
            $fetch = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM orders WHERE track_no ='".$order_id."'"));
            mysqli_query($con, "UPDATE assignment_record SET rider_status_done_no = '1', status_update_time ='".$date."' WHERE order_num = '".$order_id."'  AND  assignment_type = 2");
            if (isset($fetch['booking_type']) && $fetch['booking_type']==3) {
                mysqli_query($con,"UPDATE orders SET payment_status = 'Paid' WHERE track_no = '".$order_id."'");
            }
            $credit = 0;
            $rider_id = $fetch['delivery_rider'];
            $cod_q = "SELECT collection_amount from orders where track_no ='".$order_id."'";
            $cod_result = mysqli_query($con,$cod_q);
            $check_cod=mysqli_fetch_array($cod_result);
            $rider_b = "SELECT * from rider_wallet_ballance where rider_id=".$rider_id;
            $rider_res= mysqli_query($con,$rider_b);
            $rider_prev_balance_q = mysqli_fetch_array($rider_res);
            $rider_prev_balance = $rider_prev_balance_q['balance'];
            $newBalance = $rider_prev_balance + $check_cod['collection_amount'];
            $check_q = "SELECT * from rider_wallet_ballance where rider_id =".$rider_id;
            $check_res = mysqli_query($con,$check_q);
            $check_rider_exists  = mysqli_fetch_array($check_res);
            $rider_name_q =  mysqli_fetch_array(mysqli_query($con,"SELECT Name FROM users WHERE id ='".$rider_id."' "));
            $rider_name = $rider_name_q['Name'];
            $master_id = '';
            if (isset($check_rider_exists['rider_id']) && !empty($check_rider_exists['rider_id'])) {
                $query = "UPDATE  rider_wallet_ballance set balance = ".$newBalance.", update_date = '".date('Y-m-d H:i:s')."' WHERE rider_id =  ".$rider_id;
                $cod_q = mysqli_query($con, $query);
                $master_id = $rider_prev_balance_q['id'];
            }else{
                $query2 = "INSERT INTO `rider_wallet_ballance`(`rider_id`, `rider_name`, `balance`, `update_date`) VALUES (".$rider_id." , '".$rider_name."' , ".$newBalance.",'".date('Y-m-d H:i:s')."')";
                $cod_q = mysqli_query($con, $query2);
                $master_id = mysqli_insert_id($con);
            }
            mysqli_query($con, "INSERT INTO `rider_wallet_ballance_log`(`order_id`,`order_no`,`rider_id`,`rider_name`, `debit`,`credit`,`date`)VALUES (".$master_id." ,".$order_id.",".$rider_id.",'".$rider_name."','".$check_cod['collection_amount']."','".$credit."','".$date."')");

            mysqli_query($con,"UPDATE orders SET rider_collection = 1 WHERE track_no = '".$order_id."'");



            if (isset($_FILES["order_signature"]["name"]) and !empty($_FILES["order_signature"]["name"]))
            {
                if (!file_exists("images/order_signature/".$track_no."/")) {
                        mkdir("images/order_signature/".$track_no."/");
                    }
                $target_dir = "images/order_signature/$track_no/";

                $target_file = $target_dir .uniqid(). basename($_FILES["order_signature"]["name"]);

                $extension = pathinfo($target_file,PATHINFO_EXTENSION);
                if($extension=='jpg'||$extension=='png'||$extension=='jpeg') {
                    if (move_uploaded_file($_FILES["order_signature"]["tmp_name"], $target_file))
                    {
                        mysqli_query($con,"UPDATE order SET order_signature='".$target_file."' WHERE `track_no`='".$track_no."' ");
                    }
                }
            }

            mysqli_query($con,"INSERT INTO `order_logs`(`order_no`, `order_status`,`created_on`) VALUES ('".$order_id."','".$status_received_by."','".$date."')");
            http_response_code(201);
            echo json_encode(array("response"=>1, "message" => "Order ".$track_no." updated successfully "));
            exit();
        }
    }


?>
