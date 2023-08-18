<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) && $_SESSION['type']!=='driver'){
	include "includes/header.php";
?>
<body data-ng-app>
 	
    
	<?php
	
	include "includes/sidebar.php";
	
	?>
    <!-- Aside Ends-->
    
    <section class="content">
    	 
	<?php
	include "includes/header2.php";
	?>
        
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid">
        	
            <div class="page-header"><h1>Customer Ledgers <small>Let's get a quick overview...</small></h1></div>
            
          <div class="panel panel-default">
	<div class="panel-heading">Employees Data</div>
		<div class="panel-body">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 
							<thead>
								<tr role="row">
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Sr.No</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Register Date</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Customer Name</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Email</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Phone</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Balance</th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$sr=1;
								$query1=mysqli_query($con,"Select * from customers WHERE status=1 order by id desc");
								while($fetch1=mysqli_fetch_array($query1)){
									$balance_query = mysqli_query($con,"SELECT 
											    (COALESCE(SUM(collected_amount),0) - 
											    ( 
											        COALESCE(SUM(delivery_charges),0) + 
											        COALESCE(SUM(paid),0)
											    )) as total FROM ledger WHERE customer_id = ".$fetch1['id']." ");
									$balance_data = mysqli_fetch_array($balance_query);
							?>
								<tr class="gradeA odd" role="row">
									<td><?php echo $sr; ?></td>
									<td class="center">
									<?php
										// echo date("jS F,Y",strtotime($fetch1['dates']));
										echo date("d/m/Y",strtotime($fetch1['dates']));
														 
									?>
									</td>
									<td class="center"><?php echo $fetch1['fname']; ?></td>
									<td class="center"><?php echo $fetch1['email']; ?></td>
									<td class="center"><?php echo $fetch1['mobile_no']; ?></td>
									<td>Rs <?php echo number_format($balance_data['total'],2); ?></td>
									<td>
										<a style="width: 100%;" class="btn btn-info btn-sm" href="ledger_detail.php?id=<?php echo$fetch1['id']; ?>">Detail</a>
										<a class="btn btn-success btn-sm" href="bulk_ledger.php?customer_id=<?php echo $fetch1['id']; ?>">Pay</a>
									</td>
								</tr>
								<?php
								$sr++;
								}
								
								?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
</div>
					
            
        </div>
        <!-- Warper Ends Here (working area) -->
        
        
      <?php
	
	include "includes/footer.php";
	}
	else{
		header("location:index.php");
	}
	?>