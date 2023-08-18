<?php
		if(isset($_POST['updatecustomerpricing'])){
			$zone_id=$_GET['zone_id'];
			$zone=mysqli_real_escape_string($con,$_POST['zone']);
			$city=mysqli_real_escape_string($con,$_POST['city']);
			$point_5_kg=mysqli_real_escape_string($con,$_POST['point_5_kg']);
			$onekg = mysqli_real_escape_string($con,$_POST['onekg']);
			$other_kg=mysqli_real_escape_string($con,$_POST['other_kg']);
			$customer_id=mysqli_real_escape_string($con,$_POST['customer_id']);
			$zone_id = $_POST['zone_id'];
			$product_id = $_POST['product_id'];
			$get_query = mysqli_query($con,"SELECT * FROM customer_pricing WHERE zone_id ='".$zone_id."' AND customer_id='".$customer_id."' ");

			$rowcount = mysqli_num_rows($get_query);
			if($rowcount == 0){
			//insert
			mysqli_query($con,"INSERT INTO `customer_pricing` SET `point_5_kg`='".$point_5_kg."',`upto_1_kg`='".$onekg."',`other_kg`='".$other_kg."',`customer_id`='".$customer_id."',`zone_id`='".$zone_id."' ");
			}else{
				//update
				mysqli_query($con,"UPDATE `customer_pricing` SET `point_5_kg`='".$point_5_kg."',`upto_1_kg`='".$onekg."', `other_kg`='".$other_kg."' WHERE zone_id='".$zone_id."' AND customer_id='".$customer_id."' ");
			}
			$rowscount=mysqli_affected_rows($con);
			if($rowscount>0){
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated Pricing successfully</div>';
				echo "<script>document.location.href='customer_detail.php?customer_id=$customer_id';</script>";
			}
			else{
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a driver unsuccessfully.</div>';
				echo "<script>document.location.href='customer_detail.php?customer_id=$customer_id';</script>";
			}
		}
	if(isset($_GET['customer_id'])){

	$customer_id = $_GET['customer_id'];
	$get_query = mysqli_query($con,"SELECT * FROM customer_pricing WHERE customer_id='".$customer_id."' ");

	$rowcount = mysqli_num_rows($get_query);
	if($rowcount == 0){
		$get_query = mysqli_query($con,"SELECT * FROM zone WHERE id ='".$zone_id."' ");
		$get_query_fetch = mysqli_fetch_array($get_query);
	}else{
		$get_query_fetch = mysqli_fetch_array($get_query);
	}
	$zone = $get_query_fetch['zone'];
	$point_5_kg = $get_query_fetch['point_5_kg'];
	$onekg = $get_query_fetch['upto_1_kg'];
	$twokg = $get_query_fetch['upto_2_kg'];
	$other_kg = $get_query_fetch['other_kg'];
	$zone_id = $_GET['zone_id'];



	}else{
		header("Location:".$_SERVER['HTTP_REFERER']);
	}
	$customer_id = $_GET['customer_id'];
	$customer_query = mysqli_query($con, "SELECT * FROM customers WHERE id=" . $customer_id . " ");
        $customer_data = mysqli_fetch_array($customer_query);
        $customer_city = $customer_data['city'];
        $customer_type = $customer_data['customer_type'];
        $customerPaySql = "SELECT * FROM pay_mode WHERE account_type = '".$customer_type."'";
       
       $c_pay_mode_q = mysqli_query($con,$customerPaySql);
       $paymodeRes = mysqli_fetch_assoc($c_pay_mode_q);
       $customerPayMode = isset($paymodeRes['pay_mode']) ? $paymodeRes['pay_mode'] : '';
       $customerPayModeId = isset($paymodeRes['id']) ? $paymodeRes['id'] : '';
	// $tariff_q = mysqli_query($con,"SELECT * FROM tariff WHERE pay_mode = $customerPayModeId ");
	$products = mysqli_query($con,"SELECT * FROM products ORDER BY id DESC ");
	$c_tarif_sql = "SELECT * FROM customer_tariff_detail WHERE customer_id ='". $customer_id ."'";
	$tarifCust_query = mysqli_query($con,$c_tarif_sql);
                    $customer_tariff_ids = '';
                    while($custRes=mysqli_fetch_assoc($tarifCust_query)){
                        $customer_tariff_ids .=$custRes['tariff_id'].',';
                    }
                    $customer_tariff_ids = rtrim($customer_tariff_ids,',');
                    $tariff_q = mysqli_query($con,"SELECT * FROM tariff WHERE id NOT IN ($customer_tariff_ids) and pay_mode = $customerPayModeId");
                    
?>
<style type="text/css">
	.display_none{
		display: none;
	}
</style>
<div class="panel panel-default">
	<div class="panel-heading">Assign Tariff</div>
		<div class="panel-body" style="padding: 14px;">
			<form role="form" class=""  action="assigntariffaction.php" method="post">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label  class="control-label">Tariff</label>
							<select class="form-control select2 tariff" name="tariff_id" >
								<option value="" selected disabled>Select Tariff</option>
								<?php while($row = mysqli_fetch_array($tariff_q)){
									?>
									<option value="<?php echo $row['id'] ?>"><?php echo $row['tariff_name']; ?></option>
								<?php } ?>
							</select>
						</div>
					 </div>
				</div>
					<div class="row tariff_prices_div display_none">
                        <table class="table table-bordered table-stripped">
                            <thead>
                                <tr>
                                    <td>Sr No</td>
                                    <td>Start Range</td>
                                    <td>End Range</td>
                                    <!-- <td>Division Factor</td> -->
                                    <td>Rate</td>
                                </tr>
                            </thead>
                            <tbody class="tariff_prices">

                            </tbody>
                        </table>
                    </div>
				<input type="hidden" name='customer_id' value="<?php echo $customer_id;?>">
				 <div class="row">
				 	<div class="col-sm-6">
				 		<button type="submit" name="assign" class="add_form_btn" >Submit</button>
				 	</div>
				 </div>
			</form>
		</div>

	</div>
</div>
<script type="text/javascript">
   document.addEventListener('DOMContentLoaded', function() {
   	$('.select2').select2();
   	$('body').on('change', '.tariff', function(e) {
        let tariffValue = $(this).val();
        $.ajax({
            url: "ajax.php",
            type: "post",
            data: {
                gettariffPrices: 1,
                tariffValue: tariffValue
            },
            success: function(result) {
                $('body').find('.tariff_prices_div').removeClass('display_none');
                $('body').find('.tariff_prices').html(result);
                if (result === '') {
                    $('body').find('.tariff_prices_div').addClass('display_none');
                    $('body').find('.tariff_prices').html('');
                }
            }
        });
    })
}, false)
</script>