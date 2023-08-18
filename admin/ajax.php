	<?php
	session_start();
	include_once 'includes/conn.php';
	include_once 'includes/role_helper.php';
	include_once 'includes/API/get_api_trackings.php';
// 	ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
	if (isset($_POST['rphone_no'])) {
		$user_name = mysqli_real_escape_string($con, $_POST['rphone_no']);
		$query = mysqli_query($con, "Select * from orders where rphone='$user_name'");
		$reponse = mysqli_fetch_array($query);
		$rowcount = mysqli_affected_rows($con);
		if ($rowcount > 0) {
			$output['response'] = $reponse;
			$output['status'] 	= 1;
			echo json_encode($output);
			exit();
		} else {
			$output['status'] 	= 0;
			$output['response'] = 'No Record Found Against This No';
			echo json_encode($output);
			exit();
		}
	}
	if (isset($_POST['unAssign']) && $_POST['unAssign'] == 1) {
		$return_array = array();
		$ids = $_POST['order_ids'];
		$message = '';
		$type = $_POST['type'];
		if ($type == 'Delivery') {
			$assignment_type = 2;
			$assignQuery = 'delivery_assignment_no=NULL';
		} elseif ($type == 'Pickup') {
			$assignment_type = 1;
			$assignQuery = 'assignment_no=NULL';
		}

		foreach ($ids as $key => $track_no) {
			mysqli_query($con, "DELETE from order_logs where order_no = '" . $track_no . "' ORDER BY id DESC LIMIT 1");
			$last_log = mysqli_fetch_assoc(mysqli_query($con, "SELECT * from order_logs where order_no = '" . $track_no . "' ORDER BY id DESC LIMIT 1"));
			$lastTracking = isset($last_log['order_status']) ? $last_log['order_status'] : '';
			if ($lastTracking && !empty($lastTracking)) {
				mysqli_query($con, "UPDATE orders set status='" . $lastTracking . "' , $assignQuery WHERE track_no='" . $track_no . "'");
				mysqli_query($con, "DELETE from assignment_record where order_num = '" . $track_no . "' AND assignment_type = $assignment_type ORDER BY id DESC LIMIT 1");
			}
			$message .= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Success!</strong> Order ' . $track_no . ' Unassigned</div>';
		}
		$return_array['message'] = $message;
		$_SESSION['return_msg'] = $message;
		echo json_encode($return_array);
	}
	if (isset($_POST['template_id']) && !empty($_POST['template_id'])) {
		$id = $_POST['template_id'];
		$query = mysqli_query($con, "SELECT * FROM sms_templates where id='$id'");
		$reponse = mysqli_fetch_assoc($query);
		$template_content = $reponse['template_content'];
		$rowcount = mysqli_affected_rows($con);
		if ($rowcount > 0) {
			echo $template_content;
			exit();
		}
	}
	if (isset($_POST['payment_id']) && !empty($_POST['payment_id'])) {
		$id = $_POST['payment_id'];
		$query = mysqli_query($con, "SELECT customer_ledger_payments.*,customers.fname as customer,customers.bname as company_name,customers.client_code FROM customer_ledger_payments LEFT JOIN  customers ON customers.id=customer_ledger_payments.customer_id WHERE customer_ledger_payments.id=" . $id);
		$reponse = mysqli_fetch_assoc($query);
		$last_id_q = mysqli_query($con, "SELECT max(id) as id from customer_ledger_payments_detail");
		$lastIdRes = mysqli_fetch_assoc($last_id_q);
		$lastId = isset($lastIdRes['id']) ? $lastIdRes['id'] : 0;
		$nextId = $lastId + 1;
		$referenere = 'PI-' . $nextId;
		$reponse['referenere'] = $referenere;
		echo json_encode($reponse);
		exit();
	}
	if (isset($_POST['customer_settle_period']) && !empty($_POST['customer_settle_period'])) {
		$id = $_POST['customer_id_settle'];
		$query = mysqli_query($con, "SELECT payment_within FROM customers where id=" . $id);
		$reponse = mysqli_fetch_assoc($query);
		$payment_within = isset($reponse['payment_within']) && $reponse['payment_within'] != '' ? $reponse['payment_within'] : '30';
		$data['from'] = date('Y-m-d', strtotime('today - ' . $payment_within . ' days'));
		$data['payment_within'] = isset($reponse['payment_within']) && $reponse['payment_within'] != '' ? "<div class='alert alert-success'>This Customer Has " . $reponse['payment_within'] . " Days Settlement Period " : "<div class='alert alert-success'>This Customer Has No Settlement Period By defaulf 30 Days is selected ";
		$query = mysqli_query($con, "SELECT payment_date FROM customer_ledger_payments where customer_id=" . $id . " ORDER BY id DESC LIMIT 1");
		$reponse = mysqli_fetch_assoc($query);
		$data['payment_within'] .= isset($reponse['payment_date']) && $reponse['payment_date'] != '' ? "AND Last Payment Date is " . date('d-m-Y', strtotime($reponse['payment_date'])) : '';
		$data['payment_within'] .= '</div>';
		echo json_encode($data);
		exit();
	}
	if (isset($_POST['get_city']) && $_POST['get_city'] == '1') {
		$country_id = $_POST['country_id'];
		$search_city = '';
		if (isset($_POST['state_id']) && $_POST['state_id'] != '') {
			$state_id = $_POST['state_id'];
			$search_city = "AND state_id='$state_id'";
		}
		$query = mysqli_query($con, "SELECT * FROM cities where country_id='$country_id' $search_city");
		$rowcount = mysqli_affected_rows($con);
		$state_select = '';
		$state_select .= "<select type='text' class='js-example-basic-single' required name='city_id'>";
		$state_select .= "<option value='' disabled selected>Select City</option>";
		if ($rowcount > 0) {
			while ($row = mysqli_fetch_array($query)) {
				$state_select .= "<option value='" . $row['id'] . "'>" . $row['city_name'] . "</option>";
			}
		}
		$state_select .= "</select>";
		if (isset($state_select) && $state_select != '') {
			echo $state_select;
			exit();
		}
	}
	if (isset($_POST['bussiness_city']) && $_POST['bussiness_city'] == '1') {
		$search_city = '';
		if (isset($_POST['state_id_bissiness']) && $_POST['state_id_bissiness'] != '') {
			$state_id = $_POST['state_id_bissiness'];
			$search_city = "state_id='$state_id'";
		}
		$query = mysqli_query($con, "SELECT * FROM cities where $search_city");
		$rowcount = mysqli_affected_rows($con);
		$state_select = '';
		$state_select .= "<select type='text' class='js-example-basic-single cities' name='city'>";
		$state_select .= "<option value=''  >Select City</option>";
		if ($rowcount > 0) {
			while ($row = mysqli_fetch_array($query)) {
				$state_select .= "<option data-stn_city='" . $row['stn_code'] . "'>" . $row['city_name'] . "</option>";
			}
		}
		$state_select .= "</select>";
		if (isset($state_select) && $state_select != '') {
			echo $state_select;
			exit();
		}
	}
	if (isset($_POST['get_country']) && $_POST['get_country'] == '1') {
		$id = $_POST['country_id'];
		$query = mysqli_query($con, "SELECT * FROM state where country_id='$id'");
		$rowcount = mysqli_affected_rows($con);
		$state_select = '';
		$state_select .= "<select type='text' class='js-example-basic-single state' required name='state_id'>";
		$state_select .= "<option value='' disabled selected>Select State</option>";
		if ($rowcount > 0) {
			while ($row = mysqli_fetch_array($query)) {
				$state_select .= "<option value='" . $row['id'] . "'>" . $row['state_name'] . "</option>";
			}
		}
		$state_select .= "</select>";
		if (isset($state_select) && $state_select != '') {
			echo $state_select;
			exit();
		}
	}
	if (isset($_POST['check_desti']) && !empty($_POST['check_desti'])) {
		// $zone_id_q = mysqli_fetch_assoc(mysqli_query($con,"SELECT zone_id from customer_pricing where service_type = ".$_POST['order_type_val']));
		// $zone_id = isset($zone_id_q['zone_id']) ? $zone_id_q['zone_id'] :'';
		$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : 1;
		$zone_id_q = mysqli_query($con, "SELECT zone_id FROM customer_pricing WHERE customer_id='" . $customer_id . "' AND `service_type` ='" . $_POST['order_type_val'] . "' ");
		$i = 1;
		$whr = "";
		while ($zone_id_r = mysqli_fetch_array($zone_id_q)) {
			$zone_id = $zone_id_r['zone_id'];
			if ($i == 1) {
				$whr .= " ( `zone` = '" . $zone_id . "'  ";
			} else {
				$whr .= " or `zone` = '" . $zone_id . "'  ";
			}
			$i++;
		}
		$whr .= " ) ";
		$desti_q = mysqli_query($con, "SELECT DISTINCT destination FROM zone_cities WHERE  " . $whr . "  ORDER BY destination ");;
		while ($destinations = mysqli_fetch_assoc($desti_q)) {
			$city = $destinations['destination'];
			echo '<option value="' . $city . '">' . $city . '</option>';
		}
	}
	if (isset($_POST['tracking_status']) && !empty($_POST['tracking_status'])  and !isset($_POST['rider_for'])) {
		$ids = rtrim($_POST['order_ids'], ',');
		// $query = mysqli_query($con,"SELECT track_no,status FROM orders WHERE track_no IN(".$ids.")   ");
		$branch_query = "";
		if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
			$branch_query = " AND ( origin IN ( $all_allowed_origins ) OR current_branch = " . $_SESSION['branch_id'] . " OR booking_branch = " . $_SESSION['branch_id'] . ") ";
		} else {
			$branch_query = " AND ( origin IN ( $all_allowed_origins ) OR current_branch = 1 OR current_branch IS NULL OR booking_branch=1 )";
		}
		$list = "";
		$order_id_data = explode(',', $ids);
		foreach ($order_id_data as $order_id) {
			$query = mysqli_query($con, "SELECT track_no,status,weight,dimensional_weight FROM orders WHERE track_no ='" . $order_id . "' $branch_query");
			if ($query->num_rows != 0) {
				while ($row = mysqli_fetch_array($query)) {
					if (!empty($row['status'])) {
						$status   = isset($row['status']) ? $row['status'] : '';
						$track_no = isset($row['track_no']) ? $row['track_no'] : '0';
						$weight   = isset($row['weight']) ? $row['weight'] : '0';
						$dimensional_weight   = isset($row['dimensional_weight']) ? $row['dimensional_weight'] : '0';
						$list .= "<li class=" . $track_no . ">" . $track_no . ' current status: <span class="badge badge-primary data-status" >' . ucfirst($status) . "</span> <span class='badge badge-primary weight_html'> Weight " . $weight . "</span><button class='badge badge-primary circle edit_weight' data-toggle='modal' data-target='#exampleModal' data-id=" . $weight . " data-dimension=" . $dimensional_weight . "  data-trackno=" . $track_no . " data-status=" . ucfirst($status) . "> Edit</button>  </li>";
					}
				}
			} else {
				$list .= "<span style='color:red;' >" . $track_no . " " . getLange('no_such_order_found') . ". </span></span>";
			}
		}
		echo $list;
		exit();
	}
	if (isset($_POST['getTariffData']) && !empty($_POST['getTariffData'])) {
		$value = $_POST['value'];
		$show_header = 0;
		$origin = '';
		$destination = '';
		$heading = '';
		if ($value == 3) {
			$heading = 'Zone Mapping';
			$origincitydata = mysqli_query($con, "SELECT * from zone_type  ");
			$destcitydata = mysqli_query($con, "SELECT * from zone_type ");
			while ($row = mysqli_fetch_array($origincitydata)) {
				$origin .= '<option value="' . $row['zone_name'] . '">' . $row["zone_name"] . '</option>';
			}
			while ($row = mysqli_fetch_array($destcitydata)) {
				$destination .= '<option value="' . $row['zone_name'] . '">' . $row["zone_name"] . '</option>';
			}
			$show_header = 1;
		} elseif ($value == 6) {
			$heading = 'City Mapping';
			$origincitydata = mysqli_query($con, "SELECT * from cities Where city_name IS NOT NULL order by city_name ");
			$destcitydata = mysqli_query($con, "SELECT * from cities Where city_name IS NOT NULL order by city_name");
			while ($row = mysqli_fetch_array($origincitydata)) {
				$origin .= '<option value="' . $row['city_name'] . '">' . $row["city_name"] . '</option>';
			}
			while ($row = mysqli_fetch_array($destcitydata)) {
				$destination .= '<option value="' . $row['city_name'] . '">' . $row["city_name"] . '</option>';
			}
			$show_header = 1;
		} elseif ($value == 8) {
			$heading = 'State Mapping';
			$origincitydata = mysqli_query($con, "SELECT * from state ");
			$destcitydata = mysqli_query($con, "SELECT * from state");
			while ($row = mysqli_fetch_array($origincitydata)) {
				$origin .= '<option value="' . $row['state_name'] . '">' . $row["state_name"] . '</option>';
			}
			while ($row = mysqli_fetch_array($destcitydata)) {
				$destination .= '<option value="' . $row['state_name'] . '">' . $row["state_name"] . '</option>';
			}
			$show_header = 1;
			$show_header = 1;
		} elseif ($value == 11) {
			$heading = 'Country Mapping';
			$origincitydata = mysqli_query($con, "SELECT * from country ");
			$destcitydata = mysqli_query($con, "SELECT * from country");
			while ($row = mysqli_fetch_array($origincitydata)) {
				$origin .= '<option value="' . $row['country_name'] . '">' . $row["country_name"] . '</option>';
			}
			while ($row = mysqli_fetch_array($destcitydata)) {
				$destination .= '<option value="' . $row['country_name'] . '">' . $row["country_name"] . '</option>';
			}
			$show_header = 1;
		} else {
			$show_header = 0;
		}
		$return_array = array();
		$return_array['origin'] = $origin;
		$return_array['destination'] = $destination;
		$return_array['show_header'] = $show_header;
		$return_array['heading'] = $heading;
		echo json_encode($return_array);
		exit;
	}
	if (isset($_POST['getoriginData']) && !empty($_POST['getoriginData'])) {
		$query = mysqli_query($con, "SELECT id FROM `cities` WHERE city_name = '" . $_POST['origin'] . "'");
		$city_response = mysqli_fetch_assoc($query);
		$city_id = $city_response['id'];
		if (isset($city_id) && !empty($city_id)) {
			$area_q = mysqli_query($con, "SELECT * FROM areas WHERE city_name=" . $city_id);
			echo '<option value="">' . getLange("select") . '</option>';
			while ($row = mysqli_fetch_assoc($area_q)) {
				echo '<option value="' . $row["id"] . '">' . $row["area_name"] . '</option>';
			}
		}
	}
	if (isset($_POST['getCountCity']) && !empty($_POST['getCountCity'])) {
		$country_id = isset($_POST['country']) ? $_POST['country'] :'';
		$country_res = mysqli_fetch_assoc(mysqli_query($con,"SELECT id from country where country_name='$country_id'"));
		$countryid = isset($country_res['id']) ? $country_res['id'] : '';
		$city_query=mysqli_query($con,"SELECT * FROM cities where country_id=$countryid ORDER BY city_name ASC");
		$country_res = '<option value="">Select</option>';
		while($row = mysqli_fetch_assoc($city_query)) {
			$country_res .=  '<option value="' . $row["city_name"] . '">' . $row["city_name"] . '</option>';
		}
		echo json_encode(array("htmlRes"=>$country_res));
	}
	if (isset($_POST['country']) && !empty($_POST['country'])) {
		$query = mysqli_query($con, "SELECT * FROM `state` WHERE country_id = '" . $_POST['country'] . "'");
		$countrows = mysqli_num_rows($query);
		if ($countrows > 0) {
			echo '<option value="">Select State Province</option>';
			while ($row = mysqli_fetch_assoc($query)) {
				echo '<option value="' . $row["id"] . '">' . $row["state_name"] . '</option>';
			}
		}
	}
	if (isset($_POST['orderprocessing']) && $_POST['orderprocessing'] == "6") {
		$track_no = $_POST['track_no'];
		// $query = mysqli_query($con,"SELECT track_no,status FROM orders WHERE track_no IN(".$ids.")   ");
		$list = "";
		$query = mysqli_query($con, "SELECT * FROM orders WHERE track_no='" . $track_no . "'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$origin   = $row['origin'];
		$destination = $row['destination'];
		$order_type   = $row['order_type'];
		$customer_id   = $row['customer_id'];
		$order_id   = $row['id'];
		$list .= "<input type='text' name='origin' class='origin' value='" . $origin . "' readonly><input type='text' name='destination'order_type class='destination' value='" . $destination . "' readonly><input type='text' class='order_type' name='order_type' value=" . $order_type . " readonly><input type='text'class='customer_id' name='customer_id' value=" . $customer_id . " readonly>";
		echo $list;
		exit();
	}
	if (isset($_POST['getApiOption']) && $_POST['getApiOption'] == 1) {
		$service_name = $_POST['service_name'];
		// $query = mysqli_query($con,"SELECT track_no,status FROM orders WHERE track_no IN(".$ids.")   ");
		$list = "";
		$status=0;
		$query = mysqli_query($con, "SELECT * FROM third_party_apis WHERE title='" . $service_name . "'") or die(mysqli_error($con));
		$row = mysqli_fetch_array($query);
		$services   = $row['services'];
		$services_array = explode(",",$services);
		foreach ($services_array as $key => $service_name) {
			if(isset($service_name) && !empty($service_name)){
				$status=1;
				$list .='<option value="'.$service_name.'">'.$service_name.'</option>';
			}
			
		}
		
		echo json_encode(array('list'=>$list,'status'=>$status));
		exit();
	}
	if (isset($_POST['track_id']) && $_POST['track_id'] == "1") {
		$id = $_POST['id'];
		if (!function_exists('branch')) {
			function branch($id = null)
			{
				global $con;
				$id = isset($id) ? $id : 1;
				if ($id) {
					$query = mysqli_query($con, "SELECT * from branches where id=" . $id);
					$resposne = mysqli_fetch_assoc($query);
					return $resposne['name'];
				}
			}
		}
		function encrypt($string)
		{
			$key = "usmannnn";
			$result = '';
			for ($i = 0; $i < strlen($string); $i++) {
				$char = substr($string, $i, 1);
				$keychar = substr($key, ($i % strlen($key)) - 1, 1);
				$char = chr(ord($char) + ord($keychar));
				$result .= $char;
			}
			return base64_encode($result);
		}
		// $query = mysqli_query($con,"SELECT track_no,status FROM orders WHERE track_no IN(".$ids.")   ");
		$list = "";
		$query = mysqli_query($con, "select * from orders where id='" . $id . "'") or die(mysqli_error($con));
		$fetch1 = mysqli_fetch_array($query);
		$iddd = encrypt($fetch1['id'] . "-usUSMAN767###");
		echo "<div class='close_details'>";
		echo "<i class='fa fa-close' id='close_details'></i>";
		echo "</div>";
		echo "<div class='fix_wrapper_h' id='fix_wrapper_h'>";
		echo "  <div class='row main_location fix_location'>";
		echo "<div class='user_name_'>";
		echo    "<h3>" . getLange('viewdetail') . "</h3>";
		echo "</div>";
		echo  "<div id='fix_top' class='shiping-consignee-bdr'>";
		echo   "<div class='w_10_px '>";
		echo       "<ul>";
		echo         "<li><i class='fa fa-map-marker'></i></li>";
		echo     "</ul>";
		echo  "</div>";
		echo  "<div class='w_90_px track-result'>";
		echo    "<h3>" . getLange('orderinformation') . "  </h3>";
		echo     "<p><b>" . getLange('ordertime') . ":</b> " . date('H:i:s', strtotime($fetch1['order_time'])) . "</p>";
		echo     "<p><b>" . getLange('orderid') . ":</b> " . $fetch1['product_id'] . "</p>";
		echo    "<p><b>" . getLange('ref') . ":</b> " . $fetch1['ref_no'] . "</p>";
		echo    "<p><b>" . getLange('branch') . ":</b> " . branch($fetch1['branch_id']) . "</p>";
		echo    "<p><b>" . getLange('api') . ":</b>";
		if ($fetch1['api_posted'] == '0') {
			echo '';
		} else {
			echo  $fetch1['api_posted'];
		}
		echo "</p>";
		echo    "<p><b>" . getLange('ordertype') . ":</b>";
		if ($fetch1['booking_type'] == '2') {
			echo getLange('cash');
		} elseif ($fetch1['booking_type'] == '3') {
			echo  getLange('topay');
		} else {
			echo getLange('invoice');
		}
		echo "</p>";
		echo    "<p><b>" . getLange('apitrackingno') . ":</b> " . $fetch1['api_tracking_no'] . "</p>";
		echo    "<p><b>" . getLange('user') . ":</b> " . getusernameById($fetch1['user_id']) . "</p>";
		echo    "<p><b>" . getLange('itemdetail') . " :</b> " . $fetch1['product_desc'] . "</p>";
		echo    "<p><b>" . getLange('specialinstruction') . ":</b> " . $fetch1['special_instruction'] . "</p>";
		//echo    "<p><b>Delivery Fee:</b> ". getConfig('currency')."".$fetch1['price']."</p>";
		//   echo    "<p><b>".getLange('pickuptime').":</b> ". date('h:i A',strtotime($fetch1['pickup_time']))."</p>";
		echo    "<p><b>" . getLange('pickuplocation') . ":</b> " . $fetch1['Pick_location'] . "</p>";
		echo    "<p><b>" . getLange('ordertype') . ":</b>";
		if ($fetch1['order_type_booking'] == 1) {
			echo 'API';
		} else if ($fetch1['order_type_booking'] == 2) {
			echo getLange('admin');
		} else if ($fetch1['order_type_booking'] == 3) {
			echo getLange('bulkbooking');
		} else if ($fetch1['order_type_booking'] == 4) {
			echo getLange('customer');
		}
		echo "</p>";
		echo    "<p><b>" . getLange('action') . ":</b> <a title='view order' style='display:inline-block' target='_blank' href='order.php?id=" . $fetch1['id'] . "' ><svg class='view_action_btn' viewBox='0 0 24 24'><path d='M16 12a3 3 0 0 1-3-3V5H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h13a2 2 0 0 0 2-2v-6h-4zm-2-3a2 2 0 0 0 2 2h3.586L14 5.414V9zM5 4h9l7 7v7a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3zm0 4h6v1H5V8zm0 4h6v1H5v-1zm0 4h13v1H5v-1z' fill='#fff'/></svg></a>";
		if ($fetch1['order_booking_type'] == 1) {
			echo "<a target='_blank' title='view invoice' style='display:inline-block' href='../invoicehtml_new.php?order_id=" . $fetch1['id'] . "&booking=1'><svg class='view_action_btn' style='    width: 18px;'  viewBox='0 0 45 45' style='enable-background:new 0 0 45 45;' xml:space='preserve'><g><path style='fill:#fff;' d='M42.5,19.408H40V1.843c0-0.69-0.561-1.25-1.25-1.25H6.25C5.56,0.593,5,1.153,5,1.843v17.563H2.5c-1.381,0-2.5,1.119-2.5,2.5v20c0,1.381,1.119,2.5,2.5,2.5h40c1.381,0,2.5-1.119,2.5-2.5v-20C45,20.525,43.881,19.408,42.5,19.408z M32.531,38.094H12.468v-5h20.063V38.094z M37.5,19.408H35c-1.381,0-2.5,1.119-2.5,2.5v5h-20v-5c0-1.381-1.119-2.5-2.5-2.5H7.5V3.093h30V19.408z M32.5,8.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,8.792,32.5,8.792z M32.5,13.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,13.792,32.5,13.792z M32.5,18.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,18.792,32.5,18.792z'/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></a>";
		} else {
			echo "<a target='_blank' title='view invoice' style='display:inline-block' href='../" . getConfig('print_template') . "?order_id=" . $fetch1['id'] . "&booking=1'><svg class='view_action_btn' style='    width: 18px;'  viewBox='0 0 45 45' style='enable-background:new 0 0 45 45;' xml:space='preserve'><g><path style='fill:#fff;' d='M42.5,19.408H40V1.843c0-0.69-0.561-1.25-1.25-1.25H6.25C5.56,0.593,5,1.153,5,1.843v17.563H2.5c-1.381,0-2.5,1.119-2.5,2.5v20c0,1.381,1.119,2.5,2.5,2.5h40c1.381,0,2.5-1.119,2.5-2.5v-20C45,20.525,43.881,19.408,42.5,19.408z M32.531,38.094H12.468v-5h20.063V38.094z M37.5,19.408H35c-1.381,0-2.5,1.119-2.5,2.5v5h-20v-5c0-1.381-1.119-2.5-2.5-2.5H7.5V3.093h30V19.408z M32.5,8.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,8.792,32.5,8.792z M32.5,13.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,13.792,32.5,13.792z M32.5,18.792h-20c-0.69,0-1.25-0.56-1.25-1.25s0.56-1.25,1.25-1.25h20c0.689,0,1.25,0.56,1.25,1.25S33.189,18.792,32.5,18.792z'/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></a>";
		}
		if ($fetch1['order_booking_type'] == 1) {
			echo "<a target='_blank' title='view invoice' style='display:inline-block' href='../airway_bill.php?order_id=" . $fetch1['id'] . "&booking=1'><svg class='view_action_btn' viewBox='0 0 24 24'><path d='M7 3h9a3 3 0 0 1 3 3v13a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3zm0 1a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-3v6.7l-3-2.1l-3 2.1V4zm5 0H8v4.78l2-1.401l2 1.4V4zM9 19v-2H7v-1h2v-2h1v2h2v1h-2v2H9z' fill='#ffff'/></svg></a>";
		}
		if ($fetch1['status'] != 'cancelled') {
			$url=isset($fetch1['order_booking_type']) && $fetch1['order_booking_type'] == 1 ? "editbookingform_new.php?id=" . $fetch1['id'] . "'" : "editbookingform.php?id=" . $fetch1['id'] . "'";
			echo    "<a target='_blank' style='display:inline-block' title='edit' href='".$url."' > <svg class='view_action_btn' viewBox='0 0 24 24'><path d='M19.706 8.042l-2.332 2.332l-3.75-3.75l2.332-2.332a.999.999 0 0 1 1.414 0l2.336 2.336a.999.999 0 0 1 0 1.414zM2.999 17.248L13.064 7.184l3.75 3.75L6.749 20.998H3v-3.75zM16.621 5.044l-1.54 1.539l2.337 2.335l1.538-1.539l-2.335-2.335zm-1.264 5.935l-2.335-2.336L4 17.664V20h2.336l9.021-9.021z' fill='#fff'/></svg></a>";
		}
		if ($fetch1['status'] == 'New Booked') {
			echo  "<a style='display:inline-block' title='cancel order'  href='cancel_order.php?cancel_id=" . $iddd . "' onclick='return confirm(" . '"Are you sure you want to cancel?"' . "); return false' ><svg class='view_action_btn' viewBox='0 0 24 24'><path d='M9 6.5c0 .786-.26 1.512-.697 2.096L20 20.293V21h-.707L11.5 13.207l-3.197 3.197a3.5 3.5 0 1 1-.707-.707l3.197-3.197l-3.197-3.197A3.5 3.5 0 1 1 9 6.5zm-1 0a2.5 2.5 0 1 0-5 0a2.5 2.5 0 0 0 5 0zM19.293 4H20v.707l-7.146 7.147l-.708-.707L19.293 4zM5.5 16a2.5 2.5 0 1 0 0 5a2.5 2.5 0 0 0 0-5z' fill='#fff'/></svg></a>";
		}
		if ($fetch1['status'] != 'Delivered' &&  $fetch1['status'] != 'Returned to Shipper') {
			$url=isset($fetch1['order_booking_type']) && $fetch1['order_booking_type'] == 1 ? "cancel_order.php?delete_id_new=" . $iddd . "'" : "cancel_order.php?delete_id=" . $iddd . "'";
			echo  "<a style='display:inline-block' title='delete order'  href='".$url."'  onclick='return confirm(" . '"Are you sure you want to Delete?"' . "); return false'><svg class='view_action_btn' viewBox='0 0 24 24'><path d='M18 19a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3V7H4V4h4.5l1-1h4l1 1H19v3h-1v12zM6 7v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V7H6zm12-1V5h-4l-1-1h-3L9 5H5v1h13zM8 9h1v10H8V9zm6 0h1v10h-1V9z' fill='#fff'/></svg></a>";
		}
		if ($fetch1['status'] != 'cancelled') {
			echo  "<a style='display:inline-block'  target='_blank' title='track order' href='" . BASE_URL . "track-details.php?track_code=" . $fetch1['track_no'] . "' > <svg class='view_action_btn' viewBox='0 0 24 24'><path d='M5.5 14a2.5 2.5 0 0 1 2.45 2H15V6H4a2 2 0 0 0-2 2v8h1.05a2.5 2.5 0 0 1 2.45-2zm0 5a2.5 2.5 0 0 1-2.45-2H1V8a3 3 0 0 1 3-3h11a1 1 0 0 1 1 1v2h3l3 3.981V17h-2.05a2.5 2.5 0 0 1-4.9 0h-7.1a2.5 2.5 0 0 1-2.45 2zm0-4a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm12-1a2.5 2.5 0 0 1 2.45 2H21v-3.684L20.762 12H16v2.5a2.49 2.49 0 0 1 1.5-.5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zM16 9v2h4.009L18.5 9H16z' fill='#fff'/></svg> </a>";
		}
		if (!empty($fetch1['google_address'])) {
			echo  "<a  target='_blank' style='display:inline-block' title='location' href='" . $fetch1['google_address'] . "' hidden> <svg class='view_action_btn' viewBox='0 0 24 24'><path d='M11.5 7a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm-4.7 4.357l4.7 7.73l4.7-7.73a5.5 5.5 0 1 0-9.4 0zm10.254.52L11.5 22.012l-5.554-9.135a6.5 6.5 0 1 1 11.11 0h-.002z' fill='#fff'/></svg></a>";
		}

		echo "<div class='carge_location_map'>";
		echo "<img src='" . BASE_URL . "admin/img/map-img.png'>";
		echo "</div>";
		echo   "</p>";
		if($fetch1['order_booking_type'] == 1){
		echo     "<p><a target='_blank' href='../admin/print.php?order_id=".$_POST['id']."' style=''><button class='button btn-danger' style='border: none;border-top-left-radius:15px;border-bottom-right-radius:15px;width:100%;'>View Prints</button></a></p>";
			}
		echo   "</div>";
		echo "</div>";
		echo  "</div>";
		echo "</div>";
	}
	if (isset($_POST['order_log_detail']) && $_POST['order_log_detail'] == "1") {
		$track = $_POST['track'];
		// $query = mysqli_query($con,"SELECT track_no,status FROM orders WHERE track_no IN(".$ids.")   ");
		$query = mysqli_query($con, "SELECT * from order_logs where order_no='" . $track . "'") or die(mysqli_error($con));
		// var_dump($query);
		// die('ok');
		echo "<div class='row main_location fix_location'>";
		echo "<div class='user_name_'>";
		echo "<h3>" . getLange('livetrackig') . "</h3>";
		$orders_q = mysqli_query($con,"SELECT * from orders where track_no='" . $track . "'") or die(mysqli_error($con));
		$order_row = mysqli_fetch_assoc($orders_q);
		if (isset($order_row['vendor_id']) && !empty($order_row['vendor_id'])) {
			$vendor_url=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM vendors WHERE id=".$order_row['vendor_id']));
			if (isset($vendor_url['vendor_url']) && !empty($vendor_url['vendor_url'])) {
				echo "<a href='".$vendor_url['vendor_url'].$order_row['vendor_track_no']."' target='_blank'><h4 style='margin: 0 0 16px;color: #ffffff; font-size: 15px;'> <img src='assets/images/vendor/".$vendor_url['logo']."' style='width: 48px;margin: 0 11px 0 0; height: 19px; object-fit: contain;'>".$order_row['vendor_track_no']."</h4></a>";
			}else{
				echo "<h4 style='margin: 0 0 16px;color: #ffffff; font-size: 15px;'><img src='assets/images/vendor/".$vendor_url['logo']."'>".$order_row['vendor_track_no']."</h4>";
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
		echo "<h3><span>" . getLange('date') . "</span> <i>-</i> <b> " . getLange('status') . "</b></h3>";
		$order = mysqli_query($con, "SELECT * FROM orders WHERE  track_no ='" . $track . "' ");
		while ($row = mysqli_fetch_array($order)) {
			$api_tracking_no = isset($row['api_tracking_no']) ? $row['api_tracking_no'] : '';
			$api_id = isset($row['api_id']) ? $row['api_id'] : '';
			$api_posted = isset($row['api_posted']) ? $row['api_posted'] : '';
		}
		$tracking_number = isset($api_tracking_no) && $api_tracking_no != '' ? $api_tracking_no : 0;
		if(isset($api_id) && !empty($api_id) && $api_id!=''){
			// echo "here";
			// die;
			$fetch_api_records = get_api_trackings($api_posted,$tracking_number);
			// foreach ($fetch_api_records as $key => $record) {
			// 	echo "<p class='status_by'><span>" . date('Y-m-d', strtotime($record['created_on'])) . ' ' . date('H:i:s', strtotime($record['created_on'])) . "</span>  " . $record['order_status'] . "</b></p>";
			// }
		}else{
			while ($fetch1 = mysqli_fetch_array($query)) {
				echo "<p class='status_by'><span>" . date('Y-m-d', strtotime($fetch1['created_on'])) . ' ' . date('H:i:s', strtotime($fetch1['created_on'])) . "</span>  <br> " . getUserNameById($fetch1['user_id']) . " <b> " . $fetch1['order_status'] . "</b></p>";
			}
		}
		
		
		echo "</div>";
		echo "</div>";
		echo "</div>";
	}
	if (isset($_POST['weight']) && $_POST['weight'] != '') {
		$msg = '';
		$list = '';
		$response = array();
		$track_no = $_POST['track_no'];
		$weight = $_POST['weight'];
		$delivery_charges = $_POST['delivery_charges'];
		$pft_amount = $_POST['pft_amount'];
		$inc_amount = $_POST['inc_amount'];
		$dimensional_weight = $_POST['dimensional_weight'];
		$status = $_POST['status'];
		$query = mysqli_query($con, "UPDATE orders SET weight='" . $weight . "', dimensional_weight= '" . $dimensional_weight . "', price='" . $delivery_charges . "',pft_amount='" . $pft_amount . "',inc_amount='" . $inc_amount . "' WHERE track_no='" . $track_no . "'") or die(mysqli_error($con));
		if (mysqli_affected_rows($con) > 0) {
			$msg = "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>X</button><strong>" . getLange('Well_done') . "!</strong> " . getLange('weight_of_this_track_no') . " '" . $track_no . "' " . getLange('is_updated_successfully') . " </div>";
			$list    .= "<li>" . $track_no . ' ' . getLange('current_status') . ' : <span class="badge badge-primary">' . ucfirst($status) . "</span> <span class='badge badge-primary'> " . getLange('weight') . " " . $weight . "</span><button class='badge badge-primary circle edit_weight' data-toggle='modal' data-target='#exampleModal' data-id=" . $weight . " data-trackno=" . $track_no . "> " . getLange('edit') . "</button>  </li>";
			$response['list'] = $list;
			$response['msg'] = $msg;
			$response['weight'] = $weight;
			echo json_encode($response);
		} else {
			$msg = "<div class='alert alert-danger'><button type='button' class='close' data-dismiss='alert'>X</button><strong>!</strong> " . getLange('weight_of_this_track_no') . " '" . $track_no . "' " . getLange('is_not_updated_successfully') . " </div>";
			$response['msg'] = $msg;
			echo json_encode($response);
		}
		// echo $msg;
	}
	if (isset($_POST['comentid'])) {
		$cmntid = $_POST['comentid'];
		$update_query = mysqli_query($con, "UPDATE order_comments set is_read=1 where id = $cmntid");
		$result = mysqli_affected_rows($con);
		if ($result > 0) {
			echo json_encode("true");
		}
		exit();
	}
	if (isset($_POST['tracking_status']) && !empty($_POST['tracking_status']) and isset($_POST['rider_for'])) {
		$rider_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : $_POST['users_id'];
		$ids = rtrim($_POST['order_ids'], ',');
		// $query = mysqli_query($con,"SELECT track_no,status FROM orders WHERE track_no IN(".$ids.")   ");
		$list  = "";
		$error = 0;
		$order_id_data = explode(',', $ids);
		foreach ($order_id_data as $order_id) {
			$check_for = '';
			if (isset($_POST['rider_for']) and $_POST['rider_for'] == 'delivery') {
				$check_for = ' AND  assignment_type = 2 ';
			} else if (isset($_POST['rider_for']) and $_POST['rider_for'] == 'pickup') {
				$check_for = ' AND  assignment_type = 1 ';
			}
			$order_pickup = mysqli_fetch_array(mysqli_query($con, "SELECT assignment_record.*,order_status.status as status_name FROM assignment_record LEFT JOIN order_status ON assignment_record.assignment_status=order_status.status WHERE  assignment_record.order_num ='" . $order_id . "' $check_for   "));
			if (!empty($order_pickup)) {
				$status_submitted_id  = $order_pickup['status_submitted'];
				$mark_done_enable     = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM order_status WHERE sts_id ='" . $order_pickup['status_submitted'] . "' AND marked_done = '1' "));
				// if ($order_pickup['rider_status_done_no'] == 1 and !empty($mark_done_enable))
				if ($order_pickup['rider_status_done_no'] == 1) {
					$list .= "<li style='color:red;' >" . $order_id . " " . getLange('has_already_been_updated') . ". </span></li>";
					$error = 1;
				} else {
					$query = mysqli_query($con, "SELECT track_no,status,pickup_rider,delivery_rider,weight FROM orders WHERE track_no ='" . $order_id . "'   ");
					if ($query->num_rows != 0) {
						while ($row = mysqli_fetch_array($query)) {
							if (!empty($row['status'])) {
								$track_no = $row['track_no'];
								$status   = $row['status'];
								if (isset($_POST['rider_for']) and $_POST['rider_for'] == 'delivery' and $row['delivery_rider'] == $rider_id) {
									$weight   = $row['weight'];
									$list    .= "<li>" . $track_no . ' current status: <span class="badge badge-primary">' . ucfirst($status) . " </span>  <span class='badge badge-primary'> " . getLange('weight') . " " . $weight . "</span> </li>";
								} else  if (isset($_POST['rider_for']) and $_POST['rider_for'] == 'pickup' and $row['pickup_rider'] == $rider_id) {
									$list .= "<li>" . $track_no . ' ' . getLange('current_status') . ': <span class="badge badge-primary">' . ucfirst($status) . "</span></li>";
								} else {
									$list .= "<li style='color:red;' >" . $track_no . " " . getLange('is_not_assigned_to_you') . " </span></li>";
									$error = 1;
								}
							}
						}
					}
				}
			} else {
				$list .= "<li style='color:red;' >" . $order_id . " " . getLange('no_such_order_found') . ". </span></li>";
				$error = 1;
			}
		}
		$response['output'] = $list;
		$response['error'] = $error;
		echo json_encode($response);
		exit();
	}
	if (isset($_POST['action']) && $_POST['action'] == 'user_name') {
		$user_name = mysqli_real_escape_string($con, $_POST['user_name']);
		$query = mysqli_query($con, "Select * from users where user_name='$user_name'");
		$rowcount = mysqli_affected_rows($con);
		if ($rowcount > 0) {
			echo "<ul class='list-unstyled'><li>" . getLange('username_already_exist') . ".</li></ul>";
		} else {
			echo "";
		}
		// echo json_encode($msg);
	}
	if (isset($_POST['action']) && $_POST['action'] == 'email') {
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$query = mysqli_query($con, "Select * from users where email='$email'");
		$rowcount = mysqli_affected_rows($con);
		if ($rowcount > 0) {
			$msg = "<ul class='list-unstyled'><li>" . getLange('email_already_exist') . ".</li></ul>";
		} else {
			$msg = "";
		}
		echo json_encode($msg);
	}
	if (isset($_POST['action']) && $_POST['action'] == 'assign') {
		$query = mysqli_query($con, "Select * from users where type='driver' and status='complete'");
		$rowcount = mysqli_affected_rows($con);
		if ($rowcount > 0) {
			while ($fetch = mysqli_fetch_array($query)) {
				echo "<option value='" . $fetch['id'] . "'>" . $fetch['Name'] . "</option>";
			}
		} else {
			echo "<option>" . getLange('drivers_are_not_available_yet') . ".</option>";
		}
	}
	if (isset($_POST['mode_id']) && !empty($_POST['mode_id'])) {
		$query = mysqli_query($con, "SELECT * FROM `transport_company` WHERE mode_id =" . $_POST['mode_id']);
		$rowcount = mysqli_affected_rows($con);
		if ($rowcount > 0) {
			echo "<option value='' selected=''>Select " . getLange('transportcompany') . "</option>";
			while ($fetch = mysqli_fetch_array($query)) {
				echo "<option value='" . $fetch['id'] . "'>" . $fetch['name'] . "</option>";
			}
		} else {
			echo "<option value='0'>" . getLange('no_company_available_for_this_mode') . ".</option>";
		}
	}
	if (isset($_POST['receiving_branch']) && !empty($_POST['receiving_branch'])) {
		$query = mysqli_query($con, "SELECT * FROM `users` WHERE type != 'driver' AND branch_id =  " . $_POST['receiving_branch']);
		$rowcount = mysqli_affected_rows($con);
		if ($rowcount > 0) {
			while ($fetch = mysqli_fetch_array($query)) {
				echo "<option value='" . $fetch['id'] . "'>" . $fetch['Name'] . "</option>";
			}
		} else {
			echo "<option value=''>" . getLange('no_record_found') . ".</option>";
		}
	}
	if (isset($_POST['enter_cn']) && !empty($_POST['enter_cn'])) {
		$user_role_id = $_POST['user_role_id'];
		$users_id = $_POST['users_id'];
		$franchisen_role = '';
		if (isset($user_role_id) && $user_role_id == getfranchisemanagerId()) {
			$franchisen_role = "AND user_id=" . $users_id;
		}
		$query = mysqli_query($con, "SELECT * FROM `orders` WHERE track_no = '" . $_POST['enter_cn'] . "' $franchisen_role");
		$sr_no = isset($_POST['length']) ? $_POST['length'] : 0;
		$check_manifest = mysqli_query($con, "SELECT track_no, manifest_no FROM manifest_detail WHERE is_demanifest=0 AND track_no='" . $_POST['enter_cn'] . "'");
		// echo "SELECT track_no, manifest_no FROM manifest_details WHERE is_demanifest=0 AND track_no='".$_POST['enter_cn']."'";
		// die();
		$result_manifest = mysqli_fetch_assoc($check_manifest);
		$existing_manifest = $result_manifest['track_no'];
		$return_array = array();
		if ($_POST['enter_cn'] == $existing_manifest) {
			$return_array['error'] = 1;
			$return_array['msg'] = " " . getLange('order') . " #" . $existing_manifest . " " . getLange('is_already_in_manifest') . " #" . $result_manifest['manifest_no'] . ". " . getLange('please_demanifest_first') . ".";
			echo json_encode($return_array);
			exit;
		} else {
			$return_array['table'] = '';
			if (mysqli_num_rows($query) > 0) {
				while ($fetch = mysqli_fetch_array($query)) {
					$service_type = '';
					if (isset($fetch['order_type']) && $fetch['order_type'] != '') {
						$service_type_q = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM services WHERE id=" . $fetch['order_type']));
						$service_type = $service_type_q['service_type'];
					}
					$bussiness_name = '';
					if (isset($fetch['customer_id']) && $fetch['customer_id'] != '') {
						$customer_q = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM customers WHERE id=" . $fetch['customer_id']));
						$bussiness_name = $customer_q['bname'];
					}
					$return_array['table'] .= "<tr><td>" . $fetch['track_no'] . " <input type='hidden' name='all_cn_no[]' class='all_cn_no' value='" . $fetch['track_no'] . "' /></td><td>" . $service_type . "</td><td>" . $fetch['sname'] . "</td><td>" . $fetch['origin'] . "</td><td>" . $fetch['scity'] . "</td><td>" . $fetch['rname'] . "</td><td>" . $fetch['destination'] . "</td><td>" . $fetch['rcity'] . "</td><td>" . $fetch['quantity'] . "<input type='hidden' class='hidden_qunatity_value' value=" . $fetch['quantity'] . " /></td><td>" . $fetch['weight'] . "<input type='hidden' class='hidden_weight' value=" . $fetch['weight'] . " /></td><td><a data-wt='" . $fetch["weight"] . "' data-qt='" . $fetch["quantity"] . "'  style='cursor:pointer' title='Trash' class='delete_row'><i class='fa fa-trash ''></i></a></td></tr>";
				}
				echo json_encode($return_array);
				exit;
			} else {
				$return_array['error'] = 1;
				$return_array['msg'] = getLange('no_record_found');
				echo json_encode($return_array);
				exit;
			}
		}
	}
	if (isset($_POST['pick_cn']) && !empty($_POST['pick_cn'])) {
		// var_dump($_POST);
		// die();
		if (isset($_POST['sending_branch']) && $_POST['sending_branch'] != 1) {
			$current_branch = " AND current_branch = " . $_POST['sending_branch'];
		} else {
			$current_branch = '';
		}
		$allowed_statuses = $_POST['allowed_statuses'];
		$exclude_destination = $_POST['exclude_destination'];

		$origin = '';
		if (isset($_POST['origin']) && $_POST['origin'] != '') {
			$origin = " AND origin =  '" . $_POST['origin'] . "'";
		}
		$destination = '';
		if (isset($_POST['destination']) && $_POST['destination'] != '') {
			$destination = " AND destination =  '" . $_POST['destination'] . "'";
		}
		$scity = '' ;
		if (isset($_POST['city1']) && $_POST['city1'] != '') {
			$scity = " AND scity =  '" . $_POST['city1'] . "'";
		}
		$rcity = '' ;
		if (isset($_POST['city2']) && $_POST['city2'] != '') {
			$rcity = " AND rcity =  '" . $_POST['city2'] . "'";
		}
		$where = '';
		foreach ($allowed_statuses as $key => $value) {
			$where .= " status = '" . $value . "' OR";
		}
		$where_des = '';
		foreach ($exclude_destination as $key => $ex_Dest) {
			$where_des .= "  destination != '" . $ex_Dest . "' AND";
		}
		$where = rtrim($where, "OR");
		$where_des = rtrim($where_des, "AND");
		if (isset($where_des) && $where_des != '') {
			$where_des = " AND  " . $where_des . " ";
		}
		$sql_query = "SELECT * FROM `orders` WHERE ( $where )  $where_des  $origin $destination $scity $rcity $current_branch";
		$query = mysqli_query($con, $sql_query);
		// echo  $sql_query;
		// die('ok');
		$rowcount = mysqli_num_rows($query);
		// echo "ROw count is" . $rowcount;
		// die;
		if ($rowcount > 0) {
			$total_pieces = '';
			$total_weight = '';
			$sr_no = 1;
			echo '<table class="table_box">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>' . getLange('cn') . '#</th>';
			echo '<th>' . getLange('service') . '</th>';
			// echo '<th>Bag#</th>';
			echo '<th>' . getLange('consigner') . '</th>';
			echo '<th>' . getLange('origin') . '</th>';
			echo '<th>' . getLange('city') . '</th>';
			echo '<th>' . getLange('consignee') . '</th>';
			echo '<th>' . getLange('destination') . '</th>';
			echo '<th>' . getLange('city') . '</th>';
			echo '<th>' . getLange('pcs') . '</th>';
			echo '<th>' . getLange('weight') . '</th>';
			// echo '<th>Remarks</th>';
			echo '<th>' . getLange('action') . '</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody class="response_table_body">';
			while ($fetch = mysqli_fetch_array($query)) {
					$service_type_q = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM services WHERE id=" . $fetch['order_type']));
						$service_type = $service_type_q['service_type'];
				$total_pieces += $fetch['quantity'];
				$total_weight += $fetch['weight'];
				echo "<tr>";
				echo "<td>" . $fetch['track_no'] . " <input type='hidden' name='all_cn_no[]' class='all_cn_no' value='" . $fetch['track_no'] . "' /></td>";
				echo "<td>" . $service_type . "</td>";
				// echo "<td></td>";
				echo "<td>" . $fetch['sname'] . "</td>";
				echo "<td>" . $fetch['origin'] . "</td>";
				echo "<td>" . $fetch['scity'] . "</td>";
				echo "<td>" . $fetch['rname'] . "</td>";
				echo "<td>" . $fetch['destination'] . "</td>";
				echo "<td>" . $fetch['rcity'] . "</td>";
				echo "<td>" . $fetch['quantity'] . "<input type='hidden' class='hidden_qunatity_value' value=" . $fetch['quantity'] . " /></td>";
				echo "<td>" . $fetch['weight'] . "<input type='hidden' class='hidden_weight' value=" . $fetch['weight'] . " /></td>";
				// echo "<td></td>";
				echo "<td>";
				echo '<a data-wt="' . $fetch["weight"] . '" data-qt="' . $fetch["quantity"] . '"  style="cursor:pointer" title="Trash" class="delete_row"><i class="fa fa-trash "></i></a>';
				echo '</td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '<input type="hidden" class="pieces" value=' . $total_pieces . ' readonly />';
			echo '<input type="hidden" class="new_weight" value=' . $total_weight . ' readonly />';
		} else {
			echo "<option>" . getLange('no_record_found') . ".</option>";
		}
	}
	if (isset($_POST['getProductPrices']) && $_POST['getProductPrices'] == 1) {
		$productValue = $_POST['productValue'];
		$returnOptions = "";
		$sql = "SELECT * FROM product_type_prices where product_id=$productValue ORDER BY id asc";
		// echo $sql;
		// die;
		$query =  mysqli_query($con, $sql);
		$srno = 1;
		while ($row = mysqli_fetch_array($query)) {
			$returnOptions .= "<tr>";
			$returnOptions .= '<td>' . $srno++ . '</td>';
			$returnOptions .= '<td><input type="text" name="start_range[]" value="' . $row["start_range"] . '" class="form-control" readonly /></td>';
			$returnOptions .= '<td><input type="text" name="end_range[]" value="' . $row["end_range"] . '" class="form-control"  readonly /></td>';
			$returnOptions .= '<td><input type="text" name="division_factor[]" value="' . $row["division_factor"] . '" class="form-control"  readonly /></td>';
			$returnOptions .= '<td><input type="text" name="rate[]" placeholder="Enter Rate" class="form-control" required /></td>';
			$returnOptions .= "</tr>";
		}
		echo $returnOptions;
		exit();
	}
	if (isset($_POST['gettariffPrices']) && $_POST['gettariffPrices'] == 1) {
		$tariffValue = $_POST['tariffValue'];
		$returnOptions = "";
		$sql = "SELECT * FROM tariff_detail where tariff_id=$tariffValue ORDER BY id asc";
		// echo $sql;
		// die;
		$query =  mysqli_query($con, $sql);
		$srno = 1;
		while ($row = mysqli_fetch_array($query)) {
			$returnOptions .= "<tr>";
			$returnOptions .= '<td>' . $srno++ . '</td>';
			$returnOptions .= '<td><input type="text" name="start_range[]" value="' . $row["start_range"] . '" class="form-control" readonly /></td>';
			$returnOptions .= '<td><input type="text" name="end_range[]" value="' . $row["end_range"] . '" class="form-control"  readonly /></td>';
			$returnOptions .= '<td><input type="text" name="rate[]" value="' . $row["rate"] . '" placeholder="Enter Rate" class="form-control" required /></td>';
			$returnOptions .= "</tr>";
		}
		echo $returnOptions;
		exit();
	}
	if (isset($_POST['getSate']) && $_POST['getSate'] == 1) {
		$country_id = $_POST['country'];
		$returnOptions = "<option value=''>--select state--</option>";
		$country_id_q =  mysqli_query($con, "SELECT * FROM country where country_name='$country_id'");
		$country_id_result = mysqli_fetch_assoc($country_id_q);
		$country_id = $country_id_result['id'];
		$query = mysqli_query($con, "SELECT * FROM state where country_id='$country_id'");
		while ($row = mysqli_fetch_array($query)) {
			$returnOptions .= "<option value='" . $row['state_name'] . "'>" . $row['state_name'] . "</option>";
		}
		echo $returnOptions;
		exit();
	}
	if (isset($_POST['getCity']) && $_POST['getCity'] == 1) {
		$state_id = $_POST['state'];
		$returnOptions = "<option value=''>--select city--</option>";
		$state_id_q =  mysqli_query($con, "SELECT * FROM state where state_name='$state_id'");
		$state_id_result = mysqli_fetch_assoc($state_id_q);
		$state_id = $state_id_result['id'];
		$query = mysqli_query($con, "SELECT * FROM city where state_id='$state_id'");
		while ($row = mysqli_fetch_array($query)) {
			$returnOptions .= "<option value='" . $row['city_name'] . "'>" . $row['city_name'] . "</option>";
		}
		echo $returnOptions;
		exit();
	}
	if(isset($_POST['getAccount']) && $_POST['getAccount'] == 1){
		$id = $_POST['id'];
		$fetch = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pay_mode where id = '".$id."' "));
		$acc_id1 = $fetch['payable'];
		$acc_id2 = $fetch['receivable'];
		if(!empty($fetch['payable']) && !empty($fetch['receivable'])){
			$record = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$fetch['payable']."'"));
		
		$groupIds = $record['id'];
		$lastGroupQuery = mysqli_query($con, "SELECT * FROM tbl_accountledger WHERE company_id IS NULL AND groupUnder= '".$groupIds."' ORDER BY id DESC LIMIT 1");
		$lastGroupData = mysqli_fetch_array($lastGroupQuery);
		// var_dump($lastGroupQuery);
		$child = '';
		if(isset($lastGroupData['chart_account_id_child']) && $lastGroupData['chart_account_id_child'])
			{
				$explodedData = array_reverse(explode('-', $lastGroupData['chart_account_id_child']));
				$index = $explodedData[0];
				$newIndex = $index+1;
				$newIndex = $index+1;
				$explodedData[0]  = sprintf("%02d", $newIndex);
				$explodedData = array_reverse($explodedData);
				$child = implode('-', $explodedData).'-01-L';
			}
			else
			{
				$existingId = $record['chart_account_id_child'];
				$child =$existingId.'-01-L';
			}

		$parent = $record['chart_account_id_child'];


		$forReceivable = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$fetch['receivable']."'"));
		$groupIds = $forReceivable['id'];

		$lastGroupQuery = mysqli_query($con, "SELECT * FROM tbl_accountledger WHERE company_id IS NULL AND groupUnder= '".$groupIds."' ORDER BY id DESC LIMIT 1");
		$lastGroupData = mysqli_fetch_array($lastGroupQuery);
		$child1 = '';
		if(isset($lastGroupData['chart_account_id_child']) && $lastGroupData['chart_account_id_child'])
			{
				$explodedData = array_reverse(explode('-', $lastGroupData['chart_account_id_child']));
				$index = $explodedData[0];
				if($fetch['payable'] == $fetch['receivable']){
					$newIndex = $index+2;
				}else{
					$newIndex = $index+1;
				}
				$explodedData[0]  = sprintf("%02d", $newIndex);
				$explodedData = array_reverse($explodedData);
				$child1 = implode('-', $explodedData).'-01-L';
			}
			else
			{
				$existingId = $forReceivable['chart_account_id_child'];
				$child1 =$existingId.'-01-L';
			}
			
		// $parent1 = $child1;
		$parent1 = $forReceivable['chart_account_id_child'];
			
		}elseif(!empty($fetch['payable']) && empty($fetch['receivable'])){
			$record = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$fetch['payable']."'"));
		
		$groupIds = $record['id'];
		$lastGroupQuery = mysqli_query($con, "SELECT * FROM tbl_accountledger WHERE company_id IS NULL AND groupUnder= '".$groupIds."' ORDER BY id DESC LIMIT 1");
		$lastGroupData = mysqli_fetch_array($lastGroupQuery);
		// var_dump($lastGroupQuery);
		$child = '';
		if(isset($lastGroupData['chart_account_id_child']) && $lastGroupData['chart_account_id_child'])
			{
				$explodedData = array_reverse(explode('-', $lastGroupData['chart_account_id_child']));
				$index = $explodedData[0];
				$newIndex = $index+1;
				$explodedData[0]  = sprintf("%02d", $newIndex);
				$explodedData = array_reverse($explodedData);
				$child = implode('-', $explodedData).'-01-L';
			}
			else
			{
				$existingId = $record['chart_account_id_child'];
				$child =$existingId.'-01-L';
			}

		$parent = $record['chart_account_id_child'];
		$child1 = '';
		$parent1 = '';
		}elseif(empty($fetch['payable']) && !empty($fetch['receivable'])){
			// echo "string";
		$forReceivable = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM tbl_accountgroup where id = '".$fetch['receivable']."'"));
		$groupIds = $forReceivable['id'];
		$lastGroupQuery = mysqli_query($con, "SELECT * FROM tbl_accountgroup WHERE company_id IS NULL AND groupUnder= '".$groupIds."' ORDER BY id DESC LIMIT 1");
		$lastGroupData = mysqli_fetch_array($lastGroupQuery);
		// var_dump($lastGroupQuery);
		$child1 = '';
		if(isset($lastGroupData['chart_account_id_child']) && $lastGroupData['chart_account_id_child'])
			{
				$explodedData = array_reverse(explode('-', $lastGroupData['chart_account_id_child']));
				$index = $explodedData[0];
				$newIndex = $index+1;
				$explodedData[0]  = sprintf("%02d", $newIndex);
				$explodedData = array_reverse($explodedData);
				$child1 = implode('-', $explodedData).'-01-L';
			}
			else
			{
				$existingId = $forReceivable['chart_account_id_child'];
				$child1 =$existingId.'-01-L';
			}
			
		$parent1 = $forReceivable['chart_account_id_child'];
		$child = '';
		$parent = '';
		}else
		{
			$child = '';
			$child1 = '';
			$parent = '';
			$parent1 = '';
		}

		$output = array(
			'payable_parent' => $parent,
			'payable' => $child,
			'payable_acc_id' => $acc_id1,
			'receivable_parent' => $parent1,
			'receivable' => $child1,
			'recievable_acc_id' => $acc_id2,
		);
		echo json_encode($output);
	}
		if(isset($_POST['branches']) && $_POST['branches'] == 1){

			$query = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `tbl_grp_mapping` where description like 'Cash Ledger'"));
			$group =mysqli_query($con, "SELECT * FROM `tbl_accountledger` where accountGroupId = '".$query['group_id']."' AND branchCode = '".$_POST['branch']."'");
			if(isset($_POST['id'])){
			$ledger = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `users` where id = '".$_POST['id']."'"));
		}
			$select = '';
			$selected = '';
			$select .= '<label for="exampleInputEmail1">'. getLange('cashledger').'</label>';
			$select .= '<select name="cashledger" class="form-control js-example-basic-single">';
			while ($fetch = mysqli_fetch_array($group)) {
				
				if(isset($fetch['id']) && $fetch['id'] == $ledger['ledgerid']){
					$selected = 'selected';
				}else{
					$selected = '';
				}
				$select .= '<option '.$selected.' value="'.$fetch['id'].'">'.$fetch['ledgerName'].'</option>';
			}
			$select .= '</select>';
			echo $select;
		}

?>