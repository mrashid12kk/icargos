<h4 class="modal-title " >Delivery Information</h4>
	<form action="" method="post" role="form" data-toggle="validator">
				<input type="hidden" id="distance" name="distance" value="0">
				<input type="hidden" id="price" name="price" value="50">
				<input type="hidden" id="multiple" value="on">
				<input type="hidden" id="pick" value="<?php echo $_SESSION['step1']['plocation'];?>">
				<input type="hidden" id="del" value="<?php echo $_SESSION['step2']['daddress'];?>">
			<div class="form-group">
			  <label for="usr">Type of Package:</label>
				<select class="form-control" name="package_type">
					<option>Food</option>
					<option>Product</option>
					<option>Comments</option>
				</select>
			</div>
			<?php
			
			if(isset($_SESSION['customers'])){
				echo "<input type='hidden' name='customers_id' value='".$_SESSION['customers']."'>";
			}
			
			?>
			<div class="input-group input-append date datepickerr" id="datepickerr" data-provide="datepicker">
				<label for="usr">Date of Pickup:</label>
						<span style="display: inherit;" class="col-lg-12"><input type="text" name="pickup_date" class="form-control" >
				<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span></span>
			</div>
			<div class="input-group input-append date datepickerr" id="datepickerr" data-provide="datepicker">
				<label for="usr">Date of Delivery:</label>
						<span style="display: inherit;" class="col-lg-12"><input type="text" name="delivery_date" class="form-control" >
				<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span></span>
			</div>
			<div class="form-group">
			  <label for="usr">Delivery Fees</label>
				<select class="form-control" name="delivery_by">
					<option>Cash on Delivery(By receiver)</option>
					<option>By the Sender</option>
				</select>
			</div>
			<div class="form-group">
			  <label for="usr">payment Amount:</label>
			  <input type="text" class="form-control" name='payment_amount' required>
				<div class="help-block with-errors"></div>
			</div>
			<br>
			<br>
			<button type="submit" class="btn btn-success col-lg-5 pull-right" name="send_package">Send Package</button>
	</form>