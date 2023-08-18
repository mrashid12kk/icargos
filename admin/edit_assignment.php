<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver')) {
	require_once "includes/role_helper.php";
	if (!checkRolePermission($_SESSION['user_role_id'], 10, 'view_only', $comment = null)) {

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

            <!-- <div class="page-header"><h1>Dashboard <small>Let's get a quick overview...</small></h1></div> -->

            <?php

				include "pages/orders/edit_assignment.php";

				?>


        </div>
        <!-- Warper Ends Here (working area) -->


        <?php

		include "includes/footer.php";
	} else {
		header("location:index.php");
	}
		?>
        <script type="text/javascript">
        $(function() {
            $('.datetimepicker4').datetimepicker({
                format: 'YYYY/MM/DD',
            });
        });
        </script>
        <script type="text/javascript">
        $('body').on('click', '.main_select', function(e) {
            var check = $(this).closest('table').find('tbody > tr > td:first-child .order_check');
            if ($(this).closest('table').find('.main_select').prop("checked") == true) {
                $(this).closest('table').find('tbody > tr > td:first-child .order_check').prop('checked', true);
            } else {
                $(this).closest('table').find('tbody > tr > td:first-child .order_check').prop('checked',
                false);
            }

            $(this).closest('table').find('tbody > tr > td:first-child .order_check').val();
        })

        $('body').on('click', '.select_all', function(e) {
            var check = $('.assognment_table').find('tbody > tr > td:first-child .order_check');
            if ($('.select_all').prop("checked") == true) {
                $('.assognment_table').find('tbody > tr > td:first-child .order_check').prop('checked', true);
            } else {
                $('.assognment_table').find('tbody > tr > td:first-child .order_check').prop('checked', false);
            }

            $('.orders_tbl').find('tbody > tr > td:first-child .order_check').val();
        })
        var mydata = [];
        $('body').on('click', '.unassign_parcels', function(e) {
            e.preventDefault();
            // alert('hello')
            $('.assognment_table > tbody  > tr').each(function() {
                var checkbox = $(this).find('td:first-child .order_check');
                if (checkbox.prop("checked") == true) {
                    var order_id = $(checkbox).data('id');
                    mydata.push(order_id);
                }
            });
            // var order_data = JSON.stringify(mydata);
            $('#print_data').val(mydata.join());
            let type = $('#type').val();
            // $('#print_data').val(order_data);

            $.ajax({
                url: "ajax.php", //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                data: {
                    unAssign: 1,
                    order_ids: mydata,
                    type: type
                },
                success: function(result) {
                    // $('#messageDiv').html(result.message);
                    location.reload();
                }
            });
        })
        </script>

        <?php
		if (isset($_SESSION['print_url']) && !empty($_SESSION['print_url'])) {
			echo "<script>window.open('" . $_SESSION['print_url'] . "', '_blank')</script>";

			unset($_SESSION['print_url']);
		}
		?>