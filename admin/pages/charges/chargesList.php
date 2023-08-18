<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
	function checkChargeIdInOrderCharge($charge_id = null)
	{
		global $con;
		if($charge_id!=null)
		{
			$query_order_charges=mysqli_query($con,"SELECT id FROM order_charges WHERE  charges_id=$charge_id") or die(mysqli_error($con));
			// echo '<pre>',print_r($query_order_charges),'</pre>';exit();
			$countrow = mysqli_num_rows($query_order_charges);
			if($countrow == 0)
			{
				return 0;
			}
			else
			{
				return 1;
			}
		}
	}
	if(isset($_POST['delete'])){
		$id=mysqli_real_escape_string($con,$_POST['id']);
		$query1=mysqli_query($con,"DELETE FROM `charges` WHERE  id=$id") or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a charge successfully</div>';
		}
		else{
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete a charge successfully.</div>';
		}
	}
	$msg="";
	if(isset($_POST['addCharges'])){
		

		$query1=mysqli_query($con,"INSERT INTO `charges`(`charge_name`,`charge_value`,`charge_type`) VALUES ('".$_POST['charge_name']."', '".$_POST['charge_value']."', '".$_POST['charge_type']."')") or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			$msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new Charge successfully</div>';
		/* 	$query=mysqli_query($con,"select * from admin");
			$fetch=mysqli_fetch_array($query);
			$reciever=$fetch['email'];
			$subject = "Signup Request";
			$txt = "$user_name send a signup request to you please check the the details from admin panel";
			$headers = "From: $email" . "\r\n";
			mail('muhammad.usman93333@gmail.com',$subject,$txt,$headers);*/
			}
		else{
			$msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new Charge successfully.</div>';
		}
		
	}
	if(isset($_POST['updateCharges'])){



			$id=mysqli_real_escape_string($con,$_POST['edit_id']);



			$charge_name=mysqli_real_escape_string($con,$_POST['charge_name']);

			$charge_value=mysqli_real_escape_string($con,$_POST['charge_value']);

			$charge_type=mysqli_real_escape_string($con,$_POST['charge_type']);



			$query2=mysqli_query($con,"update charges set charge_name='$charge_name', charge_value = '$charge_value', charge_type = '$charge_type' where id=$id") or die(mysqli_error($con));

			$rowscount=mysqli_affected_rows($con);

			



			if($query2){



				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a Charge successfully</div>';



			}



			else{



				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a Charge successfully.</div>';



			}



		}
echo $msg;
if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
            
	$id = $_GET['edit_id'];
	$query1=mysqli_query($con,"select * from charges where id=$id") or die(mysqli_error($con));
		$edit=mysqli_fetch_array($query1);
	              
}
?>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('add').' '.getLange('charges'); ?></div>
<div class="panel-body">

		<form role="form" data-toggle="validator" action="" method="post">
			<div id="charges">
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
					<label  class="control-label"><span style="color: red;">*</span><?php echo getLange('charges').' '.getLange('name'); ?></label>
					<input type="text" class="form-control" name="charge_name" placeholder="<?php echo getLange('enter').' '.getLange('charges').' '.getLange('name'); ?>" value="<?php if(isset($edit)){echo $edit['charge_name']; } ?>" required>
					<div class="help-block with-errors "></div>
				</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
					<label  class="control-label"><span style="color: red;">*</span><?php echo getLange('chargesvalue'); ?></label>
					<input type="text" class="form-control" name="charge_value" placeholder="<?php echo getLange('enter').' '.getLange('chargesvalue'); ?>" value="<?php if(isset($edit)){echo $edit['charge_value']; } ?>" required>
					<div class="help-block with-errors "></div>
				</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
					<label  class="control-label"><?php echo getLange('chargestype'); ?></label>
					<select class="form-control" name="charge_type" required>
						<option value="1" <?php if(isset($edit) && $edit['charge_type']=='1'){echo $edit['service_code']; } ?>>Fixed Amount</option>
						<option value="2" <?php if(isset($edit) && $edit['charge_type']=='2'){echo $edit['service_code']; } ?>>Percentage</option>
					</select>

					<div class="help-block with-errors "></div>
				</div>
				</div>


			</div>


			</div>
			<div>
			</div>

				  <input type="hidden" name="<?php if(isset($edit)){echo 'edit_id'; } ?>" value="<?php if(isset($edit)){echo $_GET['edit_id']; } ?>">
			<button type="submit" name="<?php if(isset($edit)){ echo 'updateCharges'; }else{echo 'addCharges';} ?>" class="add_form_btn" ><?php echo getLange('submit'); ?></button>
		</form>

	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('chargeslist'); ?>
		<!-- <a href="addcities.php" class="btn btn-info pull-right" style="margin-top:-7px;" ><i class="fa fa-plus"></i>Add New City</a> -->
	</div>
		<div class="panel-body" id="same_form_layout" style="padding: 11px;">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
							<thead>
								<tr role="row">
								   <th style="width: 44%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('chargesname'); ?></th>
								   <th style="width: 44%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('chargesvalue'); ?></th>
								   <th style="width: 44%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('type'); ?></th>
								  <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;"><?php echo getLange('action'); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php


								$query1=mysqli_query($con,"SELECT * FROM charges ORDER BY id DESC");
								while($fetch1=mysqli_fetch_array($query1)){
							?>
								<tr class="gradeA odd" role="row">
									<td class="sorting_1"><?php echo $fetch1['charge_name']; ?></td>
									<td class="sorting_1"><?php echo $fetch1['charge_value']; ?></td>
									<td class="sorting_1"><?php if($fetch1['charge_type']==1){echo 'Fixed Amount'; }  else{echo 'Percentage'; } ; ?></td>
									<td class="center inline_Btn">
										<!-- <a href="editcharges.php" name="id" value="<?php echo $fetch1['id']; ?>"><span class="glyphicon glyphicon-edit"></span></a>
										<a href="#" name="delete" value="<?php echo $fetch1['id']; ?>" onclick="return confirm('Are are sure you want to delete this charge?')"><span class="glyphicon glyphicon-trash"></span></a> -->
										<form action="chargesLists.php" method="get" style="display: inline-block;">
											<input type="hidden" name="edit_id" value="<?php echo $fetch1['id']; ?>">
											<button type="submit" name="edit"  >
											  <span class="glyphicon glyphicon-edit"></span>
											</button>
										</form>
										<?php
											$flag = 0;
											if(isset($fetch1['id']))
											{
												$flag = checkChargeIdInOrderCharge($fetch1['id']);
											}
										?>
										<?php if($flag == 0){ ?>
											<form action="" method="post" style="display: inline-block;">
												<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">
												<button type="submit" name="delete" onclick="return confirm('Are are sure you want to delete this charge?')" >
												  <span class="glyphicon glyphicon-trash"></span>
												</button>
											</form>
										<?php } ?>
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
