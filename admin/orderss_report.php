<?php
    session_start();
 //    if(empty($_SESSION['branch_id']) ){
 //     header("Location:index.php");
    // }
    // echo "<pre>";
    // print_r($_SERVER);
    // die();
        require 'includes/conn.php';
        require 'includes/functions.php';
    if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
         require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],20,'view_only',$comment =null)) {

        header("location:access_denied.php");
    }
        include "includes/header.php";
        $cities1 = mysqli_query($con,"SELECT * FROM cities WHERE 1 ");
        $cities2 = mysqli_query($con,"SELECT * FROM cities WHERE 1 order by id desc ");
        $drivers = mysqli_query($con,"SELECT * FROM users WHERE type='driver' order by id desc ");
        $customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");

        $active_customer_query = "";
        if(isset($_GET['active_customer'])){
            $active_customer = $_GET['active_customer'];
            if(empty($active_customer)){
                $active_customer_query = "";
            }else{
                $active_customer_query = " AND customer_id=".$active_customer." ";
            }
        }
?>
<body data-ng-app>
<style>
    .row{
        margin: 0 !important;
    }
    .table-responsive{
        padding: 0 !important;
    }
    .col-md-2{
            padding-right: 20px !important;
    }
    .select2-container ,.form-control{
    margin: 0;
    width: 97% !important;
}
.select2-container--default .select2-selection--single {
    border: 1px solid #ccc !important;
}
.select2-container .select2-selection--single {
    height: 34px !important;
}
.buttons-print,.buttons-pdf{
    display: none !important;
}
</style>



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

            <?php
            $active_origin = '';
            $active_destination = '';
            $active_courier = '';
            $customer_id='';
            $check_status='';
            $customer_type='';
            $payment_status='';
    if(isset($_POST['submit']))
    {
        // echo '<pre>';
        // print_r($_POST);
        // die;
        $active_status = '';
        $from = date('Y-m-d',strtotime($_POST['from']));
        $to = date('Y-m-d',strtotime($_POST['to']));
        $origin = $_POST['origin'];
        $destination = $_POST['destination'];
        $courier = $_POST['courier'];
        $customer_id = $_POST['customer_id'];
        $status = $_POST['status'];
        $origin_check = '';
        $destination_check = '';
        $active_tracking='';
         $customer_type_get='';
         $payment_status_check='';
        if(isset($_POST['tracking_no']) && !empty($_POST['tracking_no'])){
            $query1 = mysqli_query($con,"SELECT * FROM orders WHERE track_no = '".$_POST['tracking_no']."' ");
            $active_tracking = $_POST['tracking_no'];
        }else{
            if(!empty($_POST['origin'])){
                $origin_check = " AND origin = '{$origin}' ";
                 $active_origin = $origin;
            }
             if(!empty($_POST['customer_id'])){
                $customer_id_check = " AND customer_id = '{$customer_id}' ";
                 $customer_id = $customer_id;
            }
             if(!empty($_POST['status'])){
                $qstatus = " AND status = '{$status}' ";
                 $check_status =  $status;
            }
            if(!empty($_POST['destination'])){
                $destination_check = " AND destination = '{$destination}' ";
                $active_destination = $destination;
            }
            if(!empty($_POST['courier'])){
                $destination_check = " AND assign_driver = '{$courier}' ";
                $active_courier = $courier;
            }

            if($active_status == '')
            {
                $stat_status = 'All';
            }else{
                $stat_status = $active_status;
            }
            if(!empty($_POST['status']))
            {
                $status = $_POST['status'];
                $status_check = " AND status = '".$status."' ";
                $active_status = $status;
            }
            if(!empty($_POST['payment_status']))
            {
                $payment_status = $_POST['payment_status'];
                $payment_status_check = " AND orders.payment_status = '".$payment_status."' ";
            }
            if (!empty($_POST['customer_type'])) {
                if($_POST['customer_type']=='9'){
                    $customer_type='0';
                }
                else{
                    $customer_type=$_POST['customer_type'];
                }
                $customer_type_get = " AND customers.customer_type = '".$customer_type."' ";
            }
            $query1 = mysqli_query($con,"SELECT orders.*, services.service_type as order_type_name,customers.bname as businessname,customers.customer_type FROM orders  LEFT JOIN  services ON orders.order_type=services.id inner join customers on orders.customer_id=customers.id WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' AND  orders.status != 'cancelled' $origin_check $destination_check $status_check $active_customer_query $payment_status_check $customer_type_get $customer_id_check $qstatus order by orders.id desc ");
        }

    }else{
        $from = date('Y-m-d', strtotime('today - 30 days'));
        $to = date('Y-m-d');
        $query1 = mysqli_query($con,"SELECT orders.*, services.service_type as order_type_name,customers.bname as businessname,customers.customer_type FROM orders   LEFT JOIN  services ON orders.order_type=services.id inner join customers on orders.customer_id=customers.id  WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '" .$to. "'  AND  orders.status != 'cancelled'  $active_customer_query order by orders.id desc ");



    }
 ?>
<?php

    if(isset($_POST['verified'])){

        $order_id=mysqli_real_escape_string($con,$_POST['order_id']);

        $deliver_id=mysqli_real_escape_string($con,$_POST['deliver_id']);

        $query1=mysqli_query($con,"update orders,deliver set orders.status='completed',deliver.status='completed' where orders.id=$order_id and deliver.id=$deliver_id") or die(mysqli_error($con));

        $rowscount=mysqli_affected_rows($con);

        // die($deliver_id.$order_id);

        if($rowscount>0){

            // Send a notification to client

            $query = mysqli_query($con, "SELECT * FROM orders WHERE id = $order_id");

            $data = mysqli_fetch_array($query);

            $data['email'] = $data['semail'];

            $data['phone'] = $data['sphone'];

            $data['name'] = $data['sname'];

            if($data['email'] == '' && $data['customer_id'] > 0) {

                $customerID = $data['customer_id'];

                $query1 = mysqli_query($con, "SELECT email FROM customers WHERE id = $customerID");

                $customer = mysqli_fetch_array($query1);

                $data['email'] = $customer['email'];

                $data['name'] = $customer['bname'];

            }

            $message['subject'] = 'Order Delivery';

            $message['body'] = '<p>Order has been delivered successfully at '.$data['receiver_address'].'</p>';

            $message['alt_body'] = 'Order has been delivered successfully at '.$data['receiver_address'];

            sendEmail($data, $message);

            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have successfully mark order as Verified .</div>';

        }

        else{

            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not mark the order as Verified.</div>';

        }

    }

?>

    <div class="row">
                <div class="col-sm-6" style="padding: 7px 0 0;">

                </div>
                <div class="col-sm-6" style="padding: 0;">
                        <?php
                       $active_id = "";
                       if(isset($_GET['active_customer'])){
                        $active_id = $_GET['active_customer'];
                       }
                        ?>
                                         <div class="col-sm-4 all_customer_gapp left_right_none" style="    margin-bottom: 2px;margin-top:0;float: right;">
                         <div class="form-group" style="margin-bottom: 6px;">
                        <select class="form-control active_customer_detail js-example-basic-single" onchange="window.location.href='orders_report.php?active_customer='+this.value;">
                            <option value="">All Business Accounts</option>
                            <?php foreach($customers as $customer){ ?>
                                <option  <?php if($customer['id'] == $active_id ){ echo "selected"; } ?> value="<?php echo $customer['id']; ?>"><?php echo $customer['fname'].(($customer['bname'] != '') ? ' ('.$customer['bname'].')' : ''); ?></option>
                            <?php } ?>
                        </select>
                       </div>
                      </div>
                </div>
            </div>





            <?php

            include "pages/reports/order_report.php";

            ?>


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

            $('body').on('click','.main_select',function(e){
        var check = $('.orders_tbl').find('tbody > tr > td:first-child .order_check');
        if($('.main_select').prop("checked") == true){
            $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked',true);
        }else{
            $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked',false);
        }

        $('.orders_tbl').find('tbody > tr > td:first-child .order_check').val();
    })
            var mydata = [];
            $('body').on('click','.print_invoice',function(e){
        e.preventDefault();
        $('.orders_tbl > tbody  > tr').each(function() {
            var checkbox = $(this).find('td:first-child .order_check');
            if(checkbox.prop("checked") ==true){
                var order_id = $(checkbox).data('id');
                mydata.push(order_id);
            }
        });
        var order_data = mydata.join(',');

        $('#print_data').val(order_data);
        $('#bulk_submit').submit();
        location.reload();
    })
        </script>
