<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) && $_SESSION['type']!=='driver'){
		 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],14,'view_only',$comment =null)) {
        header("location:access_denied.php");
    }
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
	 $customer_list = mysqli_query($con,"SELECT * FROM users WHERE type='driver' ");
	?>
        
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid">
        	<div class="row">
        		<div class="col-sm-12">
        			<?php if(isset($_SESSION['success'])){ ?>
        				<div class="alert alert-success">
        					<?php
        						echo $_SESSION['success'];
        						unset($_SESSION['success']); 
        					?>
        				</div>
        			<?php } ?>
        			<?php if(isset($_SESSION['errors'])){ ?>
        				<div class="alert alert-danger">
        					<?php
        						echo $_SESSION['errors'];
        						unset($_SESSION['errors']); 
        					?>
        				</div>
        			<?php } ?>
        		</div>
        	</div>
            <div class="row">
            	<div class="col-sm-6" style="padding: 7px 0 0;">
            		<div class="page-header"><h1><?php echo getLange('employepayment'); ?><small><?php echo getLange('letsgetquick'); ?></small>
		               </h1>
		            </div>	
            	</div>
            	<div class="col-sm-6" style="padding: 0;">
            		<div class="form-group pull-right select_customer_box" >
                  <select class="form-control flyer_selecter" onchange="window.location.href='employee_payments.php?customer_id='+this.value" name="customer_id">
                    <option value="">Select Employee</option>
                    <?php while($row_customer = mysqli_fetch_array($customer_list))
                      {  
                        ?>
                          <option <?php if(isset($_GET['customer_id']) && $_GET['customer_id'] == $row_customer['id']){ echo "Selected"; } ?>  value="<?php echo $row_customer['id']; ?>" > <?php echo $row_customer['Name']; ?> </option>
                        <?php 
                      }
                    ?>
                  </select>
                </div>
            	</div>
            </div>
            
          <div class="panel panel-default" style="margin-top: 0; position: relative;">
          	<div class="panel-heading"><?php echo getLange('payment'); ?>
				<a href="employee_bulk_ledger_payment.php" class="add_form_btn" style="float: right;font-size: 11px;"><?php echo getLange('addpayment'); ?> </a>
			</div>
			
	
		<div class="panel-body" id="same_form_layout" style="padding: 11px;">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				<div class="row">
					<div class="col-sm-12 table-responsive">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info"> 
							<thead>
								<tr>
									<th><?php echo getLange('srno'); ?>.</th>
									<th><?php echo getLange('employee'); ?></th>
									<th><?php echo getLange('paymentid'); ?> </th>
									<th><?php echo getLange('chequenotransactionid'); ?></th>
									<th><?php echo getLange('paymentdate'); ?> </th>
									<th><?php echo getLange('totalshipment'); ?> </th>
									<th><?php echo getLange('totalpickup'); ?> </th>
									<th><?php echo getLange('totaldeliveries'); ?> </th>
									<th><?php echo getLange('totalpayable'); ?> </th>
									<th><?php echo getLange('addition'); ?> </th>
									<th><?php echo getLange('deduction'); ?> </th>
									<th><?php echo getLange('payment'); ?></th>
									<th><?php echo getLange('balance'); ?></th>
									<th><?php echo getLange('action'); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php
							$where = (isset($_GET['customer_id']) && $_GET['customer_id'] != '') ? "WHERE customer_id = ".$_GET['customer_id'] : "";
							$sr=1;
								$query1=mysqli_query($con,"SELECT employee_ledger_payments.*, users.Name as customer  FROM employee_ledger_payments LEFT JOIN users ON (users.id = employee_ledger_payments.customer_id) {$where} order by id DESC");
								while($fetch1=mysqli_fetch_array($query1)){
									$total_ship = 0;
									$shipments_html = $deliveries_html = $returned_html = $flyers_html = '';
									$orders = ($fetch1['ledger_orders']) ? explode(',', $fetch1['ledger_orders']) : [];
									if(!empty($orders)) {
										$orders = array_unique($orders);
										$shipments_html = '<ul>';
										foreach ($orders as $ship) {
											$shipments_html .= '<li>'.((int)$ship+20000000).'</li>';
											++$total_ship;
										}
										$shipments_html .= '</ul>';
									}
									$orders = ($fetch1['ledger_delivered']) ? explode(',', $fetch1['ledger_delivered']) : [];
									$total_del = 0;
									if(!empty($orders)) {
										$orders = array_unique($orders);
										$deliveries_html = '<ul>';
										foreach ($orders as $ship) {
											$deliveries_html .= '<li>'.((int)$ship+20000000).'</li>';
											++$total_del;
										}
										$deliveries_html .= '</ul>';
									}
									$orders = ($fetch1['ledger_pickup']) ? explode(',', $fetch1['ledger_pickup']) : [];
									$total_pickup =0;
									if(!empty($orders)   ) {
										$orders = array_unique($orders);
										$returned_html = '<ul>';
										foreach ($orders as $ship) {
											$returned_html .= '<li>'.((int)$ship+20000000).'</li>';
											++$total_pickup;
										}
										$returned_html .= '</ul>';
									}
									$orders = ($fetch1['ledger_flyers']) ? explode(',', $fetch1['ledger_flyers']) : [];
									if(!empty($orders)) {
										$orders = array_unique($orders);
										$flyers_html = '<ul>';
										foreach ($orders as $ship) {
											$flyers_html .= '<li>'.sprintf('%04d', $ship).'</li>';
										}
										$flyers_html .= '</ul>';
									}
							?>
								<tr class="gradeA odd" role="row">
									<td><?=$sr;?></td>
									<td><?=$fetch1['customer'];?></td>
									<td><?=sprintf('%05d', $fetch1['id']);?></td>
									<td><?=$fetch1['reference_no'];?></td>
									<td><?=date('Y-m-d', strtotime($fetch1['payment_date']));?></td>
									<td><a href="#" title="Total Orders" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?=$shipments_html;?>"><?=$total_ship;?></a></td>
									<td><a href="#" title="Total Pickups" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?=$returned_html;?>"><?=$total_pickup;?></a></td>
									<td><a href="#" title="Total Deliveries" data-trigger="focus" data-toggle="popover" data-html="true" data-content="<?=$deliveries_html;?>"><?=$total_del;?></a></td>
									
									<td><?php echo getConfig('currency'); ?>. <?=number_format($fetch1['total_payable'], 2);?></td>
									<td><?php echo getConfig('currency'); ?>. <?=number_format($fetch1['total_addition'], 2);?></td>
									<td><?php echo getConfig('currency'); ?>. <?=number_format($fetch1['total_deduction'], 2);?></td>
									<td><?php echo getConfig('currency'); ?>. <?=number_format($fetch1['total_paid'], 2);?></td>
									<td><?php echo getConfig('currency'); ?>. <?=number_format(((float)$fetch1['total_payable'] - (float)$fetch1['total_paid']), 2);?></td>
									 <td class="action_btns"> 
										 <a  target="_blank" href="employee_ledger_payment_view.php?payment_id=<?php echo $fetch1['id']; ?>">	<i class="fa fa-eye" style="font-size: 14px;"></i>
										 </a> 
									 	<?php if($fetch1['update_able'] == 1){ ?>
											<a href="employee_submit_bulk_ledger_payment.php?delete=<?=$fetch1['id'];?>&customer_id=<?=$fetch1['customer_id'];?>" > <i style="color: #da1414;font-size: 14px;" class="fa fa-trash"></i></a>
										<?php } ?>
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