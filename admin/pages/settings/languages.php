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
			<h3 class="box-title">language Settings</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
			
          <form role="form" action="" method="post" enctype="multipart/form-data">
            <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Page</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_page" value="<?php echo $_POST['lang_page']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Date</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_date" value="<?php echo $_POST['lang_date']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Bank</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_bank" value="<?php echo $_POST['lang_bank']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Bank Account</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_bank_account" value="<?php echo $_POST['lang_bank_account']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Payment Method</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_pmethod" value="<?php echo $_POST['lang_pmethod']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Due Date</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_duedate" value="<?php echo $_POST['lang_duedate']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Page</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_page" value="<?php echo $_POST['lang_page']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Status</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_status" value="<?php echo $_POST['lang_status']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Paid</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_paid" value="<?php echo $_POST['lang_paid']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Unpaid</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_unpaid" value="<?php echo $_POST['lang_unpaid']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Partial</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_partial" value="<?php echo $_POST['lang_partial']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Description</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_description" value="<?php echo $_POST['lang_description']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Quantity</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_qty" value="<?php echo $_POST['lang_qty']; ?>" >
				</div>
			   <div class="form-group">
                  <label for="exampleInputEmail1">Taxes</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_taxes" value="<?php echo $_POST['lang_taxes']; ?>" >
				</div>
			   <div class="form-group">
                  <label for="exampleInputEmail1">NET Price</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_netprice" value="<?php echo $_POST['lang_netprice']; ?>" >
				</div>
			   <div class="form-group">
                  <label for="exampleInputEmail1">Amount</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_amount" value="<?php echo $_POST['lang_amount']; ?>" >
				</div>
			   <div class="form-group">
                  <label for="exampleInputEmail1">Invoice</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_invoice" value="<?php echo $_POST[' ']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Client</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_client" value="<?php echo $_POST['lang_client']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Address</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_address" value="<?php echo $_POST['lang_address']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Location</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_location" value="<?php echo $_POST['lang_location']; ?>" >
				</div>
			    <div class="form-group">
                  <label for="exampleInputEmail1">Telephone</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_tel" value="<?php echo $_POST['lang_tel']; ?>" >
				</div>
			   <div class="form-group">
                  <label for="exampleInputEmail1">Pay To</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_payto" value="<?php echo $_POST['lang_payto']; ?>" >
				</div>
			   <div class="form-group">
                  <label for="exampleInputEmail1">Subtotal</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_subtotal" value="<?php echo $_POST['lang_payto']; ?>" >
				</div>
			   <div class="form-group">
                  <label for="exampleInputEmail1">Total</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" name="lang_total" value="<?php echo $_POST['lang_total']; ?>" >
				</div>
			   
			   
			</div>
              <!-- /.box-body -->

              <div class="box-footer">
                <input type="submit" name="submit" value="Submit" class="btn btn-primary">
              </div>
            </form>
          </div>