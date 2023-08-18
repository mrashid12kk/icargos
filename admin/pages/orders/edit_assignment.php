<style type="text/css">
.zones_main {
    margin-bottom: 20px;
}

.panel-default>.panel-heading {
    color: #333 !important;
    background-color: #f5f5f5 !important;
    border-color: #ddd !important;

}

.panel-default>.panel-heading a {
    font-weight: bold !important;
}
</style>

<?php


if (isset($_GET['assignment_no']) && !empty($_GET['assignment_no'])) {
	$assignment_no = $_GET['assignment_no'];
	$result = mysqli_query($con, "select * from assignments where assignment_no='" . $assignment_no . "' ");
	$result_fetch = mysqli_fetch_array($result);
}
$date = $result_fetch['created_on'];
$assignment_no = $result_fetch['assignment_no'];
$barcode_image = $result_fetch['barcode_image'];
$destination_name = $result_fetch['destination'];
$rider_q = mysqli_query($con, "SELECT id,Name FROM users WHERE id='" . $result_fetch['rider_id'] . "' ");
$rider_res = mysqli_fetch_array($rider_q);
$rider_id = $rider_res['id'];
$rider_name = $rider_res['Name'];
$type = isset($_GET['type']) ? $_GET['type'] : '';
if ($type && $type == 'Delivery') {
	$assignment_query =  "delivery_assignment_no='" . $assignment_no . "'";
} else {
	$assignment_query =  "assignment_no='" . $assignment_no . "'";
}

$order_query = mysqli_query($con, "SELECT barcode_image, track_no,rname,sname,status,sender_address,receiver_address,customer_id,rphone,destination,origin,weight,quantity,collection_amount FROM orders WHERE $assignment_query  ");
?>
<div class="panel panel-default">

    <div class="panel-heading">Edit Assignment Sheet </div>

    <div class="panel-body" id="same_form_layout">
        <div id="messageDiv"></div>
        <?php
		if (isset($_SESSION['return_msg']) && !empty($_SESSION['return_msg'])) {
			$msg = $_SESSION['return_msg'];
			echo $msg;
			unset($_SESSION['return_msg']);
		}
		?>
        <div class="col-sm-12">
            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

                <div class="col-sm-1 left_right_none upate_Btn">
                    <a href="#" class="unassign_parcels btn btn-success" style="margin-top: 7px;">UnAssign</a>
                    <input type="hidden" id="print_data">
                    <input type="hidden" id="type" value="<?php echo $_GET['type'] ?>">
                </div>

                <div class="row">

                    <div class="col-sm-12 table-responsive gap-none">

                        <div class="panel-group" id="faqAccordion">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label><input type="checkbox" class="select_all" name="">
                                        <?php echo getLange('selectallorder'); ?></label>
                                </div>
                            </div>
                            <div class="panel panel-default ">

                                <!-- <div  class="panel-body" style="height: 0px;"> -->
                                <div class="panel-body">
                                    <table cellpadding="0" cellspacing="0" border="0"
                                        class="table table-striped table-bordered  no-footer assognment_table"
                                        role="grid" aria-describedby="basic-datatable_info">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" name="" class="main_select"></th>
                                                <th><?php echo getLange('tracking'); ?></th>
                                                <th><?php echo getLange('sender'); ?> </th>
                                                <th><?php echo getLange('receiver'); ?> </th>
                                                <th><?php echo getLange('origin'); ?></th>
                                                <th><?php echo getLange('destination'); ?></th>
                                                <th><?php echo getLange('status'); ?></th>
                                                <th><?php echo getLange('pickupaddress'); ?> </th>
                                                <th style="width: 220px;"> <?php echo getLange('deliveryaddress'); ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($order_row = mysqli_fetch_array($order_query)) { ?>
                                            <tr>
                                                <td><input type="checkbox" class="order_check"
                                                        data-id="<?php echo $order_row['track_no']; ?>" name=""></td>
                                                <td><?php echo $order_row['track_no']; ?></td>
                                                <td><?php echo $order_row['sname']; ?></td>
                                                <td><?php echo $order_row['rname']; ?></td>
                                                <td><?php echo $order_row['origin']; ?></td>
                                                <td><?php echo $order_row['destination']; ?></td>
                                                <td><?php echo getKeyWord($order_row['status']); ?></td>
                                                <td><?php echo $order_row['sender_address']; ?></td>
                                                <td style="width: 220px;"><?php echo $order_row['receiver_address']; ?>
                                                </td>

                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- </div> -->
                            </div>

                        </div>
                        <!--/panel-group-->
                    </div>


                </div>

            </div>

        </div>
    </div>

</div>