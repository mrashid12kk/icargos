<?php

	session_start();

	require 'includes/conn.php';

	if(isset($_SESSION['users_id']) && $_SESSION['type']!=='driver'){

	include "includes/header.php";

?>

<body data-ng-app>

 	

    

	<?php

	

	include "includes/sidebar.php";

	

	?>

    <!-- Aside Ends-->

    

    <section class="content">

    	 

	<?php

	include "includes/header2.php";

	 $customer_list = mysqli_query($con,"SELECT * FROM customers WHERE is_non_cod=1 ");

	   $gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");

  $total_gst = mysqli_fetch_array($gst_query);

	?>

        

        <!-- Header Ends -->

        

        

        <div class="warper container-fluid">

        	

            <div class="row">

            	<div class="col-sm-6" style="padding: 7px 0 0;">

            		<div class="page-header"><h1>Customer Payments <small>Let's get a quick overview...</small>

		               </h1>

		            </div>	

            	</div>

            	<div class="col-sm-6" style="padding: 0;">

            		<div class="form-group pull-right select_customer_box" >

                  <select class="form-control flyer_selecter" onchange="window.location.href='non_cod_ledger_payments.php?customer_id='+this.value" name="customer_id">

                    <option value="">Select Customer</option>

                    <?php while($row_customer = mysqli_fetch_array($customer_list))

                      {  

                        ?>

                          <option <?php if(isset($_GET['customer_id']) && $_GET['customer_id'] == $row_customer['id']){ echo "Selected"; } ?>  value="<?php echo $row_customer['id']; ?>" > <?php echo $row_customer['fname']." (".$row_customer['bname'].")"; ?> </option>

                        <?php 

                      }

                    ?>

                  </select>

                </div>

            	</div>

            </div>

            

          <div class="panel panel-default" style="margin-top: 0; position: relative;">

	<div class="row panel_box_Bg">

		<div class="col-sm-6 payment_title">

			<h3><?php echo getLange('payment'); ?></h3>

		</div>

		<div class="col-sm-6 add_payment_btn">

			<a href="non_cod_bulk_ledger_payment.php" class="btn btn-info pull-right"><?php echo getLange('addpayment'); ?> </a>

		</div>

	</div>

		<div class="panel-body" id="same_form_layout" style="padding: 11px;">

			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

				<div class="row">

					<div class="col-sm-12 table-responsive">

						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 

							<thead>

								<tr>

									<th><?php echo getLange('srno'); ?>.</th>

									<th><?php echo getLange('customer'); ?></th>

									<th><?php echo getLange('paymentid'); ?> </th>

									<th><?php echo getLange('transactionid'); ?> </th>

									<th><?php echo getLange('paymentdate'); ?> </th>

									<th><?php echo getLange('totalshipment'); ?> </th>

									<th><?php echo getLange('totaldeliveries'); ?> </th>

									<th><?php echo getLange('totalreturned'); ?> </th>

									<th><?php echo getLange('totalcodamount'); ?>  </th>

									<th><?php echo getLange('deliverycharges'); ?> </th>

									<th><?php echo getLange('returned'); ?></th>

									<th><?php echo getLange('returnedfee'); ?> </th>

									<th><?php echo getLange('cashhandling'); ?> </th>

									<th><?php echo getLange('gst'); ?> (<?php echo $total_gst['value']; ?>%)</th>

									<th><?php echo getLange('flyers'); ?></th>

									<th><?php echo getLange('totalpayable'); ?> </th>

									<th><?php echo getLange('payment'); ?></th>

									<th><?php echo getLange('balance'); ?></th>

									<th><?php echo getLange('action'); ?></th>

								</tr>

							</thead>

							<tbody>

							<?php

							$where = (isset($_GET['customer_id']) && $_GET['customer_id'] != '') ? "AND customer_id = ".$_GET['customer_id'] : "";

							$sr=1;

								$query1=mysqli_query($con,"SELECT customer_ledger_payments.*, customers.fname as customer, customers.bname as company_name  FROM customer_ledger_payments LEFT JOIN customers ON (customers.id = customer_ledger_payments.customer_id) WHERE customer_ledger_payments.is_non_cod = 1 {$where} order by id DESC");

								while($fetch1=mysqli_fetch_array($query1)){

									$shipments_html = $deliveries_html = $returned_html = $flyers_html = '';

									$orders = ($fetch1['ledger_orders']) ? explode(',', $fetch1['ledger_orders']) : [];

									if(!empty($orders)) {

										$shipments_html = '<ul>';

										foreach ($orders as $ship) {

											$shipments_html .= '<li>'.((int)$ship+20000000).'</li>';

										}

										$shipments_html .= '</ul>';

									}

									$orders = ($fetch1['ledger_delivered']) ? explode(',', $fetch1['ledger_delivered']) : [];

									if(!empty($orders)) {

										$deliveries_html = '<ul>';

										foreach ($orders as $ship) {

											$deliveries_html .= '<li>'.((int)$ship+20000000).'</li>';

										}

										$deliveries_html .= '</ul>';

									}

									$orders = ($fetch1['ledger_returned']) ? explode(',', $fetch1['ledger_returned']) : [];

									if(!empty($orders)) {

										$returned_html = '<ul>';

										foreach ($orders as $ship) {

											$returned_html .= '<li>'.((int)$ship+20000000).'</li>';

										}

										$returned_html .= '</ul>';

									}

									$orders = ($fetch1['ledger_flyers']) ? explode(',', $fetch1['ledger_flyers']) : [];

									if(!empty($orders)) {

										$flyers_html = '<ul>';

										foreach ($orders as $ship) {

											$flyers_html .= '<li>'.sprintf('%04d', $ship).'</li>';

										}

										$flyers_html .= '</ul>';

									}

							?>

								<tr class="gradeA odd" role="row">

									<td><?=$index+1;?></td>

									<td><?=$fetch1['customer']. '('.$fetch1['company_name'].')';?></td>

									<td><?=sprintf('%05d', $fetch1['id']);?></td>

									<td><?=$fetch1['reference_no'];?></td>

									<td><?=date('Y-m-d', strtotime($fetch1['payment_date']));?></td>

									<td><a href="#" title="Shipments" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?=$shipments_html;?>"><?=$fetch1['total_shipments'];?></a></td>

									<td><a href="#" title="Delivered" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?=$deliveries_html;?>"><?=$fetch1['total_delivered'];?></a></td>

									<td><a href="#" title="Returned" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?=$returned_html;?>"><?=$fetch1['total_returned'];?></a></td>

									<td>Rs. <?=number_format($fetch1['cod_amount'], 2);?></td>

									<td>Rs. <?=number_format($fetch1['delivery_charges'], 2);?></td>

									<td>Rs. <?=number_format($fetch1['returned_amount'], 2);?></td>

									<td>Rs. <?=number_format($fetch1['total_returned_fee'], 2);?></td>

									<td>Rs. <?=number_format($fetch1['cash_handling'], 2);?></td>

									<td>Rs. <?=number_format($fetch1['gst_amount'], 2);?></td>

									<td>Rs. <?=number_format($fetch1['sell_flyers_amount'], 2);?> (<a href="#" title="Flyers" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?=$flyers_html;?>"><?=$fetch1['total_sell_flyers']; ?></a>)</td>

									<td>Rs. <?=number_format($fetch1['total_payable'], 2);?></td>

									<td>Rs. <?=number_format($fetch1['total_paid'], 2);?></td>

									<td>Rs. <?=number_format(((float)$fetch1['total_payable'] - (float)$fetch1['total_paid']), 2);?></td>

									<td class="action_btns">

										<a  target="_blank" href="ledger_payment_view.php?payment_id=<?php echo $fetch1['id']; ?>"> <i class="fa fa-eye" style="font-size: 14px;"></i></a>

										<?php if($fetch1['update_able'] == 1): ?>

											<a href="submit_bulk_ledger_payment.php?delete=<?=$fetch1['id'];?>&customer_id=<?=$fetch1['customer_id'];?>" > <i style="color: #da1414;font-size: 14px;" class="fa fa-trash"></i></a>

										<?php endif; ?>

									</td>

								</tr>

								<?php

								$sr++;

								}

								

								?>

							</tbody>

						</table>

				</div>

			</div>

		</div>

	</div>

</div>

					

            

        </div>

        <!-- Warper Ends Here (working area) -->

        

        

      <?php

	

	include "includes/footer.php";

	}

	else{

		header("location:index.php");

	}

	?>