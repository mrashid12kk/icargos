<?php
session_start();
$page_title = 'Login';
include_once "includes/conn.php";
$companyname = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='companyname' "));
$email = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='email' "));
$contactno = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='contactno' "));

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

.theme-clr,
    .hvr-clr:hover,
    .hvr-clr:focus,
    a:focus,
    a:hover,
    .theme-menu>li:hover>a::after,
    .theme-menu>li.active>a,
    .list-items li.active a,
    .header-main .theme-menu .dropdown-menu>li>a:hover,
    .header-main .theme-menu .dropdown-menu>li>a:focus {
        color: #3a3a3a  ;
}
.tracking-form .form-group .btn-1:hover{
  color: #fff !important;
}
.pass_main{
	position: relative;
}
.btn-info:hover {
    color: #fff;
    background-color: #286fad;
    border-color: #286fad;
}
.view_pass {
    position: absolute;
    top: 0;
    right: 0;
    color: #fff;
    border-radius: 0 2px 2px 0;
    background: #286fad;
    border: 1px solid #286fad;
}
.view_pass:hover,.view_pass:focus{
	color: #fff !important;
}
.pass_main i{
	padding: 6px;
    font-size: 20px;
}
@media (max-width: 1250px){
    .container{
        width: 100%;
    }
}

@media (max-width: 1024px){
    .container{
        width: 100%;
    }

/*#header_wrap .navbar-nav .login_title a,*/ .free_qoute button {
    padding: 6px 10px !important;
    margin-top: 0 !important;
    font-size: 11px !important;
}


}

@media (max-width: 767px){
    .container{
        width: auto;
    }
}


</style>
            <!-- Content Wrapper -->
            <article>
                <!-- Tracking -->
                <section class="pt-50 tracking-wrap">
                    <div class="theme-container container ">
                        <div class="row pad-10 tracking_form">
                            <?php if(isset($_SESSION['success']) && !empty($_SESSION['success'])){ ?>
                    <?php
                    echo'<div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                                Password has been changed
                              </div>';
                     ?>
                    <?php unset($_SESSION['success']); } ?>
                        	<!-- <div>
                        		<h2 style="    font-size: 20px;color: #23294c;font-weight: 500;"> <span style="color: #286fad ;"><?php echo $companyname['value']; ?>
                            </h2>
                        	</div> -->
                            <div class="col-md-12 tracking-form" >
                            	<h2 class="title-1"> <?php echo getConfig('companyname'); ?></h2>
                                <h2 class="title-1"> <?php echo getLange('loginhere'); ?></h2> <span class="font2-light fs-12" style="color: #000;"><?php echo getLange('nowyoucanloginhereeasily'); ?></span>
                                <?php
						if(isset($_POST['login'])){

					$email=mysqli_real_escape_string($con,$_POST['email']);
					$email = strtolower($email);
					$password=mysqli_real_escape_string($con,md5($_POST['password']));
					$query=mysqli_query($con,"SELECT * FROM customers where LOWER(email)='$email' AND password = '".$password."' AND status = 1 ");
					$count=mysqli_affected_rows($con);
					if($count>0){
						$fetch=mysqli_fetch_array($query);
						$_SESSION['customers']=$fetch['id'];
                        $_SESSION['address']=$fetch['address'];
                        $_SESSION['customer_type']=$fetch['customer_type'];
                        $_SESSION['user_customer_id']='';
                        if(!isset($_SESSION['language']) && empty($_SESSION['language'])){
                        	 $_SESSION['language']='english';
                        }
						mysqli_query($con, "UPDATE customers SET is_online = 1 WHERE id = ".$fetch['id']);
						echo '<script>window.location.href="profile.php";</script>';
					}else{
						$pending_query = mysqli_query($con,"SELECT * FROM customers WHERE email ='$email' && (status = 0 OR status=2)  ");
						$count=mysqli_affected_rows($con);
						if($count>0){
							echo'<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-check"></i> Alert!</h4>
							Your Account is inactive.
						  </div>';
						}else{
							$querycu=mysqli_query($con,"SELECT * FROM customer_user where LOWER(email)='$email' AND password = '".$password."'");
							$count=mysqli_affected_rows($con);
							if($count>0){
								$fetchcu=mysqli_fetch_array($querycu);
							
								$querycus=mysqli_query($con,"SELECT * FROM customers where id=".$fetchcu['created_by']);
								$fetchcus=mysqli_fetch_array($querycus);
								$_SESSION['customers']=$fetchcus['id'];
		                        $_SESSION['address']=$fetchcus['address'];
		                        $_SESSION['customer_type']=$fetchcus['customer_type'];
		                        $_SESSION['user_customer_id']=$fetchcu['id'];
		                        if(!isset($_SESSION['language']) && empty($_SESSION['language'])){
		                        	 $_SESSION['language']='english';
		                        }
								//mysqli_query($con, "UPDATE customers SET is_online = 1 WHERE id = ".$fetchcus['id']);
								echo '<script>window.location.href="profile.php";</script>';
							}
								else{
									
									echo'<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4><i class="icon fa fa-check"></i> Alert!</h4>
									No such email or password available please signup first
								  </div>';
								}

						// echo "<script>alert('No such email or email availble please signup first then login');</script>";
					}
				}
			}
?>
								<div class="row">

								<div class="login-form clrbg-before">
									<form action="" method="post" class="login">
										<div class="form-group">
                                            <svg class="envelope_svg" viewBox="0 0 24 24"><path d="M21 9v9a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V9c0-1.11.603-2.08 1.5-2.598l-.003-.004l8.001-4.62l8.007 4.623l-.001.003A2.999 2.999 0 0 1 21 9zM3.717 7.466L11.5 12.52l7.783-5.054l-7.785-4.533l-7.781 4.533zm7.783 6.246L3.134 8.28A1.995 1.995 0 0 0 3 9v9a2 2 0 0 0 2 2h13a2 2 0 0 0 2-2V9c0-.254-.047-.497-.134-.72L11.5 13.711z" fill="#626262"/></svg>
                                            <input type="text" name="email" placeholder="<?php echo getLange('email').' '.getLange('address') ?>" class="form-control">
                                        </div>
										<div class="form-group pass_main">
                                            <svg class="envelope_svg" viewBox="0 0 24 24"><path fill-opacity=".886" d="M16 8a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3v-8a3 3 0 0 1 3-3V6.5a4.5 4.5 0 1 1 9 0V8zM7 9a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2H7zm8-1V6.5a3.5 3.5 0 0 0-7 0V8h7zm-3.5 6a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm0-1a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5z" fill="#626262"/></svg>
                                            <input type="password" name="password" placeholder="<?php echo getLange('password'); ?>" class="form-control " id="password">

										</div>
										<div class="form-group">
											<button class="btn-1 " name="login" type="submit"> <?php echo getLange('signinnow'); ?> </button>
										</div>
									</form>
									<a href="forgot_password.php" class="gray-clr"> <?php echo getLange('forgetpassword'); ?> </a>
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
