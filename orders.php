<?php
session_start();
include_once "includes/conn.php";
$id = $_SESSION['customers'];


function decrypt($string)
{
	$key = "usmannnn";
	$result = '';
	$string = base64_decode($string);
	for ($i = 0; $i < strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key)) - 1, 1);
		$char = chr(ord($char) - ord($keychar));
		$result .= $char;
	}
	return $result;
}
if (isset($_POST["page"])) {
	//sanitize post value
	$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	//throw HTTP error if page number is not valid
	if (!is_numeric($page_number)) {
		header('HTTP/1.1 500 Invalid page number!');
		exit;
	}
	$item_per_page = 8;
	//get current starting point of records
	$position = (($page_number - 1) * $item_per_page);
	$query2 = mysqli_query($con, "SELECT * FROM orders WHERE customer_id=$id ORDER BY id DESC LIMIT $position, $item_per_page") or die(mysqli_error($con));
	if ($query2) {
		while ($row = mysqli_fetch_object($query2)) {
			$statusss = isset($row->status) ? $row->status : 'Pending';
			if ($statusss == 'in process' || $statusss == 'accepted') {
				$statusss = 'On the Way';
			}
?>
			<li class="bdr-btm">
				<div class="open_first_order">
					<a href="#">
						<b><?php echo getLange('order'); ?># <?php echo $row->track_no; ?></b>
						<b><?php echo getLange('pickUp_date'); ?>: <?php echo $row->order_date; ?> <i class="fa fa-angle-down"></i></b>
						<b><?php echo getLange('status'); ?>: <?php echo getKeyWord($statusss); ?></b>
					</a>
				</div>
				<div class="down_box_order">
					<ul>
						<li><i class="fa fa-check"></i> <strong><?php echo getLange('order'); ?>#</strong> <?php echo $row->track_no; ?></li>
						<li><i class="fa fa-check"></i> <strong><?php echo getLange('status'); ?>:</strong> <?php echo getKeyWord($row->status); ?></li>
						<li><i class="fa fa-check"></i> <strong><?php echo getLange('orderdate'); ?></strong> <?php echo $row->order_date; ?></li>
						<li><i class="fa fa-check"></i> <strong><?php echo getLange('collectionamount'); ?>:</strong> <?php echo $row->collection_amount; ?></li>
						<li><i class="fa fa-check"></i> <strong><?php echo getLange('price'); ?>: </strong> <?php echo $row->price; ?></li>
						<li><i class="fa fa-check"></i> <strong><?php echo getLange('total'); ?>: </strong> <?php echo ((int)$row->collection_amount + (int)$row->price); ?></li>
					</ul>
				</div>
			</li>
	<?php
		}
	}
	exit();
}
$message = "";
//cancel order

$order_status = '';
$active_order_status = '';
$check_other = '';
$date_range = "";
$status_date_query = "";
$status_date_from = "";
$status_date_to = "";
$order_date_query = "";
if (isset($_GET['order_status'])) {
	$order_status = $_GET['order_status'];
	$active_order_status  = $order_status;
	$order_status = " AND status= '" . $order_status . "' ";
}

if ((isset($_GET['status_date_from']) && !empty($_GET['status_date_from'])) && (isset($_GET['status_date_to']) && (!empty($_GET['status_date_to'])))) {
	$status_date_from = date('Y-m-d', strtotime($_GET['status_date_from']));
	$status_date_to = date('Y-m-d', strtotime($_GET['status_date_to']));

	$status_date_query = " AND DATE_FORMAT(`action_date`, '%Y-%m-%d') >= '" . $status_date_from . "' AND  DATE_FORMAT(`action_date`, '%Y-%m-%d') <= '" . $status_date_to . "' ";
}
if ((isset($_GET['from']) && !empty($_GET['from'])) && (isset($_GET['to']) && !empty($_GET['to']))) {
	$from = date('Y-m-d', strtotime($_GET['from']));
	$to = date('Y-m-d', strtotime($_GET['to']));
	$order_date_query = " AND DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '" . $to . "' ";
}

if (isset($_GET['orderss_status']) && !empty($_GET['orderss_status'])) {
	$order_status = $_GET['orderss_status'];
	$active_order_status = $_GET['orderss_status'];
	$order_status = " AND status= '" . $order_status . "' ";
}

$query1 = mysqli_query($con, "SELECT * FROM orders WHERE customer_id =" . $id . " $order_date_query  $order_status $status_date_query order by id desc ");



if (isset($_SESSION['customers'])) {
	include "includes/header.php";


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
	// $page_title = 'Request Details';
	$is_profile_page = true;
	$status_q = mysqli_query($con, "SELECT * FROM order_status WHERE 1 ORDER BY sort_num");
	?>
	<section class="bg padding30">
		<div class="container-fluid dashboard">
			<div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
				<?php
				include "includes/sidebar.php";
				?>
			</div>
			<div class="col-lg-10 col-md-9 col-sm-8 padd_none_all">
				<div class="">
					<?php
					// $query1=mysqli_query($con,"select * from orders where customer_id=$id order by id desc") or die(mysqli_error($con));
					// $query2=mysqli_query($con,"select * from orders where customer_id=$id order by id desc") or die(mysqli_error($con));

					?>
					<style type="text/css">
						table th {
							color: #8f8f8f;
						}

						.table-bordered tr td {
							color: #000;
						}

						section .dashboard .white {
							background: #fff;
							padding: 20px;
							box-shadow: 0 0 3px #ccc;
							width: 99%;
							display: inline-block;
						}

						.btn-default {
							min-width: 60px;
						}

						@media (max-width: 1250px) {
							.container {
								width: 100%;
							}

							.submit_load {
								margin-top: 20px !important;
							}
						}

						@media (max-width: 1024px) {
							.container {
								width: 100%;
							}

							.padding30 .dashboard {
								margin-top: 0 !important;
								padding: 0 12px 30px;
							}

							.dashboard .white {
								padding: 0 !important;
							}

							.white .col-sm-4 {
								width: 50%;
								float: left;
								margin-bottom: 11px;
								padding: 0;
							}

							section .dashboard .white {
								box-shadow: none !important;
							}

						}

						@media (max-width: 767px) {
							.container {
								width: auto;
							}

							.white .col-sm-4 {
								width: 100%;
								float: none;
								margin-bottom: 11px;
								padding: 0;
							}

							section .dashboard .dashboard {
								padding: 3px 0 0;
							}



						}

						.print_invoice {
							color: #fff;
						}

						.print_invoice:hover,
						.print_invoice:focus {
							color: #fff !important;
						}

						.ready_for_pickup {
							color: #fff;
						}

						.ready_for_pickup:hover,
						.ready_for_pickup:focus {
							color: #fff !important;
						}

						.btn-danger,
						.btn-danger:hover {
							color: #fff !important;
						}

						.view_invoice {
							background-color: #4cade0 !important;
							border: none !important;
						}

						.btn-sm {
							padding: 0px 6px !important;
							font-size: 12px !important;
							line-height: 1.5 !important;
							border-radius: 3px !important;
							margin: 2px 0;
						}

						.buttons-print {
							display: none;
						}
					</style>
					<?php
					if (isset($_GET['message']) && !empty($_GET['message'])) {
						echo $_GET['message'];
					}
					?>
					<h4 class="Order_list" style="color:#000;"><?php echo getLange('requestdetail'); ?></h4>

					<?php
					if (isset($_SESSION['return_msg']) && !empty($_SESSION['return_msg'])) {
						$msg = $_SESSION['return_msg'];
						echo $msg;
						unset($_SESSION['return_msg']);
					}
					?>
					<form method="GET" action="" class="booking_sheet_form">
						<div class="row order_status_box">
							<div class="col-sm-1 left_right_none" style="margin-top: 20px;">
								<a href="#" class="btn btn-info print_invoice" style="color: #fff;    margin-bottom: 10px;"><?php echo getLange('print'); ?></a>


							</div>
							<div class="col-sm-2 left_right_none">
								<div class="form-group">
									<label><?php echo getLange('status'); ?></label>
									<select class="form-control js-example-basic-single" name="order_status">
										<option selected disabled><?php echo getLange('select') . ' ' . getLange('status'); ?> </option>
										<?php while ($row = mysqli_fetch_array($status_q)) { ?>
											<option value="<?php echo $row['status']; ?>" <?php if ($active_order_status == $row['status']) {
																								echo "selected";
																							} ?>><?php echo $row['status']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sm-2 left_right_none">
								<div class="form-group">
									<label><?php echo getLange('orderdatefrom'); ?> </label>
									<input type="text" value="<?php echo isset($from) ? $from : ''; ?>" class="form-control datepicker" name="from">
								</div>
							</div>
							<div class="col-sm-2 left_right_none">
								<div class="form-group">
									<label><?php echo getLange('orderdateto'); ?> </label>
									<input type="text" value="<?php echo isset($to) ? $to : ''; ?>" class="form-control datepicker" name="to">
								</div>
							</div>
							<div class="col-sm-2 left_right_none">
								<div class="form-group">
									<label><?php echo getLange('statusdatefrom'); ?> </label>
									<input type="text" value="<?php echo isset($status_date_from) ? $status_date_from : ''; ?>" class="form-control datepicker" name="status_date_from">
								</div>
							</div>
							<div class="col-sm-2 left_right_none">
								<div class="form-group">
									<label><?php echo getLange('statusdateto'); ?> </label>
									<input type="text" value="<?php echo isset($status_date_to) ? $status_date_to : ''; ?>" class="form-control datepicker" name="status_date_to">
								</div>
							</div>
							<div class="col-sm-1 left_right_none">
								<input type="submit" style="margin-top: 20px; color: #fff !important;" name="submit" class="btn btn-info submit_load" value="<?php echo getLange('submit'); ?>">
							</div>

							<div class="col-sm-1 left_right_none">

							</div>

							<?php
							$pickupData = mysqli_fetch_object(mysqli_query($con, "SELECT * FROM order_status WHERE sts_id = '1000' "));
							?>

							<?php if (isset($pickupData->status)) : ?>
								<a href="#" name="ready_for_pickup" class="btn btn-info ready_for_pickup" style="color: #fff;margin-bottom: 10px;"><?php echo $pickupData->status; ?></a>
							<?php endif ?>
							<a href="#" class="btn btn-info print_small_invoice" style="margin-bottom: 10px;"><?php echo getLange('labelprint'); ?></a>
						</div>
					</form>

					<table class="table table-hover table-bordered dataTable hide-on-tab orders_tbl">
						<thead>
							<tr>
								<th><input type="checkbox" name="" class="main_select"></th>
								<th><?php echo getLange('date'); ?></th>
								<th><?php echo getLange('trackingon'); ?> </th>
								<th><?php echo getLange('pickupinfo'); ?> </th>
								<th><?php echo getLange('deliveryinfo'); ?> </th>
								<th><?php echo getLange('qty'); ?></th>
								<th><?php echo getLange('ordertype'); ?></th>
								<th><?php echo getLange('pickupcity'); ?> </th>
								<th><?php echo getLange('deliverycity'); ?> </th>
								<th><?php echo getLange('weight'); ?></th>
								<th><?php echo getLange('codamount'); ?> </th>
								<th><?php echo getLange('paymentstatus'); ?> </th>
								<th><?php echo getLange('orderstatus'); ?> </th>
								<th><?php echo getLange('orderid'); ?> </th>
								<th><?php echo getLange('statusdate'); ?> </th>
								<th><?php echo getLange('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							while ($fetch1 = mysqli_fetch_array($query1)) {
								$iddd = encrypt($fetch1['id'] . "-usUSMAN767###");
								$status = $fetch1['status'];
								if ($status == 'assigned') {
									$status = 'Assigned to Transco Logistics Pk';
								}

								if (!empty($fetch1['status_reason']) && $status != 'delivered') {
									$status = $status . ' (' . $fetch1['status_reason'] . ')';
								}
							?>

								<tr>
									<td><input type="checkbox" name="" class="order_check" data-id="<?php echo $fetch1['id']; ?>"></td>
									<td><?php echo date(DATE_FORMAT, strtotime($fetch1['order_date'])); ?></td>
									<td><?php echo $fetch1['track_no']; ?></td>

									<td>
										<b><?php echo getLange('accountname'); ?> :</b><?php echo $fetch1['sname']; ?></br>
										<b><?php echo getLange('company'); ?>:</b><?php echo $fetch1['sbname']; ?></br>
										<b><?php echo getLange('phone'); ?>:</b><?php echo $fetch1['sphone']; ?></br>
										<b><?php echo getLange('orderid'); ?> :</b><?php echo $fetch1['tracking_no']; ?></br>

									</td>
									<td>
										<b><?php echo getLange('name'); ?>:</b><?php echo $fetch1['rname']; ?></br>
										<b><?php echo getLange('phone'); ?>:</b><?php echo $fetch1['rphone']; ?></br>

									</td>
									<td>
										<?php echo $fetch1['quantity']; ?>
									</td>
									<td>
										<?php if ($fetch1['booking_type'] == '2') {
											echo 'Cash';
										} elseif ($fetch1['booking_type'] == '3') {
											echo  'To Pay';
										} else {
											echo 'Invoice';
										} ?>
									</td>
									<td>
										<?php echo $fetch1['origin']; ?>
									</td>
									<td>
										<?php echo $fetch1['destination']; ?>
									</td>
									<td>
										<?php echo $fetch1['weight']; ?> Kg
									</td>
									<td>
										<?php echo $currency['value'] ?> <?php echo $fetch1['collection_amount']; ?>
									</td>
									<td>
										<?php if ($fetch1['payment_status'] == 'Paid') { ?>
											<span class="badge badge-success" style="background: #4cb034;">Cleared</span>
										<?php } else { ?>
											<span class="badge badge-info">Pending</span>
										<?php } ?>
									</td>

									<td style="text-transform: capitalize;">
										<?php if ($fetch1['status'] == 'delivered') { ?>
											<b style="color: green;"><?php echo $status; ?></b>
										<?php } elseif ($fetch1['status'] == 'returned') { ?>
											<b style="color: #caca09;"><?php echo $status; ?></b>
										<?php } elseif ($fetch1['status'] == 'cancelled') { ?>
											<b style="color: red;"><?php echo $status; ?></b>
										<?php } else { ?>
											<b style="color: #9bd4e5;"><?php echo $status; ?></b>
										<?php } ?>
									</td>
									<td>
										<?php echo $fetch1['product_id']; ?>
									</td>
									<td><?php echo date(DATE_FORMAT, strtotime($fetch1['action_date'])); ?></td>
									<td>
										<?php if ($fetch1['status'] == 'New Booked') { ?><a target="_blank" title="view order" href="edituserbooking.php?id=<?php echo $fetch1['id']; ?>" class="btn btn-info"> <span class="glyphicon glyphicon-edit"></span></a>
										<?php } ?>
										<?php if ($fetch1['status'] != 'cancelled') { ?>
											<a target="_blank" href="<?php echo getConfig('print_template'); ?>?order_id=<?php echo $fetch1['id']; ?>&print=1&booking=1" class="btn btn-info btn-sm view_invoice"><?php echo getLange('viewinvoice'); ?> </a>


											<?php if ($fetch1['status'] == 'New Booked') { ?>
												<a href="cancel_order.php?cancel_id=<?php echo $iddd; ?>" class="btn-sm btn btn-danger cancel_order"><?php echo getLange('cancelorder'); ?> </a>
											<?php } ?>

											<a target="_blank" href="track-details.php?track_code=<?php echo $fetch1['track_no'] ?>" class="btn btn-info btn-sm track_order"><?php echo getLange('trackingno'); ?> </a>
											<!-- <a target="_blank" href="#" class="btn btn-info btn-sm ">Order List</a> -->
										<?php } ?>
									</td>
								</tr>
							<?php
							}
							?>
						</tbody>
					</table>

					<form method="GET" id="bulk_submit" action="<?php echo getConfig('print_template'); ?>" target="_blank">
						<input type="hidden" name="order_id" id="print_data">
						<input type="hidden" name="save_print">
					</form>
					<form method="GET" id="small_bulk_submit" action="small_bulk_invoice.php" target="_blank">
						<input type="hidden" name="print_data" id="small_print_data">
						<input type="hidden" name="save_print">
					</form>

					<div class="order_info-details">
						<ul id="results"></ul>
					</div>

				</div>
			</div>
		</div>
	</section>
	</div>
<?php

} else {
	header("location:index.php");
}
?>
<script type="text/javascript" src="js/ajax_load_data.js"></script>
<script type="text/javascript">
	$('.datepicker').datepicker({
		format: 'yyyy/mm/dd',
	});
	(function($) {
		$("body").on('click', ".open_first_order a", function() {
			$(this).closest('li').find('.down_box_order').slideToggle();
		});

		if ($('#results').length > 0) {
			$("#results").loaddata({
				data_url: 'orders.php',
				end_record_text: ''
			});
		}
	})(jQuery);
	$('body').on('click', '.main_select', function(e) {
		var check = $('.orders_tbl').find('tbody > tr > td:first-child .order_check');
		if ($('.main_select').prop("checked") == true) {
			$('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked', true);
		} else {
			$('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked', false);
		}

		$('.orders_tbl').find('tbody > tr > td:first-child .order_check').val();
	})
	var mydata = [];
	$('body').on('click', '.print_invoice', function(e) {
		e.preventDefault();
		$('.orders_tbl > tbody  > tr').each(function() {
			var checkbox = $(this).find('td:first-child .order_check');
			if (checkbox.prop("checked") == true) {
				var order_id = $(checkbox).data('id');
				mydata.push(order_id);
			}
		});
		var order_data = mydata.join(',');

		$('#print_data').val("");
		$('#print_data').val(order_data);
		$('#bulk_submit').submit();
		location.reload();
	});
	$('body').on('click', '.print_small_invoice', function(e) {
		e.preventDefault();
		$('.orders_tbl > tbody  > tr').each(function() {
			var checkbox = $(this).find('td:first-child .order_check');
			console.log(checkbox);
			if (checkbox.prop("checked") == true) {
				var order_id = $(checkbox).data('id');
				mydata.push(order_id);
			}
		});
		var order_data = mydata.join(',');

		$('#small_print_data').val(order_data);
		$('#small_bulk_submit').submit();
		location.reload();
	});



	$('body').on('click', '.ready_for_pickup', function(e) {

		e.preventDefault();
		$('.orders_tbl > tbody  > tr').each(function() {
			var checkbox = $(this).find('td:first-child .order_check');
			if (checkbox.prop("checked") == true) {
				var order_id = $(checkbox).data('id');
				mydata.push(order_id);
			}
		});
		var order_data = mydata.join(',');

		$.ajax({
			url: "edit_ready_for_pickup.php",
			type: "post",
			dataType: 'json',
			data: {
				order_ids: order_data
			},
			success: function(result) {
				location.reload();
			}
		});

	});
</script>

<?php include 'includes/footer.php'; ?>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		$('title').text($('title').text() + ' Orders')
	}, false);
</script>