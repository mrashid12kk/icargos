<?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
	$branch_id = $_SESSION['branch_id'];
} else {
	$branch_id = 1;
}
require '../../includes/conn.php';
function addQuote($value)
{
	return (!is_string($value) == true) ? $value : "'" . $value . "'";
}
function getLedgerCode()
{
	global $con;
	$ledgerCode = '';
	$getMaximum_query = "SELECT MAX(ledgerCode) AS ledgerCode FROM tbl_accountledger";
	$getMaximum = mysqli_query($con, $getMaximum_query) or die(mysqli_error($con));
	$maximumnumber =  mysqli_fetch_row($getMaximum);
	$maximumnumber = isset($maximumnumber[0]) ? $maximumnumber[0] : '';
	if (!$maximumnumber) {
		$maximumnumber = 20;
	}
	$maximumnumber = $maximumnumber + 1;
	$ledgerCode = $maximumnumber;
	return $ledgerCode;
}
if (isset($_POST['submit'])) {

	$data = $_POST;
	$sale_man_id = $_POST['sale_man_id'];
	$pricing = $data['pricing'];
	unset($data['pricing']);
	unset($data['password']);
	unset($data['repassword']);
	$send = true;
	$customer_id = $_POST['edit_customer_id'];
	$update_query = '';
	if (isset($_POST['password']) && !empty($_POST['password'])) {
		if (trim($_POST['password']) == trim($_POST['repassword'])) {
			$send = true;
			$password = md5($_POST['password']);
			$data['password'] = $password;
			$update_query .= ",`password`='" . $password . "'";
		} else {
			$send = false;
			$_SESSION['fail_add'] = 'Password not matched, please try another';
			header("Location:" . $_SERVER['HTTP_REFERER']);
		}
	}

	$check_email = mysqli_query($con, "SELECT * FROM customers WHERE email='" . $data['email'] . "' && id!=" . $customer_id);
	if (mysqli_num_rows($check_email) > 0) {
		$send = false;
		$_SESSION['fail_add'] = 'Email already exist, please try another';
		header("Location:" . $_SERVER['HTTP_REFERER']);
	} else {
		if ($send) {
			$data['bname'] = mysqli_real_escape_string($con, $data['bname']);
			$data['address'] = mysqli_real_escape_string($con, $data['address']);
			$data['fname'] = mysqli_real_escape_string($con, $data['fname']);
			$data['branch_id'] = mysqli_real_escape_string($con, $data['branch_id']);
			// $data['is_saletax'] = 1;
			// $data['is_fuelsurcharge'] = 1;
			// $data['status'] =1;
			if (isset($data['submit']))
				unset($data['submit']);
			unset($data['repassword']);
			$email = $data['email'];
			$index = 0;
			if (isset($_FILES["logo"]["name"]) and !empty($_FILES["logo"]["name"])) {
				$target_dir = "../../../users/";
				$target_file = $target_dir . uniqid() . basename($_FILES["logo"]["name"]);
				$extension = pathinfo($target_file, PATHINFO_EXTENSION);
				if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'JPEG ' || $extension == 'jpeg ') {
					if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
						// echo $target_file;
						$target_file = trim($target_file, '../../../');
						$data['image'] = $target_file;
						$update_query .= ",`image`='" . $target_file . "'";
					}
				} else {
					$_SESSION['fail_add'] = 'Your Logo Image Type in Wrong<br>';
					header("Location:" . $_SERVER['HTTP_REFERER']);
					exit();
				}
			}
			if (isset($_FILES["cnic_copy"]["name"]) and !empty($_FILES["cnic_copy"]["name"])) {
				$target_dir = "../../../cnic_copy/";
				$target_file = $target_dir . uniqid() . basename($_FILES["cnic_copy"]["name"]);

				// $db_dir = "users/";
				// $db_file = $db_dir .uniqid(). basename($_FILES["cnic_copy"]["name"]);

				$extension = pathinfo($target_file, PATHINFO_EXTENSION);
				if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'JPEG ' || $extension == 'jpeg ') {
					if (move_uploaded_file($_FILES["cnic_copy"]["tmp_name"], $target_file)) {
						// echo $target_file;
						$target_file = trim($target_file, '../../../');
						$data['cnic_copy'] = $target_file;
						$update_query .= ",`cnic_copy`='" . $target_file . "'";
					}
				} else {
					$_SESSION['fail_add'] = 'Your Logo Image Type in Wrong<br>';
					header("Location:" . $_SERVER['HTTP_REFERER']);
					exit();
				}
			}
			if (isset($data['is_booking_manual']) && $data['is_booking_manual'] == 1) {
				$data['is_booking_manual'] = $data['is_booking_manual'];
			} else {
				$data['is_booking_manual'] = 0;
			}
			unset($data['edit_customer_id']);

			if (isset($data['is_order_manual']) && $data['is_order_manual'] == 1) {
				$data['is_order_manual'] = 1;
			} else {
				$data['is_order_manual'] = 0;
			}
			if (isset($data['is_booking_manual']) && $data['is_booking_manual'] == 1) {
				$data['is_booking_manual'] = 1;
			} else {
				$data['is_booking_manual'] = 0;
			}
			if (isset($data['is_fuelsurcharge']) && $data['is_fuelsurcharge'] == 1) {
				$data['is_fuelsurcharge'] = 1;
			} else {
				$data['is_fuelsurcharge'] = 0;
			}

			if (isset($data['is_merchant']) && $data['is_merchant'] == 1) {
				$data['is_merchant'] = 1;
			} else {
				$data['is_merchant'] = 0;
			}

			if (isset($data['is_saletax']) && $data['is_saletax'] == 1) {
				$data['is_saletax'] = 1;
			} else {
				$data['is_saletax'] = 0;
			}
			if (isset($data['wave_off_return_delivery_fee']) && $data['wave_off_return_delivery_fee'] == 1) {
				$data['wave_off_return_delivery_fee'] = 1;
			} else {
				$data['wave_off_return_delivery_fee'] = 0;
			}
			if (isset($data['is_return_fee_per_parcel']) && $data['is_return_fee_per_parcel'] == 1) {
				$data['is_return_fee_per_parcel'] = 1;
			} else {
				$data['is_return_fee_per_parcel'] = 0;
			}
			if (isset($_POST['fuel_charge_val']) && $_POST['fuel_charge_val'] > 0 && $data['is_fuelsurcharge'] == 1) {
				$check_prev_val = mysqli_fetch_array(mysqli_query($con, "SELECT charge_value FROM customer_wise_charges WHERE customer_id = " . $customer_id . " AND charge_name = 'fuel_surcharge' "));
				$ch_q = '';
				if (isset($check_prev_val['charge_value']) && !empty($check_prev_val['charge_value'])) {
					$ch_q = "UPDATE customer_wise_charges SET charge_value='" . $_POST['fuel_charge_val'] . "' WHERE customer_id = " . $customer_id . " AND charge_name = 'fuel_surcharge' ";

					mysqli_query($con, $ch_q);
				} else {
					$ch_q = "INSERT INTO `customer_wise_charges`(`customer_id`, `charge_name`, `charge_type`, `charge_value`) VALUES ('" . $customer_id . "','fuel_surcharge','','" . $_POST['fuel_charge_val'] . "')";

					mysqli_query($con, $ch_q);
				}
				unset($data['fuel_charge_val']);
			}
			unset($data['fuel_charge_val']);
			$is_booking_manual = $data['is_booking_manual'];
			$is_fuelsurcharge = $data['is_fuelsurcharge'];
			$is_saletax = $data['is_saletax'];
			$is_merchant = $data['is_merchant'];
			$is_order_manual = $data['is_order_manual'];
			$wave_off_return_delivery_fee = $data['wave_off_return_delivery_fee'];
			$is_return_fee_per_parcel = $data['is_return_fee_per_parcel'];
			$return_fee_per_parcel = $data['return_fee_per_parcel'];
			$multi_user = isset($data['multi_user']) && $data['multi_user'] != '' ? $data['multi_user'] : '0';
			mysqli_query($con, "UPDATE customers SET is_booking_manual=" . $is_booking_manual . ",is_fuelsurcharge=" . $is_fuelsurcharge . ",is_saletax=" . $is_saletax . ",is_merchant = '" . $is_merchant . "',is_order_manual=" . $is_order_manual . ",wave_off_return_delivery_fee=" . $wave_off_return_delivery_fee . ",return_fee_per_parcel=" . $return_fee_per_parcel . ",is_return_fee_per_parcel=" . $is_return_fee_per_parcel . ",multi_user=" . $multi_user . " WHERE id = " . $customer_id);
			// echo "<pre>";
			// print_r ($data);
			// echo "</pre>";
			// die;
			// foreach ($data as $key => &$value) {
			//     if (trim($value) == '') {
			//         array_splice($data, $index, 1);
			//         $index--;
			//     }
			//     $index++;
			// }
			$customer_type = $data['customer_type'];
			$data['validity'] = isset($data['validity']) && $data['validity'] != '' ? date('d-m-Y', strtotime($data['validity'])) : '';
			$sql = "UPDATE customers SET `bname`='" . $data['bname'] . "',`sale_man_id`=$sale_man_id,`customer_type`=" . $data['customer_type'] . ",`cnic`='" . $data['cnic'] . "',`branch_id`='" . $data['branch_id'] . "',`address`='" . $data['address'] . "',`billing_address`='" . $data['billing_address'] . "',`is_booking_manual`='" . $data['is_booking_manual'] . "',`fname`='" . $data['fname'] . "',`mobile_no`='" . $data['mobile_no'] . "',`email`='" . $data['email'] . "',`state_id`='" . $data['state_id'] . "',`city`='" . $data['city'] . "',`parent_code`='" . $data['parent_code'] . "',`contact_person`='" . $data['contact_person'] . "',`designation`='" . $data['designation'] . "',`industry_code`='" . $data['industry_code'] . "',`gst`='" . $data['gst'] . "',`fax`='" . $data['fax'] . "',`website_url`='" . $data['website_url'] . "',`product_type`='" . $data['product_type'] . "',`expected_shipment`='" . $data['expected_shipment'] . "',`validity`='" . $data['validity'] . "',`billing_instruction`='" . $data['billing_instruction'] . "',`handling_charges`='" . $data['handling_charges'] . "',`tariff_increase`='" . $data['tariff_increase'] . "',`payment_within`='" . $data['payment_within'] . "',`monthly_revenue`='" . $data['monthly_revenue'] . "',`fuel_formula`='" . $data['fuel_formula'] . "',`other_charges`='" . $data['other_charges'] . "',`special_instruction`='" . $data['special_instruction'] . "',`frequent_destination`='" . $data['frequent_destination'] . "',`bdm_kam`='" . $data['bdm_kam'] . "',`territory_code`='" . $data['territory_code'] . "',`collector`='" . $data['collector'] . "',`collection_id`='" . $data['collection_id'] . "',`chs_abh`='" . $data['chs_abh'] . "',`mr_amr`='" . $data['mr_amr'] . "',`sm`='" . $data['sm'] . "',`bank_name`='" . $data['bank_name'] . "',`acc_title`='" . $data['acc_title'] . "',`bank_ac_no`='" . $data['bank_ac_no'] . "',`branch_name`='" . $data['branch_name'] . "',`branch_code`='" . $data['branch_code'] . "',`swift_code`='" . $data['swift_code'] . "',`ntn_no`='" . $data['ntn_no'] . "',`stn_no`='" . $data['stn_no'] . "',`logistics`='" . $data['logistics'] . "', `customer_latitude`= '" . $data['customer_latitude'] . "', `customer_longitude`= '" . $data['customer_longitude'] . "',   `express`='" . $data['express'] . "',`iban_no`='" . $data['iban_no'] . "',`zone_type`='" . $data['zone_type'] . "',`tariff_type`='" . $data['tariff_type'] . "',`status`='" . $data['active_status'] . "',`api_status`='" . $data['api_status'] . "' " . $update_query . " WHERE id = " . $customer_id;
			// echo $customer_type;
			// die;
			$query = mysqli_query($con, $sql) or die(mysqli_error($con));

			if($query == TRUE){
				if(isset($data['bname'])){
					$x = mysqli_query($con,"UPDATE tbl_accountledger set ledgerName='".$data['bname']."', branchCode = '". $data['branch_id'] ."' WHERE customer_id = '".$customer_id."'");
					}
				if(!empty($_POST['c_payable']) ||!empty($_POST['p_payable']) ||!empty($_POST['p_acc_id']) || !empty($_POST['c_recievable']) || !empty($_POST['p_recievable']) || !empty($_POST['r_acc_id'])){
					// die();
					$sql = "DELETE FROM tbl_accountledger where customer_id= '".$customer_id."'";
					$query1 = mysqli_query($con, $sql);
					// var_dump($query1);
					$ledgercode = mysqli_query($con , "SELECT MAX(`ledgerCode`) AS `ledgerCode` FROM `tbl_accountledger`");
				$getLedgerCode = mysqli_fetch_assoc($ledgercode);

				$ledgerCode = $getLedgerCode['ledgerCode'];
				if(!empty($ledgerCode)){
						$ledgerCode = $ledgerCode + 1 ;
				}else{
						$ledgerCode=13;	
				}
				$dateToday = date("Y-m-d H:i:s");
				if(!empty($_POST['c_payable']) && !empty($_POST['p_payable']) && !empty($_POST['p_acc_id'])){
					$sql =  "INSERT into tbl_accountledger (`ledgerCode`,`ledgerName`,`customer_id`,`created_on`,`accountGroupId`,`chart_account_id`, `chart_account_id_child`,`branchCode`) VALUES ('".$ledgerCode."', '".$data['bname']."', '".$customer_id."','".$dateToday."','".$_POST['p_acc_id']."', '".$_POST['p_payable']."', '".$_POST['c_payable']."', ".$data['branch_id']." )";
					$query1 =mysqli_query($con ,$sql);
					// echo $sql;
				}
				if($query1 == TRUE){
						$ledgerCode = $ledgerCode+1;
					}else{
						$ledgerCode = $ledgerCode;
					}

				if(!empty($_POST['c_recievable']) && !empty($_POST['p_recievable']) && !empty($_POST['r_acc_id'])){
						$sql =  "INSERT into tbl_accountledger (`ledgerCode`,`ledgerName`,`customer_id`,`created_on`,`accountGroupId`,`chart_account_id`, `chart_account_id_child`,`branchCode`) VALUES ('".$ledgerCode."', '".$data['bname']."', '".$customer_id."','".$dateToday."','".$_POST['r_acc_id']."', '".$_POST['p_recievable']."', '".$_POST['c_recievable']."', ".$data['branch_id']." )";
						// echo $sql;
					$query1 = mysqli_fetch_array(mysqli_query($con ,$sql));
				}
				}
			}
			$rowscount = mysqli_affected_rows($con);

			$_SESSION['succ_msg'] = 'Account UPDATED successfully';
			$customer_update = array();
			$customer_update['client_code'] = 1000 + $customer_id;

			// $accountLedgerData = [];
			// // $accountLedgerData['account_type']=$customer_update['account_type'];
			// // $account_type=$customer_update['account_type'];
			// $accountLedgerData['ledgerName'] = $data['fname'];
			// $accountLedgerData['ledgerName'] .= isset($data['bname']) ? '(' . $data['bname'] . ')' : '';
			// $ledgerName = $accountLedgerData['ledgerName'];
			// $accountLedgerData['company_id'] = 1;
			// $accountLedgerData['accountGroupId'] = 26;
			// $accountLedgerData['mobile'] = isset($data['mobile_no']) ? $data['mobile_no'] : '';
			// $accountLedgerData['address'] = isset($data['address']) ? $data['address'] : '';
			// // $accountLedgerData['ledgerName'] .= isset($customer_update['client_code']) ? ' (' . $customer_update['client_code'] . ')' : '';

			// $account_sql2 = mysqli_query($con, "SELECT * FROM  tbl_accountledger  WHERE  is_external=" . $customer_id . " ");
			// $account_sql_d = mysqli_fetch_array($account_sql2);
			// // echo '<pre>', print_r($account_sql_d), '</pre>';
			// // exit();
			// if (empty($account_sql_d)) {
			// 	$accountLedgerData['ledgerName'] .= isset($customer_update['client_code']) ? ' (' . $customer_update['client_code'] . ')' : '';
			// 	$accountLedgerData['ledgerCode'] = getLedgerCode();
			// 	$accountLedgerData['is_external'] = $customer_id;
			// 	$accountLedgerData['email'] = $_POST['email'];
			// 	$creditor_data = $accountLedgerData;
			// 	foreach ($accountLedgerData as $j => &$row) {
			// 		$row = addQuote($row);
			// 	}
			// 	$keyss = implode(", ", array_keys($accountLedgerData));
			// 	$valuess = implode(",", $accountLedgerData);
			// 	$ledger_sql = "INSERT INTO tbl_accountledger ($keyss) VALUES($valuess)";
			// 	$ledger_sql_query = mysqli_query($con, $ledger_sql) or die(mysqli_error($con));
			// 	$ledger_id = mysqli_insert_id($con);
			// 	$post = [
			// 		"ledger_id" => $ledger_id,
			// 		"customer_id" => $customer_id,
			// 		"account_group_id" => $accountLedgerData['accountGroupId'],
			// 	];
			// 	$url = 'https://pla.a.eloerp.net/accounting/chart_account_api/generateChartOfAccount';
			// 	$ch = curl_init();
			// 	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
			// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json;text/html;charset=utf-8'));
			// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// 	curl_setopt($ch, CURLOPT_URL, $url);
			// 	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
			// 	$all = curl_exec($ch);
			// 	curl_close($ch);
			// 	$res = json_decode($all);

			// 	if ($customer_type != 2) {
			// 		$creditor_data['accountGroupId'] = 22;
			// 		$creditor_data['ledgerCode'] = getLedgerCode();
			// 		foreach ($creditor_data as $j => &$row) {
			// 			$row = addQuote($row);
			// 		}
			// 		$keyss = implode(", ", array_keys($creditor_data));
			// 		$valuess = implode(",", $creditor_data);
			// 		$ledger_sql = "INSERT INTO tbl_accountledger ($keyss) VALUES($valuess)";
			// 		$ledger_sql_query = mysqli_query($con, $ledger_sql) or die(mysqli_error($con));
			// 		$ledger_id = mysqli_insert_id($con);
			// 		$post = [
			// 			"ledger_id" => $ledger_id,
			// 			"customer_id" => $customer_id,
			// 			"account_group_id" => $creditor_data['accountGroupId'],
			// 		];
			// 		$url = 'https://pla.a.eloerp.net/accounting/chart_account_api/generateChartOfAccount';
			// 		$ch = curl_init();
			// 		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
			// 		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
			// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// 		curl_setopt($ch, CURLOPT_URL, $url);
			// 		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
			// 		$all = curl_exec($ch);
			// 		curl_close($ch);
			// 		$res = json_decode($all);
			// 	}
			// } else {
			// 	// echo "string";
			// 	// die;
			// 	$accountLedgerData['ledgerName'] .= isset($customer_update['client_code']) ? ' (' . $customer_update['client_code'] . ')' : '';
			// 	$accountGroupId = $accountLedgerData['accountGroupId'];
			// 	$account_sql23 = mysqli_query($con, "SELECT * FROM  tbl_accountledger  WHERE  is_external=" . $customer_id . " AND accountGroupId =" . $accountGroupId);
			// 	$account_sql_d1 = mysqli_fetch_array($account_sql23);

			// 	$creditor_data = $accountLedgerData;
			// 	$account_sql = "UPDATE tbl_accountledger SET ";
			// 	foreach ($accountLedgerData as $a => &$values) {
			// 		$values = addQuote($values);
			// 		$account_sql .=  $a . " = " . $values . ",";
			// 	}
			// 	$account_sql = rtrim($account_sql, ',') . " WHERE is_external = " . $customer_id . " AND accountGroupId =" . $accountGroupId;
			// 	mysqli_query($con, $account_sql);

			// 	$post = [
			// 		"ledger_id" => $account_sql_d1['id'],
			// 		"customer_id" => $customer_id,
			// 		"account_group_id" => $accountGroupId,
			// 	];
			// 	$url = 'https://pla.a.eloerp.net/accounting/chart_account_api/generateChartOfAccount';
			// 	$ch = curl_init();
			// 	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
			// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json;text/html;charset=utf-8'));
			// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// 	curl_setopt($ch, CURLOPT_URL, $url);
			// 	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
			// 	$all = curl_exec($ch);
			// 	curl_close($ch);
			// 	$res = json_decode($all);

			// 	$creditor_data['accountGroupId'] = 22;
			// 	$accountGroupId = $creditor_data['accountGroupId'];

			// 	$account_sql23 = mysqli_query($con, "SELECT * FROM  tbl_accountledger  WHERE  is_external=" . $customer_id . " AND accountGroupId =" . $accountGroupId);
			// 	$account_sql_d1 = mysqli_fetch_array($account_sql23);

			// 	$account_sql = "UPDATE tbl_accountledger SET ";
			// 	foreach ($creditor_data as $a => &$values) {
			// 		$values = addQuote($values);
			// 		$account_sql .=  $a . " = " . $values . ",";
			// 	}
			// 	$account_sql = rtrim($account_sql, ',') . " WHERE is_external = " . $customer_id . " AND accountGroupId =" . $accountGroupId;
			// 	mysqli_query($con, $account_sql);

			// 	$post = [
			// 		"ledger_id" => $account_sql_d1['id'],
			// 		"customer_id" => $customer_id,
			// 		"account_group_id" => $accountGroupId,
			// 	];
			// 	$url = 'https://pla.a.eloerp.net/accounting/chart_account_api/generateChartOfAccount';
			// 	$ch = curl_init();
			// 	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
			// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json;text/html;charset=utf-8'));
			// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// 	curl_setopt($ch, CURLOPT_URL, $url);
			// 	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
			// 	$all = curl_exec($ch);
			// 	curl_close($ch);
			// 	$res = json_decode($all);
			// }
		}


		header("Location:" . $_SERVER['HTTP_REFERER']);
	}
}