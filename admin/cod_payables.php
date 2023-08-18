<?php

    session_start();

 //    if(empty($_SESSION['branch_id']) ){

 //     header("Location:index.php");

    // }

    // echo "<pre>";

    // print_r($_SERVER);

    // die();

        require 'includes/conn.php';

        require 'includes/functions.php';

    if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){

         require_once "includes/role_helper.php";

    if (!checkRolePermission($_SESSION['user_role_id'],55,'view_only',$comment =null)) {



        header("location:access_denied.php");

    }

        include "includes/header.php";

        $cities1 = mysqli_query($con,"SELECT * FROM cities WHERE 1 ");

        $cities2 = mysqli_query($con,"SELECT * FROM cities WHERE 1 order by id desc ");

        $drivers = mysqli_query($con,"SELECT * FROM users WHERE type='driver' order by id desc ");

        $customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");



        $active_customer_query = "";

        if(isset($_GET['active_customer'])){

            $active_customer = $_GET['active_customer'];

            if(empty($active_customer)){

                $active_customer_query = "";

            }else{

                $active_customer_query = " AND customer_id=".$active_customer." ";

            }

        }

?>

<body data-ng-app>

<style>

    .row{

        margin: 0 !important;

    }

    .table-responsive{

        padding: 0 !important;

    }

    .col-md-2{

            padding-right: 20px !important;

    }

    .select2-container ,.form-control{

    margin: 0;

    width: 97% !important;

}

.select2-container--default .select2-selection--single {

    border: 1px solid #ccc !important;

}

.select2-container .select2-selection--single {

    height: 34px !important;

}

.buttons-print,.buttons-pdf{

    display: none !important;

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



          

            <?php



            include "pages/reports/cod_payables.php";



            ?>





        </div>

        <!-- Warper Ends Here (working area) -->





     <?php



    include "includes/footer.php";

    }

    else{

        header("location:index.php");

    }

    ?>

        <script type="text/javascript">

            $(function () {

                $('.datetimepicker4').datetimepicker({

                    format: 'YYYY/MM/DD',

                });

            });



            $('body').on('click','.main_select',function(e){

        var check = $('.orders_tbl').find('tbody > tr > td:first-child .order_check');

        if($('.main_select').prop("checked") == true){

            $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked',true);

        }else{

            $('.orders_tbl').find('tbody > tr > td:first-child .order_check').prop('checked',false);

        }



        $('.orders_tbl').find('tbody > tr > td:first-child .order_check').val();

    })

            var mydata = [];

            $('body').on('click','.print_invoice',function(e){

        e.preventDefault();

        $('.orders_tbl > tbody  > tr').each(function() {

            var checkbox = $(this).find('td:first-child .order_check');

            if(checkbox.prop("checked") ==true){

                var order_id = $(checkbox).data('id');

                mydata.push(order_id);

            }
        });

        var order_data = mydata.join(',');



        $('#print_data').val(order_data);

        $('#bulk_submit').submit();

        location.reload();

    })

        </script>

