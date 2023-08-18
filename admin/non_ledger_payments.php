<?php
	session_start();
	require 'includes/conn.php';
	if(isset($_SESSION['users_id']) && $_SESSION['type']!=='driver'){
		 require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],16,'view_only',$comment =null)) {

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
  $customer_list = mysqli_query($con,"SELECT * FROM customers where status='1'");
	   $gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
  $total_gst = mysqli_fetch_array($gst_query);
  $from = date('Y-m-d', strtotime('today - 30 days'));
  $to = date('Y-m-d');
	?>

        <!-- Header Ends -->


        <div class="warper container-fluid">

            <div class="row">
            	<div class="col-sm-6" style="padding: 7px 0 0;">
            		<div class="page-header"><h1><?php echo getLange('customer').' '.getLange('payment'); ?> <small><?php echo getLange('letsgetquick'); ?></small>
		               </h1>
		            </div>
            	</div>
            	
            	
            </div>

          <div class="panel panel-default" style="margin-top: 0; position: relative;">
          	<div class="panel-heading"><?php echo getLange('payment'); ?>
				<a href="non_bulk_ledger_payment.php" class="add_form_btn" style="float: right;font-size: 11px;"><?php echo getLange('createnewinvoice'); ?> </a>
			</div>


		<div class="panel-body" id="same_form_layout" style="padding: 11px;">
			<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
				<form method="POST" action="">

          <div class="col-sm-2" id="ledger_payemnt_dropdown" style="padding: 0;">
              <div class="form-group pull-right select_customer_box" >
                <label><?php echo getLange('selectcustomer'); ?></label>
                <select class="form-control js-example-basic-single" id="cid" name="customer_id">
                  <option value="">Select Customer</option>
                  <?php while($row_customer = mysqli_fetch_array($customer_list))
                    {
                      ?>
                        <option <?php if(isset($_GET['customer_id']) && $_GET['customer_id'] == $row_customer['id']){ echo "Selected"; } ?>  value="<?php echo $row_customer['id']; ?>" > <?php echo $row_customer['fname']." (".$row_customer['bname'].")"; ?> </option>
                      <?php
                    }
                  ?>
                </select>
              </div>
            </div>

            <div class="col-sm-2 left_right_none">
              <div class="form-group">
                <label><?php echo getLange('from'); ?></label>
                  <input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4" name="from"id="from">
              </div>
            </div>
            <div class="col-sm-2 left_right_none">
              <div class="form-group">
                <label><?php echo getLange('to'); ?></label>
                  <input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to" id="to">
              </div>
            </div>
            <div class="col-sm-1 sidegapp-submit left_right_none">
              <input type="button" id="submit_order" style="margin-top: 9px;"  name="submit" class="btn btn-info" value="<?php echo getLange('submit'); ?>">
            </div>
        </form>
        <div class="row">
					<div class="col-sm-12 table-responsive">
						<table id="non_ledeger_datatable" cellpadding="0" cellspacing="0" border="0" class="table table-striped orders_tbl  table-bordered no-footer dtr-inline collapsed" role="grid" aria-describedby="basic-datatable_info">
              <div class="fake_loader" id="image" style="text-align: center;">
              <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;"> 
            </div>
							<thead>

								<tr>
									<th><?php echo getLange('srno'); ?>.</th>
									<th><?php echo getLange('customer'); ?></th>
									<th><?php echo getLange('paymentid'); ?> </th>
									<th><?php echo getLange('transactionid'); ?> </th>
									<th><?php echo getLange('paymentdate'); ?> </th>
									<th><?php echo getLange('totalshipment'); ?> </th>
									<th><?php echo getLange('totaldeliveries'); ?> </th>
									
									<th><?php echo getLange('totalcodamount'); ?>  </th>
									<th><?php echo getLange('deliveycharges'); ?> </th>
							
									<th><?php echo getLange('gst'); ?> (<?php echo $total_gst['value']; ?>%)</th>
									<th><?php echo getLange('flyers'); ?></th>
									<th><?php echo getLange('totalpayable'); ?> </th>
									<th><?php echo getLange('payment'); ?></th>
									<th><?php echo getLange('balance'); ?></th>
									<th><?php echo getLange('action'); ?></th>
								</tr>
							</thead>
              
						
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
  <script type="text/javascript">
    $(function () {
      $('.datetimepicker4').datetimepicker({
        format: 'YYYY/MM/DD',
      });
    });
  </script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
	//alert("faisal");
  var dataTable = $('#non_ledeger_datatable').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    // 'scrollCollapse': true,
    // 'ordering': false,
    'responsive': true,
    'pageLength': 10,
    'lengthMenu':[[10,25,50,100,200,300],[10,25,50,100,200,300]],
    'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
       // dom: '<"html5buttons"B>lTfgitp',
      'buttons': [
              {extend: 'copy'},
              {extend: 'csv'},
              {extend: 'excel', title: 'ExampleFile'},
              {extend: 'pdf', title: 'ExampleFile'},
              
          ],
    //'searching': false, // Remove default Search Control
    'ajax': {
       'url':'ajax_view_non_ledger_payments.php',
         beforeSend: function(){
         $('#image').show();
     },
    complete: function(){
        $('#image').hide();
    }, 
    'data': function(data){
        var from = $('#from').val();
        var to = $('#to').val();
        var cid = $('#cid').val();

        data.from = from;
        data.to = to;
        data.cid = cid

      }  
    },
    
    'columns': [
       { data: 'srno' }, 
       { data: 'customer_name' }, 
       { data: 'id' }, 
       { data: 'reference_no' }, 
       { data: 'payment_date' }, 
       { data: 'total_shipments' }, 
       { data: 'total_delivered' }, 
       { data: 'cod_amount' },
       { data: 'delivery_charges' },   
       { data: 'gst_amount' },
       { data: 'total_sell_flyers' },   
       { data: 'currency' },
       { data: 'total_paid' },
       { data: 'total_payable' },
       { data: 'update_able' },

    ]
  });
 
$('#submit_order').click(function(e){
    e.preventDefault();
    
    dataTable.draw();
  });

}, false);
</script>
