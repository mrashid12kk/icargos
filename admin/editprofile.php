<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id'])){
	require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],7,'edit_only',$comment =null)) {

        header("location:access_denied.php");
    }
   // ini_set('display_errors', 1);
   // ini_set('display_startup_errors', 1);
   // error_reporting(E_ALL);
    $msg='';
	if(isset($_POST['editp']))
	{ 
		// include "pages/users/dbedit.php";
		$users_id=$_SESSION['users_id'];
		$target_dir = "img/";
		$flag = 0;
		if(isset($_FILES["fileToUpload"]["name"]) && $_FILES["fileToUpload"]["name"]!="")
		{
			$target_file = $target_dir .uniqid(). basename($_FILES["fileToUpload"]["name"]);
			$extension = pathinfo($target_file,PATHINFO_EXTENSION);
			if($extension=='jpg'||$extension=='png'||$extension=='JPG'||$extension=='PNG'||$extension=='GIF'||$extension=='gif')
			{
				$size=$_FILES["fileToUpload"]["size"];
				if($size>2000000)
				{
					echo "file size too large";
				}
				else
				{
					if(!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],$target_file))
					{
					}
					$query2=mysqli_query($con,"UPDATE users SET image='$target_file' WHERE id='$users_id'") or die(mysqli_error($con)); 
					$rowcount=mysqli_affected_rows($con);
					if($rowcount >0 )
					{
						$flag = 1;
						$msg='<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> Alert!</h4>
						Success.Your profile Image been Updated .
					  	</div>';
					}
					else
					{
						$msg='<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-ban"></i> Alert!</h4>
						Unsuccess.Your profile Image has not been updated.
					  	</div>';
					}
				}
		    }
		    else
		    {
				$msg='<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-ban"></i> Alert!</h4>
					Unsuccess.Your profile Image  Type Wrong.
				  	</div>';
			}
	    }
	    $Name=mysqli_real_escape_string($con,$_POST['Name']);
		// $staff_id=mysqli_real_escape_string($con,$_POST['staff_id']);
		$plate_no=mysqli_real_escape_string($con,$_POST['plate_no']);
		$phone=mysqli_real_escape_string($con,$_POST['phone']);
		$email=mysqli_real_escape_string($con,$_POST['email']);
		
		$query=mysqli_query($con,"UPDATE `users` SET `Name`='$Name',`phone`='$phone',`plate_no`='$plate_no',email='".$email."' where id=$users_id");
		$rowcount=mysqli_affected_rows($con);
		if($rowcount > 0)
		{
			$msg='<div class="alert alert-success alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-check"></i> Alert!</h4>
			Success.Your profile has been Updated please logout then login.
		  	</div>';
		}
		else if($flag == 0)
		{
			$msg='<div class="alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa fa-ban"></i> Alert!</h4>
			Unsuccess.Your profile has not been updated.
		  	</div>';
		}
    } 
	include "includes/header.php";
?>
<body data-ng-app>
	
   
    
	<?php
	
	include "includes/sidebar.php";
	
	?>
    <!-- Aside Ends-->
    
    <section class="content">
    	 
	<?php
	include "includes/header2.php";
	?>
        
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid">
        	
            <div class="page-header"><h1><?php echo getLange('dashboard'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1></div>
            
            <?php
	
			include "pages/users/editprofile.php";
			
			?>
					
            
        </div>
        <!-- Warper Ends Here (working area) -->
        
        
      <?php
	
	include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
	?>