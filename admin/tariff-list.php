<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && $_SESSION['type'] == 'admin') {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 63, 'view_only', $comment = null)) {
        header("location:access_denied.php");
    }
    include "includes/header.php";

?>

<body data-ng-app>
    <style type="text/css">
    .label {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 100%;
        font-weight: bold;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
        float: left;
        margin: 2px;
        width: 100%;
    }

    .city_dropdown {
        max-height: 186px;
        overflow-y: auto;
        overflow-x: hidden;
        min-height: auto;
    }
    </style>

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
                <?php
                    require_once "setup-sidebar.php";
                    ?>
                <div class="col-sm-10 table-responsive" id="setting_box">
                    <?php
                        $msg = "";
                        if (isset($_POST['addcities'])) {
                            for ($i = 0; $i < count($_POST['city']); $i++) {

                                $query1 = mysqli_query($con, "INSERT INTO `cities`(`city_name`) VALUES ('" . $_POST['city'][$i] . "')") or die(mysqli_error($con));
                                $rowscount = mysqli_affected_rows($con);
                                if ($rowscount > 0) {
                                    $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new City successfully</div>';
                                } else {
                                    $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new City unsuccessfully.</div>';
                                }
                            }
                        }
                        echo $msg;
                        ?>




                    <div class="panel panel-default">
                        <div class="panel-heading">Tariff List
                            <a href="add-tarif-setup.php" class="add_form_btn" style="float: right;font-size: 11px;">Add
                                Tariff Setup </a>
                        </div>
                        <div class="panel-body" id="same_form_layout" style="padding: 11px;">
                            <div id="basic-datatable_wrapper"
                                class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">


                                <table cellpadding="0" cellspacing="0" border="0"
                                    class="table table-striped table-bordered dataTable no-footer" id="basic-datatable"
                                    role="grid" aria-describedby="basic-datatable_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 2%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;"><?php echo getLange('srno'); ?></th>

                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Tariff Name</th>
                                            <th style="width: 2%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Product</th>

                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;"><?php echo getLange('servicetype'); ?> </th>

                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Tariff Mapping</th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Pay Mode</th>
                                            <!-- <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Weight Lower Limit </th>
                                            <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Weight Upper Limit </th> -->

                                            <!-- <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Rate </th> -->
                                            <!-- <th style="width: 20%;" class="sorting_asc" tabindex="0"
                                                aria-controls="basic-datatable" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending"
                                                style="width: 179px;">Additional Charges</th> -->


                                            <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1"
                                                colspan="1" aria-label="CSS grade: activate to sort column ascending"
                                                style="width: 108px;"><?php echo getLange('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php


                                            $query1 = mysqli_query($con, "Select tariff.* from tariff order by id desc");
                                            $sr = 1;
                                            while ($fetch1 = mysqli_fetch_array($query1)) {
                                                $zone_id = $fetch1['id'];
                                                $service_type = $fetch1['service_type'];
                                                $product_id = $fetch1['product_id'];
                                                $tariff_mapping_id = $fetch1['tariff_mapping_id'];
                                                $zone_cities_query = mysqli_query($con, "SELECT * FROM zone_cities WHERE zone='" . $zone_id . "' ");
                                                $service_query = mysqli_query($con, "SELECT * FROM services WHERE id='" . $service_type . "' ");
                                                $services_fetch = mysqli_fetch_array($service_query);
                                                $service_type = $services_fetch['service_type'];

                                                $product_query = mysqli_query($con, "SELECT * FROM products WHERE id=" . $product_id);
                                                $product_fetch = mysqli_fetch_array($product_query);
                                                $product_name = $product_fetch['name'];

                                                $maping_query = mysqli_query($con, "SELECT * FROM  tarrif_mapping WHERE id=" . $tariff_mapping_id);
                                                $fetchMapping = mysqli_fetch_array($maping_query);
                                                $mappingName = $fetchMapping['name'];

                                            ?>
                                        <tr class="gradeA odd" role="row">
                                            <td><?php echo $sr; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['tariff_name']; ?></td>
                                            <td class="sorting_1"><?php echo $product_name; ?></td>
                                            <td class="sorting_1"><?php echo $service_type; ?></td>
                                            <td class="sorting_1"><?php echo $mappingName; ?></td>
                                            <td class="sorting_1"><?php echo getpaymodeById($fetch1['pay_mode']); ?></td>
                                            <!-- <td class="sorting_1"><?php echo $fetch1['weight_lower_limit']; ?></td> -->
                                            <!-- <td class="sorting_1"><?php echo $fetch1['weight_upper_limit']; ?></td> -->
                                            <!-- <td class="sorting_1"><?php echo $fetch1['rate']; ?></td> -->
                                            <!-- <td class="sorting_1"><?php echo $fetch1['additional_charges']; ?></td> -->
                                            <td class="center">
                                                <a href="edit-tarif-setup.php?tariff_id=<?php echo $fetch1['id']; ?>">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                </a>
                                                <a href="deletetariff.php?tariff_id=<?php echo $fetch1['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this Tariff?');">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                                $sr++;
                                            }

                                            ?>
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