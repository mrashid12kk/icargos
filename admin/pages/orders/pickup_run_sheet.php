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

#same_form_layout table tr th:last-child {
    width: 250px !important;
}
</style>

<?php

$branch_id = $_SESSION['branch_id'];
$creatd_by_query = '';
if (!isset($_SESSION['branch_id'])) {
	$branch_id = 1;
}
if (isset($_SESSION['branch_id'])) {
    $courier_query=mysqli_query($con,"SELECT * from users where user_role_id = 4 AND type='driver' AND branch_id=".$_SESSION['branch_id']);
    $creatd_by_query= mysqli_query($con, "SELECT * FROM users WHERE (type ='admin' OR type ='manager') AND branch_id = $branch_id");

}else{
$courier_query=mysqli_query($con,"SELECT * from users where type='driver' AND user_role_id = 4  AND (branch_id IS NULL OR branch_id=1)");
$creatd_by_query= mysqli_query($con, "SELECT * FROM users WHERE (type ='admin' OR type ='manager') AND (branch_id = 1 OR branch_id IS NULL) ");
}
$branch_query=mysqli_query($con,"Select * from branches where 1 ");
$zone_query=mysqli_query($con,"Select * from zone where 1");

$origin = '';
$zone_q = '';
$customer_q = '';
$origin_str = '';
$origin_arr = '';
if(isset($_GET['origin']) && !empty($_GET['origin'])){
	$origin_arr = $_GET['origin'];
	foreach($origin_arr as $r){
		$origin_str .= '"'.$r.'",';
	}
	$origin=rtrim($origin_str, ',');

	$zone_q = ' AND origin IN ('.$origin.') ';
	$customer_q =  ' AND o.origin IN ('.$origin.') ';
}
$current_branch_query='';
if (isset($_SESSION['branch_id']) AND !empty($_SESSION['branch_id'])) {
 $current_branch_query = " AND (o.origin IN ($all_allowed_origins) OR o.current_branch = ".$_SESSION['branch_id']." OR o.booking_branch =".$_SESSION['branch_id'].") ";
}else{

 $current_branch_query = " AND (o.origin IN ($all_allowed_origins) OR o.booking_branch = 1 OR o.current_branch = 1)";
}
// print_r($allowed_origins);
$origin_zone_q = mysqli_query($con," SELECT DISTINCT city_name FROM cities ");
$customer_fetch_q = mysqli_query($con,"SELECT  cus.id as customer_id,cus.fname as business,cus.bname as business_name FROM orders o INNER JOIN customers cus ON (o.customer_id = cus.id) WHERE  (o.status='New Booked'  OR o.status='Ready for Pickup') $current_branch_query $customer_q  GROUP BY cus.id ");


 ?>
<div class="panel panel-default">

    <div class="panel-heading"><?php echo getLange('pickuprunsheet'); ?> </div>

    <div class="panel-body" id="same_form_layout">

        <div class="col-sm-12">
            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                <form action="">
                    <div class="row orgin_box">
                        <div class="col-sm-4 left_right_none">
                            <div class="form-group">
                                <label><?php echo getLange('selectorigin'); ?> </label>
                                <select
                                    class="form-control courier_list js-example-basic-multiple js-example-basic-multiple"
                                    name="origin[]" multiple>
                                    <?php while($row=mysqli_fetch_array($origin_zone_q)){ ?>
                                    <option <?php if(in_array($row['city_name'], $origin_arr)){ echo "selected"; } ?>
                                        value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-1 left_right_none upate_Btn">
                            <input style="    margin-top: 7px;" type="submit" name="search" class=" btn btn-success"
                                value="<?php echo getLange('search'); ?>">
                        </div>
                    </div>
                </form>
                <form method="POST" action="bulk_pickup_assign.php" id="bulk_submit">

                    <div class="row orgin_box">

                        <div class="col-sm-3 left_right_none">
                            <div class="form-group">
                                <label><?php echo getLange('assignpickuprider'); ?> </label>
                                <select class="form-control courier_list" name="active_courier">
                                    <option selected disabled> <?php echo getLange('select').' '.getLange('rider'); ?>
                                    </option>
                                    <?php while($row=mysqli_fetch_array($courier_query)){ ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>


                        <input type="hidden" name="order_ids" id="print_data">
                        <div class="col-sm-1 left_right_none upate_Btn">
                            <a href="#" class="update_status btn btn-success"
                                style="margin-top: 7px;"><?php echo getLange('assign'); ?></a>

                        </div>

                </form>

                <div class="row">

                    <div class="col-sm-12 table-responsive gap-none">

                        <div class="panel-group" id="faqAccordion">
                            <div class="row">
                                <div class="col-sm-2">
                                    <label><input type="checkbox" class="select_all" name="">
                                        <?php echo getLange('selectallorder'); ?></label>
                                </div>
                            </div>
                            <?php
		    	$row_start = 0;
		    	while($single = mysqli_fetch_array($customer_fetch_q))
		    	{
		    		$customer_id = $single['customer_id'];

		    		$current_branch_query='';
					if (isset($_SESSION['branch_id']) AND !empty($_SESSION['branch_id'])) {
					 $current_branch_query = " AND (origin IN ($all_allowed_origins) OR current_branch = ".$_SESSION['branch_id']." OR booking_branch =".$_SESSION['branch_id'].") ";
					}else{

					 $current_branch_query = " AND (origin IN ($all_allowed_origins) OR booking_branch = 1 OR current_branch = 1)";
					}


		    		$order_query = mysqli_query($con,"SELECT id,sname,sender_address,track_no,sphone,status,origin FROM orders WHERE customer_id='".$customer_id."' $current_branch_query AND (status='New Booked'  OR status='Ready for Pickup') $zone_q ORDER BY id DESC ");


		    	 ?>
                            <div class="panel panel-default ">

                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="#"
                                            class="ing"><?php echo $single['business'].' ('.$single['business_name'].')'; ?></a>
                                    </h4>

                                </div>
                                <!-- <div  class="panel-body" style="height: 0px;"> -->
                                <div class="panel-body">
                                    <table cellpadding="0" cellspacing="0" border="0"
                                        class="table table-striped table-bordered dataTable no-footer pickup_tbl"
                                        id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">
                                        <thead>
                                            <tr>
                                                <th style="width: 20px;"><input type="checkbox" name=""
                                                        class="main_select"></th>
                                                <th style="width: 100px;"><?php echo getLange('tracking'); ?></th>
                                                <th style="width: 100px;"><?php echo getLange('vendername'); ?> </th>
                                                <th style="width: 100px;"><?php echo getLange('pickupphone'); ?> </th>
                                                <th style="width: 100px;"><?php echo getLange('origin'); ?></th>
                                                <th style="width: 100px;"><?php echo getLange('status'); ?></th>
                                                <th style="width: 265px !important;text-align: left !important;">
                                                    <?php echo getLange('pickupaddress'); ?> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($order_row = mysqli_fetch_array($order_query)){ ?>
                                            <tr>
                                                <td><input type="checkbox" class="order_check"
                                                        data-id="<?php echo $order_row['track_no']; ?>" name=""></td>
                                                <td><?php echo $order_row['track_no']; ?></td>
                                                <td><?php echo $order_row['sname']; ?></td>
                                                <td><?php echo $order_row['sphone']; ?></td>
                                                <td><?php echo $order_row['origin']; ?></td>
                                                <td><?php echo getKeyWord($order_row['status']); ?></td>
                                                <td style="text-align: left !important;">
                                                    <?php echo $order_row['sender_address']; ?></td>

                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- </div> -->
                            </div>
                            <?php $row_start++; } ?>

                        </div>
                        <!--/panel-group-->
                    </div>


                </div>

            </div>

        </div>
    </div>

</div>