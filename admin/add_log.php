<?php
session_start(); 
require 'includes/conn.php';
if(isset($_SESSION['users_id']) &&($_SESSION['type']=='driver')){
	include "includes/header.php";
	$id = $_SESSION['users_id'];
	$query = mysqli_query($con, "SELECT * FROM users WHERE id = $id");
	$data = mysqli_fetch_assoc($query);
	$message = '';
	if(isset($_POST['submit_log'])) {
		$formData = $_POST;
		unset($formData['submit_log']);
		if(isset($_FILES['start_km_attach'])) {
			$file = $_FILES['start_km_attach'];
			$path = 'images/'.time().'_'.$file['name'];
			if(move_uploaded_file($file['tmp_name'], $path))
				$formData['start_km_attach'] = $path;
		}
		if(isset($_FILES['trip_km_attach'])) {
			$file = $_FILES['trip_km_attach'];
			$path = 'images/'.time().'_'.$file['name'];
			if(move_uploaded_file($file['tmp_name'], $path))
				$formData['trip_km_attach'] = $path;
		}
		if(isset($_FILES['gas_amount_attach'])) {
			$file = $_FILES['gas_amount_attach'];
			$path = 'images/'.time().'_'.$file['name'];
			if(move_uploaded_file($file['tmp_name'], $path))
				$formData['gas_amount_attach'] = $path;
		}
		if(isset($_FILES['deposit_amount_attach'])) {
			$file = $_FILES['deposit_amount_attach'];
			$path = 'images/'.time().'_'.$file['name'];
			if(move_uploaded_file($file['tmp_name'], $path))
				$formData['deposit_amount_attach'] = $path;
		}
		$today = date('Y-m-d');
		$formData['date'] = $today;
		$key = '';
		if(isset($_SESSION['key']) && !empty($_SESSION['key'])) {
			$key = $_SESSION['key'];
		} else {
			$key = md5(uniqid());
			$_SESSION['key'] = $key;
		}
		$formData['unique_key'] = $key;
		foreach ($formData as $k => &$value) {
			if($value == '')
				unset($formData[$k]);
			if(is_string($value))
				$value = "'".$value."'";
		}
		$flag = true;
		$query = mysqli_query($con, "SELECT * FROM driver_logs WHERE driver = $id AND unique_key = '$key'");
		if(mysqli_affected_rows($con) > 0) {
			$log = mysqli_fetch_assoc($query)['id'];
			$sql = "UPDATE driver_logs SET";
			$index = 0;
			foreach ($formData as $key => $value) {
				$sql .= " $key = $value";
				$index++;
				if($index != count($formData))
					$sql .= ", ";
			}
			$sql .= " WHERE id = $log";
			$flag = mysqli_query($con, $sql);
		} else {
			$formData['driver'] = $id;
			$keys = implode(',', array_keys($formData));
			$values = implode(',', $formData);
			$sql = "INSERT INTO driver_logs ($keys) VALUES($values)";
			$flag = mysqli_query($con, $sql);
		}
		if($flag) {
			if(isset($_GET['login'])) {
				// header('Location: dashboard.php?status=active');
				echo "<script>window.location.href='dashboard.php?status=active';</script>";
			
			} else if(isset($_GET['logout'])) {
				// header('Location: logout.php');
				echo "<script>window.location.href='logout.php';</script>";
			
			} else 
				$message = '<div class="alert alert-success">'.getLange('Log_successfully_saved').'!</div>';
		} else {
			$message = '<div class="alert alert-warning">'.getLange('database_error_occured').'!</div>';
		}
	}
?>
<body data-ng-app>
 	
    
	<?php
	
	include "includes/sidebar.php";
	
	?>
    <!-- Aside Ends-->
    
    <section class="content">
    	 
	<?php
	include "includes/header2.php";
	?>
        
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid">
        	
            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
            <?php echo $message;  ?>
            <div class="panel panel-default">
            	<?php if(isset($_GET['login'])) { ?>
					<div class="panel-heading">Good Morning <?php echo $data['Name']; ?></div>
            	<?php } else if(isset($_GET['logout'])) { ?>
					<div class="panel-heading">Good Afternoon <?php echo $data['Name']; ?></div>
            	<?php } else { ?>
					<div class="panel-heading">Add Log Detail</div>
            	<?php } ?>
				<div class="panel-body">
					<form action="" method="POST" enctype="multipart/form-data">
						<?php if(isset($_GET['login']) == false && isset($_GET['logout']) == false) { ?>
						<!-- <div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Date:</label>
									<input class="form-control" data-provide="datepicker" type="date" name="date" />
								</div>
							</div>
						</div> -->
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Enter Car KM:</label>
									<input class="form-control" type="text" name="start_km" />
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Attachment(Car Km):</label>
									<input class="form-control" type="file" name="start_km_attach" />
								</div>
							</div>
						</div>
						<?php } ?>
						<?php if(isset($_GET['login'])) { ?>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Enter Car KM:</label>
									<input class="form-control" type="text" name="start_km" />
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Attachment(Car Km):</label>
									<input class="form-control" type="file" name="start_km_attach" />
								</div>
							</div>
						</div>
						<?php } else { ?>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Enter your Car KM when you finish:</label>
									<input class="form-control" type="text" name="trip_km" />
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Attachment(Car KM when you finish):</label>
									<input class="form-control" type="file" name="trip_km_attach" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Enter Gas Amount:</label>
									<input class="form-control" type="text" name="gas_amount" />
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Attachment(Gas Slip):</label>
									<input class="form-control" type="file" name="gas_amount_attach" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Enter Deposit Amount:</label>
									<input class="form-control" type="text" name="deposit_amount" />
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Attachment(Deposit Slip):</label>
									<input class="form-control" type="file" name="deposit_amount_attach" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Enter Cash Amount:</label>
									<input class="form-control" type="text" name="cash_amount" />
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label>Any Comment</label>
								<textarea class="form-control" rows="5" name="any_comment" ></textarea>
							</div>
						</div>
						
						<?php } ?>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group col-sm-6">
									<?php if(isset($_GET['login'])) { ?>
										<!--<a href="dashboard.php?status=active" class="form-control btn btn-default">Login</a>-->
										</div>
									<div class="form-group col-sm-6">
										<input class="form-control btn btn-info" value="Submit & Login" type="submit" name="submit_log" />
									</div>
									
									<?php } else if(isset($_GET['logout'])) { ?>
										<!--<a href="logout.php" class="form-control btn btn-danger">Skip & Logout</a>-->
									</div>
									<div class="form-group col-sm-6">
										<input class="form-control btn btn-info" value="Submit & Logout" type="submit" name="submit_log" />
									</div>
									<?php }
									else{
										?>
									<div class="form-group col-sm-6">
										<input class="form-control btn btn-info" value="Submit" type="submit" name="submit_log" />
									</div>
									
									<?php
									
									}
									?>
							
							</div>
							<div class="col-sm-6"></div>
						</div>
					</form>
				</div>
			</div>	
            	
        </div>
        <!-- Warper Ends Here (working area) -->
        
        
      <?php
	
	include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
	?>