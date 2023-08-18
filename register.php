<?php

session_start();

if (isset($_SESSION['customers'])) {
    header('Location: profile.php');
    exit();
}

$banks_list = array();
include_once "includes/conn.php";
$cities = mysqli_query($con, "SELECT * FROM cities");

$page_title = 'Please Register Here';
include "includes/header.php";
function addQuote($value)
{
    return (!is_string($value) == true) ? $value : "'" . $value . "'";
}
$companyname = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='companyname' "));

// $logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
// $logo_img = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='logo' "));
?>


<div>
    <div class="register_title">

        <?php
        if (isset($_POST['fname'])) {
            // $client_code = $_POST['client_code'];
            // $check_client_q = mysqli_query($con,"SELECT * FROM customers WHERE client_code ='".$client_code."' ");

            $target_file = "";
            //  if(isset($_FILES["cnic_copy"]) && $_FILES["cnic_copy"]["name"]!=''){
            // $target="cnic_copy/";
            //     $target_file = $target .uniqid(). basename($_FILES["cnic_copy"]["name"]);
            //     $extension = pathinfo($target_file,PATHINFO_EXTENSION);
            //     if($extension=='jpg'||$extension=='png'||$extension=='jpeg' ||$extension=='pdf' ||$extension=='doc') {
            //         move_uploaded_file($_FILES["cnic_copy"]["tmp_name"],$target_file);
            //     }
            //     // $query2=mysqli_query($con,"UPDATE `customers` SET emirates_id='$target_file' WHERE id='$id'");
            // }
            if (trim($_POST['password']) == trim($_POST['repassword'])) {
                $send = true;
            } else {
                $send = false;
            }

            if ($send) {
                // $_POST['emirates_id']==$target_file;
                $password = md5($_POST['password']);
                // $_POST['address']=implode(',,',$_POST['address']);
                $data = $_POST;
                $data['pass'] = $_POST['password'];
                $data['password'] = md5($_POST['password']);
                $data['cnic_copy'] = $target_file;
                if (isset($data['submit']))
                    unset($data['submit']);
                unset($data['repassword']);
                unset($data['merchant_key']);
                unset($data['c_payable']);
                unset($data['p_payable']);
                unset($data['p_acc_id']);
                unset($data['c_recievable']);
                unset($data['p_recievable']);
                unset($data['r_acc_id']);
                $email = $data['email'];
                if (isset($_FILES["image"]["name"]) and !empty($_FILES["image"]["name"])) {
                    $target_dir = "users/";
                    $target_file = $target_dir . uniqid() . basename($_FILES["image"]["name"]);

                    // $db_dir = "users/";
                    // $db_file = $db_dir .uniqid(). basename($_FILES["image"]["name"]);

                    $extension = pathinfo($target_file, PATHINFO_EXTENSION);
                    if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'JPEG ' || $extension == 'jpeg ') {
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

                            $data['image'] = $target_file;
                        }
                    } else {
                        $_SESSION['fail_add'] = 'Your Logo Image Type in Wrong<br>';
                        header("Location:" . $_SERVER['HTTP_REFERER']);
                        exit();
                    }
                }
                if (isset($_FILES["cnic_copy"]["name"]) and !empty($_FILES["cnic_copy"]["name"])) {
                    $target_dir = "cnic_copy/";
                    $target_file = $target_dir . uniqid() . basename($_FILES["cnic_copy"]["name"]);


                    $extension = pathinfo($target_file, PATHINFO_EXTENSION);
                    if ($extension == 'jpg' || $extension == 'png' || $extension == 'JPG' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF' || $extension == 'JPEG ' || $extension == 'jpeg ') {
                        if (move_uploaded_file($_FILES["cnic_copy"]["tmp_name"], $target_file)) {

                            //$target_file=trim($target_file , '');
                            $data['cnic_copy'] = $target_file;
                        }
                    } else {
                        $_SESSION['fail_add'] = 'Your CNIC Copy Type in Wrong<br>';
                        header("Location:" . $_SERVER['HTTP_REFERER']);
                        exit();
                    }
                }
                $index = 0;
                foreach ($data as $key => &$value) {
                    if (trim($value) == '') {
                        array_splice($data, $index, 1);
                        $index--;
                    }
                    $index++;
                }
                foreach ($data as $k => &$value) {
                    $value = addQuote($value);
                }

                $keys = implode(", ", array_keys($data));
                $values = implode(",", $data);
                $sql = "INSERT INTO customers ($keys) VALUES($values)";

                $query = mysqli_query($con, $sql) or die(mysqli_error($con));
                $customer_id = mysqli_insert_id($con);
                $code = 1000 + $customer_id;
                $query5 = mysqli_query($con, "UPDATE customers SET client_code = '" . $code . "'  WHERE id = " . $customer_id);
                $rowscount = mysqli_affected_rows($con);
                // Reference key code
                if (isset($_POST['merchant_key']) && !empty($_POST['merchant_key'])) {
                    $check_query = mysqli_query($con, "SELECT merchant_key, is_merchant, id from customers where merchant_key='" . $_POST['merchant_key'] . "'");
                    $merKeyRes = mysqli_fetch_assoc($check_query);
                    $merchantKey = isset($merKeyRes['merchant_key']) ? $merKeyRes['merchant_key'] : '';
                    $is_merchant = isset($merKeyRes['is_merchant']) ? $merKeyRes['is_merchant'] : '';
                    $mer_id = isset($merKeyRes['id']) ? $merKeyRes['id'] : '';
                    if (isset($is_merchant) && $is_merchant == 1) {
                        mysqli_query($con, "UPDATE customers set reference_with=" . $mer_id . " where id=" . $customer_id);
                    }
                }
            }
            if ($send == true && $rowscount > 0) {
                $code = 1000 + $customer_id;
                $cust = mysqli_fetch_array(mysqli_query($con , "SELECT * FROM `customers` where id=" . $customer_id." "));
                $customerName = $cust['bname'];
                $ledgercode = mysqli_query($con , "SELECT MAX(`ledgerCode`) AS `ledgerCode` FROM `tbl_accountledger`");
                $getLedgerCode = mysqli_fetch_assoc($ledgercode);

                $ledgerCode = $getLedgerCode['ledgerCode'];
                if(!empty($ledgerCode)){
                        $ledgerCode = $ledgerCode + 1 ;
                }else{
                        $ledgerCode=13; 
                }
                $dateToday = date("Y-m-d H:i:s");
                if(!empty($_POST['c_payable']) && !empty($_POST['p_payable']) && !empty($_POST['p_acc_id'])){
                    $sql =  "INSERT into tbl_accountledger (`ledgerCode`,`ledgerName`,`customer_id`,`created_on`,`accountGroupId`,`chart_account_id`, `chart_account_id_child`,`status`) VALUES ('".$ledgerCode."', '".$customerName."', '".$customer_id."','".$dateToday."','".$_POST['p_acc_id']."', '".$_POST['p_payable']."', '".$_POST['c_payable']."','0' )";
                    $query1 =mysqli_query($con ,$sql);

                }
                if($query1 == TRUE){
                        $ledgerCode = $ledgerCode+1;
                    }else{
                        $ledgerCode = $ledgerCode;
                    }

                if(!empty($_POST['c_recievable']) && !empty($_POST['p_recievable']) && !empty($_POST['r_acc_id'])){
                        $sql =  "INSERT into tbl_accountledger (`ledgerCode`,`ledgerName`,`customer_id`,`created_on`,`accountGroupId`,`chart_account_id`, `chart_account_id_child`,`status`) VALUES ('".$ledgerCode."', '".$customerName."', '".$customer_id."','".$dateToday."','".$_POST['r_acc_id']."', '".$_POST['p_recievable']."', '".$_POST['c_recievable']."','0' )";
                    $query1 = mysqli_fetch_array(mysqli_query($con ,$sql));
                }
                
                if (isset($data['email'])) {
                    $data['email'] = $email;
                    $customer_name = $_POST['fname'];
                    $message['subject'] = 'Account Registration';
                    $message['body'] = "<b>Hello " . $customer_name . " </b>";
                    $message['body'] .= '<p>Thank you for registering with ' . $companyname['value'] . '</p>';
                    $message['body'] .= '<p>Your account has been created but must be activated before you can start booking your shipments. Our admin will review your information and approve within 24 hours.</p>';
                    require_once 'admin/includes/functions.php';
                    sendEmail($data, $message);
                    // Admin
                    $path = BASE_URL . 'admin/customer_detail.php?customer_id=' . $customer_id;
                    $message['body'] = '<p>New User Account has been created</p>';
                    $message['body'] .= '<p>Click below link to view customer.</p>';
                    $message['body'] .= "<a href='$path'>$path</a>";
                    sendEmailToAdmin($data, $message);
                }
                $id = mysqli_insert_id($con);
                $query = mysqli_query($con, "Select * from customers where id=$id") or die(mysqli_error($con));
                $fetch = mysqli_fetch_array($query);



                echo '<div style="width: 663px; margin: 0px auto;" class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> Your registration is successful. Please wait for account approval email by ' . $companyname['value'] . '</div>';
            } else {
                echo '<div style="width: 663px; margin: 0px auto;" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> your registration is unsuccessful, please try again.</div>';
            }
        }

        $query = mysqli_query($con, "SELECT COUNT(*) as total FROM customers");
        $code = 1;
        $row = mysqli_fetch_object($query);
        if (isset($row->total)) {
            $code = (int)$row->total;
            $code++;
        }
        $code = $code + 1000;
        ?>
    </div>


</div>
</div>
<style>
.form-control,
.input-group-addon,
.bootstrap-select .btn {
    background-color: #ffffff;
    border-color: #ccc;
    border-radius: 3px;
    box-shadow: none;
    color: #000;
    font-size: 14px;
    height: 34px;
    padding: 0 20px;
    font-weight: 300;
}

label {
    font-weight: normal;
    margin: 0;
    color: #000;
    margin-bottom: 7px;
    font-weight: bold;
}

.modal-header {
    padding: 6px 11px;
    border-bottom: 1px solid #e5e5e5;
    margin-top: 0;
}

.profile-page-title,
.col-lg-4 {
    padding: 0 15px;
}

.modal-title {
    text-align: center;
}

.register_page {
    max-width: 660px;
}

.form-group input,
input.emaill {
    background-color: #f8fbff7d !important;
}

label {
    margin: 6px 0;
    font-weight: 500;
    font-size: 14px;
}

.term_label {
    color: #0a68bb;
}


@media (max-width: 1250px) {
    .container {
        width: 100%;
    }


}

@media (max-width: 1024px) {
    .container {
        width: 100%;
    }


}

@media (max-width: 767px) {
    .container {
        width: auto;
    }

    .register_title {
        margin-top: 0;
    }
}
</style>
<div class="modal-body">

    <!-- steps -->

    <section>
        <div class="wizard">
            <h3 class="modal-title modal-title-center hide-register-title"><?php echo getConfig('companyname'); ?>
            </h3>
            <h3 class="modal-title modal-title-center hide-register-title"><?php echo getLange('pleaseregisterhere'); ?>
            </h3>
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation" class="active">
                        <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                            <span class="round-tab">1</span>
                        </a>
                        <b><?php echo getLange('personalinformation'); ?> </b>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                            <span class="round-tab">2</span>
                        </a>
                        <b><?php echo getLange('bankinformation'); ?> </b>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
                            <span class="round-tab">3</span>
                        </a>
                        <b> <?php echo getLange('shipperinformation'); ?></b>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Step 4">
                            <span class="round-tab">4</span>
                        </a>
                        <b><?php echo getLange('password'); ?> </b>
                    </li>

                    <!-- <li role="presentation" class="disabled">
                        <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                            <span class="round-tab"> 5 </span>
                        </a>
                            <b>view all data</b>
                    </li> -->
                </ul>
            </div>

            <form autocomplete="off" class="validateform" id="contactForm" action="" method="post" class="City:"
                role="form" enctype="multipart/form-data">
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" id="step1">

                        <div class="row" style="margin-left: 0px; margin-right: 0px;">

                            <div class="form-group col-lg-6" id="bname">
                                <label for="usr"><span style="color: red;">*</span>
                                    <?php echo getLange('companynamebrandname'); ?></label>
                                <input type="text" class="form-control bname_check bname"
                                    placeholder="<?php echo getLange('companynamebrandname'); ?>" name="bname" required>
                            </div>
                            <div class="form-group col-lg-6" id="bname">
                                <label for="usr"><span style="color: red;">*</span>
                                    <?php echo getLange('personofconatct'); ?></label>
                                <input type="text" class="form-control bname_check fname"
                                    placeholder="<?php echo getLange('personofconatct'); ?>" name="fname" required>
                            </div>


                            <div class="form-group col-lg-6">
                                <label for="usr"><span style="color: red;">*</span>
                                    <?php echo getLange('phoneno'); ?></label>
                                <input type="text" class="form-control bname_check mobile_no"
                                    placeholder="<?php echo getLange('phoneno'); ?>" name="mobile_no" required>
                            </div>




                            <div class="form-group col-lg-6">
                                <label for="usr"><span style="color: red;">*</span>
                                    <?php echo getLange('email'); ?>:</label>
                                <input type="email" class="form-control bname_check emailleee email" name="email"
                                    required>
                                <input type="hidden" value="" class="msg_email">
                                <div class="help-block with-errors email_errorr"></div>
                            </div>

                            <!-- <div class="form-group col-lg-12" id="bname">
                                    <label for="usr"><span style="color: red;">*</span>
                                        <?php echo getLange('companypickupaddress'); ?></label>
                                     <textarea name="address"
                                        placeholder=" <?php echo getLange('companypickupaddress'); ?>"
                                        style="height: 52px;" type="text" class="form-control address bname_check"
                                        required></textarea>
                            </div> -->
                            <div class="row">
                                <div class="col-sm-12 padd_left" style="padding-right:0;">
                                    <div class="form-group">
                                        <label class="control-label"><span style="color: red;">*</span>
                                            <?php echo getLange('companypickupaddress'); ?></label>
                                        <!-- <textarea class="form-control" name="receiver_address"  placeholder="Consignee Address" required="true"></textarea> -->
                                        <input autocomplete="false" required="true" name="address"
                                            class="address form-control" type="text" value="" id="property_add"
                                            placeholder="<?php echo getLange('companypickupaddress'); ?>">
                                        <input type="hidden" name="google_address" id="google_address">
                                        <input type="hidden" class="form-control" id="latitude"
                                            name="customer_latitude">
                                        <input type="hidden" class="form-control" id="longitude"
                                            name="customer_longitude">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="mapping" id="mapping"
                                                    style="width: 100%; height: 173px;margin-bottom: 10px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="form-group col-lg-6">
                                <label for="usr"><span style="color: red;">*</span>
                                    <?php echo getLange('cnic'); ?></label>
                                <input type="text" class="form-control bname_check cnic"
                                    placeholder="<?php echo getLange('cnic'); ?>" name="cnic" style="padding-top: 5px;"
                                    required>

                            </div>


                            <div class="form-group col-lg-6">
                                <label for="usr">Reference Key</label>
                                <input type="text" class="form-control" placeholder="Enter Reference key"
                                    name="merchant_key" style="padding-top: 5px;">

                            </div>

                            <div class="form-group col-lg-4">
                                <label for="usr"><?php echo getLange('cnic') . ' ' . getLange('copy'); ?></label>
                                <input type="file" class="form-control " id="image" name="cnic_copy"
                                    style="padding-top: 5px;">
                                <div class="msg"></div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="usr"><?php echo getLange('logoimage'); ?></label>
                                <input type="file" class="form-control " id="logo" name="image"
                                    style="padding-top: 5px;">
                                <div id="msg"></div>
                            </div>
                        </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-primary next-step"
                                    id="submit_step_data1"><?php echo getLange('next'); ?></button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="step2">
                        <div class="row" style="margin-left: 0px; margin-right: 0px;">
                            <div class="row">
                                <div class="form-group col-lg-6" id="bname">
                                    <label for="usr"> <?php echo getLange('bankname'); ?>:</label>
                                    <select type="text" class="form-control js-example-basic-single" name="bank_name">
                                        <option value="" selected disabled>Select Bank Name</option>
                                        <?php $bank_query = mysqli_query($con, "SELECT * FROM bank_detail ORDER By id Desc");
                                        while ($row = mysqli_fetch_array($bank_query)) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['bank_name']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6" id="acc_title">
                                    <label for="usr"> <?php echo getLange('accountitle'); ?>:</label>
                                    <input type="text" class="form-control" name="acc_title">
                                </div>
                            </div>
                            <div class="form-group col-lg-6" id="bank_ac_no">
                                <label for="usr"> <?php echo getLange('accountno'); ?>:</label>
                                <input type="text" class="form-control" name="bank_ac_no">
                            </div>
                            <div class="form-group col-lg-6" id="branch_name">
                                <label for="usr"> <?php echo getLange('branchname'); ?>:</label>
                                <input type="text" class="form-control" name="branch_name">
                            </div>
                            <div class="form-group col-lg-6" id="branch_code">
                                <label for="usr"> <?php echo getLange('branchcode'); ?>:</label>
                                <input type="text" class="form-control" name="branch_code">
                            </div>
                            <div class="form-group col-lg-6" id="swift_code">
                                <label for="usr"> <?php echo getLange('swiftcode'); ?>:</label>
                                <input type="text" class="form-control" name="swift_code">
                            </div>
                            <div class="form-group col-lg-12" id="iban">
                                <label for="usr"> <?php echo getLange('iban'); ?>:</label>
                                <input type="text" class="form-control" name="iban_no">
                            </div>
                        </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button"
                                    class="btn btn-default prev-step"><?php echo getLange('previous'); ?></button>
                            </li>
                            <li><button type="button" class="btn btn-primary next-step"
                                    id="submit_step_data"><?php echo getLange('next'); ?></button></li>
                        </ul>
                    </div>


                    <div class="tab-pane" role="tabpanel" id="step3">
                        <div class="row">
                            <div class="form-group col-lg-6" id="bname">
                                <label for="usr"><?php echo getLange('websiteurl'); ?></label>
                                <input type="text" class="form-control " name="website_url"
                                    placeholder="Website / Facebook Page">
                            </div>
                            <div class="form-group col-lg-6" id="bname">
                                <label for="usr"><span style="color: red;">*</span>
                                    <?php echo getLange('select') . ' ' . getLange('city'); ?></label>
                                <select class="form-control bname_check2 js-example-basic-single city" name="city"
                                    required>
                                    <option value="" disabled selected>Select</option>
                                    <?php
                                    $cities = mysqli_query($con, "SELECT * FROM cities");
                                    while ($city = mysqli_fetch_array($cities)) {
                                    ?>
                                    <option value="<?php echo $city['city_name']; ?>">
                                        <?php echo $city['city_name']; ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-6" id="bname">
                                <label for="usr"><span style="color: red;">*</span>
                                    <?php echo getLange('selectnatureofaccount'); ?></label>
                                <select class="form-control bname_check2 js-example-basic-single customer_type"
                                    name="customer_type" required id="cust_type">
                                    <option value="" disabled selected>Select</option>
                                    <?php $account_q = mysqli_query($con, "SELECT * FROM pay_mode WHERE account_type!='4' AND account_type!='5' AND account_type!='6' ORDER BY id ASC");
                                    while ($row = mysqli_fetch_array($account_q)) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['pay_mode']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                  <input type="hidden" name="c_payable" class="form-control" id ="getValue" >
                                  <input type="hidden" name="p_payable" class="form-control" id ="getValueparent1" >
                                  <input type="hidden" name="p_acc_id" class="form-control" id ="p_acc_id" >
                                  <input type="hidden" name="c_recievable" class="form-control" id ="getValue1" >
                                  <input type="hidden" name="p_recievable" class="form-control" id ="getValueparent2" >
                                  <input type="hidden" name="r_acc_id" class="form-control" id ="r_acc_id" >
                            </div>
                            <div class="form-group col-lg-6" id="bname">
                                <label for="usr"><span style="color: red;">*</span>
                                    <?php echo getLange('producttype'); ?></label>
                                <select class="form-control bname_check2 js-example-basic-single product_type"
                                    name="product_type" required>
                                    <option value="" disabled selected>Select</option>
                                    <?php $product_q = mysqli_query($con, "SELECT * FROM products ORDER BY id DESC");
                                    while ($row = mysqli_fetch_array($product_q)) { ?>
                                    <option><?php echo $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-6" id="bname">
                                <label for="usr"><span
                                        style="color: red;">*</span><?php echo getLange('expectedaverageshipmentmonth'); ?></label>
                                <input type="text" class="form-control bname_check2 expected_shipment"
                                    name="expected_shipment" required>
                            </div>
                        </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button"
                                    class="btn btn-default prev-step"><?php echo getLange('previous'); ?></button>
                            </li>
                            <li><button type="button" class="btn btn-primary btn-info-full next-step"
                                    id="submit_step_data12"><?php echo getLange('next'); ?></button></li>
                        </ul>
                    </div>

                    <div class="tab-pane" role="tabpanel" id="step4">
                        <div class="msg"></div>
                        <div class="row">
                            <div class="form-group col-lg-6" id="bname">
                                <label for="usr"><span
                                        style="color: red;">*</span><?php echo getLange('password'); ?></label>
                                <input type="password" class="form-control bname_check3 password" name="password"
                                    required>
                            </div>
                            <div class="form-group col-lg-6" id="bname">
                                <label for="usr"><span
                                        style="color: red;">*</span><?php echo getLange('comfirmpassword'); ?></label>
                                <input type="password" class="form-control bname_check3 repassword" name="repassword"
                                    required>
                                <div class="msg_pass"></div>
                            </div>
                        </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button"
                                    class="btn btn-default prev-step"><?php echo getLange('previous'); ?></button>
                            </li>
                            <li><button type="submit" class="btn btn-primary btn-info-full submit_step_data3"
                                    id="final_submit"><?php echo getLange('submit'); ?></button></li>
                        </ul>
                    </div>
                    <!-- <div class="tab-pane" role="tabpanel" id="complete">
                        <div class="row">
                        	<div class="col-sm-8 padd_left">
                        		<div class="company_info">
                        			<h3>Peronal Information</h3>
                        			<ul>
                        				<li><b>Company Name / Brand Name:</b></li>
                        				<li><b>Person of Contact:</b> </li>
                        				<li><b>Phone Number:</b> </li>
                        				<li><b>Email:</b> </li>
                        				<li><b>Company / Pickup Address:</b> </li>
                        				<li><b> City:</b> </li>
                        				<li><b> CNIC Number:</b> 	</li>
                        				<li><b> CNIC Number:</b> 	</li>
                        				<li><b> CNIC Copy:</b> 	</li>
                        			</ul>
                        		</div>
                        	</div>
                        	<div class="col-sm-4 padd_right">
                        		<div class="company_info">
                        			<h3>Bank In Formation</h3>
                        			<ul>
                        				<li><b>Bank Name:</b>  </li>
                        				<li><b>Account Title:</b>  </li>
                        				<li><b>Account Number:</b> </li>
                        				<li><b>Branch Name:</b> </li>
                        				<li><b>Branch Code:</b> </li>
                        				<li><b> Swift Code:</b> </li>
                        				<li><b> IBAN:</b> 	</li>
                        			</ul>
                        		</div>
                        	</div>
                        </div>
                        <div class="row">
                        	<div class="col-sm-8 padd_left">
                        		<div class="company_info">
                        			<h3>Shipping Information</h3>
                        			<ul>
                        				<li><b>Website URL:</b> </li>
                        				<li><b>Select City:</b> </li>
                        				<li><b>Select Nature of Account:</b> </li>
                        				<li><b>Product Type Select:</b> </li>
                        				<li><b>Expected Average Shipments / Month:</b> </li>
                        			</ul>
                        		</div>
                        	</div>
                        	<div class="col-sm-4 padd_right">
                        		<div class="company_info">
                        			<h3>Password</h3>
                        			<ul>
                        				<li><b>Password:</b> </li>
                        				<li><b>Confirm Password:</b> $</li>
                        			</ul>
                        		</div>
                        	</div>
                        </div>
                         <input type="submit" name="submit" id="submit_form_data">
                    </div> -->
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>

</div>
</section>
</div>

</div>
<?php include "includes/footer.php"; ?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
</script>
<script type="text/javascript">
$(document).ready(function() {
    // $('.select2').select2();

    $("#logo").change(function() {
        var validExtensions = ["jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF", "PNG"]
        var file = $(this).val().split('.').pop();

        if (validExtensions.indexOf(file) == -1) {
            var msg = ("Only formats are allowed : " + validExtensions.join(', '));
            $('#msg').html('');
            $('#msg').html(msg);
            $(this).val("");
        } else {
            $('#msg').html('');
        }
    });
    $("#image").change(function() {
        var validExtensions = ["jpg", "jpeg", "png", "JPG", "JPEG", "PNG"]
        var file = $(this).val().split('.').pop();
        if (validExtensions.indexOf(file) == -1) {
            var msg = ("Only formats are allowed : " + validExtensions.join(', '));
            $('.msg').html('');
            $('.msg').html(msg);
            $(this).val("");
        } else {
            $('.msg').html('');
        }

    });

    if (($('.expected_shipment').val()) == '') {
        step1_submit2();
    }
    $('body').on('keyup change', '.bname_check2', function(e) {
        e.preventDefault();
        step1_submit2();
    });

    function step1_submit2() {
        var validation = true;
        var product_type = $('body').find('.product_type').val();
        var customer_type = $('body').find('.customer_type').val();
        var city = $('body').find('.city').val();
        var expected_shipment = $('body').find('.expected_shipment').val();
        if (product_type == null || customer_type == null || city == null || expected_shipment == '') {
            validation = false;
        }
        if (validation == false) {
            $('#submit_step_data12').prop('disabled', true);
        } else {
            $('#submit_step_data12').prop('disabled', false);
        }
    }
    if (($('.password').val()) == '') {
        step1_submit3();
    }
    $('body').on('keyup change', '.bname_check3', function(e) {
        e.preventDefault();
        step1_submit3();
    });

    function step1_submit3() {
        var validation = true;
        var password = $('body').find('.password').val();
        var repassword = $('body').find('.repassword').val();
        if (password == '' || repassword == '') {
            validation = false;
        }
        if (validation == false) {
            $('.submit_step_data3').prop('disabled', true);
        } else {
            if (password !== repassword) {
                $('body').find('.msg_pass').html('Password does not match.');
                $('.submit_step_data3').prop('disabled', true);
            } else {
                $('body').find('.msg_pass').html('');
                $('.submit_step_data3').prop('disabled', false);
            }
        }
    }

    $(document).on('blur', '.emailleee', function() {
        var email = $(this).val();
        var email_current = $(this);
        error = $(this).parent().find("div.help-block");
        if (email != "") {
            var postdata = "action=email&email=" + email;
            $.ajax({
                type: 'POST',
                data: postdata,
                url: 'ajax.php',
                success: function(fetch) {
                    error.html(fetch);
                    if (error.html() !== "") {
                        $(email_current).parent().addClass("has-error").addClass(
                            "has-danger");
                        $('input[type="submit"]').attr('disabled', true);
                        var wringmsg = 'wringmsg';
                        $('.msg_email').val('');
                        $('.msg_email').val(wringmsg);
                        step1_submit();
                    } else {
                        $('input[type="submit"]').attr('disabled', false);
                        $('.msg_email').val('');
                        step1_submit();
                    }
                }
            });
        }
    });
    if (($('.bname').val()) == '') {
        step1_submit();
    }
    $('body').on('keyup change', '.bname_check', function(e) {
        e.preventDefault();
        step1_submit();
    });

    function step1_submit() {
        var validation = true;
        var fname = $('body').find('.fname').val();
        var bname = $('body').find('.bname').val();
        var mobile_no = $('body').find('.mobile_no').val();
        var address = $('body').find('.address').val();
        var email = $('body').find('.emailleee').val();
        var cnic = $('body').find('.cnic').val();
        var msg_email = $('body').find('.msg_email').val();
        if (fname == '' || bname == '' || mobile_no == "" || address == "" || email == "" || cnic == "" ||
            msg_email != '') {
            validation = false;
        }
        if (validation == false) {

            $('#submit_step_data1').prop('disabled', true);
        } else {
            $('#submit_step_data1').prop('disabled', false);
        }
    }
    $('.nav-tabs > li a[title]').tooltip();
    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

        var $target = $(e.target);

        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function(e) {

        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);

    });
    $(".prev-step").click(function(e) {

        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}

function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}
const api_key = '<?php echo getConfig("api_key") ?>';
var placeSearch, autocomplete;
var componentForm = {
    // street_number: 'short_name',
    // route: 'long_name',
    // locality: 'long_name',
    // administrative_area_level_1: 'short_name',
    // country: 'long_name',
    // postal_code: 'short_name'
};
// starting Navigator

navigator.geolocation.getCurrentPosition(function(position) {
        getUserAddressBy(position.coords.latitude, position.coords.longitude);
        latitude = position.coords.latitude;
        longitude = position.coords.longitude;
        // console.log("ere latitude is" + latitude)
        // console.log(" er e longitude is" + longitude)
        initialize();
    },
    function(error) {
        console.log("The Locator was denied :(")
    })
var locatorSection = document.getElementById("location-input-section")

function init() {
    var locatorButton = document.getElementById("location-button");
    locatorButton.addEventListener("click", locatorButtonPressed)
}

function locatorButtonPressed() {
    locatorSection.classList.add("loading")

    navigator.geolocation.getCurrentPosition(function(position) {
            getUserAddressBy(position.coords.latitude, position.coords.longitude)
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
        },
        function(error) {
            locatorSection.classList.remove("loading")
            alert("The Locator was denied :( Please add your address manually")
        })
}

function getUserAddressBy(lat, long) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var address = JSON.parse(this.responseText)
            document.getElementById('property_add').value = address.results[0].formatted_address;
            document.getElementById('google_address').value = address.results[0].formatted_address;
            // filladdress(address.results[0]);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = long;

        }
    };
    xhttp.open("GET", "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + long +
        "&key=" + api_key + "", true);
    xhttp.send();

}
// Ending Navigator

var latitude = document.getElementById('latitude').value;
var longitude = document.getElementById('longitude').value;

// console.log("latitude is" + latitude)
// console.log("longitude is" + longitude)

function initialize() {

    var latlng = new google.maps.LatLng(latitude, longitude);
    var map = new google.maps.Map(document.getElementById('mapping'), {
        center: latlng,
        zoom: 14
    });
    var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        draggable: true,
        anchorPoint: new google.maps.Point(0, -29)
    });
    var input = document.getElementById('property_add');
    var geocoder = new google.maps.Geocoder();
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        bindDataToForm(place.formatted_address, place.geometry.location.lat(), place.geometry
            .location.lng());
        infowindow.setContent(place.formatted_address);
        infowindow.open(map, marker);
    });
    // this function will work on marker move event into map
    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({
            'latLng': marker.getPosition()
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    bindDataToForm(results[0].formatted_address, marker.getPosition().lat(),
                        marker
                        .getPosition().lng());
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                }
            }
        });
    });
}
// }, false);

function bindDataToForm(address, lat, lng) {
    document.getElementById('property_add').value = address;
    document.getElementById('google_address').value = address;
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}
</script>
 <script type="text/javascript">
    $('#cust_type').on('change',function(){
        var id = $(this).val();
         $.ajax({
        url: 'admin/ajax.php',
        dataType: "json",
        type: "Post",
        data: { getAccount:1, id:id},
        success: function (data) {
           console.log(data);
           $('#getValue').val(data.payable);
           $('#getValueparent1').val(data.payable_parent);
           $('#p_acc_id').val(data.payable_acc_id);
           $('#getValue1').val(data.receivable);
           $('#getValueparent2').val(data.receivable_parent);
           $('#r_acc_id').val(data.recievable_acc_id);
        },
     
    });
    });
    
  </script>