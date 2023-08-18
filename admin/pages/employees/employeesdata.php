<?php

	if(isset($_POST['delete'])){

		$id=mysqli_real_escape_string($con,$_POST['id']);

		$query1=mysqli_query($con,"delete from users where id=$id") or die(mysqli_error($con));

		$rowscount=mysqli_affected_rows($con);

		if($rowscount>0){

			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a driver successfully</div>';

		

			}

		else{

			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete a driver unsuccessfully.</div>';

		}

	}

?>

<div class="panel panel-default">

	<div class="panel-heading">Employees Data</div>

		<div class="panel-body">

			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

				<div class="row">

					<div class="col-sm-12 table-responsive">

						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 

							<thead>

								<tr role="row">

								   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Name</th>

								    <!-- <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 150px;">Image</th> -->

								  

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Username</th>

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Email</th>

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Phone #</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Branch</th>

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;">Opertaions</th>

								</tr>

							</thead>

							<tbody>

							<?php

								$query1=mysqli_query($con,"Select * from users where type='employee' order by id desc");

								while($fetch1=mysqli_fetch_array($query1)){

									if(isset($fetch1['branch_id'])) {
										$branch = mysqli_query($con, "SELECT * FROM branches WHERE id = ".$fetch1['branch_id']);
										$branch = ($branch) ? mysqli_fetch_object($branch) : null;
									}
							?>



								<tr class="gradeA odd" role="row">

									<td class="sorting_1"><?php echo $fetch1['Name']; ?></td>

									<!-- <td><img src="<?php echo $fetch1['image']; ?>" width="100" class="img-circle"></td> -->

									<td><?php echo $fetch1['user_name']; ?></td>

									<td class="center"><?php echo $fetch1['email']; ?></td>

									<td class="center"><?php echo $fetch1['phone']; ?></td>
									<td class="center"><?php echo isset($branch->name) ? $branch->name : ''; ?></td>

									<td class="center">

										<form action="editemployee.php" method="post" >

											<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

											<button type="submit" name="edit" class="btn btn-info" >

											  <span class="glyphicon glyphicon-edit"></span> Edit

											</button>

										</form>

									

										<form action="" method="post">

											<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

											<button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are You Sure Delete this Driver')" >

											  <span class="glyphicon glyphicon-trash"></span> Trash

											</button>

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