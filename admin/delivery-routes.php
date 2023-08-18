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


        <div class="warper container-fluid">
            <?php

                include "pages/location/location_sidebar.php";
                include "pages/location/delivery-routes.php";

                ?>


        </div>
        <!-- Warper Ends Here (working area) -->


        <?php

        include "includes/footer.php";
    } else {
        header("location:index.php");
    }
        ?>
        <script>
        $('.select2').select2();
        // var countryValue = $('.country').find(":selected").val();
        // if (countryValue !== '') {
        //     getState(countryValue);
        // }
        // var stateValue = $('.state').find(":selected").val();
        // if (stateValue !== '') {
        //     getCity(stateValue);
        // }

        function getState(value) {
            $.ajax({
                url: "ajax.php", //the page containing php script
                type: "post",
                data: {
                    getSate: 1,
                    country: value
                },
                success: function(result) {
                    $(document).find('.state').html(result);
                }
            });
        }

        function getCity(value) {
            $.ajax({
                url: "ajax.php", //the page containing php script
                type: "post",
                data: {
                    getCity: 1,
                    state: value
                },
                success: function(result) {
                    $(document).find('.city').html(result);
                }
            });
        }
        $(document).on('change', '.country', function() {
            $(document).find('.state').html('');
            $(document).find('.city').html('');
            let value = $(this).val();
            getState(value);
        });
        $(document).on('change', '.state', function() {
            $(document).find('.city').html('');
            let value = $(this).val();
            getCity(value);
        });
        </script>