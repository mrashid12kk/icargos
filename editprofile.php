<?php

$banks_list = array(

);

session_start();
include_once "includes/conn.php";
if(isset($_SESSION['customers'])){
	require_once "includes/role_helper.php";
	if (!checkRolePermission(10 ,'view_only','')) {

		header("location:access_denied.php");
	}
	include "includes/header.php";
	$cities = mysqli_query($con,"SELECT * FROM cities WHERE 1 ");
	$page_title = 'Edit Profile';
	$is_profile_page = true;
	?>
	<style>
		#fileform .col-lg-12{
			padding: 0;
		}section .dashboard .white {
			background: #fff;
			padding: 0;
			box-shadow: 0 0 3px #ccc;
			width: 100%;
			display: table;
		}
		#fileform .col-lg-6{
			padding: 0 15px 0 0;
		}
		.form-group label{
			color: #000;
			margin-bottom: 6px;
		}
		.white h2{
			color: #000;
		}
		select,input,textarea{
			border: 1px solid #ccc !important;
			color: #000 !important;
		}
		::-webkit-input-placeholder { /* Chrome/Opera/Safari */
			color: #000 !important;
		}
		::-moz-placeholder { /* Firefox 19+ */
			color: #000 !important;
		}
		:-ms-input-placeholder { /* IE 10+ */
			color: #000 !important;
		}
		:-moz-placeholder { /* Firefox 18- */
			color: #000 !important;
		}
	}





</style>
<style>
	table th {
		color: #8f8f8f;
	}
	.table-bordered tr td{
		color: #000;
	}
	@media (max-width: 1024px){
		.container{
			width: 100%;
		}
		.padding30 .dashboard {
			margin-top: 0 !important;
			margin-bottom: 30px;
		}
	}
	@media(max-width: 767px){
		.container{
			width: auto;
		}
		.white h2 {
			margin-top: 23px !important;
		}
		.dashboard .col-sm-8 {
			width: 100%;
			padding: 0 !important;
			margin-top: 19px;
		}
		.col-sm-3{
			padding: 0;
			color: #000;
			margin-bottom: 8px;
		}
		.col-lg-12 ,.col-sm-3{
			padding: 0;
		}
		.btn-danger{
			width: 100%;
		}
		section .white {
			min-height: auto;
		}
		.bg ,.password{
			padding: 0px 0 5px;
		}
		.profile{
			padding: 4px 12px !important;
		}
		.white h2 {
			margin-top: 0 !important;
		}

	}
	.btn-success {
		color: #fff !important;
		background-color: #286fad;
		border-color: #286fad;
	}
</style>
<section class="bg padding30">
	<div class="container-fluid dashboard">
		<div class="col-lg-2 col-md-3 col-sm-4 profile" style="margin-left:0">
			<?php
			include "includes/sidebar.php";
			?>
		</div>
		<div class="col-lg-10 col-md-9 col-sm-8 profile">


			<div class="row">
				<div class="col-lg-12  login">
					<div class="white">
						<h2 style="    background-color: #286fad;
						border-color: #286fad;
						margin: 0;
						color: #fff;
						font-size: 14px;
						padding: 10px 15px;
						border-bottom: 1px solid transparent;
						border-top-left-radius: 3px;
						border-top-right-radius: 3px;"><?php echo getLange('editprofile'); ?> </h2>
						<?php
						if(isset($_POST['update'])){

							if($_FILES["fileToUpload"]["name"]!=''){
								$target="users/";
								$target_file = $target .uniqid(). basename($_FILES["fileToUpload"]["name"]);
								$extension = pathinfo($target_file,PATHINFO_EXTENSION);
								if($extension=='jpg'||$extension=='png'||$extension=='jpeg') {
									move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],$target_file);
								}
								$query2=mysqli_query($con,"UPDATE `customers` SET image='$target_file' WHERE id='$id'");
							}
							if($_POST['bussiness_type1']=='Shop'){
								if($_FILES["trade_license"]["name"]!=''){
									$target="license/";
									$target_file = $target .uniqid(). basename($_FILES["trade_license"]["name"]);
									$extension = pathinfo($target_file,PATHINFO_EXTENSION);
									if($extension=='docx'||$extension=='pdf'){
										move_uploaded_file($_FILES["trade_license"]["tmp_name"],$target_file);
									}
									$query2=mysqli_query($con,"UPDATE `customers` SET trade_license='$target_file' WHERE id='$id'");
								}
							}
							if($_FILES["emirates_id"]["name"]!=''){
								$target="emirates_id/";
								$target_file = $target .uniqid(). basename($_FILES["emirates_id"]["name"]);
								$extension = pathinfo($target_file,PATHINFO_EXTENSION);
								if($extension=='jpg'||$extension=='png'||$extension=='jpeg') {
									move_uploaded_file($_FILES["emirates_id"]["tmp_name"],$target_file);
								}
								$query2=mysqli_query($con,"UPDATE `customers` SET emirates_id='$target_file' WHERE id='$id'");
							}

							unset($_POST['update']);
							$form_data = $_POST;
							$sql_query = mysqli_query($con,"SELECT * FROM customers WHERE id =".$id." ");
							$db_data = mysqli_fetch_assoc($sql_query);
							$msg = "";
				// foreach($db_record as $db_data){
							if($db_data['fname'] != $form_data['fname']){
								$msg .= 'Customer Name: '.$db_data['fname'].' Updated to '.$form_data['fname'].'<br>';
							}
							if($db_data['bname'] != $form_data['bname']){
								$msg .= 'Business Name: '.$db_data['bname'].' Updated to '.$form_data['bname'].'<br>';
							}
							if($db_data['bank_name'] != $form_data['bank_name']){
								$msg .= 'Bank Name: '.$db_data['bank_name'].' Updated to '.$form_data['bank_name'].'<br>';
							}
							if($db_data['bank_ac_no'] != $form_data['bank_ac_no']){
								$msg .= 'Bank Account: '.$db_data['bank_ac_no'].' Updated to '.$form_data['bank_ac_no'].'<br>';
							}
							if($db_data['mobile_no'] != $form_data['mobile_no']){
								$msg .= 'Phone No: '.$db_data['mobile_no'].' Updated to '.$form_data['mobile_no'].'<br>';
							}
							if($db_data['city'] != $form_data['city']){
								$msg .= 'City: '.$db_data['city'].' Updated to '.$form_data['city'].'<br>';
							}
							if($db_data['address'] != $form_data['address']){
								$msg .= 'address: '.$db_data['address'].' Updated to '.$form_data['address'].'<br>';
							}
				// }

							$sql="update customers set ";
							$countt=0;
							foreach($_POST as $keys=>$values){
								$sql.="$keys='$values'";
								$countt++;
								if($countt!==count($_POST)){
									$sql.=",";
								}
								else{
									$sql.=" where id=$id";
								}
							}
				// die($sql);
							$query=mysqli_query($con,$sql);
							require_once 'admin/includes/functions.php';
							$message['body'] = $msg;
							$message['subject'] = ''.getConfig('companyname').'  Update Profile';
							sendEmailToAdmin($data, $message);
				// $fname=mysqli_real_escape_string($con,$_POST['fname']);
				// $bname=mysqli_real_escape_string($con,$_POST['bname']);
			  // $family_name=mysqli_real_escape_string($con,$_POST['family_name']);
				// $bussiness_type=mysqli_real_escape_string($con,$_POST['bussiness_type']);
			  // $payment_method=mysqli_real_escape_string($con,$_POST['payment_method']);
			  // $address=mysqli_real_escape_string($con,$_POST['address']);
				// $query=mysqli_query($con,"UPDATE `customers` SET `fname`='$fname',`bname`='$bname',`family_name`='$family_name',`payment_method`='$payment_method',`bussiness_type`='$bussiness_type',`address`='$address' WHERE id='$id'");
							if($query==false){
								print(mysqli_error($con));
							}
							$rowscount=mysqli_affected_rows($con);
							if($rowscount=1){
								echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you successfully update your profile.</div>';
							}
						}
						$query=mysqli_query($con,"select * from customers where id='$id'");
						$fetch=mysqli_fetch_array($query);

						?>
						<form autocomplete="off" role="form" id="fileform" data-toggle="validator" action="" method="post" enctype="multipart/form-data"data-fv-framework="bootstrap"
						data-fv-icon-valid="glyphicon glyphicon-ok"
						data-fv-icon-invalid="glyphicon glyphicon-remove"
						data-fv-icon-validating="glyphicon glyphicon-refresh" >
						<div class="col-lg-12 edit_profile_box">
							<div class="row">


							</div>
							<div class="row" style="padding:12px 0 0;">

								<div class="form-group col-sm-3" id="fname"  style="display:<?php if($fetch['bussiness_type1']=='Shop') echo "none";?>";>
									<label for="usr"><?php echo getLange('accountname'); ?> :</label>
									<input type="text" class="form-control" value="<?php echo $fetch['fname']; ?>" name="fname" required>
								</div>
								<div class="form-group col-sm-3">
									<label for="client_code"><?php echo getLange('clientcode'); ?> :</label>
									<input type="text" class="form-control" readonly="true" name="client_code" value="<?php echo $fetch['client_code']; ?>" >
								</div>
								<div class="form-group col-sm-3" id="bname"  style="display:<?php if($fetch['bussiness_type1']=='Personal') echo "none"; else echo "block"; ?>";>
									<label for="usr"><?php echo getLange('businessname'); ?> :</label>
									<input type="text" class="form-control"  value="<?php echo $fetch['bname']; ?>" name="bname" required>
								</div>


								<div class="form-group col-sm-3"  >

									<label for="usr"><?php echo getLange('mobile'); ?> #:</label>

									<input type="text" class="form-control"  name="mobile_no"  value="<?php echo $fetch['mobile_no']; ?>" required>

								</div>


								<div class="form-group col-sm-3">
									<label for="usr"><?php echo getLange('email'); ?>:</label>
									<input type="email" class="form-control "  value="<?php echo $fetch['email']; ?>" name="email" disabled >
								</div>
								<div class="form-group  col-sm-3">
									<label for="pwd"><?php echo getLange('city'); ?>:</label>
									<select class="form-control" name="city">
										<?php while($row = mysqli_fetch_array($cities)){ ?>
											<option <?php if(trim($row['city_name']) == trim($fetch['city'])) { echo "selected"; } ?> ><?php echo isset($row['city_name']) ? $row['city_name'] : ''; ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="form-group col-sm-3" id="bname" >
									<label for="usr"><?php echo getLange('bankname'); ?> :</label>
									<input type="text" class="form-control" value="<?php echo $fetch['bank_name']; ?>"  name="bank_name" required>
								</div>
								<div class="form-group col-sm-3" id="bname" >
									<label for="usr"><?php echo getLange('accountname'); ?> :</label>
									<input type="text" class="form-control" value="<?php echo $fetch['bank_ac_no']; ?>"  name="bank_ac_no" required>
								</div>
								<div class="form-group col-sm-3" id="acc_title" >
									<label for="usr"><?php echo getLange('accountitle'); ?> :</label>
									<input type="text" class="form-control" value="<?php echo $fetch['acc_title']; ?>"  name="acc_title" required>
								</div>

								<div class="form-group col-sm-3" id="branch_name" >
									<label for="usr"><?php echo getLange('branchname'); ?> :</label>
									<input type="text" class="form-control" value="<?php echo $fetch['branch_name']; ?>"  name="branch_name" >
								</div>
								<div class="form-group col-sm-3" id="branch_code" >
									<label for="usr"><?php echo getLange('branchcode'); ?> :</label>
									<input type="text" class="form-control" value="<?php echo $fetch['branch_code']; ?>"  name="branch_code" >
								</div>
								<div class="form-group col-sm-3" id="swift_code" >
									<label for="usr"><?php echo getLange('swiftcode'); ?> :</label>
									<input type="text" class="form-control" value="<?php echo $fetch['swift_code']; ?>"  name="swift_code" >
								</div>
								<div class="form-group col-sm-3" id="iban" >
									<label for="usr"><?php echo getLange('iban'); ?>:</label>
									<input type="text" class="form-control" value="<?php echo $fetch['iban_no']; ?>"    name="iban_no" >
								</div>
								<div class="form-group  col-sm-3">
									<label for="pwd">Language Priority:</label>
									<select class="form-control" name="language_priority">
										<?php
										$sql_portal_lang = mysqli_query($con, "SELECT * FROM portal_language WHERE is_active = 1");
										while ($rowsls = mysqli_fetch_assoc($sql_portal_lang)) {?>
											<?php $name = isset($rowsls['language']) ? $rowsls['language'] : ''; ?><?php $id = isset($rowsls['id']) ? $rowsls['id'] : ''; ?>
											<option <?php if ($id==$fetch['language_priority']) { echo "selected"; } ?> value="<?php echo $id; ?>" ><?php echo ucfirst($name); ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group  col-lg-12" id="baddress">
									<div id="addres" style="padding: 0 15px;">
										<label ><?php echo getLange('address'); ?>:</label>
										<textarea class="form-control" name="address" ><?php echo $fetch['address']; ?></textarea>
									</div>
								</div>





							</div>
						   <!-- <div class="form-group">
							  <label>Change Image</label>
								<input type="file" id="img" name="fileToUpload" onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">
								 <img src="<?php echo $fetch['image']; ?>" id="blah1" class="img-rounded" width="200" border="2">
								</div> -->

							</div>
							<div class="row" style="padding:0 14px;">
								<div class="col-sm-1" style="padding:0;">
									<button style="  padding: 7px 9px;font-size: 14px;" type="submit" class="update_btn btn btn-success  editp" name="update"><?php echo getLange('update'); ?></button>
								</div>
							</div>
						</form>
					</div>

				</div>

			</div>


		</div>
	</div>
</section>

</div>
<div id='map-canvas' style="display:none;"></div>

<script src="admin/assets/js/plugins/bootstrap-validator/bootstrapValidator.min.js"></script>
<?php $disabled=disable_customer_type_field(); 
?>
<?php
// include "includes/footer.php";
}
else{
	header("location:index.php");
}
?>
<?php include 'includes/footer.php'; ?>


<script>
	document.addEventListener('DOMContentLoaded', function(){
		var disabled=<?php echo $disabled; ?>;
		if (disabled) {
			$("#fileform input,select,textarea,button").prop("disabled", true);
		}
	}, false);

	document.addEventListener('DOMContentLoaded', function(){
		$('title').text($('title').text()+' Edit Profile')
	}, false);
</script>
