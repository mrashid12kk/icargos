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
<style >
    button{
        width: 250px;
        height: 100px;
    }
    #barcodeInput{
        display: none;
    }
    a, a:hover,a:focus{
    	color:#fff;
    }

</style>

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


            <div class="warper container-fluid text-center justify-content-center">

                <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->
            
             <div class="row">
                 <div class="col-md-6">
                    <?php
                        $id= $_GET['order_id'];
                    ?>
                     <button type="button" class="btn btn-primary"><a href="../invoice_international.php?order_id=<?= $id?>&print=1&booking=1" target="_blank" id="invoice">Invoice Print</a></button>

                 </div>
                 <div class="col-md-6"><button type="button" class="btn btn-success" ><a href="../small_bulk_invoice.php?print_data=<?= $id?>&save_print=&print=1 " target="_blank">Label Print (4x2)</a></button></div>
                          </div>
             <br>
             <!-- https://a.icargos.com/portal/small_bulk_invoice.php?print_data=756&save_print=&print=1 -->
             <div class="row">
                 <div class="col-md-6">
                     <button type="button" class="btn btn-danger"><a href="	../airway_bill.php?order_id=<?= $id?>&booking=1&print=1" id="airway_bill" target="_blank">Airway Bill Print</a></button>

                 </div>
                 <div class="col-md-6"><button type="button" class="btn btn-warning"><a href="../invoicehtml_new.php?order_id=<?= $id?>&print=1&booking=1" target="_blank">Gift Invoice Print</a></button></div>
             </div>
                          <br>

            <div class="row">
                 <div class="col-md-6">
                     <button type="button" class="btn btn-dark" style="background-color: #5bea23;"><a href=" ../invoicehtml.php?order_id=<?= $id?>&save_print=1&print=1&booking=1" id="airway_bill" target="_blank">Lable Print</a></button>

                 </div>
            <div class="col-md-6"><button type="button" class="btn btn-warning" style="    background-color: red;">
                <a href="../receipt_invoice.php?order_id=<?= $id?>&print=1&frontdesk=1&booking=1" target="_blank">Thermal Reciept Print</a></button></div>
             </div>
             
            </div>
            <!-- Warper Ends Here (working area) -->

 
            <?php

            include "includes/footer.php";
        } else {
            header("location:index.php");
        }
        ?>