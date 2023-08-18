<?php
   function email_status_update($orde_id){
    global $con;
        $date=date('Y-m-d H:i:s');
        // echo "SELECT * FROM orders WHERE track_no='".$orde_id."'".'<br>';
        $order_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM orders WHERE track_no='".$orde_id."'"));
        // echo "<pre>";
        // print_r ($order_data);
        // echo "</pre>";
        // die;
        $origin = isset($order_data['origin']) ? $order_data['origin']:'';
        $sname = isset($order_data['sname']) ? $order_data['sname']:'';
        $semail = isset($order_data['semail']) ? $order_data['semail']:'';
        $sphone = isset($order_data['sphone']) ? $order_data['sphone']:'';
        $sender_address = isset($order_data['sender_address']) ? $order_data['sender_address']:'';
        $destination = isset($order_data['destination']) ? $order_data['destination']:'';
        $rname = isset($order_data['rname']) ? $order_data['rname']:'';
        $rphone = isset($order_data['rphone']) ? $order_data['rphone']:'';
        $remail = isset($order_data['remail']) ? $order_data['remail']:'';
        $receiver_address = isset($order_data['receiver_address']) ? $order_data['receiver_address']:'';
        $track_no = isset($order_data['track_no']) ? $order_data['track_no']:'';
        $product_desc = isset($order_data['product_desc']) ? $order_data['product_desc']:'';
        $special_instruction = isset($order_data['special_instruction']) ? $order_data['special_instruction']:'';
        $ref_no = isset($order_data['ref_no']) ? $order_data['ref_no']:'';
        $product_id = isset($order_data['product_id']) ? $order_data['product_id']:'';
        $quantity = isset($order_data['quantity']) ? $order_data['quantity']:'';
        $weight = isset($order_data['weight']) ? $order_data['weight']:'';
        $collection_amount = isset($order_data['collection_amount']) ? $order_data['collection_amount']:'';
        $status = isset($order_data['status']) ? getKeyWord($order_data['status']):'';
        $received_by = isset($order_data['received_by']) ? $order_data['received_by']:'';
        $customer_id = isset($order_data['customer_id']) ? $order_data['customer_id']:'';
        $customer_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM customers WHERE id=".$customer_id));
        $email_data =mysqli_query($con,"SELECT * FROM email_templates WHERE sms_events='Status Update' AND status='1' ");
        $template_content='';
        $send_to='';
        while($row= mysqli_fetch_array($email_data)) 
        {
           $prev_array = isset($row['status_allowed']) ? explode(',', $row['status_allowed']):'';
           if(isset($order_data['status']) && in_array($order_data['status'] , $prev_array)) 
           {
                $template_content=isset($row['template_content']) ? $row['template_content']:'';
                $send_to=isset($row['send_to']) ? $row['send_to']:'';
                $template_id=isset($row['id']) ? $row['id']:'';
              if(!empty($order_data)) 
              {
                $template_content=preg_replace('/@Origin_City/', $origin, $template_content);
                $template_content=preg_replace('/@Sender_Name/', $sname, $template_content);
                $template_content=preg_replace('/@Sender_Phone/', $sphone, $template_content);
                $template_content=preg_replace('/@Sender_Address/', $sender_address, $template_content);
                $template_content=preg_replace('/@Destination_City/', $destination, $template_content);
                $template_content=preg_replace('/@Receiver_Name/', $rname, $template_content);
                $template_content=preg_replace('/@Receiver_Phone/', $rphone, $template_content);
                $template_content=preg_replace('/@Reciover_Email/', $remail, $template_content);
                $template_content=preg_replace('/@Receiver_Address/', $receiver_address, $template_content);
                $template_content=preg_replace('/@Tracking_NO/', $track_no, $template_content);
                $template_content=preg_replace('/@Item_Detail/', $product_desc, $template_content);
                $template_content=preg_replace('/@Special_instruction/', $special_instruction, $template_content);
                $template_content=preg_replace('/@Reference_No/', $ref_no, $template_content);
                $template_content=preg_replace('/@Order_id/', $product_id, $template_content);
                $template_content=preg_replace('/@No_of_pieces/', $quantity, $template_content);
                $template_content=preg_replace('/@Weight/', $weight, $template_content);
                $template_content=preg_replace('/@COD_amount/', $collection_amount, $template_content);
                $template_content=preg_replace('/@Order_Status/', $status, $template_content);
                $template_content=preg_replace('/@Received_By/', $received_by, $template_content);
                $template_content=preg_replace('/@Tracking_History/', $status, $template_content);
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
            $customerstatuslang=getKeyWordCustomer($order_data['customer_id'],$order_data['status']);
              $prev_array =explode(',', $send_to);
              $prev_array =explode(',', $send_to);
              if (in_array(1, $prev_array)) 
              {
                  $data['email'] = $_SESSION['users_email'];
                
                  $insert_qry="INSERT INTO `email_detail`(`contact_email`,`message`,`created_on`,`status`,`template_id`,`company_id`,`subject`,`order_status`) VALUES ('". $_SESSION['users_email']."','".$template_content."','".$date."','1','".$template_id."','". $_SESSION['users_id']."','Admin','".$status."') ";

                  $query=mysqli_query($con,$insert_qry);
                  $message['subject'] = $status;
                  $message['body'] = "<b>Hello ".$_SESSION['users_name']." </b>";
                  $message['body'] .= '<p>'.$template_content.'</p>';
                  sendEmail_template($data, $message);
              } 
              if (in_array(2, $prev_array)) 
              {
                  $data['email'] = $semail;
                  
                  $insert_qry="INSERT INTO `email_detail`(`contact_email`,`message`,`created_on`,`status`,`template_id`,`company_id`,`subject`,`order_status`) VALUES ('".$semail."','".$template_content."','".$date."','1','".$template_id."','".$customer_id."','Shipper','".$customerstatuslang."') ";
                  $query=mysqli_query($con,$insert_qry);
                  $message['subject'] = getKeyWord($order_data['status']);
                  $message['body'] = "<b>Hello ".$order_data['sname']." </b>";
                  $message['body'] .= '<p>'.$template_content.'</p>';
                  sendEmail_template($data, $message);
              }
              if(in_array(3, $prev_array)) 
              {
                $data['email'] = $remail;
                $insert_qry="INSERT INTO `email_detail`(`contact_email`,`message`,`created_on`,`status`,`template_id`,`subject`,`order_status`) VALUES ('".$order_data['semail']."','".$template_content."','".$date."','1','".$template_id."','Consignee','".$status."') ";
                $query=mysqli_query($con,$insert_qry);
                $message['subject'] = getKeyWord($order_data['status']);
                $message['body'] = "<b>Hello ".$order_data['rname']." </b>";
                $message['body'] .= '<p>'.$template_content.'</p>';
                sendEmail_template($data, $message);
              }
            }
      }
   }
 ?>