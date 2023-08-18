<?php

session_start();

require 'includes/conn.php';

if (isset($_SESSION['users_id']) && $_SESSION['type'] == 'admin') {
	require_once "includes/role_helper.php";
	if (!checkRolePermission($_SESSION['user_role_id'], 21, 'view_only', $comment = null)) {

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



            <div class="page-header">
                <h1><?php echo getLange('dashboard'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1>
            </div>

            <div class="row">
                <?php
					require_once "pages/location/location_sidebar.php";
					?>
                <div class="col-sm-10 table-responsive" id="setting_box">

                    <?php



						include "pages/area/areasdata.php";



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
                $('#addmorearea').click(function() {
                    var area_name = $(".city_name").html();
                    $("input[name='area_code[]']").addClass('area_code');
                    $("input[name='area_code[]']").removeClass('check_area_code');
                    var areas = '<div class="row" style="margin-bottom:21px !important">' +
                        '<div class="col-lg-4" style="padding-left: 0;">' +
                        '<input type="text" class="form-control check_area_code" name="area_code[]" placeholder="Enter Area Code">' +
                        '</div>' +
                        '<div class="col-lg-4" style="padding-left: 0;">' +
                        '<input type="text" class="form-control area_name" name="area[]" placeholder="Enter City/Area Name">' +
                        '</div>' +

                        '<div class="col-lg-4" style="padding-left: 0;">' +
                        area_name +
                        '</div>' +
                        '</div>';
                    $("#areas").append(areas);
                });
                $(document).on('keyup', '.check_area_code', function() {
                    var thi = $(this).val();
                    var values = [];
                    $('body').find(".area_code").each(function() {
                        var area_code = $(this).val();
                        if (area_code === thi) {
                            $('.add_form_btn').prop('disabled', true);
                            $('#addmorearea').prop('disabled', true);
                            $('body').find(".error_area_code").show();
                            $('body').find(".error_area_code").show();
                            $('body').find(".error_area_code").html(thi +
                                ' Area Code already in These Rows');
                        } else {
                            $('.add_form_btn').prop('disabled', false);
                            $('#addmorearea').prop('disabled', false);
                            $('body').find(".error_area_code").hide();
                        }
                    });
                });
                </script>