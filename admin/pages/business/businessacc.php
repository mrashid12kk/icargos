<style type="text/css">
	.label {
    font-size: 100% !important;
}
</style>
<?php

	if(isset($_POST['delete'])){

		$id=mysqli_real_escape_string($con,$_POST['id']);

		$query1=mysqli_query($con,"delete from users where id=$id") or die(mysqli_error($con));

		$rowscount=mysqli_affected_rows($con);

		if($rowscount>0){

			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a driver successfully</div>';

		}

		else{

			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete a driver unsuccessfully.</div>';

		}

	}

$query12=mysqli_query($con,"Select count(id) as approve from customers WHERE status='1'");
$fetch12=mysqli_fetch_assoc($query12);
$totalapprove=$fetch12['approve'];
$query123=mysqli_query($con,"Select count(id) as pending from customers WHERE status='0'");
$fetch12=mysqli_fetch_assoc($query123);
$totalpending=$fetch12['pending'];

?>
<div class="add_business_tabs">
		<ul>
			<li><a class="active" href="businessacc.php"><?php echo getLange('approved'); ?> (<?php echo $totalapprove; ?>)</a></li>
			<li><a href="pendingbusinessacc.php"><?php echo getLange('pending'); ?> (<?php echo $totalpending; ?>)</a></li>
		</ul>
	</div>
<div class="panel panel-default">
	
	<div class="panel-heading"><?php echo getLange('businessaccount'); ?> <a href="addbusiness.php" class="add_form_btn" style="float: right;font-size: 11px;"><?php echo getLange('addnew'); ?>  </a></div>

		<div class="panel-body" id="same_form_layout" style="padding: 11px;"> 

			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

				<div class="row">
						
					<div class="col-sm-12 table-responsive">

						<table id="order_datatable" cellpadding="0" cellspacing="0" border="0" class="table table-striped orders_tbl  table-bordered no-footer dtr-inline collapsed" role="grid" aria-describedby="basic-datatable_info">
						<div class="fake_loader" id="image" style="text-align: center;">
							<img src="images/fake-loader-img.gif" alt="logo" style="width:130px;"> 
						</div>
						
							<thead>
								<tr role="row">
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 40px;"><?php echo getLange('srno'); ?></th>
								      <th style="width: 100px;"><?php echo getLange('logo'); ?> </th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('registerdate'); ?> </th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('accountid'); ?> </th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('accountname'); ?> </th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('businessname'); ?> </th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('businessmanager'); ?> </th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('email'); ?></th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('phone'); ?></th>
								    <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('cnic').' '.getLange('copy'); ?></th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('status'); ?></th>
								   <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 265px;"><?php echo getLange('action'); ?></th>
								</tr>
							</thead>

						</table>

				</div>
				
			</div>
		</div>
	</div>
</div>
