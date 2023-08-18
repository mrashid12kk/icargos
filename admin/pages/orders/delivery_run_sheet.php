<style type="text/css">
.zones_main {
    margin-bottom: 20px;
}

.panel-default>.panel-heading {
    color: #333 !important;
    background-color: #f5f5f5 !important;
    border-color: #ddd !important;

}

.panel-default>.panel-heading a {
    font-weight: bold !important;
}
</style>
<?php
$branch_id = $_SESSION['branch_id'];
$creatd_by_query = '';
if (isset($_SESSION['branch_id'])) {
    $courier_query = mysqli_query($con, "SELECT * FROM users WHERE  user_role_id = 3 or user_role_id = 4 AND type='driver'  AND branch_id = $branch_id  ");

    $creatd_by_query = mysqli_query($con, "SELECT * FROM users WHERE (type ='admin' OR type ='manager') AND branch_id = $branch_id");
} else {
    $courier_query = mysqli_query($con, "SELECT * FROM users WHERE ( user_role_id = 3 OR user_role_id = 4 ) AND type='driver' AND (branch_id IS NULL OR branch_id=1)  ");

    $creatd_by_query = mysqli_query($con, "SELECT * FROM users WHERE (type ='admin' OR type ='manager') AND (branch_id = 1 OR branch_id IS NULL) ");
}
// $origin = LAHORE;
// if (in_array($origin, $all_allowed_origins_array)) {
// 	echo "Got It";
// }else{
// 	echo 'Sorry';
// }
// die();
// echo $current_branch_city;
$zone_query    = mysqli_query($con, "SELECT * from zone where 1");

if (isset($_SESSION['branch_id'])) {
    $branch_query = mysqli_query($con, "SELECT * from branches where id !=" . $current_branch_id);
} else {
    $branch_query = mysqli_query($con, "SELECT * from branches where id != 1");
}
// echo "Select * from branches where id !=".$current_branch_id;
// die;
$destination_zone_q = mysqli_query($con, " SELECT city_name as destination FROM cities WHERE 1 ");


$destination = '';
$destination_q = '';
$customer_q = '';
if (isset($_GET['destination']) && !empty($_GET['destination'])) {
    $destination = $_GET['destination'];
    $destination_q = ' AND destination = "' . $destination . '" ';
    $customer_q =  ' AND o.destination = "' . $destination . '" ';
}


/////Filter Data
$delivery_zone_q = mysqli_query($con, " SELECT * FROM delivery_zone WHERE 1 ");
$filter_destination_q = '';
$filter_destination   = '';
if (isset($_GET['filter_destination']) && !empty($_GET['filter_destination'])) {
    $filter_destination = $_GET['filter_destination'];
    $filter_destination_q =  ' AND o.destination = "' . $filter_destination . '" ';
    $filter_destination_q_o =  ' AND destination = "' . $filter_destination . '" ';
}


$delivery_zone_number_q = '';
$delivery_zone_number_o = '';
$delivery_zone_number   = '';
if (isset($_GET['delivery_zone_number']) && !empty($_GET['delivery_zone_number'])) {
    $delivery_zone_number   =  $_GET['delivery_zone_number'];
    $delivery_zone_number_q =  ' AND o.delivery_zone_id = "' . $delivery_zone_number . '" ';
    $delivery_zone_number_o =  ' AND delivery_zone_id = "' . $delivery_zone_number . '" ';
}
/////////////////////////////

$current_branch_query = '';
if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
    $current_branch_query = " AND o.current_branch = " . $_SESSION['branch_id'];
} else {
    $current_branch_query = " AND (o.current_branch = 1 OR o.current_branch IS NULL) ";
}



$customer_fetch_q = mysqli_query($con, "SELECT  cus.id as customer_id,cus.fname as business, cus.bname as business_name FROM orders o INNER JOIN customers cus ON (o.customer_id = cus.id) WHERE (o.status='Parcel Received at office' OR o.status='Parcel Received at Destination') $current_branch_query $customer_q $filter_destination_q $delivery_zone_number_q   GROUP BY cus.id  ");

?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo getLange('deliveryrunsheet'); ?> </div>
    <div class="panel-body" id="same_form_layout">
        <div class="col-sm-12">
            <div id="basic-datatable_wrapper"
                class="pickuprun_sheet dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                <div class="row" id="delivery_run">
                    <div class="alert alert-success"><button type="button" class="close"
                            data-dismiss="alert">X</button>This page will show "Receive at Office" status orders of
                        origin branch and "Parcel Received at Destination" status of for currently assigned branch.
                    </div>
                    <form method="GET" action="">
                        <div class="row">
                            <div class="col-sm-3 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('selectdestination'); ?> </label>
                                    <select class="form-control courier_list js-example-basic-single"
                                        name="filter_destination">
                                        <option selected value="">
                                            <?php echo getLange('select') . ' ' . getLange('destination'); ?></option>
                                        <?php while ($row = mysqli_fetch_array($destination_zone_q)) { ?>
                                        <option <?php if ($row['destination'] == $filter_destination) {
                                                        echo "selected";
                                                    } ?> value="<?php echo $row['destination']; ?>">
                                            <?php echo $row['destination']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('selectdeliveryzone'); ?> </label>
                                    <select class="form-control deliveryZone js-example-basic-single"
                                        name="delivery_zone_number">
                                        <option selected value="">
                                            <?php echo getLange('select') . ' ' . getLange('deliveryzone'); ?></option>
                                        <?php while ($row = mysqli_fetch_array($delivery_zone_q)) { ?>
                                        <option <?php if ($row['route_code'] == $delivery_zone_number) {
                                                        echo "selected";
                                                    } ?> value="<?php echo $row['route_code']; ?>">
                                            <?php echo $row['route_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>


                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <input type="submit" style="margin-top: 25px;" name="filter"
                                        value="<?php echo getLange('filter'); ?>" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>

                    <br>
                    <form method="POST" action="bulk_delivery_assign.php" id="bulk_submit">
                        <input type="hidden" name="filter_destination"
                            value="<?php echo isset($filter_destination) ? $filter_destination : ''; ?>">
                        <div class="col-sm-3 left_right_none">
                            <div class="form-group">
                                <label><?php echo getLange('selecttoassign'); ?> </label>
                                <select class="form-control select_to_assign js-example-basic-single">
                                    <option value="1" selected><?php echo getLange('assigndeliverridervenderzone'); ?>
                                    </option>
                                    <option value="2"><?php echo getLange('assign') . ' ' . getLange('branch'); ?>
                                    </option>

                                </select>
                            </div>
                        </div>


                        <div class="col-sm-3 left_right_none rider_assign">
                            <div class="form-group">
                                <label><?php echo getLange('assigndeliverridervenderzone'); ?> </label>
                                <select class="form-control courier_list js-example-basic-single" name="active_courier">
                                    <option selected disabled>
                                        <?php echo getLange('selectdelivery') . ' (' . getLange('rider') . '/' . getLange('vendor') . ')'; ?>
                                    </option>
                                    <?php mysqli_data_seek($courier_query, 0);
                                    while ($row = mysqli_fetch_array($courier_query)) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3 left_right_none branch_assign hidden ">
                            <div class="form-group">
                                <label><?php echo getLange('assignbranch'); ?> </label>
                                <select class="form-control courier_list js-example-basic-single" name="assign_branch">
                                    <option selected disabled>Select Branch</option>
                                    <?php while ($row = mysqli_fetch_array($branch_query)) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>




                        <input type="hidden" name="order_ids" id="print_data">
                        <input type="hidden" class="cls_delivery_zone" name="delivery_zone_number"
                            value="<?php echo isset($_GET['delivery_zone_number']) ? $_GET['delivery_zone_number'] : ''; ?>">
                        <div class="col-sm-1 left_right_none upate_Btn">
                            <a href="#" class="update_status btn btn-success"
                                style="margin-top: 7px;"><?php echo getLange('assign'); ?></a>
                        </div>
                </div>
                </form>
                <div class="row">
                    <div class="col-sm-12 table-responsive gap-none">
                        <div class="panel-group" id="faqAccordion">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label><input type="checkbox" class="select_all" name="">
                                        <?php echo getLange('selectallorder'); ?> </label>
                                </div>
                            </div>
                            <?php
                            $row_start = 0;
                            while ($single = mysqli_fetch_array($customer_fetch_q)) {
                                $customer_id = $single['customer_id'];

                                $current_branch_query = '';
                                if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
                                    $current_branch_query = " AND current_branch = " . $_SESSION['branch_id'];
                                } else {
                                    $current_branch_query = " AND (current_branch = 1 OR current_branch IS NULL) ";
                                }

                                $order_query = mysqli_query($con, "SELECT id,rname,receiver_address,track_no,destination,rphone FROM orders WHERE customer_id='" . $customer_id . "' $current_branch_query AND (status='Parcel Received at office' OR status='Parcel Received at Destination') $filter_destination_q_o $delivery_zone_number_o  ORDER BY id DESC ");



                            ?>
                            <div class="panel panel-default ">
                                <div class="panel-heading " data-target="#question<?php echo $row_start; ?>">
                                    <h4 class="panel-title">
                                        <a href="#"
                                            class="ing"><?php echo $single['business'] . ' ( ' . $single['business_name'] . ' )'; ?></a>
                                    </h4>
                                </div>
                                <!-- <div id="question<?php echo $row_start; ?>" class="panel-collapse collapse" style="height: 0px;"> -->
                                <div class="panel-body">
                                    <table cellpadding="0" cellspacing="0" border="0"
                                        class="table table-striped table-bordered dataTable no-footer pickup_tbl"
                                        id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
                                        <thead>
                                            <tr>
                                                <th style="width: 20px;"><input type="checkbox" name=""
                                                        class="main_select"></th>
                                                <th style="width: 100px;"><?php echo getLange('tracking'); ?></th>
                                                <th style="width: 100px;"><?php echo getLange('receivername'); ?> </th>
                                                <th style="width: 100px;"><?php echo getLange('receiverphone'); ?> </th>
                                                <th style="width: 100px;">Destinaion City</th>
                                                <th style="width: auto !important;"><?php echo getLange('receiver'); ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($order_row = mysqli_fetch_array($order_query)) { ?>
                                            <tr>
                                                <td><input type="checkbox" class="order_check"
                                                        data-id="<?php echo $order_row['track_no']; ?>" name=""></td>
                                                <td><?php echo $order_row['track_no']; ?></td>
                                                <td><?php echo $order_row['rname']; ?></td>
                                                <td><?php echo $order_row['rphone']; ?></td>
                                                <td><?php echo $order_row['destination']; ?></td>
                                                <td><?php echo $order_row['receiver_address']; ?></td>

                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php $row_start++;
                            } ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    $(document).on('change', '.status_list', function() {
        if ($(this).find(':selected').attr('data-reasonenable') == 1) {
            $('.enable_reason').show();
            $('.receive').removeClass('hidden');
        } else {
            $('.enable_reason').hide();
            $('.receive').addClass('hidden');
        }
    })
}, false);
document.addEventListener('DOMContentLoaded', function() {
    $(document).on('change', '.select_to_assign', function() {
        var id = $(this).val();
        if (id == '2') {
            $('.branch_assign').removeClass('hidden');
            $('.rider_assign').addClass('hidden');
            // $('.receive').show();
        } else if (id === '1') {
            $('.branch_assign').addClass('hidden');
            $('.rider_assign').removeClass('hidden');
        }
    })
}, false);
document.addEventListener('DOMContentLoaded', function() {
    $(document).on('click', '.edit_weight', function() {
        var weight = $(this).attr("data-id");
        var track_no = $(this).attr('data-trackno');
        console.log(track_no);
        $(".edituserweight").val(weight);
        $(".editusertrackno").val(track_no);
    })
}, false);
document.addEventListener('DOMContentLoaded', function() {
    $(document).on('keyup', '.weight', function() {
        var weight = $(this).val();
        if (weight == '') {
            $('.viewcharges').addClass('hidden');
            // $('.receive').show();
        } else {
            $('.viewcharges').removeClass('hidden');
        }
    })
}, false);
</script>