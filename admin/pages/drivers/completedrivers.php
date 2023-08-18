
<div class="panel panel-default">
	<div class="panel-heading">Completed Orders</div>
		<div class="panel-body">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 
							<thead>
								<tr role="row">
								   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Pickup Location</th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 150px;">Delivery Location</th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 150px;">Pickup Date</th>
								  
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Sender Details</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Receiver Details</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Delivery Details</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$query1=mysqli_query($con,"Select * from deliver,orders where deliver.driver_id=".$fetch['id']." and orders.id=deliver.order_id and deliver.status='completed' order by deliver.id desc") or die(mysqli_error($con));
									while($fetch1=mysqli_fetch_array($query1)){
											
								?>

								<tr class="gradeA odd" role="row">
									<td class="sorting_1"><?php echo $fetch1['plocation']; ?></td>
									<td><?php echo $fetch1['daddress']; ?></td>
									<td><?php echo $fetch1['pickup_date']; ?></td>
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