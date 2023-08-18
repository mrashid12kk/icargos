<?php
session_start();
include_once 'includes/conn.php';
include_once 'includes/role_helper.php';
include_once 'includes/custom_functions.php';
if(isset($_POST['action'])&&$_POST['action']=='email'){
    $email=mysqli_real_escape_string($con,$_POST['email']);
    $query=mysqli_query($con,"SELECT * FROM users where email='$email'");
    $rowcount=mysqli_affected_rows($con);
    if($rowcount>0){
        echo "<ul class='list-unstyled'><li>Email Already exist.</li></ul>";
    }
    else{
        echo "";
    }
}
if(isset($_POST['delivered_status'])&& !empty($_POST['delivered_status'])){
    $response_array= array();
    $result['table'] = '';
    if (isset($_POST['some_cn_no']) && !empty($_POST['some_cn_no'])) {
        $sr_no=1;
        $response_array['table'] .= "<table class='table table-striped'><thead><tr><th>Sr.#</th><th>Rider Name</th><th>Amount</th></tr></thead><tbody id='collection_table'>";
        $order_no_array  ='';
        foreach ($_POST['some_cn_no'] as $key => $value) {
            $trimvalue = trim($value);
            $orderno = "'".$trimvalue."'";
            $order_no_array .= $trimvalue.',';
        }
        $order_no_array = rtrim($order_no_array, ',');
        $sr_no=1;
        $sql = "SELECT SUM( orders.collection_amount) as collection_amount, orders.delivery_rider, orders.track_no, orders.assignment_no, users.Name as rider_name  FROM orders Join users on orders.delivery_rider = users.id where orders.status = 'Delivered' AND orders.rider_collection = 1 AND orders.admin_collection=0 AND orders.track_no IN ($order_no_array) GROUP BY orders.delivery_rider";
               // echo $sql;
               // die;
        $query= mysqli_query($con, $sql);
        $button = false;
        while($result = mysqli_fetch_assoc($query)){
            $button = true;
            $response_array['table'] .= "<tr><td>".$sr_no++."</td><td>".$result['rider_name']."</td><td>".$result['collection_amount']."</td></tr>";
        }
        if ($button ==true) {
            $response_array['table'] .="<tr><td><input type='hidden' name='order_ids' value=".$order_no_array."><button class='save_manifest' type='submit' name='collect' >Collect</button></td></tr>";
        }else{
            $response_array['table'] .="<tr><td colspan='5'>No orders available to collect</td></tr>";
        }
        $response_array['table'] .= "</tbody></table>";
    }
    echo json_encode($response_array);
}
    // if(isset($_POST['update_credit'])&& !empty($_POST['update_credit'])){
    //        foreach ($_POST['riders_ids'] as $key => $value) {
    //           $rider_id = $value;
    //           $riders_names = $_POST['riders_names'][$key];
    //           $riders_collections = $_POST['riders_collections'][$key];
    //           $all_cn_no = $_POST['all_cn_no'][$key];
    //           $assignment_no = $_POST['all_assignment_nos'][$key];
    //             $debitamount = 0;
    //             $rider_b = "SELECT * FROM rider_wallet_ballance where rider_id=".$rider_id;
    //             $rider_res= mysqli_query($con,$rider_b);
    //             $rider_prev_balance_q = mysqli_fetch_array($rider_res);
    //             $rider_prev_balance = $rider_prev_balance_q['balance'];
    //             $newBalance = $rider_prev_balance - $riders_collections;
    //             $check_q = "SELECT * FROM rider_wallet_ballance where rider_id =".$rider_id;
    //             $check_res = mysqli_query($con,$check_q);
    //             $check_rider_exists  = mysqli_fetch_array($check_res);
    //             // if (isset($rider_id) && !empty($rider_id)) {
    //             //     $query = "UPDATE  rider_wallet_ballance set balance = ".$newBalance.", update_date = '".date('Y-m-d H:i:s')."' WHERE rider_id =  ".$rider_id;
    //             //     $cod_q = mysqli_query($con, $query);
    //             //     $master_id = $rider_prev_balance_q['id'];
    //             // }
    //             if (isset($check_rider_exists['rider_id']) && !empty($check_rider_exists['rider_id'])) {
    //                 $query = "UPDATE  rider_wallet_ballance set balance = ".$newBalance.", update_date = '".date('Y-m-d H:i:s')."' WHERE rider_id =  ".$rider_id;
    //                 $cod_q = mysqli_query($con, $query);
    //                 $master_id = $rider_prev_balance_q['id'];
    //             }else{
    //                 $query2 = "INSERT INTO `rider_wallet_ballance`(`rider_id`, `rider_name`, `balance`, `update_date`) VALUES (".$rider_id." , '".$rider_name."' , ".$newBalance." , '".date('Y-m-d H:i:s')."'  )";
    //                 $cod_q = mysqli_query($con, $query2);
    //                 $master_id = mysqli_insert_id($con);
    //             }
    //                 $querys = "INSERT INTO `rider_wallet_ballance_log`(`order_id`, `order_no`, `rider_id`, `rider_name`, `debit`, `credit`, `date`)VALUES (".$master_id." , ".$all_cn_no."  , ".$rider_id." , '".$riders_names."' , '$debitamount' , '".$riders_collections."' , '".date('Y-m-d H:i:s')."') ";
    //                 $log_q = mysqli_query($con, $querys);
    //                 $q = mysqli_query($con, "UPDATE orders SET status ='Deliverred'  WHERE track_no = $all_cn_no");
    //                 $check_rider = mysqli_query($con,"SELECT * FROM assignments WHERE rider_id = ".$rider_id."  and assignment_no='".$assignment_no."' ");
    //     }
    // }
if(isset($_POST['action'])&&$_POST['action']=='assign'){
    $query=mysqli_query($con,"SELECT * FROM users WHERE type='driver' and status='complete'");
    $rowcount=mysqli_affected_rows($con);
    if($rowcount>0){
        while($fetch=mysqli_fetch_array($query)){
            echo "<option value='".$fetch['id']."'>".$fetch['Name']."</option>";
        }
    }
    else{
        echo "<option>Drivers are not available yet.</option>";
    }
}
if(isset($_POST['mode_id'])&& !empty($_POST['mode_id'])){
    $query=mysqli_query($con,"SELECT * FROM `transport_company` WHERE mode_id =".$_POST['mode_id']);
    $rowcount=mysqli_affected_rows($con);
    if($rowcount>0){
        while($fetch=mysqli_fetch_array($query)){
            echo "<option value='".$fetch['id']."'>".$fetch['name']."</option>";
        }
    }
    else{
        echo "<option>No company available for this mode.</option>";
    }
}
if(isset($_POST['bulk_edit'])&& !empty($_POST['bulk_edit'])){
    include '../price_calculation.php';
    $gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
    $total_gst = mysqli_fetch_array($gst_query);
    $fuel_query = mysqli_query($con,"SELECT * FROM charges WHERE `charge_name`='Fuel Surcharge' ");
    $fuel_value_result = mysqli_fetch_array($fuel_query);
    $feul_value = $fuel_value_result['charge_value'];
    $charge_type = $fuel_value_result['charge_type'];
    $bulk_value = $_POST['bulk_value'];
    $weight = (float)$_POST['bulk_edit'];
    foreach ($bulk_value as $key => $value) {
        $track_no = $value;
        $customer_id = $_POST['customer_id'][$key];
        $order_type = $_POST['order_type'][$key];
        $origin = $_POST['origin'][$key];
        $destination = $_POST['destination'][$key];
        $total_charges = $_POST['total_charges'][$key];
        $extra_charges = $_POST['extra_charges'][$key];
        $insured_premium = $_POST['insured_premium'][$key];
        $delivery_charges = delivery_calculation($origin,$destination,$weight,$customer_id,$order_type);
        $excl_amount= $insured_premium + $delivery_charges + $total_charges + $extra_charges;
        $gst = $total_gst['value'];
        $pft_amount = ($excl_amount/100)*$gst;
        $grandTotalCharges = $excl_amount + $pft_amount;
        $query=mysqli_query($con,"UPDATE orders SET weight = '".$weight."', price = '".$delivery_charges."', pft_amount='".$pft_amount."', grand_total_charges='".$grandTotalCharges."' WHERE track_no =  '".$track_no."'");
    }
    if (mysqli_affected_rows($con) > 0) {
     echo $_POST['bulk_edit'];
     exit;
 }
}
if(isset($_POST['single_weight'])&& !empty($_POST['single_weight'])){
    $customer_id = $_POST['customer_id'];
    $weight = (float)$_POST['single_weight'];
    $order_type = $_POST['order_type'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $total_charges = $_POST['total_charges'];
    $extra_charges = $_POST['extra_charges'];
    $insured_premium = $_POST['insured_premium'];
    $gst_query = mysqli_query($con,"SELECT value FROM config WHERE `name`='gst' ");
    $total_gst = mysqli_fetch_array($gst_query);
    include '../price_calculation.php';
    $delivery_charges = delivery_calculation($origin,$destination,$weight,$customer_id,$order_type);
    $excl_amount= $insured_premium + $delivery_charges + $total_charges + $extra_charges;
    $gst = $total_gst['value'];
    $pft_amount = ($excl_amount/100)*$gst;
    $grandTotalCharges = $excl_amount + $pft_amount;
    $query=mysqli_query($con,"UPDATE orders SET weight = '".$_POST['single_weight']."', price = '".$delivery_charges."', pft_amount='".$pft_amount."', grand_total_charges='".$grandTotalCharges."' WHERE track_no =  '".$_POST['track_no']."'");
    if (mysqli_affected_rows($con) > 0) {
     echo $_POST['single_weight'];
     exit;
 }
}
if(isset($_POST['receiver_person'])&& !empty($_POST['receiver_person'])){
    if (isset($_SESSION['branch_id']) && $_SESSION['branch_id'] !='') {
        $query=mysqli_query($con,"SELECT * FROM `users` WHERE type != 'driver' AND branch_id =  ".$_SESSION['branch_id']);
    }else{
        $query=mysqli_query($con,"SELECT * FROM `users` WHERE type != 'driver' AND branch_id IS NULL");
    }
    $rowcount=mysqli_affected_rows($con);
    if($rowcount>0){
        while($fetch=mysqli_fetch_array($query)){
            echo "<option value='".$fetch['id']."'>".$fetch['Name']."</option>";
        }
    }
    else{
        echo "<option value=''>No record found.</option>";
    }
}
if(isset($_POST['enter_cn'])&& !empty($_POST['enter_cn'])){
    $query=mysqli_query($con,"SELECT * FROM `orders` WHERE track_no = '".$_POST['enter_cn']."'");
    $sr_no = isset($_POST['length'] ) ? $_POST['length'] : 0;
    while($fetch=mysqli_fetch_array($query)){
        $service_type='';
        if (isset($fetch['order_type']) && $fetch['order_type']!='') {
            $service_type_q=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM services WHERE id=".$fetch['order_type']));
            $service_type=$service_type_q['service_type'];
        }
        $bussiness_name='';
        if (isset($fetch['customer_id']) && $fetch['customer_id']!='') {
            $customer_q=mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM customers WHERE id=".$fetch['customer_id']));
            $bussiness_name=$customer_q['bname'];
        }
        echo "<tr>";
        echo "<td>".$fetch['track_no']." <input type='hidden' name='all_cn_no[]' class='all_cn_no' value='".$fetch['track_no']."' /> <input type='hidden' name='customer_id[]' class='customer_id' value='".$fetch['customer_id']."' /><input type='hidden' name='origin[]' class='origin' value='".$fetch['origin']."' /><input type='hidden' name='destination[]' class='destination' value='".$fetch['destination']."' /><input type='hidden' name='order_type[]' class='order_type' value='".$fetch['order_type']."' /><input type='hidden' name='total_charges[]' class='total_charges' value='".$fetch['net_amount']."' /><input type='hidden' name='extra_charges[]' class='extra_charges' value='".$fetch['extra_charges']."' /><input type='hidden' name='insured_premium[]' class='insured_premium' value='".$fetch['insured_premium']."' /></td>";
        echo "<td>".$service_type."</td>";
        echo "<td>".$bussiness_name."</td>";
        echo "<td>".$fetch['origin']."</td>";
        echo "<td>".$fetch['rname']."</td>";
        echo "<td>".$fetch['destination']."</td>";
        echo "<td>".$fetch['status']."</td>";
        echo "<td>".$fetch['quantity']."<input type='hidden' class='hidden_qunatity_value' value=".$fetch['quantity']. " /></td>";
        echo "<td class='single_weight'>".$fetch['weight']."<input type='hidden' class='hidden_weight' value=".$fetch['weight']. " /></td>";
                // echo "<td></td>";
        if (isset($_POST['update_cn_no']) && $_POST['update_cn_no']==1) {
           echo "<td>";
           echo '<a data-wt="'.$fetch["weight"].'" data-qt="'.$fetch["quantity"].'"  style="cursor:pointer" title="Edit" class="edit_row"><i class="fa fa-edit "></i></a>';
           echo '<a data-wt="'.$fetch["weight"].'" data-qt="'.$fetch["quantity"].'"  style="cursor:pointer" title="Trash" class="delete_row"><i class="fa fa-trash "></i></a>';
           echo '</td>';
       }else{
        echo "<td>";
        echo '<a data-wt="'.$fetch["weight"].'" data-qt="'.$fetch["quantity"].'"  style="cursor:pointer" title="Edit" class="edit_row"><i class="fa fa-edit "></i></a>';
        echo '<a data-wt="'.$fetch["weight"].'" data-qt="'.$fetch["quantity"].'"  style="cursor:pointer" title="Trash" class="delete_row"><i class="fa fa-trash "></i></a>';
        echo '</td>';
    }
    echo '</tr>';
}
}
if(isset($_POST['pick_update_cn'])&& !empty($_POST['pick_update_cn'])){
        // echo "<pre>";
        // print_r($_POST);
        // die();
    $field_name = isset($_POST['field_name']) ? $_POST['field_name'] : '';
    $where = '';
    if (isset($_POST['allowed_statuses']) && !empty($_POST['allowed_statuses'])) {
        $allowed_statuses = $_POST['allowed_statuses'];
        $where .=" AND ( ";
        foreach ($allowed_statuses as $key => $value) {
            $where .= " orders.status = '".$value."' OR";
        }
        $where = rtrim($where , "OR");
        $where.= ")";
    }
    $orgin_q = '';
    if (isset($_POST['origin']) && !empty($_POST['origin'])) {
        $origin = $_POST['origin'];
        $orgin_q = " AND orders.origin = '".$_POST['origin']."' ";
    }
    $dest_q = '';
    if (isset($_POST['destination']) && !empty($_POST['destination'])) {
        $origin = $_POST['origin'];
        $dest_q = " AND orders.destination = '".$_POST['destination']."' ";
    }
    $custom_field_query = "";
    if (isset($_POST['field_name']) && $_POST['field_name'] == 'sheet_no') {
        $custom_field_query = " AND (orders.assignment_no = ".$_POST['custom_field'].")  ";
    }
    if (isset($_POST['field_name']) && $_POST['field_name'] == 'run_sheet_no') {
        $custom_field_query = " AND (orders.assignment_no = ".$_POST['custom_field'].")  ";
    }
    if (isset($_POST['field_name']) && $_POST['field_name'] == 'delivery_sheet_no') {
        $custom_field_query = " AND (orders.delivery_assignment_no = ".$_POST['custom_field'].")  ";
    }
    if (isset($_POST['field_name']) && $_POST['field_name'] == 'return_sheet_no') {
        $custom_field_query = " AND (orders.return_assignment_no = ".$_POST['custom_field'].")  ";
    }
    if (isset($_POST['field_name']) && $_POST['field_name'] == 'manifest_no') {
        $custom_field_query = " AND (manifest_detail.manifest_no = ".$_POST['custom_field'].")  ";
    }
    if (isset($_POST['field_name']) && $_POST['field_name'] == 'demanifest_no') {
        $custom_field_query = " AND (demanifest_detail.demanifest_no = ".$_POST['custom_field'].")  ";
    }
    $sql = "SELECT orders.origin,orders.scity, orders.sname, orders.rname, orders.status, orders.order_type, orders.insured_premium, orders.net_amount, orders.extra_charges, orders.customer_id, orders.destination, orders.rcity, orders.weight,orders.status, orders.quantity, orders.track_no , manifest_detail.manifest_no, demanifest_detail.demanifest_no FROM orders LEFT join manifest_detail on orders.track_no=manifest_detail.track_no LEFT join demanifest_detail on orders.track_no =demanifest_detail.track_no  WHERE 1   $orgin_q  $dest_q $custom_field_query $where GROUP BY orders.track_no";
    // echo $sql;
    $query=mysqli_query($con , $sql );
    $rowcount=mysqli_affected_rows($con);
    if($rowcount>0){
        $total_pieces = '';
        $total_weight = '';
        $sr_no = 1;
        echo '<table class="table_box">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>CN#</th>';
        echo '<th>Ser</th>';
        echo '<th>Consigor</th>';
        echo '<th>Origin</th>';
        echo '<th>city</th>';
        echo '<th>Consignee</th>';
        echo '<th>Dest</th>';
        echo '<th>city</th>';
        echo '<th>Status</th>';
        echo '<th>PCS</th>';
        echo '<th>Weight</th>';
                                // echo '<th>Remarks</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody class="response_table_body">';
        while($fetch=mysqli_fetch_array($query)){
            $total_pieces +=$fetch['quantity'];
            $total_weight +=$fetch['weight'];
            echo "<tr>";
            echo "<td>".$fetch['track_no']." <input type='hidden' name='all_cn_no[]' class='all_cn_no' value='".$fetch['track_no']."' /><input type='hidden' name='customer_id[]' class='customer_id' value='".$fetch['customer_id']."' /><input type='hidden' name='origin[]' class='origin' value='".$fetch['origin']."' /><input type='hidden' name='destination[]' class='destination' value='".$fetch['destination']."' /><input type='hidden' name='order_type[]' class='order_type' value='".$fetch['order_type']."' /><input type='hidden' name='total_charges[]' class='total_charges' value='".$fetch['net_amount']."' /><input type='hidden' name='extra_charges[]' class='extra_charges' value='".$fetch['extra_charges']."' /><input type='hidden' name='insured_premium[]' class='insured_premium' value='".$fetch['insured_premium']."' /></td>";
            echo "<td>".$sr_no++."</td>";
            echo "<td>".$fetch['sname']."</td>";
            echo "<td>".$fetch['origin']."</td>";
            echo "<td>".$fetch['scity']."</td>";
            echo "<td>".$fetch['rname']."</td>";
            echo "<td>".$fetch['destination']."</td>";
            echo "<td>".$fetch['rcity']."</td>";
            echo "<td>".$fetch['status']."</td>";
            echo "<td>".$fetch['quantity']."<input type='hidden' class='hidden_qunatity_value' value=".$fetch['quantity']. " /></td>";
            echo "<td class='single_weight'>".$fetch['weight']."<input type='hidden' class='hidden_weight' value=".$fetch['weight']. " /></td>";
                                        // echo "<td></td>";<a href="#"><i class="fa fa-edit"></i></a>
            echo "<td>";
            echo '<a data-wt="'.$fetch["weight"].'" data-qt="'.$fetch["quantity"].'"  style="cursor:pointer" title="Edit" class="edit_row"><i class="fa fa-edit "></i></a>';
            echo '<a data-wt="'.$fetch["weight"].'" data-qt="'.$fetch["quantity"].'"  style="cursor:pointer" title="Trash" class="delete_row"><i class="fa fa-trash "></i></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '<input type="hidden" class="pieces" value='.$total_pieces.' readonly />';
        echo '<input type="hidden" class="new_weight" value='.$total_weight.' readonly />';
    }
    else{
        echo "No record found.";
    }
}
if(isset($_POST['city_value']) && !empty($_POST['city_value'])){
    $id_q = mysqli_query($con,"SELECT * FROM `cities` WHERE city_name = '".$_POST['city_value']."'");
    $id_res = mysqli_fetch_array($id_q);
    $id = $id_res['id'];
    $q=  "SELECT * FROM `areas` WHERE city_name = ".$id;
    $query=mysqli_query($con,$q);
    $rowcount=mysqli_affected_rows($con);
    if($rowcount>0){
        while($fetch=mysqli_fetch_array($query)){
            echo "<option value='".$fetch['city_name']."'>".$fetch['area_name']."</option>";
        }
    }
    else{
        echo "<option>No record found.</option>";
    }
}
?>
