<?php



	if(isset($_GET['delete'])){



		$id=mysqli_real_escape_string($con,$_GET['delete']);



		$query1=mysqli_query($con,"UPDATE customers SET is_delete=1 where id=$id") or die(mysqli_error($con));



		$rowscount=mysqli_affected_rows($con);



		if($rowscount>0){



			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You have rejected customer successfully</div>';



		}



		else{



			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> YYou have rejected customer successfully</div>';



		}



	}



?>



<div class="panel panel-default">



	<div class="panel-heading">Employees Data</div>



		<div class="panel-body" id="same_form_layout">



			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer same_inner_gapp">



				<div class="row">



					<div class="col-sm-12 table-responsive">



						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 



							<thead>



								<tr role="row">



								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Sr.No</th>

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Register Date</th>

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Customer Name</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Company Name</th>

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Email</th>

								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Phone</th>



								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;">Action</th>



								</tr>



							</thead>



							<tbody>



							<?php

							$sr = 1;

								$query1=mysqli_query($con,"Select * from customers WHERE status=0 order by id desc");



								while($fetch1=mysqli_fetch_array($query1)){



							?>







								<tr class="gradeA odd" role="row">

									<td><?php echo $sr; ?></td>

									<td class="center">



									<?php

										// echo date("jS F,Y",strtotime($fetch1['dates']));

										echo date("d/m/Y",strtotime($fetch1['dates']));

														 

									?>



									</td>

									<td class="center"><?php echo $fetch1['fname']; ?></td>
									<td class="center"><?php echo $fetch1['bname']; ?></td>

									<td class="center"><?php echo $fetch1['email']; ?></td>

									<td class="center"><?php echo $fetch1['mobile_no']; ?></td>

									<td class="center">
										<?php if($fetch1['is_delete'] == 0){ ?>
										<a class="btn btn-info" href="customer_detail.php?customer_id=<?php echo$fetch1['id']; ?>">View</a>
										<a href="approve_customer.php?id= <?php echo $fetch1['id'] ?> " class="btn btn-success">Approve</a>
									<a href="pending_customers.php?delete= <?php echo $fetch1['id'] ?> " class="btn btn-danger">Reject</a>
								<?php } ?>
								<?php if($fetch1['is_delete'] == 1){  ?>
									<a href="#" class="btn btn-danger" disabled>Rejected</a>
								<?php } ?>
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