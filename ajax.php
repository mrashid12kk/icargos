<?php

session_start();

include_once 'includes/conn.php';

if(isset($_POST['rphone_no'] ) ){

	$user_name=mysqli_real_escape_string($con,$_POST['rphone_no']);

	$query=mysqli_query($con,"Select * from orders where rphone='$user_name'");

	$reponse = mysqli_fetch_array($query);

	$rowcount=mysqli_affected_rows($con);

	if($rowcount>0){

		$output['response'] = $reponse;
		$output['status'] 	= 1;

		echo json_encode($output);

		exit();

	}
	else{
		$output['status'] 	= 0;
		$output['response'] = 'No Record Found Against This No';
		echo json_encode($output);

		exit();
	}

}
if(isset($_POST['track_id'])&&$_POST['track_id']=="1"){
	$id = $_POST['id'];
	if(!function_exists('branch')){
		function branch($id=null)
		{
			global $con;
			$id = isset($id) ? $id : 1;
			if($id)
			{
				$query = mysqli_query($con,"SELECT * from branches where id=".$id);
				$resposne = mysqli_fetch_assoc($query);
				return $resposne['name'];
			}
		}
	}
	function encrypt($string) {
		$key="usmannnn";
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}
		// $query = mysqli_query($con,"SELECT track_no,status FROM orders WHERE track_no IN(".$ids.")   ");
	$list = "";
	$query=mysqli_query($con,"select * from orders where id='".$id."'") or die(mysqli_error($con));
	$fetch1=mysqli_fetch_array($query);
	$iddd=encrypt($fetch1['id']."-usUSMAN767###");
	echo "<div class='close_details'>";
	echo "<i class='fa fa-close' id='close_details'></i>";
	echo "</div>";
	echo "<div class='fix_wrapper_h'>";
	echo "  <div class='row main_location fix_location'>";
	echo "<div class='user_name_'>";
	echo    "<h3>".getLange('viewdetail')."</h3>";
	echo "</div>";
	echo  "<div id='fix_top' class='shiping-consignee-bdr'>";
	echo   "<div class='w_10_px '>";
	echo       "<ul>";
	echo         "<li><i class='fa fa-map-marker'></i></li>";
	echo     "</ul>";
	echo  "</div>";
	echo  "<div class='w_90_px track-result'>";
	echo    "<h3>".getLange('orderinformation')."  </h3>";
	echo     "<p><b>".getLange('ordertime').":</b> ".date('H:i:s', strtotime($fetch1['order_time']))."</p>";
	echo     "<p><b>".getLange('orderid').":</b> ".$fetch1['product_id']."</p>";
	echo    "<p><b>".getLange('ref').":</b> ".$fetch1['ref_no']."</p>";
	echo    "<p><b>".getLange('branch').":</b> ".branch($fetch1['branch_id'])."</p>";
	                  //               echo    "<p><b>".getLange('api').":</b>";
	                  //               if($fetch1['api_posted'] == '0'){
							            // echo ''	;
							            // }else{
							            //   echo  $fetch1['api_posted'];
							            // }
							            // echo "</p>";
	echo    "<p><b>".getLange('ordertype').":</b>";
	if($fetch1['booking_type'] == '2'){
		echo 'Cash'	;
	}elseif($fetch1['booking_type'] == '3'){
		echo  'To Pay';
	}else{
		echo 'Invoice';
	}
	echo "</p>";
	                                // echo    "<p><b>".getLange('apitrackingno').":</b> ".$fetch1['api_tracking_no']."</p>";
	echo    "<p><b>".getLange('itemdetail')." :</b> ".$fetch1['product_desc']."</p>";
	echo    "<p><b>".getLange('specialinstruction').":</b> ".$fetch1['special_instruction']."</p>";
	                                //echo    "<p><b>Delivery Fee:</b> ". getConfig('currency')."".$fetch1['price']."</p>";
	                                // echo    "<p><b>".getLange('pickuptime').":</b> ". date('h:i A',strtotime($fetch1['pickup_time']))."</p>";
	echo    "<p><b>".getLange('pickuplocation').":</b> ". $fetch1['Pick_location']."</p>";
	echo    "<p><b>".getLange('ordertype').":</b>";
	if($fetch1['order_type_booking']==1){echo 'API';}
	else if($fetch1['order_type_booking']==2){echo 'Admin';}
	else if($fetch1['order_type_booking']==3){echo 'Bulk Booking';}
	else if($fetch1['order_type_booking']==4){echo 'Customer';}
	echo "</p>";
	if($fetch1['status'] !='cancelled'){
		if ($fetch1['order_booking_type'] == 1) {
			echo "<a target='_blank' title='view invoice' style='display:inline-block' href='invoicehtml_new.php?order_id=" . $fetch1['id'] . "&booking=1'><svg class='view_action_btn' style='    width: 18px;'  viewBox='0 0 45 45' style='enable-background:new 0 0 45 45;' xml:space='preserve'><g><path style='fill:#fff;' d='M42.5,19.408H40V1.843c0-0.69-0.561-1.25-1.25-1.25H6.25C5.56,0.593,5,1.153,5,1.843v17.563H2.5c-1.381,0-2.5,1.119-2.5,2.5v20c0,1.381,1.119,2.5,2.5,2.5h40c1.381,0,2.5-1.119,2.5-2.5v-20C45,20.525,43.881,19.408,42.5,19.408z M32.531,38.094H12.468v-5h20.063V38.094z M37.5,19.408H35c-1.381,0-2.5,1.119-2.5,2.5v5h-20v-5c0-1.381-1.119-2.5-2.5-2.5H7.5V3.093h30V19.408z M32.5,8.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,8.792,32.5,8.792z M32.5,13.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,13.792,32.5,13.792z M32.5,18.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,18.792,32.5,18.792z'/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></a>";
		} else {
			echo "<a target='_blank' title='view invoice' style='display:inline-block' href='".getConfig('print_template')."?order_id=".$fetch1['id']."&booking=1'><svg class='view_action_btn' style='    width: 18px;'  viewBox='0 0 45 45' style='enable-background:new 0 0 45 45;' xml:space='preserve'><g><path style='fill:#fff;' d='M42.5,19.408H40V1.843c0-0.69-0.561-1.25-1.25-1.25H6.25C5.56,0.593,5,1.153,5,1.843v17.563H2.5c-1.381,0-2.5,1.119-2.5,2.5v20c0,1.381,1.119,2.5,2.5,2.5h40c1.381,0,2.5-1.119,2.5-2.5v-20C45,20.525,43.881,19.408,42.5,19.408z M32.531,38.094H12.468v-5h20.063V38.094z M37.5,19.408H35c-1.381,0-2.5,1.119-2.5,2.5v5h-20v-5c0-1.381-1.119-2.5-2.5-2.5H7.5V3.093h30V19.408z M32.5,8.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,8.792,32.5,8.792z M32.5,13.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,13.792,32.5,13.792z M32.5,18.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,18.792,32.5,18.792z'/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></a>";
		}
	}
	if ($fetch1['order_booking_type'] == 1 && $fetch1['status'] !='cancelled') {
		echo "<a target='_blank' title='view invoice' style='display:inline-block' href='airway_bill.php?order_id=" . $fetch1['id'] . "&booking=1'><svg class='view_action_btn' viewBox='0 0 24 24'><path d='M7 3h9a3 3 0 0 1 3 3v13a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3zm0 1a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-3v6.7l-3-2.1l-3 2.1V4zm5 0H8v4.78l2-1.401l2 1.4V4zM9 19v-2H7v-1h2v-2h1v2h2v1h-2v2H9z' fill='#ffff'/></svg></a>";
	}
	if($fetch1['status'] == 'New Booked' ){
		$url_edit=isset($fetch1['order_booking_type']) && $fetch1['order_booking_type'] == 1 ? "edituserbooking_new.php?id=".$fetch1['id']."" : "edituserbooking.php?id=".$fetch1['id']."";
		echo    "<a target='_blank' style='display:inline-block' title='edit' href='".$url_edit."'  > <svg class='view_action_btn' viewBox='0 0 24 24'><path d='M19.706 8.042l-2.332 2.332l-3.75-3.75l2.332-2.332a.999.999 0 0 1 1.414 0l2.336 2.336a.999.999 0 0 1 0 1.414zM2.999 17.248L13.064 7.184l3.75 3.75L6.749 20.998H3v-3.75zM16.621 5.044l-1.54 1.539l2.337 2.335l1.538-1.539l-2.335-2.335zm-1.264 5.935l-2.335-2.336L4 17.664V20h2.336l9.021-9.021z' fill='#fff'/></svg></a>";
	}
	if($fetch1['status'] == 'New Booked' ){
		echo  "<a style='display:inline-block' title='cancel order'  href='cancel_order.php?cancel_id=".$iddd."' onclick='return confirm(".'"Are you sure you want to cancel?"'."); return false' ><svg class='view_action_btn' viewBox='0 0 24 24'><path d='M9 6.5c0 .786-.26 1.512-.697 2.096L20 20.293V21h-.707L11.5 13.207l-3.197 3.197a3.5 3.5 0 1 1-.707-.707l3.197-3.197l-3.197-3.197A3.5 3.5 0 1 1 9 6.5zm-1 0a2.5 2.5 0 1 0-5 0a2.5 2.5 0 0 0 5 0zM19.293 4H20v.707l-7.146 7.147l-.708-.707L19.293 4zM5.5 16a2.5 2.5 0 1 0 0 5a2.5 2.5 0 0 0 0-5z' fill='#fff'/></svg></a>";
	}

	if($fetch1['status'] != 'cancelled' ){
		echo  "<a style='display:inline-block'  target='_blank' title='track order' href='".BASE_URL."track-details.php?track_code=".$fetch1['track_no'] ."' > <svg class='view_action_btn' viewBox='0 0 24 24'><path d='M5.5 14a2.5 2.5 0 0 1 2.45 2H15V6H4a2 2 0 0 0-2 2v8h1.05a2.5 2.5 0 0 1 2.45-2zm0 5a2.5 2.5 0 0 1-2.45-2H1V8a3 3 0 0 1 3-3h11a1 1 0 0 1 1 1v2h3l3 3.981V17h-2.05a2.5 2.5 0 0 1-4.9 0h-7.1a2.5 2.5 0 0 1-2.45 2zm0-4a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm12-1a2.5 2.5 0 0 1 2.45 2H21v-3.684L20.762 12H16v2.5a2.49 2.49 0 0 1 1.5-.5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zM16 9v2h4.009L18.5 9H16z' fill='#fff'/></svg> </a>";
	}
	echo "<div class='carge_location_map'>";
	echo "<img src='".BASE_URL."admin/img/map-img.png'>";
	echo "</div>";
	echo   "</p>";
	echo   "</div>";
	echo "</div>";
	echo  "</div>";
	echo "</div>";

}

if(isset($_POST['order_log_detail']) && $_POST['order_log_detail']=="1"){
	$track = $_POST['track'];
	$id = $_SESSION['customers'];
		// $query = mysqli_query($con,"SELECT track_no,status FROM orders WHERE track_no IN(".$ids.")   ");

	$query=mysqli_query($con,"SELECT * from order_logs where order_no='".$track."'") or die(mysqli_error($con));
	echo "<div class='row main_location fix_location'>";
	echo "<div class='user_name_'>";
	echo "<h3>".getLange('livetrackig')."</h3>";
	$orders_q = mysqli_query($con,"SELECT * from orders where track_no='" . $track . "'") or die(mysqli_error($con));
	$order_row = mysqli_fetch_assoc($orders_q);
	if (isset($order_row['vendor_id']) && !empty($order_row['vendor_id'])) {
		$vendor_url=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM vendors WHERE id=".$order_row['vendor_id']));
		if (isset($vendor_url['vendor_url']) && !empty($vendor_url['vendor_url'])) {
			echo "<a href='".$vendor_url['vendor_url'].$order_row['vendor_track_no']."' target='_blank'><h4 style='margin: 0 0 16px;color: #ffffff; font-size: 15px;'> ".$order_row['vendor_track_no']."</h4></a>";
		}else{
			echo "<h4 style='margin: 0 0 16px;color: #ffffff; font-size: 15px;'>".$order_row['vendor_track_no']."</h4>";
		}
	}
	echo "</div>";
	echo "<div id='fix_top'>";
	echo "<div class='w_10_px'>";
	echo "<ul>";
	echo "<li><i class='fa fa-calendar'></i></li>";
	echo "</ul>";
	echo "</div>";
	echo "<div class='w_90_px status_box'>";
	echo "<h3><span>".getLange('date')."</span> <i>-</i> <b> ".getLange('status')."</b></h3>";
	while ($fetch1=mysqli_fetch_array($query)) {
		echo "<p><span>".date('Y-m-d', strtotime($fetch1['created_on'])).' '.date('H:i:s', strtotime($fetch1['created_on']))."</span><b>".getKeyWordCustomer($id,$fetch1['order_status'])."</b></p>";
	}
	echo "</div>";
	echo "</div>";
	echo "</div>";

}

if(isset($_POST['is_service_type']))
{
	$service_type = $_POST['service_type'];
	$customer_id = $_POST['customer_id'];
	$customer_query = mysqli_query($con,"SELECT * FROM customers WHERE id=".$customer_id." ");
	$customer_data = mysqli_fetch_array($customer_query);
	$customer_city = $customer_data['city'];

	$zone_id_q = mysqli_query($con,"SELECT zone_id FROM customer_pricing WHERE customer_id='".$customer_id."' AND `service_type` ='".$service_type."' ");

	$i = 1;
	$whr = "";
	while($zone_id_r = mysqli_fetch_array($zone_id_q))
	{
		$zone_id = $zone_id_r['zone_id'];
		if ($i == 1)
		{
			$whr .= " ( `zone` = '".$zone_id."'  ";
		}else {
			$whr .= " or `zone` = '".$zone_id."'  ";
		}
		$i++;
	}
	$whr .= " ) ";

	// $zone_id_r = mysqli_fetch_array($zone_id_q);
	// $zone_id = $zone_id_r['zone_id'];
	// $origin_zone_q = mysqli_query($con,"SELECT DISTINCT origin FROM zone_cities WHERE zone = '".$zone_id."' ORDER BY origin ");
	$origin_zone_q = mysqli_query($con,"SELECT DISTINCT origin FROM zone_cities WHERE  ".$whr." ORDER BY origin ");

	// echo "SELECT DISTINCT origin FROM zone_cities WHERE  '".$whr."' ORDER BY origin ";

	$origin_cities_list = "";

	while($origin_r = mysqli_fetch_array($origin_zone_q)){
		$selected = '';
		$city = $origin_r['origin'];
		if ($city == $customer_city) {
			$selected = 'selected';
		}
		$origin_cities_list .= "<option ".$selected." value='".$city."' >".$city."</option>";
	}

	// $destination_zone_q = mysqli_query($con,"SELECT DISTINCT destination FROM zone_cities WHERE zone = '".$zone_id."' ORDER BY destination ");
	$destination_zone_q = mysqli_query($con,"SELECT DISTINCT destination FROM zone_cities WHERE  ".$whr."  ORDER BY destination ");
	$destination_cities_list = "";


	while($destination_r = mysqli_fetch_array($destination_zone_q)){
		$city = $destination_r['destination'];
		if($city == 'Other'){
			$city_q = mysqli_query($con,"SELECT DISTINCT city_name FROM cities WHERE city_name !='Other'  ");
			while($city_q_r = mysqli_fetch_array($city_q))
			{
				$city = $city_q_r['city_name'];
				$destination_cities_list .= "<option   value='".$city."'     >".$city."</option>";
			}
		}else{
			$destination_cities_list .= "<option   value='".$city."' >".$city."</option>";
		}

	}
	$result_arr = array();
	$result_arr['origin_cities'] = $origin_cities_list;
	$result_arr['destination_cities'] = $destination_cities_list;
	echo json_encode($result_arr); exit();
}
if(isset($_POST['getoriginData'])&& !empty($_POST['getoriginData'])){
	$query=mysqli_query($con,"SELECT id FROM `cities` WHERE city_name = '".$_POST['origin']."'");

	$city_response = mysqli_fetch_assoc($query);
	$city_id = $city_response['id'];

	if(isset($city_id) && !empty($city_id))
	{
		$area_q = mysqli_query($con, "SELECT * FROM areas WHERE city_name=".$city_id);
		echo '<option value="">'.getLange("select").'</option>';
		while ($row = mysqli_fetch_assoc($area_q)) {
			echo '<option value="'.$row["id"].'">'.$row["area_name"].'</option>';
		}
	}

}
if(isset($_POST['action'])&&$_POST['action']=='email'){

	$email=mysqli_real_escape_string($con,$_POST['email']);

	$query=mysqli_query($con,"Select * from customers where email='$email'");

	$rowcount=mysqli_affected_rows($con);

	if($rowcount>0){

		echo "<ul class='list-unstyled'><li>Email Already exist.</li></ul>";

	}

	else{

		echo "";

	}

}

if(isset($_POST['cusaction'])&&$_POST['cusaction']=='cusaction'){

	$cusemail=mysqli_real_escape_string($con,$_POST['cusemail']);

	$id='';
	if (isset($_POST['user_id']) && $_POST['user_id']!='') {
		$id="AND id!=".$_POST['user_id'];
	}

	$query=mysqli_query($con,"SELECT * from customer_user WHERE email='".$cusemail."' $id ");

	$rowcount=mysqli_affected_rows($con);

	if($rowcount>0){

		echo "<ul class='list-unstyled'><li>Email Already exist.</li></ul>";

	}

	else{

		$query=mysqli_query($con,"SELECT * from customers WHERE email='".$cusemail."'");

		$rowcount=mysqli_affected_rows($con);

		if($rowcount>0){

			echo "<ul class='list-unstyled'><li>Email Already exist.</li></ul>";

		}

		else{

			echo "";

		}


	}

}

if(isset($_POST['action'])&&$_POST['action']=='pickup_dat'){

	$pickup_date=mysqli_real_escape_string($con,$_POST['pickup_date']);
	$query=mysqli_query($con,"SELECT * FROM `orders` where pickup_date='$pickup_date' ") or die(mysqli_error($con));
	$query2=mysqli_query($con,"SELECT * FROM `settings` ") or die(mysqli_error($con));
	$fetch=mysqli_fetch_array($query2);
	if(mysqli_num_rows($query)>=$fetch['per_day_packages']){
		echo '<div class="help-block with-errors"><ul class="list-unstyled"><li>Driver not availbale.Please Choose Another Date. </li></ul></div>';
	}
	else{
			// echo '<div class="help-block with-errors"><ul class="list-unstyled"><li>Driver not availbale.Please Choose Another Date. </li></ul></div>';
	}




}

if(isset($_POST['action'])&&$_POST['action']=='fbshare'){

	$id=mysqli_real_escape_string($con,$_POST['id']);

	$query=mysqli_query($con,"select * from orders where id=$id");

	$fetch=mysqli_fetch_array($query);

	if($fetch['discount']=='on'){

		mysqli_query($con,"update orders set price=price-5,discount='off' where id=$id") or die(mysqli_error($con));

		$share[]=true;

	}

	else{

		$share[]=false;

	}

	echo json_encode($share);





} else if(isset($_POST['action']) && $_POST['action'] == 'calculatePrice') {
	$weight = (int)$_POST['weight'];
	$pickup_price = '';
	$drop_price = '';
	if(isset($_POST['city'])) {
		$city = $_POST['city'];
		$query = mysqli_query($con, "SELECT * FROM cities WHERE city_name LIKE '$city'") or die(mysqli_error($con));
		$city = mysqli_fetch_object($query);
		if(isset($city->prices)) {
			$prices = json_decode($city->prices);
			if(isset($prices->$weight)) {
				$pickup_price = (float)$prices->$weight;
			}
		}
	}
	if(isset($_POST['dropoff_location']) && $_POST['dropoff_location'] != '') {
		$city = $_POST['dropoff_location'];
		$query = mysqli_query($con, "SELECT * FROM cities WHERE city_name LIKE '$city'") or die(mysqli_error($con));
		$city = mysqli_fetch_object($query);
		if(isset($city->prices)) {
			$prices = json_decode($city->prices);
			if(isset($prices->$weight)) {
				$drop_price = (float)$prices->$weight;
			}
		}
	}
	if($pickup_price != '') {
		if($drop_price != '') {
			echo ($drop_price > $pickup_price) ? $drop_price : $pickup_price;
		} else {
			echo $pickup_price;
		}
	} else {
		$weight = mysqli_query($con, "SELECT * FROM weights WHERE id = $weight");
		$weight = mysqli_fetch_object($weight);
		echo (isset($weight->price)) ? $weight->price : '0';
	}
	exit();
}