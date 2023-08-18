<?php
// $curl_handle = curl_init();
// curl_setopt($curl_handle, CURLOPT_URL, 'http://new.leopardscod.com/webservice/getAllCities/format/json/'); // Write here Test or Production Link
// curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($curl_handle, CURLOPT_POST, 1);
// curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode(array(
//     'api_key' => 'E4D3C9DC93166B49C82C1A2D2B4880A1',
//     'api_password' => 'ZOOM1238'
// )));
// curl_setopt($curl_handle, CURLOPT_HTTPHEADER, [
//     'Authorization: E4D3C9DC93166B49C82C1A2D2B4880A1',
//     'Content-Type: application/json'
// ]);
// $buffer = curl_exec($curl_handle);
// curl_close($curl_handle);
// $leopardCities =  json_decode($buffer);
// $leoCities = $leopardCities->city_list;
// // echo "<pre>";
// // print_r($leoCities);
// // die;
// foreach ($leoCities as $key => $leoCity) {
    
//     $leo_city_name = $leoCity->name;
//     $leo_city_id = $leoCity->id;
//     $sql = "SELECT * FROM cities WHERE city_name LIKE '%$leo_city_name%'";
//     $cities_q = mysqli_query($con,$sql);
//     $cities_res = mysqli_fetch_assoc($cities_q);
//     $db_city_name = isset($cities_res['city_name']) ? $cities_res['city_name'] :'';
//     $db_city_id = isset($cities_res['id']) ? $cities_res['id'] :'';
//     if(isset($db_city_id) && !empty($db_city_id)){
//         echo $leoCity->name."<br>";
//         echo $leoCity->id."<br>";
//         mysqli_query($con,"INSERT INTO `city_mapping`(`city_id`, `api_id`, `api_city_id`, `api_city_name`) VALUES ('$db_city_name','Leopards',$leo_city_id,'$leo_city_name')");
//         if(mysqli_error($con)){
//             echo mysqli_error($con)."<br>";
//         }
//     }
// }
$date = date('Y-m-d H:i:s');
$all_branches = mysqli_query($con, "SELECT * from branches WHERE id != '1'");
// echo "<pre>";
// print_r($_SESSION);
$all_customerss = '';
if (isset($_SESSION['branch_id']) && $_SESSION['branch_id'] == 1) {
    $all_customerss = mysqli_query($con, "SELECT * from customers WHERE 1");
} else {
    $all_customerss = mysqli_query($con, "SELECT * from customers WHERE branch_id=" . $_SESSION['branch_id']);
}
$active_date_type = "";
$active_customer = "";
$active_customer_query = "";

$selected_branch = '';
$from_date = date('Y-m-d', strtotime('-30 days'));
$to_date = date('Y-m-d');
$branch_id = 1;
if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
    $branch_id = $_SESSION['branch_id'];
}
if (isset($_POST['submit'])) {
    $date_type = $_POST['date_type'];
    $from_date = date('Y-m-d', strtotime($_POST['from']));
    $to_date = date('Y-m-d', strtotime($_POST['to']));
    $branch_id = isset($_POST['branch_id']) ? $_POST['branch_id'] : 1;
    $active_customer = $_POST['active_customer'];
    $active_date_type = $_POST['date_type'];
    if (isset($_POST['active_customer'])) {
        $active_customer = $_POST['active_customer'];
        if (empty($active_customer) || $_POST['active_customer'] == 'all') {
            $active_customer_query = "";
        } else {
            $active_customer_query = " AND customer_id=" . $active_customer . " ";
        }
    }
}
if (isset($_POST['branch_id']) && $_POST['branch_id'] == 'all') {
    $branch_id = 1;
} else {
    $branch_id = isset($_POST['branch_id']) ? $_POST['branch_id'] : $_SESSION['branch_id'];
}
$where = "";
// echo $branch_id;
// die;
// die;
$allowed_origins = branchAllowedOrigins($branch_id);
if ($branch_id == 1) {
    $where .= " AND ( origin = '$allowed_origins' OR  current_branch IS NULL OR current_branch = 1)";
} elseif (isset($branch_id) && !empty($branch_id) && $branch_id != 1) {
    $where .= " AND  ( origin = '$allowed_origins'  OR current_branch = $branch_id) ";
} else {
    $where = '';
}

$main_query = mysqli_query($con, "SELECT * FROM order_status WHERE 1  ORDER BY order_status.sort_num ");


$pendingPayments = 0;
$collectedPayments = 0;

$query33 = mysqli_query($con, "SELECT sum(price) as collectedPayments FROM orders where  invoice_status='paid'" . $where . $active_customer_query) or die(mysqli_error($con));
$fetch33 = mysqli_fetch_array($query33);
$collectedPayments = $fetch33['collectedPayments'];
// echo
// die($collectedPayments);
$query33 = mysqli_query($con, "SELECT sum(price) as pendingPayments FROM orders where (invoice_status='pending' or invoice_status is null)" . $where) or die(mysqli_error($con));
$fetch33 = mysqli_fetch_array($query33);
$pendingPayments = $fetch33['pendingPayments'];


if (isset($active_date_type) && $active_date_type == 'created_on') {
    $query = mysqli_query($con, "SELECT * from orders WHERE status!='cancelled'  AND DATE_FORMAT(action_date, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(action_date, '%Y-%m-%d') <= '" . $to_date . "' $where $active_customer_query");
    $orderscount = mysqli_affected_rows($con);
} else {
    $query = mysqli_query($con, "SELECT * from orders WHERE status!='cancelled'  AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '" . $to_date . "' $where $active_customer_query");
    $orderscount = mysqli_affected_rows($con);
}
$delayed_order = 0;
if (isset($active_date_type) && $active_date_type == 'created_on') {
    $active_date_type = 'action_date';
    $delayed_q = mysqli_query($con, "SELECT * from orders WHERE status!='cancelled' AND status!='New Booked' AND status!='Delivered' AND status!='Returned to Shipper' AND DATE_FORMAT(action_date, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(action_date, '%Y-%m-%d') <= '" . $to_date . "' $where $active_customer_query");
    while ($row = mysqli_fetch_array($delayed_q)) {
        $action_date = strtotime($row['action_date']);
        $date = strtotime(date('Y-m-d H:i:s'));
        $diff = $date - $action_date;
        $hours = $diff / (60 * 60);
        $order_delat_time = getConfig('order_delayed_time');
        if ($hours >= $order_delat_time) {
            $delayed_order += 1;
        }
    }
} else {
    $active_date_type = 'order_date';
    $delayed_q = mysqli_query($con, "SELECT * from orders WHERE status!='cancelled' AND status!='New Booked' AND status!='Delivered' AND status!='Returned to Shipper'  AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '" . $to_date . "' $where $active_customer_query");
    while ($row = mysqli_fetch_array($delayed_q)) {
        $action_date = strtotime($row['action_date']);
        $date = strtotime(date('Y-m-d H:i:s'));
        $diff = $date - $action_date;
        $hours = $diff / (60 * 60);
        $order_delat_time = getConfig('order_delayed_time');
        if ($hours >= $order_delat_time) {
            $delayed_order += 1;
        }
    }
}

if (isset($branch_id) && !empty($branch_id) && $branch_id != 1) {
    $query = mysqli_query($con, "SELECT * FROM users WHERE type='driver' AND branch_id = " . $branch_id);
    $driverscount = mysqli_affected_rows($con);
    $query = mysqli_query($con, "SELECT * FROM customers WHERE status = 1 AND branch_id = " . $branch_id);
    $customerscount = mysqli_affected_rows($con);
} else {
    $query = mysqli_query($con, "SELECT * from users where type='driver'");
    $driverscount = mysqli_affected_rows($con);
    $query = mysqli_query($con, "SELECT * FROM customers WHERE status = 1");
    $customerscount = mysqli_affected_rows($con);
}



$total_rev_q = mysqli_query($con, "SELECT SUM(price) as total_revenue,SUM(collection_amount) as total_cod FROM orders WHERE DATE_FORMAT(order_date, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '" . $to_date . "' AND status IN('Delivered') $where $active_customer_query");
$total_revenue_res = mysqli_fetch_array($total_rev_q);





?>
<style>
    .dashboard_icons {
        padding: 0 21px;
        background: #f5f5f5 !important;
    }

    .page-header h1 {
        color: #84868e;
        font-weight: 500;
        font-size: 20px;
        margin-top: 11px;
        margin-bottom: 11px;
    }
</style>

<div class="row">
    <div class="row">
        <div class="col-sm-3">
            <form action="<?php echo BASE_URL; ?>track-details.php" target="_blank">
                <input type="text" placeholder="CN Tracking" class="form-control" name="track_code" autocomplete="off">
            </form>
        </div>
        <div class="col-sm-7"></div>
        <div class="col-sm-2 text-right">
            <?php $totacount=mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(id) as total_unread FROM `order_comments` WHERE is_read=0")); ?>
            <a href="comments_report.php"><button class="btn btn-primary">Comments(<?php echo $totacount['total_unread']; ?>)</button></a>

        </div>
    </div>
    <form method="POST" action="">
        <div class="col-sm-2 left_right_none from_to">
            <div class="form-group">
                <label>Date Type</label>
                <select name="date_type" class="form-control">
                    <option <?php if ($active_date_type == 'order_date') {
                        echo "selected";
                    } ?> value="order_date">Order Date</option>
                    <option <?php if ($active_date_type == 'created_on') {
                        echo "selected";
                    } ?> value="created_on">Status Date</option>
                </select>

            </div>
        </div>
        <div class="col-sm-2 left_right_none from_to">
            <div class="form-group">
                <label><?php echo getLange('from'); ?></label>
                <input type="text" value="<?php echo $from_date; ?>" class="form-control datetimepicker4" name="from">
            </div>
        </div>
        <div class="col-sm-2 left_right_none from_to">
            <div class="form-group">
                <label><?php echo getLange('to'); ?></label>
                <input type="text" value="<?php echo $to_date; ?>" class="form-control datetimepicker4" name="to">
            </div>
        </div>

        <?php if ($_SESSION['branch_id'] && $_SESSION['branch_id'] == 1) : ?>
            <div class="col-sm-2 left_right_none">
                <div class="form-group">
                    <label><?php echo getLange('selectbranch'); ?></label>
                    <select class="js-example-basic-single" name="branch_id">
                        <option selected disabled><?php echo getLange('selectbranch'); ?></option>
                        <option value="all" <?php if ($_POST['branch_id'] == 'all') {
                            echo "selected";
                        } ?>>All Branches</option>
                        <?php while ($row = mysqli_fetch_assoc($all_branches)) {

                            ?>
                            <option <?php if ($_POST['branch_id'] == $row['id']) {
                                echo "selected";
                            } ?> value="<?php echo $row['id']; ?>"><?php echo $row['name'] ?></option>

                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php endif ?>

        <!-- <?php if ($_SESSION['branch_id'] && $_SESSION['branch_id'] == 1) : ?> -->
        <div class="col-sm-2 left_right_none">
            <div class="form-group">
                <label><?php echo getLange('customer'); ?></label>
                <select class="js-example-basic-single" name="active_customer">
                    <option selected disabled><?php echo getLange('selectcustomer'); ?></option>
                    <option value="all" <?php if ($_POST['customer_id'] == 'all') {
                        echo "selected";
                    } ?>>All Customers</option>
                    <?php while ($row = mysqli_fetch_assoc($all_customerss)) {

                        ?>
                        <option <?php if ($_POST['active_customer'] == $row['id']) {
                            echo "selected";
                        } ?> value="<?php echo $row['id']; ?>"><?php echo $row['bname'] ?></option>

                    <?php } ?>
                </select>
            </div>
        </div>
        <!-- <?php endif ?> -->
        <div class="col-sm-1 sidegapp-submit search_dashboard_Btn">
            <input type="submit" name="submit" class="btn btn-success" value="<?php echo getLange('search'); ?>">
        </div>
    </form>
</div>
<div class="row icon_panel">
    <div class="col-sm-12 dashboard_icons_box">

        <div class="row">
            <div class="col-sm-10 icon_left">
                <div class="row">
                    <?php
                    if ($type == 'driver') {
                        ?>

                        <?php
                    } else {
                        ?>
                        <?php

                        while ($single = mysqli_fetch_array($main_query)) {
                            $searchQuery = '';
                            if (isset($branch_id) && !empty($branch_id)) {
                                $searchQuery .= " AND (origin = '$allowed_origins' OR current_branch = " . $branch_id . ")";
                            } else {
                                $searchQuery .= " AND (origin = '$allowed_origins' OR current_branch = 1 OR booking_branch = 1)";
                            }
                            $status = $single['status'];
                            if (isset($active_date_type) && $active_date_type == 'created_on') {
                                $countorderquery = mysqli_query($con, "SELECT count(id) as total_count FROM orders WHERE status='" . $status . "' AND DATE_FORMAT(action_date, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(action_date, '%Y-%m-%d') <= '" . $to_date . "' $where $searchQuery $active_customer_query");
                            } else {
                                $countorderquery = mysqli_query($con, "SELECT count(id) as total_count FROM orders WHERE status='" . $status . "' AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '" . $to_date . "' $where $searchQuery $active_customer_query");
                            }


                            $total_res = mysqli_fetch_array($countorderquery);
                            $total_orders = $total_res['total_count'];

                            $color_code = $single['color_code'];

                            $font = '<i class="fa fa-refresh bg-info transit stats-icon" style="background:' . $color_code . ';"></i>';
                            ?>
                            <div class="col-sm-3 dashborad_icon_items">
                                <a
                                href="view_order.php?order_status=<?php echo $single['status']; ?>&from=<?php echo $from_date; ?>&to=<?php echo $to_date; ?>">
                                <div class="panel panel-default clearfix dashboard-stats rounded">
                                    <span id="dashboard-stats-sparkline1" class="sparkline transit"><canvas width="89"
                                        height="60"
                                        style="display: inline-block; width: 89px; height: 60px; vertical-align: top;"></canvas></span>
                                        <?php echo $font; ?>
                                        <h3 class="transit"><?php echo $total_orders; ?></h3>
                                        <p class="text-muted transit"><?php echo getKeyWord($single['status']); ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php }
                        mysqli_data_seek($main_query, 0);
                    }
                    ?>
                    <div class="col-sm-3 dashborad_icon_items">
                        <a
                        href="view_order.php?delayed=<?php echo $active_date_type; ?>&from=<?php echo $from_date; ?>&to=<?php echo $to_date; ?>">
                        <div class="panel panel-default clearfix dashboard-stats rounded"
                        style="background: #d84315;">
                        <span id="dashboard-stats-sparkline1" class="sparkline transit"><canvas width="89"
                            height="60"
                            style="display: inline-block; width: 89px; height: 60px; vertical-align: top;"></canvas></span>
                            <?php echo $font; ?>
                            <h3 class="transit" style="color: #fff"><?php echo $delayed_order; ?></h3>
                            <p class="text-muted transit" style="color: #fff">Delayed Order</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-sm-2 icon_right">
            <div class="row">

                <div class="col-sm-12 dashborad_icon_items">
                    <a href="orders_report.php">
                        <div class="panel panel-default clearfix dashboard-stats rounded">
                            <span id="dashboard-stats-sparkline1" class="sparkline transit"><canvas width="89"
                                height="60"
                                style="display: inline-block; width: 89px; height: 60px; vertical-align: top;"></canvas></span>
                                <i class="fa fa-shopping-cart bg-danger transit stats-icon"></i>
                                <h3 class="transit"><?php echo $orderscount; ?></h3>
                                <p class="text-muted transit"><?php echo getLange('totalorder'); ?> </p>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-12 dashborad_icon_items">
                        <a href="businessacc.php">

                            <div class="panel panel-default clearfix dashboard-stats rounded">
                                <span id="dashboard-stats-sparkline3" class="sparkline transit"><canvas width="89"
                                    height="60"
                                    style="display: inline-block; width: 89px; height: 60px; vertical-align: top;"></canvas></span>
                                    <i class="fa fa-user bg-success transit stats-icon"></i>
                                    <h3 class="transit"><?php echo $customerscount; ?></h3>
                                    <p class="text-muted transit"><?php echo getLange('totalcustomer'); ?> </p>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-12 dashborad_icon_items">
                            <a href="driversdata.php">

                                <div class="panel panel-default clearfix dashboard-stats rounded">
                                    <span id="dashboard-stats-sparkline4" class="sparkline transit"><canvas width="89"
                                        height="60"
                                        style="display: inline-block; width: 89px; height: 60px; vertical-align: top;"></canvas></span>
                                        <i class="fa fa-users bg-warning transit stats-icon"></i>
                                        <h3 class="transit"><?php echo $driverscount; ?></h3>
                                        <p class="text-muted transit"><?php echo getLange('totalriders'); ?> </p>

                                    </div>
                                </a>
                            </div>
                        </div>
                        <?php if (isset($_SESSION['type']) && $_SESSION['type'] == 'admin') { ?>
                            <div class="row">
                                <?php if (isset($_SESSION['type']) && $_SESSION['type'] == 'admin' && $_SESSION['users_id'] == 100) { ?>
                                    <div class="col-sm-12 dashborad_icon_items">
                                        <a href="#">

                                            <div class="panel panel-default clearfix dashboard-stats rounded">
                                                <span id="dashboard-stats-sparkline3" class="sparkline transit"><canvas width="89"
                                                    height="60"
                                                    style="display: inline-block; width: 89px; height: 60px; vertical-align: top;"></canvas></span>
                                                    <i class="fa fa-money bg-warning transit stats-icon"></i>
                                                    <h3 class="transit"><?php echo $currency['value']; ?>
                                                    <?php echo number_format($total_revenue_res['total_revenue'], 2); ?></h3>
                                                    <p class="text-muted transit"><?php echo getLange('totalrevenue'); ?> </p>

                                                </div>
                                            </a>
                                        </div>
                                    <?php   } ?>
                                    <div class="col-sm-12 dashborad_icon_items">
                                        <a href="#">

                                            <div class="panel panel-default clearfix dashboard-stats rounded">
                                                <span id="dashboard-stats-sparkline3" class="sparkline transit"><canvas width="89"
                                                    height="60"
                                                    style="display: inline-block; width: 89px; height: 60px; vertical-align: top;"></canvas></span>
                                                    <i class="fa fa-money bg-warning transit stats-icon"></i>
                                                    <h3 class="transit"><?php echo $currency['value']; ?>
                                                    <?php echo number_format($total_revenue_res['total_cod'], 2); ?></h3>
                                                    <p class="text-muted transit"><?php echo getLange('totalcod'); ?> </p>

                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        <?php } ?>
                        <div class="col-sm-12 order_chart">
                            <div class="panel penel-success">
                                <div class="panel-heading"><?php echo getLange('orderstatistic'); ?> </div>
                                <div class="panel-body" style="height: 546px;
                                padding-top: 100px;">
                                <div class="row charts" style="background-color: #fff;">
                                    <div class="col-sm-12">
                                        <?php while ($single2 = mysqli_fetch_array($main_query)) {
                                            $color_code = $single2['color_code'];
                                            $status = $single2['status'];


                                            if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
                                                $countorderquery = mysqli_query($con, "SELECT count(id) as total_count FROM orders WHERE status='" . $status . "' AND (origin IN ($all_allowed_origins) OR current_branch = " . $_SESSION['branch_id'] . ") AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '" . $to_date . "' $where $active_customer_query");
                                                $total_res = mysqli_fetch_array($countorderquery);
                                                $total_orders = $total_res['total_count'];
                                            } else {
                                                $countorderquery = mysqli_query($con, "SELECT count(id) as total_count FROM orders WHERE status='" . $status . "' AND DATE_FORMAT(order_date, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '" . $to_date . "' $where  $active_customer_query");
                                                $total_res = mysqli_fetch_array($countorderquery);
                                                $total_orders = $total_res['total_count'];
                                            }

                                            $can = $total_orders . '_' . $color_code;
                                            ?>
                                            <input type="hidden" name="<?php echo getKeyWord($single2['status']) ?>"
                                            value="<?php echo $can; ?>" />
                                        <?php } ?>

                                        <canvas id="orderChart"></canvas>
                                    </div>
                        <!-- <div class="col-sm-6">
                        <h3>Payments</h3>
                        <input type="hidden" name="pending" value="<?php echo $pendingPayments . '_#4286f4'; ?>" />
                        <input type="hidden" name="completed" value="<?php echo $collectedPayments . '_#e28118'; ?>" />
                        <canvas id="paymentChart"></canvas>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>


</div>
<?php
if ($type == 'driver') {
} else { ?>
    <div hidden id="map-canvas" style="">
    </div>
    <div hidden id="delivery_map" style="">
    </div>

<?php } ?>


<style>
    a {
        text-decoration: none !important;
    }
</style>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {

        charts();

        function charts() {
        // alert("hello");
        var orderElement = $('#orderChart');
        var paymentElement = $('#paymentChart');
        var labels = [];
        var data = [];
        var colors = [];
        var orders = orderElement.parent().find('input[type="hidden"]');

        orders.each(function(index) {
            labels[index] = $(this).attr('name');
            data[index] = $(this).val().split('_')[0];
            colors[index] = $(this).val().split('_')[1];
        });
        var orderData = {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                hoverBackgroundColor: colors
            }]
        };
        var order = new Chart(orderElement, {
            type: 'pie',
            data: orderData
        });
        var paymentlabels = [];
        var paymentDataSet = [];
        var paymentColors = [];
        var payments = paymentElement.parent().find('input[type="hidden"]');
        payments.each(function(index) {
            paymentlabels[index] = $(this).attr('name');
            paymentDataSet[index] = $(this).val().split('_')[0];
            paymentColors[index] = $(this).val().split('_')[1];
        });
        var paymentData = {
            labels: paymentlabels,
            datasets: [{
                data: paymentDataSet,
                backgroundColor: paymentColors,
                hoverBackgroundColor: paymentColors
            }]
        };
        var payment = new Chart(paymentElement, {
            type: 'pie',
            data: paymentData
        });
    }
}, false);
</script>