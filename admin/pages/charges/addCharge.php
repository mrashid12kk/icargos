<?php



	$msg="";



	if(isset($_POST['addCharges'])){



		for($i=0;$i<count($_POST['charge_name']);$i++)

		{



		



		$query1=mysqli_query($con,"INSERT INTO `charges`(`charge_name`,`charge_value`,`charge_type`) VALUES ('".$_POST['charge_name'][$i]."', '".$_POST['charge_value'][$i]."', '".$_POST['charge_type'][$i]."')") or die(mysqli_error($con));



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



	}







echo $msg;



?>







<div class="panel panel-default">



	<div class="panel-heading">Add Charges</div>



<div class="panel-body">



	



		<form role="form" data-toggle="validator" action="" method="post">



			<div id="charges">
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">



					<label  class="control-label">Charge Name</label>



					<input type="text" class="form-control" name="charge_name[]" placeholder="Enter Charge name" required>



					<div class="help-block with-errors "></div>



				</div>
				</div>
				
				
			</div>


			<div class="row">
				
				<div class="col-sm-4">
					<div class="form-group">



					<label  class="control-label">Charge Vlaue</label>



					<input type="text" class="form-control" name="charge_value[]" placeholder="Enter Charge Value" required>



					<div class="help-block with-errors "></div>



				</div>
				</div>
				
			</div>

			<div class="row">
				
				
				<div class="col-sm-4">
					<div class="form-group">



					<label  class="control-label">Charge Type</label>



					<select class="form-control" name="charge_type[]" required>

						<option value="1">Fixed Amount</option>

						<option value="2">Percentage</option>

					</select>

					

					<div class="help-block with-errors "></div>



				</div>
				</div>
			</div> 



			</div>



			<div>


			</div>





			<button type="submit" name="addCharges" class="add_form_btn" >Submit</button>



		</form>



	



	</div>



</div>
</div>