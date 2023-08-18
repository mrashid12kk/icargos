<?php
$customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
$active_customer_query = "";
	if(isset($_GET['active_customer']))
	{
		$active_customer = $_GET['active_customer'];
		if(empty($active_customer)){
			$active_customer_query = "";
		}else{
			$active_customer_query = " AND customer_id=".$active_customer." ";
		}
	}
	if(isset($_POST['order_id'])){

		$driver_id=mysqli_real_escape_string($con,$_POST['drivers']);

		$order_id=mysqli_real_escape_string($con,$_POST['order_id']);

		$query1=mysqli_query($con,"update users,orders set orders.status='in process',users.status='assigned' where orders.id='".$order_id."' and users.id='".$driver_id."' ") or die('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Sorry!</strong> Drivers are not available now.</div>');

		$query1=mysqli_query($con,"insert into deliver (driver_id,order_id,status) values ('".$driver_id."','".$order_id."','assigned')") or die(mysqli_error($con));

		$rowscount=mysqli_affected_rows($con);

		if($rowscount>0){

			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have successfully assigned the order to the driver.</div>';

			$query2=mysqli_query($con,"select * from users where id='".$driver_id."' ") or die();

			$fetch2=mysqli_fetch_array($query2);

			$email=$fetch2['email'];

			$text="You have assigned a package, please check your profile panel ";

			$headers="From:happinessdelivery@happinessdelivery.com";

			mail($email,'Package Submitted',$text,$headers);

		}

		else{

			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not assigned the order to  a driver.please try again later.</div>';

		}

	}

	//update status

	if(isset($_POST['order_ids']) && !empty(json_decode($_POST['order_ids'])))
	{
		$message = '';
		$order_id_data = json_decode($_POST['order_ids']); 
		if($_POST['stat'] == 'received'){
			$log_msg = 'Arrived at Transco Logistics Fulfillment Facility';
   		}else{
   			$log_msg = 'Item Not Received';
   		}
    	$date = date('Y-m-d H:i:s');

		foreach($order_id_data as $order_id){
			
			$query = mysqli_query($con,"SELECT * FROM orders WHERE id =".$order_id." ");
			$record = mysqli_fetch_array($query);
			mysqli_query($con,"INSERT INTO order_logs(`order_no`,`order_status`,`created_on`) VALUES ('".$record['track_no']."', '".$log_msg."','".$date."') ");
			if($_POST['stat'] == 'received'){
				mysqli_query($con,"UPDATE orders SET status='received',is_received =1 WHERE id=".$order_id." "); 
		    }else{
		    	mysqli_query($con,"UPDATE orders SET status='cancelled',is_received =2 WHERE id=".$order_id." ");
		    }
		    $message = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> '.$log_msg.'</div>'; 
	  	}
	}


	if(isset($_POST['submit']))
	{
		$from = date('Y-m-d',strtotime($_POST['from']));
		$to = date('Y-m-d',strtotime($_POST['to']));
		$query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND status='New Booked' AND is_shipped=0 AND is_received=0 $active_customer_query order by id desc ");
		$pickup_query = mysqli_query($con,"SELECT * FROM zones WHERE city IN(SELECT origin FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND status='pending' AND is_shipped=0 AND is_received=0 $active_customer_query order by id desc) ");
		$delivery_query = mysqli_query($con,"SELECT * FROM zones WHERE city IN(SELECT destination FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND status='pending' AND is_shipped=0 AND is_received=0 $active_customer_query order by id desc) ");
	}else{
		$from = date('Y-m-d', strtotime('today - 30 days'));
		$to = date('Y-m-d');
		$query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND status='New Booked' AND is_shipped=0 AND is_received=0 $active_customer_query  order by id desc ");
		$pickup_query = mysqli_query($con,"SELECT * FROM zones WHERE city IN( SELECT origin FROM orders WHERE  DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND status='New Booked' AND is_shipped=0 AND is_received=0 $active_customer_query  order by id desc) ");
		$delivery_query = mysqli_query($con,"SELECT * FROM zones WHERE city IN( SELECT destination FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND status='New Booked' AND is_shipped=0 AND is_received=0 $active_customer_query  order by id desc) ");
	}

	$query_status = mysqli_query($con,"SELECT * FROM orders WHERE (pickup_zone IS  NULL OR delivery_zone IS   NULL) AND status = 'New Booked' ");
	$query_status_rec = mysqli_num_rows($query_status);
	
	$rec_query_status = mysqli_query($con,"SELECT * FROM orders WHERE (pickup_zone IS NOT NULL OR delivery_zone IS NOT  NULL) AND status = 'New Booked' ");
	$rec_query_status_rec = mysqli_num_rows($rec_query_status);
	
 
	if(isset($message) && !empty($message)){
		echo $message;
	}
	$courier_query=mysqli_query($con,"Select * from users where type='driver'");



	 



?>
 	<style type="text/css">
		.zones_main{
			margin-bottom: 20px;
		}
	</style>
<div class="panel panel-default">

	<div class="panel-heading">New Booked Orders</div>

		<div class="panel-body" id="same_form_layout">

			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				<form method="POST" action="bulk_pickup_assign.php" id="bulk_submit">
				    			
								<div class="col-sm-2 left_right_none" >
									<div class="form-group">
										<label>Assign Rider</label>
										<select class="form-control courier_list" name="active_courier">
											<option selected disabled>Select Rider</option>
											<?php while($row=mysqli_fetch_array($courier_query)){ ?>
											<option value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
										<?php } ?>
										</select>
									</div>
								</div>
				    		<input type="hidden" name="order_ids" id="print_data">
				    		<div class="col-sm-1 left_right_none upate_Btn">
				    			<a href="#" class="update_status btn btn-success">Update</a>
				    			
				    		</div>

				    		</form>
				<div class="row">
					
					<div class="col-sm-12 table-responsive gap-none">
						 <form method="POST" action="">
				    		<div class="row" >
				    		<div class="col-sm-1 left_right_none">
				    			<div class="form-group">
				    				<label>From</label>
				    				<input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4" name="from">
				    			</div>
				    		</div>
				    		<div class="col-sm-1 left_right_none">
				    			<div class="form-group">
				    				<label>To</label>
				    				<input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to">
				    			</div>
				    		</div>
				    		<div class="col-sm-1 sidegapp-submit ">
				    			<input type="submit"  name="submit" class="btn btn-info" value="Submit">
				    		</div>
				    	</form>
				    	
				    		<?php
	       $active_id = "";
	       if(isset($_GET['active_customer'])){
	       	$active_id = $_GET['active_customer'];
	       } 
	        ?>
	    
	     	 	<div class="col-sm-2 all_customer_gapp left_right_none" >
	      	 		<div class="form-group">
			       	<select class="form-control active_customer_detail js-example-basic-single" onchange="window.location.href='orders.php?active_customer='+this.value;">
			       		<option value="">All Customers</option>
			       		<?php foreach($customers as $customer){ ?>
			       			<option  <?php if($customer['id'] == $active_id ){ echo "selected"; } ?> value="<?php echo $customer['id']; ?>"><?php echo $customer['fname'].(($customer['bname'] != '') ? ' ('.$customer['bname'].')' : ''); ?></option>
			       		<?php } ?>
			       	</select>
			       </div>
			      </div>
				</div>
						<table class="table table-striped table-bordered dataTable_with_sorting no-footer" > 

							<thead>

								<tr role="row">
								<th class="center"><input type="checkbox" class="main_select" name=""></th>
								<th>Sr.No</th>
								<th>Order#</th>
								<th>Order Date</th>
								<th>Order Time</th>
								  

								   <th >Sender Details</th>

								   <th >Receiver Details</th>

								   <th >Shipment Details</th>
								   <th >Payments</th>
								   <!-- <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Pickup Zone</th> -->
								   <!-- <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Delivery Zone</th> -->

								  

								   <th >Action</th>

								</tr>

							</thead>

							<tbody>

							<?php
								
							$sr=1;
								while($fetch1=mysqli_fetch_array($query1)){
									$pickup_zone_query = mysqli_query($con,"SELECT zone FROM zones WHERE id=".$fetch1['pickup_zone']." ");
									$pickup_zone_array = mysqli_fetch_array($pickup_zone_query);
									$delivery_query = mysqli_query($con,"SELECT zone FROM zones WHERE id=".$fetch1['delivery_zone']." ");
									$delivery_zone_array = mysqli_fetch_array($delivery_query);
							?>



								<tr class="gradeA odd" role="row">
									<td class="center"><input type="checkbox" class="order_check" data-id="<?php echo $fetch1['id']; ?>" name=""></td>
									<td><?php echo $sr; ?></td>
									<td class="sorting_1"><?php echo $fetch1['track_no']; ?></td>
									<td class="sorting_1"><?php echo date('d M Y',strtotime($fetch1['order_date'])); ?></td>
									<td><?php echo date('h:i A',strtotime($fetch1['order_date'])); ?></td>
									<td class="center">

											<b>Origin:</b> <?php echo $fetch1['origin']; ?><br>
											<b>Name:</b> <?php echo $fetch1['sname']; ?><br>
											<b>Company:</b> <?php echo $fetch1['sbname']; ?><br>

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
										<b>Tracking:</b><?php echo $fetch1['tracking_no']; ?></br>
										<b>Product Code:</b><?php echo $fetch1['product_id']; ?></br>
										<b>Parcel Weight:</b><?php echo $fetch1['weight']; ?></br>
										<b>Product Description:</b><?php echo $fetch1['product_desc']; ?></br>
										<b>Special Instruction:</b><?php echo $fetch1['special_instruction']; ?></br>
									</td>
					
									<td>
										<b>Delivery Fees:</b>Rs <?php echo number_format((float)$fetch1['price'],2); ?></br>
										<b>COD Amount:</b>Rs <?php echo number_format((float)$fetch1['collection_amount'],2); ?></br>
									</td>
										<!-- <td><?php echo $pickup_zone_array['zone']; ?></td> -->
										<!-- <td><?php echo $delivery_zone_array['zone']; ?></td> -->
									<td class="center action_btns" >
										<a title="view order" href="order.php?id=<?php echo $fetch1['id']; ?>" class="btn btn-info"> <i class="fa fa-eye"></i></a>
										<a  target="_blank" title="track order" href="<?php echo BASE_URL ?>track-details.php?track_code=<?php echo $fetch1['track_no'] ?>" class="track_order btn btn-success btn-sm track_order" class="btn btn-success"> <i class="fa fa-truck"></i> </a> 
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
 