<?php

	if(isset($_POST['addemployees'])){

		$Name=mysqli_real_escape_string($con,$_POST['Name']);
		$user_name=mysqli_real_escape_string($con,$_POST['user_name']);
		$email=mysqli_real_escape_string($con,$_POST['email']);
		$staff_id=mysqli_real_escape_string($con,$_POST['staff_id']);
		$cnic=mysqli_real_escape_string($con,$_POST['cnic']);
		$phone=mysqli_real_escape_string($con,$_POST['phone']);
		$pickup_comm=mysqli_real_escape_string($con,$_POST['pickup_comm']);
		$delivery_comm=mysqli_real_escape_string($con,$_POST['delivery_comm']);
		$password=mysqli_real_escape_string($con,$_POST['password']);
		$user_role_id=mysqli_real_escape_string($con,$_POST['user_role_id']);
		$branch=mysqli_real_escape_string($con,$_POST['branch_id']);
		$type=mysqli_real_escape_string($con,$_POST['type']);
		$password = mysqli_real_escape_string($con,password_hash($password,PASSWORD_DEFAULT));

		$selected_branch = '';
		if (isset($branch) && !empty($branch)) {
			$selected_branch = $branch;

		}else{
			$selected_branch = $_SESSION['branch_id'];
		}

		$query = "INSERT INTO `users`(`Name`,`user_name`,`email`,`type`,`status`,`staff_id`,`cnic`,`phone`,`pickup_comm`,`delivery_comm`,`password`,`user_role_id`,`branch_id`,`ledgerid`) VALUES ('$Name','$user_name','$email','".$type."','complete','$staff_id','$cnic','".$phone."','".$pickup_comm."','".$delivery_comm."','".$password."','".$user_role_id."',".$selected_branch.",'".$_POST['cashledger']."')";

		$query1=mysqli_query($con,$query) or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new user successfully</div>';
			}
		else{
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new user unsuccessfully.</div>';
		}
	}


	$user_roles_list = mysqli_query($con,"SELECT * FROM user_role order by id desc ");
	$branches = mysqli_query($con,"SELECT * FROM branches");
?>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('add').' '.getLange('employee'); ?></div>
	<div class="panel-body">

		<form role="form"  action="" method="post">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
				<label  class="control-label"><?php echo getLange('name'); ?></label>
				<input type="text" class="form-control" name="Name" placeholder="<?php echo getLange('enter').' '.getLange('name'); ?>" required>
				<div class="help-block with-errors "></div>
			</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<label for="exampleInputEmail1"><?php echo getLange('type'); ?></label>
					<select name="type" class="form-control">
						<option value="admin">Administrator User</option>
						<option value="driver">Rider User</option>

					</select>
					<div class="help-block with-errors  "></div>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<label for="exampleInputEmail1"><?php echo getLange('role'); ?></label>
					<select name="user_role_id" class="form-control" required="">
						<option value=""><?php echo getLange('select'); ?></option>
					 	<?php while ($row = mysqli_fetch_array($user_roles_list)) { ?>
					 		<option value="<?php echo $row['id'] ?>"> <?php echo $row['name'] ?> </option>
					 	<?php } ?>
					</select>
					<div class="help-block with-errors  "></div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="exampleInputEmail1"><?php echo getLange('email').' '.getLange('address'); ?></label>
					<input type="email" class="form-control emaill" name="email" placeholder="<?php echo getLange('enter').' '.getLange('email'); ?>" >
					<div class="help-block with-errors email_errorr"></div>
				</div>
			</div>


		</div>
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label for="exampleInputEmail1"><?php echo getLange('password'); ?></label>
					<input type="password" class="form-control " name="password" placeholder="<?php echo getLange('enter').' '.getLange('password'); ?>" >
					<div class="help-block with-errors  "></div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="exampleInputEmail1"><?php echo getLange('phoneno') ?></label>
					<input type="number" class="form-control" name="phone" placeholder="<?php echo getLange('phoneno'); ?>" >
					<div class="help-block with-errors "></div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label  class="control-label"><?php echo getLange('staffid'); ?></label>
					<input type="text" name="user_name" class="form-control user_name" placeholder="<?php echo getLange('enter').' '.getLange('staffid'); ?>" required>
					<div class="help-block with-errors user_errorr">
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="exampleInputEmail1"><span style="color: red;">*</span><?php echo getLange('cnic'); ?></label>
					<input type="number" class="form-control" name="cnic" placeholder="<?php echo getLange('cnic'); ?>" required>
					<div class="help-block with-errors "></div>
				</div>
			</div>

		</div>
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label for="exampleInputEmail1"><?php echo getLange('pickupcommision'); ?></label>
					<input type="text" class="form-control" name="pickup_comm" value="0" placeholder="<?php echo getLange('pickupcommision'); ?>" required>
					<div class="help-block with-errors "></div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label for="exampleInputEmail1"><?php echo getLange('deliverycommision'); ?></label>
					<input type="text" class="form-control" name="delivery_comm" value="0" placeholder="<?php echo getLange('deliverycommision'); ?>" required>
					<div class="help-block with-errors "></div>
				</div>
			</div>
			<?php 

			if ($_SESSION['branch_id'] == 1): ?>
				<div class="col-sm-3">
					<div class="form-group">
						<label for="exampleInputEmail1"><?php echo getLange('branch'); ?></label>
						<select class="form-control js-example-basic-single branch" name="branch_id" required="required">
							<option>SELECT BRANCH</option>
						<?php foreach($branches as $branch){ ?>
						<option value="<?php echo $branch['id']; ?>"><?php echo $branch['name']; ?></option>
						<?php } ?>
						</select>
						<div class="help-block with-errors "></div>
					</div>
				</div>
			<?php else: ?>
				<div class="col-sm-3">
					<div class="form-group">
						<label for="exampleInputEmail1"><?php echo getLange('branch'); ?></label>
						<input type="text" class="form-control branch" value="<?php echo $current_branch; ?>" readonly>
						<div class="help-block with-errors "></div>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-sm-3">
					<div class="form-group">
				<div id="cashledger"></div>
			</div>
		</div>
			<!-- <div class="form-group">
				<label>Branch</label>
				<select class="form-control js-example-basic-single" name="branch_id">
					<?php foreach($branches as $branch){ ?>
				<option  <?php //if(isset($_GET['customer_id']) && $_GET['customer_id'] == $customer['id']){ echo "Selected"; } ?> value="<?php echo $branch['id']; ?>"><?php echo $branch['name']; ?></option>
				<?php } ?>
				</select>
			</div> -->
		</div>

	 	<button style="margin: 0 0 8px 15px;" type="submit" name="addemployees" class="btn btn-purple editp" ><?php echo getLange('submit'); ?></button>
		</form>

	</div>
</div>
