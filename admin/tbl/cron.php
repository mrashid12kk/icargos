
<?php 
require 'includes/conn.php';
require 'includes/setting_helper.php';
require 'phpmailer/PHPMailerAutoload.php';


$cron=mysqli_query($con,"select * from cron_job_logs");
$cronrows = mysqli_num_rows($cron);
if ($cronrows > 0) {

   
            $cron_activity_max_id=mysqli_query($con,"select MAX(cron_activity_id) AS cron_activity_id from cron_job_logs");
              if (!$cron_activity_max_id) {
                      echo("Error description: " . mysqli_error($con)); 
                  }
                   $rec = mysqli_fetch_object($cron_activity_max_id);

                $cron_activity_max_id = $rec->cron_activity_id;
                $cron_activity_max_id = $cron_activity_max_id + 1 ; 
}else
{
 $cron_activity_max_id = 1 ; 
}

// $ordersUpdate=mysqli_query($con,"UPDATE orders set api_id = 1 where status != 'Delivered' AND status != 'Returned to Shipper' AND length(track_no)  >= 12 AND length(track_no)  <= 20");
// $status_mapper=mysqli_query($con,"select *  from third_party_api_status_mapping");

$apis=mysqli_query($con,"select * from third_party_apis");
if (!$apis) {
    echo("Error description: " . mysqli_error($con)); 
    exit(); 
}
 while($rec = mysqli_fetch_array($apis))
 {

     $api_id = $rec['id'];
     $api_name  = $rec['title'];

$orders=mysqli_query($con,"select id,status,track_no,api_id from orders where status != 'Delivered' AND status != 'Returned to Shipper' AND status != 'Discarded' AND length(track_no)  >= 12 AND length(track_no)  <= 20 AND api_id = '$api_id'");
   if (!$orders) {
    echo("Error description: " . mysqli_error($con));  
    exit();
} 
   $requested_orders_count=mysqli_num_rows($orders);
     // exit();

 while($row = mysqli_fetch_array($orders)){
    //  echo count($row);
    // echo $row['id']."<br>";
    // echo $row['status']."<br>";
    // echo $row['track_no']."<br>";
    // echo $row['api_id']."<br>";
    // echo "---------------------------------------<br>";

     $tracking_number = $row['track_no'];
   
  
// https://sonic.pk/api/shipment/status?tracking_number=101101000392&type=0

$url = 'https://sonic.pk/api/shipment';
 $collection_name = 'status?tracking_number='.$tracking_number.'&type=0';
 // $collection_name = 'status?tracking_number=2232233738742&type=0';
$request_url = $url . '/' . $collection_name;
$curl = curl_init($request_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
  'Host: sonic.pk',
  'Authorization: QXNkalIxNkFqaFYxaFgzNjIwWmZ5VGpEdzhNN2tnaDQ4eW9kdkl6S0p1TVRWSXhRZHZZc1VubTRaMEZa5f3fbd4ee280d',
  'Content-Type: application/json'
]);

$response = curl_exec($curl);
curl_close($curl);
// echo $response . PHP_EOL; 
   $data =  json_decode($response);      
    foreach ( $data as $key => $value) {


      if ($key == 'current_status') {  
                
                
        $status_mapper=mysqli_query($con,"select *  from third_party_api_status_mapping ");
         if (!$status_mapper) {
              echo("Error description: " . mysqli_error($con));  
              exit();
          } 
         while($row1 = mysqli_fetch_array($status_mapper)){
           if ($value == $row1['api_status']) {

                  $orderID = $row['id']; 
                  $new_Status_id = $row1['status_id'];
                  $status_matched_orders[] = $row['id'];

            $our_status=mysqli_query($con,"select sts_id, status from order_status where sts_id = $new_Status_id");
            if (!$our_status) {
              echo("Error description: " . mysqli_error($con));  
              exit();
               }
            $row2 = mysqli_fetch_row($our_status);
              // echo   "order_status_id  : ".$row2[0]."<br>";     
             $updated_status_name = $row2[1];

             $update_orders = mysqli_query($con,"UPDATE orders set status = '$updated_status_name' where id = '$orderID'");
                if (!$update_orders) {
              echo("Error description: " . mysqli_error($con)); 
              exit(); 
               }    
              /*$new_status=mysqli_query($con,"select id, status, track_no from orders where id = $orderID");
              if (!$new_status) {
              echo("Error description: " . mysqli_error($con));  
              exit();
               }

             $row3 = mysqli_fetch_row($new_status); */
              
              // $track_no = $row3['track_no'];
              $track_no = $row['track_no'];

               
               $insert_log=mysqli_query($con,"INSERT into order_logs (order_no,order_status) values ('$track_no','$updated_status_name')");
                if (!$insert_log) {
              echo("Error description: " . mysqli_error($con));  
                exit();
               }

            }

         }     
               // For CronJob_Logs
               $trackno = $row['track_no'];

                // echo "Order : ". $row['track_no']."<br>";
               // echo "API ID : ". $api_id."<br>";
               // echo  "API response status : ". $value."<br>";

               $orderID = $row['id'];
               $old_status=mysqli_query($con,"select id, status, track_no from orders where id = $orderID");
                
               $roww3 = mysqli_fetch_row($old_status);
                
                 // echo "Site_status : ".$roww3[1]."<br>";  

                $site_status = $roww3[1];              
                $status_mapper=mysqli_query($con,"select order_status.status  from third_party_api_status_mapping join order_status ON third_party_api_status_mapping.status_id = order_status.sts_id where third_party_api_status_mapping.api_status = '$value'");
                  
                  $row6 = mysqli_fetch_row($status_mapper);
                    
                   $updated_status_name = $row6[0];

                            if ($site_status == $updated_status_name ) {
                               $updated = 1 ;  
                             // echo "updated : ".$updated."<br>" ;  
                             
                             }else{

                               $updated = 0 ;  
                             // echo "updated : ".$updated."<br>" ; 
                             }

                          $cron_activity_id=mysqli_query($con,"select * from cron_job_logs");

                             while($rec = mysqli_fetch_array($cron_activity_id))
                             {
                                
                                $cron_activity_id = $rec['cron_activity_id'];

                             }
             $insert_cronjob__log=mysqli_query($con,"INSERT into cron_job_logs (order_id,api_id,api_status,site_status,status_updated,cron_activity_id ) values ('$trackno','$api_id','$value','$site_status','$updated','$cron_activity_max_id')");
                    if (!$insert_cronjob__log) {
                       echo "Error:". mysqli_error($con);

                    }

      }
      
    }
}

 $matched = count(array_unique($status_matched_orders));
 echo "Requested : ".$requested_orders_count."<br>";
 echo "Matched : ".$matched."<br>";
     $notmatched = $requested_orders_count - $matched ;
 echo "Not Matched : ".$notmatched."<br>" ;
 echo "API Name : ".$api_name."<br>" ;

  
if ($requested_orders_count > 0) {

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

// $mail->isSMTP();                                      // Set mailer to use SMTP
$mail->isMail();                                      // Set mailer to use SMTP
$mail->Host = 'transco.itvision.pk';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'noreply@transco.itvision.pk';                 // SMTP username
$mail->Password = 'TRDc0SIHT0N%';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('noreply@transco.itvision.pk', 'Transco');
$mail->addAddress('attiqnasir@transco.com.pk', 'Transco Admin');     // Add a recipient
// $mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('noreply@transco.itvision.pk', 'Reply Transco Information');
// $mail->addCC('upwrok@gmail.com');
// $mail->addBCC('bcc@example.com');

// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Cron Job Updates About Order Status';
$mail->Body    = '<table style="width:70%; border:2px solid black">
  <tr style="border:1px solid black; text-align:center;">
    <th style="border:1px solid black; padding:10px 10px 10px 10px;">Requests</th>
    <th style="border:1px solid black; padding:10px 10px 10px 10px;">Matched</th>
    <th style="border:1px solid black; padding:10px 10px 10px 10px;">Not Matched</th>
    <th style="border:1px solid black; padding:10px 10px 10px 10px;">API Name</th>
  </tr>
  <tr style="border:1px solid black; text-align:center;">
    <td style="border:1px solid black; padding:10px 10px 10px 10px;"><a href="https://transco.itvision.pk/admin/view_cron_logs.php?cron_activity_id='.$cron_activity_max_id.'&requested=requested">'.$requested_orders_count.'</a></td>
    <td style="border:1px solid black; padding:10px 10px 10px 10px;"><a href="https://transco.itvision.pk/admin/view_cron_logs.php?cron_activity_id='.$cron_activity_max_id.'&matched=matched">'.$matched.'</td>
    <td style="border:1px solid black; padding:10px 10px 10px 10px;"><a href="https://transco.itvision.pk/admin/view_cron_logs.php?cron_activity_id='.$cron_activity_max_id.'&notmatched=notmatched">'.$notmatched.'</td>
    <td style="border:1px solid black; padding:10px 10px 10px 10px;">'.$api_name.'</td>
  </tr>
</table>';
// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
}
}
 ?>