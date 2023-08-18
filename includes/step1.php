	
		
		<!-- <h4 class="modal-title " >Sender's Information</h4> -->
<form autocomplete='off' action="" method="post" role="form" id="step1_form" data-toggle="validator">
	<?php
	if(isset($_SESSION['customers'])){
	$id=$_SESSION['customers'];
	$query=mysqli_query($con,"select * from customers where id='$id'");
	$fetch=mysqli_fetch_array($query);
	}
		?>
			<div class="wrap">
			<label for="usr">Pickup Location:</label><br>
			   <label class="radio-inline"><input type="radio" name="pickup_option" value="By Search" checked>By Search</label>
				<label class="radio-inline"><input type="radio" value="By Coordinates" name="pickup_option">By Coordinates</label>
			<div class="form-group has-feedback">
			 
			<div class="search">
			  <input type="search" class="form-control" name="plocation" id="pickup_alternative" value="<?php echo isset($_SESSION['step1']['plocation'])?($_SESSION['step1']['plocation']):(isset($_SESSION['address'])?$_SESSION['address']:""); ?>" placeholder="Search Google Maps" required>
			  <input type="hidden" class="pickup_city" value="">
				  <span class="glyphicon glyphicon-search form-control-feedback"></span> 
				  </div>
				 <div class="row coordinates" id="latlngg" style="display:none;">
					<div class=" col-lg-6">
						 <input type="text" class="form-control" id="latitude" placeholder="Enter Latitude" >
					</div>
					<div class=" col-lg-6">
						 <input type="text" class="form-control col-lg-6" id="longitude" placeholder="Enter Longitude" >
					</div>
				</div>
			</div>
			</div>
			
		<div class="wrap">
			<label for="pwd">Delivery Location( City,Area,Villa Number ):</label><br>
		   <label class="radio-inline"><input type="radio" name="optradio" value="By Search" checked>By Search</label>
			<label class="radio-inline"><input type="radio" value="By Coordinates" name="optradio">By Coordinates</label>
			
		<div class="form-group has-feedback">
		  						
			<div class="search">
		  <input type="search" class="form-control" name="daddress" value="<?php echo isset($_SESSION['step1']['daddress'])?$_SESSION['step1']['daddress']:"";?>" id="delivery_address"  placeholder="Search Google Maps" required>
				<span class="glyphicon glyphicon-search form-control-feedback"></span> 
				</div>
			 <div class="row coordinates" id="latlngg" style="display:none;">
				<div class=" col-lg-6">
					 <input type="text" class="form-control" id="latitude" placeholder="Enter Latitude" >
				</div>
				<div class=" col-lg-6">
					 <input type="text" class="form-control col-lg-6" id="longitude" placeholder="Enter Longitude" >
				</div>
			</div>
									
		</div>
		</div>
			<!-- <div class="form-group">
			  <label for="pwd">Pickup City:</label>
				<select class="form-control" name="pickup_city">
					<?php
					$query=mysqli_query($con,"Select * from cities");
					while($fetch2=mysqli_fetch_array($query)){
						?>
						<option <?php if($fetch2['city_name']==$_SESSION['step1']['pickup_city']) echo "selected"; ?>><?php echo $fetch2['city_name']; ?></option>
					<?php
					}
					
					?>
				</select>
			</div> -->
		
			
			<!-- <input type="hidden" name="pickup_address"> -->
		<!-- <div class="form-group">
		  <label for="usr">Name of the Happy Sender:</label>
		  <input type="text" class="form-control" name="sname" value="<?php echo $_SESSION['step1']['sname'];  ?>" required>
		   <div class="help-block with-errors"></div>

		</div>
		<div class="form-group">
		  <label for="usr">Phone Number of the Happy Sender:</label>
		  <input type="text" class="form-control" name="sphone" value="<?php echo $_SESSION['step1']['sphone'];  ?>" required>
		   <div class="help-block with-errors"></div>
		</div> -->
		<!-- <div class="form-group">
		  <label for="usr">Email of the Happy Sender:</label>
		  <input type="email" class="form-control"   value="<?php echo $_SESSION['step1']['semail'];  ?>" name="semail">
		   <div class="help-block with-errors"></div>
		</div>
		<div class="form-group">
		  <label for="pwd">Address( City,Area,Villa Number ) of the Happy Sender:</label>
			<textarea class="form-control" required name="sender_address"> <?php echo $_SESSION['step1']['sender_address'];  ?></textarea>
			 <div class="help-block with-errors"></div>
		</div> -->
		<div class=" input-append date " data-date-start-date="0d" data-date-autoclose="true"  data-provide="datepicker">
			<label for="usr">Date of Pickup:</label>
			<div class="col-lg-12 input-group"><input type="text" name="pickup_date" value="<?php echo isset($_SESSION['step1']['pickup_date'])?$_SESSION['step1']['pickup_date']: date('m/d/Y'); ?>" class="form-control" required>
			<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span></div>
		</div>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
				  <label for="usr">Weight:</label>
				  <select class="form-control" required="true" name="weight">
				  	<option value="">Select Weight</option>
				  	<?php
				  	$query= mysqli_query($con, "SELECT * FROM weights");
				  	while($row = mysqli_fetch_object($query)) {
						 $selected= isset($_SESSION['step1']['weight'])&&$_SESSION['step1']['weight']==$row->id?"selected":"";
				  		echo '<option data-price="'.$row->price.'" value="'.$row->id.'" '.$selected.'>'.$row->name.'</option>';
				  	}
				  	?>
				  </select>
				  </div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
				  <label for="usr">Price:</label>
				  <input class="form-control" type="text"  value="<?php echo isset($_SESSION['step1']['price'])?$_SESSION['step1']['price']:""; ?>" readonly="true" name="price" >
				</div>
			</div>
		</div>
		  <input type="hidden" name="distance" id="distance" value="">
		<div class="form-group">
		  <label for="usr">Type of Package:</label>
			<select class="form-control" name="package_type">
				<option <?php echo  $selected= isset($_SESSION['step1']['weight'])&&$_SESSION['step1']['weight']=="Documents"?"selected":"";?>>Documents</option>
				<option <?php echo  $selected= isset($_SESSION['step1']['weight'])&&$_SESSION['step1']['weight']=="Accessories"?"selected":"";?>>Accessories</option>
				<option <?php echo  $selected= isset($_SESSION['step1']['weight'])&&$_SESSION['step1']['weight']=="Healthy products"?"selected":"";?>>Healthy products</option>
				<option <?php echo  $selected= isset($_SESSION['step1']['weight'])&&$_SESSION['step1']['weight']=="Food"?"selected":"";?>>Food</option>
				<option <?php echo  $selected= isset($_SESSION['step1']['weight'])&&$_SESSION['step1']['weight']=="Others"?"selected":"";?>>Others</option>
			</select>
		</div>
		
		<div class="form-group">
		  <label for="usr">Collection Amount:<?php echo isset($_SESSION['customers']) ? '' : '<span style="font-size: 10px;">(Please Login to use this)</span>'; ?></label>
		  <input class="form-control" <?php echo isset($_SESSION['customers']) ? '' : ' disabled="disabled" ' ?> type="text"  value="<?php echo isset($_SESSION['step1']['collection_amount'])?$_SESSION['step1']['collection_amount']:""; ?>"  name="collection_amount" >
		</div>
		<div class="form-group">
		  <label for="usr">Select Payment Method:</label>
			<select class="form-control" name="payment_type">
				<option value="PAYPAL"<?php echo  $selected= isset($_SESSION['step1']['weight'])&&$_SESSION['step1']['weight']=="PAYPAL"?"selected":"";?>>PAYPAL/VISA/MASTER CARD</option>
				<option value="CASH"  <?php echo  $selected= isset($_SESSION['step1']['weight'])&&$_SESSION['step1']['weight']=="CASH"?"selected":"";?>>CASH</option>
			</select>
		</div>
		<div class="form-group cash_by" hidden>
			<select class="form-control" name="cash_by">
				<option>By Sender</option>
				<option>By Receiver</option>
			</select>
		</div>
		<button type="submit" class="btn btn-info col-lg-5" name="step1">Next</button>
</form>
									
	