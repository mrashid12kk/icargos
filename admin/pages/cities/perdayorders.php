<?php

		if(isset($_POST['perdayorders'])){

			$id=mysqli_real_escape_string($con,$_POST['id']);

			$per_day_packages=mysqli_real_escape_string($con,$_POST['per_day_packages']);
			
			$query2=mysqli_query($con,"update settings set per_day_packages='$per_day_packages' where id=$id") or die(mysqli_error($con));

			$rowscount=mysqli_affected_rows($con);

			if($query2){

				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated  per day orders successfully</div>';

			}

			else{

				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated  per day orders  unsuccessfully.</div>';

			}

		}

	

	// if(isset($_POST['id'])){

		// $id=mysqli_real_escape_string($con,$_POST['id']);

		$query1=mysqli_query($con,"select * from settings") or die(mysqli_error($con));

		$fetch1=mysqli_fetch_array($query1);

	// }

?>

<div class="panel panel-default">

	<div class="panel-heading">Per Day Orders</div>

	<div class="panel-body">

	

		<form role="form" class="" data-toggle="validator" action="" method="post">

			<div class="form-group">

					<label  class="control-label">Per Day Orders</label>

					<input type="number" class="form-control" value="<?php echo isset($fetch1['per_day_packages'])?$fetch1['per_day_packages']:""; ?>" name="per_day_packages" placeholder="Enter per day orders" required>

					<div class="help-block with-errors "></div>

				</div>
				
				<input type="hidden" name="id" value="<?php echo $fetch1['id']; ?>">

		 <button type="submit" name="perdayorders" class="btn btn-purple" >Update</button>

		</form>

	

	</div>

</div>

<?php

	

?>