	<h4 class="modal-title " >Sender's Information</h4>
							<form autocomplete='off' action="" method="post" role="form" data-toggle="validator">
									<label for="usr">Pickup Location:</label><br>
										   <label class="radio-inline"><input type="radio" name="optradio" value="By Search" checked>By Search</label>
											<label class="radio-inline"><input type="radio" value="By Coordinates" name="optradio">By Coordinates</label>
										
										<div class="form-group has-feedback">
										 
										  <input type="search" class="form-control" name="plocation" id="pickup_alternative" value="" placeholder="Search Google Maps" required>
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
									<div class="form-group">
									  <label for="usr">Name:</label>
									  <input type="text" class="form-control" name="sname" required>
									   <div class="help-block with-errors"></div>
									</div>
									<div class="form-group">
									  <label for="usr">Phone Number of the Happy Sender:</label>
									  <input type="text" class="form-control" name="sphone" required>
									   <div class="help-block with-errors"></div>
									</div>
									<div class="form-group">
									  <label for="usr">Email of the Happy Sender:</label>
									  <input type="email" class="form-control" name="semail" required>
									   <div class="help-block with-errors"></div>
									</div>
									<div class="form-group">
									  <label for="pwd">Address( City,Area,Villa Number ) of the Happy Sender:</label>
										<textarea class="form-control" required name="sender_address"></textarea>
										 <div class="help-block with-errors"></div>
									</div>
									
									<button type="submit" class="btn btn-info col-lg-5 pull-right" name="step1">Next</button>
		
							</form>
									