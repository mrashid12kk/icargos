<?php
	$method = -1;
	if(isset($_POST['delivered'])){
		$method = 0;
	} else if(isset($_POST['damaged'])){
		$method = 1;
	}
	 else if(isset($_POST['lost'])){
		$method = 2;
	}
	if($method > -1) {
		$driver_id=$fetch['id'];
		$status = '';
		if($method == 1)
			$status = 'damaged';
		else if($method == 2)
			$status = 'lost';
		else
			$status = 'delivered';
		$order_id=mysqli_real_escape_string($con,$_POST['order_id']);
		$query1=mysqli_query($con,"update users,orders,deliver set orders.status= '$status',users.status='complete',deliver.status= '$status' where orders.id=$order_id and users.id=$driver_id and deliver.order_id=$order_id and deliver.driver_id=$driver_id") or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have successfully mark order as '.$status.' .</div>';
		}
		else{
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not mark the order as '.$status.'.</div>';
		}
	}
?>
<div class="panel panel-default">
	<div class="panel-heading">Pending Pickup Orders</div>
		<div class="panel-body">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 
							<thead>
								<tr role="row">
								<th>Order#</th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 150px;">Pickup Date</th>
								   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Pickup Location</th>
								   
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Sender Details</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Delivery Details</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;">Opertaions</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$query1=mysqli_query($con,"Select * from deliver,orders where deliver.driver_id=".$fetch['id']." and orders.id=deliver.order_id and (deliver.status='assigned' || deliver.status = 'accepted') order by deliver.id desc") or die(mysqli_error($con));
									while($fetch1=mysqli_fetch_array($query1)){
								?>
								<tr class="gradeA odd" role="row">
									<td><?php echo $fetch1['track_no']; ?></td>
									<td>
										<b>Date:</b> <?php echo $fetch1['pickup_date']; ?><br>
									</td>
									<td class="sorting_1"><?php echo $fetch1['plocation']; ?></td>
									
									<td class="center">
										<b>Name:</b> <?php echo $fetch1['sname']; ?><br>
										<b>Phone:</b> <?php echo $fetch1['sphone']; ?><br>
										<b>Email:</b> <?php echo $fetch1['semail']; ?><br>
										<b>Address:</b> <?php echo $fetch1['sender_address']; ?><br>
										
									</td>
									<td class="center">
										<b>Type:</b> <?php echo $fetch1['package_type']; ?><br>
										<b>Delivery Fees:</b> <?php echo $fetch1['delivery_by']; ?><br>
										<b>Collection Amount:</b> <?php echo $fetch1['collection_amount']; ?><br>
										<b>Delivery Fees:</b> <?php echo $fetch1['price']; ?><br>
										<b>Total:</b> <?php echo (int)$fetch1['price']+(int)$fetch1['collection_amount']; ?><br>
									</td>
									<td class="center" >
										<!-- <form action="" method="post">
											<input type="hidden" name="order_id" value="<?php// echo $fetch1['order_id']; ?>" >
											<input type="submit" name="delivered" class="btn btn-info" value="Mark delivered" onclick="return confirm('Are you sure to mark this order as delivered')">
											<input type="submit" name="damaged" class="btn btn-warning" value="Mark Damaged" onclick="return confirm('Are you sure to mark this order as damaged')">
											<input type="submit" name="lost" class="btn btn-danger" value="Mark Lost" onclick="return confirm('Are you sure to mark this order as lost')">
										</form> -->
										<a href="order.php?id=<?php echo $fetch1['id']; ?>" class="btn btn-info">View Order</a>
											<a href="map.php?pickup=<?php echo $fetch1['plocation']; ?>&delivery=<?php echo $fetch1['daddress']; ?>" class="btn btn-success btn-sm"  target="-blank">View Google Map</button>
										
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