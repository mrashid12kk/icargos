<?php
    $customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
    $filter_query = "";
    $active_tracking = "";
    $active_customer_name = "";
    $active_customer_phone = "";
    $active_customer_email = "";
    $active_customer_id = "";
    $pickup_rider = "";
    $delivery_rider = "";
    $active_order_status = "";
    $active_order_city = "";
    $branch_query = '';
    if(isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id']))
    {
        $branch_query = " AND booking_branch = ".$_SESSION['branch_id']." OR current_branch = ".$_SESSION['branch_id'];
    }else if($_SESSION['branch_id']=='')
    {
        $branch_query = " AND booking_branch = 1 OR booking_branch = 1  OR current_branch = 1 OR booking_branch = 1 ";
    }

    if(isset($_POST['submit'])){
        if(isset($_POST['tracking_no']) && !empty($_POST['tracking_no'])){
            $query1 = mysqli_query($con,"SELECT * FROM orders WHERE track_no = '".$_POST['tracking_no']."' ");
            $active_tracking = $_POST['tracking_no'];
        }else{
            if(isset($_POST['customer_name']) && !empty($_POST['customer_name'])){
            $filter_query .= " AND sname = '".$_POST['customer_name']."' ";
            $active_customer_name = $_POST['customer_name'];
            }
            if(isset($_POST['customer_phone']) && !empty($_POST['customer_phone'])){
            $filter_query .= " AND sphone = '".$_POST['customer_phone']."' ";
            $active_customer_phone = $_POST['customer_phone'];
            }
            if(isset($_POST['customer_email']) && !empty($_POST['customer_email'])){
            $filter_query .= " AND semail = '".$_POST['customer_email']."' ";
            $active_customer_email = $_POST['customer_email'];
            }
            if(isset($_POST['active_customer']) && !empty($_POST['active_customer'])){
            $filter_query .= " AND customer_id = '".$_POST['active_customer']."' ";
            $active_customer_id = $_POST['active_customer'];
            }
            if(isset($_POST['pickup_rider']) && !empty($_POST['pickup_rider'])){
            $filter_query .= " AND pickup_rider = '".$_POST['pickup_rider']."' ";
            $pickup_rider = $_POST['pickup_rider'];
            }
            if(isset($_POST['delivery_rider']) && !empty($_POST['delivery_rider'])){
            $filter_query .= " AND delivery_rider = '".$_POST['delivery_rider']."' ";
            $delivery_rider = $_POST['delivery_rider'];
            }
            if(isset($_POST['order_status']) && !empty($_POST['order_status'])){
            $filter_query .= " AND status = '".$_POST['order_status']."' ";
            $active_order_status = $_POST['order_status'];
            }
            if(isset($_POST['order_city']) && !empty($_POST['order_city'])){
            $filter_query .= " AND destination = '".$_POST['order_city']."' ";
            $active_order_city = $_POST['order_city'];
            }
            // $branch_query = " booking_branch = ".$_SESSION['branch_id']." OR current_branch = ".$_SESSION['branch_id'];
            $from = date('Y-m-d',strtotime($_POST['from']));
            $to = date('Y-m-d',strtotime($_POST['to']));
            if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
            $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."' $filter_query  AND ( origin IN ($all_allowed_origins) OR current_branch = ".$_SESSION['branch_id']." ) order by id desc ");
            }else{
            $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query  AND ( origin IN ($all_allowed_origins) OR current_branch = 1 OR booking_branch = 1 ) order by id desc ");
            }
        }

    }
    elseif(isset($_GET['order_status']) && !empty($_GET['order_status'])){
    // $branch_query = " booking_branch = ".$_SESSION['branch_id']." OR current_branch = ".$_SESSION['branch_id'];
    $filter_query .= " AND status = '".$_GET['order_status']."' ";
    $active_order_status = $_GET['order_status'];
    $from = date('Y-m-d',strtotime($_GET['from']));
    $to = date('Y-m-d',strtotime($_GET['to']));
    if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])) {
    $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query AND (origin IN ($all_allowed_origins) OR current_branch = ".$_SESSION['branch_id']." ) order by id desc ");
    }else{
    $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query AND (origin IN ($all_allowed_origins) OR current_branch = 1 OR booking_branch = 1 ) order by id desc ");
    }
    }
    else{
    // $branch_query = " booking_branch = ".$_SESSION['branch_id']." OR current_branch = ".$_SESSION['branch_id'];
    $from = date('Y-m-d', strtotime('today - 30 days'));
    $to = date('Y-m-d');
        if (isset($_SESSION['branch_id']) AND !empty($_SESSION['branch_id'])) {
        $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query AND (origin IN ($all_allowed_origins) OR current_branch = ".$_SESSION['branch_id']." ) order by id desc");
        }else{
        $query1 = mysqli_query($con,"SELECT * FROM orders WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '".$to."'   $filter_query AND (origin IN ($all_allowed_origins) OR current_branch = 1 OR booking_branch = 1 ) order by id desc");
        }
    }
?>
<?php
if(isset($message) && !empty($message)){
echo $message;
}
$courier_query=mysqli_query($con,"Select * from users where type='driver'");
$delivery_courier_query=mysqli_query($con,"Select * from users where type='driver'");
$status_query=mysqli_query($con,"Select * from order_status where active='1'");
$city_query=mysqli_query($con,"Select * from cities where 1");
$branch_query=mysqli_query($con,"Select * from branches where 1");
$currency = mysqli_fetch_array(mysqli_query($con,"SELECT value FROM config WHERE `name`='currency' "));
$status_data_fr_dl = mysqli_fetch_array(mysqli_query($con,"Select * from order_status where sts_id=1  "));
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
<?php
function getBranchNameById($id)
{
global $con;
$id = isset($id) ? $id : 1;
$branchQ = mysqli_query($con, "SELECT name from branches where id = $id");
$res = mysqli_fetch_array($branchQ);
return $res['name'];
}
function encrypt($string) {
$key="usmannnn";
$result = '';
for($i=0; $i<strlen($string); $i++) {
$char = substr($string, $i, 1);
$keychar = substr($key, ($i % strlen($key))-1, 1);
$char = chr(ord($char)+ord($keychar));
$result.=$char;
}
return base64_encode($result);
}
?>
<?php
if(isset($_GET['message']) && !empty($_GET['message'])){
echo $_GET['message'];
}
?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo getLange('orders'); ?></div>
    <div class="panel-body" id="same_form_layout">
        <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
            <div class="row">
                <div class="col-sm-12 table-responsive gap-none">
                    <form method="POST" action="">
                        <div class="row" >
                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('trackingno'); ?> </label>
                                    <input type="text" value="<?php echo $active_tracking; ?>" class="form-control " name="tracking_no">
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('pickupname'); ?> </label>
                                    <input type="text" value="<?php echo $active_customer_name; ?>" class="form-control " name="customer_name">
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('pickupphone'); ?></label>
                                    <input type="text" value="<?php echo $active_customer_phone; ?>" class="form-control " name="customer_phone">
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('pickupphone'); ?> </label>
                                    <input type="text" value="<?php echo $active_customer_email; ?>" class="form-control " name="customer_email">
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
                            <div class="col-sm-2" >
                                <div class="form-group">
                                    <label><?php echo getLange('customer'); ?></label>
                                    <select class="form-control active_customer_detail js-example-basic-single" name="active_customer">
                                        <option value="">All Customers</option>
                                        <?php foreach($customers as $customer){ ?>
                                        <option  <?php if($customer['id'] == $active_customer_id ){ echo "selected"; } ?> value="<?php echo $customer['id']; ?>"><?php echo $customer['fname'].(($customer['bname'] != '') ? ' ('.$customer['bname'].')' : ''); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none" >
                                <div class="form-group">
                                    <label><?php echo getLange('pickuprider'); ?> </label>
                                    <select class="form-control courier_list js-example-basic-single" name="pickup_rider">
                                        <option selected value="">Select Rider</option>
                                        <?php while($row=mysqli_fetch_array($courier_query)){ ?>
                                        <option <?php if($row['id'] == $pickup_rider ){ echo "selected"; } ?> value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none" >
                                <div class="form-group">
                                    <label><?php echo getLange('deliveryrider'); ?> </label>
                                    <select class="form-control courier_list js-example-basic-single" name="delivery_rider">
                                        <option selected value="">Select Rider</option>
                                        <?php while($row=mysqli_fetch_array($delivery_courier_query)){ ?>
                                        <option <?php if($row['id'] == $delivery_rider ){ echo "selected"; } ?> value="<?php echo $row['id']; ?>"><?php echo $row['Name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none" >
                                <div class="form-group">
                                    <label><?php echo getLange('orderstatus'); ?> </label>
                                    <select class="form-control courier_list js-example-basic-single" name="order_status">
                                        <option selected value="">Select status</option>
                                        <?php while($row=mysqli_fetch_array($status_query)){ ?>
                                        <option <?php if($row['status'] == $active_order_status ){ echo "selected"; } ?> value="<?php echo $row['status']; ?>"><?php echo getKeyWord($row['status']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 left_right_none" >
                                <div class="form-group">
                                    <label><?php echo getLange('city'); ?></label>
                                    <select class="form-control courier_list js-example-basic-single" name="order_city">
                                        <option selected value="">Select City</option>
                                        <?php while($row=mysqli_fetch_array($city_query)){ ?>
                                        <option  <?php if($row['id'] == $active_order_city ){ echo "selected"; } ?> value="<?php echo $row['city_name']; ?>"><?php echo $row['city_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1 sidegapp-submit " style="margin-top: 0;">
                                <input type="submit"  name="submit" class="btn btn-success" value="<?php echo getLange('search'); ?>">
                            </div>
                        </div>
                    </form>
                    <table class="table table-striped table-bordered dataTable_with_sorting no-footer" id="basic-datatable" >
                        <thead>
                            <tr role="row">
                                <th><?php echo getLange('srno'); ?></th>
                                <th><?php echo getLange('trackingno'); ?> </th>
                                <th><?php echo getLange('orderid'); ?> </th>
                                <th><?php echo getLange('branch'); ?></th>
                                <th><?php echo getLange('orderdate'); ?> </th>
                                <th><?php echo getLange('ordertime'); ?> </th>
                                <th><?php echo getLange('ref'); ?></th>
                                <th><?php echo getLange('api'); ?></th>
                                <th><?php echo getLange('api').' '.getLange('trackingno') ?></th>
                                <th><?php echo getLange('pickupdetail'); ?> </th>
                                <th><?php echo getLange('deliverydetail'); ?> </th>
                                <th><?php echo getLange('shipmentdetail'); ?> </th>
                                <th><?php echo getLange('payment'); ?></th>
                                <th><?php echo getLange('pickup'); ?></th>
                                <th><?php echo getLange('ordertype'); ?> </th>
                                <th><?php echo getLange('status'); ?></th>
                                <th><?php echo getLange('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sr=1;
                            while($fetch1=mysqli_fetch_array($query1)){
                            $iddd=encrypt($fetch1['id']."-usUSMAN767###");
                            ?>
                            <tr class="gradeA odd" role="row">
                                <td><?php echo $sr; ?></td>
                                <td class="sorting_1"><?php echo $fetch1['track_no']; ?></td>
                                <td><?php echo $fetch1['product_id']; ?></td>
                                <td><?php echo getBranchNameById($fetch1['current_branch']); ?></td>
                                <td class="sorting_1"><?php echo date(DATE_FORMAT,strtotime($fetch1['order_date'])); ?></td>
                                <td><?php echo date('h:i A',strtotime($fetch1['order_time'])); ?></td>
                                <td><?php echo $fetch1['ref_no']; ?></td>

                                <td><?php  if($fetch1['api_posted'] == '0'){
							            echo ''	;}else{
							              echo  $fetch1['api_posted'];
							            }
								?></td>
								<td><?php echo $fetch1['api_tracking_no']; ?></td>
                                <td class="center">
                                    <b><?php echo getLange('pickupcity'); ?> :</b> <?php echo $fetch1['origin']; ?><br>
                                    <b><?php echo getLange('accountname'); ?> :</b> <?php echo $fetch1['sname']; ?><br>
                                    <b><?php echo getLange('businessname'); ?> :</b> <?php echo $fetch1['sbname']; ?><br>
                                    <b><?php echo getLange('phone'); ?>:</b> <?php echo $fetch1['sphone']; ?><br>
                                    <b><?php echo getLange('email'); ?>:</b> <?php echo $fetch1['semail']; ?><br>
                                    <b><?php echo getLange('address'); ?>:</b> <?php echo $fetch1['sender_address']; ?><br>
                                </td>
                                <td class="center">
                                    <b><?php echo getLange('destination'); ?>:</b> <?php echo $fetch1['destination']; ?><br>
                                    <b><?php echo getLange('name'); ?>:</b> <?php echo $fetch1['rname']; ?><br>
                                    <b><?php echo getLange('phone'); ?>:</b> <?php echo $fetch1['rphone']; ?><br>
                                    <!-- <b>Email:</b> <?php echo $fetch1['remail']; ?><br> -->
                                    <b><?php echo getLange('address'); ?>:</b> <?php echo $fetch1['receiver_address']; ?><br>
                                </td>
                                <td>
                                    <b><?php echo getLange('parcelweight'); ?> :</b><?php echo $fetch1['weight']; ?> Kg</br>
                                    <b><?php echo getLange('itemdetail'); ?> :</b><?php echo $fetch1['product_desc']; ?></br>
                                    <b><?php echo getLange('specialinstruction'); ?> :</b><?php echo $fetch1['special_instruction']; ?></br>
                                </td>
                                <td>
                                    <b><?php echo getLange('deliveryfee'); ?> :</b><?php echo $currency['value']; ?> <?php echo number_format((float)$fetch1['price'],2); ?></br>
                                    <b><?php echo getLange('amount'); ?>:</b><?php echo $currency['value']; ?> <?php echo number_format((float)$fetch1['collection_amount'],2); ?></br>
                                </td>
                                <td>
                                    <b><?php echo getLange('pickuptime'); ?> :</b> <?php echo date('h:i A',strtotime($fetch1['pickup_time'])); ?></br>
                                    <b><?php echo getLange('pickuplocation'); ?> :</b> <?php echo $fetch1['Pick_location']; ?></br>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-primary" style="background: #bd12f5;">
                                        <?php
                                        if($fetch1['order_type_booking']==1){echo 'API';}
                                        else if($fetch1['order_type_booking']==2){echo 'Admin';}
                                        else if($fetch1['order_type_booking']==3){echo 'Bulk Booking';}
                                        else if($fetch1['order_type_booking']==4){echo 'Customer';}
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-primary" style="background: #39bcb5;">
                                        <?php echo $fetch1['status']; ?>
                                        <?php if (isset($fetch1['status_reason']) and !empty($fetch1['status_reason']) && $fetch1['status'] != 'Delivered'): ?>
                                        <?php echo "<br>( ".$fetch1['status_reason']." )" ?>
                                        <?php endif ?>
                                    </span>
                                </td>
                                <!-- <td><?php echo $delivery_zone_array['zone']; ?></td> -->
                                <td class="center action_btns" >
                                    <?php if($fetch1['status'] != 'cancelled' ){ ?>
                                    <a target="_blank" title="view order" href="editbookingform.php?id=<?php echo $fetch1['id']; ?>" class="btn btn-info"> <span class="glyphicon glyphicon-edit"></span></a>
                                    <?php }
                                    if($fetch1['status'] == 'New Booked' ){ ?>
                                    <a  href="cancel_order.php?cancel_id=<?php echo $iddd; ?>" class="btn-sm btn btn-danger cancel_order"><?php echo getLange('cancelorder'); ?> </a>
                                    <?php } ?>
                                    <a target="_blank" title="view order" href="order.php?id=<?php echo $fetch1['id']; ?>" class="btn btn-info"> <i class="fa fa-eye"></i></a>
                                    <!-- <a target="_blank" title="Edit order" href="edit_booking_form.php?id=<?php echo $fetch1['id']; ?>&customer_id=<?php echo $fetch1['customer_id']; ?>" class="btn btn-info"> <i class="fa fa-edit"></i></a> -->
                                    <?php if($fetch1['status'] != 'cancelled' ){ ?>
                                    <a  target="_blank" title="track order" href="<?php echo BASE_URL ?>track-details.php?track_code=<?php echo $fetch1['track_no'] ?>" class="track_order btn btn-success btn-sm track_order" class="btn btn-success"> <i class="fa fa-truck"></i> </a>
                                    <?php } ?>
                                    <?php if(!empty($fetch1['google_address'])){ ?>
                                    <a target="_blank" href="<?php echo $fetch1['google_address'] ?>" hidden> <i class="fa fa-map-marker"></i></a>
                                    <?php } ?>
                                </td>
                                <?php if (isset($status_data_fr_dl['status']) and $status_data_fr_dl['status'] == $fetch1['status']): ?>
                                <!-- <a  href="cancel_order.php?delete_id=<?php echo $iddd; ?>" class="btn-sm btn btn-danger delete_order"><i class="fa fa-trash"></i></a> -->
                                <?php else: ?>
                                <!--    <?php echo $status_data_fr_dl['status'] ?>
                                <?php echo $fetch1['status'] ?> -->
                                <?php endif; ?>
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
