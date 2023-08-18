<?php
session_start();
require 'includes/conn.php';
$branch_query = mysqli_query($con, "SELECT * FROM branches ");
if (isset($_SESSION['users_id']) && $_SESSION['type'] !== 'driver') {
    require_once "includes/role_helper.php";
    // if (!checkRolePermission($_SESSION['user_role_id'], 68, 'view_only', $comment = null)) {
    //     header("location:access_denied.php");
    // }
    include "includes/header.php";
?>
<style>
#branch_SearchAndSelector .select2-container {
    width: 152px !important;
}
</style>

<body data-ng-app>
    <?php
        include "includes/sidebar.php";
        ?>
    <!-- Aside Ends-->
    <section class="content">
        <?php
            $branch_where = '';
            if ($_SESSION['branch_id'] == 1) {
                if (isset($_GET['branch_id']) && $_GET['branch_id'] > 0) {
                    $branch_where = ' AND branch_id = ' . $_GET['branch_id'];
                }
            } else {
                $branch_where = " AND branch_id =" . $_SESSION['branch_id'];
            }
            include "includes/header2.php";
            if (isset($_POST['submit'])) {
                $from = date('Y-m-d', strtotime($_POST['from']));
                $to = date('Y-m-d', strtotime($_POST['to']));
                $user_name = isset($_POST['courier_code']) && $_POST['courier_code'] != '' ? $_POST['courier_code'] : '';
                $courier_code = isset($_POST['courier_code']) && $_POST['courier_code'] != '' ? "AND courier_code='" . $_POST['courier_code'] . "'" : '';
            } else {
                $courier_code = '';
                $from = date('Y-m-d', strtotime('today - 1 days'));
                $to = date('Y-m-d');
            }
            $rider_name = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE user_name='" . $row['courier_code'] . "'"));
            // Delete Cash Deposit Form
            ?>
        <!-- Header Ends -->
        <div class="warper container-fluid">
            <div class="row">
                <div class="col-sm-6" style="padding: 7px 0 0;">
                    <div class="page-header">
                        <h1><?php echo getLange('customer') . ' ' . getLange('payment'); ?>
                            <small><?php echo getLange('letsgetquick'); ?></small>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="row" id="branch_SearchAndSelector">
                <div class="col-sm-6"></div>
                <?php if ($_SESSION['branch_id'] == 1) : ?>
                <div class="col-sm-6" id="ledger_payemnt_dropdown" style="padding: 0;">
                    <div class="form-group pull-right select_customer_box">
                        <select class="form-control js-example-basic-single"
                            onchange="window.location.href='cash_deposit_list.php?branch_id='+this.value"
                            name="branch_id">
                            <option value="">Select Branch</option>
                            <?php while ($row_branch = mysqli_fetch_array($branch_query)) {
                                    ?>
                            <option <?php if (isset($_GET['branch_id']) && $_GET['branch_id'] == $row_branch['id']) {
                                                    echo "Selected";
                                                } ?> value="<?php echo $row_branch['id']; ?>">
                                <?php echo $row_branch['name']; ?> </option>
                            <?php
                                    }
                                    ?>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="panel panel-default" style="margin-top: 0; position: relative;">
                <div class="panel-heading">Cash Deposit List
                    <a href="cash_deposit_form.php" class="add_form_btn" style="float: right;font-size: 11px;">Create
                        New </a>
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
                                    <label><?php echo getLange('from'); ?></label>
                                    <input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4"
                                        name="from" readonly>
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('to'); ?></label>
                                    <input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4"
                                        name="to" readonly>
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <label>Courior Code</label>
                                    <select type="text" class="form-control js-example-basic-single"
                                        name="courier_code">
                                        <option value="" selected>Select Courier Code</option>
                                        <?php
                                            $rider_q = mysqli_query($con, "SELECT * FROM users WHERE user_role_id=4 $branch_where");
                                            while ($rider = mysqli_fetch_array($rider_q)) { ?>
                                        <option value="<?php echo $rider['user_name']; ?>"
                                            <?php echo isset($user_name) && $user_name = $rider['user_name'] ? 'selected' : '';  ?>>
                                            <?php echo $rider['user_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-1 sidegapp-submit left_right_none">
                                <input type="submit" id="submit_order" style="margin-top: 9px;" name="submit"
                                    class="btn btn-info" value="<?php echo getLange('submit'); ?>">
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table cellpadding="0" cellspacing="0" border="0"
                                    class="table table-striped dataTable  table-bordered no-footer dtr-inline collapsed"
                                    role="grid" aria-describedby="basic-datatable_info">
                                    <thead>
                                        <tr>
                                            <th><?php echo getLange('srno'); ?></th>
                                            <th>Delivery Date</th>
                                            <th>Courier Code</th>
                                            <th>Courier Name</th>
                                            <th>CDF NO</th>
                                            <th><?php echo getLange('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $srno = 1;

                                            $result_q = mysqli_query($con, "SELECT * FROM cash_deposit_form_master WHERE DATE_FORMAT(`date`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`date`, '%Y-%m-%d') <= '" . $to . "' "  . $courier_code . " $branch_where ORDER BY id DESC");
                                            while ($row = mysqli_fetch_array($result_q)) {
                                                $rider_name = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE user_name='" . $row['courier_code'] . "'"));
                                            ?>
                                        <tr>
                                            <td><?php echo $srno++; ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($row['date'])); ?></td>
                                            <td><?php echo $row['courier_code']; ?></td>
                                            <td><?php echo $rider_name['Name']; ?></td>
                                            <td><?php echo $row['report_id']; ?></td>
                                            <td>
                                                <?php if (checkRolePermission($_SESSION['user_role_id'], 68, 'delete_only', $comment = null)) { ?>
                                                <a
                                                    href="delete_cash_deposit_list.php?delete_id=<?php echo $row['id']; ?>"><i
                                                        class="fa fa-trash"></i></a>
                                                <?php } ?>
                                                <a href="cash_deposit_sheet.php?cash_id=<?php echo $row['id']; ?>"
                                                    target="_blank"><i class="fa fa-eye"></i></a>
                                                    <a href="cash_deposit_form_pay.php?cash_id=<?php echo $row['id']; ?>"
                                                    target="_blank"><i class="fa fa-credit-card"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
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