<?php 

		if(isset($_POST['updateCharges'])){



			$id=mysqli_real_escape_string($con,$_POST['id']);



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



		



	



	if(isset($_POST['id'])){



		$id=mysqli_real_escape_string($con,$_POST['id']);



		$query1=mysqli_query($con,"select * from charges where id=$id") or die(mysqli_error($con));



		$fetch1=mysqli_fetch_array($query1);

		 



	}



?>


<div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">
<div class="panel panel-default">



	<div class="panel-heading">Edit Charges</div>



	<div class="panel-body">



	



		<form role="form" class="" data-toggle="validator" action="" method="post">



			<div class="form-group">



				<label  class="control-label">Charge Name</label>

				

				<input type="text"  class="form-control" value="<?php echo isset($fetch1['charge_name'])?$fetch1['charge_name']:""; ?>" name="charge_name" placeholder="Enter Charge name" required>



				<div class="help-block with-errors "></div>



			</div>



			<div class="form-group">



				<label  class="control-label">Charge Value</label>

				 

				<input type="text"  class="form-control" value="<?php echo isset($fetch1['charge_value'])?$fetch1['charge_value']:""; ?>" name="charge_value" placeholder="Enter City code" required>



				<div class="help-block with-errors "></div>



			</div>



			<div class="form-group">



					<label  class="control-label">Charge Type</label>



					<select class="form-control" name="charge_type" required>
						<option value="">Select</option>

						<option value="1" <?php if($fetch1['charge_type']==1){ echo 'selected'; } ?>>Fixed Amount</option>

						<option value="2" <?php if($fetch1['charge_type']==2){ echo 'selected'; } ?>>Percentage</option>

					</select>

					

					<div class="help-block with-errors "></div>



				</div>

			

				<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">



		 <button type="submit" name="updateCharges" class="btn btn-purple add_form_btn" >Update</button>



		</form>



	



	</div>



</div>
</div>
</div>



<?php



	



?>