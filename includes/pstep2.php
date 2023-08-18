	<h4 class="modal-title " >Receiver's Information</h4>
	
		<form autocomplete='off' action="" method="post" role="form" data-toggle="validator">
			<?php
			if(isset($_SESSION['address'])){
				$address_seprate1=explode(",,",$_SESSION['address']);
				
		?>
				<div class="form-group">
				  <label for="usr">Delivery Location:</label>
				  <select class="form-control" name="daddress" id="delivery_address">
						<?php  
						for($i=0;$i<count($address_seprate1);$i++){
						?>
							<option><?php echo $address_seprate1[$i]; ?></option>
						<?php
						}
						?>
					</select>
				</div>
					<?php
					}
					else{
					?>
					<label for="usr">Delivery Location( City,Area,Villa Number ):</label><br>
					   <label class="radio-inline"><input type="radio" name="optradio" value="By Search" checked>By Search</label>
						<label class="radio-inline"><input type="radio" value="By Coordinates" name="optradio">By Coordinates</label>
					
					<div class="form-group has-feedback">
					 
					  <input type="search" class="form-control" name="daddress" id="delivery_address" value="<?php if(isset($_SESSION['address'])) echo $_SESSION['address']; ?>" placeholder="Search Google Maps" required>
						  <span class="glyphicon glyphicon-search form-control-feedback"></span> 
						 <div class="row" id="latlngg" style="display:none;">
							<div class=" col-lg-6">
								 <input type="text" class="form-control" id="latitude" placeholder="Enter Latitude" >
							</div>
							<div class=" col-lg-6">
								 <input type="text" class="form-control col-lg-6" id="longitude" placeholder="Enter Longitude" >
							</div>
						</div>
					</div>
					
			<?php
			}
			?>					
			<div class="form-group">
			  <label for="usr">Name of the Happy Receiver:</label>
			  <input type="text" class="form-control" name='rname' required>
			    <div class="help-block with-errors"></div>
	
			</div>
			<div class="form-group">
			  <label for="usr">Phone Number of the Happy Receiver:</label>
			  <input type="text" class="form-control" name="rphone" required>
			      <div class="help-block with-errors"></div>
	
			</div>
			<div class="form-group">
			  <label for="usr">Email of the Happy Receiver:</label>
			  <input type="email" class="form-control" name="remail" required>
			      <div class="help-block with-errors"></div>
	
			</div>
			<div class="form-group">
			  <label for="pwd">Address( City,Area,Villa Number ) of the Happy Receiver:</label>
				<textarea class="form-control" name='receiver_address' required></textarea>
				    <div class="help-block with-errors"></div>
			</div>
			<button type="submit" class="btn btn-success col-lg-5 pull-right" name="step2">Next</button>
		</form>
		