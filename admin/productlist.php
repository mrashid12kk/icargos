<?php
session_start();
require 'includes/conn.php';

if (isset($_SESSION['users_id'])) {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 62, 'view_only', $comment = null)) {

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
                <div class="page-header">
                    <h1><?php echo getLange('servicelist'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1>
                </div>
                <div class="row">
                    <?php
                    require_once "setup-sidebar.php";
                    ?>
                    <div class="col-sm-10 table-responsive" id="setting_box">
                        <?php
                        if (isset($_GET['delete_id'])) {
                            $id = $_GET['delete_id'];
                            $query1 = mysqli_query($con, "DELETE FROM products where id=" . $id) or die(mysqli_error($con));

                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you have delete a Product successfully</div>';
                            } else {
                                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not delete a Product unsuccessfully.</div>';
                            }
                            $delete = mysqli_query($con, "DELETE FROM `product_type_prices` WHERE product_id=" . $id);
                        }

                        if (isset($_POST['edit_product_type'])) {
                            $product_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
                            $name = isset($_POST['name']) ? $_POST['name'] : '';
                            $set_dimension = isset($_POST['set_dimension']) ? $_POST['set_dimension'] : '';
                            $max_weight = isset($_POST['max_weight']) ? $_POST['max_weight'] : '';
                            $max_width = isset($_POST['max_width']) ? $_POST['max_width'] : '';
                            $max_height = isset($_POST['max_height']) ? $_POST['max_height'] : '';
                            $max_length = isset($_POST['max_length']) ? $_POST['max_length'] : '';
                            $price_type = isset($_POST['price_type']) ? $_POST['price_type'] : '';
                            $product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';
                            $end = end(array_keys($_POST['end_range']));
                            if ($max_weight <= $_POST['end_range'][$end]) {
                                $volumetric_weight_mul = $max_width * $max_height * $max_length;
                                $volumetric_weight = $volumetric_weight_mul / 400;
                                if ($max_weight >= $volumetric_weight) {
                                    if (isset($product_id) && !empty($product_id)) {
                                        $sqlQuery = 'UPDATE `products` SET `product_type`="' . $product_type . '",`name`="' . $name . '",`price_type`="' . $price_type . '",`max_weight`="' . $max_weight . '",`set_dimension`="' . $set_dimension . '",`max_length`="' . $max_length . '",`max_height`="' . $max_height . '",`max_width`="' . $max_width . '" WHERE id= ' . $product_id . '';
                                        mysqli_query($con, $sqlQuery);
                                        $delete = mysqli_query($con, "DELETE FROM `product_type_prices` WHERE product_id=" . $product_id);
                                        // if (mysqli_affected_rows($con)) {
                                        foreach ($_POST['start_range'] as $key => $value) {
                                            $sqlQuery = 'INSERT INTO `product_type_prices`(`product_id`, `start_range`, `end_range`, `division_factor`) VALUES (' . $product_id . ', "' . $value . '", "' . $_POST['end_range'][$key] . '", "' . $_POST['division_factor'][$key] . '" ) ';
                                            mysqli_query($con, $sqlQuery);
                                        }
                                        // }
                                        // echo $sqlQuery;
                                        // die;
                                        echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> Product Type updated successfully</div>';
                                    } else {
                                        $sqlQuery = 'INSERT INTO `products`(`product_type`, `name`, `price_type`, `max_weight`, `set_dimension`, `max_length`, `max_height`, `max_width`) VALUES ( "' . $product_type . '", "' . $name . '" ,"' . $price_type . '","' . $max_weight . '","' . $set_dimension . '","' . $max_length . '","' . $max_height . '" ,"' . $max_width . '" ) ';
                                        mysqli_query($con, $sqlQuery);
                                        $insert_id = mysqli_insert_id($con);
                                        if ($insert_id > 0) {

                                            foreach ($_POST['start_range'] as $key => $value) {
                                                $sqlQuery = 'INSERT INTO `product_type_prices`(`product_id`, `start_range`, `end_range`, `division_factor`) VALUES (' . $insert_id . ', "' . $value . '", "' . $_POST['end_range'][$key] . '", "' . $_POST['division_factor'][$key] . '" ) ';
                                                mysqli_query($con, $sqlQuery);
                                            }

                                            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> Product Type updated successfully</div>';
                                        }
                                        // die;
                                    }
                                } else {
                                    echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Please Use Volumetric Weight Lass Than Maximum Weight</div>';
                                    $PTData = $_POST;
                                }
                            } else {
                                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> Please Use Maximum Weight Lass Than End Range</div>';
                                $PTData = $_POST;
                            }
                        }


                        echo $msg;
                        if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {

                            $id = $_GET['edit_id'];
                            $PTDataQ = mysqli_query($con, "SELECT * from products where id = " . $id . "");
                            $PTData = mysqli_fetch_assoc($PTDataQ);
                        }

                        ?>
                        <div class="panel panel-default fix_product_type">
                            <div class="panel-heading">
                                <?php echo isset($_GET['edit_id']) ? 'Edit' : 'Add'; ?> Product Type
                            </div>
                            <div class="panel-body" id="same_form_layout">
                                <form role="form" action="productlist.php<?php echo isset($_GET['edit_id']) ? '?edit_id=' . $_GET['edit_id'] : ''; ?>" method="post">
                                    <div id="cities">
                                        <div class="row" id="select_citites">
                                            <div class="col-md-12">
                                                <div class="form-group label_side_fix">
                                                    <label class="form-check-label">
                                                        <input type="radio" <?php if (isset($PTData['product_type']) && $PTData['product_type'] == 'domestic') {
                                                                                echo 'checked';
                                                                            } ?> class="form-check-input ml-0" name="product_type" value="domestic">Domestic
                                                    </label>
                                                    <label class="form-check-label">
                                                        <input type="radio" <?php if (isset($PTData['product_type']) && $PTData['product_type'] == 'international') {
                                                                                echo 'checked';
                                                                            } ?> class="form-check-input" name="product_type" value="international">International
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label"><span style="color: red;">*</span>Product Type Name</label>
                                                    <input type="text" class="form-control" name="name" placeholder="Product Type Name" value="<?php echo isset($PTData['name']) ? $PTData['name'] : ''; ?>" required="">
                                                    <div class="help-block with-service_code "></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <h3>Price Type</h3>
                                                <div class="form-group label_side_fix">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input ml-0" <?php if (isset($PTData['price_type']) && $PTData['price_type'] == 'per_kg') {
                                                                                                                echo 'checked';
                                                                                                            } ?> name="price_type" value="per_kg">Per KG Price
                                                    </label>
                                                    <label class="form-check-label">
                                                        <input type="radio" <?php if (isset($PTData['price_type']) && $PTData['price_type'] == 'fix') {
                                                                                echo 'checked';
                                                                            } ?> class="form-check-input" value="fix" name="price_type">Fix
                                                        Price
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 table_input">
                                                <table class="table table-striped show_product_type_weight">
                                                    <tbody>
                                                        <tr>
                                                            <td>Sr.No</td>
                                                            <th>Start Range In Kg</th>
                                                            <th>End Range In Kg</th>
                                                            <th>Division Factor</th>
                                                            <th></th>
                                                        </tr>
                                                        <?php
                                                        $srno = 0;
                                                        if (isset($_GET['edit_id'])) {
                                                            $queryweight = mysqli_query($con, "SELECT * FROM product_type_prices where product_id=" . $_GET['edit_id'] . " ORDER BY id ASC");
                                                            $rowscount = mysqli_affected_rows($con);
                                                            if ($rowscount > 0) {
                                                                foreach ($queryweight as $key => $weight) {
                                                                    ++$srno;
                                                        ?>
                                                                    <tr>
                                                                        <input type="hidden" name="product_type_prices_id[]" value="<?php echo $weight['id']; ?>">
                                                                        <td><b><?php echo $srno; ?></b></td>
                                                                        <td>
                                                                            <input type="text" name="start_range[]" value="<?php echo $weight['start_range']; ?>" required>
                                                                            <div class="help-block with-service_code "></div>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="end_range[]" value="<?php echo $weight['end_range']; ?>" required>
                                                                            <div class="help-block with-service_code "></div>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="division_factor[]" value="<?php echo isset($weight['division_factor']) ? $weight['division_factor'] : ''; ?>" required>
                                                                            <div class="help-block with-service_code "></div>
                                                                        </td>
                                                                        <td><?php if (isset($srno) && $srno == 1) { ?><button type="button" class="btn btn-success add_product_type_weight">+</button><?php } else { ?><button class='btn btn-danger remove_product_type_weight'>-</button><?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                <? php;
                                                                }
                                                                echo "<input type='hidden' value='" . $srno . "' class='srno'>";
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <input type="hidden" value="1" class="srno">
                                                                    <td><b><?php echo ++$srno; ?></b></td>
                                                                    <td>
                                                                        <input type="text" name="start_range[]" value="0" required>
                                                                        <div class="help-block with-service_code "></div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="end_range[]" value="0" required>
                                                                        <div class="help-block with-service_code "></div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="division_factor[]" value="0" required>
                                                                        <div class="help-block with-service_code "></div>
                                                                    </td>
                                                                    <td><button type="button" class="btn btn-success add_product_type_weight">+</button>
                                                                    </td>
                                                                </tr><?php
                                                                    }
                                                                } else { ?>
                                                            <tr>
                                                                <input type="hidden" value="1" class="srno">
                                                                <td><b><?php echo ++$srno; ?></b></td>
                                                                <td>
                                                                    <input type="text" name="start_range[]" value="0" required>
                                                                    <div class="help-block with-service_code "></div>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="end_range[]" value="0" required>
                                                                    <div class="help-block with-service_code "></div>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="division_factor[]" value="0" required>
                                                                    <div class="help-block with-service_code "></div>
                                                                </td>
                                                                <td><button type="button" class="btn btn-success add_product_type_weight">+</button>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12">
                                                <h3>Weight Info</h3>
                                                <h4>Maximum Weight(KG)</h4>
                                                <p>NOTE: Maximum Weight should not exceeded to your last weight rang. And
                                                    Also volumetric weight should not exceeded to maximum weight that is
                                                    calculated by this [(Length"with"Height")/400]
                                                </p>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="max_weight" placeholder="Maximum Weight" value="<?php echo isset($PTData['max_weight']) ? $PTData['max_weight'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <h3>Price Type</h3>
                                                <div class="form-group label_side_fix">
                                                    <h4>Do You want to Set Dimension</h4>
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input ml-0" name="set_dimension" <?php if (isset($PTData['set_dimension']) && $PTData['set_dimension'] == 'yes') {
                                                                                                                                    echo 'checked';
                                                                                                                                } ?> value="yes">Ye</label>
                                                    <label class="form-check-label">
                                                        <input type="radio" <?php if (isset($PTData['set_dimension']) && $PTData['set_dimension'] == 'no') {
                                                                                echo 'checked';
                                                                            } ?> class="form-check-input" name="set_dimension" value="no">No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Maximum Length</label>
                                                    <input type="number" class="form-control" value="<?php echo isset($PTData['max_length']) ? $PTData['max_length'] : ''; ?>" name="max_length" placeholder="Maximum Length" >
                                                    <div class="help-block with-service_code "></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Maximum Height</label>
                                                    <input type="text" class="form-control" value="<?php echo isset($PTData['max_height']) ? $PTData['max_height'] : ''; ?>" name="max_height" placeholder="Maximum Height" >
                                                    <div class="help-block with-service_code "></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Maximum Width</label>
                                                    <input type="text" class="form-control" value="<?php echo isset($PTData['max_width']) ? $PTData['max_width'] : ''; ?>" name="max_width" placeholder="Maximum Width">
                                                    <div class="help-block with-service_code "></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 rtl_full">
                                            <button type="submit" name="edit_product_type" class="add_form_btn disabled">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php echo getLange('servicetype'); ?>
                            </div>
                            <div class="panel-body" id="same_form_layout" style="padding: 11px;">
                                <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 2%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;"><?php echo getLange('srno'); ?></th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Product Type </th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Product Name </th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Maximum Weight </th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Maximum Length </th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Maximum Height </th>
                                                <th style="width: 20%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Maximum Width </th>
                                                   <th style="width: 10%;" class="sorting_asc" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 179px;">Check</th>
                                                <th class="sorting" tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 108px;"><?php echo getLange('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query1 = mysqli_query($con, "Select * from products order by id desc");
                                            $sr = 1;
                                            while ($fetch1 = mysqli_fetch_array($query1)) {
                                            ?>
                                                <tr class="gradeA odd" role="row">
                                                    <td><?php echo $sr; ?></td>
                                                    <td class="sorting_1"><?php echo $fetch1['product_type']; ?></td>
                                                    <td class="sorting_1" style="text-transform: uppercase;">
                                                        <?php echo $fetch1['name']; ?>
                                                    </td>
                                                    <td class="sorting_1" style="text-transform: uppercase;">
                                                        <?php echo $fetch1['max_weight']; ?>
                                                    </td>
                                                    <td class="sorting_1" style="text-transform: uppercase;">
                                                        <?php echo $fetch1['max_length']; ?>
                                                    </td>
                                                    <td class="sorting_1" style="text-transform: uppercase;">
                                                        <?php echo $fetch1['max_height']; ?>
                                                    </td>
                                                    <td class="sorting_1" style="text-transform: uppercase;">
                                                        <?php echo $fetch1['max_width']; ?>
                                                    </td>
                                                    <td class="sorting_1" style="text-transform: uppercase;">
                                                    <input type="checkbox" class="checkit" id="<?php echo $fetch1['id']; ?>" name="checked[<?php echo $fetch1['id']; ?>]" value="<?php echo (isset($fetch1['checkbox']) && $fetch1['checkbox'])?"1":"0"; ?>" <?php echo (isset($fetch1['checkbox']) && $fetch1['checkbox'])?"checked":""; ?>/>
                                                    </td>
                                                    <td class="center">
                                                        <a href="productlist.php?edit_id=<?php echo $fetch1['id']; ?>">
                                                            <span class="glyphicon glyphicon-edit"></span>
                                                        </a>
                                                        <a href="productlist.php?delete_id=<?php echo $fetch1['id']; ?>" onclick="return confirm('Are you sure you want to delete this Product?');">
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
        <script type="text/javascript">
            $('.add_product_type_weight').click(function() {
                var srno = $('.srno').val();
                var srno = parseFloat(srno) + 1;
                var producttype = "<tr><td><b>" + srno +
                    "</b></td><td><input type='text' name='start_range[]' value='0'></td><td><input type='text' name='end_range[]' value='0'></td><td><input type='text' name='division_factor[]' value='0'></td><td><button type='button' class='btn btn-danger remove_product_type_weight'>-</button></td></tr>";
                $('.srno').val(srno);
                $(".show_product_type_weight").append(producttype);
            });
            $(document).on("click", ".remove_product_type_weight", function() {
                $(this).closest('tr').remove();
                var srno = $('.srno').val();
                var srno = parseFloat(srno) - 1;
                $('.srno').val(srno);
            });
                      $('.checkit').on('change',  function(){
                 var val = $(this).attr('id');
               // alert('checked');
                $.ajax({
        url: 'checked.php',
        type: "Post",
        async: true,
        data: { id:val },
        success: function (data) {
           // alert(data);
        },
        error: function (xhr, exception) {
            alert('ok');
            var msg = "";
            if (xhr.status === 0) {
                msg = "Not connect.\n Verify Network." + xhr.responseText;
            } else if (xhr.status == 404) {
                msg = "Requested page not found. [404]" + xhr.responseText;
            } else if (xhr.status == 500) {
                msg = "Internal Server Error [500]." +  xhr.responseText;
            } else if (exception === "parsererror") {
                msg = "Requested JSON parse failed.";
            } else if (exception === "timeout") {
                msg = "Time out error." + xhr.responseText;
            } else if (exception === "abort") {
                msg = "Ajax request aborted.";
            } else {
                msg = "Error:" + xhr.status + " " + xhr.responseText;
            }
           
        }
    }); 

            })
        </script>