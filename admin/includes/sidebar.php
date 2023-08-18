<?php
$query = mysqli_query($con, "select * from users where id='" . $_SESSION['users_id'] . "'") or die(mysqli_error($con));
$fetch = mysqli_fetch_array($query);
$type = $fetch['type'];
$current = $_SERVER['REQUEST_URI'];
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$temp = explode('/', $url);

$current = end($temp);

$user_role_id = $_SESSION['user_role_id'];

include('role_helper.php');
include_once('custom_functions.php');

if ($_SESSION['type'] == 'driver' && $user_role_id == 4) {
    $sql = "SELECT balance FROM rider_wallet_ballance WHERE rider_id=" . $_SESSION['users_id'];

    $response = mysqli_query($con, $sql);
    $result = mysqli_fetch_assoc($response);
    $rider_ballance = isset($result['balance']) ? $result['balance'] : 0;
}

?><head>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<aside class="left-panel " id="<?php if (isset($dynamic_id) && !empty($dynamic_id)) {
    echo $dynamic_id;
} ?>">


<div class="user text-center" style="overflow: hidden;">
    <a href="dashboard.php">
        <img src="<?php echo $fetch['image']; ?>" alt="...">
        <!-- <img src="<?php echo $fetch['image']; ?>" class="img-circle" alt="..."> -->
        <h4 class="user-name"><?php echo $fetch['Name']; ?></h4>
    </a>
</div>

<!------------------------------------------------------------------>

<div class="wallet_bln">
    <?php if ($user_role_id == 4) {  ?>
        <i class="fa fa-money"></i>
        <span class="nav-label"><?php echo $rider_ballance; ?></span>
    <?php } ?>
</div>



<!------------------------------------------------------------------>

<nav class="navigation" id="navigation_Bar">
    <ul class="list-unstyled">
        <?php if (checkRolePermission($user_role_id, 1, 'view_only', '')) {  ?>
            <div class="main_title_menu">
                <h4><?php echo getLange('main'); ?></h4>
            </div>
        <?php } ?>

        <!------------------------------------------------------------------>

        <?php if (checkRolePermission($user_role_id, 1, 'view_only', 'Dashboard Module is enabled')) {  ?>
            <li <?php if ($current == 'dashboard.php') {
                echo "class='active'";
            } ?>>
            <a href="dashboard.php">
                <i class="lnr lnr-home"></i>
                <span class="nav-label"><?php echo getLange('dashboard'); ?></span>
            </a>
        </li>
    <?php } ?>

    <!------------------------------------------------------------------>



    <?php if (($user_role_id == 4) && $user_role_id != 1 && $user_role_id != 6) {  ?>
        <li <?php if ($current == 'order_pickups.php') {
            echo "class='active'";
        } ?>>
        <a href="order_pickups.php">
            <i class="lnr lnr-bus"></i>
            <span class="nav-label"><?php echo getLange('pickup'); ?> </span>
        </a>
    </li>
<?php } ?>

<!------------------------------------------------------------------>

<?php if (($user_role_id == 4) && $user_role_id != 1 && $user_role_id != 6) {  ?>

    <li <?php if ($current == 'order_deliveries.php') {
        echo "class='active'";
    } ?>>
    <a href="order_deliveries.php">
        <i class="lnr lnr-bus"></i>
        <span class="nav-label"><?php echo getLange('deliveries'); ?> </span>
    </a>
</li>
<?php } ?>

<!------------------------------------------------------------------>

<?php if (($user_role_id == 4) && $user_role_id != 1 && $user_role_id != 6) {  ?>

    <li <?php if ($current == 'pickups_order_processing.php') {
        echo "class='active'";
    } ?>>
    <a href="pickups_order_processing.php">
        <i class="lnr lnr-enter"></i>
        <span class="nav-label"><?php echo getLange('submitpickups'); ?></span>
    </a>
</li>
<?php } ?>

<!------------------------------------------------------------------>

<?php if (($user_role_id == 4) && $user_role_id != 1 && $user_role_id != 6) {  ?>

    <li <?php if ($current == 'deliveries_order_processing.php') {
        echo "class='active'";
    } ?>>
    <a href="deliveries_order_processing.php">
        <i class="lnr lnr-enter"></i>
        <span class="nav-label"><?php echo getLange('submitdeliveries'); ?></span>
    </a>
</li>
<?php } ?>

<!------------------------------------------------------------------>

<?php if ($type == 'admin' || $type == 'employee') { ?>
<?php } ?>

<?php if ($type == 'admin') { ?>
    <?php if (checkRolePermission($user_role_id, 8, 'add_only', 'Booking Module is enabled')) {  ?>

        <li <?php if ($current == 'booking_form.php') {
            echo "class='active'";
        } ?>>
        <a href="booking_form.php">
            <i class="lnr lnr-calendar-full"></i>
            <span class="nav-label"><?php echo getLange('bookingform'); ?> </span>
        </a>
    </li>
    <li <?php if ($current == 'booking_form_new.php') {
        echo "class='active'";
    } ?>>
    <a href="booking_form_new.php">
        <i class="lnr lnr-calendar-full"></i>
        <span class="nav-label">International Booking</span>
    </a>
</li>
<?php } ?>

<!------------------------------------------------------------------>

<?php if (checkRolePermission($user_role_id, 8, 'view_only', 'Booking Module is enabled')) {  ?>
    <li <?php if ($current == 'view_order.php') {
        echo "class='active'";
    } ?>>
    <a href="view_order.php">
        <i class="lnr lnr-eye"></i>
        <span class="nav-label">View All Orders </span>
    </a>
</li>
<?php } ?>

<!------------------------------------------------------------------>
<?php if (checkRolePermission($user_role_id, 36, 'view_only', 'Booking Module is enabled')) {  ?>

    <li <?php if ($current == 'upload_exe_file.php') {
        echo "class='active'";
    } ?>>
    <a href="upload_exe_file.php">
        <i class="lnr lnr-cart"></i>
        <span class="nav-label"> Bulk Upload</span>
    </a>
</li>
<?php } ?> 
             <?php if (checkRolePermission($user_role_id, 36, 'view_only', 'Booking Module is enabled')) {  ?>

            <li <?php if ($current == 'my_order.php') {
                                echo "class='active'";
                            } ?>>
                <a href="my_order.php">
                    <i class="lnr lnr-cart"></i>
                    <span class="nav-label"> My Branch Orders</span>
                </a>
            </li>
            <?php } ?> 

            <!------------------------------------------------------------------>
            <!-- Assignments Starts from here -->
            <!------------------------------------------------------------------>

            <?php if (checkRolePermission($user_role_id, 9, 'view_only', '') || checkRolePermission($user_role_id, 10, 'view_only', '') || checkRolePermission($user_role_id, 11, 'view_only', '') || checkRolePermission($user_role_id, 12, 'view_only', '') || checkRolePermission($user_role_id, 30, 'view_only', '') || checkRolePermission($user_role_id, 31, 'view_only', '')) {  ?>


                <div class="main_title_menu second_menu_head">
                    <h4><?php echo getLange('assignment'); ?></h4>
                </div>

            <?php } ?>


            <?php if (checkRolePermission($user_role_id, 9, 'view_only', '') || checkRolePermission($user_role_id, 10, 'view_only', '') || checkRolePermission($user_role_id, 13, 'view_only', '') || checkRolePermission($user_role_id, 40, 'view_only', '') || checkRolePermission($user_role_id, 53, 'view_only', '') || checkRolePermission($user_role_id, 30, 'view_only', '') || checkRolePermission($user_role_id, 11, 'view_only', '') || checkRolePermission($user_role_id, 31, 'view_only', '') || checkRolePermission($user_role_id, 12, 'view_only', '')) {  ?>


            <!-- <li class="has-submenu <?php if ($current == 'view_order_assignment.php' || $current == 'manifest_form.php' || $current == 'demanifest_form.php' || $current == 'demanifest_report.php' || $current == 'manifest_report.php' ||  $current == 'pickup_run_sheet.php' || $current == 'delivery_run_sheet.php' || $current == 'bulk_status_update.php' || $current == 'return_run_sheet.php') {
                                                    echo "active";
                                                } ?>"><a href="#"> <span class="nav-label"><?php echo getLange('orderassignment'); ?> </span></a>

                                                /li> -->


                                                <?php if (checkRolePermission($user_role_id, 10, 'view_only', 'Pickup Module is enabled')) {  ?>

                                                    <li <?php if ($current == 'pickup_run_sheet.php') {
                                                        echo "class='active'";
                                                    } ?>>
                                                    <a href="pickup_run_sheet.php">
                                                        <i class="lnr lnr-bus"></i> <?php echo getLange('pickuprunsheet'); ?>
                                                    </a>
                                                </li>
                                            <?php } ?>

                                            <?php if (checkRolePermission($user_role_id, 11, 'view_only', 'Delivery Module is enabled')) {  ?>
                                                <li <?php if ($current == 'delivery_run_sheet.php') {
                                                    echo "class='active'";
                                                } ?>>
                                                <a href="delivery_run_sheet.php">
                                                    <i class="lnr lnr-bus"></i> <?php echo getLange('deliveryrunsheet'); ?>
                                                </a>
                                            </li>
                                        <?php } ?>

                                        <?php if (checkRolePermission($user_role_id, 30, 'view_only', 'Dashboard Module is enabled')) {  ?>

                                            <li style="position: relative;" <?php if ($current == 'manifest_form.php' || $current == 'manifest_report.php') {
                                                echo "class='active'";
                                            } ?>>
                                            <a href="manifest_report.php">
                                                <i class="lnr lnr-inbox"> </i> Manifest
                                            </a>
                                            <a class="plus_action" href="manifest_report.php">
                                                <span class="nav-label">
                                                    <i class="lnr lnr-eye"></i>
                                                </span>
                                            </a>
                                            <a class="circle_action" href="manifest_form.php">
                                                <span class="nav-label">
                                                    <i class="lnr lnr-plus-circle"></i>
                                                </span>
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <?php if (checkRolePermission($user_role_id, 31, 'view_only', 'Dashboard Module is enabled')) {  ?>
                                        <li style="position: relative;" <?php if ($current == 'demanifest_form.php' || $current == 'demanifest_report.php') {
                                            echo "class='active'";
                                        } ?>>
                                        <a href="demanifest_report.php">
                                            <i class="lnr lnr-inbox"> </i> De-Manifest
                                        </a>
                                        <a class="plus_action" href="demanifest_report.php">
                                            <span class="nav-label">
                                                <i class="lnr lnr-eye"></i>
                                            </span>
                                        </a>
                                        <a class="circle_action" href="demanifest_form.php">
                                            <span class="nav-label">
                                                <i class="lnr lnr-plus-circle"></i>
                                            </span>
                                        </a>
                                    </li>
                                <?php } ?>

            <!-- <?php  //if(checkRolePermission($user_role_id , 12 ,'view_only','Return Module is enabled')){  
                            ?>
							<li <?php //if($current=='return_run_sheet.php'){echo "class='active'";}
                                ?> ><a href="return_run_sheet.php"><?php //echo getLange('returnparcelordersheet'); 
                                                                    ?></a></li>
						<?php //} 
                    ?> -->

                    <?php if (checkRolePermission($user_role_id, 58, 'view_only', 'Order Processing Module is enabled')) {  ?>
                        <li <?php if ($current == 'delivery_scan_sheet.php') {
                            echo "class='active'";
                        } ?>>
                        <a href="delivery_scan_sheet.php">
                            <i class="lnr lnr-bus"></i>
                            <span class="nav-label"> Delivery Scan Sheet </span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (checkRolePermission($user_role_id, 13, 'view_only', 'Order Processing Module is enabled')) {  ?>
                    <li <?php if ($current == 'order_processing.php') {
                        echo "class='active'";
                    } ?>>
                    <a href="order_processing.php">
                        <i class="lnr lnr-cart"></i>
                        <span class="nav-label"> <?php echo getLange('orderprocessing'); ?> </span>
                    </a>
                </li>
                <li <?php if ($current == 'manual_api_booking.php') {
                            echo "class='active'";
                        } ?>>
                        <a href="manual_api_booking.php">
                            <i class="lnr lnr-cart"></i>
                            <span class="nav-label">Manual Vendor Booking</span>
                        </a>
                    </li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 40, 'view_only', 'Bulk Status Module is enabled')) {  ?>
                <li <?php if ($current == 'bulk_status_update.php') {
                    echo "class='active'";
                } ?>>
                <a href="bulk_status_update.php">
                    <i class="lnr lnr-checkmark-circle"></i> <?php echo getLange('bulkstatusupdate'); ?>
                </a>
            </li>
        <?php } ?>

        <?php if (checkRolePermission($user_role_id, 53, 'view_only', 'Order Status Module is enabled')) {  ?>
            <li <?php if ($current == 'order_status_update.php') {
                echo "class='active'";
            } ?>>
            <a href="order_status_update.php">
                <i class="lnr lnr-checkmark-circle"></i> <?php echo getLange('order_status_update'); ?>
            </a>
        </li>
    <?php } ?>

    <?php if (checkRolePermission($user_role_id, 9, 'view_only', 'Booking Module is enabled')) {  ?>
        <li <?php if ($current == 'view_order_assignment.php') {
            echo "class='active'";
        } ?>>
        <a href="view_order_assignment.php">
            <i class="lnr lnr-eye"></i> <?php echo getLange('vieworderassignment'); ?>
        </a>
    </li>
<?php } ?>
<?php } ?>

<!------------------------------------------------------------------>
<!-- Assignments Ends from here -->
<!------------------------------------------------------------------>




            <!-- <?php if (checkRolePermission($user_role_id, 13, 'view_only', 'Assign Delivery Zone Module is enabled')) {  ?>
						<li <?php if ($current == 'assign_delivery_zone.php') {
                                echo "class='active'";
                            } ?> ><a href="assign_delivery_zone.php"><i class="fa fa-refresh"></i> <span class="nav-label"><?php echo getLange('assigndeliverzone'); ?>   </span></a>
						</li>
                       <?php } ?> -->


                       <!------------------------------------------------------------------>
                       <!-- Accounts starts from here -->
                       <!------------------------------------------------------------------>

                       <?php if (checkRolePermission($user_role_id, 14, 'view_only', '') || checkRolePermission($user_role_id, 15, 'view_only', '') || checkRolePermission($user_role_id, 16, 'view_only', '') || checkRolePermission($user_role_id, 35, 'view_only', '') || checkRolePermission($user_role_id, 2, 'add_only', '') || checkRolePermission($user_role_id, 35, 'view_only', '') ) {  ?>

                        <div class="main_title_menu second_menu_head">
                            <h4><?php echo getLange('account'); ?></h4>
                        </div>

                    <?php } ?>


                    <?php if (checkRolePermission($user_role_id, 34, 'view_only', '') || checkRolePermission($user_role_id, 2, 'view_only', '') || checkRolePermission($user_role_id, 2, 'add_only', '')) {  ?>
                        <li class=" <?php if ($current == 'addbusiness.php' || $current == 'businessacc.php' || $current == 'pendingbusinessacc.php') {
                            echo "active";
                        } ?>">
                        <a href="businessacc.php"><i class="lnr lnr-user"></i>
                            <span class="nav-label"><?php echo getLange('bussinessacco'); ?> </span>
                        </a>

                    </li>
                <?php } ?>

                <!-- Bulk Payments  -->
                <?php if (checkRolePermission($user_role_id, 34, 'view_only', '') || checkRolePermission($user_role_id, 2, 'view_only', '') || checkRolePermission($user_role_id, 2, 'add_only', '')) {  ?>
                    <li class=" <?php if ($current == 'bulk_payments.php' ) {
                        echo "active";
                    } ?>">
                    <a href="bulk_payments.php"><i class="lnr lnr-user"></i>
                        <span class="nav-label">Bulk Payments </span>
                    </a>

                </li>
            <?php } ?>

            <!-- Bulk Payments  -->


            <?php if (checkRolePermission($user_role_id, 35, 'view_only', 'Business Module with add enabled')) {  ?>
                <li <?php if ($current == 'ledger_payments.php') {
                    echo "class='active'";
                } ?>>
                <a href="ledger_payments.php">
                    <i class="lnr lnr-briefcase"></i> <?php echo getLange('customerpayment'); ?>
                </a>
            </li>
        <?php } ?>

        <?php if (checkRolePermission($user_role_id, 16, 'view_only', 'Business Module with add enabled')) {  ?>
            <li <?php if ($current == 'non_ledger_payments.php') {
                echo "class='active'";
            } ?>>
            <a href="non_ledger_payments.php">
                <i class="lnr lnr-file-empty"></i> <?php echo getLange('invoicetocustomer'); ?>
            </a>
        </li>
    <?php } ?>
    <li <?php if ($current == 'cash_deposit_list.php') {
            echo "class='active'";
        } ?>>
        <a href="cash_deposit_list.php">
            <i class="lnr lnr-file-empty"></i> Cash Deposite Form
        </a>
    </li>

    <?php if (checkRolePermission($user_role_id, 15, 'view_only', 'Business Module with add enabled')) {  ?>
        <li <?php if ($current == 'bulk_ledger_creation.php') {
            echo "class='active'";
        } ?>>
        <a href="bulk_ledger_creation.php">
            <span class="nav-label">
                <i class="fa fa-users"></i> <?php echo getLange('bulkpayment'); ?>
            </span>
        </a>
    </li>
<?php } ?>

<?php if (checkRolePermission($user_role_id, 14, 'view_only', 'Business Module with add enabled')) {  ?>
    <li <?php if ($current == 'employee_payments.php') {
        echo "class='active'";
    } ?>>
    <a href="employee_payments.php">
        <i class="lnr lnr-briefcase"></i> <?php echo getLange('riderpayment'); ?>
    </a>
</li>
<?php } ?>

<!------------------------------------------------------------------>
<!-- Accounts Ends from here -->
<!------------------------------------------------------------------>


<!------------------------------------------------------------------>
<!-- Reports Starts Here -->
<!------------------------------------------------------------------>

<?php if (checkRolePermission($user_role_id, 19, 'view_only', 'Shipment Report Module with add enabled') || checkRolePermission($user_role_id, 20, 'view_only', 'Shipment Report Module with add enabled') || checkRolePermission($user_role_id, 29, 'view_only', 'Shipment Report Module with add enabled') || checkRolePermission($user_role_id, 38, 'view_only', 'Order Wallet Report') || checkRolePermission($user_role_id, 69, 'view_only', 'Branch Wise Report') || checkRolePermission($user_role_id, 37, 'view_only', 'Order Wallet Report')) {  ?>

    <div class="main_title_menu second_menu_head">
        <h4><?php echo getLange('report'); ?></h4>
    </div>

<?php } ?>

            <!-- <li <?php if ($current == 'addzone.php') {
                                echo "class='active'";
                            } ?> ><a href="addzone.php"><i class="fa fa-money"></i> Zones</a></li> -->


                            <?php if (checkRolePermission($user_role_id, 19, 'view_only', 'Shipment Report Module with add enabled') || checkRolePermission($user_role_id, 20, 'view_only', 'Shipment Report Module with add enabled') || checkRolePermission($user_role_id, 29, 'view_only', 'Shipment Report Module with add enabled') || checkRolePermission($user_role_id, 54, 'view_only', 'Shipment Report Module with add enabled') || checkRolePermission($user_role_id, 55, 'view_only', 'Shipment Report Module with add enabled') || checkRolePermission($user_role_id, 38, 'view_only', 'Order Wallet Report')  || checkRolePermission($user_role_id, 69, 'view_only', 'Branch Wise Report') || checkRolePermission($user_role_id, 37, 'view_only', 'Order Wallet Report')) {  ?>



                                <li class="has-submenu <?php if ($current == 'orders_report.php' || $current == 'charges_report.php'  || $current == 'rider_wallet_report.php' || $current == 'order_status_report.php' || $current == 'shipment_report.php' || $current == 'comments_report.php' || $current == 'city_wise_report.php' || $current == 'branch_wise_report.php' || $current == 'cod_payables.php' || $current == 'saleman_performance_report.php' || $current == 'third_party_report.php'|| $current == 'account_ledger_reports.php') {
                                    echo "active";
                                } ?>">

                                <a href="#">
                                    <i class="lnr lnr-file-empty"></i>
                                    <span class="nav-label"><?php echo getLange('report'); ?></span>
                                    <i class="fa fa-angle-down"></i>
                                </a>

                                <ul class="list-unstyled">


                                    <?php if (checkRolePermission($user_role_id, 19, 'view_only', 'Shipment Report Module with add enabled')) {  ?>
                                        <li <?php if ($current == 'shipment_report.php') {
                                            echo "class='active'";
                                        } ?>>
                                        <a href="shipment_report.php"><?php echo getLange('shipmentreport'); ?> </a>
                                    </li>
                                <?php } ?>

                    <!-- <?php if (checkRolePermission($user_role_id, 39, 'view_only', '')) {  ?>
                    <li <?php if ($current == 'order_status_report.php') {
                                            echo "class='active'";
                                        } ?>>
                        <a href="order_status_report.php"><?php echo getLange('statusreport'); ?></a>
                    </li>
                    <?php } ?> -->
                    <?php if (checkRolePermission($user_role_id, 45, 'view_only', '')) {  ?>
                        <li <?php if ($current == 'charges_report.php') {
                            echo "class='active'";
                        } ?>>
                        <a href="charges_report.php"><?php echo getLange('chargesreport'); ?></a>
                    </li>
                <?php } ?>
                <?php if (checkRolePermission($user_role_id, 55, 'view_only', '')) {  ?>
                    <li <?php if ($current == 'cod_payables.php') {
                        echo "class='active'";
                    } ?>>
                    <a href="cod_payables.php"><?php echo getLange('cod_payables'); ?></a>
                </li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 20, 'view_only', '')) {  ?>
                <li <?php if ($current == 'orders_report.php') {
                    echo "class='active'";
                } ?>>
                <a href="orders_report.php"><?php echo getLange('orderreport'); ?> </a>
            </li>
        <?php } ?>
                     <?php if (checkRolePermission($user_role_id, 54, 'view_only', '')) {  ?>
                    <li <?php if ($current == 'city_wise_report.php') {
                                            echo "class='active'";
                                        } ?>>
                        <a href="city_wise_report.php"><?php echo getLange('city_wise_report'); ?> </a>
                    </li>
                    <?php } ?>
                    <?php if (checkRolePermission($user_role_id, 69, 'view_only', '')) {  ?>
                    <li <?php if ($current == 'branch_wise_report.php') {
                                            echo "class='active'";
                                        } ?>>
                        <a href="branch_wise_report.php"><?php echo getLange('Branch Wise Report'); ?> </a>
                    </li>
                    <?php } ?>
                    <?php if (checkRolePermission($user_role_id, 29, 'view_only', '')) {  ?>
                        <li <?php if ($current == 'comments_report.php') {
                            echo "class='active'";
                        } ?>>
                        <a href="comments_report.php"><?php echo getLange('commentsreports'); ?> </a>
                    </li>
                <?php } ?>

                <?php if (checkRolePermission($user_role_id, 38, 'view_only', '')) {  ?>
                    <li <?php if ($current == 'rider_wallet_report.php') {
                        echo "class='active'";
                    } ?>>
                    <a href="rider_wallet_report.php"><?php echo getLange('riderwalletreport'); ?> </a>
                </li>
            <?php } ?>
            <?php if (checkRolePermission($user_role_id, 38, 'view_only', '')) {  ?>
                <li <?php if ($current == 'saleman_performance_report.php') {
                    echo "class='active'";
                } ?>>
                <a href="saleman_performance_report.php">Revenue Report </a>
            </li>
        <?php } ?>
        <?php if (checkRolePermission($user_role_id, 38, 'view_only', '')) {  ?>
            <li <?php if ($current == 'third_party_report.php') {
                echo "class='active'";
            } ?>>
            <a href="third_party_report.php">Third-party Booking report </a>
        </li>
    <?php } ?>
    <?php if (checkRolePermission($user_role_id, 38, 'view_only', '')) {  ?>
            <li <?php if ($current == 'account_ledger_reports.php') {
                echo "class='active'";
            } ?>>
            <a href="account_ledger_reports.php">Account Ledger report </a>
        </li>
    <?php } ?>
    <?php if (checkRolePermission($user_role_id, 38, 'view_only', '')) {  ?>
            <li <?php if ($current == 'account_ledger_reports.php') {
                echo "class='active'";
            } ?>>
            <a href="account_ledger_reports.php">Account Group report </a>
        </li>
    <?php } ?>
</ul>
</li>
<?php } ?>

<?php } ?>
<!------------------------------------------------------------------>
<!-- Reports Ends from here -->
<!------------------------------------------------------------------>
<!-- account group start-->


                                        <?php 
                                        if(getConfig('accounting_settings') == 1){
                                        if (checkRolePermission($user_role_id, 30, 'view_only', 'Dashboard Module is enabled')) {  ?>

                                            <li style="position: relative;" <?php if ($current == 'accountgroup_form.php' || $current == 'account_group.php') {
                                                echo "class='active'";
                                            } ?>>
                                            <a href="account_group.php">
                                                <i class="lnr lnr-inbox"> </i> Account Group
                                            </a>
                                            <a class="plus_action" href="account_group.php">
                                                <span class="nav-label">
                                                    <i class="lnr lnr-eye"></i>
                                                </span>
                                            </a>
                                            <a class="circle_action" href="accountgroup_form.php">
                                                <span class="nav-label">
                                                    <i class="lnr lnr-plus-circle"></i>
                                                </span>
                                            </a>
                                        </li>
                                    <?php } }?>

<!--------------- account group end--------------------->
<!------------------------------------------------------>
<!-- account ledger start-->
    <?php if (checkRolePermission($user_role_id, 30, 'view_only', 'Dashboard Module is enabled')) {  ?>

                                            <li style="position: relative;" <?php if ($current == 'accountledger_form.php' || $current == 'account_ledger.php') {
                                                echo "class='active'";
                                            } ?>>
                                            <a href="account_ledger.php">
                                                <i class="lnr lnr-inbox"> </i> Account Ledger
                                            </a>
                                            <a class="plus_action" href="account_ledger.php">
                                                <span class="nav-label">
                                                    <i class="lnr lnr-eye"></i>
                                                </span>
                                            </a>
                                            <a class="circle_action" href="accountledger_form.php">
                                                <span class="nav-label">
                                                    <i class="lnr lnr-plus-circle"></i>
                                                </span>
                                            </a>
                                        </li>
                                    <?php }  ?>
<!------------------------------------------------------>
<!---- account ledger end ------->
<!------------------------------------------------------------------>
<!-- Setting module starts here -->
<!------------------------------------------------------------------>

<?php if ($type == 'admin') { ?>
    <?php if (checkRolePermission($user_role_id, 32, 'view_only', '') || checkRolePermission($user_role_id, 2, 'add_only', '') || checkRolePermission($user_role_id, 17, 'view_only', '') || checkRolePermission($user_role_id, 22, 'view_only', '')) {  ?>

        <div class="main_title_menu second_menu_head">
            <h4><?php echo getLange('setting'); ?></h4>
        </div>

    <?php } ?>

            <?php if (checkRolePermission($user_role_id, 32, 'view_only', 'Branches')) {  ?>
            <li <?php if ($current == 'branch_list.php') {
                            echo "class='active'";
                        } ?>>
                <a href="branch_list.php">
                    <span class="nav-label">
                        <i class="lnr lnr-apartment"></i> <?php echo getLange('branch'); ?>
                    </span>
                </a>
            </li>
            <?php } ?>

            <?php if (checkRolePermission($user_role_id, 67, 'view_only', 'Business Module with add enabled') || checkRolePermission($user_role_id, 67, 'add_only', '') || checkRolePermission($user_role_id, 17, 'edit_only', '')) {  ?>
                <li <?php if ($current == 'driversdata.php') {
                    echo "class='active'";
                } ?>>
                <a href="driversdata.php">
                    <i class="lnr lnr-users"></i> <?php echo getLange('users'); ?>
                </a>
            </li>
        <?php } ?>
            
						<?php if (checkRolePermission($user_role_id, 17, 'view_only', 'Dashboard Module is enabled')) {  ?>
							<li <?php if ($current == 'thirdparty_setting.php') {
                                    echo "class='active'";
                                } ?> >
								<a href="thirdparty_setting.php">
									<i class="lnr lnr-link"></i> <?php echo getLange('thirdparty'); ?>
								</a>
							</li>
						<?php } ?>
                    


                    <?php if (checkRolePermission($user_role_id, 51, 'view_only', 'Dashboard Module is enabled')) {  ?>
                        <li <?php if ($current == 'flyer_sell.php') {
                            echo "class='active'";
                        } ?>>
                        <a href="flyer_sell.php">
                            <i class="lnr lnr-cog"></i> <?php echo getLange('flyers'); ?>
                        </a>
                    </li>

                    <?php

                }
                ?>
                <?php if (checkRolePermission($user_role_id, 22, 'view_only', 'Dashboard Module is enabled')) {  ?>
                    <li <?php if ($current == 'setting.php') {
                        echo "class='active'";
                    } ?>>
                    <a href="setting.php">
                        <i class="lnr lnr-cog"></i> <?php echo getLange('setting'); ?>
                    </a>
                </li>

                <?php

            }
            ?>
            <?php if (checkRolePermission($user_role_id, 59, 'view_only', 'Shipment Report Module with add enabled')) {  ?>
                <li class="has-submenu <?php if ($current == 'add_country.php' || $current == 'add_state.php' || $current == 'add_city.php' || $current == 'add_pincode.php' || $current == 'pincode_list.php' || $current == 'location_list.php') {
                    echo "active";
                } ?>">
                <a href="country.php">
                    <i class="lnr lnr-location"></i> Location
                </a>
            </li>
        <?php } ?>
     <?php            if(getConfig('accounting_settings') == 1){?>   <li >
          <a href="#">
                    <i class="lnr lnr-envelope"></i>
                    <span class="nav-label"><?php echo getLange(''); ?>Transactions</span>
                    <i class="fa fa-angle-down"></i>
                </a>
        
                <ul class="list-unstyled">


                   <li style="position: relative;">
                                                <a href="debitNoteListing.php">
                                                     Debit Note
                                                </a>
                                                <a class="plus_action" href="debitNoteListing.php">
                                                    <span class="nav-label">
                                                        <i class="lnr lnr-eye"></i>
                                                    </span>
                                                </a>
                                                <a class="circle_action" href="debit_note.php">
                                                    <span class="nav-label">
                                                        <i class="lnr lnr-plus-circle"></i>
                                                    </span>
                                                </a>
                                            </li>
                                                <li style="position: relative;">
                                                <a href="creditNoteListing.php">
                                                    Credit Note
                                                </a>
                                                <a class="plus_action" href="creditNoteListing.php">
                                                    <span class="nav-label">
                                                        <i class="lnr lnr-eye"></i>
                                                    </span>
                                                </a>
                                                <a class="circle_action" href="credit_note.php">
                                                    <span class="nav-label">
                                                        <i class="lnr lnr-plus-circle"></i>
                                                    </span>
                                                </a>
                                            </li>
                                             <li style="position: relative;">
                                                <a href="journalVoucherListing.php">
                                                    Journal Voucher
                                                </a>
                                                <a class="plus_action" href="journalVoucherListing.php">
                                                    <span class="nav-label">
                                                        <i class="lnr lnr-eye"></i>
                                                    </span>
                                                </a>
                                                <a class="circle_action" href="journal_voucher.php">
                                                    <span class="nav-label">
                                                        <i class="lnr lnr-plus-circle"></i>
                                                    </span>
                                                </a>
                                            </li>
                                             <li style="position: relative;">
                                                <a href="paymentVoucherListing.php">
                                                    Payment Voucher
                                                </a>
                                                <a class="plus_action" href="paymentVoucherListing.php">
                                                    <span class="nav-label">
                                                        <i class="lnr lnr-eye"></i>
                                                    </span>
                                                </a>
                                                <a class="circle_action" href="payment_voucher.php">
                                                    <span class="nav-label">
                                                        <i class="lnr lnr-plus-circle"></i>
                                                    </span>
                                                </a>
                                            </li>
                                             <li style="position: relative;">
                                                <a href="recieptVoucherListing.php">
                                                    Reciept Voucher
                                                </a>
                                                <a class="plus_action" href="recieptVoucherListing.php">
                                                    <span class="nav-label">
                                                        <i class="lnr lnr-eye"></i>
                                                    </span>
                                                </a>
                                                <a class="circle_action" href="reciept_voucher.php">
                                                    <span class="nav-label">
                                                        <i class="lnr lnr-plus-circle"></i>
                                                    </span>
                                                </a>
                                            </li>
            </ul>
        </li>
        <?php }?> 
        <?php if (checkRolePermission($user_role_id, 56, 'view_only', 'Shipment Report Module with add enabled') || checkRolePermission($user_role_id, 57, 'view_only', 'Shipment Report Module with add enabled')) {  ?>
            <li class="has-submenu <?php if ($current == 'sent_list.php' || $current == 'sent_list_email.php' || $current == 'templates.php' || $current == 'api_settings.php' || $current == 'third_party_api_setting.php' || $current == 'mobile_gateway.php' || $current == 'template_form.php' || $current == 'templates_email.php' || $current == 'api_settings_email.php' || $current == 'third_party_api_setting_email.php' || $current == 'mobile_gateway_email.php' || $current == 'template_form_email.php') {
                echo "active";
            } ?>">

            <a href="#">
                <i class="lnr lnr-envelope"></i>
                <span class="nav-label"><?php echo getLange(''); ?>Communication</span>
                <i class="fa fa-angle-down"></i>
            </a>

            <ul class="list-unstyled">


                <?php if (checkRolePermission($user_role_id, 56, 'view_only', 'Shipment Report Module with add enabled')) {  ?>
                    <li <?php if ($current == 'sent_list.php') {
                        echo "class='active'";
                    } ?>>
                    <a href="sent_list.php"><?php echo getLange(''); ?>SMS </a>
                </li>
            <?php } ?>

            <?php if (checkRolePermission($user_role_id, 57, 'view_only', '')) {  ?>
                <li <?php if ($current == 'sent_list_email.php') {
                    echo "class='active'";
                } ?>>
                <a href="sent_list_email.php"><?php echo getLange(''); ?>EMAIL</a>
            </li>
        <?php } ?>
    </ul>
</li>

<?php
}
}
?>
<!------------------------------------------------------------------>
<!-- Setting module Ends here-->
<!------------------------------------------------------------------>
</ul>
</nav>
</aside>