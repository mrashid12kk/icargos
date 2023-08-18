<?php
	if(isset($_POST['order_id'])){
		$driver_id=mysqli_real_escape_string($con,$_POST['drivers']);
		$order_id=mysqli_real_escape_string($con,$_POST['order_id']);
		$query1=mysqli_query($con,"update users,orders set orders.status='in process',users.status='assigned' where orders.id=$order_id and users.id=$driver_id ") or die('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Sorry!</strong> Drivers are not available now.</div>');
		$query1=mysqli_query($con,"insert into deliver (driver_id,order_id,status) values ('$driver_id','$order_id','assigned')") or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have successfully assigned the order to the driver.</div>';
			$query2=mysqli_query($con,"select * from users where id=$driver_id") or die();
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
	$driver_id=$_SESSION['users_id'];
?>

<div class="panel panel-default">
	<div class="panel-heading">Services Reports</div>
		<div class="panel-body">
		<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				<div class="row">
					<form action="" method="post">
						<div class="form-group">
							<label class="col-sm-2 control-label">Generate Reports</label>
								<div class="col-sm-3">
									<div class="input-group date" id="datetimerangepicker1">
										<input type="text" name="from" class="form-control" data-date-format="YYYY-MM-DD">
										<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span>
										</span>
									</div>
								</div>
								
								<div class="col-sm-3">
									<div class="input-group date" id="datetimerangepicker2">
										<input type="text" name="to" class="form-control" data-date-format="YYYY-MM-DD">
										<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span>
										</span>
									</div>
								</div>
								<div class="col-sm-3">
									<button type="submit" name="generate" class="btn btn-info">Generate Reports</button>
								</div>
							
						</div>
					</form>
		
					<div class="col-sm-12 table-responsive">
						<div class="pdf">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 
							<thead>
								<tr role="row">
								   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Order No.</th>
								   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Order Date.</th>
								   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Package Type</th>
								   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Pickup Location</th>
								    <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 150px;">Delivery Location</th>
								    <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 150px;">Delivery Driver Name</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Sender Details</th>
								   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Receiver Details</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Delivery Details</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Status</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Collection Amount</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Delivery Amount</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Total</th>
								 </tr>
							</thead>
							<tbody>
							<?php
								if(isset($_POST['generate'])){
									$from=mysqli_real_escape_string($con,$_POST['from']);
									$to=mysqli_real_escape_string($con,$_POST['to']);
									$sql="Select * from users,deliver,orders where users.id=$driver_id and deliver.driver_id=$driver_id and orders.id=deliver.order_id and orders.order_date between '$from' and '$to' order by deliver.id desc";
								}
								else{
									$date=date('Y-m-d');
									$sql="Select * from users,deliver,orders where users.id=$driver_id and deliver.driver_id=$driver_id and orders.id=deliver.order_id  order by deliver.id desc";
								}
								$query1=mysqli_query($con,$sql) or die(mysqli_error($con));
								while($fetch1=mysqli_fetch_array($query1)){
							?>

								<tr class="gradeA odd" role="row">
									<td class="sorting_1"><?php echo $fetch1['id']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['order_date']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['package_type']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['plocation']; ?></td>
									<td><?php echo $fetch1['daddress']; ?></td>
									<?php
										$query22=mysqli_query($con,"Select * from users where id=".$fetch1['deliver_driver_id']);
										$fetch22=mysqli_fetch_array($query22);
										
									?>
									<td><?php echo $fetch22['Name']; ?></td>
									<td class="center">
										<b>Name:</b> <?php echo $fetch1['sname']; ?><br>
										<b>Phone:</b> <?php echo $fetch1['sphone']; ?><br>
										<b>Email:</b> <?php echo $fetch1['semail']; ?><br>
										<b>Address:</b> <?php echo $fetch1['sender_address']; ?><br>
										
									</td>
									<td class="center">
										<b>Name:</b> <?php echo $fetch1['rname']; ?><br>
										<b>Phone:</b> <?php echo $fetch1['rphone']; ?><br>
										<b>Email:</b> <?php echo $fetch1['remail']; ?><br>
										<b>Address:</b> <?php echo $fetch1['receiver_address']; ?><br>
									</td>
									<td class="center">
										<b>Type:</b> <?php echo $fetch1['package_type']; ?><br>
										<b>Delivery Fees:</b> <?php echo $fetch1['delivery_by']; ?><br>
									</td>
									<td class="sorting_1"><?php echo $fetch1['status']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['collection_amount']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['price']; ?></td>
									<td class="sorting_1"><?php echo (int)$fetch1['price']+(int)$fetch1['collection_amount']; ?></td>
									
								</tr> 
								<?php
								}
								
								?>
							</tbody>
						</table>
						</div>
						<div class="text-center">
							<img src="images/raw.gif" style="display:none;">
							<a href="#" class="btn btn-success center" target="_blank" id="down_pdf"  style="display:none;">Download PDF</a>
							<a href="#" class="btn btn-success center" id="gen_pdf">Generate PDF</a>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>