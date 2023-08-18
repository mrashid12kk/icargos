<?php
session_start();
$branch_id = $_SESSION['branch_id'];
	if(isset($_POST['delete'])){

		$id=mysqli_real_escape_string($con,$_POST['id']);

		$query1=mysqli_query($con,"delete from pricing where id=$id") or die(mysqli_error($con));

		$query1=mysqli_query($con,"delete from pricing where id=$id") or die(mysqli_error($con));

		$rowscount=mysqli_affected_rows($con);

		if($rowscount>0){

			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete an employee successfully</div>';

		}

		else{

			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete an employee unsuccessfully.</div>';

		}

	}

?>

<div class="panel panel-default">

	<div class="panel-heading">Pricing Data</div>

		<div class="panel-body" id="same_form_layout" style="padding:10px; ">

			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

				<div class="row">

					<div class="col-sm-12 table-responsive">

						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 

							<thead>

								<tr role="row">
									<th class="sorting_asc" style="    width: 31px;" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Sr No</th>
								   <th class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Zone</th>
								

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">City</th>

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">0.5 kg price (Rs)</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">1kg Price (Rs)</th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Additional kg (Rs)</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;">Action</th>

								</tr>

							</thead>

							<tbody>

							<?php

								if(isset($_GET['id'])){

									$id=mysqli_real_escape_string($con,$_GET['id']);

									$query1=mysqli_query($con,"Select * from users where id=$id AND branch_id='".$branch_id."' ");

									while($fetch1=mysqli_fetch_array($query1)){

							?>

									<tr class="gradeA odd" role="row">

									<td class="sorting_1"><?php echo $fetch1['Name']; ?></td>

									<!-- <td><img src="<?php echo $fetch1['image']; ?>" width="100" class="img-circle"></td> -->


									<td class="center"><?php echo $fetch1['email']; ?></td>

									<td class="center"><?php echo $fetch1['phone']; ?></td>
									<td class="center"><?php echo $fetch1['cnic']; ?></td>

									<td class="center">

										<form action="editdrivers.php" method="post" >

											<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

											<button type="submit" name="edit" class="btn_stye_custom" >

											  <span class="glyphicon glyphicon-edit"></span>  

											</button>

										</form>

									

										<form action="" method="post">

											<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

											<button type="submit" name="delete" class="btn_stye_custom" onclick="return confirm('Are You Sure Delete this Employee')" >

											  <span class="glyphicon glyphicon-trash"></span>  

											</button>

										</form>

									</td>

								</tr>

								<?php

									}

								}

								else{

								$query1=mysqli_query($con,"Select * from pricing where 1 order by zone ");
								$sr=1;
								while($fetch1=mysqli_fetch_array($query1)){

							?>



								<tr class="gradeA odd" role="row">

									<td class="sorting_1"><?php echo $sr; ?></td>
									<td class="sorting_1"><?php echo $fetch1['zone']; ?></td>


									<td class="center"><?php echo $fetch1['city']; ?></td>

									<td class="center"><?php echo $fetch1['point_5_kg']; ?></td>
									<td class="center"><?php echo $fetch1['1_kg']; ?></td>
									<td class="center"><?php echo $fetch1['additional_kg']; ?></td>
									<td class="center">

										<a href="editpricing.php?id=<?php echo $fetch1['id'] ?>"><i class="fa fa-edit"></i></a>

									

										<!-- <form action="" method="post">

											<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

											<button type="submit" name="delete" class="btn_stye_custom" onclick="return confirm('Are You Sure Delete this Price')" >

											  <span class="glyphicon glyphicon-trash"></span>  

											</button>

										</form> -->

									</td>

								</tr>

								<?php
								$sr++;
									}

								}

								

								?>

							</tbody>

						</table>

			

				</div>

			</div>

		</div>

	</div>

</div>