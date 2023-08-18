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
             if (isset($_GET['detele_id']) && $_GET['detele_id']!='') {
                $id=$_GET['detele_id'];
                $select_sql=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM customer_ledger_payments_detail WHERE id=".$id));    
                $rowcount=mysqli_affected_rows($con);
                if ($rowcount > 0) {
                    $customer_ledger_payments_detail_id=$select_sql['customer_payment_id'];
                    $amount=$select_sql['amount'];
    		    	$sql="DELETE FROM `customer_ledger_payments_detail` WHERE id=".$id;
    		    	$query=mysqli_query($con,$sql);
    		    	$rowcount=mysqli_affected_rows($con);
    		    	if ($rowcount > 0) {
    		    		$select_sql=mysqli_fetch_assoc(mysqli_query($con,"SELECT total_paid FROM customer_ledger_payments WHERE id=".$customer_ledger_payments_detail_id));
    		    		$total_paid_amount=$select_sql['total_paid'] - $amount;
    		    		$update_sql="UPDATE `customer_ledger_payments` SET `total_paid`=".$total_paid_amount.",status='0' WHERE id=".$customer_ledger_payments_detail_id;
    		    		$query=mysqli_query($con,$update_sql);
    			    	$rowcount=mysqli_affected_rows($con);
    			    	if ($rowcount > 0) {
    			    		$_SESSION['update_class']='success';
    		                $_SESSION['update_title']='Success';
    			    		$_SESSION['update_message']="Deleted Successfully";
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
                    <li><a href="ledger_payments.php">Payment</a></li>
                    <li><a class="active" href="ledger_payments_detail.php">Payment Details</a></li>
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
                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('customername'); ?></label>
                                    <select class="form-control js-example-basic-single" name="customer_id" id="customer_id">
                                        <option value="" selected> Select Customer</option>
                                        <?php $customre_q=mysqli_query($con,"SELECT * FROM customers WHERE status=1"); 
                                        while ($customer=mysqli_fetch_array($customre_q)) {?>
                                            <option value="<?php echo $customer['id']; ?>"><?php echo $customer['fname'] . (($customer['bname'] != '') ? ' (' . $customer['bname'] . ')' : ''); ?>
                                            </option>
                                        <?php } ?>
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
                                <button class="btn btn-info mbl_report">MBL Report</button>
                                <table id="ledeger_datatable" cellpadding="0" cellspacing="0" border="0"
                                    class="table table-striped orders_tbl  table-bordered no-footer dtr-inline collapsed"
                                    role="grid" aria-describedby="basic-datatable_info">
                                    <div class="fake_loader" id="image" style="text-align: center;">
                                        <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;">
                                    </div>
                                    <thead>
                                        <tr>
                                            <th><?php echo getLange('srno'); ?>.</th>
                                            <th><?php echo getLange('transactionid'); ?> </th>
                                            <th><?php echo getLange('invoiceno'); ?></th>
                                            <th><?php echo getLange('amount'); ?></th>
                                            <th><?php echo getLange('customername'); ?> </th>
                                            <th><?php echo getLange('User'); ?> </th>
                                            <th><?php echo getLange('paymentdate'); ?> </th>
                                            <th><?php echo getLange('Created On'); ?> </th>
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
        </script>
        <script type="text/javascript">
        
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
                    'url': 'ajax_view_ledeger_payments_detail.php',
                    beforeSend: function() {
                        $('#image').show();
                    },
                    complete: function() {
                        $('#image').hide();
                    },
                    'data': function(data) {
                        var from = $('#from').val();
                        var to = $('#to').val();
                        var customer_id = $('#customer_id').val();

                        data.from = from;
                        data.to = to;
                        data.customer_id = customer_id;

                    }
                },

                'columns': [{
                        data: 'srno'
                    },
                    {
                        data: 'transactionid'
                    },
                    {
                        data: 'invoiceno'
                    },
                    {
                        data: 'amount'
                    },
                    {
                        data: 'customername'
                    },
                    {
                        data: 'User'
                    },
                    {
                        data: 'paymentdate'
                    },
                    {
                        data: 'createdon'
                    },
                    {
                        data: 'action'
                    },
                ]
            });
            $('#submit_order').click(function(e) {
                e.preventDefault();

                dataTable.draw();
            });
        }, false);
        </script>