<?php
function addQuote($value){

		return(!is_string($value)==true) ? $value : "'".$value."'";

	}
	if(isset($_POST['update'])){
		unset($_POST['update']);
		$data=$_POST;
		 $sql = "UPDATE prices SET ";
            $count = 0;
            foreach ($data as $key => $value) {
                $value =addQuote($value);
                $sql .= "$key = $value";
                $count++;
                if($count !== count($data))
                    $sql .= ", ";
            }
                $sql .= " WHERE id=".$data['id'];
				$query1=mysqli_query($con,$sql) or die(mysqli_error($con));
				$rowscount=mysqli_affected_rows($con);
				if($rowscount>0){
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have successfully update the prices.</div>';
				}
				else{
					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not update the prices .please try again later.</div>';
				}
	}
?>
<h3>
	<?php echo isset($_GET['guest']) ? "For Guest Users":"For Registered Users";?>
</h3>
<div class="panel panel-default col-sm-6">
	<div class="panel-heading">For Food</div>
	<?php
		if(isset($_GET['guest']))
			$query1=mysqli_query($con,"Select * from prices where package_type='Food' and user_type='Guest' ") or die(mysqli_error($con));
		else
			$query1=mysqli_query($con,"Select * from prices where package_type='Food' and user_type='registered' ") or die(mysqli_error($con));
			$fetch1=mysqli_fetch_array($query1);
	?>

	<div class="panel-body">
		<form role="form" class="" data-toggle="validator" action="" method="post">
		<div class="form-group">
			<label  class="control-label">City To Same City Price</label>
			<input type="text" class="form-control" name="city_to_city" value="<?php echo $fetch1['city_to_city']; ?>" placeholder="City To City Price" required>
			<div class="help-block with-errors "></div>
		
		 </div>
		<div class="form-group">
			<label  class="control-label">City To Another Price</label>
			<input type="text" class="form-control" name="city_to_ano" value="<?php echo $fetch1['city_to_ano']; ?>" placeholder="City To Another Price" required>
			<div class="help-block with-errors "></div>
		
		 </div>
		<div class="form-group">
			<label  class="control-label">City To Fujairah City Price</label>
			<input type="text" class="form-control" name="city_to_fuj" value="<?php echo $fetch1['city_to_fuj']; ?>" placeholder="City To Fujairah City Price" required>
			<div class="help-block with-errors "></div>
		
		 </div>
		<div class="form-group">
			<label  class="control-label">On Same Date Increment</label>
			<input type="text" class="form-control" name="same_date_inc" value="<?php echo $fetch1['same_date_inc']; ?>" placeholder="Increment" required>
			<div class="help-block with-errors "></div>
		
		 </div>
		
			<input type="hidden" name='id' value="<?php echo $fetch1['id']; ?>">
		 <button type="submit" name="update" class="btn btn-purple" >Update</button>
		</form>
	
	</div>
</div>
<div class="panel panel-default col-sm-6">
	<div class="panel-heading">For Product</div>
	<?php
		if(isset($_GET['guest']))
			$query1=mysqli_query($con,"Select * from prices where package_type='Product' and user_type='guest' ") or die(mysqli_error($con));
		else
			$query1=mysqli_query($con,"Select * from prices where package_type='Product' and user_type='registered' ") or die(mysqli_error($con));
			
		$fetch1=mysqli_fetch_array($query1);
	?>

	<div class="panel-body">
		<form role="form" class="" data-toggle="validator" action="" method="post">
		<div class="form-group">
			<label  class="control-label">City To Same City Price</label>
			<input type="text" class="form-control" name="city_to_city" value="<?php echo $fetch1['city_to_city']; ?>" placeholder="City To City Price" required>
			<div class="help-block with-errors "></div>
		
		 </div>
		<div class="form-group">
			<label  class="control-label">City To Another Price</label>
			<input type="text" class="form-control" name="city_to_ano" value="<?php echo $fetch1['city_to_ano']; ?>" placeholder="City To Another Price" required>
			<div class="help-block with-errors "></div>
		
		 </div>
		<div class="form-group">
			<label  class="control-label">City To Fujairah City Price</label>
			<input type="text" class="form-control" name="city_to_fuj" value="<?php echo $fetch1['city_to_fuj']; ?>" placeholder="City To Fujairah City Price" required>
			<div class="help-block with-errors "></div>
		
		 </div>
		<div class="form-group">
			<label  class="control-label">On Same Date Increment</label>
			<input type="text" class="form-control" name="same_date_inc" value="<?php echo $fetch1['same_date_inc']; ?>" placeholder="Increment" required>
			<div class="help-block with-errors "></div>
		
		 </div>
		
			<input type="hidden" name='id' value="<?php echo $fetch1['id']; ?>">
		 <button type="submit" name="update" class="btn btn-purple" >Update</button>
		</form>
	
	</div>
</div>
