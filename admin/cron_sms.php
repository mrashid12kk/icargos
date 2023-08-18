
<?php 
require 'includes/conn.php'; 
include "includes/mobile_gateway_helper.php";
$vars = new Mobile_gateway_helper();
  $total = 0;
  $sent = 0;
  $query = "SELECT * FROM cron_sms WHERE status = 'Pending'";
  $sql = mysqli_query($con,$query);
  while($fetch = mysqli_fetch_assoc($sql)){
    $track_no = isset($fetch['track_no']) ? $fetch['track_no'] :"";
    $type = isset($fetch['type']) ? $fetch['type'] :"";
    $number = isset($fetch['number']) ? $fetch['number'] :"";
    $message = isset($fetch['sms']) ? $fetch['sms'] :"";
    $id = isset($fetch['id']) ? $fetch['id'] :"";
    if(isset($track_no) && !empty($track_no)){
      $res = $vars->send_sms_mobile_gateway($number, $message); 
      if($res){
        $sent++;
        $date = date('Y-m-d H:i:s');
        mysqli_query($con,"UPDATE cron_sms set status = 'Sent', sent_at = '$date' Where id = $id ");
      }
      
    }
    $total++;
  }

  echo "Requested : ".$total."<br>";
  echo "Sent : ".$sent."<br>";
  $notmatched = $total - $sent ;
  echo "Not Sent : ".$notmatched."<br>" ; 
 ?>