<style>
	.row{
		margin: 0 !important;
	}
	.table-responsive{
		padding: 0 !important;
	}
	.col-md-2{
		    padding-right: 20px !important;
	}
</style>
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
	$date=date('Y-m-d');
									
?>
<div class="panel panel-default">
	<div class="panel-heading order_box">Reports</div>
		<div class="panel-body" id="same_form_layout">
		<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				<div class="row gap-none" >
					<form action="" method="post">
						<div class="form-group">
							<label class="col-sm-2 control-label ">Generate Reports</label>
								<?php if(isset($_SESSION['type']) && $_SESSION['type'] == 'admin') { ?>
								<div class="col-sm-2">
									<div class="input-group">
										<select class="form-control" name="branch_id">
											<option value="">All Branches</option>
											<?php
											$query = mysqli_query($con, "SELECT * FROM branches");
											if($query) {
												while ($row = mysqli_fetch_object($query)) {
													if(isset($_POST['branch_id']) && $_POST['branch_id'] == $row->id)
														echo '<option selected value="'.$row->id.'">'.$row->name.'</option>';
													else
														echo '<option value="'.$row->id.'">'.$row->name.'</option>';
												}
											}
											?>
										</select>
									</div>
								</div>
								<?php } ?>
								<div class="col-sm-2">
									<div class="input-group date" id="datetimerangepicker1">
										<input type="text" name="from" class="form-control" value="<?php echo isset($_POST['from'])?$_POST['from']:$date; ?>" data-date-format="YYYY-MM-DD">
										<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span>
										</span>
									</div>
								</div>
								
								<div class="col-sm-2">
									<div class="input-group date" id="datetimerangepicker2">
										<input type="text" name="to" class="form-control" value="<?php echo isset($_POST['to'])?$_POST['to']:$date; ?>" data-date-format="YYYY-MM-DD">
										<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span>
										</span>
									</div>
								</div>
								<div class="col-sm-2">
									<button type="submit" name="generate" class="btn btn-info">Generate Reports</button>
								</div>
							
						</div>
					</form>
		
					<div class="col-sm-12 table-responsive">
						<div class="pdf">
						<?php
						if(isset($_POST['generate'])){
							$from=mysqli_real_escape_string($con,$_POST['from']);
							$from = date('Y-m-d', strtotime('-1 day', strtotime($from)));
							$to=mysqli_real_escape_string($con,$_POST['to']);
							$to = date('Y-m-d', strtotime('+1 day', strtotime($to)));
							$branch_id=mysqli_real_escape_string($con,$_POST['branch_id']);
							$where = "";
							if($branch_id != '')
								$where = " AND branch_id = ".$branch_id;
							if(isset($_SESSION['type']) && $_SESSION['type'] == 'admin')
								$sql="Select * from orders where order_date BETWEEN '$from' and '$to' ".$where." order by id desc";
							else
								$sql="Select * from orders where order_date BETWEEN and '$to' AND branch_id = ".$_SESSION['branch_id']." order by id desc";
						}
						else{
							if(isset($_SESSION['type']) && $_SESSION['type'] == 'admin')
								$sql="Select * from orders where order_date='$date' order by id desc";
							else
								$sql="Select * from orders where order_date='$date' AND  branch_id = ".$_SESSION['branch_id']." order by id desc";
						}
						$query1=mysqli_query($con,$sql) or die(mysqli_error($con));
						$query2=mysqli_query($con,$sql) or die(mysqli_error($con));
						$total_delivery = 0;
						$total_received = 0;
						$total_collection = 0;
						$total_collection_received = 0;
						while($fetch1=mysqli_fetch_array($query1)){
							$total_delivery += (float)$fetch1['price'];
							if(isset($fetch1['invoice_status']) && $fetch1['invoice_status'] == 'paid')  {
								$total_received += (float)$fetch1['price'];
							}
							$total_collection += (float)$fetch1['collection_amount'];
							if(isset($fetch1['is_amount_collected']) && $fetch1['is_amount_collected'] == '1') {
								$total_collection_received += (float)$fetch1['collection_amount'];
							}
						}
						?>
						<div class="summary row">
							<div class="col-sm-6">
								<h4><strong>Total Delivery: </strong><span><?php echo $total_delivery; ?></span></h4>
								<h4><strong>Total Received: </strong><span><?php echo $total_received; ?></span></h4>
								<h4><strong>Balance: </strong><span><?php echo ($total_delivery-$total_received); ?></span></h4>
							</div>
							<div class="col-sm-6">
								<h4><strong>Total Collection: </strong><span><?php echo $total_collection; ?></span></h4>
								<h4><strong>Total Collection Received: </strong><span><?php echo $total_collection_received; ?></span></h4>
								<h4><strong>Balance: </strong><span><?php echo $total_collection - $total_collection_received; ?></span></h4>
							</div>
						</div>
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 
							<thead>
								<tr role="row">
								   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Order No.</th>
								   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Barcode</th>
								   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Order Date.</th>
								   <!-- <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Package Type</th> -->
								   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Pickup Location</th>
								    <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 150px;">Delivery Location</th>
								    <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 150px;">Pickup Date</th>
								  
								   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Sender Details</th>
								   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Receiver Details</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Delivery Details</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Driver</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " style="width: 179px;">Status</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " >Delivery Amount</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " >Recieved</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " >Balance</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " >Method</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " >Paid By</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " >Collection Amount</th>
								  <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column " >Collection Amount Received</th>
								 </tr>
							</thead>
							<tbody>
							<?php
								while($fetch1=mysqli_fetch_array($query2)){
									if(isset($fetch1['id'])) {
										$driver = mysqli_query($con, "SELECT * FROM users WHERE id IN (SELECT driver_id FROM deliver WHERE order_id = ".$fetch1['id'].")");
										$driver = ($driver) ? mysqli_fetch_object($driver) : null;
									}
									if(isset($fetch1['customer_id'])) {
										$customer = mysqli_query($con, "SELECT * FROM customers WHERE id = ".$fetch1['customer_id']);
										$customer = ($customer) ? mysqli_fetch_object($customer) : null;
									}
							?>
								<tr class="gradeA odd" role="row">
									<td class="sorting_1"><?php echo $fetch1['track_no']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['barcode']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['order_date']; ?></td>
									<!-- <td class="sorting_1"><?php echo $fetch1['package_type']; ?></td> -->
									<td class="sorting_1"><?php echo $fetch1['plocation']; ?></td>
									<td><?php echo $fetch1['daddress']; ?></td>
									<td><?php echo $fetch1['pickup_date']; ?></td>
									<td class="center">
										<b>Name:</b> <?php echo $fetch1['sname']; ?><br>
										<b>Client Code:</b> <?php echo isset($customer->client_code) ? $customer->client_code : ''; ?><br>
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
									<td><?php echo isset($driver->Name) ? $driver->Name : ''; ?></td>
									<td class="sorting_1"><?php echo $fetch1['status']; ?></td>
									<td><?php echo $fetch1['price']; ?></td>
									<td>
										<?php
										$balance =  (float)$fetch1['price'];
										if(isset($fetch1['invoice_status']) && $fetch1['invoice_status'] == 'paid')  {
											echo $fetch1['price'];
											$balance -= $fetch1['price'];
										}
										?>
									</td>
									<td><?php echo $balance; ?></td>
									<td><?php echo $fetch1['payment_method']; ?></td>
									<?php
									if(isset($fetch1['payment_method']) && $fetch1['payment_method'] == 'CASH') {
										echo '<td>';
											echo $fetch1['cash_by'];
										echo '</td>';
									} else {
										echo '<td></td>';
									}
									
									?>
									<td><?php echo $fetch1['collection_amount']; ?></td>
									<td><?php echo (isset($fetch1['is_amount_collected']) && $fetch1['is_amount_collected'] == 1) ? 'Yes' : 'No'; ?></td>
								</tr> 
								<?php
								}
								
								?>
							</tbody>
							<tfoot>
								<tr>
									<th>Total</th>
									<th colspan="10"></th>
									<th><?php echo $total_delivery; ?></th>
									<th><?php echo $total_received; ?></th>
									<th><?php echo ($total_delivery-$total_received); ?></th>
									<th colspan="2"></th>
									<th><?php echo $total_collection; ?></th>
								</tr>
							</tfoot>
						</table>
						</div>
						<div class="text-center">
							<img src="images/raw.gif" style="display:none;">
							<a href="#" class="btn btn-success center" target="_blank" id="down_pdf"  style="display:none;">Download PDF</a>
							<!-- <a href="#" class="btn btn-success center" id="gen_pdf">Generate PDF</a> -->
						</div>
				</div>
			</div>
		</div>
	</div>
</div>