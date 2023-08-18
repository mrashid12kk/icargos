<?php

// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);

session_start();

include_once "includes/conn.php";

$companyname = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='companyname' "));





$logo_img = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='logo' "));







?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>

        Document

    </title>

    <style>
        .cons_dtls {
            width: 33%;
        }

        .d_flex_cus {

            display: flex;



        }

        .title_small {

            font-size: 10px;

            display: block;

        }

        .list_fix ul {

            padding: 0;

            margin: 0;

        }

        .list_fix ul li {

            list-style: none;

            padding: 5px 8px;

            display: flex;

            font-size: 14px;

        }

        .list_fix {

            border: 1px solid #000;

            background: #f5f5f5;

        }

        .list_fix:first-child,
        .table_gap:first-child {

            margin-right: 3px;

        }

        .list_fix:last-child,
        .table_gap:last-child {

            margin-left: 3px;

        }

        .list_fix ul li .left_col {

            width: 50%;

            font-weight: 600;

        }

        .list_fix ul li .right_col {
            text-align: left;
            width: 50%;
            padding: 0 0 0 9px;
        }

        .logo_cus img {

            width: 100px;

            display: block;

            padding-bottom: 8px;

        }

        .col_cus {

            width: 50%;

        }

        .col_cus100 table th {

            font-size: 12px;

        }

        .col_cus h1 {

            font-size: 31px;

            margin: 15px 0;

            font-weight: 600;

            text-align: right;

        }

        .col_cus100 table {

            width: 100%;

            border-spacing: 0px;

            border-collapse: collapse;

        }

        .main_wrapper_cus tfoot,
        .main_wrapper_cus thead {

            background: #f5f5f5;

        }

        .col_cus100 {

            width: 100%;



        }

        .main_wrapper_cus {

            width: 800px;

            margin: 0 auto;

            padding: 10px;

            font-family: system-ui;

        }

        table th,
        table td {

            border: 1px solid #000;

            padding: 4px;

            font-size: 12px;
            font-weight: 500;

            vertical-align: top;

        }

        .text_cent {

            text-align: center;

            font-weight: 600;

            text-transform: uppercase;

            font-size: 15px;

            color: #c91717;

        }

        .col_cus40 {}

        .col_cus30 {}

        .ul_list_fix ul:first-child {

            width: 60%;

            float: left;

        }

        .ul_list_fix ul {}

        .list_fix .box {}

        .text_align {

            text-align: center;

        }

        .last_head_off {

            text-align: left;

            padding: 0;

        }

        .last_head_off li {

            list-style: none;

            display: inline-block;

            font-weight: 600;

            padding: 10px 8px 36px;

            margin: 0 5px;

            border-bottom: 2px solid #333;
            width: 21.3%;

        }

    }
</style>

</meta>

</meta>

</head>

<body>

    <?php

    if (isset($_GET['assignment_no']) && !empty($_GET['assignment_no'])) {

        $assignment_no = $_GET['assignment_no'];



        $result = mysqli_query($con, "select * from assignments where assignment_no='" . $assignment_no . "' ");





        $result_fetch = mysqli_fetch_array($result);
    }



    $branch_city = isset($_SESSION['branch_id']) ? getBranchCity($_SESSION['branch_id']) : '';

    $branch_city_name = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM cities WHERE id=" . $branch_city));



    $date = $result_fetch['created_on'];

    $assignment_no = $result_fetch['assignment_no'];

    $barcode_image = $result_fetch['barcode_image'];

    $destination_name = $result_fetch['destination'];

    $rider_q = mysqli_query($con, "SELECT id,Name FROM users WHERE id='" . $result_fetch['rider_id'] . "' ");

    $rider_res = mysqli_fetch_array($rider_q);

    $rider_id = $rider_res['id'];

    $rider_name = $rider_res['Name'];

    $orderQuery = mysqli_query($con, "SELECT delivery_zone_id FROM orders WHERE delivery_rider='" . $rider_id . "' AND delivery_assignment_no='" . $assignment_no . "'  ");
    $orderQueryRes = mysqli_fetch_assoc($orderQuery);
    $route_code = $orderQueryRes['delivery_zone_id'];
    $validZoneQuery = mysqli_query($con, "SELECT route_name FROM delivery_zone WHERE route_code = '" . $route_code . "' ");
    $routeCodeRes = mysqli_fetch_assoc($validZoneQuery);

    $deliveryZone = isset($routeCodeRes['route_name']) ? $routeCodeRes['route_name'] : '';
    // echo "SELECT id,Name FROM users WHERE id='".$result_fetch['rider_id']."' ";

    //   die();

    // echo "SELECT branch_id from users where Name=".$rider_name;

    // die();

    $get_branch_name = mysqli_query($con, "SELECT branch_id from users where id=" . $rider_res['id']);

    $current_branch = mysqli_fetch_assoc($get_branch_name);

    // print_r($current_branch);

    // die();

    if (isset($current_branch['branch_id']) && !empty($current_branch['branch_id'])) {

        $get_branch_q = mysqli_query($con, "SELECT name from branches where id=" . $current_branch['branch_id']);



        $current_branch_name = mysqli_fetch_assoc($get_branch_q);
    } else {

        $current_branch_name['name'] = 'Admin Branch';
    }

    ?>

    <div class="main_wrapper_cus">

        <div class="d_flex_cus">

            <div class="col_cus logo_cus">

                <img alt="" src="<?php echo BASE_URL ?>admin/<?php echo $logo_img['value'] ?>">

            </img>

        </div>

        <div class="col_cus">

            <h1>

                Shipment Delivery Record

            </h1>

        </div>

    </div>

    <div class="d_flex_cus">

        <div class="col_cus list_fix ul_list_fix">

            <div class="box">

                <ul>

                    <li>

                        <span class="left_col">

                            Delivery Date

                        </span>

                        :

                        <span class="right_col"> <?php echo date('d M Y', strtotime($date)); ?>

                    </span>

                </li>

                <li>

                    <span class="left_col">

                        Sheet Number

                    </span>

                    :

                    <span class="right_col"><?php echo $assignment_no; ?>

                </span>

            </li>

        </ul>

        <ul>

            <li>

                <span class="left_col">

                    Station

                </span>

                :

                <span class="right_col">

                    <?php echo $branch_city_name['city_name']; ?>

                </span>

            </li>

        </ul>

    </div>

</div>

<div class="col_cus list_fix">

    <div class="box">

        <ul>

            <li>

                <span class="left_col" style="    width: 28%;">

                    Route

                </span>

                :

                <span class="right_col">
                    <?php echo $deliveryZone; ?>
                </span>

            </li>

            <li>

                <span class="left_col" style="    width: 28%;">

                    Courier Officer

                </span>

                :

                <span class="right_col">

                    <?php echo $rider_name; ?>

                </span>

            </li>

        </ul>

    </div>

</div>

</div>

<div class="d_flex_cus">

    <div class="col_cus100">

        <p class="text_cent">

            The Consignee Declares That He/she Has Received The Shipment (s) In Good Order And Condition

        </p>

        <div class="d_flex_cus ">

            <div class="col_cus table_gap">

                <table>

                    <thead>

                        <tr>

                            <th>

                                Sr

                            </th>

                            <th class="cons_dtls">

                                Consignment Detail

                            </th>

                            <th>

                                Receiver's Name

                            </th>

                            <th>

                                Sign/comments

                            </th>

                        </tr>

                    </thead>



                    <tbody>

                        <?php



                        $queries = mysqli_query($con, "SELECT * FROM orders WHERE delivery_rider='" . $rider_id . "' AND delivery_assignment_no='" . $assignment_no . "'  ");





                        $srno = 1;

                        $odd = 1;

                        $orders = 0;

                        $total_pieces = 0;

                        $total_weight = 0;

                        $total_cod = 0;

                        $total_delivery = 0;

                        while ($single_order = mysqli_fetch_array($queries)) {

                            $customer_id = $single_order['customer_id'];

                            $cus_q = mysqli_query($con, "SELECT fname,bname FROM customers WHERE id='" . $customer_id . "' ");

                            $cus_q_res = mysqli_fetch_array($cus_q);

                            $business_acc = $cus_q_res['bname'];

                            $total_pieces += $single_order['quantity'];

                            $total_weight += $single_order['weight'];

                            $total_cod += $single_order['collection_amount'];

                            $orders += 1;

                            if ($odd % 2 != 0) {



                                ?>

                                <tr>

                                    <th>

                                        <?php echo $srno++; ?>

                                    </th>

                                    <td> <b style="font-weight: 700;"><?php echo $single_order['rname']; ?></b>
                                        <br><?php echo $single_order['track_no']; ?>
                                    </td>

                                    <td></td>

                                    <td></td>

                                </tr>

                                <tr>

                                    <th>

                                    </th>



                                    <td>

                                        <span class="title_small" style="display: inline-block;">COD
                                        Amount:</span>
                                        <span><?php echo $single_order['collection_amount']; ?></span>

                                    </td>

                                    <td>

                                        <span class="title_small"><span class="title_small">Time</span></span>

                                    </td>

                                    <td>

                                        <span class="title_small">Relation</span>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                    </th>

                                    <td>

                                        <span class="title_small"> <span class="title_small"> Mobile No:</span></span>
                                        <?php echo $single_order['rphone']; ?>

                                    </td>

                                    <td class="text_align" colspan="2" style="font-size: 11px;text-align: left;">

                                        <p style="    margin: 0;height: 47px;overflow: hidden;min-height: auto;">
                                            <?php echo $single_order['receiver_address']; ?></p>

                                        </td>

                                    </tr>

                                <?php }
                                $odd++;
                            } ?>

                        </tbody>



                    </table>

                </div>

                <div class="col_cus table_gap">

                    <table>

                        <thead>

                            <tr>

                                <th>

                                    Sr

                                </th>

                                <th class="cons_dtls">

                                    Consignment Detail

                                </th>

                                <th>

                                    Receiver's Name

                                </th>

                                <th>

                                    Sign/comments

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            $queries = mysqli_query($con, "SELECT * FROM orders WHERE delivery_rider='" . $rider_id . "' AND delivery_assignment_no='" . $assignment_no . "'  ");



                            $even = 1;

                            while ($single_order = mysqli_fetch_array($queries)) {

                                $customer_id = $single_order['customer_id'];

                                $cus_q = mysqli_query($con, "SELECT fname,bname FROM customers WHERE id='" . $customer_id . "' ");

                                $cus_q_res = mysqli_fetch_array($cus_q);

                                $business_acc = $cus_q_res['bname'];

                                    // $total_pieces += $single_order['quantity'];

                                    // $total_weight += $single_order['weight'];

                                    // $total_cod += $single_order['collection_amount'];

                                if ($even % 2 == 0) {

                                    ?>

                                    <tr>

                                        <th>

                                            <?php echo $srno++; ?>

                                        </th>

                                        <td><?php echo $single_order['rname']; ?><br><?php echo $single_order['track_no']; ?>
                                    </td>

                                    <td></td>

                                    <td></td>

                                </tr>

                                <tr>

                                    <th>

                                    </th>



                                    <td>

                                        <span class="title_small" style="display: inline-block;">COD
                                        Amount:</span> <span style="display: inline-block;"><?php echo $single_order['collection_amount']; ?></span>

                                    </td>

                                    <td>

                                        <span class="title_small">Time:</span>

                                    </td>

                                    <td>

                                        <span class="title_small"><span class="title_small">Relation</span></span>

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                    </th>

                                    <td>

                                        <span class="title_small"> <span class="title_small"> Mobile No:</span></span>
                                        <?php echo $single_order['rphone']; ?>

                                    </td>

                                    <td class="text_align" colspan="2" style="font-size: 11px;text-align: left;">
                                        <p style="    margin: 0;height: 47px;overflow: hidden;min-height: auto;">
                                            <?php echo $single_order['receiver_address']; ?></p>


                                        </td>

                                    </tr>

                                <?php }
                                $even++;
                            } ?>

                        </tbody>



                    </table>

                </div>

            </div>


            <div class="d_flex_cus" style="    margin: 7px 0 0;">
                <div class="col_cus table_gap">
                    <table>
                        <tfoot>

                            <tr>

                                <th colspan="2" style="text-align: left;border-right: 0;">

                                    TOTAL SHIPMENTS : <span style="float: right;"><?php echo $orders ?></span>

                                </th>

                                <td colspan="2" style="border-left: 0;">

                                </td>

                            </tr>

                        </tfoot>
                    </table>
                </div>
                <div class="col_cus table_gap">
                    <table>
                        <tfoot>

                            <tr>

                                <th colspan="2" style="text-align: left; border-right: 0;">

                                    TOTAL PIECES : <span style="float: right;"><?php echo $total_pieces; ?></span>

                                </th>

                                <td colspan="2" style="border-left: 0;">

                                </td>

                            </tr>

                        </tfoot>
                    </table>
                </div>
                <div class="col_cus table_gap">
                    <table>
                        <tfoot>

                            <tr>


                                <td colspan="12" >
                                    TOTAL COD : <span style="float: right;"><?php echo isset($total_cod) ? $total_cod : '0'; ?></span>
                                </td>

                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>


            <ul class="last_head_off">

                <li>

                    Courier Officer

                </li>

                <li>

                    Security Officer

                </li>

                <li>

                    Operation Incharge

                </li>

                <li>

                    De-Breifiing Incharge

                </li>

            </ul>

        </div>

    </div>

</div>

</body>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        window.print();
    }, false);
</script>

</html>