<?php
   function email_delivered($orde_id){

    global $con;
        $date=date('Y-m-d H:i:s');
        $order_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM orders WHERE track_no=".$orde_id));
        $customer_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM customers WHERE id=".$order_data['customer_id']));
        // $email_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM email_templates WHERE sms_events='Customer Booking' "));
        // $email_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM email_templates WHERE sms_events='Pickup Request' AND status='1'"));
        // $email_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM email_templates WHERE sms_events='Admin Booking' AND status='1' "));
        // $email_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM email_templates WHERE sms_events='Status Update' "));
        $email_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM email_templates WHERE sms_events='Delivered'  AND status='1' "));
        $template_content='';
         if (!empty($email_data)) {
            $template_content=$email_data['template_content'];
         }
        if (!empty($order_data)) {
            $template_content=preg_replace('/@Origin_City/', $order_data['origin'], $template_content);
            $template_content=preg_replace('/@Sender_Name/', $order_data['sname'], $template_content);
            $template_content=preg_replace('/@Sender_Phone/', $order_data['sphone'], $template_content);
            $template_content=preg_replace('/@Sender_Address/', $order_data['sender_address'], $template_content);
            $template_content=preg_replace('/@Destination_City/', $order_data['destination'], $template_content);
            $template_content=preg_replace('/@Receiver_Name/', $order_data['rname'], $template_content);
            $template_content=preg_replace('/@Receiver_Phone/', $order_data['rphone'], $template_content);
            $template_content=preg_replace('/@Reciover_Email/', $order_data['remail'], $template_content);
            $template_content=preg_replace('/@Receiver_Address/', $order_data['receiver_address'], $template_content);
            $template_content=preg_replace('/@Tracking_NO/', $order_data['track_no'], $template_content);
            $template_content=preg_replace('/@Item_Detail/', $order_data['product_desc'], $template_content);
            $template_content=preg_replace('/@Special_instruction/', $order_data['special_instruction'], $template_content);
            $template_content=preg_replace('/@Reference_No/', $order_data['ref_no'], $template_content);
            $template_content=preg_replace('/@Order_id/', $order_data['product_id'], $template_content);
            $template_content=preg_replace('/@No_of_pieces/', $order_data['quantity'], $template_content);
            $template_content=preg_replace('/@Weight/', $order_data['weight'], $template_content);
            $template_content=preg_replace('/@COD_amount/', $order_data['collection_amount'], $template_content);
            $template_content=preg_replace('/@Order_Status/', getKeyWord($order_data['status']), $template_content);
            $template_content=preg_replace('/@Received_By/', $order_data['received_by'], $template_content);
            $template_content=preg_replace('/@Tracking_History/', getKeyWord($order_data['status']), $template_content);
        }
        if (file_exists('includes/functions.php')){
            include_once 'includes/functions.php';
        }else if (file_exists('../includes/functions.php')) {
            include_once '../includes/functions.php';
        }else if (file_exists('../../includes/functions.php')) {
            include_once '../../includes/functions.php';
        }else if (file_exists('../../../includes/functions.php')){
            include_once '../../../includes/functions.php';
        }else if (file_exists('../../../../includes/functions.php')){
            include_once '../../../../includes/functions.php';
        }
          $prev_array =explode(',', $email_data['send_to']);
        if (in_array(1, $prev_array)) {
           $data['email'] = $_SESSION['users_email'];
             $insert_qry="INSERT INTO `email_detail`(`contact_email`,`message`,`created_on`,`status`,`template_id`,`company_id`,`subject`) VALUES ('". $_SESSION['users_email']."','".$template_content."','".$date."','1','".$email_data['id']."','". $_SESSION['users_id']."','Admin') ";
             $query=mysqli_query($con,$insert_qry);
            $message['subject'] = getKeyWord($order_data['status']);
            $message['body'] = "<b>Hello ".$_SESSION['users_name']." </b>";
            $message['body'] .= '<p>'.$template_content.'</p>';
              sendEmail($data, $message);
        } 
        if (in_array(2, $prev_array)) {
             $data['email'] = $order_data['semail'];
             $insert_qry="INSERT INTO `email_detail`(`contact_email`,`message`,`created_on`,`status`,`template_id`,`company_id`,`subject`) VALUES ('".$order_data['semail']."','".$template_content."','".$date."','1','".$email_data['id']."','".$order_data['customer_id']."','Shipper') ";
             $query=mysqli_query($con,$insert_qry);
            $message['subject'] = getKeyWord($order_data['status']);
            $message['body'] = "<b>Hello ".$order_data['sname']." </b>";
            $message['body'] .= '<p>'.$template_content.'</p>';
              sendEmail($data, $message);
        }
        if (in_array(3, $prev_array)) {
         $data['email'] = $order_data['remail'];
             $insert_qry="INSERT INTO `email_detail`(`contact_email`,`message`,`created_on`,`status`,`template_id`,`subject`) VALUES ('".$order_data['semail']."','".$template_content."','".$date."','1','".$email_data['id']."','Consignee') ";
             $query=mysqli_query($con,$insert_qry);
            $message['subject'] = getKeyWord($order_data['status']);
            $message['body'] = "<b>Hello ".$order_data['rname']." </b>";
            $message['body'] .= '<p>'.$template_content.'</p>';
              sendEmail($data, $message);
        }
   }
 ?>