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
					
				  <?php

				  if(isset($_POST['fname']))
				  {
				  	// $client_code = $_POST['client_code'];
				  	// $check_client_q = mysqli_query($con,"SELECT * FROM customers WHERE client_code ='".$client_code."' ");

					$target_file="";
					 //  if(isset($_FILES["cnic_copy"]) && $_FILES["cnic_copy"]["name"]!=''){
						// $target="cnic_copy/";
						// 	$target_file = $target .uniqid(). basename($_FILES["cnic_copy"]["name"]);
						// 	$extension = pathinfo($target_file,PATHINFO_EXTENSION);
						// 	if($extension=='jpg'||$extension=='png'||$extension=='jpeg' ||$extension=='pdf' ||$extension=='doc') {
						// 		move_uploaded_file($_FILES["cnic_copy"]["tmp_name"],$target_file);
						// 	}
						// 	// $query2=mysqli_query($con,"UPDATE `customers` SET emirates_id='$target_file' WHERE id='$id'");
						// }

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

		<!-- steps -->

		<section>
        <div class="wizard">
        	<h3 class="modal-title modal-title-center hide-register-title" >Please Register Here</h4>
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation" class="active">
                        <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                            <span class="round-tab">1</span>
                        </a>
                            <b>Peronal information </b>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                            <span class="round-tab">2</span>
                        </a>
                            <b>bank in formation </b>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
                            <span class="round-tab">3</span>
                        </a>
                            <b> Shipping Information</b>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Step 4">
                            <span class="round-tab">4</span>
                        </a>
                            <b>password </b>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                            <span class="round-tab"> 5 </span>
                        </a>
                            <b>view all data</b>
                    </li>
                </ul>
            </div>

           <form autocomplete="off" class="validateform"  action="" method="post" class="City:" role="form"   enctype="multipart/form-data">
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" id="step1">
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
					  <label for="usr"><span style="color: red;">*</span> Company Name / Brand Name</label>
					  <input type="text" class="form-control" placeholder="Company Name / Brand Name"  name="bname" required>
					</div>

					<!-- <div class="form-group col-lg-6" id="bname" >
					  <label for="usr"><span style="color: red;">*</span> Business Manager:</label>
					  <input type="text" class="form-control"  name="business_manager" required>
					</div> -->
					<div class="form-group col-lg-6" id="bname" >
					  <label for="usr"><span style="color: red;">*</span> Person of Contact</label>
					  <input type="text" class="form-control"  placeholder="Person of Contact" name="fname" required>
					</div>


					<div class="form-group col-lg-6"  >
					  <label for="usr"><span style="color: red;">*</span> Phone Number</label>
					  <input type="text" class="form-control" placeholder="Phone Number" name="mobile_no" required>
					</div>




					<div class="form-group col-lg-6">
					  <label for="usr"><span style="color: red;">*</span> Email:</label>
					  <input type="email" class="form-control emaill"  name="email" required>
						<div class="help-block with-errors email_errorr"></div>
					</div>

					<div class="form-group col-lg-12" id="bname" >
					  <label for="usr"><span style="color: red;">*</span> Company / Pickup Address</label>
					  <textarea  name="address" placeholder="Company / Pickup Address" style="height: 52px;" type="text" class="form-control" required></textarea>
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
					  <label for="usr"><span style="color: red;">*</span> CNIC Number</label>
					  <input type="text" class="form-control cnic" placeholder="CNIC Number" required name="cnic" style="padding-top: 5px;">
					</div>



					<div class="form-group col-lg-4">
					  <label for="usr">CNIC Copy</label>
					  <input type="file" class="form-control cnic"  name="cnic_copy" style="padding-top: 5px;" >
					</div>
					</div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-primary next-step">Next</button></li>
                        </ul>
                    </div>


                    <div class="tab-pane" role="tabpanel" id="step2">
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
					</div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Previous</button></li>
                            <li><button type="button" class="btn btn-primary next-step">Next</button></li>
                        </ul>
                    </div>


                    <div class="tab-pane" role="tabpanel" id="step3">
                        <div class="row">
                        	<div class="form-group col-lg-6" id="bname" >
							  <label for="usr"> Website URL</label>
							  <input type="text" class="form-control "  name="website_url" placeholder="Website / Facebook Page">
							</div>
							<div class="form-group col-lg-6" id="bname" >
							  <label for="usr"><span style="color: red;">*</span> Select City</label>
							  <select class="form-control js-example-basic-single" name="city" required="required" >
							  	<option value="" disabled selected>Select</option>
							  	<?php while ($city=mysqli_fetch_array($cities)) {
							  		?>
							  		<option value="<?php echo $city['city_name']; ?>"><?php echo $city['city_name']; ?></option>
							  		<?php 
							  	} ?>
							</select>
							</div>
							<div class="form-group col-lg-6" id="bname" >
							  <label for="usr"><span style="color: red;">*</span> Select Nature of Account</label>
							  <select  class="form-control js-example-basic-single" name="customer_type" required>
							  	<option value="" disabled selected>Select</option>
								<option value="1">Cash on Delivery</option>
								<option value="0">Corporate Invoicing or Cash Customers</option></select>
							</div>
							<div class="form-group col-lg-6" id="bname" >
							  <label for="usr"><span style="color: red;">*</span> Product Type Select</label>
							  <select class="form-control js-example-basic-single" name="product_type" required>
							  	<option value="" disabled selected>Select</option>
									<option value="Product Type">Product Type</option><option value="Apparel">Apparel</option><option value="Automotive Pants">Automotive Pants</option><option value="Accessories">Accessories</option><option value="Gadgets">Gadgets</option><option value="Cosmetics">Cosmetics</option><option value="Jewellry">Jewellry</option><option value="Stationary">Stationary</option><option value="Handicrafts">Handicrafts</option><option value="Footwear">Footwear</option><option value="Organic &amp; Health Products">Organic &amp; Health Products</option><option value="Appliances or Electronics">Appliances or Electronics</option><option value="Home Decor or Interior items">Home Decor or Interior items</option><option value="Toys">Toys</option><option value="Fitness items">Fitness items</option><option value="MarketPlace">MarketPlace</option><option value="Document &amp; Letters">Document &amp; Letters</option><option value="Others">Others</option>
								</select>
							</div>
							<div class="form-group col-lg-6" id="bname" >
							  <label for="usr"><span style="color: red;">*</span>Expected Average Shipments / Month</label>
							  <input type="text" class="form-control"  name="expected_shipment" required>
							</div>
                        </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Previous</button></li>
                            <li><button type="button" class="btn btn-primary btn-info-full next-step">Next</button></li>
                        </ul>
                    </div>

                    <div class="tab-pane" role="tabpanel" id="step4">
                        <div class="row">
					<div class="form-group col-lg-6" id="bname" >
					  <label for="usr"><span style="color: red;">*</span>Password</label>
					  <input type="password" class="form-control "  name="password" required>
					</div>
					<div class="form-group col-lg-6" id="bname" >
					  <label for="usr"><span style="color: red;">*</span>Confirm Password</label>
					  <input type="password" class="form-control " name="repassword" required>
					</div>
					</div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Previous</button></li>
                            <li><button type="submit" class="btn btn-primary btn-info-full next-step">Submit</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="complete">
                        <div class="row">
                        	<div class="col-sm-8 padd_left">
                        		<div class="company_info">
                        			<h3>Peronal Information</h3>
                        			<ul>
                        				<li><b>Company Name / Brand Name:</b> IT-Vision</li>
                        				<li><b>Person of Contact:</b> 0332102632</li>
                        				<li><b>Phone Number:</b> 0454725569</li>
                        				<li><b>Email:</b> info@itvision.com</li>
                        				<li><b>Company / Pickup Address:</b>  Muzaffar Garh Rd and Sarwar Shaheed Rd</li>
                        				<li><b> City:</b> Jauharabad</li>
                        				<li><b> CNIC Number:</b> 382014566895	</li>
                        				<li><b> CNIC Number:</b> 382014566895	</li>
                        				<li><b> CNIC Copy:</b> 382014566895	</li>
                        			</ul>
                        		</div>
                        	</div>
                        	<div class="col-sm-4 padd_right">
                        		<div class="company_info">
                        			<h3>Bank In Formation</h3>
                        			<ul>
                        				<li><b>Bank Name:</b> Alfalah Jauharabad</li>
                        				<li><b>Account Title:</b> Asad khalid</li>
                        				<li><b>Account Number:</b> 444588556875455</li>
                        				<li><b>Branch Name:</b> Jauharabad</li>
                        				<li><b>Branch Code:</b> 0713</li>
                        				<li><b> Swift Code:</b> 256985555</li>
                        				<li><b> IBAN:</b> ALFALAH255458545555	</li>
                        			</ul>
                        		</div>
                        	</div>
                        </div>
                        <div class="row">
                        	<div class="col-sm-8 padd_left">
                        		<div class="company_info">
                        			<h3>Shipping Information</h3>
                        			<ul>
                        				<li><b>Website URL:</b> itvision.com</li>
                        				<li><b>Select City:</b> Jauharabad</li>
                        				<li><b>Select Nature of Account:</b> Cash On Delivery</li>
                        				<li><b>Product Type Select:</b> Gadgets</li>
                        				<li><b>Expected Average Shipments / Month:</b> 01-08-2020</li>
                        			</ul>
                        		</div>
                        	</div>
                        	<div class="col-sm-4 padd_right">
                        		<div class="company_info">
                        			<h3>Password</h3>
                        			<ul>
                        				<li><b>Password:</b> Global8112$</li>
                        				<li><b>Confirm Password:</b> Global8112$</li>
                        			</ul>
                        		</div>
                        	</div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </section>




					
		</div>
	</div>

  	</div>
<?php include "includes/footer.php"; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    
    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);
    
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);

    });
    $(".prev-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}
</script>
