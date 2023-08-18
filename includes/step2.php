<form autocomplete='off' action="" method="post" role="form" data-toggle="validator">
		<div class="form-group">
		  <label for="usr">Name of Sender:</label>
		  <input type="text" class="form-control" name='sender[fname]'  value="<?php echo isset($customer->fname) ? $customer->fname : '' ?>" required>
			<div class="help-block with-errors"></div>
		</div>
		<div class="form-group">
		  <label for="usr">Phone number of Sender:</label>
		  <input type="text" class="form-control" name='sender[mobile_no]'  value="<?php echo isset($customer->mobile_no) ? $customer->mobile_no : '+971' ?>" required>
			<div class="help-block with-errors"></div>
		</div>
		<div class="form-group">
		  <label for="usr">Email of Sender(optional):</label>
		  <input type="email" class="form-control" name='sender[email]'  value="<?php echo isset($customer->email) ? $customer->email : '' ?>" >
			<div class="help-block with-errors"></div>
		</div>
		<div class="form-group">
		  <label for="usr">Name of Receiver:</label>
		  <input type="text" class="form-control" name='rname'  value="<?php echo isset($_SESSION['step2']['rname'])?$_SESSION['step2']['rname']:"";?>" required>
			<div class="help-block with-errors"></div>
		</div>
		<div class="form-group">
		  <label for="usr">Phone Number of Receiver:</label>
		  <input type="text" class="form-control" name="rphone" value="<?php echo isset($_SESSION['step2']['rphone'])?$_SESSION['step2']['rphone']:"+971";?>" required>
			  <div class="help-block with-errors"></div>
		</div>
		<div class="form-group">
		  <label for="usr">Email of Receiver(optional):</label>
		  <input type="email" class="form-control" name="remail" value="<?php echo isset($_SESSION['step2']['remail'])?$_SESSION['step2']['remail']:"";?>">
			  <div class="help-block with-errors"></div>
	</div>
	
	<button type="submit" class="btn btn-info col-lg-5 pull-right" name="step2">Submit</button>
</form>
<form autocomplete='off' action="" method="post" role="form" data-toggle="validator">
<button style="color:#fff;" type="submit" class="btn btn-success back_green_btn col-lg-5" name="back">BACK</button>
	</form>
		