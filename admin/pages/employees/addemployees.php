<?php

	if(isset($_POST['addemployees'])){

		$Name=mysqli_real_escape_string($con,$_POST['Name']);

		$user_name=mysqli_real_escape_string($con,$_POST['user_name']);

		$email=mysqli_real_escape_string($con,$_POST['email']);

		$password=mysqli_real_escape_string($con,password_hash($_POST['password'],PASSWORD_DEFAULT));
		$branch_id=mysqli_real_escape_string($con,$_POST['branch_id']);
		$query1=mysqli_query($con,"INSERT INTO `users`(`Name`,`user_name`,`email`, `password`,`type`, `branch_id`) VALUES ('$Name','$user_name','$email','$password','employee', $branch_id)") or die(mysqli_error($con));

		$rowscount=mysqli_affected_rows($con);

		if($rowscount>0){

			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new employee successfully</div>';

		/* 	$query=mysqli_query($con,"select * from admin");

			$fetch=mysqli_fetch_array($query);

			$reciever=$fetch['email'];

			$subject = "Signup Request";

			$txt = "$user_name send a signup request to you please check the the details from admin panel";

			$headers = "From: $email" . "\r\n";

			mail('muhammad.usman93333@gmail.com',$subject,$txt,$headers);*/

			}

		else{

			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new employee unsuccessfully.</div>';

		}

	}





?>

<div class="panel panel-default">

	<div class="panel-heading">Add employees</div>

	<div class="panel-body">

	

		<form role="form" class="validateform" data-toggle="validator" action="" method="post">

		<div class="form-group">

			<label  class="control-label">Name</label>

			<input type="text" class="form-control" name="Name" placeholder="Enter name" required>

			<div class="help-block with-errors "></div>

		

		 </div>
		 <div class="form-group">

			<label  class="control-label">Branch</label>

			<select name="branch_id" class="form-control">
				<option value="">SELECT Branch</option>
				<?php
				$query = mysqli_query($con, "SELECT * FROM branches");
				if($query) {
					while($row = mysqli_fetch_object($query)) {
						echo '<option value="'.$row->id.'">'.$row->name.'</option>';
					}
				}
				?>
			</select>

			<div class="help-block with-errors "></div>

		

		 </div>

		<div class="form-group">

			<label  class="control-label">Username</label>

			<input type="text" name="user_name" class="form-control user_name" placeholder="Enter username" required>

			<div class="help-block with-errors user_errorr">

			</div>

		

		 </div>

		  <div class="form-group">

			<label for="exampleInputEmail1">Email address</label>

			<input type="email" class="form-control emaill" name="email" placeholder="Enter email" required>

			<div class="help-block with-errors email_errorr"></div>

		</div>

		  

		  <div class="form-group">

			<label  class="control-label">Password</label>

			<input type="Password" class="form-control" id="passwordboot" name="password" placeholder="Enter password" required>

			<div class="help-block with-errors"></div>

		

		 </div>

		<div class="form-group">

			<label for="exampleInputPassword1">Confirm Password</label>

			<input type="password" class="form-control" data-match="#passwordboot" placeholder="Confirm Password">

			<div class="help-block with-errors"></div>

		

		 </div>

		 <button type="submit" name="addemployees" class="btn btn-purple editp" >Submit</button>

		</form>

	

	</div>

</div>