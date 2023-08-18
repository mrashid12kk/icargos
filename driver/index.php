<?php

	session_start();
	$admin_path = '../admin/';
	include $admin_path.'includes/conn.php';

	if(isset($_SESSION['users_id'])){

		header("location:../admin/dashboard.php?status=active");

	}

	else{

		if(isset($_POST['submit'])){

		$user_name=$_POST['user_name'];

		$query=mysqli_query($con,"select * from users where (user_name='$user_name' or email='$user_name') AND type = 'driver'") or die(mysqli_error($con));

		$fetch=mysqli_fetch_array($query);

		$password=mysqli_real_escape_string($con,$_POST['password']);

		$hash=$fetch['password'];

		if(password_verify($password,$hash)){

			$_SESSION['users_id']=$fetch['id'];

			$_SESSION['type']=$fetch['type'];
			$_SESSION['branch_id']=$fetch['branch_id'];

			header('location: ../admin/add_log.php?login&status=active');

			die;

		}

		else{

			echo '<div class="alert alert-danger alert-dismissible" style="

						height: 71px;

						width: 367px;

						margin: 0 auto;

					">

                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

                <h4><i class="icon fa fa-danger"></i> Sorry!</h4>

				Wrong user_name or password

				</div>';

		}



	}


?>

<!DOCTYPE html>

<html lang="en">



<head>

  

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">



	<title>Delivery System</title>



	<meta name="description" content="">

	<meta name="author" content="Akshay Kumar">



	<!-- Bootstrap core CSS -->

	<link rel="stylesheet" href="../admin/assets/css/bootstrap/bootstrap.css" /> 



    <!-- Fonts  -->

    <link href='http://fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>

    

    <!-- Base Styling  -->

    <link rel="stylesheet" href="../admin/assets/css/app/app.v1.css" />



	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>

      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->

<style>
	
@media(max-width: 1199px){
	.container{
		width: 1000px;
		margin: 0 !important;
	}
	.container_diver{
		width: 100%;
	}
	body{
		background-size: cover !important;
		width: 100% !important;
	}
}
@media(max-width: 1024px){
	.container{
		width: 100%;
	}
	
}
@media(max-width: 767px){
	.container{
		width: auto;
	}
	.form-group {
    margin: 0 1px 10px !important;
}
.row{
	margin-top: 60px;
	padding-bottom: 100%;
}
	
}
body{
		background-size: cover !important;
		width: 100% !important;background-position:top center !important; 
	}
</style>

</head>

<body style="background: url('../admin/images/delivery-service-bg.jpg') no-repeat;background-size: 100%;width: 1365px;">	

    

	

    <div class="container container_diver">

    	<div class="row" style="margin-top: 172px;">

    	<div class="col-lg-4 col-lg-offset-4">

        	<h3 class="text-center">Deliver System</h3>

            <p class="text-center">Sign in to get in touch</p>

            <hr class="clean">

        	<form role="form" action="" method="post">

              <div class="form-group input-group">

              	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>

                <input type="text" class="form-control" name="user_name" placeholder="Username or Email Adress " required>

              </div>

              <div class="form-group input-group">

              	<span class="input-group-addon"><i class="fa fa-key"></i></span>

                <input type="password" class="form-control" name="password" placeholder="Password" required>

              </div>

              

        	  <button type="submit" name="submit" class="btn btn-purple btn-block">Sign in</button>

            </form>

        </div>

        </div>

    </div>

    

    

    

    <!-- JQuery v1.9.1 -->

	<script src="../admin/assets/js/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>

 <!-- Bootstrap -->

    <script src="../admin/assets/js/bootstrap/bootstrap.min.js"></script>

    

    

    <!-- NanoScroll -->

    <script src="../admin/assets/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>

    

	

    

    

    <!-- Custom JQuery -->

	<script src="../admin/assets/js/app/custom.js" type="text/javascript"></script>

    



  

</body>

</html>

<?php

	}



?>

