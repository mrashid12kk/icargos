<?php
	if(isset($_GET['tariff_id'])){
		$tariff_id     = $_GET['tariff_id'];
		$customer_id = $_GET['customer_id'];
		$edit = mysqli_query($con,"SELECT * FROM customer_tariff_detail WHERE tariff_id ='".$tariff_id."' AND customer_id='".$customer_id."' ORDER By id ASC");
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
	$tariff_q = mysqli_query($con,"SELECT * FROM tariff WHERE pay_mode = $customerPayModeId ");
	$products = mysqli_query($con,"SELECT * FROM products ORDER BY id DESC ");
?>
<style type="text/css">
	.display_none{
		/*display: none;*/
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
									<option value="<?php echo $row['id'] ?>" <?php echo isset($_GET['tariff_id']) && $_GET['tariff_id']==$row['id'] ? 'selected' : ''; ?>><?php echo $row['tariff_name']; ?></option>
								<?php } ?>
							</select>
						</div>
					 </div>
				</div>
					<div class="row tariff_prices_div ">
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
                            	<?php
                            	$srno = 1;
                            	 while ($row=mysqli_fetch_array($edit)) { ?>
	                            	<tr>
									<td><?php echo $srno++; ?></td>
									<td><input type="text" name="start_range[]" value="<?php echo $row["start_range"]; ?>" class="form-control" readonly /></td>
									<td><input type="text" name="end_range[]" value="<?php echo $row["end_range"]; ?>" class="form-control"  readonly /></td>
									<td><input type="text" name="rate[]" value="<?php echo $row["rate"]; ?>" placeholder="Enter Rate" class="form-control" required /></td>
									</tr>
								<?php } ?>
                            </tbody>
                        </table>
                    </div>
				<input type="hidden" name='customer_id' value="<?php echo $customer_id;?>">
				 <div class="row">
				 	<div class="col-sm-6">
				 		<button type="submit" name="update_customer_tariff" class="add_form_btn" >Submit</button>
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