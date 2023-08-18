<?php
session_start();
include 'includes/conn.php';
// if(isset($_SESSION['users_id'])){

//   header("Location:dashboard.php?status=active",true,301);
//   }else{
    $logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo getConfig('webtitle'); ?></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap/bootstrap.css" />
    <!-- Fonts  -->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="<?php echo BASE_URL ?>admin/<?php echo getConfig('webfavicon'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/app/app.v1.css" />
</head>
<body class="login_bg">
   <div class="container">
    	<div class="row" >
    		<?php
    		if(isset($_SESSION['login_fail']) && !empty($_SESSION['login_fail'])){
    			?>
    			<div class="alert alert-danger alert-dismissible" style="
						height: 71px;
						width: 367px;
						margin: 0 auto;
					">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-danger"></i> Sorry!</h4>
				Wrong Username or Password
				</div>
    		<?php unset($_SESSION['login_fail']); }
    		 ?>
    	<div class="login_page_box">

        	<div class="logo_admin">
           <img src="<?php echo BASE_URL ?>admin/<?php echo getConfig('logo'); ?>" alt="logo" />
          </div>
            <p class="text-center"><?php echo getLange('signintogetintouch'); ?></p>
            <hr class="clean">
        	<form role="form" action="login_process.php" method="post">
              <div class="form-group input-group">
              	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="text" class="form-control" name="user_name" placeholder="<?php echo getLange('username').' '.getLange('or').' '.getLange('email').' '.getLange('address'); ?>" required>
              </div>
              <div class="form-group input-group">
              	<span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input type="password" class="form-control" name="password" placeholder="<?php echo getLange('password'); ?>" required>
              </div>

        	  <button type="submit" name="submit" class="btn btn-purple btn-block"><?php echo getLange('signin'); ?></button>
            </form>
        </div>
        </div>
    </div>
    <!-- JQuery v1.9.1 -->
	<script src="assets/js/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
 <!-- Bootstrap -->
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>
  <!-- NanoScroll -->
    <script src="assets/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
  <!-- Custom JQuery -->
	<script src="assets/js/app/custom.js" type="text/javascript"></script>
</body>
</html>
<?php
// }
?>

