<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && $_SESSION['type'] == 'admin') {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 18, 'add_only', $comment = null)) {
        header("location:access_denied.php");
    }
    include "includes/header.php";

    $riderdata = mysqli_query($con, "Select * from users WHERE type='driver' ");
    $servicetypes = mysqli_query($con, "Select * from services WHERE 1 ");
    $products = mysqli_query($con, "SELECT * FROM products ORDER BY id DESC ");
    $tarifs = mysqli_query($con, "SELECT * FROM 	tarrif_mapping ORDER BY calculation_priority ASC ");
    $paymodes = mysqli_query($con, "SELECT * FROM pay_mode ORDER BY id ASC ");
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
                        <div class="panel-heading">Add Tariff Setup </div>
                        <div class="panel-body" id="same_form_layout">
                            <form role="form" data-toggle="validator" action="savetariff.php" method="post">
                                <div id="cities">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label"><span style="color: red;">*</span>Tariff Setup Name</label>
                                                <input type="text" class="form-control" name="tariff_name"
                                                    placeholder="Tariff Setup Name" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label"><span style="color: red;">*</span>Pay Mode </label>

                                                <Select required name="pay_mode" class="form-control select2" required>
                                                    <option value="">--select pay mode--</option>
                                                    <?php while ($pay = mysqli_fetch_assoc($paymodes)) { ?>
                                                    <option
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
                                                <label class="control-label"><span style="color: red;">*</span>Product </label>
                                                <select class="form-control select2 product" name="product_id" required>
                                                    <option value="">--select product--</option>
                                                    <?php if (isset($products) && !empty($products)) {
                                                            foreach ($products as $row) { ?>
                                                    <option value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
                                                        <?php echo isset($row['name']) ? $row['name'] : ''; ?></option>
                                                    <?php }
                                                        } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label"><span style="color: red;">*</span>Tariff Mapping</label>
                                                <Select required name="tariff_mapping_id"
                                                    class="form-control tariff_mapping select2">
                                                    <option value="">Select Tariff Type</option>
                                                    <?php while ($row = mysqli_fetch_assoc($tarifs)) { ?>
                                                    <option value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?>
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
                                                    <option value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
                                                        <?php echo isset($row['service_type']) ? $row['service_type'] : ''; ?>
                                                    </option>
                                                    <?php }
                                                        } ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="panel panel-default manual_origin_destination display_none">
                                            <div class="panel-heading dynamic_panel_heading"></div>
                                            <input type="hidden" name="mappingFor" class="mappingFor" value="">
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
                                                    <tr>
                                                        <td>
                                                            <div class="form-group">
                                                                <select class="form-control city_form origin_list "
                                                                    name="origin[]">

                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <select
                                                                    class="form-control city_to get_city_name destination_list"
                                                                    name="destination[]">

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
                                                    <?php
                                                        $loop++;
                                                        ?>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row product_prices_div display_none">
                                        <table class="table table-bordered table-stripped">
                                            <thead>
                                                <tr>
                                                    <td>Sr No</td>
                                                    <td>Start Range</td>
                                                    <td>End Range</td>
                                                    <td>Division Factor</td>
                                                    <td>Rate</td>
                                                </tr>
                                            </thead>
                                            <tbody class="product_prices">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 submit_padd">
                                            <button type="submit" name="addTariff"
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