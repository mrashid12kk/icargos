
<style type="text/css">
	#phone_code .flag-container {
	    margin: 0 0 0 -52px;
	}
	.flag_default {
	    margin: 0 0 0 -13px;
	}
	.default_number {
	    margin: 0 0 0 -22px;
	}

</style>
<?php
$cities = mysqli_query($con,"SELECT * FROM cities WHERE 1 ");
$cities_from = mysqli_query($con,"SELECT * FROM cities WHERE 1 ORDER BY city_name ");
$cities_to = mysqli_query($con,"SELECT * FROM cities WHERE 1  order by ORDER BY city_name ");
$all_branches = mysqli_query($con,"SELECT * FROM branches");
$all_salemans = mysqli_query($con, "SELECT * FROM users where type='admin' and user_role_id !=4");

?>
	<form autocomplete="off"  method="POST"  action="pages/business/save_business.php"   enctype="multipart/form-data">
		<?php
		if(isset($_SESSION['fail_add']) && !empty($_SESSION['fail_add'])){
			$msg = $_SESSION['fail_add'];
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> '.$msg.'</div>';
			unset($_SESSION['fail_add']);
		}
		if(isset($_SESSION['succ_msg']) && !empty($_SESSION['succ_msg'])){
			$msg = $_SESSION['succ_msg'];
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Successful!</strong> '.$msg.'</div>';
			unset($_SESSION['succ_msg']);
		}
		 ?>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('businessaccountinformation'); ?>  </div>
	  <div class="panel-body">


				<div class="col-lg-12 customer_gapp" >

					<div class="row" style="margin-left: 0px; margin-right: 0px;">
					<!-- <div class="form-group col-lg-4" id="fname">
					  <label for="usr"><span style="color: red;">*</span> Account Name</label>
					  <input type="text" class="form-control" name="fname" required>
					</div> -->
					<!-- <div class="form-group col-lg-4" id="bname" >
					  <label for="usr"><span style="color: red;">*</span> Short Code:</label>
					  <input type="text" class="form-control"  name="bname" required>
					</div> -->
					<div class="form-group col-lg-2" id="bname" >
					  <label for="usr"><span style="color: red;">*</span><?php echo getLange('businessname'); ?>  :</label>
					  <input type="text" class="form-control"  name="bname" required>
					</div>
					<div class="form-group col-lg-2"  >
					  <label for="usr"><span style="color: red;">*</span><?php echo getLange('customer').' Pay Mode'; ?>  :</label>
					  <select type="text" class="form-control select2"  name="customer_type" required id="cust_type">
					  	<option value="0">Select Pay Mode</option>
					  	<?php $acount_type_q=mysqli_query($con,"SELECT * FROM pay_mode ORDER BY id ASC");
					  	while($account_type=mysqli_fetch_array($acount_type_q)){ ?>
					  		<option value="<?php echo $account_type['id']; ?>"><?php echo $account_type['pay_mode']; ?></option>
					  	<?php } ?>
					  </select>
					</div>
					  <input type="hidden" name="c_payable" class="form-control" id ="getValue" >
					  <input type="hidden" name="p_payable" class="form-control" id ="getValueparent1" >
					  <input type="hidden" name="p_acc_id" class="form-control" id ="p_acc_id" >
					  <input type="hidden" name="c_recievable" class="form-control" id ="getValue1" >
					  <input type="hidden" name="p_recievable" class="form-control" id ="getValueparent2" >
					  <input type="hidden" name="r_acc_id" class="form-control" id ="r_acc_id" >
					
					
					<!-- <div class="form-group  col-lg-2">
					  <label for="pwd"><span style="color: red;">*</span> <?php echo getLange('city'); ?>:</label>
						<select class="form-control js-example-basic-single" name="city">
							<?php while($row = mysqli_fetch_array($cities)){ ?>
							<option <?php if($row['city_name'] == 'KARACHI'){ echo "selected"; } ?> ><?php echo isset($row['city_name']) ? $row['city_name'] : ''; ?></option>
						<?php } ?>
						</select>
					</div> -->
					<div class="form-group col-lg-3">
					  <label for="usr"><?php echo getLange('cnic'); ?></label>
					  <input type="text" class="form-control cnic" name="cnic" style="padding-top: 5px;">
					</div>
					<div class="form-group col-lg-3" id="bname" >
					  <label for="usr"><?php echo getLange('defaultbranch'); ?>:</label>
					  <select class="form-control select2" name="branch_id">
							<?php while($row = mysqli_fetch_array($all_branches)){ ?>
							<option value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
						<?php } ?>
						</select>
					</div>
					<div class="form-group col-lg-2">
                        <label for="usr"><span style="color: red;">*</span>Sales Representative:</label>
                        <select class="form-control select2" name="sale_man_id">
                            <?php while ($row = mysqli_fetch_array($all_salemans)) { ?>
                            <option value="<?php echo $row['id'] ?>">
                                <?php echo $row['Name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
					<div class="form-group col-lg-12" id="bname" >
					  <label for="usr"><span style="color: red;">*</span> Office Address :</label>
					  <textarea  name="address" style="height: 52px;" type="text" class="form-control" required></textarea>
				</div>
				<div class="form-group col-lg-12" id="bname" >
					  <label for="usr">Pickup Address :</label>
					  <textarea  name="billing_address" style="height: 52px;" type="text" class="form-control"></textarea>
				</div>
				<div class="form-group col-lg-3" id="bname" >
					  <label for="usr" style="display: block;"> <?php echo getLange('logoimage'); ?> :</label>
					  <input type="file" name="logo" id="logo">
					   <div id="msg"></div>
				</div>
				<div class="form-group col-lg-3" id="bname" >
					  <label for="usr" style="display: block;"> <?php echo getLange('cnic').' '.getLange('copy'); ?> :</label>
					  <input type="file" name="cnic_copy"  id="image">
					  <div class="msg"></div>
				</div>
				<div class="form-group col-lg-3" id="bname" >
					  <label for="usr" style="display: block;"> <?php echo getLange('enablebookingform'); ?> :</label>
					  <input type="checkbox" name="is_booking_manual" checked value="1">
				</div>
		  </div>
	   </div>
	</div>
</div>



<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('contactinformation'); ?> </div>
	<div class="panel-body">
		<div class="row">
			<div class="form-group col-lg-4"  >
			  <label for="usr"><span style="color: red;">*</span> <?php echo getLange('name'); ?> #:</label>
			  <input type="text" class="form-control"  name="fname" required>
			</div>
			<!-- <div class="form-group col-lg-4"  >
			  <label for="usr"><span style="color: red;">*</span> <?php echo getLange('mobile'); ?> #:</label>
			  <input type="text" class="form-control"  name="mobile_no" required>
			</div> -->
			<div class="col-lg-4 " id="phone_code">
			    <label><span style="color: red;">*</span><?php echo getLange('mobile'); ?>#</label>
			    <input id="phone" type="tel">
			    <span class="default_number">+92</span>
			    <img class="flag_default" src="img/pkr-flag.jpg">
			    <span id="valid-msg" class="hide">Valid</span>
			    <span id="error-msg" class="hide">Invalid number</span>
			    <input type="text" id="phoneno" placeholder="Phone Number" name="mobile_no" required>
			</div>
			<div class="form-group col-lg-4">
			  <label for="usr"><span style="color: red;">*</span><?php echo getLange('email'); ?> :</label>
			  <input type="email" class="form-control emaill"  name="email" required>
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4" style="display: none;">
			  <label for="usr">Devision :</label>
			  <select class="form-control state select2"  name="state_id">
			  	<option value="">Select Devision</option>
			  	<?php $state=mysqli_query($con,"SELECT * FROM state ORDER BY id DESC");
			  		while ($row=mysqli_fetch_array($state)) {
			  	 ?>
			  	 <option value="<?php echo $row['id']; ?>"><?php echo $row['state_name']; ?></option>
			  	<?php } ?>
			  </select>
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4">
			  <label for="usr">Region :</label>
			  <div class="get_city">
			  <select class="form-control cities select2"  name="city">
			  	<option value="">Select Region</option>
			  	<?php $state=mysqli_query($con,"SELECT * FROM cities ORDER BY id DESC");
			  		while ($row=mysqli_fetch_array($state)) {
			  	 ?>
			  	 <option value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?></option>
			  	<?php } ?>
			  </select>
			  </div>
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4">
			  <label for="usr">Area :</label>
			   <input type="text" class="form-control stn_code_of_city" readonly>
			  <!-- <select class="form-control select2">
			  	<option value="">Select Region</option>
			  	<?php $state=mysqli_query($con,"SELECT * FROM areas ORDER BY id DESC");
			  		while ($row=mysqli_fetch_array($state)) {
			  	 ?>
			  	 <option value="<?php echo $row['id']; ?>"><?php echo $row['area_name']; ?></option>
			  	<?php } ?>
			  </select> -->
			  </div>
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4"   style="display: none;">
			  <label for="usr">Parent Code #:</label>
			  <input type="text" class="form-control"  name="parent_code">
			</div>
			<div class="form-group col-lg-4"  >
			  <label for="usr">Contact Person #:</label>
			  <input type="text" class="form-control"  name="contact_person">
			</div>
			<div class="form-group col-lg-4"   style="display: none;">
			  <label for="usr">Designation #:</label>
			  <input type="text" class="form-control"  name="designation">
			</div>	
			<div class="form-group col-lg-4"  style="display: none;" >
			  <label for="usr">Industry Code #:</label>
			  <input type="text" class="form-control"  name="industry_code">
			</div>
			<div class="form-group col-lg-4"  style="display: none;" >
			  <label for="usr">GST #:</label>
			  <input type="text" class="form-control"  name="gst">
			</div>
			<div class="form-group col-lg-4"  style="display: none;" >
			  <label for="usr">FAX #:</label>
			  <input type="text" class="form-control"  name="fax">
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('shipmentdetail'); ?> </div>
	<div class="panel-body">
		<div class="row">
			<div class="form-group col-lg-4"  >
			  <label for="usr"><?php echo getLange('websiteurl'); ?> :</label>
			  <input type="text" class="form-control"  name="website_url" >
			</div>
			<div class="form-group col-lg-4"  >
			  <label for="usr"> <?php echo getLange('producttype'); ?> #:</label>
			  <select  class="form-control select2" name="product_type">
			  	<?php $state=mysqli_query($con,"SELECT * FROM products ORDER BY id DESC");
			  		while ($row=mysqli_fetch_array($state)) {
			  	 ?>
			  	 <option ><?php echo $row['name']; ?></option>
			  	<?php } ?>
				</select>
			</div>
			<div class="form-group col-lg-4">
			  <label for="usr"><?php echo getLange('expectedaverageshipmentmonth'); ?> :</label>
			  <input type="text" class="form-control"  name="expected_shipment">
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4" style="display: none;">
			  <label for="usr">Validity :</label>
			  <input type="text" class="form-control datetimepicker4"  name="validity" value="<?php echo date('Y-m-d'); ?>">
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4" style="display: none;">
			  <label for="usr">Billing Instruction :</label>
			  <input type="text" class="form-control"  name="billing_instruction">
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4" style="display: none;">
			  <label for="usr">Handling Charges :</label>
			  <input type="text" class="form-control"  name="handling_charges">
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4" style="display: none;">
			  <label for="usr">Yearly Tariff Increase :</label>
			  <input type="text" class="form-control"  name="tariff_increase">
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4"  >
			  <label for="usr"> Settlment Period #:</label>
			  <select  class="form-control select2" name="payment_within">
			  	<?php $settle_period_q=mysqli_query($con,"SELECT * FROM settlement_period");
			  	while($row=mysqli_fetch_array($settle_period_q)){
			  	 ?>
			  	 <option><?php echo $row['no_of_day']; ?></option>
			  <?php } ?>
			  </select>
			</div>
			<div class="form-group col-lg-4" style="display: none;">
			  <label for="usr">Monthly Revenue :</label>
			  <input type="text" class="form-control"  name="monthly_revenue">
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4" style="display: none;">
			  <label for="usr">Flexible Fule Formula :</label>
			  <input type="text" class="form-control"  name="fuel_formula">
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4" style="display: none;">
			  <label for="usr">Other Charges :</label>
			  <input type="text" class="form-control"  name="other_charges">
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4" style="display: none;">
			  <label for="usr"><span style="color: red;">*</span>Special Instructios :</label>
			  <input type="text" class="form-control"  name="special_instruction">
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4" style="display: none;">
			  <label for="usr"><span style="color: red;">*</span>Frequent Destinations :</label>
			  <input type="text" class="form-control"  name="frequent_destination">
				<div class="help-block with-errors email_errorr"></div>
			</div>
			<div class="form-group col-lg-4"  style="display: none;" >
			  <label for="usr"> Zone #:</label>
			  <select  class="form-control select2" name="zone_type">
			  	<option value="">Select</option>
			  	<?php $zone=mysqli_query($con,"SELECT * FROM zone_type ORDER BY id DESC");
			  		while ($row=mysqli_fetch_array($zone)) {
			  	 ?>
			  	 <option value="<?php echo $row['id']; ?>"><?php echo $row['zone_name']; ?></option>
			  	<?php } ?>
				</select>
			</div>
		</div>
	</div>
</div>
<div class="panel panel-default" style="display: none;">
	<div class="panel-heading">Contact Person </div>
	<div class="panel-body">
		<div class="row" style="margin-left: 0px; margin-right: 0px;">
			<div class="form-group col-lg-4" >
			  <label for="usr">BDM/KAM :</label>
			  <input type="text" class="form-control"  name="bdm_kam" >
			</div>
		    <div class="form-group col-lg-4"  >
			  <label for="usr">Territory Code  :</label>
			  <input type="text" class="form-control"  name="territory_code" >
			</div>
			<div class="form-group col-lg-4">
			  <label for="usr">Collector  :</label>
			  <input type="text" class="form-control"  name="collector" >
			</div>
			<div class="form-group col-lg-4"  >
			  <label for="usr">Collection id  :</label>
			  <input type="text" class="form-control"  name="collection_id" >
			</div>
			<div class="form-group col-lg-4"  >
			  <label for="usr">CHS/ABH:</label>
			  <input type="text" class="form-control"  name="chs_abh" >
			</div>
			<div class="form-group col-lg-4"  >
			  <label for="usr"> MR/AMR:</label>
			  <input type="text" class="form-control"  name="mr_amr" >
			</div>
			<div class="form-group col-lg-6"  >
			  <label for="usr"> Sm :</label>
			  <input type="text" class="form-control"  name="sm" >
			</div>
		</div>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('bankdetail'); ?> </div>
	<div class="panel-body">
		<div class="row" style="margin-left: 0px; margin-right: 0px;">

					<div class="form-group col-lg-4" id="bname" >
					  <label for="usr"><?php echo getLange('bankname'); ?>  :</label>
					  <select type="text" class="form-control select2" name="bank_name">
                        <option value="" selected disabled>Select Bank Name</option>
                        <?php 
                        $bank_query=mysqli_query($con,"SELECT * FROM bank_detail ORDER By id Desc");
                    	while ($row=mysqli_fetch_array($bank_query)) {?>
                       	 <option value="<?php echo $row['id']; ?>"><?php echo $row['bank_name']; ?></option>
                        <?php } ?>
	                  </select>
					</div>
				    <div class="form-group col-lg-4" id="acc_title" >
					  <label for="usr"><?php echo getLange('accountitle'); ?>  :</label>
					  <input type="text" class="form-control"  name="acc_title" >
					</div>
					<div class="form-group col-lg-4" id="bank_ac_no" >
					  <label for="usr"><?php echo getLange('accountno'); ?>  :</label>
					  <input type="text" class="form-control"  name="bank_ac_no" >
					</div>
					<div class="form-group col-lg-4" id="branch_name" >
					  <label for="usr"><?php echo getLange('branchname'); ?>  :</label>
					  <input type="text" class="form-control"  name="branch_name" >
					</div>
					<div class="form-group col-lg-4" id="branch_code" >
					  <label for="usr"><?php echo getLange('branchcode'); ?>  :</label>
					  <input type="text" class="form-control"  name="branch_code" >
					</div>
					<div class="form-group col-lg-4" id="swift_code" >
					  <label for="usr"> <?php echo getLange('swiftcode'); ?> :</label>
					  <input type="text" class="form-control"  name="swift_code" >
					</div>
					<div class="form-group col-lg-6"  >
					  <label for="usr"> <?php echo getLange('ntn'); ?>  :</label>
					  <input type="text" class="form-control"  name="ntn_no" >
					</div>
					<div class="form-group col-lg-6"  >
					  <label for="usr"> <?php echo getLange('stn'); ?> :</label>
					  <input type="text" class="form-control"  name="stn_no" >
					</div>
					<div class="form-group col-lg-6" id="iban"  style="display: none;">
					  <label for="usr"> Logitics:</label>
					  <input type="text" class="form-control"  name="logistics" >
					</div>
					<div class="form-group col-lg-6" id="iban"  style="display: none;">
					  <label for="usr"> Express:</label>
					  <input type="text" class="form-control"  name="express" >
					</div>
					<div class="form-group col-lg-12" id="iban" >
					  <label for="usr"> <?php echo getLange('iban'); ?> :</label>
					  <input type="text" class="form-control"  name="iban_no" >
					</div>
					</div>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('password'); ?></div>
	<div class="panel-body">
		<div class="row">
			<div class="form-group col-lg-4">
			  <label for="pwd"><span style="color: red;">*</span> <?php echo getLange('password'); ?>:</label>
			  <input type="password" class="form-control" id="passwordboot" name="password" required>
			</div>
			<div class="form-group col-lg-4">
			  <label for="pwd"><span style="color: red;">*</span><?php echo getLange('comfirmpassword'); ?>  :</label>
			  <input type="password" name="repassword" class="form-control" data-match="#passwordboot" required>
			  <div class="help-block with-errors"></div>
			</div>
		</div>
	</div>
</div>
<input type="submit" name="submit" class="btn btn-info register_btn" value="<?php echo getLange('save').' & '.getLange('print'); ?>" style="font-size: 16px;letter-spacing: 1.5px;background-color:#0950a1; margin-top: 18px;text-transform: capitalize;">
</form>

	<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function() {
		 $(document).ready( function (){
	$("#logo").change(function () {
    var validExtensions = ["jpg","jpeg","gif","png","JPG","JPEG","GIF","PNG"]
    var file = $(this).val().split('.').pop();

    if (validExtensions.indexOf(file) == -1) {
        var msg=("Only formats are allowed : "+validExtensions.join(', '));
        $('#msg').html('');
        $('#msg').html(msg);
        $(this).val("");
    }
    else{
    	 $('#msg').html('');
    }
});
	$("#image").change(function () {
    var validExtensions = ["jpg","jpeg","png","JPG","JPEG","PNG"]
    var file = $(this).val().split('.').pop();
    if (validExtensions.indexOf(file) == -1) {
        var msg=("Only formats are allowed : "+validExtensions.join(', '));
        $('.msg').html('');
        $('.msg').html(msg);
        $(this).val("");
    }
    else{
    	 $('.msg').html('');
    }

});
});
}, false);

</script>
	
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
	$('.select2').select2();
$('body').on('change','.state',function(e){
    e.preventDefault();
    var state_id=$(this).val();
          $.ajax({
          type:'POST',
          data:{state_id_bissiness:state_id,bussiness_city:1},
          url:'ajax.php',
          success:function(response){
          $('.get_city').html('');
          $('.get_city').html(response);
          $('.js-example-basic-single').select2();
          }
          });
     })
$(document).on("change",".cities",function(e){
    e.preventDefault();
    var stn_code=$(this).find(':selected').attr('data-stn_city');
    $('.stn_code_of_city').val(stn_code);
     })
}, false);
</script>
<?php if (isset($_SESSION['print_url']) && $_SESSION['print_url']!='') { 
	$url=BASE_URL.$_SESSION['print_url'];
	?>
<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function() {
	var	url='<?php echo $url; ?>';
    window.open(url, '_blank');
}, false);
</script>
<?php 
unset($_SESSION['print_url']);
} ?>
<script type="text/javascript">
    var telInput = $("#phone"),
  errorMsg = $("#error-msg"),
  validMsg = $("#valid-msg");

// initialise plugin
telInput.intlTelInput({

  allowExtensions: true,
  formatOnDisplay: true,
  autoFormat: true,
  autoHideDialCode: true,
  autoPlaceholder: true,
  defaultCountry: "auto",
  ipinfoToken: "yolo",

  nationalMode: false,
  numberType: "MOBILE",
  //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
  preferredCountries: ['sa', 'ae', 'qa','om','bh','kw','ma'],
  preventInvalidNumbers: true,
  separateDialCode: true,
  initialCountry: "auto",
  geoIpLookup: function(callback) {
  $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
    var countryCode = (resp && resp.country) ? resp.country : "";
    callback(countryCode);
  });
},
   utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
});

var reset = function() {
  telInput.removeClass("error");
  errorMsg.addClass("hide");
  validMsg.addClass("hide");
};

// on blur: validate
telInput.blur(function() {
  reset();
  if ($.trim(telInput.val())) {
    if (telInput.intlTelInput("isValidNumber")) {
      validMsg.removeClass("hide");
    } else {
      telInput.addClass("error");
      errorMsg.removeClass("hide");
    }
  }
});

// on keyup / change flag: reset
telInput.on("keyup change", reset);
var telInput = $("#phone"),
  errorMsg = $("#error-msg"),
  validMsg = $("#valid-msg");

// initialise plugin
telInput.intlTelInput({

  allowExtensions: true,
  formatOnDisplay: true,
  autoFormat: true,
  autoHideDialCode: true,
  autoPlaceholder: true,
  defaultCountry: "auto",
  ipinfoToken: "yolo",

  nationalMode: false,
  numberType: "MOBILE",
  //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
  preferredCountries: ['sa', 'ae', 'qa','om','bh','kw','ma'],
  preventInvalidNumbers: true,
  separateDialCode: true,
  initialCountry: "auto",
  geoIpLookup: function(callback) {
  $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
    var countryCode = (resp && resp.country) ? resp.country : "";
    callback(countryCode);

  });
},
   utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
});

var reset = function() {
  telInput.removeClass("error");
  errorMsg.addClass("hide");
  validMsg.addClass("hide");
};

// on blur: validate
telInput.blur(function() {
  reset();
  if ($.trim(telInput.val())) {
    if (telInput.intlTelInput("isValidNumber")) {
      validMsg.removeClass("hide");
    } else {
      telInput.addClass("error");
      errorMsg.removeClass("hide");
    }
  }
});

// on keyup / change flag: reset
telInput.on("keyup change", reset);
</script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var country_code=$('.default_number').html();
    country_code=country_code.replace('+', '');
    $('#phoneno').val('00'+country_code);
$('body').on('click keyup','.country',function(e){
        var country_code=$(this).attr('data-dial-code');
        $('#phoneno').val('00'+country_code);
     });
// $(document).on('change','#phone',function(){
//        var country_code=$(this).attr('data-dial-code');
//         $('#phoneno').val('00'+country_code);
// })

}, false);
</script>