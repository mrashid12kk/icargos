<?php
$type = $_SESSION['type'];
$userID = $_SESSION['users_id'];
$query = null;
$sdate = date('Y-m-d');
$edate = date('Y-m-d');

if(isset($_POST['generate'])){
	
	$sdate = date('Y-m-d', strtotime($_POST['sdate']));
	$edate = date('Y-m-d', strtotime($_POST['edate']));
}
if($type == 'driver')
	$query = mysqli_query($con, "SELECT * FROM driver_logs WHERE driver = $userID AND date between '$sdate' and '$edate' ORDER BY date DESC");
else{
	
	$driver_id=isset($_POST['driver_id'])&&$_POST['driver_id']!=""? "driver=".$_POST['driver_id']." and":"";
	// die("SELECT * FROM driver_logs WHERE $driver_id date between '$sdate' and '$edate' ORDER BY date DESC");
	$query = mysqli_query($con, "SELECT * FROM driver_logs WHERE $driver_id date between '$sdate' and '$edate' ORDER BY date DESC") or die(mysqli_error($con));

}
$results = $query;
?>
<div class="panel panel-default">
	<?php if($type != 'driver') { ?>
	<div class="panel-heading">Drivers Log List</div>
	<?php } else { ?>
	<div class="panel-heading">My Log List</div>
	<?php } ?>
	<div class="panel-body">
		
		<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
			<div class="row">
			<form action="" method="POST">
						<div class="form-group">
							<label class="col-sm-2 control-label">Generate Reports</label>
								<?php
									if($type!="driver"){
										?>
								
								<div class="col-sm-2">
									<select class="form-control" name="driver_id">
										<option value="">Select Driver</option>
										<?php
											$queryy=mysqli_query($con,"Select * from users where type='driver' ");
											while($fetchh=mysqli_fetch_array($queryy)){
												$selected=isset($_POST['driver_id'])&&$_POST['driver_id']==$fetchh['id']?"Selected":"";
												echo "<option value='".$fetchh['id']."' $selected>".$fetchh['Name']."</option>";
											}
											
										?>
									</select>
								</div>
									<?php
									}
								?>
								<div class="col-sm-2">
									<div class="input-group date" id="datetimerangepicker1">
										<input type="text" name="sdate" class="form-control" value="<?php echo $sdate; ?>" data-date-format="YYYY-MM-DD">
										<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span>
										</span>
									</div>
								</div>
								
								<div class="col-sm-2">
									<div class="input-group date" id="datetimerangepicker2">
										<input type="text" name="edate" class="form-control" value="<?php echo $edate; ?>" data-date-format="YYYY-MM-DD">
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
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 
							<thead>
							<tr role="row">
							   <?php if($type != 'driver') { ?>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: auto;">Driver</th>
							  	<?php } ?>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: auto;">Start KM of Car</th>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: auto;">Trip KM of Car</th>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: auto;">Total KM of Car</th>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: auto;">No. of Pickups</th>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: auto;">No. of Deliveries</th>
							    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: auto;">Gas Amount</th>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: auto;">Total Sender Fees</th>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: auto;">Total Delivery Fees</th>
							    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: auto;">Deposit Amount</th>
							  
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: auto;">Cash Amount</th>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: auto;">Comments</th>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: auto;">Start Km Attach</th>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: auto;">End Km Attach</th>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: auto;">Gas Receipt</th>
							   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: auto;">Deposit Cash Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(!empty($results)) {
								$total_km=0;
								$total_drivers=0;
								$no_of_pickups=0;
								$no_of_deliveries=0;
								$total_gas=0;
								$total_supplier_fees=0;
								$total_delivery_fees=0;
								$total_cash=0;
								$total_bank_deposit=0;
								while ($row = mysqli_fetch_assoc($results)) {
									if(!empty($row['start_km']) &&!empty($row['trip_km']) &&!empty($row['gas_amount']) &&!empty($row['deposit_amount'])){
										$total_drivers++;
										$driver = $row['driver'];
										$datee = date("m/d/Y",strtotime($row['date']));
										$pick=mysqli_query($con,"Select * from deliver,orders where orders.id=deliver.order_id and (orders.status='completed' or orders.status='delivered' or orders.status='in process') and (deliver.status='completed' or deliver.status='delivered' or deliver.status='in process') and deliver.driver_id=$driver and orders.pickup_date='$datee'") or die(mysqli_error($con));
										$pickups=mysqli_num_rows($pick);
										$supplier_fees=0;
										$delivery_fees=0;
										while($fees=mysqli_fetch_array($pick)){
											$supplier_fees+=$fees['collection_amount'];
											$delivery_fees+=$fees['price'];
										}
										$deli=mysqli_query($con,"Select * from deliver,orders where orders.id=deliver.order_id and (orders.status='completed' or orders.status='delivered') and (deliver.status='completed' or deliver.status='delivered') and deliver.deliver_driver_id=$driver and orders.delivery_date='$datee'") or die(mysqli_error($con));
										$deliveries=mysqli_num_rows($deli);
										while($fees=mysqli_fetch_array($deli)){
											$supplier_fees+=$fees['collection_amount'];
											$delivery_fees+=$fees['price'];
										}
										$driver = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE id = $driver"));
									echo '<tr>';
										if($type != 'driver')
											echo '<td>'.$driver['Name'].'</td>';
										$total_km+=((int)$row['trip_km']-(int)$row['start_km']);
										$no_of_pickups+=$pickups;
										$no_of_deliveries+=$deliveries;
										$total_gas+=$row['gas_amount'];
										$total_supplier_fees+=$supplier_fees;
										$total_delivery_fees+=$delivery_fees;
										$total_cash+=$row['cash_amount'];
										$total_delivery_fees+=$delivery_fees;
										$total_bank_deposit+=$row['deposit_amount'];
										echo '<td>'.$row['start_km'].'</td>';
										echo '<td>'.$row['trip_km'].'</td>';
										// echo '<td>'.(int)$row['trip_km']-(int)$row['start_km'].'</td>';
										echo '<td>'.((int)$row['trip_km']-(int)$row['start_km']).'</td>';
										echo '<td>'.$pickups.'</td>';
										echo '<td>'.$deliveries.'</td>';
										echo '<td>'.$row['gas_amount'].'</td>';
										echo '<td>'.$supplier_fees.'</td>';
										echo '<td>'.$delivery_fees.'</td>';
										echo '<td>'.$row['deposit_amount'].'</td>';
										echo '<td>'.$row['cash_amount'].'</td>';
										echo '<td>'.$row['any_comment'].'</td>';
										echo '<td>';
										if($row['start_km_attach'] != '')
											echo '<a download href="'.$row['start_km_attach'].'">Start KM attach</a>';
										echo '</td>';
										echo '<td>';
										if($row['trip_km_attach'] != '')
											echo '<a download href="'.$row['trip_km_attach'].'">Start KM attach</a>';
										echo '</td>';
										echo '<td>';
										if($row['gas_amount_attach'] != '')
											echo '<a download href="'.$row['gas_amount_attach'].'">Start KM attach</a>';
										echo '</td>';
										echo '<td>';
										if($row['deposit_amount_attach'] != '')
											echo '<a download href="'.$row['deposit_amount_attach'].'">Start KM attach</a>';
										echo '</td>';
									echo '</tr>';
									}
									// $i++;
									
								}
								?>
								<tfoot>
								 <?php if($type != 'driver') { ?>
							   
									<th>Total Drivers:<?php echo $total_drivers; ?></th>
									<?php
								 }
									?>
									<th></th>
									<th></th>
									<th>Total Km's:<?php echo $total_km; ?></th>
									<th>Total No.of Pickups:<?php echo $no_of_pickups; ?></th>
									<th>Total No.of Deliveries:<?php echo $no_of_deliveries; ?></th>
									<th>Total Gas:<?php echo $total_gas; ?></th>
									<th>Total Sender Fees:<?php echo $total_supplier_fees; ?></th>
									<th>Total Delivery Fees:<?php echo $total_delivery_fees; ?></th>
									<th>Total Bank Deposit:<?php echo $total_bank_deposit; ?></th>
									<th>Total Cash:<?php echo $total_cash; ?></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									
								</tfoot>
							<?php
							
							}
							?>
						</tbody>
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