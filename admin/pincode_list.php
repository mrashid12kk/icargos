<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver' && $_SESSION['type'] == 'admin')) {
  require_once "includes/role_helper.php";
  if (!checkRolePermission($_SESSION['user_role_id'], 8, 'add_only', $comment = null)) {

    header("location:access_denied.php");
  }
  include "includes/header.php";
?>

<body data-ng-app>


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

            <div class="row">
                <?php include "pages/location/location_sidebar.php"; ?>


                <div class="col-lg-10 " id="setting_box">
                    <div class="warper container-fluid">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="panel panel-primary">
                                <div class="panel-heading add_new_btn">
                                    Pincode List <a href="add_pincode.php" class="btn btn-info">Add New</a>
                                </div>
                                <div class="panel-body">
                                    <?php
                    if (isset($_GET['delete_id'])) {
                      $ex = $_GET['delete_id'];

                      mysqli_query($con, "DELETE FROM pincode WHERE id=" . $ex . " ");
                      $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Pincode Deleted Sucessfully.</div>';
                      header('Location: location_list.php');
                    }
                    ?>
                                    <div class=" table_template">

                                        <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                      } ?>
                                        <style>
                                        md-table-container thead input {
                                            width: 100%;
                                        }

                                        .overaly_container.list_popup_box {
                                            max-width: 99% !important;
                                        }

                                        table.md-table th.md-column md-icon {
                                            height: 12px;
                                            width: 9px;
                                            font-size: 9px !important;
                                            line-height: 16px !important;
                                        }

                                        .list_popup_box table.md-table td.md-cell,
                                        .list_popup_box table.md-table th.md-column {
                                            padding: 2px 3px !important;
                                        }

                                        md-icon svg {
                                            width: 12px;
                                            vertical-align: middle;
                                            margin-top: 1px;
                                        }

                                        .md-row th:nth-child(2) {
                                            width: 46px !important;
                                        }

                                        md-icon {
                                            height: 6px;
                                            width: 6px;
                                            min-height: 6px;
                                            min-width: 6px;
                                        }

                                        table.md-table td.md-cell {
                                            font-size: 11px;
                                        }

                                        table.md-table th.md-column {
                                            font-size: 10px;
                                        }

                                        md-table-container table.md-table thead.md-head>tr.md-row {
                                            height: 30px;
                                        }

                                        md-table-pagination .md-button md-icon {
                                            color: #000;
                                        }

                                        md-table-pagination .md-button[disabled] md-icon {
                                            color: #6d6d6d;
                                        }

                                        md-table-container img {
                                            height: 35px;
                                        }

                                        table.md-table th.md-column md-icon:not(:first-child) {
                                            margin-left: 0;
                                        }

                                        .template_content {
                                            padding: 7px 8px 10px;
                                            line-height: 16px;
                                            height: 24px;
                                            display: -webkit-box;
                                            max-width: 410px;
                                            -webkit-line-clamp: 2;
                                            -webkit-box-orient: vertical;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        }
                                        </style>
                                        <form>
                                            <div class="row">
                                                <div class="col-sm-4 side_gapp">
                                                    <label>Pincode</label>
                                                    <input type="text" class="form-control pincode" id="pincode"
                                                        placeholder="Enter Pincode">
                                                </div>
                                                <div class="col-sm-2">
                                                    <button class="btn btn-primary search_pincode"
                                                        style="margin-top: 23px;"> Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                        <br>
                                        <table class="table table-striped table-bordered  no-footer dtr-inline"
                                            id="pincode_table">
                                            <div class="fake_loader" id="image" style="text-align: center;">
                                                <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;">
                                            </div>
                                            <thead>
                                                <th>srno</th>
                                                <th>Country</th>
                                                <th>State</th>
                                                <th>City</th>
                                                <th>Pincode</th>
                                                <th>Action</th>
                                            </thead>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
            <!--  <?php

              // include "pages/location/location_sidebar.php";
              // include "pages/location/pincode_list.php";

              ?> -->


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
        document.addEventListener('DOMContentLoaded', function() {

            var dataTable = $('#pincode_table').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                // 'scrollCollapse': true,
                // 'ordering': false,
                // pageLength: 5,
                'responsive': true,
                'dom': "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
                // dom: '<"html5buttons"B>lTfgitp',
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
                ],
                'ajax': {
                    'url': 'ajax_pincode_list.php',
                    'data': function(data) {
                        var pincode = $('#pincode').val();
                        data.pincode = pincode;
                    },
                    beforeSend: function() {
                        $('#image').show();
                    },
                    complete: function() {
                        $('#image').hide();
                    },
                },
                'columns': [{
                        data: 'id'
                    },
                    {
                        data: 'country'
                    },
                    {
                        data: 'state'
                    },
                    {
                        data: 'city'
                    },
                    {
                        data: 'pincode'
                    },
                    {
                        data: 'action'
                    },

                ]
            });
            $('.search_pincode').click(function(e) {
                e.preventDefault();
                dataTable.draw();
            });
        }, false);
        </script>