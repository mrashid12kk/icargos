<?php
session_start();
$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

require 'includes/conn.php';
if (isset($_SESSION['customers'])) {
	require_once "includes/role_helper.php";
	if (!checkRolePermission(6, 'view_only', '')) {

		header("location:access_denied.php");
	}
	if (!isset($_GET['print'])) {
		include "includes/header.php";
	}

	$customer_id = $_SESSION['customers'];
?>
	<?php if (isset($_GET['print'])) { ?>
		<!DOCTYPE html>
		<html>

		<head>
			<title>Transco Logistics We Store, Pack and Ship for Pakistan-Based Online Stores</title>
			<link href="bootstrap/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css" />
		</head>

		<body>
		<?php } ?>
		<section class="bg padding30">
			<div class="container-fluid dashboard">
				<div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
					<?php
					if (!isset($_GET['print'])) {
						include "includes/sidebar.php";
					}
					?>
				</div>
				<div class="col-lg-10 col-md-9 col-sm-8 dashboard" style="    padding-top: 0;">
					<div class="white" style="    padding-top: 0;">
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
							}

							@media (max-width: 1024px) {
								.container {
									width: 100%;
								}

								.padding30 .dashboard {
									margin-top: 20px !important;
								}

								.padding30 .dashboard {
									margin-top: 0 !important;
									padding: 0 12px 30px;
								}

								.dashboard .white {
									padding: 0 !important;
								}

								section .dashboard .white {
									box-shadow: none !important;
								}


							}

							@media (max-width: 767px) {
								.container {
									width: auto;
								}


							}

							.white .col-sm-4 {
								width: 100%;
								float: none;
								margin-bottom: 11px;
								padding: 0;
							}

							section .dashboard .dashboard {
								padding: 26px 0 0;
							}

							.print_invoice {
								color: #fff;
							}

							.print_invoice:hover {
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
								padding: 2px 4px !important;
								font-size: 10px !important;
								line-height: 1.5 !important;
								border-radius: 3px !important;
							}

							.print_btn {
								color: #fff !important;
							}

							.print_btn:hover,
							.print_btn:focus {
								color: #fff !important;
							}

							table.table-bordered.dataTable th,
							table.table-bordered.dataTable td {
								border-left-width: 0;
								font-size: 11px;
								padding: 8px 7px;

							}

							@media print {

								table.table-bordered.dataTable th,
								table.table-bordered.dataTable td {
									border: 1px solid #a2a1a1;
								}
							}
						</style>
						<?php
						if (isset($_GET['message']) && !empty($_GET['message'])) {
							echo $_GET['message'];
						}
						?>


						<!-- Payment Clearance (COD Account) Starts -->



						<h4 class="Order_list" style="color:#000;"><?php echo getLange('paymentclearancecod'); ?></h4>
						<?php if (!isset($_GET['print'])) { ?>
							<a style="margin-bottom: 14px;" href="<?php echo $url ?>?print=1" class="print_btn btn btn-info" target="_blank"><?php echo getLange('print'); ?></a>
						<?php } else { ?>
							<script type="text/javascript">
								window.print();
							</script>
						<?php } ?>
						<?php if (isset($_GET['print'])) { ?>
							<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="basic-datatable_info">
							<?php } else { ?>
								<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
								<?php } ?>
								<thead>
									<tr>
										<th><?php echo getLange('srno'); ?>.</th>
										<th><?php echo getLange('paymentid'); ?> </th>
										<th><?php echo getLange('chequenotransactionid'); ?> </th>
										<th><?php echo getLange('Invoice Date'); ?> </th>
										<th><?php echo getLange('totalshipment'); ?> </th>
										<th><?php echo getLange('totaldeliveries'); ?> </th>
										<!-- <th><?php echo getLange('totalreturned'); ?> </th> -->
										<th><?php echo getLange('totalcodamount'); ?> </th>
										<th><?php echo getLange('deliverycharges'); ?> </th>
										<!-- <th><?php echo getLange('returned'); ?></th> -->
										<!-- <th><?php echo getLange('returnedfee'); ?> </th> -->
										<!-- <th><?php echo getLange('cashhandling'); ?> </th> -->
										<th><?php echo getLange('gst'); ?></th>
										<th><?php echo getLange('flyers'); ?></th>
										<th><?php echo getLange('totalpayable'); ?> </th>
										<th><?php echo getLange('payment'); ?></th>
										<th><?php echo getLange('balance'); ?></th>
										<?php if (!isset($_GET['print'])) { ?>
											<th><?php echo getLange('action'); ?></th>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<?php
									$sr = 1;
									$query1 = mysqli_query($con, "SELECT customer_ledger_payments.*, customers.fname as customer  FROM customer_ledger_payments LEFT JOIN customers ON (customers.id = customer_ledger_payments.customer_id) WHERE customer_ledger_payments.customer_id = " . $customer_id . " order by id DESC");

									while ($fetch1 = mysqli_fetch_array($query1)) {
										$shipments_html = $deliveries_html = $returned_html = $flyers_html = '';
										$orders = ($fetch1['ledger_orders']) ? explode(',', $fetch1['ledger_orders']) : [];
										if (!empty($orders)) {
											$shipments_html = '<ul>';
											foreach ($orders as $ship) {
												$shipments_html .= '<li>' . ((int)$ship + 20000000) . '</li>';
											}
											$shipments_html .= '</ul>';
										}
										$orders = ($fetch1['ledger_delivered']) ? explode(',', $fetch1['ledger_delivered']) : [];
										if (!empty($orders)) {
											$deliveries_html = '<ul>';
											foreach ($orders as $ship) {
												$deliveries_html .= '<li>' . ((int)$ship + 20000000) . '</li>';
											}
											$deliveries_html .= '</ul>';
										}
										// $orders = ($fetch1['ledger_returned']) ? explode(',', $fetch1['ledger_returned']) : [];
										// if(!empty($orders)) {
										// 	$returned_html = '<ul>';
										// 	foreach ($orders as $ship) {
										// 		$returned_html .= '<li>'.((int)$ship+20000000).'</li>';
										// 	}
										// 	$returned_html .= '</ul>';
										// }
										$orders = ($fetch1['ledger_flyers']) ? explode(',', $fetch1['ledger_flyers']) : [];
										if (!empty($orders)) {
											$flyers_html = '<ul>';
											foreach ($orders as $ship) {
												$flyers_html .= '<li>' . sprintf('%04d', $ship) . '</li>';
											}
											$flyers_html .= '</ul>';
										}
									?>
										<tr class="gradeA odd" role="row">
											<td><?= $sr; ?></td>
											<td><?= sprintf('%05d', $fetch1['id']); ?></td>
											<td><?= $fetch1['reference_no']; ?></td>
											<td><?= date('Y-m-d', strtotime($fetch1['payment_date'])); ?></td>
											<td><a href="#" title="Shipments" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?= $shipments_html; ?>"><?= $fetch1['total_shipments']; ?></a></td>
											<td><a href="#" title="Delivered" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?= $deliveries_html; ?>"><?= $fetch1['total_delivered']; ?></a></td>
											<!-- <td><a href="#" title="Returned" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?= $returned_html; ?>"><?= $fetch1['total_returned']; ?></a></td> -->
											<td>Rs. <?= number_format($fetch1['cod_amount'], 2); ?></td>
											<td>Rs. <?= number_format($fetch1['delivery_charges'], 2); ?></td>
											<!-- <td>Rs. <?= number_format($fetch1['returned_amount'], 2); ?></td> -->
											<!-- <td>Rs. <?= number_format($fetch1['total_returned_fee'], 2); ?></td> -->
											<!-- <td>Rs. <?= number_format($fetch1['cash_handling'], 2); ?></td> -->
											<td>Rs. <?= number_format($fetch1['gst_amount'], 2); ?></td>
											<td>Rs. <?= number_format($fetch1['sell_flyers_amount'], 2); ?> (<a href="#" title="Flyers" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?= $flyers_html; ?>"><?= $fetch1['total_sell_flyers']; ?></a>)</td>
											<td>Rs. <?= number_format($fetch1['total_payable'], 2); ?></td>
											<td>Rs. <?= number_format($fetch1['total_paid'], 2); ?></td>
											<td>Rs. <?= number_format(((float)$fetch1['total_payable'] - (float)$fetch1['total_paid']), 2); ?></td>
											<?php if (!isset($_GET['print'])) { ?>
												<td>
													<a style="width: 100%;" target="_blank" class="btn btn-info btn-sm" href="<?php if (isset($_SESSION['customer_type']) && !empty($_SESSION['customer_type']) && $_SESSION['customer_type'] == 2) { ?>admin/non_ledger_payment_view.php?payment_id=<?php echo $fetch1['id']; } else { ?> admin/ledger_payment_view.php?payment_id=<?php echo $fetch1['id']; } ?> ">View</a>
												</td>
											<?php } ?>
										</tr>
									<?php
										$sr++;
									}

									?>
								</tbody>
								</table>

								<!-- Payment Clearance (COD Account) Ends -->


								<!-- Invoices (Non COD Account) Starts -->



								<h4 class="Order_list" style="color:#000;"><?php echo getLange('invoicecod'); ?></h4>
								<!-- 	  <?php if (!isset($_GET['print'])) { ?>
<a  style="margin-bottom: 14px;" href="<?php echo $url ?>?print=1"  class="print_btn btn btn-info" target="_blank" ><?php echo getLange('print'); ?></a>
<?php } else { ?>
  <script type="text/javascript">
  window.print();
</script>
  <?php } ?> -->
								<?php if (isset($_GET['print'])) { ?>
									<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="basic-datatable_info">
									<?php } else { ?>
										<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
										<?php } ?>
										<thead>
											<tr>
												<th><?php echo getLange('srno'); ?>.</th>
												<th><?php echo getLange('paymentid'); ?> </th>
												<th><?php echo getLange('chequenotransactionid'); ?> </th>
												<th><?php echo getLange('paymentdate'); ?> </th>
												<th><?php echo getLange('totalshipment'); ?> </th>
												<th><?php echo getLange('totaldeliveries'); ?> </th>
												<!-- <th><?php echo getLange('totalreturned'); ?> </th> -->
												<th><?php echo getLange('totalcodamount'); ?> </th>
												<th><?php echo getLange('deliverycharges'); ?> </th>
												<!-- <th><?php echo getLange('returned'); ?></th> -->
												<!-- <th><?php echo getLange('returnedfee'); ?> </th> -->
												<!-- <th><?php echo getLange('cashhandling'); ?> </th> -->
												<th><?php echo getLange('gst'); ?></th>
												<th><?php echo getLange('flyers'); ?></th>
												<th><?php echo getLange('totalpayable'); ?> </th>
												<th><?php echo getLange('payment'); ?></th>
												<th><?php echo getLange('balance'); ?></th>
												<?php if (!isset($_GET['print'])) { ?>
													<th><?php echo getLange('action'); ?></th>
												<?php } ?>
											</tr>
										</thead>
										<tbody>
											<?php
											$sr = 1;
											$query1 = mysqli_query($con, "SELECT non_customer_ledger_payments.*, customers.fname as customer  FROM non_customer_ledger_payments LEFT JOIN customers ON (customers.id = non_customer_ledger_payments.customer_id) WHERE non_customer_ledger_payments.customer_id = " . $customer_id . " order by id DESC");

											while ($fetch1 = mysqli_fetch_array($query1)) {
												$shipments_html = $deliveries_html = $returned_html = $flyers_html = '';
												$orders = ($fetch1['ledger_orders']) ? explode(',', $fetch1['ledger_orders']) : [];
												if (!empty($orders)) {
													$shipments_html = '<ul>';
													foreach ($orders as $ship) {
														$shipments_html .= '<li>' . ((int)$ship + 20000000) . '</li>';
													}
													$shipments_html .= '</ul>';
												}
												$orders = ($fetch1['ledger_delivered']) ? explode(',', $fetch1['ledger_delivered']) : [];
												if (!empty($orders)) {
													$deliveries_html = '<ul>';
													foreach ($orders as $ship) {
														$deliveries_html .= '<li>' . ((int)$ship + 20000000) . '</li>';
													}
													$deliveries_html .= '</ul>';
												}
												// $orders = ($fetch1['ledger_returned']) ? explode(',', $fetch1['ledger_returned']) : [];
												// if(!empty($orders)) {
												// 	$returned_html = '<ul>';
												// 	foreach ($orders as $ship) {
												// 		$returned_html .= '<li>'.((int)$ship+20000000).'</li>';
												// 	}
												// 	$returned_html .= '</ul>';
												// }
												$orders = ($fetch1['ledger_flyers']) ? explode(',', $fetch1['ledger_flyers']) : [];
												if (!empty($orders)) {
													$flyers_html = '<ul>';
													foreach ($orders as $ship) {
														$flyers_html .= '<li>' . sprintf('%04d', $ship) . '</li>';
													}
													$flyers_html .= '</ul>';
												}
											?>
												<tr class="gradeA odd" role="row">
													<td><?= $sr; ?></td>
													<td><?= sprintf('%05d', $fetch1['id']); ?></td>
													<td><?= $fetch1['reference_no']; ?></td>
													<td><?= date('Y-m-d', strtotime($fetch1['payment_date'])); ?></td>
													<td><a href="#" title="Shipments" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?= $shipments_html; ?>"><?= $fetch1['total_shipments']; ?></a></td>
													<td><a href="#" title="Delivered" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?= $deliveries_html; ?>"><?= $fetch1['total_delivered']; ?></a></td>
													<!-- <td><a href="#" title="Returned" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?= $returned_html; ?>"><?= $fetch1['total_returned']; ?></a></td> -->
													<td>Rs. <?= number_format($fetch1['cod_amount'], 2); ?></td>
													<td>Rs. <?= number_format($fetch1['delivery_charges'], 2); ?></td>
													<!-- <td>Rs. <?= number_format($fetch1['returned_amount'], 2); ?></td> -->
													<!-- <td>Rs. <?= number_format($fetch1['total_returned_fee'], 2); ?></td> -->
													<!-- <td>Rs. <?= number_format($fetch1['cash_handling'], 2); ?></td> -->
													<td>Rs. <?= number_format($fetch1['gst_amount'], 2); ?></td>
													<td>Rs. <?= number_format($fetch1['sell_flyers_amount'], 2); ?> (<a href="#" title="Flyers" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?= $flyers_html; ?>"><?= $fetch1['total_sell_flyers']; ?></a>)</td>
													<td>Rs. <?= number_format($fetch1['total_payable'], 2); ?></td>
													<td>Rs. <?= number_format($fetch1['total_paid'], 2); ?></td>
													<td>Rs. <?= number_format(((float)$fetch1['total_payable'] - (float)$fetch1['total_paid']), 2); ?></td>
													<?php if (!isset($_GET['print'])) { ?>
														<td>
															<a style="width: 100%;" target="_blank" class="btn btn-info btn-sm" href="<?php if (isset($_SESSION['customer_type']) && !empty($_SESSION['customer_type']) && $_SESSION['customer_type'] == 1) { ?>admin/non_ledger_payment_view.php?payment_id=<?php echo $fetch1['id'];
																																																																										} else { ?> admin/ledger_payment_view.php?payment_id=<?php echo $fetch1['id'];
																																																																																													} ?> ">View</a>
														</td>
													<?php } ?>
												</tr>
											<?php
												$sr++;
											}

											?>
										</tbody>
										</table>

										<!-- Invoices (Non COD Account) Ends -->
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

	<?php
	if (!isset($_GET['print'])) {
		include 'includes/footer.php';
	}
	?>
	<script type="text/javascript">
		jQuery(document).bind("keyup keydown", function(e) {
			e.preventDefault();
			if (e.ctrlKey && e.keyCode == 80) {
				$('.print_btn').trigger('click');
			}
		});
		$('body').on('click', '.print_btn', function(e) {
			e.preventDefault();
			var invoice = $(this).attr('href');
			window.open(invoice, 'mywindow', 'width = 800, height = 800');
		})
	</script>
	<?php if (isset($_GET['print'])) { ?>
		</body>
	<?php } ?>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			$('title').text($('title').text() + ' Ledger Payments')
		}, false);
	</script>