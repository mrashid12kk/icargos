<?php
// die('ok');
$customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
$default_city = getConfig('city');
$filter_query = "";
$active_acc_grp_name = "";
$active_ledger_name = "";
$active_voucher_type = "";
$active_customer_email = "";

if(isset($_POST['submit']))
{
    // if(isset($_POST['account_group_name']) && !empty($_POST['account_group_name'])){
    //     $filter_query .= " AND accountGroupId = '".$_POST['account_group_name']."' ";
    //     $active_acc_grp_name = $_POST['account_group_name'];
    // }
    // if(isset($_POST['ledger_name']) && !empty($_POST['ledger_name'])){
    //     $filter_query .= " AND ledgerName = '".$_POST['ledger_name']."' ";
    //     $active_ledger_name = $_POST['ledger_name'];
    // }
    // if(isset($_POST['voucher_type']) && !empty($_POST['voucher_type'])){
    //     $filter_query .= " AND voucherTypeName = '".$_POST['voucher_type']."' ";
    //     $active_voucher_type = $_POST['voucher_type'];
    // }
    // if(isset($_POST['customer_email']) && !empty($_POST['customer_email'])){
    //     $filter_query .= " AND semail = '".$_POST['customer_email']."' ";
    //     $active_customer_email = $_POST['customer_email'];
    // }
    // if(isset($_POST['active_customer']) && !empty($_POST['active_customer'])){
    //     $filter_query .= " AND customer_id = '".$_POST['active_customer']."' ";
    //     $active_customer_id = $_POST['active_customer'];
    // }
  
    // $from = date('Y-m-d',strtotime($_POST['from']));
    // $to = date('Y-m-d',strtotime($_POST['to']));
    // $query1 = mysqli_query($con,"SELECT * FROM tbl_accountledger WHERE DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '".$to."'   $filter_query order by id desc ");
    // $query1 = mysqli_query($con,"SELECT * FROM tbl_accountledger WHERE 1 $filter_query order by id desc ");

    // echo "<pre>";
   
    // // $data = mysqli_fetch_all($query1,MYSQLI_ASSOC);
    // print_r($query1->num_rows);
    // exit();

}



?>
<?php
if(isset($message) && !empty($message)){
    echo $message;
}
$courier_query=mysqli_query($con,"Select * from users where type='driver'");
$delivery_courier_query=mysqli_query($con,"Select * from users where type='driver'");
$status_query=mysqli_query($con,"Select * from order_status where active='1'");
$acc_grp_query=mysqli_query($con,"Select * from tbl_accountgroup where 1");
$acc_ledger_query=mysqli_query($con,"Select * from tbl_accountledger where 1");
$voucher_type_query =mysqli_query($con,"Select * from tbl_vouchertype where 1");


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
    <div class="panel-heading"> Ledger Report</div>
    <div class="panel-body" id="same_form_layout">
        <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
            <div class="row">

                <div class="col-sm-12 table-responsive gap-none">

                    <form method="POST" action="">
                        <div class="row">
                         
                        <div class="col-sm-2 left_right_none">
                            <div class="form-group">
                                <label> Account Group </label>
                                <select class="form-control origin js-example-basic-single" name="account_group_name" id="acc_group_name">
                                    <option value="all" <?php if($active_acc_grp_name == ''){ echo "selected"; } ?> >All</option>
                                    <?php while($row = mysqli_fetch_array($acc_grp_query)){ ?>
                                        <option <?= ($_GET['acc_grp_id'] == $row['id']) ? 'selected' : ''; ?>
                                        value="<?php echo $row['id']; ?>">
                                            <?php echo $row['accountGroupName']; ?>    
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2 left_right_none">
                            <div class="form-group">
                                <label>Account Ledger</label>
                                <select class="form-control destination js-example-basic-single" name="ledger_name" id="ledger_name">
                                    <option value="all" <?php if($active_ledger_name == ''){ echo "selected"; } ?>>All</option>
                                    <?php while($row = mysqli_fetch_array($acc_ledger_query)){ ?>
                                        <option value="<?= $row['id']; ?>">
                                            <?php echo $row['ledgerName']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-sm-2 left_right_none">
                            <div class="form-group">
                                <label>Voucher Type</label>
                                <select class="form-control js-example-basic-single" name="voucher_type" id="voucher_type">
                                    <option value="all" <?php if($active_voucher_type == ''){ echo "selected"; } ?>>All</option>
                                    <?php while($row = mysqli_fetch_array($voucher_type_query)){ ?>
                                        <option <?= ($_GET['voucher_type_id'] == $row['voucherTypeId']) ? 'selected' : ''; ?>
                                         value="<?= $row['voucherTypeId']; ?>">
                                            <?php echo $row['voucherTypeName']; ?>    
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2 left_right_none">
                            <div class="form-group">
                                <label> Date Range</label>
                                <select class="form-control" name="date_range" id="date_range">
                                    <option <?= ($_GET['date_range'] == 'today') ? 'selected' : '' ?> value="today">Today</option>
                                    <option <?= ($_GET['date_range'] == 'last30Days') ? 'selected' : '' ?> value="last30Days">Last 30 days</option>
                                    <option <?= ($_GET['date_range'] == 'last15Days') ? 'selected' : '' ?> value="last15Days" >Last 15 days</option>
                                   
                                    <option <?= ($_GET['date_range'] == 'specific') ? 'selected' : '' ?> value="specific">Specific Range</option>

                                </select>
                            </div>
                            
                        </div>
                        <div  class="col-sm-2 side_gapp date-selector" style="<?= ($_GET['date_range'] == 'specific') ? 'display:block' : 'display:none';  ?>">
                            <label>Start Date</label>
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control datepicker" name="from" id="from" value="<?= isset($_GET['from']) ? $_GET['from'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-sm-2 side_gapp  date-selector" style="<?= ($_GET['date_range'] == 'specific') ? 'display:block' : 'display:none';  ?>">
                            <label>End Date</label>
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control datepicker" id="to" name="to" value="<?= isset($_GET['to']) ? $_GET['to'] : ''; ?>">
                            </div>
                        </div>

                        <div class="col-sm-1 sidegapp-submit left_right_none">
                            <input type="button" id="submit_order" style="margin-top: 9px;" class="btn btn-info" value="<?php echo getLange('submit'); ?>">
                        </div>

                    </div>
                   
                    
                    <div class="progress" id="csv_progress" style="display:none;">
                      <div class="progress-bar" role="progressbar" aria-valuenow="70"
                      aria-valuemin="0" aria-valuemax="100" style="width:0%">
                        <span class="sr-only">70% Complete</span>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 left_right_none upate_Btn_box" >
                            <a href="#" class="btn btn-info print_invoice btn-sm" ><?php echo getLange('printinvoice'); ?> </a>
                        </div>
                       <!--  <div class="col-sm-2 left_right_none upate_Btn_box" >
                            <a href="#" class="btn btn-info lable_print_hor btn-sm" >Label Print (Horizontal) </a>
                        </div>
                        <div class="col-sm-2 left_right_none upate_Btn_box" >
                            <a href="#" class="btn btn-info lable_print_ver btn-sm" >Label Print (Vertical)</a>
                        </div> -->
                    </div>
                </form>
                <table id="order_datatable" cellpadding="0" cellspacing="0" border="0" class="table table-striped orders_tbl  table-bordered no-footer dtr-inline collapsed" role="grid" aria-describedby="basic-datatable_info">
                    <div class="fake_loader" id="image">
                        <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;">
                    </div>
                    <thead>
                        <tr role="row">
                            <th><input type="checkbox" name="" class="main_select"></th>
                            <th> Ledger Id </th>
                            <th> Ledger Code </th>
                            <th> Ledger Name </th>
                            <th> Account Group  </th>
                            <th> Mobile/SMS no</th>
                            <th> Phone </th>
                            <th> CNIC </th>
                            <th> City </th>
                            <th> Address </th>
                            <th> Debit </th>
                            <th> Credit </th>
                            <th> Closing Balance </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                  
                    <!-- <tfoot>
                        <tr>
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
                <form method="GET" id="bulk_submit_hor" action="../small_bulk_invoice.php" target="_blank">
                    <input type="hidden" name="print_data" id="print_data_hor" >

                    <input type="hidden" name="save_print">
                </form>
                <form method="GET" id="bulk_submit_ver" action="../receipt_invoice.php" target="_blank">
                    <input type="hidden" name="order_id" id="print_data_ver" >
                    <input type="hidden" name="print" value="1">
                    <input type="hidden" name="booking" value="1">
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">

      $(document).ready(function () {

        $(document).on('change','#date_range',function(){
            var dateselecter=$(this).val();
            if(dateselecter == 'specific')
            {
                $('.date-selector').show();
            } else {
                $('.date-selector').hide();
            }
        })


   });
        



    // document.addEventListener('DOMContentLoaded', function(){
    //     $(document).ready(function(){
    //         var dateselecter=$('.selectDate').val();
    //         if( dateselecter== 'specificrange')
    //         {
    //             $('.dateselecter').removeClass('hidden');
    //         } else {
    //             $('.dateselecter').addClass('hidden');
    //         }
    //     })
    // }, false);
</script>
