<?php
	if(isset($_POST['receiving_status'])){
		$id=mysqli_real_escape_string($con,$_POST['id']);
		$receiving_status=mysqli_real_escape_string($con,$_POST['receiving_status']);
		$query1=mysqli_query($con,"update payments set receiving_status='$receiving_status' where id=$id") or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have successfully mark the receiving_status as '.$receiving_status.'</div>';
		}
		else{
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not mark the receiving_status as '.$receiving_status.'.</div>';
		}
	}
	if(isset($_POST['deliver'])){
		if($_FILES["attachment"]["name"]!=""){
			$target_dir="images/";
			$target_file = $target_dir .uniqid(). basename($_FILES["attachment"]["name"]);
			$extension = pathinfo($target_file,PATHINFO_EXTENSION);
			if($extension!=='php') {
				move_uploaded_file($_FILES["attachment"]["tmp_name"],$target_file);
			}
			
		}
		$id=mysqli_real_escape_string($con,$_POST['id']);
		$delivery_status=mysqli_real_escape_string($con,$_POST['delivery_status']);
		$query1=mysqli_query($con,"update payments set delivery_status='$delivery_status', attachment='$target_file' where id=$id") or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have successfully delivered</div>';
		}
		else{
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delivered unsuccessfully.</div>';
		}
	}
							
?>
<div class="panel panel-default">
	<div class="panel-heading">
	<?php
			
	?>
	Payments to be Deliver</div>
	
		<div class="panel-body">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 
							<thead>
								<tr role="row">
								   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Date</th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 150px;">Payment Type</th>
								   
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Receiver Information</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Amount</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Receiving Status</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;">Did you deliver?</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$query1=mysqli_query($con,"Select * from payments where driver_id=".$fetch['id']." and delivery_status is null order by id desc") or die(mysqli_error($con));
									while($fetch1=mysqli_fetch_array($query1)){
								?>

								<tr class="gradeA odd" role="row">
									<td class="sorting_1"><?php echo $fetch1['date']; ?></td>
									<td>
										<?php echo $fetch1['type']; ?>
									</td>
									<td class="center">
										<b>Name:</b> <?php echo $fetch1['rname']; ?><br>
										<b>Phone:</b> <?php echo $fetch1['rphone']; ?><br>
										<b>City:</b> <?php echo $fetch1['rcity']; ?><br>
										<b>raddress:</b> <?php echo $fetch1['raddress']; ?><br>
									</td>
									<td class="center">
										<?php echo $fetch1['amount']; ?>
									</td>
									<td class="center">
										<?php if($fetch1['receiving_status']==null){ ?>
										<form action="" method="post">
											<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>" >
											Did you receive or not?
											<input type="submit" name="receiving_status" class="btn btn-info" value="Yes" onclick="return confirm('Are you sure, you receive?')">
											<input type="submit" name="receiving_status" class="btn btn-danger" value="no" onclick="return confirm('Are you sure,you are not receive?')">
										</form>
										<?php
										}
										else{
											echo $fetch1['receiving_status'];
										}
										?>
									</td>
									<td class="center" >
										<form action="" method="post" enctype="multipart/form-data">
											<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>" >
											<div class="form-group">
												<div class=" col-lg-12">
													<select class="form-control" name="delivery_status">
														<option>Yes</option>
														<option>No</option>
													</select>
													<label  class="control-label">Add attachment</label>
													<input type="file" name="attachment" class="form-control"  >
												</div>
											</div>
											<input type="submit" name="deliver" class="btn btn-success" value="Submit" >
										</form>
									
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