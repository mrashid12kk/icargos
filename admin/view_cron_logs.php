
<!DOCTYPE html>
<html>
<head>
  <title>View Cron Job Logs</title>
  <style type="text/css">
  table{
    text-align: center; width:80%; border: 2px solid black;
  }
  th {
     text-align: center;   border: 2px solid black; font-weight: bold;
  }
  tr, td {
     text-align: center;   border: 2px solid black;
  }

  </style>
</head>
<body style="text-align: center;">

<?php 


require 'includes/conn.php';
require 'includes/setting_helper.php';

/*$str_to_replace = "";

$number = "923334103310";
// $number = "03334103310";

 $firstTwoNumbers = substr($number, 0, 2);
if($firstTwoNumbers == 92){
   $output_str = $str_to_replace.substr($number, 2);
   echo $output_str ; 
}else{
    echo $number;
}*/


// $cron=mysqli_query($con,"delete from cron_job_logs");

/*$a = "Lahore hamara HY";
echo strtolower($a);*/
// $string = '923334103310a';
// // 92333433
// $res = preg_replace("/[2-9]/", "", $string);
// echo $res ; 
if (isset($_GET['cron_activity_id'])) {
     
      $cron_activity_id = $_GET['cron_activity_id'];
    
    
         if (isset($_GET['requested'])) {
  

        $cron_job_logs=mysqli_query($con,"select * from cron_job_logs where cron_activity_id = '$cron_activity_id'");

        }elseif(isset($_GET['matched'])) {
      

        $cron_job_logs=mysqli_query($con,"select * from cron_job_logs where cron_activity_id = '$cron_activity_id' AND status_updated = 1 ");
            
        }elseif(isset($_GET['notmatched'])) {

            // echo $_GET['notmatched'];

        $cron_job_logs=mysqli_query($con,"select * from cron_job_logs where cron_activity_id = '$cron_activity_id' AND status_updated = 0 ");
            // print_r($cron_job_logs);
        }
        

?> 

 <h1>Cron Job Logs</h1>
 <div style="text-align: center;">
  <table align="center" >
    <tr>
    <th>SR#</th>
    <th>Order Track No</th>
    <th>API ID</th>
    <th>API Status</th>
    <th>Site Status</th>
    <th>Status Update</th>
    <th>Created at</th>
  </tr>
<?php
$serial = 1 ; 
 while($rec = mysqli_fetch_array($cron_job_logs))
 {
?>

  
  <tr>
    <td><?php echo $serial ?> </td>
    <td><?php echo $rec['order_id'] ?></td>
    <td><?php echo $rec['api_id'] ?></td>
    <td><?php echo $rec['api_status'] ?></td>
    <td><?php echo $rec['site_status'] ?></td>
    <td><?php echo $rec['status_updated'] ?></td>
    <td><?php echo $rec['created_at'] ?></td>

    <?php $serial++ ;  ?> 
  </tr>
  
<?php

    /* echo "CronJob Log id : ".$rec['id']."<br>";
     echo "Track_no : ".$rec['order_id']."<br>";
     echo "API id : ".$rec['api_id']."<br>";
     echo "API Status : ".$rec['api_status']."<br>";
     echo "Site Status :".$rec['site_status']."<br>";
     echo "Status Updated :".$rec['status_updated']."<br>";
     echo "Cron Activity Log ID :".$rec['cron_activity_id']."<br>";
     echo "Created at :".$rec['created_at']."<br>";
     echo "Updated at :".$rec['updated_at']."<br>";
     echo "..............................<br>";*/
    
 } 
 }


  

  
 ?>

</table>
</div>
 </body>
</html>