<?php
$customers = mysqli_query($con, "SELECT * FROM customers WHERE status=1");
$filter_query = "";
$active_tracking = "";
$selected_branch = '';
$assignment_type = '';
if (isset($_POST['submit'])) {
	if (isset($_POST['tracking_no']) && !empty($_POST['tracking_no'])) {
		$active_tracking = $_POST['tracking_no'];
		$assignment_no=mysqli_fetch_assoc(mysqli_query($con,"SELECT assignment_no,delivery_assignment_no,return_assignment_no FROM orders WHERE track_no='".$_POST['tracking_no']."'"));
		$assignment_query.='AND (';
		if (isset($assignment_no['assignment_no']) && $assignment_no['assignment_no']!='') {
			$assignment_query .= " assignment_no = '" . $assignment_no['assignment_no'] . "' ";
		}
		if (isset($assignment_no['delivery_assignment_no']) && $assignment_no['delivery_assignment_no']!='') {
			if (isset($assignment_query) && $assignment_query !='AND (') {
				$assignment_query.='OR';
			}
			$assignment_query .= " assignment_no = '" . $assignment_no['delivery_assignment_no'] . "' ";
		}
		if(isset($assignment_no['return_assignment_no']) && $assignment_no['return_assignment_no']!=''){
			if (isset($assignment_query) && $assignment_query !='AND (') {
				$assignment_query.='OR';
			}
			$assignment_query .= " assignment_no = '" . $assignment_no['return_assignment_no'] . "' ";
		}
		$assignment_query.=')';
	}
	if (isset($_POST['assignment_type']) && !empty($_POST['assignment_type'])) {
		$filter_query .= " AND assignment_type = '" . $_POST['assignment_type'] . "' ";
		$assignment_type = $_POST['assignment_type'];
	}
	$branch_id = isset($_POST['branch_id']) ? $_POST['branch_id'] : $_SESSION['branch_id'];
	if (isset($branch_id) and $branch_id == 'all') {
		$check_branch = "1";
		$selected_branch = 'all';
	} else {
		$check_branch = "branch_id = " . $branch_id;
		$selected_branch = $branch_id;
	}

	$from = date('Y-m-d', strtotime($_POST['from']));
	$to = date('Y-m-d', strtotime($_POST['to']));
	$query1 = mysqli_query($con, "SELECT * FROM assignments WHERE DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '" . $to . "' AND $check_branch  $filter_query $assignment_query order by id desc ");
} else {
	$from = date('Y-m-d', strtotime('today - 30 days'));
	$to = date('Y-m-d');
	$query1 = mysqli_query($con, "SELECT * FROM assignments WHERE DATE_FORMAT(`created_on`, '%Y-%m-%d') >= '" . $from . "' AND  DATE_FORMAT(`created_on`, '%Y-%m-%d') <= '" . $to . "' AND $check_branch  $filter_query $assignment_query  order by id desc ");
}

?>
<?php

$courier_query = mysqli_query($con, "Select * from users where type='driver'");

$all_branches = mysqli_query($con, "SELECT * from branches WHERE id != '1'");

?>
<style type="text/css">
	.zones_main {
		margin-bottom: 20px;
	}
	#basic-datatable_wrapper .return_type ,#basic-datatable_wrapper .return_type td{
		background:#e11414 !important;
	}
	#basic-datatable_wrapper .return_type td i,
	#basic-datatable_wrapper .return_type ,#basic-datatable_wrapper .return_type td{color: #fff;}
</style>
<div class="panel panel-default">

	<div class="panel-heading"><?php echo getLange('orderassignment'); ?> </div>

	<div class="panel-body" id="same_form_layout">

		<div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

			<div class="row">

				<div class="col-sm-12 table-responsive gap-none">
					<form method="POST" action="">
						<div class="row">
							<div class="col-sm-2 left_right_none">
								<div class="form-group">
									<label><?php echo getLange('trackingno'); ?> </label>
									<input type="text" value="<?php echo $active_tracking; ?>" class="form-control " name="tracking_no">
								</div>
							</div>

							<div class="col-sm-2 left_right_none">
								<div class="form-group">
									<label><?php echo getLange('from'); ?></label>
									<input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4" name="from">
								</div>
							</div>
							<div class="col-sm-2 left_right_none">
								<div class="form-group">
									<label><?php echo getLange('to'); ?></label>
									<input type="text" value="<?php echo $to; ?>" class="form-control datetimepicker4" name="to">
								</div>
							</div>
							<div class="col-sm-2 left_right_none">
								<div class="form-group">
									<label>Sheet Type</label>
									<select class="form-control js-example-basic-single" name="assignment_type">
										<option value="" selected>Select Assignment Type</option>
										<?php $distict_q=mysqli_query($con,"SELECT DISTINCT(assignment_type) FROM `assignments`");
										while ($row=mysqli_fetch_array($distict_q)) { ?>
										<option value="<?php echo $row['assignment_type']; ?>" <?php echo isset($assignment_type) && $assignment_type==$row['assignment_type'] ? 'selected' : ''; ?>><?php echo $row['assignment_type']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<?php if ($_SESSION['branch_id'] == '') : ?>
								<div class="col-sm-2 left_right_none">
									<div class="form-group">
										<label><?php echo getLange('select') . ' ' . getLange('branch'); ?></label>
										<select class="js-example-basic-single" name="branch_id">
											<option selected disabled><?php echo getLange('select') . ' ' . getLange('branch'); ?></option>
											<option value="all" <?php if ($selected_branch == 'all') {
																	echo "selected";
																} ?>>All Branches</option>
											<?php while ($row = mysqli_fetch_assoc($all_branches)) {

											?>
												<option <?php if ($selected_branch == $row['id']) {
															echo "selected";
														} ?> value="<?php echo $row['id']; ?>"><?php echo $row['name'] ?></option>

											<?php } ?>
										</select>
									</div>
								</div>
							<?php endif ?>

						</div>
						<div class="row">
							<div class="col-sm-1 sidegapp-submit " style="margin-top: 0">
								<input type="submit" name="submit" class="btn btn-success" value="<?php echo getLange('search'); ?>">
							</div>
						</div>
					</form>


					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">

						<thead>

							<tr role="row">

								<th><?php echo getLange('srno'); ?></th>
								<th><?php echo getLange('assignmentno'); ?> </th>
								<th><?php echo getLange('branch'); ?></th>
								<th>Created By:</th>
								<th><?php echo getLange('type'); ?></th>
								<th><?php echo getLange('rider'); ?></th>
								<th><?php echo getLange('date'); ?></th>
								<th><?php echo getLange('action'); ?></th>
							</tr>

						</thead>

						<tbody>

							<?php

							$sr = 1;
							while ($fetch1 = mysqli_fetch_array($query1)) {
								$rider_id = $fetch1['rider_id'];

								$user_id = $fetch1['created_by'];

								$users = mysqli_fetch_assoc(mysqli_query($con, "SELECT Name FROM users WHERE id=" . $user_id));

								$created_by = isset($users['Name']) ? $users['Name'] : '';

								$courier_query = mysqli_query($con, "SELECT Name FROM users WHERE type='driver' AND id='" . $rider_id . "' ");
								//$courier_query=mysqli_query($con,"Select Name from users where id='".$rider_id."' ");
								$fetch_row = mysqli_fetch_array($courier_query);

								$rider_name = isset($fetch_row['Name']) ? $fetch_row['Name'] : '';

								$branch_id = isset($fetch1['branch_id']) ? $fetch1['branch_id'] : 1;
								$branch_id_q = mysqli_query($con, "Select name from branches where id='" . $branch_id . "' ");
								$fetch_branch = mysqli_fetch_assoc($branch_id_q);
								$current_branch_name = isset($fetch_branch['name']) ? $fetch_branch['name'] : '';
								if ($fetch_branch['name'] == '') {
									$current_branch_name = 'Admin Branch';
								}
							?>
								<tr class="gradeA odd <?php if (isset($fetch1['assignment_type']) && $fetch1['assignment_type']=='Return') {echo 'return_type'; }?>" role="row">

									<td><?php echo $sr; ?></td>
									<td class="sorting_1"><?php echo $fetch1['assignment_no']; ?></td>
									<td class="sorting_1"><?php echo $current_branch_name; ?></td>
									<td class="sorting_1"><?php echo $created_by; ?></td>
									<td class="sorting_1"><?php echo $fetch1['assignment_type']; ?></td>
									<td class="sorting_1"><?php echo $rider_name; ?></td>
									<td class="sorting_1"><?php echo date(DATE_FORMAT, strtotime($fetch1['created_on'])); ?></td>


									<td class="center action_btns">
										<a title="Edit assignment" href="edit_assignment.php?assignment_no=<?php echo $fetch1['assignment_no']; ?>&type=<?php echo $fetch1['assignment_type']; ?>" class="btn btn-success"> <i class="fa fa-edit"></i></a>
										<?php if ($fetch1['assignment_type'] == 'Pickup') { ?>
											<a target="_blank" title="view assignment" href="pickup_assignment_sheet.php?assignment_no=<?php echo $fetch1['assignment_no']; ?>" class="btn btn-info"> <i class="fa fa-print"></i></a>
										<?php } elseif ($fetch1['assignment_type'] == 'Delivery') { ?>
											<a target="_blank" title="view assignment" href="delivery_assignment_sheet.php?assignment_no=<?php echo $fetch1['assignment_no']; ?>" class="btn btn-info"> <i class="fa fa-print"></i></a>
										<?php } elseif ($fetch1['assignment_type'] == 'Return') { ?>
											<a target="_blank" title="view assignment" href="return_assignment_sheet.php?assignment_no=<?php echo $fetch1['assignment_no']; ?>" class="btn btn-info"> <i class="fa fa-print"></i></a>
										<?php } ?>
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