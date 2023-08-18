<?php
  session_start();
  include_once "../../includes/conn.php";
  if(isset($_GET['email_id']))
    {
        $email_id=$_GET['email_id'];
        $date=date('Y-m-d H:i:s');
        $email_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM email_detail WHERE id=".$email_id));

        $template_content=$email_data['message'];
        $send_to=$email_data['subject'];
        $order_status=$email_data['order_status'];
        $contact_email=$email_data['contact_email'];
        $template_id=$email_data['template_id'];
       
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
        };
        if ($send_to=='Admin') {
           $data['email'] = $contact_email;
             $insert_qry="INSERT INTO `email_detail`(`contact_email`,`message`,`created_on`,`status`,`template_id`,`subject`) VALUES ('".$contact_email."','".$template_content."','".$date."','1','".$template_id."','Admin') ";
             $query=mysqli_query($con,$insert_qry);
            $message['subject'] = $order_status;
            $message['body'] = "<b>Hello ".$_SESSION['users_name']." </b>";
            $message['body'] .= '<p>'.$template_content.'</p>';
              sendEmail_template($data, $message);
        } 
        if ($send_to=='Shipper') {
             $data['email'] = $contact_email;
             $insert_qry="INSERT INTO `email_detail`(`contact_email`,`message`,`created_on`,`status`,`template_id`,`subject`) VALUES ('".$contact_email."','".$template_content."','".$date."','1','".$template_id."','Shipper') ";
             $query=mysqli_query($con,$insert_qry);
            $message['subject'] = $order_status;
            $message['body'] = "<b>Hello ".$order_data['sname']." </b>";
            $message['body'] .= '<p>'.$template_content.'</p>';
              sendEmail_template($data, $message);
        }
        if ($send_to=='Consignee') {
         $data['email'] = $contact_email;
             $insert_qry="INSERT INTO `email_detail`(`contact_email`,`message`,`created_on`,`status`,`template_id`,`subject`) VALUES ('".$contact_email."','".$template_content."','".$date."','1','".$template_id."','Consignee') ";
             $query=mysqli_query($con,$insert_qry);
            $message['subject'] = $order_status;
            $message['body'] = "<b>Hello ".$order_data['rname']." </b>";
            $message['body'] .= '<p>'.$template_content.'</p>';
              sendEmail_template($data, $message);
        }
        $_SESSION['email_msg']='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Email Is Resend Successfully.</div>';
         header('Location: ../../sent_list_email.php');
   }
 ?>