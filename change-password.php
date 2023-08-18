<?php
	session_start();
	include_once "includes/conn.php";
	if(isset($_SESSION['customers'])){
        require_once "includes/role_helper.php";
    if (!checkRolePermission(11 ,'view_only','')) {

        header("location:access_denied.php");
    }

	include "includes/header.php";

	$page_title = 'Change Password';
	$is_profile_page = true;
?>


<style>
section .dashboard .white {
    background: #fff;
    padding: 0;
    box-shadow: 0 0 3px #ccc;
    width: 100%;
    display: table;
}

section .password input[type="submit"] {
    background: #4cb034;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    color: #fff !important;
    font-size: 15px;
    margin-top: 20px;
    transition: all 0.5s ease-in-out;
    text-transform: uppercase;
}
#changepassform .col-sm-3{
	margin-top: 12px;
}
table th {
	  		color: #8f8f8f;
	  	}
	  	.table-bordered tr td{
	  		color: #000;
	  	}

@media (max-width: 1024px){
	.container{
			width: 100%;
		}
.padding30 .dashboard {
    margin-top: 0 !important;
    margin-bottom: 30px;
}
}


	@media(max-width: 767px){
		.container{
			width: auto;
		}
		.col-sm-3{
			padding: 0;
			color: #000;
			margin-bottom: 8px;
		}
		.col-lg-12 {
			padding: 0;
		}
		.btn-danger{
			width: 100%;
		}
		section .white {
    min-height: auto;
}
.bg ,.password{
    padding: 0px 0 5px;
}

	}
</style>

<section class="bg padding30">
  <div class="container-fluid dashboard">
     <div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
      <?php
		include "includes/sidebar.php";
	  ?>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8 password">


      <div class="white profile-right change-pas">
        <div class="row">
          <div class="change_pasword_title" style="padding:0;">
            <h3 style="color:#000;"><?php echo getLange('changepassword'); ?> </h3>
          </div>
          <div class="clearfix"></div>
<?php
		 if(isset($_POST['changepass'])){
			$query=mysqli_query($con,"select * from customers where id='$id'");
			$fetch=mysqli_fetch_array($query);
			$password=mysqli_real_escape_string($con,md5($_POST['password']));

			$hash=$fetch['password'];
			if($password == $hash){
				$newpass=mysqli_real_escape_string($con,md5($_POST['newpass']));
				$query=mysqli_query($con,"UPDATE `customers` SET `password`='$newpass' WHERE id='$id'");
				$rowcount=mysqli_affected_rows($con);
				if($rowcount>0){
					echo '<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> Alert!</h4>
					Success.Your password has been succesfully updated.
				  </div>';
				}
				else{
					echo '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-ban"></i> Alert!</h4>
					Unsuccess.Your password has not been updated.
				  </div>';
				}

			}
			else{
					echo '<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-ban"></i> Alert!</h4>
					Unsuccess.Your old password is wrong.
				  </div>';
				}
		 }
		 ?>

		  <form id="changepassform" action="" method="post" style="padding-top: 18px;">
           <div class="col-lg-2 col-md-3 col-sm-12"><?php echo getLange('oldpassword'); ?>  :</div>
          <div class="col-lg-4 col-md-4 col-sm-4"><input type="password" name="password" class="form-control" required></div>
          <div class="clearfix"></div>
           <div class="col-lg-2 col-md-3 col-sm-12"><?php echo getLange('newpassword'); ?>  :</div>
          <div class="col-lg-4 col-md-4 col-sm-4"><input type="password" id="passwordd" class="form-control" name="newpass" required></div>
          <div class="clearfix"></div>
           <div class="col-lg-2 col-md-3 col-sm-12"><?php echo getLange('comfirmpassword'); ?>  :</div>
          <div class="col-lg-4 col-md-4 col-sm-4"><input type="password"  id="confirmpass" class="form-control" required>
		  	<span id="password_errorr" style="position:absolute;top: 36px;"></span>
		</div>
          <div class="clearfix"></div>

           <div class="col-lg-2 col-md-2 col-sm-2"></div>
          <div class="col-lg-4" id="update_btn"><input type="submit" id="update" name="changepass" class="btn btn-danger update_btn" value="<?php echo getLange('update'); ?>" ></div>
        </form>
		</div>
      </div>
  </div>
</section>
<script>
$(function(){
$('#confirmpass').keyup(function(){
		$('#password_errorr').html('');
		var password=$('#passwordd').val();
		var confirmpass=$('#confirmpass').val();
		// alert(confirmpass);
			if(confirmpass!=password){
				$('#password_errorr').html('please enter the same password');
				}
				// else{
					// $(this).submit();
				// }

			});

	});
</script>

<?php
// include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
?>
	  <?php include 'includes/footer.php'; ?>
	  <script>
document.addEventListener('DOMContentLoaded', function(){
	$('title').text($('title').text()+' Change Password')
}, false);
</script>
