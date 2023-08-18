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

			<h3 class="box-title">Invoices Settings</h3>

            </div>

            <!-- /.box-header -->

            <!-- form start -->

		<a href="../invoicehtml.php" target="_blank" class="btn btn-primary">Print Blank Invoice</a>
		<br>
		<br>
          <form role="form" action="" method="post" enctype="multipart/form-data">

            <div class="box-body">

                <div class="form-group">

                  <label for="exampleInputEmail1">Display Logo?</label>

					<select name='display_logo' class='form-control'>

						<option value='1' <?php if($_POST['display_logo']=='1')echo "selected"; ?>>Enabled</option>

						<option value='0' <?php if($_POST['display_logo']=='0')echo "selected"; ?>>Disabled</option>

					</select>	

					<span class="help-block">Disable or Enable Logo on invoice</span>

			   </div>

			    <div class="form-group">

                  <label for="exampleInputEmail1">Logo Path</label>

                  <input type="text" class="form-control" id="exampleInputEmail1" name="logo" value="<?php echo $_POST['logo']; ?>" >

					<span class="help-block">Relative to root folder</span>

			   </div>

			    <div class="form-group">

                  <label for="exampleInputEmail1">Current Logo:</label>

                  <img src='<?=$_POST['logo']?>' style='height:100px;'><span class="help-block">Will be displayed on the footer of the invoice</span>

			   </div>

			    

              <!-- /.box-body -->

				</div>

              <div class="box-footer">

                <input type="submit" name="submit" value="Submit" class="btn btn-primary">

              </div>

            </form>

          </div>