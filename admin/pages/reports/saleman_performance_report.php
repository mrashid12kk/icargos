<?php

// $from = isset($_POST['from']) ? $_POST['from'] : date('Y-m-d');
// $to = isset($_POST['to']) ? $_POST['to'] : date('Y-m-d');

// $where = " where orders.order_date>='$from' AND orders.order_date<='$to'";
// $group_by = " GROUP BY orders.customer_id";
// if (isset($_POST['customer']) && $_POST['customer'] != '') {
//     $where .= " AND orders.customer_id=" . $_POST['customer'];
// }
// if (isset($_POST['saleman']) && $_POST['saleman'] != '') {
//     $where .= " AND customers.sale_man_id=" . $_POST['saleman'];
// }

// $where .= $group_by;


?>
<div class="panel panel-default" style="margin-top:0;">
    <?php if (!isset($_GET['print'])) { ?>

        <div class="panel-heading">
            <b style="    padding-top: 6px;
    display: inline-block;">Revenue Report</b>
            <?php
            $active_id = "";
            if (isset($_GET['active_customer'])) {
                $active_id = $_GET['active_customer'];
            }
            ?>
            <!-- <div class="col-sm-2 all_customer_gapp left_right_none" style="float: right;margin-top: 0;">
                <div class="form-group all_business">
                    <select class="form-control active_customer_detail js-example-basic-single" onchange="window.location.href='shipment_report.php?active_customer='+this.value;">
                        <option value="">All Business Accounts</option>
                        <?php foreach ($customers as $customer) { ?>
                            <option <?php if ($customer['id'] == $active_id) {
                                        echo "selected";
                                    } ?> value="<?php echo $customer['id']; ?>"><?php echo $customer['fname'] . (($customer['bname'] != '') ? ' (' . $customer['bname'] . ')' : ''); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div> -->

        </div>


        <div class="panel-heading shipment_report order_box shipment_report_box"></div>
    <?php } ?>
    <div class="panel-body" id="same_form_layout">
        <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

            <div class="row">
                <div class="col-sm-12 table-responsive gap-none bordernone" style="padding:0;">
                    <?php
                    if (!isset($_GET['print'])) {
                    ?>
                        <form method="POST" action="" id="formFilter">
                            <div class="row" style="margin:0;">
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Saleman'); ?></label>
                                        <select class="form-control courier js-example-basic-single" name="saleman" id="saleman">
                                            <option value="">All</option>
                                            <?php
                                            $all_salemans = mysqli_query($con, "SELECT * FROM users where type='admin' and user_role_id !=4");
                                            while ($row = mysqli_fetch_assoc($all_salemans)) { ?>
                                                <option value="<?php echo $row['id']; ?>" <?php echo isset($_POST['saleman']) && $_POST['saleman'] == $row['id'] ? 'selected' : ''; ?>>
                                                    <?php echo $row['Name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Customers'); ?></label>
                                        <select class="form-control courier js-example-basic-single" name="customer" id="customer">
                                            <option value="">All</option>
                                            <?php
                                            $customers = mysqli_query($con, "SELECT * FROM customers WHERE status=1");
                                            while ($row = mysqli_fetch_assoc($customers)) { ?>
                                                <option value="<?php echo $row['id']; ?>" <?php echo isset($_POST['customer']) && $_POST['customer'] == $row['id'] ? 'selected' : ''; ?>>
                                                    <?php echo $row['fname'] . ' (' . $row['bname'] . ')'; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('from'); ?></label>
                                        <input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4" name="from" id="from">
                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('to'); ?></label>
                                        <input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to" id="to">
                                    </div>
                                </div>
                                <div class="col-sm-1 sidegapp-submit left_right_none">
                                    <input type="submit" id="submit_report" name="submit" class="shipment_btn btn btn-info" value="<?php echo getLange('submit'); ?>">
                                </div>
                            </div>
                        </form>
                    <?php
                    } ?>
                    <!-- <?php if (isset($_GET['print'])) { ?>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered no-footer" role="grid">
                        <?php } else { ?> -->
                    <table cellpadding="0" cellspacing="0" border="0" class="saleman_reportt table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
                        <!-- <?php } ?> -->
                        <!-- <div class="fake_loader" id="image" style="text-align: center;">
                                <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;">
                            </div> -->
                        <thead>
                            <tr role="row">
                                <th scope="col">S.No</th>
                                <th scope="col">Sale Representative</th>
                                <th scope="col">Business Name</th>
                                <th scope="col">No. of Parcels</th>
                                <th scope="col">Service Charges</th>
                                <th scope="col">Fuel Surcharge</th>
                                <th scope="col">Total Revenue</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <!-- <tbody id="tabledata"> -->
                        <tfoot>
                            <tr>
                                <td colspan="3" style="background-color: #F5F5F5;">
                                    <?php echo getLange('Total Revenue'); ?></td>
                                <!-- <td class="parcelweight" style="background-color: #b6dde8;"></td>
                                    <td class="codamount" style="background-color: #c2d69a;"></td> -->
                                <td colspan="1" class="total_parcels" style="background-color: #b6dde8;"></td>
                                <td colspan="1" class="total_delivery_price" style="background-color: #c2d69a;"></td>
                                <td colspan="1" class="total_fuel_surcharge" style="background-color: #c2d69a;"></td>
                                <td colspan="1" class="totalprice" style="background-color: #c2d69a;"></td>
                                <td></td>
                               
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>