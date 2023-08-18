<?php
	if(isset($_GET['delete'])){
		$id=mysqli_real_escape_string($con,$_GET['delete']);
		$query1=mysqli_query($con,"UPDATE customers SET is_delete=1 where id=$id") or die(mysqli_error($con));
		$rowscount=mysqli_affected_rows($con);
		if($rowscount>0){
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> You have rejected customer successfully</div>';
		}
		else{
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> YYou have rejected customer successfully</div>';
		}
	}
	$query12=mysqli_query($con,"Select count(id) as approve from customers WHERE status='1'");
$fetch12=mysqli_fetch_assoc($query12);
$totalapprove=$fetch12['approve'];
$query123=mysqli_query($con,"Select count(id) as pending from customers WHERE status='0'");
$fetch12=mysqli_fetch_assoc($query123);
$totalpending=$fetch12['pending'];
?>
<?php if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
	echo $_SESSION['message'];
	unset ($_SESSION["message"]);
} ?>
<div class="add_business_tabs">
		<ul>
			<li><a  href="businessacc.php">Approved (<?php echo $totalapprove; ?>)</a></li>
			<li><a class="active" href="pendingbusinessacc.php">Pending (<?php echo $totalpending; ?>)</a></li>
		</ul>
	</div>
<div class="panel panel-default">
	<div class="panel-heading"><?php echo getLange('accountdata'); ?> </div>
		<div class="panel-body" id="same_form_layout">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer same_inner_gapp">
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 
							<thead>
								<tr role="row">
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 40px;"><?php echo getLange('srno'); ?></th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('registerdate'); ?></th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('accountid'); ?> </th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('accountname'); ?> </th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('businessname'); ?></th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('businessmanager'); ?> </th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('email'); ?></th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('phone'); ?></th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 244px;"><?php echo getLange('action'); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php
							$sr = 1;
								$query1=mysqli_query($con,"Select * from customers WHERE status=0 order by id desc");
								while($fetch1=mysqli_fetch_array($query1)){
									$ledger = mysqli_fetch_array(mysqli_query($con,"SELECT * from tbl_accountledger where customer_id ='".$fetch1['id']."'"));
									$ledger_posting = mysqli_query($con, "SELECT * from tbl_ledgerposting WHERE `ledgerId` = '".$ledger['ledgerCode']."'");
							?>
								<tr class="gradeA odd" role="row">
									<td><?php echo $sr; ?></td>
									<td class="center">

									<?php
										// echo date("jS F,Y",strtotime($fetch1['dates']));
										echo date("d M Y",strtotime($fetch1['dates']));
														 
									?>

									</td>
									<td class="center"><?php echo $fetch1['client_code']; ?></td>
									<td class="center"><?php echo $fetch1['fname']; ?></td>
									<td class="center"><?php echo $fetch1['bname']; ?></td>
									<td class="center"><?php echo $fetch1['business_manager']; ?></td>
									<td class="center"><?php echo $fetch1['email']; ?></td>
									<td class="center"><?php echo $fetch1['mobile_no']; ?></td>
									<td class="center">
										<?php if($fetch1['is_delete'] == 0){ ?>
										<a class="btn btn-info" href="customer_detail.php?customer_id=<?php echo$fetch1['id']; ?>"><?php echo getLange('view'); ?></a>
										<a href="approve_customer.php?id= <?php echo $fetch1['id'] ?> " class="btn btn-success"><?php echo getLange('approve'); ?></a>
									<a href="pendingbusinessacc.php?delete=<?php echo $fetch1['id'] ?> " class="btn btn-danger"><?php echo getLange('reject'); ?></a>
								<?php } ?>
								<?php if($fetch1['is_delete'] == 1){  ?>
									<a href="#" class="btn btn-danger" disabled><?php echo getLange('rejected'); ?></a>
								<?php } ?>
								<?php
									if($ledger_posting->num_rows == 0){
								?>
								<a class="btn btn-info" href="cancel_customer.php?delete_id=<?php echo$fetch1['id']; ?>" onclick="return confirm('Are you sure you want to Delete?'); return false"><?php echo getLange('delete'); ?></a>
								<?php
									}
								?>
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