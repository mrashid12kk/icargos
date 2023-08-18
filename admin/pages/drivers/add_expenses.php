<?php
function addQuote($value){
	return(!is_string($value)==true) ? $value : "'".$value."'";
}

	if(isset($_POST['send_expense'])){
		$target_dir="images/";
		if($_FILES["receipt"]["name"]!=""){
			$target_file = $target_dir .uniqid(). basename($_FILES["receipt"]["name"]);
			$extension = pathinfo($target_file,PATHINFO_EXTENSION);
			if($extension=='jpg'||$extension=='png'||$extension=='jpeg'||$extension=='pdf') {
					
				move_uploaded_file($_FILES["receipt"]["tmp_name"],$target_file);
			}
			// $query2=mysqli_query($con,"update users set image='$target_file' where id='$users_id'") or die(mysqli_error($con));
			$_POST['receipt']=$target_file;
		}
		$data = $_POST;
		if(isset($data['send_expense']))
			unset($data['send_expense']);
		$keys = implode(", ", array_keys($data));
		array_walk($data, function(&$value, &$key) {
			$value =addQuote($value);
		});
		$values = implode(",",$data);
		$sql = "INSERT INTO expenses ($keys) VALUES($values)";
		// die($sql);
		$query1=mysqli_query($con,$sql) or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> your Expense report has been send successfully</div>';
		/* 	$query=mysqli_query($con,"select * from admin");
			$fetch=mysqli_fetch_array($query);
			$reciever=$fetch['email'];
			$subject = "Signup Request";
			$txt = "$user_name send a signup request to you please check the the details from admin panel";
			$headers = "From: $email" . "\r\n";
			mail('muhammad.usman93333@gmail.com',$subject,$txt,$headers);*/
			}
		else{
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> your expense report has not been send unsuccessfully.</div>';
		}
	}


?>
<div class="panel panel-default">
	<div class="panel-heading">Send Expenses Reports</div>
	<div class="panel-body">
	
		<form role="form" data-toggle="validator" action="" method="post" enctype="multipart/form-data">
			<div class="col-lg-6">
				<div class="form-group">
					<label  class="control-label">Date(YYYY-MM-DD)</label>
						<div class="input-group date" id="datepicker">
							<input type="text" name="date" class="form-control" data-date-format="YYYY-MM-DD">
							<span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span>
							</span>
						</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label  class="control-label">Time</label>
					<input type="time" class="form-control" name="time"  >
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label  class="control-label">Description</label>
					<textarea type="text" rows="5" class="form-control" name="description" placeholder="Enter Description About the Expense" ></textarea>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label  class="control-label">Amount</label>
					<input type="text" class="form-control" name="amount" placeholder="Enter Type" >
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label  class="control-label">Receipt of Expense(pdf,jpg,png)</label>
					<input type="file" class="form-control" name="receipt" placeholder="Enter Km Before Service" >
					<div class="help-block with-errors "></div>
				
				</div>
			</div>
			<br/>
			<input type="hidden" name="driver_id" value="<?php echo $fetch['id'];?>">
			
			<div class="text-center">
				<button type="submit" name="send_expense" class="col-lg-3 col-lg-offset-4 btn btn-purple center" >Send Report</button>
			</div>
		</form>
	
	</div>
</div>