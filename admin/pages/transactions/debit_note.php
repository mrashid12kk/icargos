
<div class="warper" id="transactions">
	<div class="panel panel-default">
		<div class="panel-heading">Debit Note</div>
		<div class="panel-body ">
			<div class="row">
				<div class="col-sm-1 sidegap">
					<label>Voucher No</label>
					<input type="text"  placeholder="DN-9" class="form-control" value="">
				</div>
				<div class="col-sm-2 sidegap">
					<label>Cheque Date</label>
					<input type="date"  class="form-control " value="Nov 02,2021">
				</div>
				<div class="col-sm-2 sidegap">
					<label>Lab Search By Voucher No</label>
					<input type="text" class="form-control" name="">
				</div>
				<div class="col-sm-3 manual_api">
	                  <a href="#" class="btn btn-info">Download Sample</a>
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
                    <tbody class="response_table_body">
						<tr>
							<td>1</td>
							<td><input type="text" placeholder="01-03-05-01-L" class="code_for_this form-control"></td>
							<td>
								<select class="form-control">
									<option>Profit And Loss</option>
									<option>Service Account</option>
									<option>PDC Receivable</option>
									<option>Discount Received</option>
									<option>PDC Payable</option>
								</select>
							</td>
							<td>
								<select class="form-control">
									<option>Dr</option>
									<option>Cr</option>
								</select>
							</td>
							<td>
								<input type="text"  class="form-control amount validate_input_decimal" placeholder="Rs. 00.00">
							</td>
							<td><input type="text" class="form-control" name=""></td>
							<td><input type="date" class="form-control" name=""></td>
							<td><input type="text" class="form-control" name=""></td>
							<td>
								<div class="action_btn">
									<a href="#" class="btn btn-info"><i class="fa fa-plus"></i></a>
									<a href="#" class="btn btn-danger"><i class="fa fa-trash"></i></a>
								</div>
							</td>
						</tr>


						<tr>
							<td>2</td>
							<td><input type="text" placeholder="01-03-05-01-L" class="code_for_this form-control"></td>
							<td>
								<select class="form-control">
									<option>Profit And Loss</option>
									<option>Service Account</option>
									<option>PDC Receivable</option>
									<option>Discount Received</option>
									<option>PDC Payable</option>
								</select>
							</td>
							<td>
								<select class="form-control">
									<option>Dr</option>
									<option>Cr</option>
								</select>
							</td>
							<td>
								<input type="text"  class="form-control amount validate_input_decimal" placeholder="Rs. 00.00">
							</td>
							<td><input type="text" class="form-control" name=""></td>
							<td><input type="date" class="form-control" name=""></td>
							<td><input type="text" class="form-control" name=""></td>
							<td>
								<div class="action_btn">
									<a href="#" class="btn btn-info"><i class="fa fa-plus"></i></a>
									<a href="#" class="btn btn-danger"><i class="fa fa-trash"></i></a>
								</div>
							</td>
						</tr>

						<tr>
							<td>3</td>
							<td><input type="text" placeholder="01-03-05-01-L" class="code_for_this form-control"></td>
							<td>
								<select class="form-control">
									<option>Profit And Loss</option>
									<option>Service Account</option>
									<option>PDC Receivable</option>
									<option>Discount Received</option>
									<option>PDC Payable</option>
								</select>
							</td>
							<td>
								<select class="form-control">
									<option>Dr</option>
									<option>Cr</option>
								</select>
							</td>
							<td>
								<input type="text"  class="form-control amount validate_input_decimal" placeholder="Rs. 00.00">
							</td>
							<td><input type="text" class="form-control" name=""></td>
							<td><input type="date" class="form-control" name=""></td>
							<td><input type="text" class="form-control" name=""></td>
							<td>
								<div class="action_btn">
									<a href="#" class="btn btn-info"><i class="fa fa-plus"></i></a>
									<a href="#" class="btn btn-danger"><i class="fa fa-trash"></i></a>
								</div>
							</td>
						</tr>

						<tr>
							<td>4</td>
							<td><input type="text" placeholder="01-03-05-01-L" class="code_for_this form-control"></td>
							<td>
								<select class="form-control">
									<option>Profit And Loss</option>
									<option>Service Account</option>
									<option>PDC Receivable</option>
									<option>Discount Received</option>
									<option>PDC Payable</option>
								</select>
							</td>
							<td>
								<select class="form-control">
									<option>Dr</option>
									<option>Cr</option>
								</select>
							</td>
							<td>
								<input type="text"  class="form-control amount validate_input_decimal" placeholder="Rs. 00.00">
							</td>
							<td><input type="text" class="form-control" name=""></td>
							<td><input type="date" class="form-control" name=""></td>
							<td><input type="text" class="form-control" name=""></td>
							<td>
								<div class="action_btn">
									<a href="#" class="btn btn-info"><i class="fa fa-plus"></i></a>
									<a href="#" class="btn btn-danger"><i class="fa fa-trash"></i></a>
								</div>
							</td>
						</tr>
               	</tbody>
            </table>

            <div class="row remarks_box">
            	<div class="col-sm-6 textarea_box sidegap">
            		<label>Remarks</label>
					<textarea class="form-control" name="description"></textarea>
            	</div>
            	<div class="col-sm-6 sidegap">
            		<div class="input-right-box">
						<a name="import" class="btn btn-info import_file">Import</a>
						<button type="submit" name="submit" class="btn btn-info">Save</button>
						<button type="submit" name="save_print" class="btn btn-info">Save &amp; Print</button>
						<a href="#" class="btn btn-primary">Close</a>
					</div>
            	</div>
            </div>
        </div>
    </div>
</div>