<?php

	session_start();

	include_once 'includes/conn.php';
	include_once 'includes/role_helper.php';


if(isset($_POST['order_startus_cont'])&&$_POST['order_startus_cont']=="6"){
		
$tracking_no = $_POST['tracking_no'];

$customer_name = $_POST['customer_name'];

$customer_phone = $_POST['customer_phone'];

$customer_email = $_POST['customer_email'];

$date_from = $_POST['date_from'];

$date_to = $_POST['date_to'];

$active_customer = $_POST['active_customer'];

$track_no = $_POST['track_no'];

$pickup_rider = $_POST['pickup_rider'];

$delivery_rider = $_POST['delivery_rider'];

$order_status = $_POST['order_status'];

$order_city = $_POST['order_city'];

$origin_city = $_POST['origin_city'];



$searchQuery = " ";

if($tracking_no != ''){

   $searchQuery .= " and (track_no='".$tracking_no."') ";

}

if($customer_name != ''){

   $searchQuery .= " and (sname='".$customer_name."') ";

}

if($customer_phone != ''){

   $searchQuery .= " and (sphone='".$customer_phone."') ";

}

if($customer_email != ''){

   $searchQuery .= " and (semail='".$customer_email."') ";

}

if($date_from != '' && $date_to !=''){

    $from = date('Y-m-d',strtotime($_POST['date_from']));

    $to = date('Y-m-d',strtotime($_POST['date_to']));

   $searchQuery .= " and DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' ";

}

if($active_customer != ''){

   $searchQuery .= " and (customer_id='".$active_customer."') ";

}

if($pickup_rider != ''){

   $searchQuery .= " and (pickup_rider='".$pickup_rider."') ";

}

if($delivery_rider != ''){

   $searchQuery .= " and (delivery_rider='".$delivery_rider."') ";

}

if($order_status != ''){

   $searchQuery .= " and (status='".$order_status."') ";

}

if($order_city != ''){

   $searchQuery .= " and (destination='".$order_city."') ";

}

if($origin_city != ''){

   $searchQuery .= " and (origin='".$origin_city."') ";

}
if($track_no != ''){
   $searchQuery .= " and (track_no='".$track_no."') ";
}


				$query=mysqli_query($con,"SELECT count(id) as allids from orders WHERE 1 ".$searchQuery." AND status!='Delivery' AND status!='Returned'" ) or die(mysqli_error($con));
				$row=mysqli_fetch_array($query);
						$origin   = $row['origin'];
						$destination = $row['destination'];
						$order_type   = $row['order_type'];
						$customer_id   = $row['customer_id'];

					 	$list .= "<input type='text' name='origin' class='origin' value='".$origin."' readonly><input type='text' name='destination'order_type class='destination' value='".$destination."' readonly><input type='text' class='order_type' name='order_type' value=".$order_type." readonly><input type='text'class='customer_id' name='customer_id' value=".$customer_id." readonly>";
		echo $list;
		exit();
	}




?>
