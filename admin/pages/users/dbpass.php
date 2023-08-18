<?php
		$user_id=$_SESSION['users_id'];
		$query=mysqli_query($con,"select * from users where id='$user_id'");
		$fetch=mysqli_fetch_array($query);
		$password=mysqli_real_escape_string($con,$_POST['password']);
		$hash=$fetch['password'];
		if(password_verify($password,$hash)){
			$newpass=mysqli_real_escape_string($con,password_hash($_POST['newpass'],PASSWORD_DEFAULT));
			$query=mysqli_query($con,"UPDATE `users` SET `password`='$newpass' WHERE id='$user_id'");
			$rowcount=mysqli_affected_rows($con);
			if($rowcount>0){
				echo '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                Success.Your password has been change.
              </div>';
			}
			else{
				echo '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                Unsuccess.Your password has not been change.
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
			
		
?>