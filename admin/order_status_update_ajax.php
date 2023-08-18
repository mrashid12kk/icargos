<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
    session_start();
    include_once 'includes/conn.php';
    include_once 'includes/role_helper.php';
    include_once 'includes/custom_functions.php';

    if(isset($_POST['enter_cn'])&& !empty($_POST['enter_cn'])){
        $query=mysqli_query($con,"SELECT * FROM `orders` WHERE track_no = '".$_POST['enter_cn']."'");
        if (mysqli_num_rows($query) > 0) {
            while($fetch=mysqli_fetch_array($query)){

                echo "<tr>";
                    echo "<td>".$fetch['track_no']." <input type='hidden' name='all_cn_no[]' class='all_cn_no' value='".$fetch['track_no']."' /></td>";
                    echo "<td>".$fetch['sname']."</td>";
                    echo "<td>".$fetch['origin']."</td>";
                    echo "<td>".$fetch['rname']."</td>";
                    echo "<td>".$fetch['destination']."</td>";
                    echo "<td class='prev_main_status'>".getKeyWord($fetch['status'])."</td>";
                    echo "<td class='main_status_update' style='display:none'>";
                        echo '<select class="sts_main_table">';
                            $status_query = mysqli_query($con, "SELECT * FROM order_status ");
                            while ($row = mysqli_fetch_assoc($status_query)) {
                                $selected = '';
                                if ($row['status']==$fetch['status']) {
                                    $selected = 'selected';
                                }
                                echo '<option value="'.$row['status'].'" '.$selected.'>'.getKeyWord($row['status']).'</option>';
                            }
                        echo '</select>';
                    echo "</td>";
                    echo "<td>".$fetch['quantity']."</td>";
                    echo "<td>".$fetch['weight']."</td>";
                    echo "<td class='order_payment_status'>".$fetch['payment_status']."</td>";
                    echo "<td style='text-align: center;'>";
                        echo '<a style="margin: 0 3px; cursor:pointer" title="Edit" class="edit_row"><i data-payment="'.$fetch['payment_status'].'" class="fa fa-edit  edit_orders_sts" data-track_no="'.$fetch['track_no'].'" ></i></a>';
                    echo '</td>';
                echo '</tr>';
            }
            echo "<tr>";
                echo "<td colspan='10' class='order_log_table_heading'><h5>".getLange('order_history')." <span style='margin-left:10px;'><a href='#' class='add_status_log'>".getLange('add_log')."</a></span></h5></td>";
            echo'</tr>';
            $history_q=mysqli_query($con,"SELECT * FROM `order_logs` WHERE order_no= '".$_POST['enter_cn']."' ORDER BY created_on ASC");
            while($fetch=mysqli_fetch_array($history_q)){
                $date_formate = date('M d,Y - h:i:s A',strtotime($fetch['created_on']));
                $updated_by = '';
                if (isset($fetch['is_manual']) && $fetch['is_manual']==1) {
                    $u_name_q = mysqli_fetch_assoc(mysqli_query($con,"SELECT Name FROM users where id=".$fetch['user_id']));
                    $updated_by = isset($u_name_q['Name']) ? ' ('.$u_name_q['Name'].')': '';
                }
                $order_date = $fetch['created_on'];
                $order_time = date('H:i:s',strtotime($order_date));
                $order_date = date('Y-m-d',strtotime($order_date));
                echo "<tr>";
                    echo "<td>".$fetch['order_no']."<input type='hidden' name='all_cn_no[]' class='order_no' value='".$fetch['track_no']."' /></td>";
                    echo "<td colspan='2'>".getKeyWord($fetch['order_status'])." ".$updated_by."</td>";
                    echo "<td colspan='2'>";
                    // var_dump($fetch);
                    if(isset($fetch['country']) && empty($fetch['city'])){
                        echo getKeyWord($fetch['country']);
                    }elseif(isset($fetch['country']) && isset($fetch['city'])){
                      echo getKeyWord($fetch['country']).", ". getKeyWord($fetch['city']);
                  }
                  else{
                    echo getKeyWord($fetch['location']);
                  }
                    echo "<td colspan='2'>".getKeyWord($fetch['tracking_remarks'])."</td>";
                    echo "<td colspan='2'>".$date_formate."</td>";
                    echo "<td style='text-align: center;'>";
                        echo '<a style="cursor:pointer; margin: 0 3px;" title="Edit" class="edit_row"><i data-order_no='.$fetch['order_no'].' data-log_id='.$fetch['id'].' data-status="'.$fetch['order_status'].'" data-time='.$order_time.' data-date='.$order_date.' class="edit_order_status fa fa-edit "></i></a>';
                        echo '<a style="cursor:pointer" title="Trash" class="delete_row"><i data-log_id='.$fetch['id'].' class="delete_status_log fa fa-trash "></i></a>';
                    echo '</td>';
                echo '</tr>';
            }
        }else{
            echo "No Record Found";
        }

    }
    if(isset($_POST['update_log']) && $_POST['update_log']==1){
        // die('here');
        // var_dump($_POST);
        $user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
        $date = $_POST['date'].' '.$_POST['time'];

        mysqli_query($con,"UPDATE `order_logs` SET `user_id`=".$user_id." , `order_no`='".$_POST['order_no']."' , `order_status`='".$_POST['status']."' ,`is_manual`=1  ,`country`='".$_POST['country']."', `city`='".$_POST['city']."', `tracking_remarks`='".$_POST['tracking_remarks']."' ,`created_on`='".$date."' WHERE id=".$_POST['log_id']."");
        echo "updated";
    }

    if(isset($_POST['add_log']) && $_POST['add_log']==1){
        $user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
        $date = $_POST['date'].' '.$_POST['time'];
        $tracking_remarks = isset($_POST['tracking_remarks']) ? $_POST['tracking_remarks'] :'';
        $created_on = isset($_POST['created_on']) ? $_POST['created_on'] :'';
        $city = isset($_POST['city']) ? $_POST['city'] :'';
        $country = isset($_POST['country']) ? $_POST['country'] :'';    

        mysqli_query($con,"INSERT INTO `order_logs`(`user_id`,`order_no`, `order_status`,`is_manual`, `created_on`,`country`,`city`,`tracking_remarks`) VALUES ($user_id,'".$_POST['order_no']."','".$_POST['status']."',1,'".$date."','$country','$city','$tracking_remarks')");
        echo "Added";
    }
    if(isset($_POST['del_log_id'])){
        // echo
        mysqli_query($con,"DELETE FROM `order_logs` WHERE id=".$_POST['del_log_id']);
        echo "Deleted";
    }

    if(isset($_POST['update_popup']) && $_POST['update_popup']==1){
        $status = $_POST['status'];
        $time = $_POST['time'];
        $date = $_POST['date'];
        $order_no = $_POST['order_no'];
        $log_id = $_POST['log_id'];

        echo '<div class="row">';
        echo '<input type="hidden" class="order_log_update_id" value='.$log_id.'>';
        echo '<input type="hidden" class="order_no_log_update" value='.$order_no.'>';
                echo '<div class="col-sm-12 form_box_date">';
                    echo '<label>'.getLange('orderstatus').'</label>';
                    echo '<select class="update_order_status_log">';
                    $status_query = mysqli_query($con, "SELECT * FROM order_status ");
                        while ($row = mysqli_fetch_assoc($status_query)) {
                            $selected = '';
                            if ($row['status']==$status) {
                                $selected = 'selected';
                            }
                            echo '<option value="'.$row['status'].'" '.$selected.'>'.getKeyWord($row['status']).'</option>';
                        }
                    echo '</select>';
                echo '</div>';
            echo '</div>';
            echo '<div class="row">';
                echo '<div class="col-sm-6 form_box_date">';
                    echo '<label>'.getLange('orderdate1').'</label>';
                    echo '<input type="date"  class="order_date update_order_log_date"  value='.$date.'>';
                echo '</div>';
                echo '<div class="col-sm-6 form_box_date">';
                    echo '<label>'.getLange('ordertime').'</label>';
                    echo '<input type="time"  class="order_time update_order_log_time"  value='.$time.'>';
                echo '</div>';
                    echo '<div class="col-sm-6 form_box_date">';
                    echo '<label>'.getLange('country').'</label>';
                     echo '<select class="form-control country js-example-basic-single" name="country" id="country">';
                        $country_query = mysqli_query($con, 'SELECT * FROM country ORDER BY country_name ASC');
                        while ($row = mysqli_fetch_array($country_query)) {
                         echo  '<option value="'.$row['country_name'].'">'.$row['country_name'].'</option>';
                        }

                    echo '</select>';
                echo '</div>';
                  echo '<div class="col-sm-6 form_box_date">';
                    echo '<label>'.getLange('country').'</label>';
                     echo ' <select class="form-control select_dynmic_city js-example-basic-single select2" name="city" id="city">';
                        $country_id = 'Pakistan';
                        $country_res = mysqli_fetch_assoc(mysqli_query($con, "SELECT id from country where country_name='$country_id'"));
                        $countryid = isset($country_res['id']) ? $country_res['id'] : '';
                        $city_query = mysqli_query($con, "SELECT * FROM cities where country_id=$countryid ORDER BY city_name ASC");
                        while ($row = mysqli_fetch_array($city_query)) {
                         echo  '<option value="'.$row['city_name'].'">'.$row['city_name'].'</option>';
                        }

                    echo '</select>';
                echo '</div>';
                      echo  '<div class="row">';
           echo '<div class="col-sm-12 left_right_none">';
            echo     ' <div class="form-group">';
              echo    '  <label>Tracking Remarks</label>';
               echo   '  <input type="text" name="tracking_remarks" class="form-control tracking_remarks">';
                echo  '</div>';
          echo  '</div>';
        echo '</div>';
            echo '</div>';
            echo '<div class="row">';
                echo '<div class="col-sm-12 form_box_date">';
                    echo '<button class="update_to_log_btn">'.getLange('update_log').'</button>';
                echo '</div>';
            echo '</div>';
    }


    if(isset($_POST['update_main_table']) && $_POST['update_main_table']==1){
        $user_id = isset($_SESSION['users_id']) ? $_SESSION['users_id'] : '';
        $date = date('Y-m-d H:i:s');
        mysqli_query($con,"UPDATE `orders` SET `status`='".$_POST['status']."' WHERE track_no='".$_POST['track_no']."'");
        include "includes/sms_helper.php";
        if($_POST['status']=='Delivered'){
            $sendSms = sendSmsMobileGateWay($_POST['track_no'], 'Delivered');
        }else{
            $sendSms = sendSmsMobileGateWay($_POST['track_no'], 'Status Update');
        }
        
        mysqli_query($con,"INSERT INTO `order_logs`(`user_id`,`order_no`, `order_status`,`is_manual`, `created_on`) VALUES ($user_id,'".$_POST['track_no']."','".$_POST['status']."',1,'".$date."')");
        echo "updated";
    }
?>