<?php

if (isset($_POST['updateemployees'])) {

	$id = $_GET['id'];

	$Name = mysqli_real_escape_string($con, $_POST['Name']);

	$phone = mysqli_real_escape_string($con, $_POST['phone']);

	$staff_id = mysqli_real_escape_string($con, $_POST['staff_id']);
	$cnic = mysqli_real_escape_string($con, $_POST['cnic']);
	$email = mysqli_real_escape_string($con, $_POST['email']);
	$pickup_comm = mysqli_real_escape_string($con, $_POST['pickup_comm']);
	$delivery_comm = mysqli_real_escape_string($con, $_POST['delivery_comm']);
	$password = mysqli_real_escape_string($con, $_POST['password']);
	$user_role_id = mysqli_real_escape_string($con, $_POST['user_role_id']);
	$branch_id = mysqli_real_escape_string($con, $_POST['branch_id']);
	$selected_branch = '';
	if (isset($branch_id) && !empty($branch_id)) {
		$selected_branch = $branch_id;
	} else {
		$selected_branch = $_SESSION['branch_id'];
	}
	$updateSql = "UPDATE users set Name='$Name',phone='$phone',staff_id='$staff_id', cnic='" . $cnic . "',email='" . $email . "',pickup_comm='" . $pickup_comm . "',delivery_comm='" . $delivery_comm . "',user_role_id='" . $user_role_id . "' ,ledgerid = '".$_POST['cashledger']."' where id=$id";
	// echo $updateSql;
	// die;
	$query2 = mysqli_query($con, $updateSql) or die(mysqli_error($con));

	$rowscount = mysqli_affected_rows($con);
	// echo $rowscount;
	// die;
	if (isset($branch_id) && $branch_id != '') {

		$query2 = mysqli_query($con, "UPDATE users set branch_id=" . $selected_branch . " where id=$id") or die(mysqli_error($con));
	}

	if (isset($password) && $password != '') {
		$passwordhash = mysqli_real_escape_string($con, password_hash($password, PASSWORD_DEFAULT));
		$query2 = mysqli_query($con, "UPDATE users set password='" . $passwordhash . "' where id=$id") or die(mysqli_error($con));
	}


	if ($rowscount > 0) {

		echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> user updated successfully</div>';

		// echo "<script>document.location.href='driversdata.php';</script>";
	} else {

		echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Error!</strong> Error occured try again later.</div>';
	}
}

$user_roles_list = mysqli_query($con, "SELECT * FROM user_role order by id desc ");
$branches = mysqli_query($con, "SELECT * FROM branches");

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$query1 = mysqli_query($con, "select * from users where id=$id") or die(mysqli_error($con));

	$fetch1 = mysqli_fetch_array($query1);
}

?>

<div class="panel panel-default">

    <div class="panel-heading"><?php echo getLange('update') . ' ' . getLange('employee'); ?></div>

    <div class="panel-body">



        <form role="form" class="" action="" method="post">
            <div class="row">
                <div class="form-group col-sm-3">

                    <label class="control-label"><?php echo getLange('name'); ?></label>

                    <input type="text" class="form-control" name="Name" value="<?php echo $fetch1['Name']; ?>"
                        placeholder="Enter name" required>

                    <div class="help-block with-errors "></div>



                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo getLange('type'); ?></label>
                        <select name="type" class="form-control">
                            <option value="admin" <?php if ($fetch1['type'] == 'admin') {
														echo 'selected';
													} ?>>Admin</option>
                            <option value="driver" <?php if ($fetch1['type'] == 'driver') {
														echo 'selected';
													} ?>>Rider</option>

                        </select>
                        <div class="help-block with-errors  "></div>
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="exampleInputEmail1"><?php echo getLange('role'); ?></label>
                    <select name="user_role_id" class="form-control" required="">
                        <option value=""><?php echo getLange('select'); ?></option>
                        <?php while ($row = mysqli_fetch_array($user_roles_list)) {
						?>
                        <option value="<?php echo $row['id'] ?>" <?php if (isset($row['id']) && $row['id'] == $fetch1['user_role_id']) {
																			echo 'Selected';
																		} ?>> <?php echo $row['name'] ?> </option>
                        <?php } ?>
                    </select>
                    <div class="help-block with-errors  "></div>
                </div>


                <div class="form-group col-sm-3">

                    <label for="exampleInputEmail1"><?php echo getLange('email') . ' ' . getLange('address'); ?></label>

                    <input type="email" class="form-control " name="email" value="<?php echo $fetch1['email']; ?>"
                        placeholder="Enter email">

                    <div class="help-block with-errors "></div>

                </div>





            </div>
            <div class="row">
                <div class="form-group col-sm-3">
                    <label for="exampleInputEmail1"><?php echo getLange('password'); ?></label>
                    <input type="password" class="form-control " name="password" placeholder="Enter password">
                    <div class="help-block with-errors  "></div>
                </div>
                <div class="form-group col-sm-3">

                    <label for="exampleInputEmail1"><?php echo getLange('phoneno'); ?>.</label>

                    <input type="text" class="form-control " name="phone" value="<?php echo $fetch1['phone']; ?>"
                        placeholder="Enter Phone No.">

                    <div class="help-block with-errors "></div>

                </div>



                <div class="form-group col-sm-3">

                    <label for="exampleInputEmail1"><?php echo getLange('staffid'); ?> #.</label>

                    <input type="text" class="form-control " name="staff_id" value="<?php echo $fetch1['user_name']; ?>"
                        placeholder="Enter Staff ID.">

                    <div class="help-block with-errors "></div>

                </div>
                <div class="form-group col-sm-3">

                    <label for="exampleInputEmail1"><?php echo getLange('cnic'); ?></label>

                    <input type="text" class="form-control " name="cnic" value="<?php echo $fetch1['cnic']; ?>"
                        placeholder="CNIC" required>

                    <div class="help-block with-errors "></div>

                </div>

            </div>
            <div class="row">
                <div class="form-group col-sm-3">

                    <label for="exampleInputEmail1"><?php echo getLange('pickupcommision'); ?></label>

                    <input type="text" class="form-control " name="pickup_comm"
                        value="<?php echo $fetch1['pickup_comm']; ?>" placeholder="Pickup Commission" required>

                    <div class="help-block with-errors "></div>

                </div>
                <div class="form-group col-sm-3">

                    <label for="exampleInputEmail1"><?php echo getLange('deliverycommision'); ?></label>

                    <input type="text" class="form-control " name="delivery_comm"
                        value="<?php echo $fetch1['delivery_comm']; ?>" placeholder="Delivery Commission" required>

                    <div class="help-block with-errors "></div>

                </div>
                <?php if ($_SESSION['branch_id'] == 1) : ?>

                <div class="form-group col-sm-3">
                    <label for="exampleInputEmail1"><?php echo getLange('branch'); ?></label>
                    <select class="form-control js-example-basic-single branch" name="branch_id" required="required">
                        <?php foreach ($branches as $branch) {
								if ($branch['id'] == $fetch1['branch_id']) {
									$selected =  'selected';
								}

							?>
                        <option value="<?php echo $branch['id']; ?>" <?php if (isset($branch['id']) && $branch['id'] == $fetch1['branch_id']) {
																					echo 'Selected';
																				} ?>><?php echo $branch['name']; ?></option>
                        <?php } ?>
                    </select>
                    <div class="help-block with-errors "></div>
                </div>

                <?php else : ?>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo getLange('branch'); ?></label>
                        <input type="text" class="form-control branch" value="<?php echo $fetch1['branch_id']; ?>" readonly>
                        <div class="help-block with-errors "></div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="col-sm-3">
                    <div class="form-group">
                <div id="cashledger"></div>
            </div>
        </div>
            </div>
            <div class="row">
                <input type="hidden" name='id' value="<?php echo $id; ?>">

                <button type="submit" name="updateemployees"
                    class="btn btn-purple"><?php echo getLange('update'); ?></button>
            </div>
        </form>



    </div>

</div>

<?php



?>