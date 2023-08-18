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

if($dn_query_result->num_rows > 0)
{
	$dn_data = mysqli_fetch_assoc($dn_query_result);
	$v_no = explode("-", $dn_data['voucherNo']);
	$v_no = end($v_no);
	$v_no += 1;
	
	
}

?>
<form>
<div class="warper" id="transactions">
	<div class="panel panel-default">
		<div class="panel-heading">Debit Note</div>
		
		<div class="panel-body ">
			<div class="row">
				<div class="col-sm-1 sidegap">
					<label>Voucher No</label>
					<input type="text"  placeholder="" value="<?php echo $voucher_type_tbl_data['voucher_prefix']."-".$v_no;  ?>" class="form-control" name="voucher_number" readonly>
				</div>
				<div class="col-sm-2 sidegap">
					<label>Cheque Date</label>
					<input type="date"  class="form-control " value="<?php echo date('Y-m-d'); ?>" name="master_cheque_date">
				</div>
				<div class="col-sm-2 sidegap">
					<label>Search By Voucher No</label>
					<input type="text" class="form-control" name="">
				</div>
				<div class="col-sm-3 manual_api">
	                  <a href="/portal/admin/pages/transactions/DN_sample.xlsx" class="btn btn-info" download>Download Sample</a>
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
                    <tbody class="response_table_body table-container">

						<tr class="table-item-row">
							<td>1</td>
							
						</tr>

			
               	</tbody>
            </table>

            <div class="row remarks_box">
            	
            	<div class="col-sm-6 sidegap">
            		<!-- <div class="input-right-box">
						<a name="import" class="btn btn-info import_file">Import</a>
						<button type="submit" name="debit_note_save" class="btn btn-info">Save</button>
						<a href=""  name="save_print" class="btn btn-info">Save &amp; Print</a>
						<a href="#" class="btn btn-primary">Close</a>
					</div> -->
            	</div>
            </div>
        </div>
    </div>
</div>

</form>
