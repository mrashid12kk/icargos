<?php

$voucher_type_query = "SELECT * FROM `tbl_vouchertype` WHERE voucherTypeName = 'Debit Note'";
$voucher_type_query_result = mysqli_query($con,$voucher_type_query);
	
if($voucher_type_query_result->num_rows > 0)
{
	$voucher_type_tbl_data = mysqli_fetch_assoc($voucher_type_query_result);
	
}

// ****** tbl_debit_note_detail table data ********
$dn_query = "SELECT * FROM `tbl_debit_note_detail` ORDER BY id DESC LIMIT 1";
$dn_query_result = mysqli_query($con,$dn_query);
// die("test");
if($dn_query_result->num_rows > 0)
{
	$dn_data = mysqli_fetch_assoc($dn_query_result);
	
	$v_no = explode("-", $dn_data['voucherNo']);
	$v_no = end($v_no);
	$v_no += 1;
	
	
}
else
{
	$v_no = 1;
}


// ****** for edit form , data fetching from DB **********
if(isset($_GET['voucher_no']))
{
	$voucher_no = $_GET['voucher_no'];
	$edit_query_credit_master = "SELECT * FROM `tbl_debit_note_master` WHERE voucher_no = '{$voucher_no}'";
	$credit_edit_master_query_result = mysqli_query($con,$edit_query_credit_master);
	if($credit_edit_master_query_result->num_rows > 0)
	{
		$credit_edit_master_data = mysqli_fetch_assoc($credit_edit_master_query_result);
		$master_cheque_date = date("Y-m-d",strtotime($credit_edit_master_data['cheque_date']));
		
	}
}

?>
<style type="text/css">
	.searhbox button{
		margin: 18px 0 0;
	}
</style>
<form action="https://a.icargos.com/portal/admin/includes/custom_functions.php" method="POST" id="debit-form">
<div class="warper" id="transactions">
	<div class="panel panel-default">
		<div class="panel-heading">Debit Note</div>
		<?php
		$master_id='';
		if(isset($_SESSION['msg']))
		{
			echo $_SESSION['msg'];
			unset($_SESSION['msg']);
		}

		if(isset($credit_edit_master_data['id']))
		{
			$master_id = $credit_edit_master_data['id'];
			
		}
		echo "<input type='hidden' name='master_id' id='masterId' value='{$master_id}'>";
		?>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-1 sidegap">
					<label>Voucher No</label>
					<input type="text"  placeholder="" value="<?php echo $_GET['voucher_no'] ?? ($voucher_type_tbl_data['voucher_prefix']."-".$v_no);  ?>" class="form-control" name="voucher_number" readonly>
				</div>
				<div class="col-sm-2 sidegap">
					<label>Cheque Date</label>
					<input type="date"  class="form-control " value="<?php echo $master_cheque_date ?? date('Y-m-d'); ?>" name="master_cheque_date" id="master_cheque_date">
				</div>
				<div class="col-sm-4 sidegap">
					<div class="row">
						<div class="col-sm-8">
							<label>Search By Voucher No</label>
							<input type="text" class="form-control" value="<?php echo $_GET['voucher_no'] ?? ''; ?>" name="search_voucher_number" id="search_voucher_number" >
						</div>
						<div class="col-sm-4 searhbox">
							<button type="button" class="btn btn-primary" id="debit-search-btn" data-mastertable="tbl_debit_note_master" data-table="tbl_debit_note_detail">Search</button>
						</div>
					</div>
					
				</div>
				<div class="col-sm-3 manual_api">
	                  <a href="/portal/admin/pages/transactions/DN_Sample.csv" class="btn btn-info" download>Download Sample</a>
	              </div>
			</div>
		</div>
	</div>

	<div class="row" id="listing_table">
        <div class="col-sm-12 sidegap">
            <table class="table_box">
                <thead>
                    <tr>
                        <th style="width: 45px;">Sr.No</th>
                        <th>Code</th>
                        <th>Account Ledger</th>
                        <th>Dr/Cr</th>
                        <th>Amount</th>
                        <th>Check No</th>
                        <th>Check Date</th>
                        <th>Narration</th>
                        <th></th>
                    </tr>
                </thead>
                    <tbody class="response_table_body table-container" id="debit-table-body">

						<tr class="table-item-row">
							<td>1</td>
							<td><input type="text" placeholder="01-03-05-01-L" class="code_for_this form-control ledger-code" id="ledger-code" name="ledger_code[]"></td>
							<td>
								
								<select class="form-control ledger_name"  name="ledger_id[]">
									<option value="0">Choose Ledger</option>	
								</select>
							</td>
							<td>
								<select class="form-control type" name="type[]">
									<option value="Dr">Dr</option>
									<option value="Cr">Cr</option>
								</select>
							</td>
							<td>
								<input type="text"  class="form-control amount validate_input_decimal" placeholder="00.00" name="amount[]">
							</td>
							<td><input type="text" class="form-control" name="cheque_number[]"></td>
							<td><input type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control"  name="detail_cheque_date[]"></td>
							<td><input type="text" class="form-control" name="narration[]"></td>
							<td>
								<div class="action_btn">
									<button type="button" class="btn btn-info btn-sm add"><i class="fa fa-plus"></i></button>
								</div>
								 <input type="hidden" name="id[]" />
							</td>
						</tr>

			
               	</tbody>
            </table>

            <div class="row remarks_box">
            	<div class="col-sm-6 textarea_box sidegap">
            		<label>Remarks</label>
					<textarea class="form-control" name="description" id="remarks">
						<?php echo $credit_edit_master_data['description'] ?? ''; ?>
							
						</textarea>
            	</div>
            	<div class="col-sm-6 sidegap">
            		<div class="input-right-box">
						<a name="import" class="btn btn-info import_file">Import</a>
						<button type="submit" name="debit_note_save" class="btn btn-info" id="debit_save">Save</button>
						<button type="submit" name="debit_note_save_print" value="save_print" class="btn btn-info">Save &amp; Print</button>
						<a href="#" class="btn btn-primary">Close</a>
					</div>
            	</div>
            </div>
        </div>
    </div>
</div>

</form>

<input type="file" style="display: none;" id="file" name=""/>

<script type="text/javascript">
    $('.import_file').click(function(){
        $('#file').click();
    });

    
    
	$('#file').on('change', function(event) {
    var file_data = $('#file').prop('files')[0];   
    var form_data = new FormData(); 
      event.preventDefault();
                 
    form_data.append('file', file_data);
    // alert(form_data);                             
    $.ajax({
        url: 'https://a.icargos.com/portal/admin/upload_csv.php', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(response){
            if(response != 0)
            {
            	response = JSON.parse(response);
            	displayDetailItems(response);

            } 
            else 
            {
            	alert('unable to upload file');
            }
            
        }
     });
});
</script>
