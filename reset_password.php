<?php
session_start();
$page_title = 'Login';
include_once "includes/conn.php";
function encrypt($string){
	$key="wrer3";
	  $result = '';
	  for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	  }
	  return base64_encode($result);
	}
	function decrypt($string) {
	$key="wrer3";
	  $result = '';
	  $string = base64_decode($string);
	  for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	  }
	  return $result;
	}
						
	// $code = $_GET['code'];
if(isset($_SESSION['customers'])){
	header("location:profile.php");
}
else{
include "includes/header.php";
$valid_key = false;
$user = null;
if(isset($_GET['key']) && $_GET['key'] != '') {
	$id = trim(base64_decode(trim($_GET['key'])));
	$user = mysqli_query($con, "SELECT * FROM customers WHERE id = '".$id."' AND status = 1");
	$user = ($user) ? mysqli_fetch_object($user) : null;
	if($user)
		$valid_key = true;
}
if($valid_key == false) {
	echo '<script>window.location.href="index.php";</script>';
	exit();
}
?>
    
<style>
	.tracking-form .form-group .btn-1:hover{
  color: #fff !important;
}
</style>
            <!-- Content Wrapper -->
            <article> 
                <!-- Breadcrumb -->
                <!-- <section class="theme-breadcrumb pad-50">                
                    <div class="theme-container container ">  
                        <div class="row">
                            <div class="col-sm-8 pull-left">
                                <div class="title-wrap">
                                    <h2 class="section-title no-margin hide-register-title">Login </h2>
                                    <p class="fs-16 no-margin"> Login Here </p>
                                </div>
                            </div>
                            <div class="col-sm-4">                        
                                <ol class="breadcrumb-menubar list-inline">
                                    <li><a href="tracking.html#" class="gray-clr">Home</a></li>                                   
                                    <li class="active">Login</li>
                                </ol>
                            </div>  
                        </div>
                    </div>
                </section> -->
                <!-- /.Breadcrumb -->
                <!-- Tracking -->
                <section class="pt-50 tracking-wrap">    
                    <div class="theme-container container ">  
                        <div class="row pad-10 tracking_form">
                            <div class="col-md-12 tracking-form" >     
                                <h2 class="title-1"> Reset Password</h2> <span class="font2-light fs-12" style="color: #000;">Enter your new Password</span>
                                <?php
						if(isset($_POST['login'])){
							
					$password=mysqli_real_escape_string($con,$_POST['password']);
					$confirm_password=mysqli_real_escape_string($con,$_POST['confirm_password']);
					if($password != $confirm_password) {
						echo'<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-check"></i> Alert!</h4>
							Both Passwords are not same
						  </div>';
					} else {
						$user_id = $_POST['user_id'];
						$user = mysqli_query($con, "SELECT * FROM customers WHERE id = '".$user_id."' AND status = 1");
						$user = ($user) ? mysqli_fetch_object($user) : null;
						if($user && isset($user->id)) {
							$flag = mysqli_query($con, "UPDATE customers SET password = '".md5($password)."' WHERE id = ".$user_id);
							if($flag){
								echo'<div class="alert alert-success alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h4><i class="icon fa fa-check"></i> Alert!</h4>
								Password has been changed
							  </div>';
							  $_SESSION['success'] = 'Password has been changed.';
							  echo '<script>window.location.href="index.php";</script>';
							}else{
								echo'<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h4><i class="icon fa fa-check"></i> Alert!</h4>
								Unable to Change Password
							  </div>';
							  
							  exit();
							}
						} else {
							echo'<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h4><i class="icon fa fa-check"></i> Alert!</h4>
								Unable to Change Password
							  </div>';
						}
					}
				}
?>
								<div class="row">
                                   	
								<div class="login-form clrbg-before">
									<form action="" method="post" class="login">
										<input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
										<div class="form-group"><input type="password" name="password" placeholder="Password" class="form-control"></div>
										<div class="form-group"><input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control"></div>
										<div class="form-group">
											<button class="btn-1 " name="login" type="submit"> Change Password </button>
										</div>
									</form>
									<!--<a href="index.php#" class="gray-clr"> Forgot Passoword? </a>    -->                       
								</div>      
							
                                </div>
                            </div>    
                        </div>
                     </div>
                </section>
                <!-- /.Tracking -->
            </article>
            <!-- /.Content Wrapper -->
<?php
include "includes/footer.php";
}
?>
