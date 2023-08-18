<?php 
function_exists(email_customer_booking){
   function email_customer_booking($id,$orde_id){
        $customer_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM customers WHERE id=".$id));
        $orde_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM orders WHERE id=".$orde_id));
        // $email_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM email_templates WHERE sms_events='Customer Booking' "));
        // $sms_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM email_templates WHERE sms_events='Pickup Request' "));
        // $sms_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM email_templates WHERE sms_events='Admin Booking' "));
        // $sms_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM email_templates WHERE sms_events='Status Update' "));
        $sms_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM email_templates WHERE sms_events='Delivered' "));
        $template_content=$email_data['template_content'];
        $template_content=preg_replace('/@Origin_City/', $orde_data['origin'], $template_content);
        $template_content=preg_replace('/@Sender_Name/', $orde_data['sname'], $template_content);
        $template_content=preg_replace('/@Sender_Phone/', $orde_data['sphone'], $template_content);
        $template_content=preg_replace('/@Sender_Address/', $orde_data['sender_address'], $template_content);
        $template_content=preg_replace('/@Destination_City/', $orde_data['destination'], $template_content);
        $template_content=preg_replace('/@Receiver_Name/', $orde_data['rname'], $template_content);
        $template_content=preg_replace('/@Receiver_Phone/', $orde_data['rphone'], $template_content);
        $template_content=preg_replace('/@Reciover_Email/', $orde_data['remail'], $template_content);
        $template_content=preg_replace('/@Receiver_Address/', $orde_data['receiver_address'], $template_content);
        $template_content=preg_replace('/@Tracking_NO/', $orde_data['track_no'], $template_content);
        $template_content=preg_replace('/@Item_Detail/', $orde_data['product_desc'], $template_content);
        $template_content=preg_replace('/@Special_instruction/', $orde_data['special_instruction'], $template_content);
        $template_content=preg_replace('/@Reference_No/', $orde_data['ref_no'], $template_content);
        $template_content=preg_replace('/@Order_id/', $orde_data['product_id'], $template_content);
        $template_content=preg_replace('/@No_of_pieces/', $orde_data['quantity'], $template_content);
        $template_content=preg_replace('/@Weight/', $orde_data['weight'], $template_content);
        $template_content=preg_replace('/@COD_amount/', $orde_data['collection_amount'], $template_content);
        $template_content=preg_replace('/@Order_Status/', $orde_data['status'], $template_content);
        $template_content=preg_replace('/@Received_By/', $orde_data['received_by'], $template_content);
        $template_content=preg_replace('/@Tracking_History/', $orde_data['status'], $template_content);
        $data['email'] = $email;
        $customer_name = $customer_data['fname'];
        $message['subject'] = 'Order Booked';
        $message['body'] = "<b>Hello ".$customer_name." </b>";
        $message['body'] .= '<p></p>';
        require_once 'admin/includes/functions.php';
        sendEmail($data, $message);
   }
} 
    
 ?>