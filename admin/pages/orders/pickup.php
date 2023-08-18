<?php
$customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
$active_customer_query = "";
if(isset($_GET['active_customer'])){
	$active_customer = $_GET['active_customer'];
	if(empty($active_customer)){
		$active_customer_query = "";
	}else{
		$active_customer_query = " AND customer_id=".$active_customer." ";
	}
}
//pickup rider orders 
$pickup_rider_search = "";
$pickup_rider = "";
if(isset($_GET['pickup_rider']) ){
	$pickup_rider = $_GET['pickup_rider'];
	$pickup_rider_search = " AND pickup_zone IN(SELECT id FROM zones WHERE riders ='".$pickup_rider."' ) ";
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

	if(isset($_POST['order_ids']) && !empty(json_decode($_POST['order_ids']))){
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
if(isset($_POST['submit'])){
		$from = date('Y-m-d',strtotime($_POST['from']));
		$to = date('Y-m-d',strtotime($_POST['to']));
		$query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND status='booked' AND is_shipped=0 AND is_received=0 $pickup_rider_search $active_customer_query order by id desc ");
		
		
	}else{
		$from = date('Y-m-d', strtotime('today - 30 days'));
		$to = date('Y-m-d');
		$query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND status='booked' AND is_shipped=0 AND is_received=0 $pickup_rider_search $active_customer_query   order by id desc ");

	}
$pickup_rider_query = mysqli_query($con,"SELECT GROUP_CONCAT(DISTINCT riders) as riders FROM zones;");
$pickup_rider_rec = mysqli_fetch_array($pickup_rider_query);
$riders = explode(',', $pickup_rider_rec['riders']);
$riders_arr = array_unique($riders); 
$rider_imp = implode(',', $riders_arr);
$rider_query = mysqli_query($con,"SELECT * FROM users WHERE type='driver' AND id IN($rider_imp) ");
?>
<?php
if(isset($message) && !empty($message)){
	echo $message;
}
 ?>
<div class="panel panel-default">
<style type="text/css">
	.zones_main{
		margin-bottom: 20px;
	}
</style>
	<div class="panel-heading">Pickup Sheet</div>

		<div class="panel-body">

			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

				<div class="row">
					
					<div class="col-sm-12 table-responsive gap-none">
						 <form method="POST" action="">
				    		<div class="row" style="margin: 0;">
				    		<div class="col-sm-2">
				    			<div class="form-group">
				    				<label>From</label>
				    				<input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4" name="from">
				    			</div>
				    		</div>
				    		<div class="col-sm-2" style="    padding-left: 20px;">
				    			<div class="form-group">
				    				<label>To</label>
				    				<input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to">
				    			</div>
				    		</div>
				    		<div class="col-sm-1 sidegapp-submit">
				    			<input type="submit" style="margin-top: 23px; color: #fff !important;" name="submit" class="btn btn-info" value="Submit">
				    		</div>
				    	</form>

				    	

				    	
				    		<?php
	       $active_id = "";
	       if(isset($_GET['active_customer'])){
	       	$active_id = $_GET['active_customer'];
	       } 
	        ?>
	    
	     	 <div class="col-sm-3 all_customer_gapp" style="float: right;margin-top: 26px;">
	      	 <div class="form-group">
	       	<select class="form-control active_customer_detail js-example-basic-single" onchange="window.location.href='pickup.php?active_customer='+this.value;">
	       		<option value="">All Customers</option>
	       		<?php foreach($customers as $customer){ ?>
	       			<option  <?php if($customer['id'] == $active_id ){ echo "selected"; } ?> value="<?php echo $customer['id']; ?>"><?php echo $customer['fname'].(($customer['bname'] != '') ? ' ('.$customer['bname'].')' : ''); ?></option>
	       		<?php } ?>
	       	</select>
	       </div>
	      </div>
	</div>
	<div class="zones_main">
		
	<form method="POST" action="savezone.php" id="assign_rider">
		<input type="hidden" name="assign_orders" value="" id="assign_orders">
		<div class="row">
		<div class="col-md-3">
			<label>Select Pickup Rider</label>
			<select name="rider_id" onchange="window.location.href='pickup.php?pickup_rider='+this.value;" class="form-control js-example-basic-single pickup_rider">
				<option value="0" selected disabled>Select Pickup Rider</option>
				<?php while($rec = mysqli_fetch_array($rider_query)){ ?>
				<option value="<?php echo $rec['id']; ?>" <?php if($pickup_rider == $rec['id']){ echo "selected"; } ?> ><?php echo $rec['Name']; ?></option>
			<?php } ?>
			</select>
		</div>
		<div class="col-md-2">
			<a style="margin-top: 24px;" href="#" class="assign_rider btn btn-info">Assign Pickup Rider</a>
		</div>
	</div>
	</form>
	</div>
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 

							<thead>

								<tr role="row">
								<th class="center"><input type="checkbox" class="main_select" name=""></th>
								<th>Order#</th>
								<th>Order Date</th>
								  

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Sender Details</th>

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Receiver Details</th>

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Shipment Details</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Payments</th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Pickup Zone</th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Pickup Rider</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Delivery Zone</th>
								  

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;">Opertaions</th>

								</tr>

							</thead>

							<tbody>

							<?php
								

								while($fetch1=mysqli_fetch_array($query1)){
									$pickup_zone_query = mysqli_query($con,"SELECT zone FROM zones WHERE id=".$fetch1['pickup_zone']." ");
									$pickup_zone_array = mysqli_fetch_array($pickup_zone_query);
									$pickup_rider_q = mysqli_query($con,"SELECT * FROM users WHERE id=".$fetch1['pickup_rider']." ");
									$pickup_rider_data = mysqli_fetch_array($pickup_rider_q);
									$delivery_query = mysqli_query($con,"SELECT zone FROM zones WHERE id=".$fetch1['delivery_zone']." ");
									$delivery_zone_array = mysqli_fetch_array($delivery_query);

									
							?>



								<tr class="gradeA odd" role="row">
									<td class="center"><input type="checkbox" class="order_check" data-id="<?php echo $fetch1['id']; ?>" name=""></td>
									<td class="sorting_1"><?php echo $fetch1['track_no']; ?></td>
									<td class="sorting_1"><?php echo date('d M Y',strtotime($fetch1['order_date'])); ?></td>
									
									<td class="center">

									<?php
									if(isset($fetch1['customer_id']) && $fetch1['customer_id']){
										$query2=mysqli_query($con,"Select * from customers where id='".$fetch1['customer_id']."' ") or die(mysqli_error($con));

										$fetch2=mysqli_fetch_array($query2);

									?>
										<b>Name:</b> <?php echo $fetch2['fname']; ?><br>
										<b>Company:</b> <?php echo $fetch1['sbname']; ?><br>

										<b>Phone:</b> <?php echo $fetch2['mobile_no']; ?><br>

										<b>Email:</b> <?php echo $fetch2['email']; ?><br>

										<b>Address:</b> <?php echo $fetch2['address']; ?><br>

									<?php }else{ ?>
											<b>Name:</b> <?php echo $fetch1['sname']; ?><br>

											<b>Phone:</b> <?php echo $fetch1['sphone']; ?><br>

											<b>Email:</b> <?php echo $fetch1['semail']; ?><br>

											<b>Address:</b> <?php echo $fetch1['sender_address']; ?><br>
									<?php } ?>
									</td>

									<td class="center">

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
					<td><?php echo $pickup_zone_array['zone']; ?></td>
					<td><?php echo $pickup_rider_data['Name']; ?></td>
					<td><?php echo $delivery_zone_array['zone']; ?></td>
									<td class="center action_btns" >
										<a title="view order" href="order.php?id=<?php echo $fetch1['id']; ?>" class="btn btn-info"> <i class="fa fa-eye"></i></a>
										<a  target="_blank" title="track order" href="<?php echo BASE_URL ?>track-details.php?track_code=<?php echo $fetch1['track_no'] ?>" class="track_order btn btn-success btn-sm track_order" class="btn btn-success"> <i class="fa fa-truck"></i> </a>

										

										

									</td>

								</tr>

								<?php

								}

								

								?>

							</tbody>

						</table>

				</div>

			</div>

		</div>

	</div>

</div>
 