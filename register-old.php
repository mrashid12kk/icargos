<?php

session_start();

if(isset($_SESSION['customers'])) {
	header('Location: profile.php');
	exit();
}

$banks_list = array(

);
include_once "includes/conn.php";
$cities = mysqli_query($con,"SELECT * FROM cities WHERE 1 ");

$page_title = 'Please Register Here';
include "includes/header.php";
function addQuote($value){
	return(!is_string($value)==true) ? $value : "'".$value."'";
}
 $companyname = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='companyname' "));

 // $logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
 // $logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
 ?>
 

			<div>
				  <div class="register_title">
					<h3 class="modal-title modal-title-center hide-register-title" style="color: black;font-size: 26px;font-weight: bold;">Please Register Here</h4>
				  <?php

				  if(isset($_POST['fname']))
				  {
				  	// $client_code = $_POST['client_code'];
				  	// $check_client_q = mysqli_query($con,"SELECT * FROM customers WHERE client_code ='".$client_code."' ");

					$target_file="";
					  if(isset($_FILES["cnic_copy"]) && $_FILES["cnic_copy"]["name"]!=''){
						$target="cnic_copy/";
							$target_file = $target .uniqid(). basename($_FILES["cnic_copy"]["name"]);
							$extension = pathinfo($target_file,PATHINFO_EXTENSION);
							if($extension=='jpg'||$extension=='png'||$extension=='jpeg' ||$extension=='pdf' ||$extension=='doc') {
								move_uploaded_file($_FILES["cnic_copy"]["tmp_name"],$target_file);
							}
							// $query2=mysqli_query($con,"UPDATE `customers` SET emirates_id='$target_file' WHERE id='$id'");
						}

						if(trim($_POST['password']) == trim($_POST['repassword'])){
							$send = true;
						}else{
							$send = false;
						}

						if($send) {
							// $_POST['emirates_id']==$target_file;
							 $password= md5($_POST['password']);

							$_POST['password']=$password;
							// $_POST['address']=implode(',,',$_POST['address']);
							$data = $_POST;
							$data['cnic_copy'] = $target_file;
							if(isset($data['submit']))
								unset($data['submit']);
								unset($data['repassword']);
							$email = $data['email'];
							$index = 0;
							foreach ($data as $key => &$value) {
								if(trim($value) == '') {
									array_splice($data, $index, 1);
									$index--;
								}
								$index++;
							}

							foreach ($data as $k => &$value) {
								$value =addQuote($value);
							}

							$keys = implode(", ", array_keys($data));
							$values = implode(",",$data);
							$sql = "INSERT INTO customers ($keys) VALUES($values)";
							// echo $sql;
							// exit();
							// die($sql);

							$query=mysqli_query($con,$sql) or die(mysqli_error($con));
							$customer_id = mysqli_insert_id($con);
						 $rowscount=mysqli_affected_rows($con);
						}
						if($send == true && $rowscount>0 ){
							$code = 1000 + $customer_id;
							// mysqli_query($con, "UPDATE customers SET client_code = '".$code."' WHERE id = ".$customer_id);
							if(isset($data['email']) ) {
								$data['email'] = $email;
								$customer_name = $_POST['fname'];
								$message['subject'] = 'Account Registration';
								$message['body'] = "<b>Hello ".$customer_name." </b>";
								$message['body'] .= '<p>Thank you for registering with '.$companyname['value'].'</p>';
								$message['body'] .= '<p>Your account has been created but must be activated before you can start booking your shipments. Our admin will review your information and approve within 24 hours.</p>';
								require_once 'admin/includes/functions.php';
								sendEmail($data, $message);
								// Admin
								$path = BASE_URL.'admin/customer_detail.php?customer_id='.$customer_id;
								$message['body'] = '<p>New User Account has been created</p>';
								$message['body'] .= '<p>Click below link to view customer.</p>';
								$message['body'] .= "<a href='$path'>$path</a>";
								sendEmailToAdmin($data, $message);
							}
							 $id=mysqli_insert_id($con);
							  $query=mysqli_query($con,"Select * from customers where id=$id") or die(mysqli_error($con));
							$fetch=mysqli_fetch_array($query);


							// $_SESSION['customers']=$fetch['id'];
							// $_SESSION['address']=$fetch['address'];

						echo '<div style="width: 663px; margin: 0px auto;" class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> Your registration is successful. Please wait for account approval email by '.$companyname['value'].'</div>';
							// echo "<script>document.location.href='editprofile.php';</script>";

						}else{
						echo '<div style="width: 663px; margin: 0px auto;" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> your registration is unsuccessful, please try again.</div>';
					}

				}

				  $query = mysqli_query($con, "SELECT COUNT(*) as total FROM customers");
				  $code = 1;
				  $row = mysqli_fetch_object($query);
				  if(isset($row->total)) {
				  	$code = (int)$row->total;
				  	$code++;
				  }
				  $code = $code+1000;
				  ?>
				   </div>


			</div>
		</div>
		<style>
.form-control, .input-group-addon, .bootstrap-select .btn {
    background-color: #ffffff;
    border-color: #ccc;
    border-radius: 3px;
    box-shadow: none;
    color: #000;
    font-size: 14px;
    height: 34px;
    padding: 0 20px;
    font-weight: 300;
}
		label {
    font-weight: normal;
    margin: 0;
    color: #000;
    margin-bottom: 7px;
    font-weight: bold;
}
.modal-header {
    padding: 6px 11px;
    border-bottom: 1px solid #e5e5e5;
    margin-top: 0;
}
.profile-page-title, .col-lg-4 {
    padding: 0 15px;
}
.modal-title {
	text-align: center;
}
.register_page{
	    max-width: 660px;
}
.form-group input, input.emaill {
    background-color: #f8fbff7d !important;
}
label {
    margin: 6px 0;
    font-weight: 500;
    font-size: 14px;
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
  
 

 #sidebar-open img{
            width: 45px;
    }
 
 
   #sidebar-open {
    display: none !important;
}

}

@media (max-width: 767px){
    .container{
        width: auto;
    }

 
 
 
 

.register_title {
    margin-top: 0;
}
.navbar-logo {
    left: 0px;
    margin-top: 0 !important;
    margin-left: 0 !important;
}
}
.term_label{
	color: #0a68bb;
}
</style>
		<div class="modal-body">
					<div class="clearfix gray-bg gray-bg1 gray-bg2 register-items register_page">
			<form autocomplete="off" class="validateform"  action="" method="post" class="City:" role="form"   enctype="multipart/form-data">
				<div class="col-lg-12 customer_gapp" >
					<div class="row" style="margin-left: 0px; margin-right: 0px;">

				<!-- 	<div class="form-group col-lg-12">
					  <label for="client_code">Client Code:</label>
						<input type="text" class="form-control" readonly="true" name="client_code" value="<?php echo $code; ?>">
					</div>
 -->
					<div  id="license" hidden>
					<div class="row" style="margin:0;">
							<div >
							<div class="col-lg-12">
								<label>Attach Your Trade License(docx,pdf):</label>
								<input type="file" name="trade_license" class="form-control"   data-fv-notempty="true"
        data-fv-notempty-message="Please select an image"
        data-fv-file="true"
        data-fv-file-extension="docx,pdf"
        data-fv-file-type="application/msword,application/pdf"
        data-fv-file-maxsize="2097152"
        data-fv-file-message="The selected file is not valid">
							</div>

					</div>

					</div>

				</div>
					</div>
					<div class="row" style="margin-left: 0px; margin-right: 0px;">
					<!-- <div class="form-group col-lg-6" id="fname">
					  <label for="usr"><span style="color: red;">*</span> Client Code</label>
					  <input type="text" class="form-control" name="client_code" required>
					</div> -->
					<!-- <div class="form-group col-lg-6" id="fname">
					  <label for="usr"><span style="color: red;">*</span> Account Name</label>
					  <input type="text" class="form-control" name="fname" required>
					</div> -->
					<div class="form-group col-lg-6" id="bname" >
					  <label for="usr"><span style="color: red;">*</span> Business Name:</label>
					  <input type="text" class="form-control"  name="bname" required>
					</div>

					<!-- <div class="form-group col-lg-6" id="bname" >
					  <label for="usr"><span style="color: red;">*</span> Business Manager:</label>
					  <input type="text" class="form-control"  name="business_manager" required>
					</div> -->
					<div class="form-group col-lg-6" id="bname" >
					  <label for="usr"><span style="color: red;">*</span> Contact Name:</label>
					  <input type="text" class="form-control"  name="fname" required>
					</div>


					<div class="form-group col-lg-6"  >
					  <label for="usr"><span style="color: red;">*</span> Mobile #:</label>
					  <input type="text" class="form-control"  name="mobile_no" required>
					</div>




					<div class="form-group col-lg-6">
					  <label for="usr"><span style="color: red;">*</span> Email:</label>
					  <input type="email" class="form-control emaill"  name="email" required>
						<div class="help-block with-errors email_errorr"></div>
					</div>

					<div class="form-group col-lg-12" id="bname" >
					  <label for="usr"><span style="color: red;">*</span> Pickup Address:</label>
					  <textarea  name="address" style="height: 52px;" type="text" class="form-control" required></textarea>
					</div>

					<div class="form-group  col-lg-4">
					  <label for="pwd"><span style="color: red;">*</span> City:</label>
						<select class="form-control" name="city">
							<?php while($row = mysqli_fetch_array($cities)){ ?>
							<option <?php if($row['city_name'] == 'KARACHI'){ echo "selected"; } ?> ><?php echo isset($row['city_name']) ? $row['city_name'] : ''; ?></option>
						<?php } ?>
						</select>
					</div>

					<div class="form-group col-lg-4">
					  <label for="usr"><span style="color: red;">*</span> CNIC</label>
					  <input type="text" class="form-control cnic" required name="cnic" style="padding-top: 5px;">
					</div>



					<div class="form-group col-lg-4">
					  <label for="usr">CNIC Copy</label>
					  <input type="file" class="form-control cnic"  name="cnic_copy" style="padding-top: 5px;" >
					</div>
					</div>
					<div class="row" style="margin-left: 0px; margin-right: 0px;">

					<div class="form-group col-lg-6" id="bname" >
					  <label for="usr"> Bank Name:</label>
					  <input type="text" class="form-control"  name="bank_name" >
					</div>
				    <div class="form-group col-lg-6" id="acc_title" >
					  <label for="usr"> Account Title:</label>
					  <input type="text" class="form-control"  name="acc_title" >
					</div>
					<div class="form-group col-lg-6" id="bank_ac_no" >
					  <label for="usr"> Account Number:</label>
					  <input type="text" class="form-control"  name="bank_ac_no" >
					</div>
					<div class="form-group col-lg-6" id="branch_name" >
					  <label for="usr"> Branch Name:</label>
					  <input type="text" class="form-control"  name="branch_name" >
					</div>
					<div class="form-group col-lg-6" id="branch_code" >
					  <label for="usr"> Branch Code:</label>
					  <input type="text" class="form-control"  name="branch_code" >
					</div>
					<div class="form-group col-lg-6" id="swift_code" >
					  <label for="usr"> Swift Code:</label>
					  <input type="text" class="form-control"  name="swift_code" >
					</div>
					<div class="form-group col-lg-12" id="iban" >
					  <label for="usr"> IBAN:</label>
					  <input type="text" class="form-control"  name="iban_no" >
					</div>

					<div class="form-group col-lg-6">
					  <label for="pwd"><span style="color: red;">*</span> Password:</label>
					  <input type="password" class="form-control" id="passwordboot" name="password" required>
					</div>
					<div class="form-group col-lg-6">
					  <label for="pwd"><span style="color: red;">*</span> Confirm Password:</label>
					  <input type="password" name="repassword" class="form-control" data-match="#passwordboot" required>
					  <div class="help-block with-errors"></div>
					</div>
					</div>
					<div class="row">

						<div class="col-lg-12">
							<label><input required type="checkbox" name="" class="term_condition"> By clicking Register, you agree to the <a target="_blank" class="term_label" href="/term-condition/">Terms & Conditions</a> and have read out <a target="_blank" class="term_label" href="/privacy-policy/">Privacy Policy</a></label>
						</div>
					</div>
			 	<input type="submit" name="submit" class="btn btn-info register_btn" value="Register" style="font-size: 16px;letter-spacing: 1.5px;background-color:#286fad; margin-top: 18px;text-transform: capitalize;">
			</form>
		</div>
		</div>
	</div>

  	</div>
<?php include "includes/footer.php"; ?>
