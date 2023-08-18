<?php
if (isset($_GET['delete_id'])) {
    $ex = $_GET['delete_id'];

    mysqli_query($con, "DELETE FROM country WHERE id=" . $ex . " ");
    $_SESSION['msg'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong></strong> Country Deleted Sucessfully.</div>';
    header('Location: location_list.php');
}
?>
<div class="col-sm-12 outer_shadow table_template">
    <div class="top_heading">
        <h3 class="ng-binding">Delviery Routes</h3>
    </div>
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
    <?php


    $countryQ = mysqli_query($con, "SELECT * from country");
    $stateQ = mysqli_query($con, "SELECT * from state");
    $cityQ = mysqli_query($con, "SELECT * from city");
    $productQ = mysqli_query($con, "SELECT * from products");

    if (isset($_POST['submit'])) {
        $country        = isset($_POST['country']) ? $_POST['country'] : '';
        $state          = isset($_POST['state']) ? $_POST['state'] : '';
        $city           = isset($_POST['city']) ? $_POST['city'] : '';
        $route_code     = isset($_POST['route_code']) ? $_POST['route_code'] : '';
        $route_name     = isset($_POST['route_name']) ? $_POST['route_name'] : '';
        $pickup_commission = isset($_POST['pickup_commission']) ? $_POST['pickup_commission'] : '';
        $delivery_commission = isset($_POST['delivery_commission']) ? $_POST['delivery_commission'] : '';
        $product        = isset($_POST['product']) ? $_POST['product'] : '';
        $edit_id        = isset($_POST['edit_id']) ? $_POST['edit_id'] : '';

        if (isset($edit_id) && !empty($edit_id)) {
            $sql = 'UPDATE `delivery_routes` SET `country`="' . $country . '",`state`="' . $state . '",`city`="' . $city . '",`route_code`="' . $route_code . '",`route_name`="' . $route_name . '",`pickup_commission`="' . $pickup_commission . '",`delivery_commission`="' . $delivery_commission . '",`product`="' . $product . '"  WHERE id = ' . $edit_id . '';
            $message = 'Delivery Route Updated Successfully';
        } else {
            $sql = 'INSERT INTO `delivery_routes`(`country`, `state`, `city`, `route_code`, `route_name`, `pickup_commission`, `delivery_commission`, `product`) VALUES ("' . $country . '","' . $state . '","' . $city . '","' . $route_code . '","' . $route_name . '","' . $pickup_commission . '","' . $delivery_commission . '","' . $product . '")';
            $message = 'Delivery Route Added Successfully';
        }

        mysqli_query($con, $sql);

        $class = 'success';
        $_SESSION['update_class'] = $class;
        $_SESSION['update_title'] = 'Sussess';
        $_SESSION['update_message'] = $message;
        header('Location: delivery-routes.php?status=done');
    }
    $allData = mysqli_query($con, "SELECT * from delivery_routes order by id desc");
    $edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
    if ($edit_id) {
        $rowQ = mysqli_query($con, "SELECT * FROM delivery_routes where id = " . $edit_id . "");
        $row = mysqli_fetch_assoc($rowQ);
    }
    ?>
    <?php

    if (isset($_SESSION['update_message']) && !empty($_SESSION['update_message'])) {
    ?>
    <div class="alert alert-<?php echo $_SESSION['update_class'] ?> alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong><?php echo $_SESSION['update_title'] ?>!</strong> <?php echo $_SESSION['update_message'] ?>.
    </div>
    <?php

        unset($_SESSION['update_class']);
        unset($_SESSION['update_message']);
        unset($_SESSION['update_title']);
    }
    ?>
    <form action="" method="POST" autocomplete="off">
        <input type="hidden" name="edit_id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>">
        <div class="row">
            <div class="col-sm-3">
                <label>Country</label>
                <select name="country" id="" class="form-control select2 country" required>
                    <option value=""> --select country--</option>
                    <?php while ($fetch1 = mysqli_fetch_assoc($countryQ)) { ?>
                    <option
                        <?php echo isset($row['country']) && $row['country'] == $fetch1['country_name'] ? 'selected' : '' ?>
                        value="<?php echo $fetch1['country_name']; ?>"><?php echo $fetch1['country_name']; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-sm-3">
                <label>State</label>
                <select name="state" id="" class="form-control select2 state" required>
                    <option value=""> --select state--</option>
                    <?php while ($fetch2 = mysqli_fetch_assoc($stateQ)) { ?>
                    <option
                        <?php echo isset($row['state']) && $row['state'] == $fetch2['state_name'] ? 'selected' : '' ?>
                        value="<?php echo $fetch2['state_name']; ?>"><?php echo $fetch2['state_name']; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-sm-3">
                <label>City</label>
                <select name="city" id="" class="form-control select2 city" required>
                    <option value=""> --select city--</option>
                    <?php while ($fetch3 = mysqli_fetch_assoc($cityQ)) { ?>
                    <option <?php echo isset($row['city']) && $row['city'] == $fetch3['city_name'] ? 'selected' : '' ?>
                        value="<?php echo $fetch3['city_name']; ?>"><?php echo $fetch3['city_name']; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-sm-3">
                <label>Route Code</label>
                <input type="text" value="<?php echo isset($row['route_code']) ? $row['route_code'] : ''; ?>"
                    name="route_code" class="form-control" placeholder="Route Code" required>
            </div>

            <div class="col-sm-3">
                <label>Route Name</label>
                <input type="text" value="<?php echo isset($row['route_name']) ? $row['route_name'] : ''; ?>"
                    name="route_name" class="form-control" placeholder="Route Name" required>
            </div>
            <div class="col-sm-3">
                <label>Pickup Commission</label>
                <input type="text"
                    value="<?php echo isset($row['pickup_commission']) ? $row['pickup_commission'] : ''; ?>"
                    name="pickup_commission" class="form-control" placeholder="Pickup Commission" required>
            </div>

            <div class="col-sm-3">
                <label>Delivery Commission</label>
                <input type="text"
                    value="<?php echo isset($row['delivery_commission']) ? $row['delivery_commission'] : ''; ?>"
                    name="delivery_commission" class="form-control" placeholder="Delivery Commission" required>
            </div>

            <div class="col-sm-3">
                <label>Product</label>
                <select name="product" id="" class="form-control select2">
                    <option value=""> --select product--</option>
                    <?php while ($fetch4 = mysqli_fetch_assoc($productQ)) { ?>
                    <option <?php echo isset($row['product']) && $row['product'] == $fetch4['name'] ? 'selected' : '' ?>
                        value="<?php echo $fetch4['name']; ?>"><?php echo $fetch4['name']; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-2">
                <button type="submit" name="submit" class="btn btn-primary search_pincode" style="margin-top: 23px;">
                    Submit</button>
            </div>
        </div>
    </form>
    <br>
    <table class="table table-striped table-bordered  no-footer dtr-inline dataTable">
        <thead>
            <tr>
                <th>SrNo.</th>
                <th>Country</th>
                <th>State</th>
                <th>City</th>
                <th>Route Code</th>
                <th>Route Name</th>
                <th>Product</th>
                <th>Pickup Commission</th>
                <th>Delivery Commission</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fetchData = mysqli_fetch_assoc($allData)) {

                $srono = 1;

            ?>
            <tr>
                <th><?php echo $srono++; ?></th>
                <td><?php echo isset($fetchData['country']) ? $fetchData['country'] : ''; ?></td>
                <td><?php echo isset($fetchData['state']) ? $fetchData['state'] : ''; ?></td>
                <td><?php echo isset($fetchData['city']) ? $fetchData['city'] : ''; ?></td>
                <td><?php echo isset($fetchData['route_code']) ? $fetchData['route_code'] : ''; ?></td>
                <td><?php echo isset($fetchData['route_name']) ? $fetchData['route_name'] : ''; ?></td>
                <td><?php echo isset($fetchData['product']) ? $fetchData['product'] : ''; ?></td>
                <td><?php echo isset($fetchData['pickup_commission']) ? $fetchData['pickup_commission'] : ''; ?></td>
                <td><?php echo isset($fetchData['delivery_commission']) ? $fetchData['delivery_commission'] : ''; ?>
                </td>
                <td>
                    <a href="delivery-routes.php?edit_id=<?php echo $fetchData['id']; ?>"><i class="fa fa-edit"></i></a>
                    <a href="delivery-routes.php?delete_id=<?php echo $fetchData['id']; ?>"><i
                            class="fa fa-trash"></i></a>
                </td>
            </tr>
            <?php } ?>

        </tbody>

    </table>
</div>