<?php
session_start();
$page_title = 'Login';
include_once "includes/conn.php";
function encrypt($string){
	$key="usmannnn";
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
	$key="usmannnn";
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
                                <h2 class="title-1"> <?php echo getLange('forgetpass'); ?></h2> <span class="font2-light fs-12" style="color: #000;"><?php echo getLange('enteremailyouraddresstorest'); ?></span>
                                <?php
						if(isset($_POST['login'])){
							
					$email=mysqli_real_escape_string($con,$_POST['email']);
					 
					$user = mysqli_query($con, "SELECT * FROM customers WHERE email = '".$email."' AND status = 1");
					$user = ($user) ? mysqli_fetch_object($user) : null;
					if($user && isset($user->id)) {
						$key = base64_encode($user->id);
						$data['email'] = $email;
						$message['subject'] = 'Reset Password';
						$message['body'] = '<p>Please follow below link to reset your password:</p>';
						$message['body'] .= '<p><a href="'.BASE_URL.'reset_password.php?key='.$key.'">Reset Password</a></p>';
						require_once 'admin/includes/functions.php';
						$flag = sendEmail($data, $message);
						// echo $flag;
						
						if($flag){
							echo'<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-check"></i> Alert!</h4>
							Email has been sent!
						  </div>';
						}else{
							echo'<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-check"></i> Alert!</h4>
							Unable to send Email Please try later
						  </div>';
						}
					} else {
						echo'<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-check"></i> Alert!</h4>
							Email does not exist, Please enter an valid Email
						  </div>';
					}
				}
?>
								<div class="row">
                                   	
								<div class="login-form clrbg-before">
									<form action="" method="post" class="login">
										<div class="form-group">
											<svg class="envelope_svg" viewBox="0 0 24 24"><path d="M21 9v9a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V9c0-1.11.603-2.08 1.5-2.598l-.003-.004l8.001-4.62l8.007 4.623l-.001.003A2.999 2.999 0 0 1 21 9zM3.717 7.466L11.5 12.52l7.783-5.054l-7.785-4.533l-7.781 4.533zm7.783 6.246L3.134 8.28A1.995 1.995 0 0 0 3 9v9a2 2 0 0 0 2 2h13a2 2 0 0 0 2-2V9c0-.254-.047-.497-.134-.72L11.5 13.711z" fill="#626262"/></svg>
											<input type="text" name="email" placeholder="<?php echo getLange('email').' '.getLange('address'); ?>" class="form-control"></div>
										<div class="form-group">
											<button class="btn-1 " name="login" type="submit"><?php echo getLange('resetpassword'); ?> </button>
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