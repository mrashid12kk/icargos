<?php 
require 'includes/conn.php';
$pick_up_rider_id = isset($_POST['pick_up_rider_id']) ? $_POST['pick_up_rider_id'] :'';
$delivery_rider_id = isset($_POST['delivery_rider_id']) ? $_POST['delivery_rider_id'] :'';
$status_id = isset($_POST['status_id']) ? $_POST['status_id'] :'';
if(isset($pick_up_rider_id))
{
	$up_riderQ = mysqli_query($con,"UPDATE order_status set pickup_rider = $pick_up_rider_id where sts_id = $status_id");
}
if(isset($delivery_rider_id))
{
	$up_riderQ = mysqli_query($con,"UPDATE order_status set delivery_rider = $delivery_rider_id where sts_id = $status_id");
}

 ?>