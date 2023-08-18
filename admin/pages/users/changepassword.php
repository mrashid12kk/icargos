          

<div class="box box-primary">

            <div class="box-header with-border">

			<?php 

				if(isset($_POST['changepass'])){

					include "pages/users/dbpass.php";

				}

?>



</div>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo getLange('quickpassword'); ?> </div>
    <div class="panel-body">
      <form role="form" action="" method="post" class="validateform">
              <div class="box-body">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                  <label for="exampleInputPassword1"><?php echo getLange('oldpassword'); ?></label>
                  <input type="password" class="form-control" name="password" id="expass" required>
                </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                  <label for="exampleInputPassword1"><?php echo getLange('newpassword'); ?></label>
                  <input type="password" class="form-control passwordd" name="newpass" id="exampleInputPassword1" required >
                </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                  <label for="exampleInputPassword1"><?php echo getLange('repeatpassword'); ?></label>
                  <input type="password" class="form-control confirmpass" id="exampleInputPassword1" equalto="#exampleInputPassword1" required>
          <span class="password_errorr"></span>
        </div>
                  </div>
                </div>
                
                
                
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <button type="submit" name="changepass" id="changepass" class="btn btn-primary"><?php echo getLange('submit'); ?></button>
    </div> 
  </form>
  </div>
</div>

            <!-- /.box-header -->

            <!-- form start -->

			

          </div>