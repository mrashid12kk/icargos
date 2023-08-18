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
<!--<![endif]-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Account Sheet</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,600,700,800,900' rel='stylesheet'
        type='text/css'>
    <style>
    /* Default Font Styles
______________________*/
    body,
    input,
    select,
    textarea,
    p,
    a,
    b {
        font-family: 'Roboto', sans-serif;
        color: #000;
        line-height: 1.4;
        font-weight: 600 !important;
    }

    .fl {
        float: left
    }

    .fr {
        float: right
    }

    .cl {
        clear: both;
        font-size: 0;
        height: 0;
    }

    .clearfix:after {
        clear: both;
        content: ' ';
        display: block;
        font-size: 0;
        line-height: 0;
        visibility: hidden;
        width: 0;
        height: 0;
    }

    /* p, blockquote, address
______________________*/
    p {
        font-size: 15px;
        margin-bottom: 0;
    }

    ul,
    li {
        list-style: none;
    }

    .icargo_box ul {
        padding: 0;

        margin: 0;
        background: #fff;

    }

    .icargo_box ul li h1,
    .icargo_box ul li h3 {
        margin: 0;
    }

    .icargo_box ul li:nth-child(2) h4 {
        margin: 4px;
        color: #9c9c9c;
        font-weight: 500;
        letter-spacing: 2px;
    }

    .icargo_box ul li:nth-child(3) h3 {
        background: lightgrey;
        display: inline-block;
        padding: 0 15px;
    }

    .icargo_box ul li:nth-child(3) span {
        border: 1px solid #3333;
        display: block;
        padding: 0px 10px;
        border-radius: 0;
    }

    .icargo_box ul li:nth-child(3) span p:first-child {
        border-bottom: 1px solid #3333;
        padding-bottom: 1px;
    }

    .icargo_box ul li:nth-child(3) span p {
        margin: 1px 0;
        font-size: 14px;
    }

    .icargo_box ul li h1 {
        font-size: 25px;
        color: #565555;
        padding-top: 0px;
    }

    .icargo_box ul li:nth-child(2) {
        text-align: center;
    }

    .icargo_box ul li:nth-child(3) h3 {}

    .main_address_box {
        border: 1px solid #3333;
        margin: 1%;
        background: #fff;
    }

    .icargo_box ul li img {
        width: 80%;
        padding: 15px 0;
    }

    .icargo_box ul li {
        display: inline-block;
        width: 31%;
        vertical-align: top;
        padding: 1%;
    }

    .main_div_full .full_row {
        display: flex;
    }

    .col_2 {
        text-align: center;
        width: 15%;
    }

    .main_div_full .input_fix {
        padding: 1%;
    }

    #main_page {
        max-width: 800px;
        margin: 0 auto;
    }

    .main_div_full .input_fix input {
        height: 16px;
        font-size: 11px;
        width: 90%;
        border-radius: 0;
        padding: 4px 9px 3px;
        border: 1px solid #3333;
    }

    .main_div_full .input_fix input:focus {
        outline: none;
    }

    .main_div_full .input_fix label {
        font-size: 12px;
        padding-bottom: 3px;
        text-align: left;
        display: block;
        font-weight: 500;
    }

    .main_address {
        padding: 1%;
        display: flow-root;
    }

    .main_address4 .dot_line p {
        float: right;
        padding: 0;
        width: 100%;
        margin: 0;
    }

    .main_address4 .dot_line p span {
        border-bottom: 1px solid #3333;
        display: block;
        height: 30px;
        font-size: 14px;
        padding-top: 10px;
    }

    .main_address4 .dot_line b {
        float: left;
        width: 30%;
        font-size: 14px;
        padding-top: 12px;
        font-weight: 500;
        height: 30px;
    }

    .main_address4 {
        padding: 1%;
    }

    .align_content .dot_line {
        padding-left: 0 !important;
    }

    .align_content_right .dot_line {
        padding-right: 0 !important;
    }

    .height_fix {
        height: auto;
    }

    .d_flex_cus {
        display: inherit;
    }

    .list_fix:first-child,
    .table_gap:first-child {
        margin-right: 3px;
    }

    .d_flex_cus .col_cus {
        width: 32%;
        margin: 0 3px 8px 0px;
        display: inline-block;
    }

    .d_flex_cus .col_cus h4 {
        margin: 0 0 3px;
        font-size: 13px;
    }

    .height_fix {
        padding: 2px 8px;
    }

    table th,
    table td {
        border: 1px solid #2222;
        padding: 2px;
        font-size: 10px;
        vertical-align: top;
        text-align: left;
    }

    .table_gap table {
        width: 100%;
        border-spacing: 0px;
        border-collapse: collapse;
    }

    .last_bxo {
        padding: 3% 1%;
    }

    .main_address_2 .dot_line,
    .main_address_3 .dot_line,
    .main_address_44 .dot_line {

        padding: 0 8px;
    }

    .main_address_2 .dot_line b {
        width: 50% !important;
    }

    .main_address_3 .dot_line b {
        width: 50% !important;
    }

    .main_address_44 .dot_line b {
        width: 40% !important;
    }

    .single_page {
        margin: 100px 0;
    }

    .main_address .dot_line {
        display: flex;
    }

    .main_address .dot_line b {
        float: left;
        width: 20%;
        font-size: 12px;
        padding-top: 1px;
        font-weight: 500;
        height: 20px;
    }

    .main_address .dot_line p {
        float: right;
        padding: 0;
        width: 100%;
        margin: 0;
    }

    .main_address .dot_line p span {
        border-bottom: 1px solid #3333;
        display: block;
        height: 14px;
        font-size: 11px;
        padding-top: 0px;
    }

    .col_6 {
        width: 50%;
        float: left;
    }

    .full_row {}

    .h2_fix_h2 h2 {
        text-align: left;
        font-size: 14px;
        margin: 0 0 5px 7px;
        color: #000000;
    }

    .table_gap tfoot,
    .table_gap thead {
        background: #f5f5f5;
    }

    @media print {
        @page{margin: 0;}
        .icargo_box ul li:nth-child(3) h3 {
            background: lightgrey !important;
            -webkit-print-color-adjust: exact;

        }

        .tariff_name_row {
            font-size: 8px !important;
        }

        .main_address {
            padding: 5px 10px;
        }

        .main_address_box {
            margin: 5px 0 0;
        }

        .main_address .dot_line p span {
            height: 14px;
            font-size: 10px;
            padding-top: 0px;
        }

        .main_address .dot_line b {
            font-size: 11px;
            height: 20px;
        }

        .h2_fix_h2 h2 {
            font-size: 12px;
        }

        .main_div_full .input_fix input {
            height: 10px;
            font-size: 9px;
        }

        .icargo_box ul {
            -webkit-print-color-adjust: exact;
            background: #fff;
        }

        .main_address_box {
            -webkit-print-color-adjust: exact;
            background: #fff;

        }

        #main_page {
            margin: 0;
        }

        .icargo_box ul {
            text-align: center;
        }

        .icargo_box ul li {
            width: 30% !important;
        }

    }
    </style>

</head>

<body id="page-name">


    <div id="main_page">
        <?php
        if (!function_exists('checkDivisionFactors')) {
            function checkDivisionFactors($start_range, $end_range)
            {
                global $con;
                $types_query = "SELECT * FROM product_type_prices WHERE  start_range = " . $start_range . " AND end_range = " . $end_range . "";
                $product_price_query = mysqli_query($con, $types_query);
                $returnArray = array();
                //  echo "SELECT * FROM tariff_detail WHERE tariff_id = $tariff_id";die();
                $row = mysqli_fetch_array($product_price_query);
                $division_factor_exist = isset($row['division_factor']) ? $row['division_factor'] : 0;
                $type_id = isset($row['id']) ? $row['id'] : 0;
                $returnArray['division_factor'] = $division_factor_exist;
                $returnArray['type_id'] = $type_id;
                return $returnArray;
            }
        }
        if (isset($_GET['account_no']) && !empty($_GET['account_no'])) {
            $id = $_GET['account_no'];
            $result = mysqli_query($con, "SELECT * FROM customers where id='" . $id . "' ");
            $fetch = mysqli_fetch_array($result);
        }

        if (isset($fetch) && !empty($fetch)) {
            $city = '';
            $stn_code = '';
            if (isset($fetch['city']) && $fetch['city'] != '') {
                $city_qu = mysqli_query($con, "SELECT * FROM cities WHERE id=" . $fetch['city']);
                $city_q = mysqli_fetch_assoc($city_qu);
                $city = $fetch['city'];
                if (isset($city_q) && !empty($city_q)) {
                    $city = $city_q['city_name'];
                    $stn_code = $city_q['stn_code'];
                } else {
                    $city_qu = mysqli_query($con, "SELECT * FROM cities WHERE city_name='" . $fetch['city'] . "'");
                    $city_q = mysqli_fetch_assoc($city_qu);
                    $city = $fetch['city'];
                    if (isset($city_q) && !empty($city_q)) {
                        $city = $city_q['city_name'];
                        $stn_code = $city_q['stn_code'];
                    }
                }
            }
            $area = '';
            if (isset($fetch['area_id']) && $fetch['area_id'] != '') {
                $area_q = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM areas WHERE id=" . $fetch['area_id']));
                $area = $fetch['area_id'];
                if (isset($area_q) && !empty($area_q)) {
                    $area = $area_q['area_name'];
                }
            }
            $state = '';
            if (isset($fetch['state_id']) && $fetch['state_id'] != '') {
                $state_q = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM state WHERE id=" . $fetch['state_id']));
                $state = $fetch['state_id'];
                if (isset($state_q) && !empty($state_q)) {
                    $state = $state_q['state_name'];
                }
            }

            $customer_type = '';
            if (isset($fetch['customer_type']) && $fetch['customer_type'] != '') {
                $customer_type_q = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM account_types WHERE id=" . $fetch['customer_type']));
                if (isset($customer_type_q) && !empty($customer_type_q)) {
                    $customer_type = $customer_type_q['account_type'];
                }
            }
        ?>
        <div class="single_page">
            <div class="icargo_box">
                <ul>
                    <!-- <li>
           <img src="<?php echo BASE_URL ?>admin/<?php echo $logo_img['value'] ?>" alt="" style="width: 124px;">
              <img src="https://www.icargos.com/wp-content/uploads/2019/07/iCargo-Logo.png" alt="">  
         </li>
         <li>
           <h1 ><?php print_r($companyname['value']); ?></h1>
            <h4 >Service Aggrement</h4>
         </li>
         <li>
            <h3>NTN</h3>
            <span>
                <p>Express:  <?php echo $fetch['express']; ?></p>
                <p>Logistics: <?php echo $fetch['logistics']; ?></p>
            </span>
         </li> -->

                </ul>
            </div>


            <div class="main_div_full" style="padding: 54px 0 0;">
                <div class="full_row">
                    <div class="col_2 input_fix">
                        <label>Account#</label>
                        <input type="text" value="<?php echo $fetch['client_code'] ?>" readonly>
                    </div>
                    <div class="col_2 input_fix">
                        <label>Date#</label>
                        <input type="text" value="<?php echo date('d-m-Y', strtotime($fetch['dates'])) ?>" readonly>
                    </div>
                    <div class="col_2 input_fix">
                        <label>Region#</label>
                        <?php
                        if (isset($fetch['zone_type']) && $fetch['zone_type']!=='') {
                           $zone=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM zone_type WHERE id=".$fetch['zone_type']));
                         ?>
                         <input type="text" value="<?php echo $zone['description']; ?>" readonly>
                        <?php }
                          ?>

                        
                    </div>
                    <div class="col_2 input_fix">
                        <label>Area</label>
                        <input type="text" value="<?php echo $stn_code; ?>" readonly>
                    </div>
                    <div class="col_2 input_fix">
                        <label>Account Type#</label>
                        <input type="text" value="<?php echo $customer_type; ?>" readonly>
                    </div>
                    <div class="col_2 input_fix">
                        <label>Division#</label>
                        <input type="text" value="<?php echo $state; ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="main_address">
                <div class="full_row">
                    <div class="col_12 dot_line">
                        <b>Account Title :</b>
                        <p><span><?php echo $fetch['bname']; ?></span></p>
                    </div>
                    <div class="col_12 dot_line">
                        <b>Office Address :</b>
                        <p><span><?php echo $fetch['address']; ?></span> </p>

                    </div>
                    <div class="col_12 dot_line">
                        <b>Pickup Address :</b>
                        <p><span><?php echo isset($fetch['billing_address']) ? $fetch['billing_address'] : ''; ?></span>
                        </p>
                    </div>
                </div>

            </div>
            <div class="main_address main_address_2">
                <div class="full_row">
                    <div class="col_6 left_box align_content">
                        <div class="dot_line">
                            <b>Parent Code :</b>
                            <p><span><?php echo isset($fetch['parent_code']) ? $fetch['parent_code'] : ''; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <b>NTN :</b>
                            <p><span><?php echo isset($fetch['ntn_no']) ? $fetch['ntn_no'] : ''; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <b>Contact Person :</b>
                            <p><span><?php echo isset($fetch['contact_person']) ? $fetch['contact_person'] : ''; ?></span>
                            </p>
                        </div>
                        <div class="dot_line">
                            <b>Designation :</b>
                            <p><span><?php echo isset($fetch['designation']) ? $fetch['designation'] : ''; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <b>Tel# :</b>
                            <p><span><?php echo isset($fetch['mobile_no']) ? $fetch['mobile_no'] : ''; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <b>Email :</b>
                            <p><span><?php echo isset($fetch['email']) ? $fetch['email'] : ''; ?></span></p>
                        </div>
                    </div>
                    <div class="col_6 right_box align_content_right">
                        <div class="dot_line">
                            <b>Inductry Code :</b>
                            <p><span><?php echo isset($fetch['industry_code']) ? $fetch['industry_code'] : ''; ?></span>
                            </p>
                        </div>
                        <div class="dot_line">
                            <b>GST :</b>
                            <p><span><?php echo isset($fetch['gst']) ? $fetch['gst'] : ''; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <b>CNIC :</b>
                            <p><span><?php echo isset($fetch['cnic']) ? $fetch['cnic'] : ''; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <b>Mobile :</b>
                            <p><span><?php echo isset($fetch['mobile_no']) ? $fetch['mobile_no'] : ''; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <b>Fax :</b>
                            <p><span><?php echo isset($fetch['fax']) ? $fetch['fax'] : ''; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <b>Web :</b>
                            <p><span><?php echo isset($fetch['website']) ? $fetch['website'] : ''; ?></span></p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="main_address main_address_3">
                <div class="full_row">
                    <div class="col_6 left_box align_content">
                        <div class="dot_line">
                            <b>Nature Of Account :</b>
                            <p><span><?php echo $customer_type; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <b>Validity :</b>
                            <p><span><?php echo isset($fetch['validity']) ? $fetch['validity'] : ''; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <b>Billing Instruction :</b>
                            <p><span><?php echo isset($fetch['billing_instruction']) ? $fetch['billing_instruction'] : ''; ?></span>
                            </p>
                        </div>
                        <div class="dot_line">
                            <b>Monthly Shipment :</b>
                            <p><span><?php echo isset($fetch['expected_shipment']) ? $fetch['expected_shipment'] : ''; ?></span>
                            </p>
                        </div>
                        <div class="dot_line">
                            <b>Fuel Adj Charges :</b>
                            <p><span><?php echo isset($fetch['fuel_formula']) ? $fetch['fuel_formula'] . '%' : ''; ?></span>
                            </p>
                        </div>
                        <div class="dot_line">
                            <b>Handling Charges :</b>
                            <p><span><?php echo isset($fetch['handling_charges']) ? $fetch['handling_charges'] : ''; ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="col_6 right_box align_content_right">
                        <div class="dot_line">
                            <b>Associated Companies :</b>
                            <p><span><?php echo isset($fetch['assosciated_company']) ? $fetch['assosciated_company'] : ''; ?></span>
                            </p>
                        </div>
                        <div class="dot_line">
                            <b>Yearly Tariff Increase:</b>
                            <p><span><?php echo isset($fetch['tariff_increase']) ? $fetch['tariff_increase'] : ''; ?></span>
                            </p>
                        </div>
                        <div class="dot_line">
                            <b> Payment Terms :</b>
                           <?php
                            if (isset($fetch['payment_within'])) {
                            $settle_period=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM settlement_period WHERE no_of_day='".$fetch['payment_within']."'"));
                            ?>
                            <p><span><?php echo isset($settle_period['period_name']) ? $settle_period['period_name'] : ''; ?></span>
                            </p>
                             <?php } ?>
                        </div>
                        <div class="dot_line">
                            <b>Monthly Revenue :</b>
                            <p><span><?php echo isset($fetch['monthly_revenue']) ? $fetch['monthly_revenue'] : ''; ?></span>
                            </p>
                        </div>
                        <div class="dot_line">
                            <b>Flexible Fule Formula :</b>
                            <p><span><?php echo isset($fetch['fuel_formula']) && $fetch['fuel_formula'] == 1 ? 'Yes' : 'No'; ?></span>
                            </p>
                        </div>
                        <div class="dot_line">
                            <b>Other Charges :</b>
                            <p><span><?php echo isset($fetch['other_charges']) ? $fetch['other_charges'] : ''; ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main_address_box height_fix d_flex_cus">
                <?php
                    $customerViseQ = "SELECT * From customer_tariff_detail WHERE customer_id=" . $_GET['account_no'];
                    $tarif_q = mysqli_query($con, $customerViseQ);
                    $customerViseRowCount = mysqli_num_rows($tarif_q);
                    if ($customerViseRowCount > 0) {
                        $tariffDetailQuery = "SELECT * From customer_tariff_detail WHERE customer_id=" . $_GET['account_no'] . " GROUP BY tariff_id";
                    } else {
                        $tariffDetailQuery = "SELECT * From tariff_detail GROUP BY tariff_id";
                    }
                    $allTariffQ = mysqli_query($con, $tariffDetailQuery);

                    $allTariffIds = '';
                    while ($mainTariff = mysqli_fetch_assoc($allTariffQ)) {
                        $allTariffIds .= $mainTariff['tariff_id'] . ',';
                    }
                    $allTariffIds = rtrim($allTariffIds, ',');
                    // echo $allTariffIds;
                    // die;
                    $proMainSql = "SELECT * from tariff_detail Where tariff_id IN($allTariffIds) GROUP BY tariff_id";
                    // echo $proMainSql;
                    // die;
                    $productMainQ = mysqli_query($con, $proMainSql);
                    while ($mainTariff = mysqli_fetch_assoc($productMainQ)) {
                        $getProQ = "SELECT * FROM tariff WHERE id =" . $mainTariff['tariff_id'];
                        // echo $getProQ;
                        // die;
                        $productQuery = mysqli_fetch_assoc(mysqli_query($con, $getProQ));

                    ?>
                <div class="col_cus table_gap">
                    <h4>Product: <?php echo getProduct($productQuery['product_id'])['name']; ?></h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Tarrif Type</th>
                                <!-- <th>Start Range</th> -->
                                <th>End Range</th>
                                <th>Rate</th>
                                <th>Additional Wt.</th>
                                <!-- <th>Service</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                    // Sarting Inner
                                    if ($customerViseRowCount > 0) {
                                        $innerTarifQ = "SELECT * From customer_tariff_detail WHERE tariff_id =" . $productQuery['id'] . " AND  customer_id=" . $_GET['account_no'] . " ORDER BY id asc";
                                    } else {
                                        $innerTarifQ = "SELECT * From tariff_detail WHERE tariff_id =" . $productQuery['id'] . " ORDER BY id asc";
                                    }
                                    // echo $innerTarifQ;
                                    //die;
                                    $innerTarifQuery = mysqli_query($con, $innerTarifQ);
                                    while ($product = mysqli_fetch_assoc($innerTarifQuery)) {
                                        $divisionFacctor = checkDivisionFactors($product['start_range'], $product['end_range']);
                                     $end_range=$product['end_range'];
                                    if($end_range < 1 && $end_range > 0){
                                      $end_range='0'.$end_range;
                                    }
                                    $division_factor=$divisionFacctor['division_factor'];
                                    if($division_factor < 1 && $division_factor > 0){
                                      $division_factor='0'.$division_factor;
                                    }
                                    ?>
                            <tr>
                                <th class="tariff_name_row">
                                    <?php echo str_replace("COD", "", $productQuery['tariff_name']); ?></th>
                                <!-- <td style="text-align: center;"><?php echo $product['start_range']; ?></td> -->
                                <td style="text-align: center;"><?php echo $end_range; ?></td>
                                <td style="text-align: center;"><?php echo $product['rate']; ?></td>
                                <td style="text-align: center;"><?php echo $division_factor; ?></td>
                                <!-- <td><?php echo getServiceTypeName($productQuery['service_type'])['service_type']; ?></td> -->
                            </tr>
                            <?php }



                                    ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
            <div class="main_address main_address_box">
                <div class="full_row ">
                    <div class="col_12 dot_line">
                        <b>Special Instructios:</b>
                        <p><span><?php echo isset($fetch['special_instruction']) ? $fetch['special_instruction'] : ''; ?></span>
                        </p>
                    </div>
                    <div class="col_12 dot_line">
                        <b> Frequent Destinations :</b>
                        <p><span><?php echo isset($fetch['frequent_destination']) ? $fetch['frequent_destination'] : ''; ?></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="main_address main_address_44 main_address_box">
                <div class="full_row h2_fix_h2">
                    <h2>Contact Persons at <?php echo getConfig('companyname'); ?></h2>
                    <div class="col_6 left_box">

                        <div class="dot_line">
                            <!-- <b>BDM/KAM :</b> -->
                            <b>BDM :</b>
                            <p><span><?php echo $fetch['bdm_kam']; ?> </span></p>
                        </div>
                        <div class="dot_line">
                            <b>KAM :</b>
                            <p><span> <?php echo $fetch['collector']; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <!-- <b>CHS/ABH :</b> -->
                            <b>ASM/Sales Head :</b>
                            <p><span> <?php echo $fetch['chs_abh']; ?></span></p>
                        </div>
                        <!-- <div class="dot_line">
                            <b>ASM :</b>
                            <p><span><?php echo $fetch['mr_amr']; ?></span></p>
                        </div> -->

                    </div>
                    <div class="col_6 right_box">
                        <div class="dot_line">
                            <b>Territory Code :</b>
                            <p><span> <?php echo $fetch['territory_code']; ?></span></p>
                        </div>
                        <div class="dot_line">
                            <b>Codection ID :</b>
                            <p><span><?php echo $fetch['collection_id']; ?> </span></p>
                        </div>

                        <div class="dot_line">
                            <!-- <b>MR/AMR :</b> -->
                            <b>MC :</b>
                            <p><span> <?php echo $fetch['mr_amr']; ?></span></p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- <div class="main_address last_bxo">
    <div class="dot_line">
  <div class="col_6 left_signature">
    <p><?php echo getLange('signature'); ?> : _________________</p>
  </div>
  <div class="col_6 left_signature right_signature "style="text-align: right;">
    <p><?php echo getLange('signature'); ?> : _________________</p>
  </div>
</div>
</div> -->


        </div>
        <?php } ?>
    </div>

    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        window.print();
    }, false);
    </script>
</body>

</html>