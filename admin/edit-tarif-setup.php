<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && $_SESSION['type'] == 'admin') {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 18, 'add_only', $comment = null)) {
        header("location:access_denied.php");
    }
    include "includes/header.php";
    $edit_id = $_GET['tariff_id'];
    $riderdata = mysqli_query($con, "Select * from users WHERE type='driver' ");
    $servicetypes = mysqli_query($con, "Select * from services WHERE 1 ");
    $products = mysqli_query($con, "SELECT * FROM products ORDER BY id DESC ");
    $tarifs = mysqli_query($con, "SELECT * FROM 	tarrif_mapping ORDER BY calculation_priority ASC ");
    $paymodes = mysqli_query($con, "SELECT * FROM pay_mode ORDER BY id ASC ");
    $fetchDataSql = "SELECT * FROM tariff WHERE id =" . $edit_id;

    $fetchData = mysqli_query($con, $fetchDataSql);
    $getData = mysqli_fetch_assoc($fetchData);
    $tariff_city_q = mysqli_query($con, "SELECT * FROM tariff_cities WHERE tariff_id = $edit_id ");
    $tariff_detail_q = mysqli_query($con, "SELECT * FROM tariff_detail WHERE tariff_id = " . $edit_id . " ORDER BY id ASC");
    $heading = '';
    $show_header = 0;
    $value = $getData['tariff_mapping_id'];

    if ($value == 3) {
        $heading = 'Zone Mapping';
        $show_header = 1;
    } elseif ($value == 6) {
        $heading = 'City Mapping';
        $show_header = 1;
    } elseif ($value == 9) {
        $heading = 'State Mapping';
        $show_header = 1;
    } elseif ($value == 12) {
        $heading = 'Country Mapping';
        $show_header = 1;
    } else {
        $show_header = 0;
    }

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
            <div class="page-header">
                <h1>Tariff Setup <small><?php echo getLange('letsgetquick'); ?></small></h1>
            </div>
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
                                    $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('Well_done') . '!</strong> ' . getLange('you_added_a_new_city_successfully') . '</div>';
                                } else {
                                    $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('unsuccessful') . '!</strong> ' . getLange('you_have_not_added_a_new_city_unsuccessfully') . '.</div>';
                                }
                            }
                        }
                        echo $msg;
                        if (isset($_SESSION['tariff-msg'])) {
                            echo $_SESSION['tariff-msg'];
                            unset($_SESSION['tariff-msg']);
                        }
                        ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">Edit Tariff Setup </div>
                        <div class="panel-body" id="same_form_layout">
                            <form role="form" action="updatetariff.php" method="post">
                                <input type="hidden" name="tariff_id" value="<?php echo $getData['id'] ?>">
                                <div id="cities">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Tariff Setup Name</label>
                                                <input type="text" class="form-control" name="tariff_name"
                                                    placeholder="Tariff Setup Name" required
                                                    value="<?php echo $getData['tariff_name'] ?>">
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Pay Mode </label>
                                                <Select required name="pay_mode" class="form-control select2" required>
                                                    <option value="">--select pay mode--</option>
                                                    <?php while ($pay = mysqli_fetch_assoc($paymodes)) { ?>
                                                    <option
                                                        <?php echo isset($getData['pay_mode']) && $getData['pay_mode'] == $pay['id'] ? "selected" : ''; ?>
                                                        value="<?php echo isset($pay['id']) ? $pay['id'] : ''; ?>">
                                                        <?php echo isset($pay['pay_mode']) ? $pay['pay_mode'] : ''; ?>
                                                    </option>
                                                    <?php } ?>

                                                </select>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Product </label>
                                                <select class="form-control select2 product" name="product_id" required>
                                                    <option value="">--select product--</option>
                                                    <?php if (isset($products) && !empty($products)) {
                                                            foreach ($products as $row) { ?>
                                                    <option
                                                        <?php echo isset($getData['product_id']) && $getData['product_id'] == $row['id'] ? "selected" : ''; ?>
                                                        value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
                                                        <?php echo isset($row['name']) ? $row['name'] : ''; ?></option>
                                                    <?php }
                                                        } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Tariff Mapping</label>
                                                <Select required name="tariff_mapping_id"
                                                    class="form-control tariff_mapping select2">
                                                    <option value="">Select Tariff Type</option>
                                                    <?php while ($row = mysqli_fetch_assoc($tarifs)) { ?>
                                                    <option
                                                        <?php echo isset($getData['tariff_mapping_id']) && $getData['tariff_mapping_id'] == $row['id'] ? "selected" : ''; ?>
                                                        value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </Select>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label"><?php echo getLange('servicetype'); ?>
                                                </label>
                                                <select class="form-control select2" name="service_type">
                                                    <?php if (isset($servicetypes) && !empty($servicetypes)) {
                                                            foreach ($servicetypes as $row) { ?>
                                                    <option
                                                        <?php echo isset($getData['service_type']) && $getData['service_type'] == $row['id'] ? "selected" : ''; ?>
                                                        value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
                                                        <?php echo isset($row['service_type']) ? $row['service_type'] : ''; ?>
                                                    </option>
                                                    <?php }
                                                        } ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div
                                            class="panel panel-default manual_origin_destination <?php echo isset($show_header) && $show_header == 0 ? 'display_none' : ''; ?>">
                                            <div class="panel-heading dynamic_panel_heading"><?php echo $heading; ?>
                                            </div>
                                            <input type="hidden" name="mappingFor" class="mappingFor"
                                                value="<?php echo $heading; ?>">
                                            <div class="panel-body">
                                                <table class="table add_cities" id="price_table">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo getLange('origin'); ?></th>
                                                            <th><?php echo getLange('destination'); ?></th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <?php
                                                        $loop = 0;
                                                        mysqli_data_seek($origincitydata, 0);
                                                        mysqli_data_seek($destcitydata, 0);


                                                        ?>

                                                    <?php while ($cityS = mysqli_fetch_assoc($tariff_city_q)) {

                                                            $origin = '';
                                                            $destination = '';
                                                            $value = $getData['tariff_mapping_id'];

                                                            if ($value == 3) {
                                                                $origincitydata = mysqli_query($con, "SELECT * from zone_type  ");
                                                                $destcitydata = mysqli_query($con, "SELECT * from zone_type ");
                                                                while ($row = mysqli_fetch_array($origincitydata)) {
                                                                    $selected = '';
                                                                    if (isset($cityS['origin']) && $row['zone_name'] == $cityS['origin']) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    $origin .= '<option ' . $selected . ' value="' . $row['zone_name'] . '">' . $row["zone_name"] . '</option>';
                                                                }
                                                                while ($row = mysqli_fetch_array($destcitydata)) {
                                                                    $selected = '';
                                                                    if (isset($cityS['destination']) && $row['zone_name'] == $cityS['destination']) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    $destination .= '<option ' . $selected . ' value="' . $row['zone_name'] . '">' . $row["zone_name"] . '</option>';
                                                                }
                                                            } elseif ($value == 6) {
                                                                $origincitydata = mysqli_query($con, "SELECT * from cities Where city_name IS NOT NULL order by city_name ");
                                                                $destcitydata = mysqli_query($con, "SELECT * from cities Where city_name IS NOT NULL order by city_name");
                                                                while ($row = mysqli_fetch_array($origincitydata)) {
                                                                    $selected = '';
                                                                    if (isset($cityS['origin']) && $row['city_name'] == $cityS['origin']) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    $origin .= '<option ' . $selected . ' value="' . $row['city_name'] . '">' . $row["city_name"] . '</option>';
                                                                }
                                                                while ($row = mysqli_fetch_array($destcitydata)) {
                                                                    $selected = '';
                                                                    if (isset($cityS['destination']) && $row['city_name'] == $cityS['destination']) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    $destination .= '<option ' . $selected . ' value="' . $row['city_name'] . '">' . $row["city_name"] . '</option>';
                                                                }
                                                            } elseif ($value == 9) {
                                                                $origincitydata = mysqli_query($con, "SELECT * from state ");
                                                                $destcitydata = mysqli_query($con, "SELECT * from state");
                                                                while ($row = mysqli_fetch_array($origincitydata)) {
                                                                    $selected = '';
                                                                    if (isset($cityS['origin']) && $row['state_name'] == $cityS['origin']) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    $origin .= '<option ' . $selected . ' value="' . $row['state_name'] . '">' . $row["state_name"] . '</option>';
                                                                }
                                                                while ($row = mysqli_fetch_array($destcitydata)) {
                                                                    $selected = '';
                                                                    if (isset($cityS['destination']) && $row['state_name'] == $cityS['destination']) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    $destination .= '<option ' . $selected . ' value="' . $row['state_name'] . '">' . $row["state_name"] . '</option>';
                                                                }
                                                            } elseif ($value == 12) {
                                                                $origincitydata = mysqli_query($con, "SELECT * from country ");
                                                                $destcitydata = mysqli_query($con, "SELECT * from country");
                                                                while ($row = mysqli_fetch_array($origincitydata)) {
                                                                    $selected = '';
                                                                    if (isset($cityS['origin']) && $row['country_name'] == $cityS['origin']) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    $origin .= '<option ' . $selected . ' value="' . $row['country_name'] . '">' . $row["country_name"] . '</option>';
                                                                }
                                                                while ($row = mysqli_fetch_array($destcitydata)) {
                                                                    $selected = '';
                                                                    if (isset($cityS['destination']) && $row['country_name'] == $cityS['destination']) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    $destination .= '<option ' . $selected . ' value="' . $row['country_name'] . '">' . $row["country_name"] . '</option>';
                                                                }
                                                            }


                                                        ?>
                                                    <tr>
                                                        <td>
                                                            <div class="form-group">
                                                                <select class="form-control city_form origin_list "
                                                                    name="origin[]">
                                                                    <?php echo $origin; ?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <select
                                                                    class="form-control city_to get_city_name destination_list"
                                                                    name="destination[]">
                                                                    <?php echo $destination; ?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php if ($loop == 0) { ?>
                                                            <a href="#" class="add_row btn btn-info"><i
                                                                    class="fa fa-plus"></i></a>
                                                            <?php } else { ?>
                                                            <a href="#" class="remove_row btn btn-danger"><i
                                                                    class="fa fa-trash"></i></a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>

                                                    <?php
                                                        $loop++;
                                                        ?>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row product_prices_div ">
                                        <table class="table table-bordered table-stripped">
                                            <thead>
                                                <tr>
                                                    <td>Sr No</td>
                                                    <td>Start Range</td>
                                                    <td>End Range</td>
                                                    <td>Rate</td>
                                                </tr>
                                            </thead>
                                            <tbody class="product_prices">
                                                <?php $sno = 1;
                                                    while ($detailData = mysqli_fetch_assoc($tariff_detail_q)) {

                                                    ?>
                                                <tr>
                                                    <td><?php echo $sno++; ?></td>
                                                    <td><input type="number" name="start_range[]"
                                                            value="<?php echo $detailData['start_range']; ?>"
                                                            class="form-control" readonly></td>
                                                    <td><input type="number" name="end_range[]"
                                                            value="<?php echo $detailData['end_range']; ?>"
                                                            class="form-control" readonly></td>
                                                    <td><input type="number" name="rate[]"
                                                            value="<?php echo $detailData['rate']; ?>"
                                                            class="form-control"></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 submit_padd">
                                            <button type="submit" name="updateTariff"
                                                class="add_form_btn"><?php echo getLange('submit'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
        $('.select2').select2();
        $(document).ready(function() {
            $('.tariff_mapping').change(function() {
                let value = $("select.tariff_mapping option").filter(":selected").val();
                $.ajax({
                    url: "ajax.php", //the page containing php script
                    type: "post", //request type,
                    dataType: 'json',
                    data: {
                        getTariffData: 1,
                        value: value
                    },
                    success: function(result) {
                        if (result.show_header === 1) {
                            $('.manual_origin_destination').removeClass('display_none');
                            $('.dynamic_panel_heading').text(result.heading);
                            $('.mappingFor').val(result.heading);
                            $('.origin_list').html(result.origin);
                            $('.destination_list').html(result.destination);
                        } else {
                            $('.manual_origin_destination').addClass('display_none');
                            $('.dynamic_panel_heading').text('');
                            $('.mappingFor').val('');
                            $('.origin_list').html('');
                            $('.destination_list').html('');
                        }
                    }
                });
            })

            var counter = 1;
            var selected_to_array = [];
            $('body').on('click', '.add_row', function(e) {
                e.preventDefault();
                var counter = $('#price_table > tbody tr').length;
                var row = $('#price_table > tbody tr').first().clone();
                row.find('input,select').each(function() {
                    var name = $(this).attr('name').split('[0]');
                    // $(this).attr('name', name[0] + '[' + counter + ']' + name[1]);
                })
                row.find('.add_row').addClass('remove_row');
                row.find('.add_row').addClass('btn btn-danger');
                row.find('.fa-plus').addClass('fa-trash');
                row.find('.fa-plus').removeClass('fa-plus');
                row.find('.add_row').removeClass('add_row');
                $('#price_table').append(row);
            })
            $('body').on('click', '.remove_row', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });
            $('body').on('change', '.product', function(e) {
                let productValue = $(this).val();
                $.ajax({
                    url: "ajax.php",
                    type: "post",
                    data: {
                        getProductPrices: 1,
                        productValue: productValue
                    },
                    success: function(result) {
                        $('body').find('.product_prices_div').removeClass('display_none');
                        $('body').find('.product_prices').html(result);
                        if (result === '') {
                            $('body').find('.product_prices_div').addClass('display_none');
                        }
                    }
                });
            })
        })
        </script>