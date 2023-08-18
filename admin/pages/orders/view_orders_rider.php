<?php

$customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");

$rider_id = $_SESSION['users_id'];
$filter_query = "";
$active_tracking = "";
$active_customer_name = "";
$active_customer_phone = "";
$active_customer_email = "";
$active_customer_id = "";
$pickup_rider = "";
$delivery_rider = "";
$active_order_status = "";
$active_order_city = "";

$status_query=mysqli_query($con,"Select * from order_status where sts_id = 2 AND  active='1'");
$record = mysqli_fetch_array($status_query);

$status = $record['status'];

if(isset($_POST['submit']))
{
		if(isset($_POST['tracking_no']) && !empty($_POST['tracking_no'])){
			$filter_query .= " AND order_no = '".$_POST['tracking_no']."' ";
			$active_tracking = $_POST['tracking_no'];
		}
		// if(isset($_POST['customer_name']) && !empty($_POST['customer_name'])){
		// 	$filter_query .= " AND sname = '".$_POST['customer_name']."' ";
		// 	$active_customer_name = $_POST['customer_name'];
		// }
		// if(isset($_POST['customer_phone']) && !empty($_POST['customer_phone'])){
		// 	$filter_query .= " AND sphone = '".$_POST['customer_phone']."' ";
		// 	$active_customer_phone = $_POST['customer_phone'];
		// }
		// if(isset($_POST['customer_email']) && !empty($_POST['customer_email'])){
		// 	$filter_query .= " AND semail = '".$_POST['customer_email']."' ";
		// 	$active_customer_email = $_POST['customer_email'];
		// }
		// if(isset($_POST['active_customer']) && !empty($_POST['active_customer'])){
		// 	$filter_query .= " AND customer_id = '".$_POST['active_customer']."' ";
		// 	$active_customer_id = $_POST['active_customer'];
		// }
		// if(isset($_POST['pickup_rider']) && !empty($_POST['pickup_rider'])){
		// 	$filter_query .= " AND pickup_rider = '".$_POST['pickup_rider']."' ";
		// 	$pickup_rider = $_POST['pickup_rider'];
		// }
		// if(isset($_POST['delivery_rider']) && !empty($_POST['delivery_rider'])){
		// 	$filter_query .= " AND delivery_rider = '".$_POST['delivery_rider']."' ";
		// 	$delivery_rider = $_POST['delivery_rider'];
		// }
		// if(isset($_POST['order_status']) && !empty($_POST['order_status'])){
		// 	$filter_query .= " AND status = '".$_POST['order_status']."' ";
		// 	$active_order_status = $_POST['order_status'];
		// }


		if(isset($_POST['order_city']) && !empty($_POST['order_city'])){
			$filter_query      .= " AND destination = '".$_POST['order_city']."' ";
			$active_order_city  = $_POST['order_city'];
		}

		$from = date('Y-m-d',strtotime($_POST['from']));
		$to = date('Y-m-d',strtotime($_POST['to']));

		// $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND  pickup_rider =".$rider_id."  AND status='".$status."'   $filter_query order by id desc ");



		$query1 = mysqli_query($con,"SELECT * FROM assignment_record WHERE DATE_FORMAT(`assign_data_time`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`assign_data_time`, '%Y-%m-%d') <= '".$to."' AND rider_status_done_no = '0' AND  user_id =".$rider_id."  AND status='".$status."' AND assignment_type=1    $filter_query order by id desc ");


		// echo "SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND  pickup_rider =".$rider_id."    $filter_query order by id desc ";
		// die();
}else{

	$from = date('Y-m-d', strtotime('today - 30 days'));
	$to   = date('Y-m-d');

	// $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND pickup_rider =".$rider_id." AND  status='".$status."'  $filter_query  order by id desc ");

	$query1 = mysqli_query($con,"SELECT * FROM assignment_record WHERE DATE_FORMAT(`assign_data_time`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`assign_data_time`, '%Y-%m-%d') <= '".$to."'  AND rider_status_done_no = '0' AND  user_id =".$rider_id." AND assignment_type=1  $filter_query  order by id desc ");

	// AND  status='".$status."'
	// AND  status='".$status."'


	// die();
	// echo "SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND pickup_rider =".$rider_id."   $filter_query  order by id desc ";
	// die();
}

?>
<?php
if(isset($message) && !empty($message)){
	echo $message;
}
$courier_query=mysqli_query($con,"Select * from users where type='driver'");
$delivery_courier_query=mysqli_query($con,"Select * from users where type='driver'");
$status_query=mysqli_query($con,"Select * from order_status where id = 28 AND  active='1'");
$city_query=mysqli_query($con,"Select * from cities where 1");
 ?>
 <style type="text/css">
	.zones_main{
		margin-bottom: 20px;
	}
</style>
<div class="panel panel-default">

	<div class="panel-heading">Orders</div>

		<div class="panel-body" id="same_form_layout">

			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

				<div class="row">

					<div class="col-sm-12 table-responsive gap-none">
						 <form method="POST" action="">
				    		<div class="row" >
				    			<div class="col-sm-2 left_right_none">
				    			<div class="form-group">
				    				<label>Tracking Number</label>
				    				<input type="text" value="<?php echo $active_tracking; ?>" class="form-control " name="tracking_no">
				    			</div>
				    		</div>
				    		<!-- <div class="col-sm-2 left_right_none">
				    			<div class="form-group">
				    				<label>Pickup Name</label>
				    				<input type="text" value="<?php echo $active_customer_name; ?>" class="form-control " name="customer_name">
				    			</div>
				    		</div>
				    		<div class="col-sm-2 left_right_none">
				    			<div class="form-group">
				    				<label>Pickup Phone</label>
				    				<input type="text" value="<?php echo $active_customer_phone; ?>" class="form-control " name="customer_phone">
				    			</div>
				    		</div>
				    		<div class="col-sm-2 left_right_none">
				    			<div class="form-group">
				    				<label>Pickup Email</label>
				    				<input type="text" value="<?php echo $active_customer_email; ?>" class="form-control " name="customer_email">
				    			</div>
				    		</div>	 -->
				    		<div class="col-sm-2 left_right_none">
				    			<div class="form-group">
				    				<label>Assign From</label>
				    				<input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4" name="from">
				    			</div>
				    		</div>
				    		<div class="col-sm-2 left_right_none">
				    			<div class="form-group">
				    				<label>Assign To</label>
				    				<input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to">
				    			</div>
				    		</div>
				    		<!-- <div class="col-sm-2" > -->
			      	 		<!-- <div class="form-group">
				      	 		<label>Customer</label>
						       	<select class="form-control active_customer_detail js-example-basic-single" name="active_customer">
						       		<option value="">All Customers</option>
						       		<?php foreach($customers as $customer){ ?>
						       			<option  <?php if($customer['id'] == $active_customer_id ){ echo "selected"; } ?> value="<?php echo $customer['id']; ?>"><?php echo $customer['fname'].(($customer['bname'] != '') ? ' ('.$customer['bname'].')' : ''); ?></option>
						       		<?php } ?>
						       	</select>
					       </div> -->
			    		  <!-- </div> -->

							<!-- <div class="col-sm-2 left_right_none" >
							<div class="form-group">
							<label>Pickup Rider</label>
							<select class="form-control courier_list js-example-basic-single" name="pickup_rider">
								<option selected value="">Select Rider</option>
								<?php while($row=mysqli_fetch_array($courier_query)){ ?>
								<option <?php if($row['id'] == $pickup_rider ){ echo "selected"; } ?> value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
							<?php } ?>
							</select>
							</div>
							</div> -->
							<!-- <div class="col-sm-2 left_right_none" >
								<div class="form-group">
									<label>Delivery Rider</label>
									<select class="form-control courier_list js-example-basic-single" name="delivery_rider">
										<option selected value="">Select Rider</option>
										<?php while($row=mysqli_fetch_array($delivery_courier_query)){ ?>
										<option <?php if($row['id'] == $delivery_rider ){ echo "selected"; } ?> value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
									<?php } ?>
									</select>
								</div>
							</div> -->
						<!-- <div class="col-sm-2 left_right_none" >
							<div class="form-group">
								<label>Order Status</label>
								<select class="form-control courier_list js-example-basic-single" name="order_status">
									<option selected value="">Select status</option>
									<?php while($row=mysqli_fetch_array($status_query)){ ?>
									<option <?php if($row['status'] == $active_order_status ){ echo "selected"; } ?> value="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></option>
								<?php } ?>
								</select>
							</div>
						</div> -->
						<!-- <div class="col-sm-2 left_right_none" >
							<div class="form-group">
								<label>City</label>
								<select class="form-control courier_list js-example-basic-single" name="order_city">
									<option selected value="">Select City</option>
									<?php while($row=mysqli_fetch_array($city_query)){ ?>
									<option  <?php if($row['id'] == $active_order_city ){ echo "selected"; } ?> value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?></option>
								<?php } ?>
								</select>
							</div>
						</div>
				    		 -->
				    	</div>
				    	<div class="row">
				    		<div class="col-sm-1 sidegapp-submit ">
				    			<input type="submit"  name="submit" class="btn btn-success" value="Search">
				    		</div>
				    	</div>
				    	</form>


						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">

							<thead>

								<tr role="row">
									<th>Sr.No</th>
									<th>Tracking No</th>
									<th hidden>Order ID</th>
									<th>Order Date</th>
									<th>Order Time</th>
									<th>Assignment Date/Time</th>
									<th>Status Update Date/Time</th>
									<th hidden>Refernece No</th>
									<th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Pickup Details</th>

									<th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Delivery Details</th>

									<th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Shipment Details</th>
								<!-- 	<th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Payments</th> -->
									<th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">PickUp location</th>

									<th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Status</th>


								</tr>

							</thead>

							<tbody>

							<?php

							$sr=1;
								while($row_data=mysqli_fetch_array($query1))
								{
									$fetch1=mysqli_fetch_array(mysqli_query($con,"Select * from orders where track_no='".$row_data['order_num']."'   "));
									?>

									<tr class="gradeA odd" role="row">

										<td><?php echo $sr; ?></td>
										<td class="sorting_1"><?php echo $fetch1['track_no']; ?></td>
										<td hidden><?php echo $fetch1['product_id']; ?></td>
										<td class="sorting_1"><?php echo date(DATE_FORMAT,strtotime($fetch1['order_date'])); ?></td>
										<td><?php echo date('h:i A',strtotime($fetch1['order_time'])); ?></td>

										<td><?php echo date('d/m/Y h:i A',strtotime($row_data['assign_data_time'])); ?></td>

										<td>
											<?php if (isset($row_data['status_update_time']) and !empty($row_data['status_update_time'])): ?>
												<?php echo date('d/m/Y h:i A',strtotime($row_data['status_update_time'])); ?>
											<?php endif ?>
										</td>

										<td hidden><?php echo $fetch1['ref_no']; ?></td>
										<td class="center">
											<b>Pickup City:</b> <?php echo $fetch1['origin']; ?><br>
											<b>Account Name:</b> <?php echo $fetch1['sname']; ?><br>
											<b>Business Name:</b> <?php echo $fetch1['sbname']; ?><br>

											<b>Phone:</b> <?php echo $fetch1['sphone']; ?><br>

											<b>Email:</b> <?php echo $fetch1['semail']; ?><br>

											<b>Address:</b> <?php echo $fetch1['sender_address']; ?><br>
										</td>

										<td class="center">

											<b>Destination:</b> <?php echo $fetch1['destination']; ?><br>
											<b>Name:</b> <?php echo $fetch1['rname']; ?><br>

											<b>Phone:</b> <?php echo $fetch1['rphone']; ?><br>

											<!-- <b>Email:</b> <?php echo $fetch1['remail']; ?><br> -->

											<b>Address:</b> <?php echo $fetch1['receiver_address']; ?><br>
										</td>
										<td>


											<b>Parcel Weight:</b><?php echo $fetch1['weight']; ?> Kg</br>
											<b>Item Detail:</b><?php echo $fetch1['product_desc']; ?></br>
											<b>Special Instruction:</b><?php echo $fetch1['special_instruction']; ?></br>
										</td>

										<!-- <td>
											<b>Delivery Fees:</b>Rs <?php echo number_format((float)$fetch1['price'],2); ?></br>
											<b>Amount:</b>Rs <?php echo number_format((float)$fetch1['collection_amount'],2); ?></br>
										</td> -->
										<td>

												<b>Location:</b> <?php echo $fetch1['Pick_location']; ?></br>
										</td>
										<td>
											<span class="badge badge-pill badge-primary" style="background: #39bcb5;"><?php echo getKeyWord($fetch1['status']); ?></span>
										</td>
											<!-- <td><?php echo $delivery_zone_array['zone']; ?></td> -->


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
