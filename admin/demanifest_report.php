<?php
session_start();
require 'includes/conn.php';
if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
	require_once "includes/role_helper.php";
	if (!checkRolePermission($_SESSION['user_role_id'],31,'view_only',$comment =null)) {

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

				$branch_query='';
				$branch_id = $_SESSION['branch_id'];

				if (isset($branch_id) && !empty($branch_id)) {
					$branch_query .=" AND branch_id= $branch_id";
				}else{
					$branch_query .=" AND (branch_id = 1 OR branch_id IS NULL)";
				}

				$query1 = mysqli_query($con,"SELECT * FROM demanifest_master WHERE 1 $branch_query order by id desc ");
				?>

				<style type="text/css">
					.zones_main{
						margin-bottom: 20px;
					}
					.badge {
						width: 100%;
						border-radius: 2px;
						padding: 6px 5px;
						line-height: 1.6;
					}
				</style>
				<?php function getBranchNameById($id)
				{
					global $con;
					$branchQ = mysqli_query($con, "SELECT name from branches where id = $id");

					$res = mysqli_fetch_array($branchQ);

					return $res['name'];
				}
				if(!function_exists('receiv_report')){
					function receiv_report($id=null)
					{
						global $con;
						if($id)
						{
							if($id==1){
								return 'With Address';
							}
							else{
								return 'With Out Address';
							}
						}
					}
				}
				?>
				<div class="panel panel-default">

					<div class="panel-heading"><?php echo getLange('demanifest'); ?> <?php echo getLange('report'); ?></div>

					<div class="panel-body" id="same_form_layout">

						<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

							<div class="row">

								<div class="col-sm-12 table-responsive gap-none">



									<table class="table table-striped table-bordered dataTable_with_sorting no-footer" id="basic-datatable" >

										<thead>

											<tr role="row">
												<th><?php echo getLange('srno'); ?></th>
												<th><?php echo getLange('manifestno'); ?> </th>
												<th><?php echo getLange('branch'); ?> </th>
												<th><?php echo getLange('transactionid'); ?> </th>
												<th><?php echo getLange('arrivaldate'); ?> </th>
												<th><?php echo getLange('truckno'); ?> </th>
												<th><?php echo getLange('totalorder'); ?></th>
												<th><?php echo getLange('totalquantity'); ?> </th>
												<th><?php echo getLange('totalweight'); ?> </th>
												<!-- <th><?php echo getLange('receivedreport'); ?></th> -->
												<th><?php echo getLange('action'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$sr=1;
											while($fetch1=mysqli_fetch_array($query1)){
												?>
												<tr class="gradeA odd" role="row">
													<td><?php echo $sr; ?></td>
													<td class="sorting_1"><?php echo $fetch1['manifest_no']; ?></td>
													<td class="sorting_1"><?php echo getBranchNameById($fetch1['branch_id']); ?></td>
													<td class="sorting_1"><?php echo isset($fetch1['transaction_id']) ? $fetch1['transaction_id'] : ''; ?></td>
													<td class="sorting_1"><?php echo isset($fetch1['arrive_date']) ? $fetch1['arrive_date'] : ''; ?></td>
													<td class="sorting_1"><?php echo isset($fetch1['truck_no']) ? $fetch1['truck_no'] : ''; ?></td>
													<td class="sorting_1"><?php echo isset($fetch1['total_cn']) ? $fetch1['total_cn'] : ''; ?></td>
													<td class="sorting_1"><?php echo isset($fetch1['total_pieces']) ? number_format($fetch1['total_pieces'] , 2) : ''; ?></td>
													<td class="sorting_1"><?php echo isset($fetch1['total_weight']) ? number_format($fetch1['total_weight'], 2) : ''; ?></td>
													<!-- <td class="sorting_1"><?php echo receiv_report($fetch1['received_report']); ?></td> -->
													<td class="sorting_1"><a href="demanifest.php?print_id=<?php echo $fetch1['id']; ?>" target="_blank" class="btn btn-info"><?php echo getLange('print'); ?></a></td>
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
		}
		else{
			header("location:index.php");
		}
		?>
