<?php

if(isset($_POST['submit'])){

		if(isset($_FILES['sidebar_banner'])) {
			$ext = array_reverse(explode('.', $_FILES['sidebar_banner']['name']))[0];
			$path = 'assets/uploads/sidebar_banner.'.$ext;
			if(move_uploaded_file($_FILES['sidebar_banner']['tmp_name'], $path)) {
				$_POST['sidebar_banner'] = $path;
			}
		}

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

			<h3 class="box-title">General Settings</h3>

            </div>

            <!-- /.box-header -->

            <!-- form start -->

			

          <form role="form" action="" method="post" enctype="multipart/form-data">

            <div class="box-body">

                <div class="form-group">

                  <label for="exampleInputEmail1">Company Name</label>

                  <input type="text" class="form-control" id="exampleInputEmail1" name="name" value="<?php echo $_POST['name']; ?>" >

					<span class="help-block">Will be displayed on the top left of the invoice</span>

			   </div>

			    <div class="form-group">

                  <label for="exampleInputEmail1">Address</label>

                  <input type="text" class="form-control" id="exampleInputEmail1" name="address" value="<?php echo $_POST['address']; ?>" >

					<span class="help-block">Your full address</span>

			   </div>

			    <div class="form-group">

                  <label for="exampleInputEmail1">Footer notes</label>

                  <input type="text" class="form-control" id="exampleInputEmail1" name="footer" value="<?php echo $_POST['footer']; ?>" >

					<span class="help-block">Will be displayed on the footer of the invoice</span>

			   </div>

			    <div class="form-group">

                  <label for="exampleInputEmail1">Email</label>

                  <input type="text" class="form-control" id="exampleInputEmail1" name="email" value="<?php echo $_POST['email']; ?>" >

					<span class="help-block">Used in sending invoices and recieving notifications</span>

			   </div>

			    <div class="form-group">

                  <label for="exampleInputEmail1">Bank Name</label>

                  <input type="text" class="form-control" id="exampleInputEmail1" name="bank_name" value="<?php echo $_POST['bank_name']; ?>" >

					<span class="help-block">Bank Name for payments</span>

			   </div>

			    <div class="form-group">

                  <label for="exampleInputEmail1">Bank Account</label>

                  <input type="text" class="form-control" id="exampleInputEmail1" name="bank_account" value="<?php echo $_POST['bank_account']; ?>" >

					<span class="help-block">Bank Account for payments</span>

			   </div>

			    <div class="form-group">

                  <label for="exampleInputEmail1">IBAN #</label>

                  <input type="text" class="form-control" id="exampleInputEmail1" name="IBAN" value="<?php echo $_POST['IBAN']; ?>" >

					<span class="help-block">Bank Account for payments</span>

			   </div>

			    <div class="form-group">

                  <label for="exampleInputEmail1">Watermark</label>

                  <input type="text" class="form-control" id="exampleInputEmail1" name="watermark" value="<?php echo $_POST['watermark']; ?>" >

					<span class="help-block">Will be displayed across the background of the invoice</span>

			   </div>

			    <div class="form-group">

                  <label for="exampleInputEmail1">Currency</label>

                  <input type="text" class="form-control" id="exampleInputEmail1" name="currency" value="<?php echo $_POST['currency']; ?>" >

					<span class="help-block">Ex: USD EUR RON GBP AUD PND</span>

			   </div>

			  <div class="form-group">

                  <label for="exampleInputEmail1">Invoice size</label>

                  <select name='size' class="form-control" >

						<option value='A3' <?php if($_POST['size']=='A3')echo "selected"; ?>>A3</option>

						<option value='A4' <?php if($_POST['size']=='A4')echo "selected"; ?>>A4</option>

						<option value='A5' <?php if($_POST['size']=='A5')echo "selected"; ?>>A5</option>

						<option value='letter' <?php if($_POST['size']=='letter')echo "selected"; ?>>Letter</option>

						<option value='legal' <?php if($_POST['size']=='legal')echo "selected"; ?>>Legal</option>

					</select>

				  <span class="help-block">Will be displayed on the top left of the invoice</span>

			   </div>

			   <div class="form-group">

                  <label for="exampleInputEmail1">Advertisement Banner</label>

                  <input type="file" name="sidebar_banner">

					<span class="help-block">Banner for sidebar on front page</span>
				  <?php if(isset($_POST['sidebar_banner']) && $_POST['sidebar_banner'] != '') { ?>
				  	<img src="<?php echo $_POST['sidebar_banner']; ?>">
				  <?php } ?>
			   </div>

			   

			</div>
			<div class="form-group">

                  <label for="exampleInputEmail1">Weekly Offer</label>

                  <input type="text" class="form-control" name="weekly_offer_text" value="<?php echo $_POST['weekly_offer_text']; ?>" >


			   </div>
              <!-- /.box-body -->



              <div class="box-footer">

                <input type="submit" name="submit" value="Submit" class="btn btn-primary">

              </div>

            </form>

          </div>