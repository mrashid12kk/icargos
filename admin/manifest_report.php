<?php
session_start();
require 'includes/conn.php';
if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver')) {
	require_once "includes/role_helper.php";
	if (!checkRolePermission($_SESSION['user_role_id'], 30, 'view_only', $comment = null)) {

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
				$message = '';
				if (isset($_GET['delete_id']) && $_GET['delete_id'] != '') {
					$query1 = mysqli_query($con, "SELECT * FROM manifest_master WHERE id=" . $_GET['delete_id']);
					if (mysqli_affected_rows($con) > 0) {
						$query1 = mysqli_query($con, "DELETE FROM `manifest_master` WHERE id=" . $_GET['delete_id']);
						$query1 = mysqli_query($con, "DELETE FROM `manifest_detail` WHERE manifest_id=" . $_GET['delete_id']);
						if (mysqli_affected_rows($con)) {
							$message = "<div class='alert alert-Success'>Manifest Deleted Successfully</div>";
						} else {
							$message = "<div class='alert alert-danger'>Manifest Not Deleted UnSuccessfully</div>";
						}
					}
				}
				$branch_query = '';
				$branch_id = $_SESSION['branch_id'];
				$franchisen_role = '';
				if (isset($_SESSION['user_role_id']) && $_SESSION['user_role_id'] == getfranchisemanagerId()) {
					$franchisen_role = "AND user_id=" . $_SESSION['users_id'];
				}
				if (isset($branch_id) && !empty($branch_id)) {
					$branch_query .= " AND branch_id= $branch_id";
				} else {
					$branch_query .= " AND (branch_id = 1 OR branch_id IS NULL)";
				}
				if (isset($_GET['from']) && !empty($_GET['from'])) {
					$from = date('Y-m-d', strtotime($_GET['from']));
					$to = date('Y-m-d', strtotime($_GET['to']));
					$query1 = mysqli_query($con, "SELECT * FROM manifest_master WHERE DATE_FORMAT(`date`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`date`, '%Y-%m-%d') <= '" . $to . "'  AND (origin IN ($all_allowed_origins) OR destination IN ($all_allowed_origins) OR receiving_branch = $branch_id ) $franchisen_role order by manifest_no desc ");
				} else {

					$from = date('Y-m-d', strtotime('today - 30 days'));
					$to = date('Y-m-d');
					if($branch_id = 1){
								$sql = "SELECT * FROM manifest_master WHERE DATE_FORMAT(`date`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`date`, '%Y-%m-%d') <= '" . $to . "'  order by manifest_no desc";
					}
					else{
						$sql = "SELECT * FROM manifest_master WHERE DATE_FORMAT(`date`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`date`, '%Y-%m-%d') <= '" . $to . "'  AND (origin IN ($all_allowed_origins) OR destination IN ($all_allowed_origins) OR receiving_branch = $branch_id ) $franchisen_role  order by manifest_no desc ";
					}
				

					$query1 = mysqli_query($con, $sql);
					var_dump($sql);	
					// die();
				}

				if (!function_exists('collection_center_name')) {
					function collection_center_name($id = null)
					{
						global $con;
						if ($id) {
							$query = mysqli_query($con, "SELECT name from collection_centers where id=" . $id);
							$resposne = mysqli_fetch_assoc($query);
							return $resposne['name'];
						}
					}
				}
				if (!function_exists('getServiceType')) {
					function getServiceType($id = null)
					{
						global $con;
						if ($id) {
							$query_service_type = mysqli_query($con, "SELECT * from manifest_services WHERE id=" . $id);
							$resposne_service_type = mysqli_fetch_assoc($query_service_type);
							return isset($resposne_service_type['name']) ? $resposne_service_type['name'] : '';
						}
					}
				}
				if (!function_exists('users')) {
					function users($id = null)
					{
						global $con;
						if ($id) {
							$query_service_type = mysqli_query($con, "SELECT * from users WHERE id=" . $id);

							$resposne_service_type = mysqli_fetch_assoc($query_service_type);
							return isset($resposne_service_type['Name']) ? $resposne_service_type['Name'] : '';
						}
					}
				}
				if (!function_exists('modes')) {
					function modes($id = null)
					{
						global $con;
						if ($id) {
							$query = mysqli_query($con, "SELECT name from `modes` where id=" . $id);
							$resposne = mysqli_fetch_assoc($query);
							return $resposne['name'];
						}
					}
				}
				if (!function_exists('types')) {
					function types($id = null)
					{
						global $con;
						if ($id) {
							$query = mysqli_query($con, "SELECT name from `types` where id=" . $id);
							$resposne = mysqli_fetch_assoc($query);
							return $resposne['name'];
						}
					}
				}
				if (!function_exists('branches')) {
					function branches($id = null)
					{
						global $con;
						if ($id) {
							$query = mysqli_query($con, "SELECT name from `branches` where id=" . $id);
							$resposne = mysqli_fetch_assoc($query);
							return $resposne['name'];
						}
					}
				}
				if (!function_exists('transportco')) {
					function transportco($id = null)
					{
						global $con;
						if ($id) {
							$query = mysqli_query($con, "SELECT name from `transport_company` where id=" . $id);
							$resposne = mysqli_fetch_assoc($query);
							return $resposne['name'];
						}
					}
				}
				if (!function_exists('totalcharge')) {
					function totalcharge($id = null)
					{
						global $con;
						if ($id) {
							$totalcharges = '';
							$query = mysqli_query($con, "SELECT charges_amount from order_charges where order_id=" . $id);
							while ($resposne = mysqli_fetch_assoc($query)) {
								$totalcharges += $resposne['charges_amount'];
							}
							return  $totalcharges;
						}
					}
				}
				?>
            <?php

				$courier_query = mysqli_query($con, "Select * from users where type='driver'");
				$delivery_courier_query = mysqli_query($con, "Select * from users where type='driver'");
				$status_query = mysqli_query($con, "Select * from order_status where active='1'");
				$city_query = mysqli_query($con, "Select * from cities where 1");
				$branch_query = mysqli_query($con, "Select * from branches where 1");
				$currency = mysqli_fetch_array(mysqli_query($con, "SELECT value FROM config WHERE `name`='currency' "));

				$status_data_fr_dl = mysqli_fetch_array(mysqli_query($con, "Select * from order_status where sts_id=1  "));

				?>
            <style type="text/css">
            .zones_main {
                margin-bottom: 20px;
            }

            .badge {
                width: 100%;
                border-radius: 2px;
                padding: 6px 5px;
                line-height: 1.6;
            }
            </style>
            <?php
				function getBranchNameById($id)
				{
					global $con;
					$branchQ = mysqli_query($con, "SELECT name from branches where id = $id");

					$res = mysqli_fetch_array($branchQ);

					return $res['name'];
				}
				function encrypt($string)
				{
					$key = "usmannnn";
					$result = '';
					for ($i = 0; $i < strlen($string); $i++) {
						$char = substr($string, $i, 1);
						$keychar = substr($key, ($i % strlen($key)) - 1, 1);
						$char = chr(ord($char) + ord($keychar));
						$result .= $char;
					}

					return base64_encode($result);
				}
				?>


            <div class="panel panel-default">
                <?php if (isset($message) && !empty($message)) {
						echo $message;
					} ?>
                <div class="panel-heading"><?php echo getLange('manifestreport'); ?></div>

                <div class="panel-body" id="same_form_layout">

                    <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

                        <div class="row">
                            <form method="GET" action="" class="pickuprun_sheet">
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('from'); ?></label>
                                        <input type="text" value="<?php echo $from; ?>"
                                            class="form-control datetimepicker4" name="from">
                                    </div>
                                </div>
                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('to'); ?></label>
                                        <input type="text" value="<?php echo $to; ?>"
                                            class="form-control datetimepicker4" name="to">
                                    </div>
                                </div>
								
                                <input type="submit" name="submit" value="<?php echo getLange('submit') ?>"
                                    class="btn btn-info" style="margin-top: 25px;">
                            </form>
                            <div class="col-sm-12 table-responsive gap-none">

                                <table class="table table-striped table-bordered dataTable_with_sorting no-footer"
                                    id="basic-datatable">


                                    <thead>

                                        <tr role="row">
                                            <th><?php echo getLange('srno'); ?></th>
                                            <th><?php echo getLange('manifestno'); ?> </th>
                                            <th>Manifest Type</th>
                                            <th><?php echo getLange('type'); ?> </th>
                                            <th><?php echo getLange('mode'); ?> </th>
                                            <th><?php echo getLange('biltyno'); ?> </th>
                                            <th><?php echo getLange('serviceby'); ?></th>
                                            <th><?php echo getLange('transportcompany'); ?> </th>
                                            <th><?php echo getLange('truckno'); ?> </th>
                                            <th><?php echo getLange('sendingbranch'); ?></th>
                                            <th><?php echo getLange('sealno'); ?></th>
                                            <th><?php echo getLange('origin'); ?> </th>
                                            <th><?php echo getLange('receivingbranch'); ?></th>
                                            <th><?php echo getLange('destination'); ?></th>
                                            <th><?php echo getLange('departuredate'); ?></th>
                                            <th><?php echo getLange('receivingname'); ?></th>

                                            <th><?php echo getLange('arrivaldate'); ?></th>

                                            <th><?php echo getLange('remarks'); ?></th>
                                            <th><?php echo getLange('quantity'); ?></th>
                                            <th><?php echo getLange('weight'); ?></th>

                                            <th><?php echo getLange('createdat'); ?></th>
                                            <th><?php echo getLange('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
											$sr = 1;
											while ($fetch1 = mysqli_fetch_array($query1)) {
												$sql = mysqli_query($con, "SELECT * from `manifest_detail` where manifest_id = '".$fetch1['id']."'");
												$query = mysqli_fetch_assoc($sql);

											?>
                                        <tr class="gradeA odd" role="row">
                                            <td><?php echo $sr; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['manifest_no']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['check_manifest']; ?></td>
                                            <td class="sorting_1"><?php echo types($fetch1['type']); ?></td>
                                            <td class="sorting_1"><?php echo modes($fetch1['mode']); ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['bilty_no']; ?></td>
                                            <td class="sorting_1"><?php echo getServiceType($fetch1['service_by']); ?>
                                            </td>
                                            <td class="sorting_1"><?php echo transportco($fetch1['transport_co']); ?>
                                            </td>
                                            <td class="sorting_1"><?php echo $fetch1['truck_no']; ?></td>
                                            <td class="sorting_1"><?php echo branches($fetch1['sending_branch']); ?>
                                            </td>
                                            <td class="sorting_1"><?php echo $fetch1['seal_no']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['origin']; ?></td>
                                            <td class="sorting_1"><?php echo branches($fetch1['receiving_branch']); ?>
                                            </td>
                                            <td class="sorting_1"><?php echo $fetch1['destination']; ?></td>
                                            <td class="sorting_1">
                                                <?php echo $fetch1['departure_date'] . ' ' . $fetch1['departure_time']; ?>
                                            </td>
                                            <td class="sorting_1"><?php echo users($fetch1['receiver_name']); ?></td>
                                            <td class="sorting_1">
                                                <?php echo $fetch1['arrival_date'] . ' ' . $fetch1['arrival_time']; ?>
                                            </td>
                                            <td class="sorting_1"><?php echo $fetch1['remarks']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['pieces']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['weight']; ?></td>
                                            <td class="sorting_1"><?php echo $fetch1['created_at']; ?></td>
                                            <td class="sorting_1"><a
                                                    href="manifest.php?print_id=<?php echo $fetch1['id']; ?>"
                                                    target="_blank"
                                                    class="btn btn-info"><?php echo getLange('print'); ?></a>
                                                    <?php
                                                    	if($query['is_demanifest'] == '0'){
                                                    ?>
                                                <a href="manifest_report.php?delete_id=<?php echo $fetch1['id']; ?>"
                                                    onclick="return confirm('Are you sure you want to delete this Manifest?');"
                                                    class="btn btn-info"><?php echo getLange('delete'); ?></a>
                                                <a href="manifest_edit.php?id=<?php echo $fetch1['id']; ?>"
                                                   
                                                    class="btn btn-info"><?php echo getLange('edit'); ?></a>
                                                    <?php
                                                    	}
                                                    ?>
                                               </td>


                                        </tr>

                                        <?php
												$sr++;
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