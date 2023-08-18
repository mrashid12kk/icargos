<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && $_SESSION['type'] !== 'driver') {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 35, 'view_only', $comment = null)) {
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
            $customer_list = mysqli_query($con, "SELECT * FROM customers where status='1'");
            $gst_query = mysqli_query($con, "SELECT value FROM config WHERE `name`='gst' ");
            $total_gst = mysqli_fetch_array($gst_query);
            $from = date('Y-m-d', strtotime('today - 30 days'));
            $to = date('Y-m-d');
             if (isset($_POST['payment_submit'])) {
		    	$date = date('Y-m-d H:i:s');
		    	$customer_payment_id=isset($_POST['customer_payment_id']) ? $_POST['customer_payment_id'] : '';
		    	$payment_date=isset($_POST['payment_date']) ? date('Y-m-d' , strtotime($_POST['payment_date'])) : '';
		    	$customer_id=isset($_POST['customer_id']) ? $_POST['customer_id'] : '';
		    	$transaction_id=isset($_POST['transaction_id']) ? $_POST['transaction_id'] : '';
		    	$invoice_no=isset($_POST['invoice_no']) ? $_POST['invoice_no'] : '';
		    	$amount=isset($_POST['amount']) ? $_POST['amount'] : '0';
                $sql="SELECT * FROM customer_ledger_payments_detail WHERE transaction_id='".$transaction_id."'";
                $query=mysqli_query($con,$sql);
                $rowcount=mysqli_affected_rows($con);
                if ($rowcount > 0) {
                    $_SESSION['update_class']='danger';
                    $_SESSION['update_title']='Opps';
                    $_SESSION['update_message']="This Transaction Id Already Exists";
                }
                else{
    		    	$sql="INSERT INTO `customer_ledger_payments_detail`(`customer_payment_id`, `amount`, `transaction_id`, `invoice_no`, `user_id`, `customer_id`, `payment_date`, `created_no`) VALUES (".$customer_payment_id.",".$amount.",'".$transaction_id."','".$invoice_no."',".$_SESSION['users_id'].",'".$customer_id."','".$payment_date."','".$date."')";
    		    	$query=mysqli_query($con,$sql);
    		    	$rowcount=mysqli_affected_rows($con);
    		    	if ($rowcount > 0) {
    		    		$select_sql=mysqli_fetch_assoc(mysqli_query($con,"SELECT total_paid,total_payable FROM customer_ledger_payments WHERE id=".$customer_payment_id));
    		    		$total_paid_amount=$select_sql['total_paid'] + $amount;
                        $update_status='';
                        if ($select_sql['total_payable'] == $total_paid_amount) {
                            $update_status=",status='1'";
                        }
    		    		$update_sql="UPDATE `customer_ledger_payments` SET `total_paid`=".$total_paid_amount."".$update_status." WHERE id=".$customer_payment_id;
    		    		$query=mysqli_query($con,$update_sql);
    			    	$rowcount=mysqli_affected_rows($con);
    			    	if ($rowcount > 0) {
    			    		$_SESSION['update_class']='success';
    		                $_SESSION['update_title']='Success';
    			    		$_SESSION['update_message']="Payment Added Successfully";
    			    	}
    		    	}
                }
		    }
            ?>
        <!-- Header Ends -->
        <div class="warper container-fluid">

            <div class="row">
                <div class="col-sm-6" style="padding: 7px 0 0;">
                    <div class="page-header">
                        <h1><?php echo getLange('customer') . ' ' . getLange('payment') ?>
                            <small><?php echo getLange('letsgetquick'); ?></small>
                        </h1>
                    </div>
                </div>
               
            </div>
            <div class="add_business_tabs">
                <ul>
                    <li><a class="active" href="ledger_payments.php">Invoices</a></li>
                    <li><a href="ledger_payments_detail.php">Payment</a></li>
                </ul>
            </div>
            <div class="panel panel-default" style="margin-top: 0; position: relative;">
                <div class="panel-heading"><?php echo getLange('payment'); ?>
                    <a href="bulk_ledger_payment.php" class="add_form_btn"
                        style="float: right;font-size: 11px;"><?php echo getLange('addpayment'); ?> </a>
                </div>
                <div class="panel-body" id="same_form_layout" style="padding: 11px;">
                    <?php
                    	
                        if (isset($_SESSION['update_message']) && !empty($_SESSION['update_message'])) {
                        ?>
                    <div class="alert alert-<?php echo $_SESSION['update_class'] ?> alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong><?php echo $_SESSION['update_title'] ?>!</strong>
                        <?php echo $_SESSION['update_message'] ?>.
                    </div>
                    <?php

                            unset($_SESSION['update_class']);
                            unset($_SESSION['update_message']);
                            unset($_SESSION['update_title']);
                        }
                        ?>
                    <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                        <form method="POST" action="">

                             <div class="col-sm-2" id="ledger_payemnt_dropdown" style="padding: 0;">
                                <div class="form-group pull-right select_customer_box">
                                    <label><?php echo getLange('selectcustomer'); ?></label>
                                    <select class="form-control js-example-basic-single" id="cid" 
                                        name="customer_id">
                                        <option value="">Select Customer</option>
                                        <?php while ($row_customer = mysqli_fetch_array($customer_list)) {
                                            ?>
                                        <option <?php if (isset($_GET['customer_id']) && $_GET['customer_id'] == $row_customer['id']) {
                                                            echo "Selected";
                                                        } ?> value="<?php echo $row_customer['id']; ?>">
                                            <?php echo $row_customer['fname'] . " (" . $row_customer['bname'] . ")"; ?> </option>
                                        <?php
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('from'); ?></label>
                                    <input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4"
                                        name="from" id="from">
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('to'); ?></label>
                                    <input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4"
                                        name="to" id="to">
                                </div>
                            </div>
                            <div class="col-sm-1 sidegapp-submit left_right_none">
                                <input type="button" id="submit_order" style="margin-top: 9px;" name="submit"
                                    class="btn btn-info" value="<?php echo getLange('submit'); ?>">
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <div class="row" style="margin-bottom: 15px !important;">
                                    <div class="col-sm-3">
                                    <button class="btn btn-info mbl_report">MBL Report</button> 
                                    </div>
                                    <div class="col-sm-3"> 
                                    <button class="btn btn-success mark_paid">Bulk Invoices Payment</button>
                                    </div>
                                </div>
                                <table id="ledeger_datatable" cellpadding="0" cellspacing="0" border="0"
                                    class="table table-striped orders_tbl  table-bordered no-footer dtr-inline collapsed"
                                    role="grid" aria-describedby="basic-datatable_info">
                                    <div class="fake_loader" id="image" style="text-align: center;">
                                        <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;">
                                    </div>
                                    <thead>
                                        <tr>
                                            <th><?php echo getLange('srno'); ?>.
                                                <input type="checkbox" name="" class="main_select">
                                            </th>
                                            <th><?php echo getLange('clientcode'); ?> </th>
                                            <th><?php echo getLange('customer'); ?></th>
                                            <th><?php echo getLange('invoiceno'); ?> </th>
                                            <th><?php echo getLange('paymentdate'); ?> </th>
                                            <th><?php echo getLange('totalshipment'); ?> </th>
                                            <th><?php echo getLange('totaldeliveries'); ?> </th>
                                            <th><?php echo getLange('totalreturned'); ?> </th>
                                            <th><?php echo getLange('totalcodamount'); ?> </th>
                                            <th><?php echo getLange('deliveycharges'); ?> </th>
                                            <th><?php echo getLange('returned'); ?></th>
                                            <th><?php echo getLange('returnedfee'); ?> </th>
                                            <th><?php echo getLange('cashhandling'); ?> </th>
                                            <th><?php echo getLange('gst'); ?> (<?php echo $total_gst['value']; ?>%)
                                            </th>
                                            <th><?php echo getLange('flyers'); ?></th>
                                            <th><?php echo getLange('totalpayable'); ?> </th>
                                            <th><?php echo getLange('payment'); ?></th>
                                            <th><?php echo getLange('balance'); ?></th>
                                            <th><?php echo getLange('status'); ?></th>
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
        <form method="POST" id="bulk_submit" action="mrf_report.php">
            <input type="hidden" name="order_id" id="print_data">

            <input type="hidden" name="save_print">
        </form>
        <form method="POST" id="bulk_paid" action="bulk_invoices_payment.php">
            <input type="hidden" name="payment_ids" id="payment_data">
        </form>



<div class="paynow_overlay"></div>
<div class="paynow_inner">
    <div class="close_btn">
        <i class="fa fa-close"></i>
    </div>
    <form action="" method="post">
    	<input type="hidden" value="" name="customer_payment_id" class="paynow_customer_payment_id">
        <div class="formbox">
            <label><span style="color: red;">*</span>Date</label>
            <input type="test" readonly class="datetimepicker4 paynow_payment_date" value="<?php echo date('Y-m-d'); ?>" name="payment_date" required>
        </div>
        <div class="formbox">
            <label>Customer</label>
            <input type="text" class="customer_name" value="" readonly>
            <input type="hidden" value="" name="customer_id" class="paynow_customer_id">
        </div>
        <div class="formbox">
            <label><span style="color: red;">*</span>Transaction ID</label>
            <input type="text" value="" name="transaction_id" class="paynow_transaction_id" required>
        </div>
        <div class="formbox">
            <label>Invoice No</label>
            <input type="text" value="" name="invoice_no" class="paynow_invoice_no" readonly>
        </div>
        <div class="formbox">
            <label><span style="color: red;">*</span>Amount</label>
            <input type="mynumber" value="" name="amount" class="paynow_amount"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" / required>
            <input type="hidden" value="" class="paynow_amount_payable">
        </div>
        <div class="total_msg formbox" style="display: none;">
        </div>
        <div class="formbox">
            <button type="submit" value="" name="payment_submit" class="payment_submit">Save</button>
        </div>
    </form>
</div>


        <!-- Warper Ends Here (working area) -->
        <?php
        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>
        <script type="text/javascript">
        $(function() {
            $('.datetimepicker4').datetimepicker({
                format: 'YYYY/MM/DD',
            });
        });
		$('body').on('click', '.pay_now', function(e) {
			 $('.payment_submit').prop("disabled",false);
			 $('.total_msg').hide();
			var payment_id=$(this).attr('data-id');
	          $.ajax({
	          type:'POST',
       		  dataType:'json',
	          data:{payment_id:payment_id},
	          url:'ajax.php',
	          success:function(response){
	          	  var total_paid=response.total_payable - response.total_paid;
	          		$('.paynow_customer_payment_id').val(response.id);
	          		$('.customer_name').val(response.customer+'  ('+response.company_name+')');
	          		$('.paynow_customer_id').val(response.customer_id);
	          		$('.paynow_transaction_id').val(response.referenere);
	          		$('.paynow_invoice_no').val(response.reference_no);
	          		$('.paynow_amount').val(total_paid);
	          		$('.paynow_amount_payable').val(total_paid);
		          }
	          });
          	$(".paynow_overlay,.paynow_inner").fadeIn();
        });
		$('body').on('keyup', '.paynow_amount', function() {
			 $('.payment_submit').prop("disabled",false);
			 $('.total_msg').hide();
			var total_payable=$('.paynow_amount_payable').val();
			var total=$(this).val();
			if (parseFloat(total) > parseFloat(total_payable)) {
				 $('.payment_submit').prop("disabled",true);
				 $('.total_msg').show();
				 $('.total_msg').html("<div class='alert alert-danger'>Amount is Greater than Total Payable</div>");
			}

        });
		$('body').on('click', '.close_btn,.paynow_overlay', function(e) {
         	 $(".paynow_overlay,.paynow_inner").fadeOut();
        });

        // $(".pay_now").click(function(){
        //     $(".paynow_overlay").fadeIn();
        // });

        </script>
        <script type="text/javascript">
        $('body').on('click', '.main_select', function(e) {
            $('#ledeger_datatable thead>tr>th').unbind('click');
            var check = $('#ledeger_datatable').find('tbody > tr > td:first-child .order_check');
            if ($('.main_select').prop("checked") == true) {
                $('#ledeger_datatable').find('tbody > tr > td:first-child .order_check').prop('checked', true);
            } else {
                $('#ledeger_datatable').find('tbody > tr > td:first-child .order_check').prop('checked', false);
            }

            $('#ledeger_datatable').find('tbody > tr > td:first-child .order_check').val();
        })
        var mydata = [];
        $('body').on('click', '.mbl_report', function(e) {
            e.preventDefault();
            $('.orders_tbl > tbody  > tr').each(function() {
                var checkbox = $(this).find('td:first-child .order_check');
                if (checkbox.prop("checked") == true) {
                    var order_id = $(checkbox).data('id');
                    mydata.push(order_id);
                }
            });
            var order_data = mydata.join(',');

            $('#print_data').val(order_data);
            $('#bulk_submit').submit();
            // location.reload();
        });

        var payData = [];
        $('body').on('click', '.mark_paid', function(e) {
            e.preventDefault();
            $('.orders_tbl > tbody  > tr').each(function() {
                var checkbox = $(this).find('td:first-child .order_check');
                if (checkbox.prop("checked") == true) {
                    var order_id = $(checkbox).data('id');
                    payData.push(order_id);
                }
            });
            var order_data = payData.join(',');
            $('#payment_data').val(order_data);
            $('#bulk_paid').submit();
            // location.reload();
        })
        </script>
        <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            //alert("faisal");
            var dataTable = $('#ledeger_datatable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                // 'scrollCollapse': true,
                // 'ordering': false,
                'responsive': true,
                'pageLength': 10,
                'lengthMenu': [
                    [10, 25, 50, 100, 200, 300],
                    [10, 25, 50, 100, 200, 300]
                ],
                'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
                // dom: '<"html5buttons"B>lTfgitp',
                'buttons': [{
                        extend: 'copy'
                    },
                    {
                        extend: 'csv'
                    },
                    {
                        extend: 'excel',
                        title: 'ExampleFile'
                    },
                    {
                        extend: 'pdf',
                        title: 'ExampleFile'
                    },

                ],
                //'searching': false, // Remove default Search Control
                'ajax': {
                    'url': 'ajax_view_ledeger_payments.php',
                    beforeSend: function() {
                        $('#image').show();
                    },
                    complete: function() {
                        $('#image').hide();
                    },
                    'data': function(data) {
                        var from = $('#from').val();
                        var to = $('#to').val();
                        var cid = $('#cid').val();

                        data.from = from;
                        data.to = to;
                        data.cid = cid

                    }
                },

                'columns': [{
                        data: 'srno'
                    },
                    {
                        data: 'client_code'
                    },
                    {
                        data: 'customer_name'
                    },
                    // {
                    //     data: 'id'
                    // },
                    {
                        data: 'reference_no'
                    },
                    {
                        data: 'payment_date'
                    },
                    {
                        data: 'total_shipments'
                    },
                    {
                        data: 'total_delivered'
                    },
                    {
                        data: 'total_returned'
                    },
                    {
                        data: 'cod_amount'
                    },
                    {
                        data: 'delivery_charges'
                    },
                    {
                        data: 'returned_amount'
                    },
                    {
                        data: 'total_returned_fee'
                    },
                    {
                        data: 'cash_handling'
                    },
                    {
                        data: 'gst_amount'
                    },
                    {
                        data: 'total_sell_flyers'
                    },
                    {
                        data: 'currency'
                    },
                    {
                        data: 'total_paid'
                    },
                    {
                        data: 'total_payable'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'update_able'
                    },
                ]
            });
            $('#submit_order').click(function(e) {
                e.preventDefault();

                dataTable.draw();
            });
        }, false);
        </script>