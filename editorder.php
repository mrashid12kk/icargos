<?php

session_start();
	include_once "includes/conn.php";
		if(isset($_SESSION['customers'])){
	
	include "includes/header.php";
	
?>
<section class="bg padding30">
  <div class="container-fluid dashboard">
     <div class="col-lg-3 col-md-3 col-sm-4 profile">
      <?php
		include "includes/sidebar.php";
	  ?>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-8 profile">
     
     
      <div class="row">
      <div class="col-lg-12  login">
        <div class="white">
		
          <h2>Update Request</h2>
		  <?php
		  if(isset($_POST['update_orders'])){
				unset($_POST['update_orders']);
				$id=$_POST['id'];
				$sql="update orders set ";
				$countt=0;
				foreach($_POST as $keys=>$values){
					$sql.="$keys='$values'";
					$countt++;
					if($countt!==count($_POST)){
						$sql.=",";
					}
					else{
						$sql.=" where id=$id";
					}
				}
				// die($sql);
				$query=mysqli_query($con,$sql);
				$rowscount=mysqli_affected_rows($con);
				if($rowscount=1){
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you successfully update your Request.</div>';
				}
			}
		  if(isset($_POST['id'])){
			 $id=mysqli_real_escape_string($con,$_POST['id']);
			$query=mysqli_query($con,"select * from orders where id=$id");
			$fetch=mysqli_fetch_array($query);
			
			?>
			<form autocomplete="off" role="form" id="fileform" data-toggle="validator" action="" method="post" >
					
						<div class="row">
							<h3>Sender Details</h3>
							<div class="form-group col-lg-6">
							  <label for="usr">Pickup location:</label>
							  <input type="text"  onkeypress="apply_autocomplete(this);" class="form-control" value="<?php echo $fetch['plocation']; ?>" name="plocation" required>
							</div>
								<div class="form-group  col-lg-6">
									  <label for="pwd">Pickup City:</label>
										<select class="form-control" name="pickup_city">
											<?php
											$query=mysqli_query($con,"Select * from cities");
											while($fetch2=mysqli_fetch_array($query)){
												?>
												<option <?php if($fetch2['city_name']==$fetch['pickup_city']) echo "selected"; ?>><?php echo $fetch2['city_name']; ?></option>
											<?php
											}
											
											?>
										</select>
								</div>
								<div class="form-group col-lg-6">
									  <label for="pwd">Pickup Address(Area,Steet Number,Villa #) :</label>
										<textarea rows="5" class="form-control" name="pickup_address" value="" required><?php echo $fetch['pickup_address']; ?></textarea>
										 <div class="help-block with-errors"></div>
								</div>
									<div class="form-group col-lg-6">
										  <label for="usr">Name of the Happy Sender:</label>
										  <input type="text" class="form-control" name="sname" value="<?php echo $fetch['sname'];  ?>" required>
										   <div class="help-block with-errors"></div>
		
										</div>
										<div class="form-group col-lg-6">
										  <label for="usr">Phone Number of the Happy Sender:</label>
										  <input type="text" class="form-control" name="sphone" value="<?php echo $fetch['sphone'];  ?>" required>
										   <div class="help-block with-errors"></div>
										</div>
										<div class="form-group col-lg-6">
										  <label for="usr">Email of the Happy Sender:</label>
										  <input type="email" class="form-control"   value="<?php echo $fetch['semail'];  ?>" name="semail">
										   <div class="help-block with-errors"></div>
										</div>
										<div class="form-group col-lg-6">
										  <label for="pwd">Address( City,Area,Villa Number ) of the Happy Sender:</label>
											<textarea class="form-control" required name="sender_address"> <?php echo $fetch['sender_address'];  ?></textarea>
											 <div class="help-block with-errors"></div>
										</div>	
								</div>
							<div class="row">
								<h3>Receiver's Information</h3>
								<div class="form-group col-lg-6">
								  <label for="usr">Delivery Location( City,Area,Villa Number ):</label>
								  <input type="text" onkeypress="apply_autocomplete(this);"  class="form-control" value="<?php echo $fetch['daddress']; ?>" name="daddress" required>
								</div>
								<div class="form-group col-lg-6">
								  <label for="usr">Name of the Happy Receiver:</label>
								  <input type="text" class="form-control" name='rname'  value="<?php echo $fetch['rname'];?>" required>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-lg-6">
								  <label for="usr">Phone Number of the Happy Receiver:</label>
								  <input type="text" class="form-control" name="rphone" value="<?php echo $fetch['rphone'];?>" required>
									  <div class="help-block with-errors"></div>
						
								</div>
								<div class="form-group col-lg-6">
								  <label for="usr">Email of the Happy Receiver:</label>
								  <input type="email" class="form-control" name="remail" value="<?php echo $fetch['remail'];?>">
									  <div class="help-block with-errors"></div>
						
								</div>
								<div class="form-group col-lg-6">
								  <label for="pwd">Address( City,Area,Villa Number ) of the Happy Receiver:</label>
									<textarea class="form-control" name='receiver_address' value="" required><?php echo $fetch['receiver_address'];?></textarea>
										<div class="help-block with-errors"></div>
						
								</div>
							</div>
							<div class="row">
								<h3>Delivery Information</h3>
								
								<div class="row">
								<div class="form-group col-lg-6">
								  <label for="usr">Type of Package:</label>
									<select class="form-control" name="package_type">
										<option <?php if($fetch['package_type']=='Food') echo "selected"; ?>>Food</option>
										<option <?php if($fetch['package_type']=='Product') echo "selected"; ?>>Product</option>
										<option <?php if($fetch['package_type']=='Comments') echo "selected"; ?>>Comments</option>
									</select>
								</div>
								</div>
									
								<div class="row">
									<div class="col-lg-6">
										<div class="input-group input-append date datepickerr" id="datepickerr" data-provide="datepicker">
										<label for="usr">Date of Pickup:</label>
												<span style="display: inherit;" class="col-lg-12">
												<input type="text" name="pickup_date" value="<?php echo $fetch['pickup_date']?>" class="form-control" >
										<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span></span>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group col-lg-12">
										  <label for="usr">Time of Pickup:</label>
											<select class="form-control" name="pickup_time">
												<?php
													$hours=8;
													$minute=0;
													for($i=0;$i<=48;$i++){
														if($minute==60){
															$hours++;
															$minute=0;
														}
														if($minute%2==0){
															?>
															<option  <?php if($fetch['pickup_time']=="$hours:$minute available") echo "selected"; ?>><?php echo "$hours:$minute available"; ?></option>
														<?php
														
														}
														else{
															echo "<option disabled>$hours:$minute booked</option>";
														}
														$minute+=15;
													}
												
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
									<div class="input-group input-append date datepickerr" id="datepickerr" data-provide="datepicker">
										<label for="usr">Date of Delivery:</label>
												<span style="display: inherit;" class="col-lg-12">
												<input type="text" value="<?php echo $fetch['delivery_date']; ?>" name="delivery_date" class="form-control" >
										<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span></span>
									</div>
									</div>
									<div class="col-lg-6">
									
									<div class="form-group col-lg-12">
									  <label for="usr">Time of Delivery:</label>
										<select class="form-control" name="delivery_time">
											<?php
												$hours=8;
												$minute=0;
												for($i=0;$i<=48;$i++){
													if($minute==60){
														$hours++;
														$minute=0;
													}
													// if($minute==0){
															// $minute=00;
													// }
													?>
													<option><?php echo "$hours:";if($minute==0) echo "00"; ?></option>
													<?php
													$minute+=15;
												}
											?>
										</select>
									</div>
									</div>
								</div>
								
								<div class="form-group col-lg-6">
								  <label for="usr">Delivery Fees</label>
									<select class="form-control" name="delivery_by">
										<option <?php if($fetch['delivery_by']=="Cash on Delivery(By receiver)") echo "selected"; ?>>Cash on Delivery(By receiver)</option>
										<option <?php if($fetch['delivery_by']=="By the Sender") echo "selected"; ?>>By the Sender</option>
									</select>
								</div>
								<div class="form-group col-lg-6">
								  <label for="usr">Collection Amount:</label>
								  <input type="text" class="form-control" name='collection_amount'  value="<?php echo $fetch['collection_amount']; ?>" required>
									<div class="help-block with-errors"></div>
								</div>
							</div>							
							<input type="hidden" name="id" value="<?php echo $fetch['id'];?>">						
						<br>
						<br>
						<button type="submit" class="btn btn-success col-lg-offset-2 col-lg-8 editp" name="update_orders">Update</button>
			</form>
			<?php
		}
		
			?>
        </div>
       
      </div>
      
    </div>
   

    </div>
  </div>
</section>

</div>
  <div id='map-canvas' style="display:none;"></div>
  
 <script src="admin/assets/js/plugins/bootstrap-validator/bootstrapValidator.min.js"></script>
   
<?php
// include "includes/footer.php";
}
	else{
		header("location:index.php");
	}
?>
