<?php
$customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
$default_city = getConfig('city');
$filter_query = "";
$active_tracking = "";
$active_customer_name = "";
$active_customer_phone = "";
$active_customer_email = "";
$active_customer_id = "";
$pickup_rider = "";
$delivery_rider = "";
$active_order_status = "";
$active_order_city = "";
if(isset($_POST['submit'])){
        if(isset($_POST['tracking_no']) && !empty($_POST['tracking_no'])){
            $filter_query .= " AND track_no = '".$_POST['tracking_no']."' ";
            $active_tracking = $_POST['tracking_no'];
        }
        if(isset($_POST['customer_name']) && !empty($_POST['customer_name'])){
            $filter_query .= " AND sname = '".$_POST['customer_name']."' ";
            $active_customer_name = $_POST['customer_name'];
        }
        if(isset($_POST['customer_phone']) && !empty($_POST['customer_phone'])){
            $filter_query .= " AND sphone = '".$_POST['customer_phone']."' ";
            $active_customer_phone = $_POST['customer_phone'];
        }
        if(isset($_POST['customer_email']) && !empty($_POST['customer_email'])){
            $filter_query .= " AND semail = '".$_POST['customer_email']."' ";
            $active_customer_email = $_POST['customer_email'];
        }
        if(isset($_POST['active_customer']) && !empty($_POST['active_customer'])){
            $filter_query .= " AND customer_id = '".$_POST['active_customer']."' ";
            $active_customer_id = $_POST['active_customer'];
        }
        if(isset($_POST['pickup_rider']) && !empty($_POST['pickup_rider'])){
            $filter_query .= " AND pickup_rider = '".$_POST['pickup_rider']."' ";
            $pickup_rider = $_POST['pickup_rider'];
        }
        if(isset($_POST['delivery_rider']) && !empty($_POST['delivery_rider'])){
            $filter_query .= " AND delivery_rider = '".$_POST['delivery_rider']."' ";
            $delivery_rider = $_POST['delivery_rider'];
        }
        if(isset($_POST['order_status']) && !empty($_POST['order_status'])){
            $filter_query .= " AND status = '".$_POST['order_status']."' ";
            $active_order_status = $_POST['order_status'];
        }
        if(isset($_POST['order_city']) && !empty($_POST['order_city'])){
            $filter_query .= " AND destination = '".$_POST['order_city']."' ";
            $active_order_city = $_POST['order_city'];
        }
        $from = date('Y-m-d',strtotime($_POST['from']));
        $to = date('Y-m-d',strtotime($_POST['to']));
        $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query order by id desc ");
    }else{
        $from = date('Y-m-d', strtotime('today - 30 days'));
        $to = date('Y-m-d');
        $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query  order by id desc ");
    }
?>
<?php
if(isset($message) && !empty($message)){
    echo $message;
}
$courier_query=mysqli_query($con,"Select * from users where type='driver'");
$delivery_courier_query=mysqli_query($con,"Select * from users where type='driver'");
$status_query=mysqli_query($con,"Select * from order_status where active='1'");
$city_query=mysqli_query($con,"Select * from cities where 1");
$city_querys=mysqli_query($con,"Select * from cities where 1");

 ?>
 <style type="text/css">
    .zones_main{
        margin-bottom: 20px;
    }
    .fake_loader {
    position: absolute;
    z-index: 99;
    right: 0;
    left: 0;
    width: 100%;
    text-align: center;
    background: #fff;
    height: 848px;
    top: 0;
}
.fake_loader img{
        padding-top: 134px;
}
</style>
<div class="panel panel-default">
    <div class="panel-heading">Third-Party Booking Report</div>
    <div class="panel-body" id="same_form_layout">
        <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
            <div class="row">

                <div class="col-sm-12 table-responsive gap-none">

                    <form method="POST" action="">
                            <div class="row">
                                <div class="col-sm-1 left_right_none upate_Btn_box" >
                                    <a href="#" class="btn btn-info print_invoice btn-sm" ><?php echo getLange('printinvoice'); ?> </a>
                                </div>
                              <div class="col-sm-1 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('trackingno'); ?>  </label>
                                        <input type="text" value="<?php echo $active_tracking; ?>" id="tracking_no" class="form-control " name="tracking_no" autocomplete="off">
                                    </div>
                              </div>

                              <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                        <label><?php echo getLange('selectpickupcity'); ?>  </label>
                                        <select class="form-control origin js-example-basic-single" name="origin" id="origin">
                                            <option value="" <?php if($active_origin == ''){ echo "selected"; } ?> >All</option>
                                            <?php while($row = mysqli_fetch_array($city_query)){ ?>
                                            <option  <?php if($active_origin == $row['city_name']){ echo "selected"; } ?>><?php echo $row['city_name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                              </div>

                              <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('selectdeliverycity'); ?>  </label>
                                        <select class="form-control destination js-example-basic-single" name="destination" id="destination">
                                            <option value="" <?php if($active_destination == ''){ echo "selected"; } ?>>All</option>
                                            <?php while($row = mysqli_fetch_array($city_querys)){ ?>
                                            <option <?php if($active_destination == $row['city_name']){ echo "selected"; } ?>><?php echo $row['city_name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                              </div>
                                <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                        <label><?php echo getLange('customer'); ?>  </label>
                                        <select class="form-control origin js-example-basic-single" name="customer_id" id="customer_id">
                                            <option value="" <?php if($customer_id == ''){ echo "selected"; } ?> >All</option>
                                            <?php while($row = mysqli_fetch_array($customers)){ ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($customer_id == $row['id']){ echo "selected"; } ?> ><?php echo $row['bname']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                              </div>
                              <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('customertype'); ?>  </label>
                                        <select class="form-control origin js-example-basic-single" name="customer_type" id="customer_type">
                                            <option value="" <?php if($customer_type == ''){ echo "selected"; } ?> >All</option>
                                            <option value="9" <?php if($customer_type == '0'){ echo "selected"; } ?> >COD</option>
                                            <option value="1" <?php if($customer_type == '1'){ echo "selected"; } ?> >NON COD</option>
                                            <option value="2" <?php if($customer_type == '2'){ echo "selected"; } ?> >Corporate</option>
                                        </select>
                                    </div>
                              </div>
                              <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                        <label><?php echo getLange('paymentstatus'); ?></label>
                                        <select class="form-control origin js-example-basic-single" name="payment_status" id="payment_status">
                                            <option value="" <?php if($payment_status == ''){ echo "selected"; } ?> >All</option>
                                            <option value="Paid" <?php if($payment_status == 'Paid'){ echo "selected"; } ?> >PAID</option>
                                            <option value="Pending" <?php if($payment_status == 'Pending'){ echo "selected"; } ?> >Pending</option>

                                        </select>
                                    </div>
                              </div>

                        </div>
                        <div class="row">

                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('status'); ?>  </label>
                                        <select class="form-control origin js-example-basic-single" name="status" id="status">
                                            <option value="" <?php if($check_status == ''){ echo "selected"; } ?> >All</option>
                                            <?php while($row = mysqli_fetch_array($status_query)){ ?>
                                            <option value="<?php echo $row['status']; ?>" <?php if($check_status == $row['status']){ echo "selected"; } ?> ><?php echo getKeyWord($row['status']); ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                              </div>
                              <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('selectider'); ?> </label>
                                        <select class="form-control courier js-example-basic-single" name="courier" id="courier">
                                            <option value="" <?php if($active_courier == ''){ echo "selected"; } ?>>All</option>
                                            <?php while($row = mysqli_fetch_array($delivery_courier_query)){ ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($active_courier == $row['id']){ echo "selected"; } ?>><?php echo $row['Name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                              </div>
                              <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label>Date Type </label>
                                        <select class="form-control" name="date_type" id="date_type">
                                            <option value="order_date">Order Date</option>
                                            <option value="action_date">Status Date</option>
                                        </select>
                                    </div>
                              </div>
                            <div class="col-sm-1 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('from'); ?></label>
                                    <input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4" name="from"id="from">
                                </div>
                            </div>

                            <div class="col-sm-1 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('to'); ?></label>
                                    <input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to" id="to">
                                </div>
                            </div>
                            <div class="col-sm-1 sidegapp-submit left_right_none">
                                <input type="button" id="submit_order" style="margin-top: 9px;"  name="submit" class="btn btn-info" value="<?php echo getLange('submit'); ?>">
                            </div>
                        </div>
                        </form>

                    <table id="order_datatable" cellpadding="0" cellspacing="0" border="0" class="table table-striped orders_tbl  table-bordered no-footer dtr-inline collapsed" role="grid" aria-describedby="basic-datatable_info">
                        <div class="fake_loader" id="image">
                            <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;">
                        </div>
                        <thead>
                            <tr role="row">
                                    <th><?php echo getLange('srno'); ?> <input type="checkbox" name="" class="main_select"></th>
                                    <th><?php echo getLange('Consignee Name '); ?> </th>
                                    <th><?php echo getLange('Consignee Address'); ?>  </th>
                                    <th><?php echo getLange('Consignee Mobile Number'); ?>  </th>
                                    <th><?php echo getLange('Consignee Email'); ?>  </th>
                                    <th><?php echo getLange('Destination City'); ?></th>
                                    <th><?php echo getLange('Pieces'); ?> </th>
                                    <th><?php echo getLange('Weight'); ?> </th>
                                    <th><?php echo getLange('COD Amount'); ?> </th>
                                    <th><?php echo getLange('Order Reference Number'); ?> </th>
                                    <th><?php echo getLange('Special Handling'); ?> </th>
                                    <th><?php echo getLange('Service Type'); ?> </th>
                                    <th><?php echo getLange('Product Details'); ?> </th>
                                    <th><?php echo getLange('Remarks'); ?> </th>
                                    <th><?php echo getLange('Insurance/Declared Value'); ?> </th>
                                    <!-- <th><?php echo getLange('action'); ?></th> -->
                            </tr>
                        </thead>
                        <!-- <tfoot>
                            <tr>
                                <td colspan="17" style="background-color: #DEDEDE;"> <?php echo getLange('total'); ?></td>
                                
                                <td class="noofpiece" style="background-color: #b6dde8;"></td>
                                <td class="parcelweight" style="background-color: #c2d69a;"></td>
                                <td class="fragile"style="background-color: #b6dde8;"></td>
                                <td class="insureditemdeclare"style="background-color: #c2d69a;"></td>
                                <td class="codamount"style="background-color: #b6dde8;"></td>
                                <td class="deliveryfee"style="background-color: #c2d69a;"></td>
                                <td class="specialcharges"style="background-color: #b6dde8;"></td>
                                <td class="extra_charges"style="background-color: #c2d69a;"></td>
                                <td class="insurancepremium"style="background-color: #b6dde8;"></td>
                                <td class="grand_total_charges"style="background-color: #c2d69a;"></td>
                                <td class="fuelsurcharge"style="background-color: #b6dde8;"style="background-color: #c2d69a;"></td>
                                <td class="salestax"style="background-color: #b6dde8;"></td>
                                <td class="netamount"style="background-color: #c2d69a;"></td>
                                <td class=""style="background-color: #b6dde8;"></td>
                                <td class=""style="background-color: #c2d69a;"></td>
                            </tr>
                        </tfoot> -->
                    </table>
                    <form method="GET" id="bulk_submit" action="../<?php echo getConfig('print_template'); ?>" target="_blank">
                        <input type="hidden" name="order_id" id="print_data" >

                        <input type="hidden" name="save_print">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function(){
        $(document).on('change','.selectDate',function(){
            var dateselecter=$(this).val();
            if(dateselecter== 'specificrange')
            {
                $('.dateselecter').removeClass('hidden');
            } else {
                $('.dateselecter').addClass('hidden');
            }
        })
    }, false);
    document.addEventListener('DOMContentLoaded', function(){
    $(document).ready(function(){
        var dateselecter=$('.selectDate').val();
            if( dateselecter== 'specificrange')
            {
                $('.dateselecter').removeClass('hidden');
            } else {
                $('.dateselecter').addClass('hidden');
            }
        })
    }, false);
</script>
