<?php

	$msg="";

	if(isset($_POST['addcities'])){

		for($i=0;$i<count($_POST['city']);$i++){

		

		$query1=mysqli_query($con,"INSERT INTO `cities`(`city_name`,`gst`) VALUES ('".$_POST['city'][$i]."','".$_POST['gst'][$i]."')") or die(mysqli_error($con));

		$rowscount=mysqli_affected_rows($con);

		if($rowscount>0){

			$msg= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a  City/Area successfully</div>';

		/* 	$query=mysqli_query($con,"select * from admin");

			$fetch=mysqli_fetch_array($query);

			$reciever=$fetch['email'];

			$subject = "Signup Request";

			$txt = "$user_name send a signup request to you please check the the details from admin panel";

			$headers = "From: $email" . "\r\n";

			mail('muhammad.usman93333@gmail.com',$subject,$txt,$headers);*/

			}

		else{

			$msg= '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new City unsuccessfully.</div>';

		}

		}

	}



echo $msg;

?>



<div class="panel panel-default">

	<div class="panel-heading"><?php echo getLange('addcities'); ?> </div>

	<div class="panel-body">

	

		<form role="form" data-toggle="validator" action="" method="post">

			<div id="cities" class="col-sm-6">

			

				<div class="form-group">

					<label  class="control-label"><?php echo getLange('cityareaname'); ?></label>

					<input type="text" class="form-control" name="city[]" placeholder="Enter City/Area Name"  required>

					<div class="help-block with-errors "></div>

				</div>

			</div>
			<div  class="col-sm-6">

			

				<div class="form-group">

					<label  class="control-label"><?php echo getLange('gst'); ?>%</label>

					<input type="text" class="form-control" name="gst[]" placeholder="GST" value="0" required>

					<div class="help-block with-errors "></div>

				</div>

			</div>


			<div>

			<div class="row" >
				<div class="col-sm-12" style="margin-bottom: 10px;">
						<button type="button" id="addmore" class="btn btn-info btn-sm" ><?php echo getLange('addmorecities'); ?>  </button> <button style="margin-left: 3px; vertical-align: middle; padding: 5px 8px;" type="submit" name="addcities" class="add_form_btn" ><?php echo getLange('submit'); ?></button>
				</div>
			</div>

			</div>


			

		</form>

	

	</div>

</div>
</div>