<?php
$url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
session_start();
require 'includes/conn.php';
require 'includes/functions.php';
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver')) {



    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 19, 'view_only', $comment = null)) {

        header("location:access_denied.php");
    }

    include "includes/header.php";
    $cities1 = mysqli_query($con, "SELECT * FROM cities WHERE 1 order by id desc ");
    $cities2 = mysqli_query($con, "SELECT * FROM cities WHERE 1 order by id desc ");
    $status_query = mysqli_query($con, "Select * from order_status where active='1'");
    $drivers = mysqli_query($con, "SELECT * FROM users WHERE type='driver' order by id desc ");
    $customers = mysqli_query($con, "SELECT * FROM customers WHERE status=1");
    $logo_img = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='logo' "));
    $active_customer_query = "";
    if (isset($_GET['active_customer'])) {
        $active_customer = $_GET['active_customer'];
        if (empty($active_customer)) {
            $active_customer_query = "";
        } else {
            $active_customer_query = " AND customer_id=" . $active_customer . " ";
        }
    }
?>

    <body data-ng-app>
        <style>
            .form-control {
                width: 93% !important;
            }

            table.table-bordered.dataTable th,
            table.table-bordered.dataTable td {
                border-left-width: 0;
                font-size: 11px;
                padding: 8px 7px;
            }

            .panel-default {
                margin-top: 18px;
            }

            .booked_packge {
                max-width: 100%;
                margin: 24px auto 0;
            }

            .pacecourier_logo {
                float: left;
                width: 20%;
                margin-right: 22px;
            }

            .pacecourier_logo img {}

            .booked_packges {
                float: right;
                width: 76%;
            }

            .booked_packges h4 {
                margin: 0;
                font-size: 18px;
            }

            .booked_packges ul {
                padding: 0;
                margin: 9px 0 0;
            }

            .booked_packges ul li {
                list-style: none;
                margin-bottom: 1px;
                display: inline-block;
                width: 44%;
                font-size: 14px;
            }

            .booked_packges li b {
                font-size: 12px;
            }

            .buttons-print,
            .buttons-pdf {
                display: none !important;
            }

            /*.booked_packges ul li b {
      float: left;
      width: 25%;
      }*/
        </style>
        <style>
            @media print {
                .shipment_report {
                    padding: 0;
                }
            }
        </style>
        <?php
        if (!isset($_GET['print'])) {
            include "includes/sidebar.php";
        }
        ?>
        <!-- Aside Ends-->
        <?php if (isset($_GET['print'])) { ?>
            <section class="">
            <?php } else { ?>
                <section class="content">
                <?php } ?>
                <?php
                if (!isset($_GET['print'])) {
                    include "includes/header2.php";
                }
                ?>
                <!-- Header Ends -->
                <div class="warper container-fluid">
                    <?php
                    $from = date('Y-m-d', strtotime('today - 30 days'));
                    $to = date('Y-m-d');
                    $query1 = mysqli_query($con, "SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '" . $to . "' $active_customer_query  order by id desc ");
                    ?>
                    <?php
                    if (!isset($_GET['print'])) { ?>
                        <!-- <div class="row">
                            <a target="_blank" style="margin-left: 0;margin-bottom: 10px;" href="<?php echo 'shipment_report.php?' . http_build_query(array_merge($_GET, ['print' => 1])); ?>" class="btn btn-info"><?php echo getLange('print'); ?></a>
                        </div> -->
                    <?php } ?>
                    <?php if (isset($_GET['print'])) { ?>
                        <?php
                        $stat_origin = '';
                        $stat_destination = '';
                        $stat_status = '';
                        if ($active_origin == '') {
                            $stat_origin = 'All';
                        } else {
                            $stat_origin = $active_origin;
                        }
                        if ($active_destination == '') {
                            $stat_destination = 'All';
                        } else {
                            $stat_destination = $active_destination;
                        }
                        if ($active_status == '') {
                            $stat_status = 'All';
                        } else {
                            $stat_status = $active_status;
                        }
                        ?>
                        <div class="clearfix booked_packge">
                            <div class="pacecourier_logo">
                                <img <?= isset($_GET['print']) ? '' : 'style="display:none;"'; ?> src="<?php echo BASE_URL ?>admin/<?php echo $logo_img['value']; ?>" alt="..." style="height: 66px;">
                            </div>
                            <div class="booked_packges ">
                                <h4>Shippment Report</h4>
                                <ul>
                                    <li><b>Pickup City:</b> <?php echo $stat_origin; ?></li>
                                    <li><b>Delivery City:</b> <?php echo $stat_destination; ?></li>
                                    <li><b>Rider:</b> <?php echo isset($driver_data) ? $driver_data['Name'] : ''; ?></li>
                                    <li><b>Status:</b> <?php echo isset($stat_status) ? $stat_status : ''; ?></li>
                                    <li><b>From:</b> <?php echo isset($from) ? date('Y-m-d h:i', strtotime($from)) : ''; ?></li>
                                    <li><b>To:</b> <?php echo isset($to) ? date('Y-m-d h:i', strtotime($to)) : ''; ?></li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
                    <?php
                    include "pages/reports/saleman_performance_report.php";

                    ?>
                </div>
                <!-- Warper Ends Here (working area) -->
            <?php
            if (!isset($_GET['print'])) {
                include "includes/footer.php";
            }

            // include "includes/footer.php";
        } else {
            header("location:index.php");
        }
            ?>
            <?php
            if (isset($_GET['print'])) { ?>
                <script type="text/javascript">
                    window.print();
                </script>
            <?php } ?>
            <script type="text/javascript">
                $(function() {
                    $('.datetimepicker4').datetimepicker({
                        format: 'YYYY-MM-DD',
                    });
                });
            </script>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    var dataTable = $('.saleman_reportt').DataTable({
                        'processing': true,
                        'serverSide': true,
                        "bDestroy": true,
                        'serverMethod': 'post',
                        'responsive': true,
                        'pageLength': 10,
                        'lengthMenu': [
                            [10, 25, 50, 100, 200, 300],
                            [10, 25, 50, 100, 200, 300]
                        ],
                        'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
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
                            {
                                extend: 'print',

                                customize: function(win) {
                                    $(win.document.body)
                                        .css('font-size', '10pt')
                                        .prepend(
                                            '<div>xxxxxxxxxxxxxxxxxxxxxxxx</div><img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                                        );
                                    $(win.document.body).addClass('white-bg');
                                    $(win.document.body).css('font-size', '10px');
                                    $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css('font-size', 'inherit');
                                }
                            }
                        ],
                        'ajax': {
                            'url': 'ajax_view_saleman_performance_report.php',
                            beforeSend: function() {
                                $('#image').show();
                            },
                            complete: function() {
                                $('#image').hide();
                            },

                            'data': function(data) {
                                var from = $('#from').val();
                                var to = $('#to').val();
                                var customer = $('#customer').val();
                                var saleman = $('#saleman').val();

                                data.from = from;
                                data.to = to;
                                data.customer = customer;
                                data.saleman = saleman;
                            }
                        },

                        'columns': [{
                                data: 'srno'
                            },
                            {
                                data: 'saleman'
                            },
                            {
                                data: 'businessname'
                            },
                            
                            {
                                data: 'parcels'
                            },
                            {
                                data: 'service_charges'
                            },
                            {
                                data: 'fuel_surcharge'
                            },
                            {
                                data: 'deliveryprice'
                            },
                            {
                                data: 'action'
                            }
                        ]
                    });
                    $('#submit_report').click(function(e) {
                        e.preventDefault();
                        dataTable.draw();
                        count_total();
                    });
                    $('#print_data').click(function(e) {
                        e.preventDefault();
                        var date_range = $('#date_range').val();
                        var date_from = $('#date_from').val();
                        var date_to = $('#date_to').val();
                        var collection_centers = $('#collection_centers').val();
                        window.open('https://transco.itvision.pk/admin/print_sale_report.php?date_range=' + date_range + '&date_from=' + date_from + '&date_to=' + date_to + '&collection_centers=' + collection_centers + '&print=1');
                    });
                }, false);
            </script>
            <script>
                $(function() {
                    count_total();
                });

                function count_total() {

                    var from = $('#from').val();
                    var to = $('#to').val();
                    var customer = $('#customer').val();
                    var saleman = $('#saleman').val();

                    var data = {
                        from: from,
                        to: to,
                        customer:customer,
                        saleman:saleman,
                    };
                    $.ajax({
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        url: 'ajax_performance_total_result.php',
                        success: function(response) {
                            $('.total_parcels').html(response.total_parcels);
                            $('.total_delivery_price').html(response.total_price);
                            $('.total_fuel_surcharge').html(response.total_fuel_surcharge);
                            $('.totalprice').html(response.totalprice);
                        }
                    });
                }
            </script>