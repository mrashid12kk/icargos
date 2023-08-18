<?php

session_start();

	

include_once "includes/conn.php";

include "phpmailer/PHPMailerAutoload.php";



if(false){

	header("location:profile.php");

}

else{

include "includes/header.php";



function mailer($to,$subject,$message,$attach=''){

	global $cfg;

		$mail = new PHPMailer();

		$mail->setFrom('usman@hgdhg.com','usman');

		$mail->addAddress($to, 'Invoice');

		$mail->Subject = $subject;

		$mail->msgHTML(nl2br($message));

		$mail->AltBody = 'This is a plain-text message body';



		$mail->addAttachment($attach);



		if (!$mail->send()) {

			echo "Mailer Error: " . $mail->ErrorInfo;

			return 0;

		} else {

			return 1;

		}	

}





?>

	  <div class="col-lg-6 col-lg-offset-3" style="">

			<div class="modal-content" style="">

				  <div class="modal-header">

					<h4 class="modal-title " >Any Suggestion OR Complaints</h4>

					<?php

					if(isset($_POST['send'])){

						$name=mysqli_real_escape_string($con,$_POST['name']);

						$email=mysqli_real_escape_string($con,$_POST['email']);

						$message=mysqli_real_escape_string($con,$_POST['msg']);

						$text="Name:$name \n Email:$email \n Message:$message";

						

						// $text="<button style='color:red'>Newsletter</button>";

						$headers="From:$email";

						if(mail('muhammad.usman93333@gmail.com','Suggestion & Complaints',$text,$headers)){

							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Success!</strong> Your email has been sent successfully.</div>';

						}

						else{

							echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Sorry!</strong> You can not contact with company at that moment please try later.</div>';

						}

					}

					?>

		

				  </div>

				  <div class="modal-body">

					<div class="clearfix gray-bg gray-bg1 gray-bg2">

							<form action="" method="post" role="form" data-toggle="validator">

								<div class="col-lg-12">

							

									<div class="form-group ">

									  <label for="usr">Name:</label>

									  <input type="text" class="form-control1" name="name" required>

									    <div class="help-block with-errors"></div>

	

									</div>

									<div class="form-group">

									  <label for="usr">Email:</label>

									  <input type="email" class="form-control1" name="email" required>

									    <div class="help-block with-errors"></div>

	

									</div>

									<div class="form-group">

									  <label for="pwd">Any Suggestion OR Complaints:</label>

										<textarea class="form-control" name="msg" required></textarea>

										  <div class="help-block with-errors"></div>

	

									</div>

									<br>

									<br>

									<button type="submit" name="send" class="btn btn-success col-lg-offset-3 col-lg-6" style="margin:0;" >Send</button>

								</div>

							</form>

					</div>

				</div>

			</div>

		</div>

	</div>

	



    <div id='map-canvas'></div>

  </body>

</html>

<?php

include "includes/footer.php";

}

?>