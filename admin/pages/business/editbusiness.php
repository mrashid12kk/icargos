<style type="text/css">
#phone_code .flag-container {
    margin: 0 0 0 -43px;
}
</style><?php
$cities = mysqli_query($con,"SELECT * FROM cities WHERE 1 ");
$all_salemans = mysqli_query($con, "SELECT * FROM users where type='admin' and user_role_id !=4");
$cities_from = mysqli_query($con,"SELECT * FROM cities WHERE 1 ORDER BY city_name ");
$cities_to = mysqli_query($con,"SELECT * FROM cities WHERE 1  order by ORDER BY city_name ");
$all_branches = mysqli_query($con,"SELECT * FROM branches");
if (isset($_GET['customer_id'])) {
	$customer_q=mysqli_query($con,"SELECT * FROM customers WHERE id=".$_GET['customer_id']);
	$edit=mysqli_fetch_assoc($customer_q);
}
?>
<form autocomplete="off" method="POST" action="pages/business/update_business.php" enctype="multipart/form-data">
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
        

$fuel_sur_value = mysqli_fetch_array(mysqli_query($con, "SELECT charge_value FROM customer_wise_charges WHERE customer_id = " . $_GET['customer_id'] . " AND charge_name = 'fuel_surcharge' "));
$fuel_charge_value = isset($fuel_sur_value['charge_value']) ? $fuel_sur_value['charge_value'] : 0;
		 ?>
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo getLange('businessaccountinformation'); ?> </div>
        <div class="panel-body">


            <div class="col-lg-12 customer_gapp">

                <div class="row" style="margin-left: 0px; margin-right: 0px;">
                    <div class="form-group col-lg-2" id="bname">
                        <label for="usr"><span style="color: red;">*</span><?php echo getLange('businessname'); ?>
                            :</label>
                        <input type="text" class="form-control" name="bname"
                            value="<?php echo isset($edit) ? $edit['bname'] : ''; ?>" required>
                    </div>
                    <div class="form-group col-lg-2" id="bname">
                        <label for="usr"><span
                                style="color: red;">*</span><?php echo getLange('customer').' '.  getLange('type'); ?>
                            :</label>
                            <?php
                          $acount_type=mysqli_query($con,"SELECT * FROM pay_mode where id= '".$edit['customer_type']."'");
                            $fetch = mysqli_fetch_array($acount_type);
                                $sql = "SELECT * FROM `tbl_accountledger` WHERE `customer_id` = '".$_GET['customer_id']."'";
                                $query = mysqli_fetch_array(mysqli_query($con, $sql));
                                
                                $sql1 = "SELECT * FROM `tbl_ledgerposting` where ledgerId = '".$query['id']."'" ;
                                $q = mysqli_query($con, $sql1);
                                if(mysqli_num_rows($q) > 0){
                                ?>
                                <input type="text" name="customer_type" class="form-control" value="    <?php echo $fetch['pay_mode']; ?>" readonly>
                                <?php
                                }else{
                                // die();
                            ?>
                        <select type="text" class="form-control select2" name="customer_type" required  id="cust_type">
                            <?php 
                            $acount_type_q=mysqli_query($con,"SELECT * FROM pay_mode ORDER BY id DESC");
					  	while($account_type=mysqli_fetch_array($acount_type_q)){ ?>
                            <option value="<?php echo $account_type['id']; ?>"
                                <?php echo isset($edit) && $edit['customer_type']==$account_type['id'] ? 'selected' : ''; ?>>
                                <?php echo $account_type['pay_mode']; ?></option>
                            <?php } ?>
                        </select>
                        <?php
                            }
                        ?>
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
                        <label for="usr"> <?php echo getLange('cnic'); ?></label>
                        <input type="text" class="form-control cnic" name="cnic"
                            value="<?php echo isset($edit) ? $edit['cnic'] : ''; ?>" style="padding-top: 5px;">
                    </div>
                    <div class="form-group col-lg-3" id="bname">
                        <label for="usr"><span
                                style="color: red;">*</span><?php echo getLange('defaultbranch'); ?>:</label>
                        <select class="form-control select2" name="branch_id">
                            <?php while($row = mysqli_fetch_array($all_branches)){ ?>
                            <option value="<?php echo $row['id'] ?>"
                                <?php echo isset($edit) && $edit['branch_id']==$row['id'] ? 'selected' : ''; ?>>
                                <?php echo $row['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <label for="usr"><span style="color: red;">*</span>Sales Representative:</label>
                        <select class="form-control select2" name="sale_man_id">
                            <?php while ($row = mysqli_fetch_array($all_salemans)) { ?>
                                <option value="<?php echo $row['id'] ?>" <?php echo isset($edit) && $edit['sale_man_id'] == $row['id'] ? 'selected' : ''; ?>>
                                    <?php echo $row['Name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-12" id="bname">
                        <label for="usr"><span style="color: red;">*</span> Office Address :</label>
                        <textarea name="address" style="height: 52px;" type="text" class="form-control"
                            required><?php echo isset($edit) ? $edit['address'] : ''; ?></textarea>
                    </div>
                    <!-- <div class="form-group col-lg-12" id="bname">
                        <label for="usr">Pickup Address :</label>
                        <textarea name="billing_address" style="height: 52px;" type="text"
                            class="form-control"><?php echo isset($edit) ? $edit['billing_address'] : ''; ?></textarea>
                    </div> -->
                    <div class="row">
                        <div class="col-sm-12 padd_left" style="padding-right:0;">
                            <div class="form-group">
                                <label for="usr">Pickup Address :</label>


                                <textarea id="property_add" name="billing_address" style="height: 52px;" type="text"
                                    class="form-control"><?php echo isset($edit) ? $edit['billing_address'] : ''; ?></textarea>


                                <input type="hidden" name="google_address" id="google_address">
                                <input type="hidden" class="form-control"
                                    value="<?php echo isset($edit) ? $edit['customer_latitude'] : ''; ?>" id="latitude"
                                    name="customer_latitude">
                                <input type="hidden" class="form-control"
                                    value="<?php echo isset($edit) ? $edit['customer_longitude'] : ''; ?>"
                                    id="longitude" name="customer_longitude">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mapping" id="mapping"
                                            style="width: 100%; height: 173px;margin-bottom: 10px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3" id="bname">

                        <?php if (isset($edit) && $edit['image']!=''){ ?>
                        <img src="<?php echo BASE_URL . '' . $edit['image']; ?>" style="width: 100px; height: 73px">
                        <?php } ?>
                        <label for="usr" style="display: block;"> <?php echo getLange('logoimage'); ?> :</label>
                        <input type="file" name="logo" id="logo">
                        <div id="msg"></div>
                    </div>
                    <div class="form-group col-lg-3" id="bname">
                        <?php if (isset($edit) && $edit['cnic_copy']!=''){ ?>
                        <img src="<?php echo BASE_URL . '' . $edit['cnic_copy']; ?>" style="width: 100px; height: 73px">
                        <?php } ?>
                        <label for="usr" style="display: block;"> <?php echo getLange('cnic').' '.getLange('copy'); ?>
                            :</label>
                        <input type="file" name="cnic_copy" id="image">
                        <div class="msg"></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Checkbox </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-lg-4" id="bname">
                    <label for="usr" style="display: block;"> <?php echo getLange('enablebookingform'); ?> :</label>
                    <input type="checkbox" name="is_booking_manual"
                        <?php echo isset($edit) && $edit['is_booking_manual']==1 ? 'checked' : ''; ?> value="1">
                </div>
                <div class="form-group col-lg-4" id="bname">
                    <label for="usr" style="display: block;"> <?php echo getLange('fuelsurcharge'); ?> :</label>
                    <div class="row">
                        <div class="col-lg-2">
                             <input type="checkbox" <?php echo (isset($edit['is_fuelsurcharge']) && $edit['is_fuelsurcharge'] == 1) ? 'checked' : ''; ?> name="is_fuelsurcharge" value="1">
                        </div>
                        <div class="col-lg-10">
                             <input type="text" name="fuel_charge_val" value="<?php echo isset($fuel_charge_value) ? $fuel_charge_value : 0; ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-2" id="bname">
                    <label for="usr" style="display: block;"> <?php echo getLange('salestax'); ?> :</label>
                    <input type="checkbox" <?php echo (isset($edit['is_saletax']) && $edit['is_saletax'] == 1) ? 'checked' : ''; ?> name="is_saletax" value="1">
                </div>
                 <div class="form-group col-lg-2" id="bname">
                    <label for="usr" style="display: block;"> <?php echo getLange('Account Status'); ?> :</label>
                    <select class="form-control" name="active_status">
                        <option value="1" <?php if (isset($edit['status']) &&  $edit['status']== 1) {
                                                        echo "selected";
                                                    } ?>>Active</option>
                        <option value="0" <?php if (isset($edit['status']) &&  $edit['status']== 0) {
                                                        echo "selected";
                                                    } ?>>Pending</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4" >
                    <label for="usr" style="display: block;"> <?php echo getLange('wave_off_return_delivery_fee'); ?> :</label>
                    <input type="checkbox" <?php echo (isset($edit['wave_off_return_delivery_fee']) && $edit['wave_off_return_delivery_fee'] == 1) ? 'checked' : ''; ?> name="wave_off_return_delivery_fee" value="1" />
                </div>
                <div class="form-group col-lg-4" >
                    <label for="usr" style="display: block;"> Multi User:</label>
                    <input type="checkbox" <?php echo (isset($edit['multi_user']) && $edit['multi_user'] == 1) ? 'checked' : ''; ?> name="multi_user" value="1" />
                </div>
                <div class="form-group col-lg-4" >
                    <label for="usr" style="display: block;"> <?php echo getLange('return_fee_per_parcel'); ?> :</label>
                    <div class="row">
                        <div class="col-lg-2">
                             <input type="checkbox" <?php echo (isset($edit['is_return_fee_per_parcel']) && $edit['is_return_fee_per_parcel'] == 1) ? 'checked' : ''; ?> name="is_return_fee_per_parcel" value="1">
                        </div>
                        <div class="col-lg-10">
                             <input type="text" name="return_fee_per_parcel" value="<?php echo isset($edit['return_fee_per_parcel']) ? $edit['return_fee_per_parcel'] : 0; ?>" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4" >
                    <label for="usr" style="display: block;"> <?php echo getLange('manuallyorder'); ?> :</label>
                    <input type="checkbox" name="is_order_manual" <?php if ($edit['is_order_manual'] == 1) : echo "checked"; endif ?> value=1/>
                </div>
                <div class="form-group col-lg-4" >
                    <label for="usr" style="display: block;"> Enable Merchant:</label>
                    <input type="checkbox" <?php echo (isset($edit['is_merchant']) && $edit['is_merchant'] == 1) ? 'checked':''; ?> name="is_merchant" value=1/>
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr" style="display: block;"> <?php echo getLange('api') . ' ' . getLange('status'); ?>:</label>
                   <select class="form-control" name="api_status">
                        <option value="1" <?php if (isset($edit['api_status']) &&  $edit['api_status']== 1) {
                                                        echo "selected";
                                                    } ?>>Enable</option>
                        <option value="0" <?php if (isset($edit['api_status']) &&  $edit['api_status']== 0) {
                                                        echo "selected";
                                                    } ?>>Disable</option>
                    </select>
                </div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading"><?php echo getLange('contactinformation'); ?> </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label for="usr"><span style="color: red;">*</span> <?php echo getLange('name'); ?> #:</label>
                    <input type="text" class="form-control" name="fname"
                        value="<?php echo isset($edit) ? $edit['fname'] : ''; ?>" required>
                </div>
                <!-- <div class="form-group col-lg-4"  >
			  <label for="usr"><span style="color: red;">*</span> <?php echo getLange('mobile'); ?> #:</label>
			  <input type="text" class="form-control"  name="mobile_no" value="<?php echo isset($edit) ? $edit['mobile_no'] : ''; ?>" required>
			</div> -->
                <div class="col-lg-4 " id="phone_code">
                    <label><span style="color: red;">*</span><?php echo getLange('phone'); ?></label>
                    <input id="phone" type="tel">
                    <span class="default_number">+92</span>
                    <img class="flag_default" src="img/pkr-flag.jpg">
                    <span id="valid-msg" class="hide">Valid</span>
                    <span id="error-msg" class="hide">Invalid number</span>
                    <input type="text" id="phoneno" placeholder="Phone Number" name="mobile_no"
                        value="<?php echo isset($edit) ? $edit['mobile_no'] : ''; ?>" required>
                    <div class="r_phone_msg"></div>
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr"><span style="color: red;">*</span><?php echo getLange('email'); ?> :</label>
                    <input type="email" class="form-control emaill" name="email"
                        value="<?php echo isset($edit) ? $edit['email'] : ''; ?>" required>
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4" style="display: none;">
                    <label for="usr">Devision :</label>
                    <select class="form-control state select2" name="state_id">
                        <option value="">Select Devision</option>
                        <?php $state=mysqli_query($con,"SELECT * FROM state ORDER BY id DESC");
			  		while ($row=mysqli_fetch_array($state)) {
			  	 ?>
                        <option value="<?php echo $row['id']; ?>"
                            <?php echo isset($edit) && $edit['state_id']==$row['id'] ? 'selected' : ''; ?>>
                            <?php echo $row['state_name']; ?></option>
                        <?php } ?>
                    </select>
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr">Region :</label>
                    <div class="get_city">
                        <select class="form-control cities select2" name="city">
                            <option value="" selected>Select Region</option>
                            <?php $state=mysqli_query($con,"SELECT * FROM cities ORDER BY id DESC");
			  		while ($row=mysqli_fetch_array($state)) {
			  	 ?>
                            <option data-stn_city="<?php echo $row['stn_code'] ?>"
                                value="<?php echo $row['city_name']; ?>"
                                <?php echo isset($edit) && $edit['city']==$row['city_name'] ? 'selected' : ''; ?>>
                                <?php echo $row['city_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr">Area :</label>
                    <input type="text" class="form-control stn_code_of_city" readonly>
                    <!-- <select class="form-control select2" required>
			  	<option value="">Select Region</option>
			  	<?php $state=mysqli_query($con,"SELECT * FROM areas ORDER BY id DESC");
			  		while ($row=mysqli_fetch_array($state)) {
			  	 ?>
			  	 <option value="<?php echo $row['id']; ?>"><?php echo $row['area_name']; ?></option>
			  	<?php } ?>
			  </select> -->
                </div>
                 <div class="form-group col-lg-4">
                    <label for="usr">Tariff Type:</label>
                   <select class="form-control" name="tariff_type">
                       <option value="default" <?php echo  isset($edit) && $edit['tariff_type'] == 'default' ?'selected':'';?>>Default</option>
                       <option value="custom"  <?php echo  isset($edit) && $edit['tariff_type'] == 'custom' ?'selected':'';?>>Custom</option>
                   </select>
                </div>
                <div class="help-block with-errors email_errorr"></div>
            </div>
            <div class="form-group col-lg-4" style="display: none;">
                <label for="usr">Parent Code #:</label>
                <input type="text" class="form-control" name="parent_code"
                    value="<?php echo isset($edit) ? $edit['parent_code'] : ''; ?>">
            </div>
            <div class="form-group col-lg-4">
                <label for="usr">Contact Person #:</label>
                <input type="text" class="form-control" name="contact_person"
                    value="<?php echo isset($edit) ? $edit['contact_person'] : ''; ?>">
            </div>
            <div class="form-group col-lg-4" style="display: none;">
                <label for="usr">Designation #:</label>
                <input type="text" class="form-control" name="designation"
                    value="<?php echo isset($edit) ? $edit['designation'] : ''; ?>">
            </div>
            <div class="form-group col-lg-4" style="display: none;">
                <label for="usr">Industry Code #:</label>
                <input type="text" class="form-control" name="industry_code"
                    value="<?php echo isset($edit) ? $edit['industry_code'] : ''; ?>">
            </div>
            <div class="form-group col-lg-4" style="display: none;">
                <label for="usr">GST #:</label>
                <input type="text" class="form-control" name="gst"
                    value="<?php echo isset($edit) ? $edit['gst'] : ''; ?>">
            </div>
            <div class="form-group col-lg-4" style="display: none;">
                <label for="usr">FAX #:</label>
                <input type="text" class="form-control" name="fax"
                    value="<?php echo isset($edit) ? $edit['fax'] : ''; ?>">
            </div>
        </div>
    </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><?php echo getLange('shipmentdetail'); ?> </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label for="usr"><?php echo getLange('websiteurl'); ?> :</label>
                    <input type="text" class="form-control" name="website_url"
                        value="<?php echo isset($edit) ? $edit['website_url'] : ''; ?>">
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr"> <?php echo getLange('producttype'); ?> #:</label>
                    <select class="form-control select2" name="product_type">
                        <?php $state=mysqli_query($con,"SELECT * FROM products ORDER BY id DESC");
			  		while ($row=mysqli_fetch_array($state)) {
			  	 ?>
                        <option <?php echo isset($edit) && $edit['product_type']==$row['name'] ? 'selected' : ''; ?>>
                            <?php echo $row['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr"><?php echo getLange('expectedaverageshipmentmonth'); ?> :</label>
                    <input type="text" class="form-control" name="expected_shipment"
                        value="<?php echo isset($edit) ? $edit['expected_shipment'] : ''; ?>">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4" style="display: none;">
                    <label for="usr">Validity :</label>
                    <input type="text" class="form-control datetimepicker4" name="validity"
                        value="<?php echo isset($edit) ? $edit['validity'] : date('d-m-Y'); ?>">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4" style="display: none;">
                    <label for="usr">Billing Instruction :</label>
                    <input type="text" class="form-control" name="billing_instruction"
                        value="<?php echo isset($edit) ? $edit['billing_instruction'] : ''; ?>">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4" style="display: none;">
                    <label for="usr">Handling Charges :</label>
                    <input type="text" class="form-control" name="handling_charges"
                        value="<?php echo isset($edit) ? $edit['handling_charges'] : ''; ?>">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4" style="display: none;">
                    <label for="usr">Yearly Tariff Increase :</label>
                    <input type="text" class="form-control" name="tariff_increase"
                        value="<?php echo isset($edit) ? $edit['tariff_increase'] : ''; ?>">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr"> Settlment Period #:</label>
                    <select class="form-control select2" name="payment_within">
                        <?php $settle_period_q=mysqli_query($con,"SELECT * FROM settlement_period");
			  	while($row=mysqli_fetch_array($settle_period_q)){
			  	 ?>
                        <option
                            <?php echo isset($edit) && $edit['payment_within']==$row['no_of_day'] ? 'selected' : ''; ?>>
                            <?php echo $row['no_of_day']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-lg-4" style="display: none;">
                    <label for="usr">Monthly Revenue :</label>
                    <input type="text" class="form-control" name="monthly_revenue"
                        value="<?php echo isset($edit) ? $edit['monthly_revenue'] : ''; ?>">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4" style="display: none;">
                    <label for="usr">Flexible Fule Formula :</label>
                    <input type="text" class="form-control"
                        value="<?php echo isset($edit) ? $edit['fuel_formula'] : ''; ?>" name="fuel_formula">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4" style="display: none;">
                    <label for="usr">Other Charges :</label>
                    <input type="text" class="form-control" name="other_charges"
                        value="<?php echo isset($edit) ? $edit['other_charges'] : ''; ?>">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4" style="display: none;">
                    <label for="usr">Special Instructios :</label>
                    <input type="text" class="form-control" name="special_instruction"
                        value="<?php echo isset($edit) ? $edit['special_instruction'] : ''; ?>">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4" style="display: none;">
                    <label for="usr">Frequent Destinations :</label>
                    <input type="text" class="form-control" name="frequent_destination"
                        value="<?php echo isset($edit) ? $edit['frequent_destination'] : ''; ?>">
                    <div class="help-block with-errors email_errorr"></div>
                </div>
                <div class="form-group col-lg-4" style="display: none;">
                    <label for="usr"> Zone #:</label>
                    <select class="form-control select2" name="zone_type">
                        <option value="">Select</option>
                        <?php $zone=mysqli_query($con,"SELECT * FROM zone_type ORDER BY id DESC");
			  		while ($row=mysqli_fetch_array($zone)) {
			  	 ?>
                        <option value="<?php echo $row['id']; ?>"
                            <?php echo isset($edit['zone_type']) && $edit['zone_type']==$row['id'] ? 'selected' : '';  ?>>
                            <?php echo $row['zone_name']; ?></option>
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
                <div class="form-group col-lg-4">
                    <label for="usr">BDM/KAM :</label>
                    <input type="text" class="form-control" name="bdm_kam"
                        value="<?php echo isset($edit) ? $edit['bdm_kam'] : ''; ?>">
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr">Territory Code :</label>
                    <input type="text" class="form-control" name="territory_code"
                        value="<?php echo isset($edit) ? $edit['territory_code'] : ''; ?>">
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr">Collector :</label>
                    <input type="text" class="form-control" name="collector"
                        value="<?php echo isset($edit) ? $edit['collector'] : ''; ?>">
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr">Collection id :</label>
                    <input type="text" class="form-control" name="collection_id"
                        value="<?php echo isset($edit) ? $edit['collection_id'] : ''; ?>">
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr">CHS/ABH:</label>
                    <input type="text" class="form-control" name="chs_abh"
                        value="<?php echo isset($edit) ? $edit['chs_abh'] : ''; ?>">
                </div>
                <div class="form-group col-lg-4">
                    <label for="usr"> MR/AMR:</label>
                    <input type="text" class="form-control" name="mr_amr"
                        value="<?php echo isset($edit) ? $edit['mr_amr'] : ''; ?>">
                </div>
                <div class="form-group col-lg-6">
                    <label for="usr"> Sm :</label>
                    <input type="text" class="form-control" name="sm"
                        value="<?php echo isset($edit) ? $edit['sm'] : ''; ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo getLange('bankdetail'); ?> </div>
        <div class="panel-body">
            <div class="row" style="margin-left: 0px; margin-right: 0px;">

                <div class="form-group col-lg-4" id="bname">
                    <label for="usr"><?php echo getLange('bankname'); ?> :</label>
                    <select type="text" class="form-control select2" name="bank_name">
                        <option value="" selected disabled>Select Bank Name</option>
                        <?php $bank_query=mysqli_query($con,"SELECT * FROM bank_detail ORDER By id Desc");
                        while ($row=mysqli_fetch_array($bank_query)) {?>
                            <option value="<?php echo $row['id']; ?>" <?php echo isset($edit) && $edit['bank_name']==$row['id'] ? 'selected' : ''; ?>><?php echo $row['bank_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-lg-4" id="acc_title">
                    <label for="usr"><?php echo getLange('accountitle'); ?> :</label>
                    <input type="text" class="form-control" name="acc_title"
                        value="<?php echo isset($edit) ? $edit['acc_title'] : ''; ?>">
                </div>
                <div class="form-group col-lg-4" id="bank_ac_no">
                    <label for="usr"><?php echo getLange('accountno'); ?> :</label>
                    <input type="text" class="form-control" name="bank_ac_no"
                        value="<?php echo isset($edit) ? $edit['bank_ac_no'] : ''; ?>">
                </div>
                <div class="form-group col-lg-4" id="branch_name">
                    <label for="usr"><?php echo getLange('branchname'); ?> :</label>
                    <input type="text" class="form-control" name="branch_name"
                        value="<?php echo isset($edit) ? $edit['branch_name'] : ''; ?>">
                </div>
                <div class="form-group col-lg-4" id="branch_code">
                    <label for="usr"><?php echo getLange('branchcode'); ?> :</label>
                    <input type="text" class="form-control" name="branch_code"
                        value="<?php echo isset($edit) ? $edit['branch_code'] : ''; ?>">
                </div>
                <div class="form-group col-lg-4" id="swift_code">
                    <label for="usr"> <?php echo getLange('swiftcode'); ?> :</label>
                    <input type="text" class="form-control" name="swift_code"
                        value="<?php echo isset($edit) ? $edit['swift_code'] : ''; ?>">
                </div>
                <div class="form-group col-lg-6">
                    <label for="usr"> <?php echo getLange('ntn'); ?> :</label>
                    <input type="text" class="form-control" name="ntn_no"
                        value="<?php echo isset($edit) ? $edit['ntn_no'] : ''; ?>">
                </div>
                <div class="form-group col-lg-6">
                    <label for="usr"> <?php echo getLange('stn'); ?> :</label>
                    <input type="text" class="form-control" name="stn_no"
                        value="<?php echo isset($edit) ? $edit['stn_no'] : ''; ?>">
                </div>
                <div class="form-group col-lg-6" id="iban">
                    <label for="usr"> Logitics:</label>
                    <input type="text" class="form-control" name="logistics"
                        value="<?php echo isset($edit) ? $edit['logistics'] : ''; ?>">
                </div>
                <div class="form-group col-lg-6" id="iban">
                    <label for="usr"> Express:</label>
                    <input type="text" class="form-control" name="express"
                        value="<?php echo isset($edit) ? $edit['express'] : ''; ?>">
                </div>
                <div class="form-group col-lg-12" id="iban">
                    <label for="usr"> <?php echo getLange('iban'); ?> :</label>
                    <input type="text" class="form-control" name="iban_no"
                        value="<?php echo isset($edit) ? $edit['iban_no'] : ''; ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo getLange('password'); ?></div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label for="pwd"> <?php echo getLange('password'); ?>:</label>
                    <input type="password" class="form-control" id="passwordboot" name="password" autocomplete="off">
                </div>
                <div class="form-group col-lg-4">
                    <label for="pwd"><?php echo getLange('comfirmpassword'); ?> :</label>
                    <input type="password" name="repassword" class="form-control" data-match="#passwordboot"
                        autocomplete="off">
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="edit_customer_id" value="<?php echo $_GET['customer_id']; ?>">
    <input type="submit" name="submit" class="btn btn-info register_btn"
        value="<?php echo getLange('save').' & '.getLange('print'); ?>"
        style="font-size: 16px;letter-spacing: 1.5px;background-color:#0950a1; margin-top: 18px;text-transform: capitalize;">
</form>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    $(document).ready(function() {
        $("#logo").change(function() {
            var validExtensions = ["jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG"]
            var file = $(this).val().split('.').pop();

            if (validExtensions.indexOf(file) == -1) {
                var msg = ("Only formats are allowed : " + validExtensions.join(', '));
                $('#msg').html('');
                $('#msg').html(msg);
                $(this).val("");
            } else {
                $('#msg').html('');
            }
        });
        $("#image").change(function() {
            var validExtensions = ["jpg", "jpeg", "png", "JPG", "JPEG", "PNG"]
            var file = $(this).val().split('.').pop();
            if (validExtensions.indexOf(file) == -1) {
                var msg = ("Only formats are allowed : " + validExtensions.join(', '));
                $('.msg').html('');
                $('.msg').html(msg);
                $(this).val("");
            } else {
                $('.msg').html('');
            }

        });
    });
}, false);
</script>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    $('.select2').select2();
    var stn_code = $('.cities').find(':selected').attr('data-stn_city');
    $('.stn_code_of_city').val(stn_code);
    $('body').on('change', '.state', function(e) {
        e.preventDefault();
        var state_id = $(this).val();
        $.ajax({
            type: 'POST',
            data: {
                state_id_bissiness: state_id,
                bussiness_city: 1
            },
            url: 'ajax.php',
            success: function(response) {
                $('.get_city').html('');
                $('.get_city').html(response);
                $('.js-example-basic-single').select2();
            }
        });
    })
    $(document).on("change", ".cities", function(e) {
        e.preventDefault();
        var stn_code = $(this).find(':selected').attr('data-stn_city');
        $('.stn_code_of_city').val(stn_code);
    })
}, false);
</script>
<?php if (isset($_SESSION['print_url']) && $_SESSION['print_url']!='') { 
	$url=BASE_URL.$_SESSION['print_url'];
	?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var url = '<?php echo $url; ?>';
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
    preferredCountries: ['sa', 'ae', 'qa', 'om', 'bh', 'kw', 'ma'],
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
    preferredCountries: ['sa', 'ae', 'qa', 'om', 'bh', 'kw', 'ma'],
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
    // var country_code=$('.default_number').html();
    // country_code=country_code.replace('+', '');
    // $('#phoneno').val('00'+country_code);
    $('body').on('click keyup', '.country', function(e) {
        var country_code = $(this).attr('data-dial-code');
        $('#phoneno').val('00' + country_code);
    });
    // $(document).on('change','#phone',function(){
    //        var country_code=$(this).attr('data-dial-code');
    //         $('#phoneno').val('00'+country_code);
    // })



}, false);
</script>

<script type="text/javascript">
const api_key = '<?php echo getConfig("api_key") ?>';
var placeSearch, autocomplete;
var componentForm = {
    // street_number: 'short_name',
    // route: 'long_name',
    // locality: 'long_name',
    // administrative_area_level_1: 'short_name',
    // country: 'long_name',
    // postal_code: 'short_name'
};
// starting Navigator

// navigator.geolocation.getCurrentPosition(function(position) {
//         getUserAddressBy(position.coords.latitude, position.coords.longitude);
//         latitude = position.coords.latitude;
//         longitude = position.coords.longitude;
//         // console.log("ere latitude is" + latitude)
//         // console.log(" er e longitude is" + longitude)
//         initialize();
//     },
//     function(error) {
//         console.log("The Locator was denied :(")
//     })
// var locatorSection = document.getElementById("location-input-section")

// function init() {
//     var locatorButton = document.getElementById("location-button");
//     locatorButton.addEventListener("click", locatorButtonPressed)
// }

// function locatorButtonPressed() {
//     locatorSection.classList.add("loading")

//     navigator.geolocation.getCurrentPosition(function(position) {
//             getUserAddressBy(position.coords.latitude, position.coords.longitude)
//             document.getElementById('latitude').value = position.coords.latitude;
//             document.getElementById('longitude').value = position.coords.longitude;
//         },
//         function(error) {
//             locatorSection.classList.remove("loading")
//             alert("The Locator was denied :( Please add your address manually")
//         })
// }

// function getUserAddressBy(lat, long) {

//     var xhttp = new XMLHttpRequest();
//     xhttp.onreadystatechange = function() {
//         if (this.readyState == 4 && this.status == 200) {
//             var address = JSON.parse(this.responseText)
//             document.getElementById('property_add').value = address.results[0].formatted_address;
//             document.getElementById('google_address').value = address.results[0].formatted_address;
//             // filladdress(address.results[0]);
//             document.getElementById('latitude').value = lat;
//             document.getElementById('longitude').value = long;

//         }
//     };
//     xhttp.open("GET", "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + long +
//         "&key=" + api_key + "", true);
//     xhttp.send();

// }
// Ending Navigator

var latitude = document.getElementById('latitude').value;
var longitude = document.getElementById('longitude').value;

// console.log("latitude is" + latitude)
// console.log("longitude is" + longitude)

function initialize() {

    var latlng = new google.maps.LatLng(latitude, longitude);
    var map = new google.maps.Map(document.getElementById('mapping'), {
        center: latlng,
        zoom: 14
    });
    var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        draggable: true,
        anchorPoint: new google.maps.Point(0, -29)
    });
    var input = document.getElementById('property_add');
    var geocoder = new google.maps.Geocoder();
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        bindDataToForm(place.formatted_address, place.geometry.location.lat(), place.geometry
            .location.lng());
        infowindow.setContent(place.formatted_address);
        infowindow.open(map, marker);
    });
    // this function will work on marker move event into map
    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({
            'latLng': marker.getPosition()
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    bindDataToForm(results[0].formatted_address, marker.getPosition().lat(),
                        marker
                        .getPosition().lng());
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                }
            }
        });
    });
}
// }, false);

function bindDataToForm(address, lat, lng) {
    document.getElementById('property_add').value = address;
    document.getElementById('google_address').value = address;
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}
</script>