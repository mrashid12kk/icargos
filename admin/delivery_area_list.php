<?php

session_start();

require 'includes/conn.php';

if (isset($_SESSION['users_id']) && $_SESSION['type'] == 'admin') {
    require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'], 48, 'view_only', $comment = null)) {
        header("location:access_denied.php");
    }

    include "includes/header.php";

    $origincitydata = mysqli_query($con, "SELECT areas.*, cities.city_name from areas left join cities on areas.city_name=cities.id");



    $riderdata = mysqli_query($con, "Select * from users WHERE type='driver' ");

    $servicetypes = mysqli_query($con, "Select * from users WHERE user_role_id=3 or user_role_id=4 ");



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
                <h1><?php echo getLange('deliveryarealist'); ?> <small><?php echo getLange('letsgetquick'); ?></small>
                </h1>
            </div>

            <div class="row">
                <?php
                    require_once "pages/location/location_sidebar.php";
                    ?>
                <div class="col-sm-10 table-responsive" id="setting_box">

                    <?php

                        $msg = "";

                        if (isset($_POST['addareas'])) {

                            $zone = $_POST['zone_name'];

                            $rider = $_POST['rider'];



                            $insert_master  = mysqli_query($con, "INSERT INTO `delivery_zone`(`zone_name`, `rider`) VALUES ('$zone',$rider)");

                            if ($insert_master) {

                                $master_id = mysqli_insert_id($con);



                                foreach ($_POST['areas'] as $key => $value) {

                                    $area_query =  mysqli_query($con, "INSERT INTO `delivery_zone_areas`(`delivery_zone_id`, `zone_area_name`) VALUES ($master_id,$value)");
                                }
                            }

                            $rowscount = mysqli_affected_rows($con);

                            if ($rowscount > 0) {

                                $msg = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('Well_done') . '!</strong> ' . getLange('you_added_a_new_area_succesfully') . '</div>';
                            } else {

                                $msg = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('unsuccessful') . '!</strong>' . getLange('zone_has_not_been_saved') . '.</div>';
                            }
                        }

                        echo $msg;

                        ?>
                    <?php
                        if (isset($_POST['delete'])) {
                            $id = mysqli_real_escape_string($con, $_POST['id']);
                            $query1 = mysqli_query($con, "delete from delivery_zone where id=$id") or die(mysqli_error($con));
                            $query2 = mysqli_query($con, "delete from delivery_zone_areas where delivery_zone_id=$id") or die(mysqli_error($con));
                            $rowscount = mysqli_affected_rows($con);
                            if ($rowscount > 0) {
                                echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('Well_done') . '!</strong>' . getLange('you_have_delete_a_deliver_zone_successfully') . '</div>';
                            } else {
                                echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>' . getLange('unsuccessful') . '!</strong> ' . getLange('you_have_not_delete_a_delivery_zone_unsuccessfully') . '.</div>';
                            }
                        }
                        function getRiderName($id)
                        {
                            $riderQ = mysqli_query($con, "SELECT Name from users where id = $id");

                            $name = mysqli_fetch_array($riderq);

                            return $name['Name'];
                        }

                        ?>



                    <div class="panel panel-default">

                        <div class="panel-heading">Delivery Routes <span style="float: right;"></span> <a
                                href="add_delivery_zone.php" class="add_form_btn"
                                style="float: right;font-size: 11px;">Add Delivery Route
                            </a></div>

                        <div class="panel-body" id="same_form_layout">



                            <div class="panel-body" id="same_form_layout" style="padding: 11px;">
                                <div id="basic-datatable_wrapper"
                                    class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">


                                    <table cellpadding="0" cellspacing="0" border="0"
                                        class="table table-striped table-bordered dataTable no-footer"
                                        id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
                                        <thead>
                                            <tr role="row">
                                                <th>Route Code </th>
                                                <th>Route Name</th>
                                                <th>Country</th>
                                                <th>State</th>
                                                <th>City</th>
                                                <th>Pickup Commission</th>
                                                <th>Delivery Commission</th>
                                                <th>Product</th>
                                                <th><?php echo getLange('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                                $sql_query =  "SELECT delivery_zone.*, products.name as product,country.country_name,state.state_name,cities.city_name FROM  delivery_zone join products on delivery_zone.product = products.id LEFT JOIN country on delivery_zone.country_id=country.id LEFT JOIN state on delivery_zone.state_id=state.id LEFT JOIN cities on delivery_zone.city_id=cities.id order by delivery_zone.id desc";
                                                // echo $sql_query;
                                                // die;
                                                $query1 = mysqli_query($con, $sql_query);

                                                while ($fetch1 = mysqli_fetch_array($query1)) {
                                                ?>
                                            <tr class="gradeA odd" role="row">
                                                <td class="sorting_1"><?php echo $fetch1['route_code']; ?></td>
                                                <td class="sorting_1"><?php echo $fetch1['route_name']; ?></td>
                                                <td class="sorting_1"><?php echo $fetch1['country_name']; ?></td>
                                                <td class="sorting_1"><?php echo $fetch1['state_name']; ?></td>
                                                <td class="sorting_1"><?php echo $fetch1['city_name']; ?></td>

                                                <td class="sorting_1"><?php echo $fetch1['pickup_commission']; ?></td>
                                                <td class="sorting_1"><?php echo $fetch1['delivery_commission']; ?></td>

                                                <td class="sorting_1"><?php echo $fetch1['product']; ?></td>
                                                <td class="center inline_Btn">
                                                    <a
                                                        href="edit_delivery_area_zone.php?zone_id=<?php echo $fetch1['id']; ?>"><span
                                                            style="float: left; margin: 7px 3px 0;"
                                                            class="glyphicon glyphicon-edit"></span></a>

                                                    <form action="#" method="post" style="display: inherit;">
                                                        <input type="hidden" name="id"
                                                            value="<?php echo $fetch1['id']; ?>">
                                                        <button type="submit" name="delete"
                                                            onclick="return confirm('Are You Sure Delete this Employee')">
                                                            <span class="glyphicon glyphicon-trash"></span>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php

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





        </div>

        <!-- Warper Ends Here (working area) -->





        <?php



        include "includes/footer.php";
    } else {

        header("location:index.php");
    }

        ?>



        <script type="text/javascript">
        $(document).ready(function() {

            var counter = 1;

            var selected_to_array = [];







            // updateSelectedCites();

            // $('body').on('change', '#price_table .city_to', function(e) {

            //   updateSelectedCites($(this).closest('tr'));

            // })



            $('body').on('click', '.add_row', function(e) {

                e.preventDefault();

                var counter = $('#price_table > tbody tr').length;

                var row = $('#price_table > tbody tr').first().clone();

                row.find('input,select').each(function() {

                    var name = $(this).attr('name').split('[0]');

                    $(this).attr('name', name[0] + '[' + counter + ']' + name[1]);

                })

                row.find('.add_row').addClass('remove_row');

                row.find('.add_row').addClass('btn btn-danger');

                row.find('.fa-plus').addClass('fa-trash');

                row.find('.fa-plus').removeClass('fa-plus');

                row.find('.add_row').removeClass('add_row');

                $('#price_table').append(row);

                // updateSelectedCites();

            })

            $('body').on('click', '.remove_row', function(e) {

                e.preventDefault();

                $(this).closest('tr').remove();

                // updateSelectedCites();

            })

        })
        </script>