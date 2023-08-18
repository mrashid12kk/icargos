<?php
function addQuote($value){
	return(!is_string($value)==true) ? $value : "'".$value."'";
}

	if(isset($_POST['add_services'])){
		$target_dir="images/";
		if($_FILES["service_receipt"]["name"]!=""){
			$target_file = $target_dir .uniqid(). basename($_FILES["service_receipt"]["name"]);
			$extension = pathinfo($target_file,PATHINFO_EXTENSION);
			if($extension=='jpg'||$extension=='png'||$extension=='jpeg'||$extension=='pdf') {
					
				move_uploaded_file($_FILES["service_receipt"]["tmp_name"],$target_file);
			}
			// $query2=mysqli_query($con,"update users set image='$target_file' where id='$users_id'") or die(mysqli_error($con));
			$_POST['service_receipt']=$target_file;
		}
		$data = $_POST;
		if(isset($data['add_services']))
			unset($data['add_services']);
		$keys = implode(", ", array_keys($data));
		array_walk($data, function(&$value, &$key) {
			$value =addQuote($value);
		});
		$values = implode(",",$data);
		$sql = "INSERT INTO services ($keys) VALUES($values)";
		// die($sql);
		$query1=mysqli_query($con,$sql) or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> your service has been send successfully</div>';
		/* 	$query=mysqli_query($con,"select * from admin");
			$fetch=mysqli_fetch_array($query);
			$reciever=$fetch['email'];
			$subject = "Signup Request";
			$txt = "$user_name send a signup request to you please check the the details from admin panel";
			$headers = "From: $email" . "\r\n";
			mail('muhammad.usman93333@gmail.com',$subject,$txt,$headers);*/
			}
		else{
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> your service has not been send unsuccessfully.</div>';
		}
	}


?>
<div class="panel panel-default">
	<div class="panel-heading">Send Services Reports</div>
	<div class="panel-body">
	
		<form role="form" data-toggle="validator" action="" method="post" enctype="multipart/form-data">
			<div class="col-lg-6">
				<div class="form-group">
					<label  class="control-label">Date of the Service(YYYY-MM-DD)</label>
						<div class="input-group date" id="datepicker">
							<input type="text" name="date" class="form-control" data-date-format="YYYY-MM-DD">
							<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span>
							</span>
						</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label  class="control-label">Amount for the Service</label>
					<input type="text" class="form-control" name="service_amount" placeholder="Enter Service Amount" >
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label  class="control-label">Type</label>
					<input type="text" class="form-control" name="type" placeholder="Enter Type" >
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label  class="control-label">Enter Km Before Service</label>
					<input type="number" class="form-control" name="before_service" placeholder="Enter Km Before Service" >
					<div class="help-block with-errors "></div>
				
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label  class="control-label">Next Service at Km</label>
					<input type="number" class="form-control" name="next_service" placeholder="Next Service at Km" >
					<div class="help-block with-errors "></div>
				
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label  class="control-label">Enter Receipt of Service(pdf,jpg,png)</label>
					<input type="file" class="form-control" name="service_receipt"  >
				</div>
			</div>
			<br/>
			<input type="hidden" name="driver_id" value="<?php echo $fetch['id'];?>">
			
			<div class="text-center">
				<button type="submit" name="add_services" class="col-lg-3 col-lg-offset-4 btn btn-purple center" >Send Report</button>
			</div>
		</form>
	
	</div>
</div>