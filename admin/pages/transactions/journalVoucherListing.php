
<div class="warper" id="transactions">
	<div class="row">
		<div class="col-sm-8 head_box sidegap">
			<h4>Journal Voucher</h4>
		</div>
		<div class="col-sm-4  create_btn">
			<a href="journal_voucher.php" class="btn  btn-primary "> <i class="fa fa-plus"></i> Add Journal Voucher</a>
		</div>
	</div>
	<div class="panel panel-default">
		
		<div class="row" id="listing_table">
        <div class="col-sm-12 sidegap">
            <table class="table_box">
                <thead>
                    <tr>
                        <th style="width: 45px;">Sr.No</th>
                        <th>Voucher No</th>
                        <th>Date</th>
                        <th>User</th>
                        <th style="width: 90px !important;">Actions</th>
                    </tr>
                </thead>
                    <tbody class="response_table_body">
                    	<?php
                    		$query = "SELECT dbn_det.id,dbn_det.voucher_no as voucherNo,dbn_det.created_on as created_at,u.Name FROM `tbl_journal_voucher_master` as dbn_det LEFT JOIN `users` as u
                    				  ON u.id = dbn_det.user_id ORDER BY dbn_det.id DESC";
                    		$query_result = mysqli_query($con,$query);
                    		if($query_result->num_rows > 0)
                    		{

                    			$records = mysqli_fetch_all($query_result,MYSQLI_ASSOC);
                    			
                    			$sr_no = 0;
                    			foreach ($records as $record) 
                    			{
                    	

                    	?>
						<tr>
							<td><?php echo ++$sr_no; ?></td>
							<td><?php echo $record['voucherNo']; ?></td>
							<td><?php echo $record['created_at']; ?></td>
							<td><?php echo $record['Name']; ?></td>
							<td style="text-align: center;">
								<div class="action_btn">
									<a href="#" class="btn btn-danger delete-record" data-id="<?php echo $record['id']; ?>" data-tblname="tbl_journal_voucher_master"><i class="fa fa-trash"></i></a>
									
									<a href="journal_voucher.php?voucher_no=<?=$record['voucherNo']?>" data-table="tbl_journal_voucher_detail" class="btn btn-info" id="debit-search-btn"><i class="fa fa-edit"></i></a>
									<a href="journal_voucher_print.php?voucher_number=<?=$record['voucherNo']?>&preview" class="btn btn-info"><i class="fa fa-eye"></i></a>
								</div>
							</td>
						</tr>
						<?php
								}
							}

						?>
						
					
               	</tbody>
            </table>

            
        </div>
    </div>
	</div>


	
</div>