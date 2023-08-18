<?php
$active_customer = "";
$active_customer_query = "";
if (isset($_GET['active_customer'])) {
	$active_customer = $_GET['active_customer'];
	if (empty($active_customer)) {
		$active_customer_query = "";
	} else {
		$active_customer_query = " AND customer_id=" . $active_customer . " ";
	}
}
$from_date = date('Y-m-01');
$to_date = date('Y-m-t');
if (isset($_GET['submit'])) {
	$from_date = date('Y-m-d', strtotime($_GET['from']));
	$to_date = date('Y-m-d', strtotime($_GET['to']));
}

$where = "";
if (isset($_SESSION['type']) && $_SESSION['type'] == 'branch') {
	$branch_id = $_SESSION['branch_id'];
	$where .= " AND branch_id='" . $branch_id . "' ";
}
$main_query = mysqli_query($con, "SELECT * FROM order_status WHERE 1  ORDER BY order_status.sort_num ");


$pendingPayments = 0;
$collectedPayments = 0;

$query33 = mysqli_query($con, "SELECT sum(price) as collectedPayments FROM orders where  invoice_status='paid'" . $where) or die(mysqli_error($con));
$fetch33 = mysqli_fetch_array($query33);
$collectedPayments = $fetch33['collectedPayments'];
// echo 
// die($collectedPayments);
$query33 = mysqli_query($con, "SELECT sum(price) as pendingPayments FROM orders where (invoice_status='pending' or invoice_status is null)" . $where) or die(mysqli_error($con));
$fetch33 = mysqli_fetch_array($query33);
$pendingPayments = $fetch33['pendingPayments'];

$query = mysqli_query($con, "select * from orders WHERE status!='cancelled'  AND DATE_FORMAT(action_date, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(action_date, '%Y-%m-%d') <= '" . $to_date . "' $where ");
$orderscount = mysqli_affected_rows($con);


$query = mysqli_query($con, "select * from customers WHERE status = 1");
$customerscount = mysqli_affected_rows($con);
$query = mysqli_query($con, "select * from users where type='driver'");
$driverscount = mysqli_affected_rows($con);
/*  $query = mysqli_query($con, "SELECT * FROM orders");
    while($row = mysqli_fetch_array($query)) {
        $pendingPayments += ($row['collection_amount'] - $row['payment_amount']);
        $collectedPayments += $row['payment_amount'];
    } */
$total_rev_q = mysqli_query($con, "SELECT SUM(price) as total_revenue,SUM(collection_amount) as total_cod FROM orders WHERE DATE_FORMAT(order_date, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(order_date, '%Y-%m-%d') <= '" . $to_date . "' AND status IN('Delivered') $where ");
$total_revenue_res = mysqli_fetch_array($total_rev_q);


$rider_id = $_SESSION['users_id'];





/////// Pickups of rider

// $status_query=mysqli_query($con,"Select * from order_status where sts_id = 2 AND  active='1'");
// $record = mysqli_fetch_array($status_query); 
// $pickup = $record['status'];

//    $query=mysqli_query($con,"select * from orders WHERE   DATE_FORMAT(action_date, '%Y-%m-%d') >= '".$from_date."' AND  DATE_FORMAT(action_date, '%Y-%m-%d') <= '".$to_date."' AND  pickup_rider =".$rider_id."   AND status='".$pickup."'  $where ");
// $orderspickupcount=mysqli_affected_rows($con);


$query1 = mysqli_query($con, "SELECT * FROM assignment_record WHERE DATE_FORMAT(`assign_data_time`, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(`assign_data_time`, '%Y-%m-%d') <= '" . $to_date . "'  AND  rider_status_done_no = '0' AND user_id =" . $rider_id . " AND assignment_type=1  $where  order by id desc ");
$orderspickupcount = mysqli_affected_rows($con);

// echo "<pre>";
// print_r($query);
// print_r("select * from orders WHERE   DATE_FORMAT(action_date, '%Y-%m-%d') >= '".$from_date."' AND  DATE_FORMAT(action_date, '%Y-%m-%d') <= '".$to_date."' AND  pickup_rider =".$rider_id."   AND status='".$pickup."'  $where ");
// die();









/////// Deliveries of rider

// $status_query2=mysqli_query($con,"Select * from order_status where sts_id = 7 AND  active='1'");
// $record2 = mysqli_fetch_array($status_query2); 
// $deliveries = $record2['status'];

// 	$query=mysqli_query($con,"select * from orders WHERE DATE_FORMAT(action_date, '%Y-%m-%d') >= '".$from_date."' AND  DATE_FORMAT(action_date, '%Y-%m-%d') <= '".$to_date."'  AND  pickup_rider =".$rider_id."  AND status='".$deliveries."'  $where ");
// 	$ordersdeliveriescount=mysqli_affected_rows($con);



$query1 = mysqli_query($con, "SELECT * FROM assignment_record WHERE DATE_FORMAT(`assign_data_time`, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(`assign_data_time`, '%Y-%m-%d') <= '" . $to_date . "'  AND rider_status_done_no = '0' AND  user_id =" . $rider_id . " AND assignment_type=2  $where  order by id desc ");
$ordersdeliveriescount = mysqli_affected_rows($con);

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
    <form method="GET" action="">
        <div class="col-sm-2 left_right_none">
            <div class="form-group">
                <label>From</label>
                <input type="text" value="<?php echo $from_date; ?>" class="form-control datetimepicker4" name="from">
            </div>
        </div>
        <div class="col-sm-2 left_right_none">
            <div class="form-group">
                <label>To</label>
                <input type="text" value="<?php echo $to_date; ?>" class="form-control datetimepicker4" name="to">
            </div>
        </div>

        <div class="col-sm-1 sidegapp-submit " style="margin-top: 23px;">
            <input type="submit" name="submit" class="btn btn-success" value="Search">
        </div>
    </form>
</div>
<div class="row icon_panel">
    <div class="col-sm-12 dashboard_icons_box">
        <div class="row">

            <?php if (isset($_SESSION['user_role_id']) and $_SESSION['user_role_id'] == 4) : ?>
            <div class="col-sm-4 dashborad_icon_items">
                <a href="order_pickups.php">
                    <div class="panel panel-default clearfix dashboard-stats rounded">
                        <span id="dashboard-stats-sparkline1" class="sparkline transit"><canvas width="89" height="60"
                                style="display: inline-block; width: 89px; height: 60px; vertical-align: top;"></canvas></span>
                        <i class="fa fa-shopping-cart bg-danger transit stats-icon"></i>
                        <h3 class="transit"><?php echo $orderspickupcount; ?></h3>
                        <p class="text-muted transit">Total Pickups</p>
                    </div>
                </a>
            </div>
            <?php endif ?>
            <div class="col-sm-4 dashborad_icon_items">
                <a href="order_deliveries.php">
                    <div class="panel panel-default clearfix dashboard-stats rounded">
                        <span id="dashboard-stats-sparkline3" class="sparkline transit"><canvas width="89" height="60"
                                style="display: inline-block; width: 89px; height: 60px; vertical-align: top;"></canvas></span>
                        <i class="fa fa-user bg-success transit stats-icon"></i>
                        <h3 class="transit"><?php echo $ordersdeliveriescount; ?></h3>
                        <p class="text-muted transit">Total Deliveries</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-12 order_chart">
        <div class="panel penel-success">
            <div class="panel-heading">Orders Statistics</div>
            <div class="panel-body">
                <div class="row charts" style="background-color: #fff;">
                    <div class="col-sm-12">
                        <div class="row">

                            <?php if (isset($_SESSION['user_role_id']) and $_SESSION['user_role_id'] == 4) : ?>
                            <div class="col-sm-6">
                                <div id="pickup_map_rider" style="height: 400px; width: 100%"></div>
                            </div>
                            <?php endif ?>
                            <div
                                class="<?php if (isset($_SESSION['user_role_id']) and $_SESSION['user_role_id'] == 4) : ?> col-sm-6   <?php endif ?>  <?php if (isset($_SESSION['user_role_id']) and $_SESSION['user_role_id'] == 3) : ?> col-sm-12   <?php endif ?> ">
                                <div id="delivery_map_rider" style="height: 400px; width: 100%"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
a {
    text-decoration: none !important;
}
</style>