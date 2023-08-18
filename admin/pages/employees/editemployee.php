<?php

		if(isset($_POST['updateemployees'])){

			$id=mysqli_real_escape_string($con,$_POST['id']);

			$Name=mysqli_real_escape_string($con,$_POST['Name']);

			$phone=mysqli_real_escape_string($con,$_POST['phone']);
			$branch_id=mysqli_real_escape_string($con,$_POST['branch_id']);

			$query2=mysqli_query($con,"update users set Name='$Name',phone='$phone', branch_id = '$branch_id' where id=$id") or die(mysqli_error($con));

			$rowscount=mysqli_affected_rows($con);

			if($rowscount>0){

				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated an employee successfully</div>';

				echo "<script>document.location.href='employeesdata.php';</script>";

			}

			else{

				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated an employee unsuccessfully.</div>';

			}

		}

	

	if(isset($_POST['edit'])){

		$id=mysqli_real_escape_string($con,$_POST['id']);

		$query1=mysqli_query($con,"select * from users where id=$id") or die(mysqli_error($con));

		$fetch1=mysqli_fetch_array($query1);

	}

?>

<div class="panel panel-default">

	<div class="panel-heading">Add employees</div>

	<div class="panel-body">

	

		<form role="form" class="" data-toggle="validator" action="" method="post">

		<div class="form-group">

			<label  class="control-label">Name</label>

			<input type="text" class="form-control" name="Name" value="<?php echo $fetch1['Name']; ?>" placeholder="Enter name" required>

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
						if(isset($fetch1['branch_id']) && $fetch1['branch_id'] == $row->id)
							echo '<option selected value="'.$row->id.'">'.$row->name.'</option>';
						else
							echo '<option value="'.$row->id.'">'.$row->name.'</option>';
					}
				}
				?>
			</select>

			<div class="help-block with-errors "></div>

		

		 </div>
		<div class="form-group">

			<label  class="control-label">Username</label>

			<input type="text" name="user_name" class="form-control" value="<?php echo $fetch1['user_name']; ?>"  placeholder="Enter username" disabled>

			<div class="help-block with-errors">

			</div>

		

		 </div>

			  <div class="form-group">

				<label for="exampleInputEmail1">Email address</label>

				<input type="email" class="form-control " name="email"  value="<?php echo $fetch1['email']; ?>" placeholder="Enter email" disabled>

				<div class="help-block with-errors "></div>

				</div>

			  <div class="form-group">

				<label for="exampleInputEmail1">Phone no.</label>

				<input type="text" class="form-control " name="phone" value="<?php echo $fetch1['phone']; ?>"  placeholder="Enter Phone no." >

				<div class="help-block with-errors "></div>

			</div>

			<input type="hidden" name='id' value="<?php echo $id;?>">

		 <button type="submit" name="updateemployees" class="btn btn-purple" >Update</button>

		</form>

	

	</div>

</div>

<?php

	

?>