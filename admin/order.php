<?php
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
$message = '';
session_start();
require 'includes/conn.php';
require_once "includes/role_helper.php";
if (!checkRolePermission($_SESSION['user_role_id'], 8, 'view_only', $comment = null)) {
	header("location:access_denied.php");
}
$currency = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='currency' "));
if (isset($_SESSION['users_id'])) {
	include "includes/header.php";
	if (isset($_POST['update_order']) && isset($_GET['id'])) {
		$id = (int)$_GET['id'];
		$data = $_POST;
		unset($data['update_order']);
		$sql = "UPDATE orders SET";
		$index = 0;
		foreach ($data as $key => $value) {
			$sql .= " $key = '$value'";
			$index++;
			if ($index != count($data))
				$sql .= ",";
		}
		$sql .= " WHERE id = $id";
		if (mysqli_query($con, $sql)) {
			$collection_amount = (int)trim($data['collection_amount']);
			$price = (int)trim($data['price']);
			$order_data = mysqli_query($con, "SELECT track_no FROM orders WHERE id =" . $id . " ");
			$order_number_data = mysqli_fetch_array($order_data);
			$order_no = $order_number_data['track_no'];

			mysqli_query($con, "UPDATE ledger SET delivery_charges ='" . $price . "', collected_amount='" . $collection_amount . "' WHERE order_no=" . $order_no . " ");
			$message = '<div class="alert alert-success">Order is updated successfully!</div>';
		} else {
			$message = '<div class="alert alert-warning">Order is not updated!</div>';
		}
	}
	if (isset($_POST['branch_id']) && $_POST['branch_id'] != '') {
		$branch_id = $_POST['branch_id'];
		$order_id = $_GET['id'];
		mysqli_query($con, "UPDATE orders SET branch_id = '" . $branch_id . "' WHERE id = '" . $order_id . "' ");
	}
	$id = (int)$_GET['id'];
	$message_query = mysqli_query($con, "SELECT * FROM order_comments WHERE order_id =" . $id . " order by id   ");
	$total_comments = mysqli_num_rows($message_query);



	$cities2 = mysqli_query($con, "SELECT * FROM cities WHERE 1 order by id desc ");
?>

	<body data-ng-app>
		<style type="text/css">
			#same_form_layout table tr th:last-child {
				width: auto !important;
			}

			@media(max-width: 767px) {
				.container {
					width: auto;
				}

				.content>.container-fluid {
					padding-left: 5px;
					padding-right: 6px;
				}

				table.detail a,
				table.detail select,
				table.detail input {
					margin-bottom: 7px;
					margin-right: 10px;
				}

				#same_form_layout {
					padding: 0 !important;
				}

				.table-bordered {
					border: 1px solid #ddd;
					border-right: none;
				}

				.panel-body {
					padding: 10px 8px;
				}
			}

			table tr th,
			table tr td {
				font-size: 18px !important;
				font-weight: 500 !important;
				color: #6e6e71;
			}

			.form-input strong {
				width: 100px !important;
				float: left;

			}
		</style>

		<?php

		include "includes/sidebar.php";

		?>
		<!-- Aside Ends-->

		<section class="content">

			<?php
			include "includes/header2.php";
			?>

			<!-- Header Ends -->
			<style type="text/css">
				table.detail a,
				table.detail select,
				table.detail input {
					margin-right: 10px;
				}
			</style>

			<div class="warper container-fluid">
				<?php if (isset($_SESSION['order_message'])) { ?>
					<div class="alert alert-success">
						<?php
						echo  $_SESSION['order_message'];
						unset($_SESSION['order_message']);
						?>
					</div>
				<?php } ?>
				<?php


				if (isset($_GET['id']) && !empty($_GET['id'])) {

					$id = $_GET['id'];

					$orderID = $_GET['id'];

					$query = mysqli_query($con, "SELECT * FROM orders WHERE id = '" . $id . "'");
					// echo "SELECT * FROM orders WHERE id = '".$id."'";
					// die;
					$data = mysqli_fetch_array($query);
					$prev_order = mysqli_query($con, "SELECT * FROM orders WHERE id < '" . $id . "' ORDER BY id DESC LIMIT 1");
					$prev_order = ($prev_order) ? mysqli_fetch_object($prev_order) : null;
					$next_order = mysqli_query($con, "SELECT * FROM orders WHERE id > '" . $id . "' ORDER BY id LIMIT 1");
					$next_order = ($next_order) ? mysqli_fetch_object($next_order) : null;
					if ($data['status'] == 'null' || $data['status'] == '')
						$data['status'] = 'pending';
					$type = $_SESSION['type'];
					$userID = $_SESSION['users_id'];
					$query = mysqli_query($con, "SELECT * FROM orders WHERE id = '" . $id . "' ") or die(mysqli_error($con));
					$deliverData = mysqli_fetch_array($query);
					$status = $deliverData['status'];
					$driverID = $deliverData['driver_id'];
					$deliverdriverID = isset($deliverData['assign_driver']) ? $deliverData['assign_driver'] : '';
					$deliverID = $deliverData['id'];
					$status = $data['status'];

					//pickup query
					$pickup_query = mysqli_query($con, "SELECT * FROM zones WHERE city='" . $data['origin'] . "' ");
					$delivery_query = mysqli_query($con, "SELECT * FROM zones WHERE city='" . $data['destination'] . "' ");
					echo '<div class="panel panel-default">';
					echo '<div class="panel-heading">Order Details</div>';
					echo '<div class="panel-body">';
					echo $message;
					$status_q =  mysqli_query($con, "SELECT * FROM order_status WHERE  active='1' and hide_from_listing = '0'  ORDER BY sort_num");
					$status_reason2 = mysqli_query($con, "SELECT * FROM order_reason ");
				?>
					<div id="same_form_layout" style="padding:0 9px;">
						<div class="row">
							<div class="col-sm-1 text-left upate_Btn left_right_none">

								<?php



								$rider_query = mysqli_query($con, "SELECT * FROM users WHERE type='driver'  ");
								?>

							</div>

							<?php
							echo '<table class="table table-bordered detail order-detail-form">';
							echo '<tr>';
							echo '<td width="20%"> ' . getLange('updatestatus') . ' </td>';
							echo '<td>'; ?>
							<form method="POST" action="orderAction.php">
								<input type="hidden" name="order_id" value="<?php echo $data['id'] ?>">
								<div class="row">
									<div class="col-md-4">
										<select class="js-example-basic-single receivesatatus" name="update_status">
											<?php while ($sts_row = mysqli_fetch_array($status_q)) { ?>
												<option data-id="<?php echo $sts_row['reason_id']; ?>" class="reasonoo" <?php if ($data['status'] == $sts_row['status']) {
																															echo "selected";
																														} ?> value="<?php echo $sts_row['status']; ?>"><?php echo $sts_row['status']; ?></option>
											<?php } ?>
										</select>

									</div>
									<div class="col-md-4 receive hidden">
										<input type="text" name="received_by" class="form-control " placeholder="Received By" value="<?php if (empty($data['received_by'])) {
																																			echo $data['rname'];
																																		} else {
																																			echo $data['received_by'];
																																		} ?>">
									</div>
									<div class="col-md-4 reason hidden">
										<select type="text" class="js-example-basic-single  status_reason" name="status_reason" class="form-control">
											<option value="">Select Reason</option>
											<?php while ($status_reason = mysqli_fetch_array($status_reason2)) { ?>
												<option value="<?php echo $status_reason['reason_desc']; ?>" <?php if ($data['status_reason'] == $status_reason['reason_desc']) {
																													echo "selected";
																												} ?>><?php echo $status_reason['reason_desc']; ?></option>
											<?php } ?>
										</select>
									</div>

									<div class="col-md-2">
										<input type="submit" name="update_sts" class="btn btn-success" value="<?php echo getLange('update') ?>">
									</div>

								</div>
							</form>
							<?php echo '</td>';
							echo '</tr>';



							echo '<tr>';
							echo '<td width="20%">' . getLange('addcomment') . ' </td>';
							echo '<td>'; ?>
							<form method="POST" action="orderAction.php">
								<input type="hidden" name="order_id" value="<?php echo $data['id'] ?>">

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<select class="form-control default_cmnt">
												<option selected disabled>Default comment</option>
												<option>Customer not available</option>
												<option>Customer wants it tomorrow</option>
												<option>Customer refused to accept</option>
												<option>Customer wanted to open the parcel</option>
												<option>Customer is out of city</option>
												<option>Delivery point is not responding</option>
												<option>Pick up point is not responding</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<textarea class="form-control comment_sec" name="comment" required="true"></textarea>
									</div>
									<div class="col-md-2">
										<input type="submit" name="addcomment" class="btn btn-success" value="<?php echo getLange('submit') ?>">
									</div>
								</div>
							</form>
							<?php echo '</td>';
							echo '</tr>';


							echo '<tr>';

							echo '<td colspan="2" class="other-actions">';

							if ($type == 'driver') {
							} else {
								if ($type == 'admin') {
									// echo '<a href="editbookingform.php?id='.$orderID.'" class="btn btn-info edit-order pull-right">'.getLange('edit').'</a>';
									echo '<a href="editbookingform.php?id=' . $orderID . '" class="btn btn-info pull-right">' . getLange('edit') . '</a>';
								}
							}

							echo '</td>';
							echo '</tr>';
							echo '<form method="POST" action="" />';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('businessname') . ' </td>';
							echo '<td class="form-input">';
							echo '<span>' . $data['sname'] . '</span>';
							echo '<input hidden type="text" class="ins" name="sbname" value="' . $data['sname'] . '" />';
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('ordertrack') . '</td>';
							echo '<td class="form-input">';
							echo '<span >' . $data['track_no'] . '</span>';
							echo '</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td width="20%">' . getLange('pickupcity') . ' </td>';
							echo '<td class="form-input">';
							echo '<span class="main_origin">' . $data['origin'] . '</span>';
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('deliverycity') . ' </td>';
							echo '<td class="form-input">';
							echo '<span class="main_destination">' . $data['destination'] . '</span>';
							?>
							<select style="display: none;" class=" destination destination_select " name="destination">
								<?php while ($row = mysqli_fetch_array($cities2)) { ?>
									<option value="<?php echo $row['city_name']; ?>" <?php if ($row['city_name'] == $data['destination']) {
																							echo " selected";
																						} ?>><?php echo $row['city_name']; ?></option>
								<?php } ?>
							</select>
							<?php
							echo '<input type="hidden" class="customer_id" value=' . $data['customer_id'] . ' >';
							echo '</td>';
							echo '</tr>';

							$qrrry = "SELECT * FROM users WHERE id = '" . $data['pickup_rider'] . "' ";
							$query33 = mysqli_query($con, $qrrry) or die(mysqli_error($con));
							$fetch33 = mysqli_fetch_array($query33);
							$qrrry1 = "SELECT * FROM users WHERE  type='driver' ";
							$query34 = mysqli_query($con, $qrrry1) or die(mysqli_error($con));


							echo '<tr>';
							echo '<td width="20%">' . getLange('pickuprider') . ' </td>';
							echo '<td class="form-input">';
							echo '<span>' . $fetch33['Name'] . '</span>';
							if ($data['status'] == 'assigned' || $data['status'] == 'delivered') {
								echo '<select hidden class="assign_driver" hidden name="pickup_rider" />';
								while ($rec = mysqli_fetch_array($query34)) {
									if ($rec['id'] == $data['pickup_rider']) {
										echo "<option selected value=" . $rec['id'] . ">" . $rec['Name'] . "</option>";
									} else {
										echo "<option value=" . $rec['id'] . ">" . $rec['Name'] . "</option>";
									}
								}

								echo '<select/>';
							}
							echo '</td>';
							echo '</tr>';
							$qrrry12 = "SELECT * FROM users WHERE  type='driver' ";
							$query123 = mysqli_query($con, $qrrry12) or die(mysqli_error($con));


							$qrrry12 = "SELECT * FROM users WHERE id = '" . $data['delivery_rider'] . "' AND type='driver' ";
							$query35 = mysqli_query($con, $qrrry12) or die(mysqli_error($con));
							$total = mysqli_num_rows($query35);
							$query35_rec = mysqli_fetch_array($query35);
							if ($total == 1) {
								echo '<tr>';
								echo '<td width="20%">' . getLange('deliveryrider') . '</td>';
								echo '<td class="form-input">';
								echo '<span>' . $query35_rec['Name'] . '</span>';
								if ($data['status'] == 'assigned' || $data['status'] == 'delivered') {
									echo '<select hidden class="assign_driver" hidden name="assign_driver" />';
									while ($rec = mysqli_fetch_array($query123)) {
										if ($rec['id'] == $data['assign_driver']) {
											echo "<option selected value=" . $rec['id'] . ">" . $rec['Name'] . "</option>";
										} else {
											echo "<option value=" . $rec['id'] . ">" . $rec['Name'] . "</option>";
										}
									}

									echo '<select/>';
								}
								echo '</td>';
								echo '</tr>';
							}

							if (isset($data['branch_id']) && $type == 'admin') {
								$branch_idss = $data['branch_id'];
								$qrry = "SELECT * FROM branches WHERE id = '" . $branch_idss . "' ";
								$branch = mysqli_query($con, $qrry);
								if ($branch) {
									$branch = mysqli_fetch_object($branch);
									if (isset($branch->name)) {
										echo '<tr>';
										echo '<td width="20%">' . getLange('branchname') . ' </td>';
										echo '<td class="form-input">';
										echo '<span >' . $branch->name . '</span>';
										echo '</td>';
										echo '</tr>';
									}
								}
							}
							echo '<tr>';
							echo '<td width="20%">' . getLange('sender') . '</td>';
							echo '<td class="form-input">';
							echo '<strong>' . getLange('name') . '</strong><span >' . $data['sname'] . '</span><input hidden type="text" name="sname" value="' . $data['sname'] . '"><br>';
							echo '<strong>' . getlange('company') . ':</strong><span >' . $data['sbname'] . '</span><input hidden type="text" name="sbname" value="' . $data['sbname'] . '"><br>';
							echo '<strong>' . getLange('email') . ':</strong><span >' . $data['semail'] . '</span><input hidden type="text" name="semail" value="' . $data['semail'] . '"><br>';
							echo '<strong>' . getLange('phone') . ':</strong><span >' . $data['sphone'] . '</span><input hidden type="text" name="sphone" value="' . $data['sphone'] . '"><br>';
							echo '<strong>' . getLange('cnic') . ':</strong><span >' . $data['scnic'] . '</span><input hidden type="text" name="sphone" value="' . $data['scnic'] . '"><br>';
							echo '<strong>' . getLange('address') . ':</strong><span >' . $data['sender_address'] . '</span><input hidden type="text" name="sender_address" value="' . $data['sender_address'] . '"><br>';
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('receiver') . '</td>';
							echo '<td class="form-input">';
							echo '<strong>' . getLange('name') . ':</strong><span >' . $data['rname'] . '</span><input hidden type="text" name="rname" value="' . $data['rname'] . '"><br>';
							// echo '<span ><strong>Email:</strong>'.$data['remail'].'</span><br>';
							echo '<strong>' . getLange('phone') . ':</strong><span >' . $data['rphone'] . '</span><input hidden type="text" name="rphone" value="' . $data['rphone'] . '"><br>';
							echo '<strong>' . getLange('email') . ':</strong><span >' . $data['remail'] . '</span><input hidden type="text" name="remail" value="' . $data['remail'] . '"><br>';
							echo '<strong>' . getLange('address') . ':</strong><span >' . $data['receiver_address'] . '</span><input hidden type="text" name="receiver_address" value="' . $data['receiver_address'] . '"><br>';
							echo '</td>';
							echo '</tr>';
							$service_type = $data['order_type'];
							$order_type_q = mysqli_query($con, "SELECT service_type FROM services WHERE id ='" . $service_type . "' ");
							$order_type_r = mysqli_fetch_array($order_type_q);
							$order_type = $order_type_r['service_type'];

							$services_q = mysqli_query($con, "SELECT * FROM services WHERE 1");
							echo '<tr>';
							echo '<td width="20%">' . getLange('servicetype') . ' </td>';
							echo '<td class="form-input">';
							echo '<span>' . $order_type . '</span>';
							echo '<select hidden class="order_type" hidden name="order_type" />';
							while ($r = mysqli_fetch_array($services_q)) {
								$selected = ($r['id'] == $data['order_type']) ? 'selected' : '';
							?>
								<option <?= $selected; ?> value="<?php echo $r['id']; ?>"><?php echo $r['service_type']; ?></option>
							<?php }
							echo '<select/>';
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('orderdate') . '</td>';
							echo '<td class="form-input">';
							echo '<span>' . date("Y-m-d", strtotime($data['order_date'])) . '</span>';
							echo '<input hidden type="text" class="datetimepicker4" name="order_date" value="' . date("Y-m-d", strtotime($data['order_date'])) . '" />';
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('ordertime') . '</td>';
							echo '<td class="form-input">';
							echo '<span>' . date("H:i:s", strtotime($data['order_time'])) . '</span>';
							echo '<input hidden type="text" class="datetimepicker4" name="order_time" value="' . date("H:i:s", strtotime($data['order_time'])) . '" />';
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('booking_type') . '</td>';
							echo '<td class="form-input">';
							if ($data['booking_type'] == '1') {
								echo '<span>Invoice</span>';
							}
							if ($data['booking_type'] == '2') {
								echo '<span>Cash</span>';
							}
							if ($data['booking_type'] == '3') {
								echo '<span>To Pay</span>';
							}
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('parcelweight') . '</td>';
							echo '<td class="form-input">';
							echo '<span>' . $data['weight'] . 'KG</span>';
							echo '<input hidden type="text" class="weighting" name="weight" value="' . $data['weight'] . '" />';
							echo '</td>';
							echo '</tr>';

							echo '<tr>';
							echo '<td width="20%">' . getLange('codamount') . ' </td>';
							echo '<td class="form-input">';
							echo '<span >' . $currency['value'] . ' ' . (int)$data['collection_amount'] . '</span>';
							echo '<input hidden type="text" name="collection_amount" value="' . $data['collection_amount'] . '" />';
							echo '</td>';
							echo '</tr>';


							echo '<tr>';
							echo '<td width="20%">' . getLange('deliverycharges') . ' </td>';
							echo '<td class="form-input">';
							echo '<span >' . $currency['value'] . ' ' . (int)$data['price'] . '</span>';
							echo '<input hidden type="text" class="delivery" name="price" value="' . $data['price'] . '" />';
							echo '</td>';

							echo '<tr>';


							echo '<tr>';
							echo '<td width="20%">' . getLange('specialcharges') . ' </td>';
							echo '<td class="form-input">';
							echo '<span >' . $currency['value'] . ' ' . (int)$data['special_charges'] . '</span>';
							echo '<input hidden type="text" class="delivery" name="price" value="' . $data['price'] . '" />';
							echo '</td>';

							echo '<tr>';

							echo '<tr>';
							echo '<td width="20%">' . getLange('totalcharges') . ' </td>';
							echo '<td class="form-input">';
							echo '<span >' . $currency['value'] . ' ' . (int)$data['grand_total_charges'] . '</span>';
							echo '<input hidden type="text" class="delivery" name="grand_total_charges" value="' . $data['grand_total_charges'] . '" />';
							echo '</td>';

							echo '<tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('insurancepremium') . ' </td>';
							echo '<td class="form-input">';
							echo '<span >' . $currency['value'] . ' ' . (int)isset($data['insured_premium']) && !empty($data['insured_premium']) ? $data['insured_premium'] : 0 . '</span>';
							echo '<input hidden type="text" class="delivery" name="price" value="' . $data['insured_premium'] . '" />';
							echo '</td>';

							echo '<tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('fuelsurcharge') . ' </td>';
							echo '<td class="form-input">';
							echo '<span >' . $currency['value'] . ' ' . (int)isset($data['fuel_surcharge']) && !empty($data['fuel_surcharge']) ? $data['fuel_surcharge'] : 0 . '</span>';
							echo '<input hidden type="text" class="delivery" name="price" value="' . $data['fuel_surcharge'] . '" />';
							echo '</td>';

							echo '<tr>';



							echo '<tr>';
							echo '<td width="20%">' . getLange('salestax') . ' </td>';
							echo '<td class="form-input">';
							echo '<span >' . $currency['value'] . ' ' . (int)$data['pft_amount'] . '</span>';
							echo '<input hidden type="text" class="pft_amount" name="pft_amount" value="' . $data['pft_amount'] . '" />';
							echo '</td>';
							echo '<tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('netamount') . '  </td>';
							echo '<td class="form-input">';
							echo '<span >' . $currency['value'] . ' ' . (int)$data['net_amount'] . '</span>';
							echo '<input hidden type="text" class="inc_amount" name="net_amount" value="' . $data['net_amount'] . '" />';
							echo '</td>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('refernceno') . ' .</td>';
							echo '<td class="form-input">';
							echo '<span>' . $data['ref_no'] . '</span>';
							echo '<input hidden type="text" class="ins" name="ref_no" value="' . $data['ref_no'] . '" />';
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('orderid') . ' </td>';
							echo '<td class="form-input">';
							echo '<span>' . $data['product_id'] . '</span>';
							echo '<input hidden type="text" class="ins" name="product_id" value="' . $data['product_id'] . '" />';
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('noofpiece') . '</td>';
							echo '<td class="form-input">';
							echo '<span>' . $data['quantity'] . '</span>';
							echo '<input hidden type="text" class="ins" name="quantity" value="' . $data['quantity'] . '" />';
							echo '</td>';
							echo '</tr>';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('noofflyers') . '</td>';
							echo '<td class="form-input">';
							echo '<span>' . $data['flyer_qty'] . '</span>';
							echo '<input hidden type="text" class="ins" name="flyer_qty" value="' . $data['flyer_qty'] . '" />';
							echo '</td>';
							echo '</tr>';

							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('itemdetail') . ' </td>';
							echo '<td class="form-input">';
							echo '<span>' . $data['product_desc'] . '</span>';
							echo '<input hidden type="text" class="desc" name="product_desc" value="' . $data['product_desc'] . '" />';
							echo '</td>';
							echo '</tr>';

							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('fragile') . '</td>';
							echo '<td class="form-input">';
							echo '<span>' . ($data['is_fragile'] == 1 ? "Yes" : "No") . '</span>';
							echo '</td>';
							echo '</tr>';
							echo '<tr>';
							echo '<td width="20%">' . getLange('specialinstruction') . ' </td>';
							echo '<td class="form-input">';
							echo '<span>' . $data['special_instruction'] . '</span>';
							echo '<input hidden type="text" class="ins" name="special_instruction" value="' . $data['special_instruction'] . '" />';
							echo '</td>';

							if (isset($data['barcode_image']) && $data['barcode_image'] != '') {
								echo '<tr>';
								echo '<td width="20%">' . getLange('barcode') . '</td>';
								echo '<td class="form-input">';
								echo '<div style="width: 200px; text-align: center;">';
								echo '<span><img src="../' . $data['barcode_image'] . '" /></span><br>';
								echo '<div>' . $data['barcode'] . '</div>';
								echo '</div>';
								echo '</td>';
								echo '</tr>';
							}
							if (isset($data['delivery_assignment_no']) && $data['delivery_assignment_no'] != '') {
								echo '<tr>';
								echo '<td width="20%">Delivery Assignment No.</td>';
								echo '<td class="form-input">';
								echo '<div style="width: 200px; text-align: center;">';
								echo '<span><a href="' . BASE_URL . 'admin/delivery_assignment_sheet.php?assignment_no=' . $data['delivery_assignment_no'] . '">' . $data['delivery_assignment_no'] . '</span>';
								echo '</div>';
								echo '</td>';
								echo '</tr>';
							}

							if (isset($data['order_signature']) && $data['order_signature'] != '') {
								echo '<tr>';
								echo '<td width="20%">' . getLange('signature') . '</td>';
								echo '<td class="form-input">';
								echo '<div style="width: 200px; text-align: center;">';
								echo '<span><img style="width: 70px;" src="./images/order_signature/' . $data['order_signature'] . '" /></span><br>';
								echo '</div>';
								echo '</td>';
								echo '</tr>';
							}
							if (isset($data['reason']) && $data['reason'] != '') {
								echo '<tr>';
								echo '<td width="20%">' . getLange('reason') . '</td>';
								echo '<td class="form-input">';
								echo '<span>' . $data['reason'] . '</span>';
								echo '</td>';
								echo '</tr>';
							}
							echo '<tr>';
							echo '<td width="20%">' . getLange('status') . '</td>';
							echo '<td class="form-input">';

							if ($data['status'] == 'accepted') {
								$statusss = 'Assigned to Rider';
							} else if ($userID == $deliverdriverID) {
								if ($data['status'] == 'in process') {
									$statusss = 'Pickup is done';
								} else if ($data['status'] == 'accepted') {
									$statusss = 'Assigned to Rider';
								} else {
									$statusss = $data['status'];
								}
							} else {
								$statusss = $data['status'];
							}
							if (isset($data['is_returned']) && $data['is_returned'] == 1 && $data['status'] != 'returned') {
								$statusss .= ' (Returned)';
							}
							$status_reason = '';
							if ($data['status_reason'] != '') {
								if ($data['status'] == 'delivered' || $data['status'] == 'Returned to Shipper') {
									if (!empty($data['status_reason'])) {
										$status_reason = '- Received By (' . $data['status_reason'] . ')';
									} else {
										$status_reason = ' (' . $data['status_reason'] . ')';
									}
								} else {
									$status_reason = ' (' . $data['status_reason'] . ')';
								}
							}
							$received_by = '';
							if ($data['received_by'] != '') {
								if ($data['status'] == 'Delivered' || $data['status'] == 'Returned to Shipper') {
									if (!empty($data['received_by'])) {

										$status_reason = '-( Received By ' . $data['received_by'] . ')';
									} else {
										$status_reason = ' (' . $data['received_by'] . ')';
									}
								} else {
									$status_reason = ' (' . $data['received_by'] . ')';
								}
							}
							echo '<span >' . ucfirst($statusss) . $status_reason . $received_by . '</span>';
							// echo '<input hidden type="text" name="status" value="'.$data['status'].'" />';
							echo '</td>';
							echo '</tr>';
							?>
							<?php
							echo '<tr>';
							echo '<td width="20%">' . getLange('action') . '</td>';
							echo '<td>';
							$iddd = encrypt($data['id'] . "-usUSMAN767###");
							echo '<a target="_blank" href="../' . getConfig('print_template') . '?order_id=' . $data['id'] . '&booking=1">View Invoice</a>';
							echo '</td>';
							echo '</tr>';
							if ($userID == $driverID) {
								$query33 = mysqli_query($con, "SELECT * from users where id='" . $deliverdriverID . "' ") or die(mysqli_error($con));
								$fetch33 = mysqli_fetch_array($query33);
								echo '<tr>';
								echo '<td width="20%">' . getLange('ridername') . ' </td>';
								echo '<td class="form-input">';
								echo '<span >' . $fetch33['Name'] . '</span>';
								echo '</td>';
								echo '</tr>';
								echo '<tr>';
								echo '<td width="20%">' . getLange('rideremail') . ' </td>';
								echo '<td class="form-input">';
								echo '<span >' . $fetch33['email'] . '</span>';
								echo '</td>';
								echo '</tr>';
							}

							echo '<tr>';
							echo '<td hidden colspan="2" class="order-buttons">';
							echo '<input type="button" class="btn btn-default reset-form" value="Cancel" />';
							echo '<input type="submit" class="btn btn-success" name="update_order" value="' . getLange('update') . '" />';
							echo '</td>';
							echo '</form>';
							echo '<td colspan="2" class="other-actions">';

							if ($type == 'driver') {
							} else {
								if ($type == 'admin') {
									echo '<a href="editbookingform.php?id=' . $orderID . '" class="btn btn-info pull-right">' . getLange('edit') . '</a>';
								}
							}

							echo '</td>';
							echo '</tr>';

							if ($total_comments > 0) { ?>
								<tr>

									<td width="20%"><?php echo getLange('save'); ?>Order Comments</td>
									<td>
										<table class="table table-bordered">
											<thead>
												<tr>

													<th><?php echo getLange('sendby'); ?>Send By</th>
													<th><?php echo getLange('message'); ?>Message</th>
													<th><?php echo getLange('date'); ?>Date</th>
												</tr>
											</thead>
											<tbody>
												<?php while ($row = mysqli_fetch_array($message_query)) { ?>

													<tr>
														<td><?php echo $row['comment_by']; ?></td>
														<td><?php echo $row['order_comment']; ?></td>
														<td><?php echo date('d M Y h:i A', strtotime($row['created_on'])); ?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</td>
									</td>
								</tr>
						<?php
							}

							echo '</table>';
							echo '</div>';
							echo '</div>';
						} else {
							$ref = $_SERVER['HTTP_REFERER'];
							echo '<script>window.location.href="../admin/"</script>';
						}
						?>
						</div>
					</div>


				<?php

				include "includes/footer.php";
			} else {
				header("location:index.php");
			}
				?>
				<script type="text/javascript">
					$(function() {
						$('.datetimepicker4').datetimepicker({
							format: 'YYYY/MM/DD',
						});
					});
				</script>