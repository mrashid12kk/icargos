<?php

		if(isset($_POST['updatepricing'])){
			$id=$_GET['id'];


			$point_5_kg=mysqli_real_escape_string($con,$_POST['point_5_kg']);
			$onekg = mysqli_real_escape_string($con,$_POST['onekg']);
			$additional_kg=mysqli_real_escape_string($con,$_POST['additional_kg']);

			$query2=mysqli_query($con,"UPDATE pricing set `point_5_kg`='$point_5_kg',`1_kg`='$onekg',`additional_kg`='$additional_kg' where id=$id") or die(mysqli_error($con));

			$rowscount=mysqli_affected_rows($con);

			if($rowscount>0){

				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated Pricing successfully</div>';

				echo "<script>document.location.href='pricing.php';</script>";

			}

			else{

				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a driver unsuccessfully.</div>';

			}

		}

	

	if(isset($_GET['id'])){
		$id= $_GET['id'];

		$query1=mysqli_query($con,"select * from pricing where id=$id") or die(mysqli_error($con));

		$fetch1=mysqli_fetch_array($query1);
	}

?>

<div class="panel panel-default">

	<div class="panel-heading">Update Pricing</div>

	<div class="panel-body" style="padding: 14px;">

	

		<form role="form" class=""  action="" method="post">

		<div class="col-sm-6">
			<div class="form-group">

			<label  class="control-label">Zone</label>

			<input type="text" class="form-control" name="zone" value="<?php echo $fetch1['zone']; ?>" placeholder="Zone" disabled>

			<div class="help-block with-errors "></div>

		

		 </div>

		

			  <div class="form-group">

				<label for="exampleInputEmail1">City</label>

				<input type="text" class="form-control " name="city"  value="<?php echo $fetch1['city']; ?>" placeholder="City" disabled >

				<div class="help-block with-errors "></div>

				</div>

			  <div class="form-group">

				<label for="exampleInputEmail1">0.5 kg price (Rs)</label>

				<input type="number" class="form-control " name="point_5_kg" value="<?php echo $fetch1['point_5_kg']; ?>"  required >

				<div class="help-block with-errors "></div>

			</div>

			  <div class="form-group">

				<label for="exampleInputEmail1">1 kg price (Rs)</label>

				<input type="text" class="form-control " name="onekg" value="<?php echo $fetch1['1_kg']; ?>"  required >

				<div class="help-block with-errors "></div>

			</div>
			<div class="form-group">

				<label for="exampleInputEmail1">Additional kg price</label>

				<input type="text" class="form-control " name="additional_kg" value="<?php echo $fetch1['additional_kg']; ?>"   required>

				<div class="help-block with-errors "></div>

			</div>
			<input type="hidden" name='id' value="<?php echo $id;?>">

		 <button type="submit" name="updatepricing" class="btn btn-purple" >Update</button>
		</div>

		</form>

	

	</div>

</div>

<?php

	

?>