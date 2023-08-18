<?php 
   require 'includes/conn.php';

   if($_SERVER['REQUEST_METHOD']=="POST"){
       if(isset($_POST['track_no']) && !empty($_POST['track_no'])){
           // update
           $track_no=$_POST['track_no'];
           $address=isset($_POST['location']) ? $_POST['location'] : "";
           $lat=isset($_POST['latitude']) ? $_POST['latitude'] : "";
           $lng=isset($_POST['longitude']) ? $_POST['longitude'] : "";
           $update_query="UPDATE `orders` SET `google_address`='$address',`receiver_address`='$address',`map_latitude`='$lat',`map_longitude`='$lng' WHERE `track_no`='$track_no'";
           if (mysqli_query($con, $update_query)) {
               echo "updated";
             } else {
               echo "Error updating record: " . mysqli_error($conn);
             }
       }
   }
   
   ?>
