<?php 

		if(isset($_POST['updateInsurance'])){



			$id=mysqli_real_escape_string($con,$_POST['id']);



			$insurance_name=mysqli_real_escape_string($con,$_POST['insurance_name']);

			$insurance_value=mysqli_real_escape_string($con,$_POST['insurance_value']);

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



		



	



	if(isset($_POST['id'])){



		$id=mysqli_real_escape_string($con,$_POST['id']);



		$query1=mysqli_query($con,"select * from insurance_type where id=$id") or die(mysqli_error($con));



		$fetch1=mysqli_fetch_array($query1);

		 



	}



?>
<div class="row">
                <?php
            require_once "setup-sidebar.php";
          ?>
          <div class="col-sm-10 table-responsive" id="setting_box">


<div class="panel panel-default">



	<div class="panel-heading">Edit Insurance</div>



	<div class="panel-body">



	



		<form role="form" class="" data-toggle="validator" action="" method="post">



			<div class="form-group">



				<label  class="control-label">Insurance Name</label>

				

				<input type="text"  class="form-control" value="<?php echo isset($fetch1['name'])?$fetch1['name']:""; ?>" name="insurance_name" placeholder="Enter Insurance name" required>



				<div class="help-block with-errors "></div>



			</div>



			<div class="form-group">



				<label  class="control-label">Insurance Value</label>

				 

				<input type="text"  class="form-control" value="<?php echo isset($fetch1['rate'])?$fetch1['rate']:""; ?>" name="insurance_value" placeholder="Enter Insurance Value" required>



				<div class="help-block with-errors "></div>



			</div>



			<!--div class="form-group">



					<label  class="control-label">Charge Type</label>



					<select class="form-control" name="charge_type" required>
						<option value="">Select</option>

						<option value="1" <?php// if($fetch1['charge_type']==1){ echo 'selected'; } ?>>Fixed Amount</option>

						<option value="2" <?php //if($fetch1['charge_type']==2){ echo 'selected'; } ?>>Percentage</option>

					</select>

					

					<div class="help-block with-errors "></div>



				</div-->

			

				<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">



		 <button type="submit" name="updateInsurance" class="btn btn-purple add_form_btn" >Update</button>



		</form>



	



	</div>



</div>
</div>
</div>



<?php



	



?>