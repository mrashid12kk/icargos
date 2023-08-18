<?php

	if(isset($_POST['delete'])){

		$id=mysqli_real_escape_string($con,$_POST['id']);

		$query1=mysqli_query($con,"DELETE FROM `insurance_type` WHERE  id=$id") or die(mysqli_error($con));

		$rowscount=mysqli_affected_rows($con);

		if($rowscount>0){

			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a Insurance successfully</div>';

		}

		else{

			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete a Insurance successfully.</div>';

		}

	}

		
		


	$msg="";



	if(isset($_POST['addInsurance'])){



		// for($i=0;$i<count($_POST['insurance_name']);$i++)

		// {
		$insurance_name=mysqli_real_escape_string($con,$_POST['insurance_name']);

	 $insurance_rate=mysqli_real_escape_string($con,$_POST['insurance_rate']);


		



		$query1=mysqli_query($con,"INSERT INTO `insurance_type`(`name`,`rate`) VALUES ('".$insurance_name."', '".$insurance_rate."')") or die(mysqli_error($con));



		$rowscount=mysqli_affected_rows($con);



		if($rowscount>0){



			$msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new Insurance successfully</div>';



		/* 	$query=mysqli_query($con,"select * from admin");



			$fetch=mysqli_fetch_array($query);



			$reciever=$fetch['email'];



			$subject = "Signup Request";



			$txt = "$user_name send a signup request to you please check the the details from admin panel";



			$headers = "From: $email" . "\r\n";



			mail('muhammad.usman93333@gmail.com',$subject,$txt,$headers);*/



			}



		else{



			$msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new Insurance successfully.</div>';



		}



		//}



	}

echo $msg;
if(isset($_POST['updateInsurance'])){



			$id=mysqli_real_escape_string($con,$_POST['edit_id']);



			$insurance_name=mysqli_real_escape_string($con,$_POST['insurance_name']);

			$insurance_value=mysqli_real_escape_string($con,$_POST['insurance_rate']);

			//$charge_type=mysqli_real_escape_string($con,$_POST['charge_type']);



			$query2=mysqli_query($con,"update insurance_type set name='$insurance_name', rate = '$insurance_value' where id=$id") or die(mysqli_error($con));

			$rowscount=mysqli_affected_rows($con);

			



			if($query2){



				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a Insurance successfully</div>';



			}



			else{



				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a Insurance successfully.</div>';



			}
			}


if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
            
	$id = $_GET['edit_id'];
	$query1=mysqli_query($con,"select * from insurance_type where id=$id") or die(mysqli_error($con));
	$edit=mysqli_fetch_array($query1);
	              
}


		
?>
<div class="panel panel-default">



	<div class="panel-heading"><?php echo getLange('addinsurance'); ?></div>



	<div class="panel-body">



	



		<form role="form" data-toggle="validator" action="" method="post">



			<div id="charges"> 
				<div class="row">
					<div class="col-sm-4">
					<div class="form-group">



					<label  class="control-label"><span style="color: red;">*</span><?php echo getLange('insurancename'); ?></label>



					<input type="text" class="form-control" name="insurance_name" placeholder="<?php echo getLange('enter').' '.getLange('insurancename'); ?>" value="<?php if(isset($edit)){echo $edit['name']; } ?>" required>



					<div class="help-block with-errors "></div>



				</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">



					<label  class="control-label"><span style="color: red;">*</span><?php echo getLange('insurancevalue'); ?></label>



					<input type="text" class="form-control" name="insurance_rate" placeholder="<?php echo getLange('enter').' '.getLange('insurancevalue'); ?>" value="<?php if(isset($edit)){echo $edit['rate']; } ?>" required>



					<div class="help-block with-errors "></div>



				</div>
				</div>
				
				</div>
				

				 



			</div>


			<div>

			</div>
			<input type="hidden" name="<?php if(isset($edit)){echo 'edit_id'; } ?>" value="<?php if(isset($edit)){echo $_GET['edit_id']; } ?>">
			<button type="submit" name="<?php if(isset($edit)){ echo 'updateInsurance'; }else{echo 'addInsurance';} ?>" class="add_form_btn" ><?php echo getLange('submit'); ?></button>



		</form>



	



	</div>



</div>
<div class="panel panel-default">

	<div class="panel-heading"><?php echo getLange('insurancelist'); ?>

		<!-- <a href="addcities.php" class="btn btn-info pull-right" style="margin-top:-7px;" ><i class="fa fa-plus"></i>Add New City</a> -->

	</div>

		<div class="panel-body" id="same_form_layout" style="padding: 11px;">

			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

				

					

						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 

							<thead>

								<tr role="row">

								   <th style="width: 44%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('insurancename'); ?></th>

								   <th style="width: 44%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('insurancevalue'); ?></th>

								   <!--th style="width: 44%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Type</th-->

								  <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;"><?php echo getLange('action'); ?></th>

								</tr>

							</thead>

							<tbody>

							<?php

								

							

								$query1=mysqli_query($con,"Select * from insurance_type order by id desc");

								while($fetch1=mysqli_fetch_array($query1)){

									// print_r($query1);

									// die;

							?>

								<tr class="gradeA odd" role="row">

									<td class="sorting_1"><?php echo $fetch1['name']; ?></td>

									<td class="sorting_1"><?php echo $fetch1['rate']; ?></td>

									<!--td class="sorting_1"><?php //if($fetch1['charge_type']==1){echo 'Fixed Amount'; }  else{echo 'Percentage'; } ; ?></td-->

									<td class="center inline_Btn">

										<!-- <a href="editcharges.php" name="id" value="<?php echo $fetch1['id']; ?>"><span class="glyphicon glyphicon-edit"></span></a>



										<a href="#" name="delete" value="<?php echo $fetch1['id']; ?>" onclick="return confirm('Are are sure you want to delete this charge?')"><span class="glyphicon glyphicon-trash"></span></a> -->

										<form action="insuranceLists.php" method="get" style="display: inline-block;">

											<input type="hidden" name="edit_id" value="<?php echo $fetch1['id']; ?>">

											<button type="submit" name="edit"  >

											  <span class="glyphicon glyphicon-edit"></span> 

											</button>

											</form>

											<form action="" method="post" style="display: inline-block;">

											<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

											<button type="submit" name="delete" onclick="return confirm('Are are sure you want to delete this insurance?')" >

											  <span class="glyphicon glyphicon-trash"></span> 

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