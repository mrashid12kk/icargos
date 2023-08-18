<?php
if(isset($_POST['submit'])){
		foreach($_POST as $tag=>$val){
				$res=mysqli_query($con,"select * from config where name='$tag'") or die(mysqli_error($con));
					if(mysqli_num_rows($res)){
						mysqli_query($con,"update config set value='$val' where name='$tag'") or die(mysqli_error($con));
					}else{
						mysqli_query($con,"insert into config(name,value) values('$tag','$val')") or die(mysqli_error($con));
					
					}
			}
		}else{
			$_POST=$cfg;
		}
		?>
		
<div class="box box-primary">
            <div class="box-header with-border">
			<h3 class="box-title">Payments Settings</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
          <form role="form" action="" method="post" enctype="multipart/form-data">
            <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Enable Payments</label>
                  <select name='enable_payments' class='form-control'>
						<option value='1' <?php if($_POST['enable_payments']=='1') echo "selected"; ?>>Enabled</option>
						<option value='0' <?php if($_POST['enable_payments']=='0') echo "selected"; ?>>Disabled</option>
					</select>
				<span class="help-block">Disable or Enable Online Payments</span>
			   </div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Paypal Email</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="paypal_email" value="<?php echo $_POST['paypal_email']; ?>" >
					<span class="help-block">Used for recieving payments</span>
			   </div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Paypal Currency</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="paypal_currency" value="<?php echo $_POST['paypal_currency']; ?>" >
					<span class="help-block"><b>Must be Official!</b>(USD,EUR,GBP,RON)</span>
			   </div>
			    
              <!-- /.box-body -->

              <div class="box-footer">
                <input type="submit" name="submit" value="Submit" class="btn btn-primary">
              </div>
            </form>
          </div>