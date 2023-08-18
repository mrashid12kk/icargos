<?php
session_start();
include 'includes/conn.php';
if(isset($_SESSION['users_id'])){
 	header("Location:dashboard.php?status=active",true,301);
	}else{
		$language = mysqli_query($con,"SELECT * FROM portal_language WHERE is_default=1");
	    $response = mysqli_fetch_assoc($language);
	    if (!isset($response) && empty($response)) {
	    	$response = array(
	                'language'=>'english',
	                'direction'=>'ltr',
	                'id'=>1
	            );
	    }
		if(isset($_POST['submit'])){
		$user_name=$_POST['user_name'];
		$query=mysqli_query($con,"SELECT * FROM users WHERE user_name='$user_name' OR email='$user_name'") or die(mysqli_error($con));
		$fetch=mysqli_fetch_array($query);
		$password=mysqli_real_escape_string($con,$_POST['password']);
		$hash=$fetch['password'];
		if(password_verify($password,$hash)){
			$_SESSION['users_id']=$fetch['id'];
			$_SESSION['users_email']=$fetch['email'];
			$_SESSION['users_name']=$fetch['Name'];
			$_SESSION['type']=$fetch['type'];
			$_SESSION['branch_id']=$fetch['branch_id'];
			$_SESSION['user_role']=$fetch['user_role'];
			$_SESSION['user_role_id'] = $fetch['user_role_id'];
			$_SESSION['language'] = $response['language'];
			$_SESSION['language_id'] = $response['id'];
			header("Location:dashboard.php?status=active");
		}else{
			$_SESSION['login_fail'] = 'Login fail';
			header("Location:index.php");
		}
	}
}
?>
