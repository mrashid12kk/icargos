<div class="col-sm-12 outer_shadow">
    <div class="row">
        <?php
      $date = date('Y-m-d H:i:s');
      if (isset($_POST['add_tempate'])) {
         $template_content = mysqli_real_escape_string($con, $_POST['template_content']);

         $template_name = mysqli_real_escape_string($con, $_POST['template_name']);

         $template_code = mysqli_real_escape_string($con, $_POST['template_code']);

         $sms_events = mysqli_real_escape_string($con, $_POST['sms_events']);

         $status = isset($_POST['status']) ? $_POST['status'] : 0;
         $trim_send_to_value = '';
         if (isset($_POST['send_to'])) {
            $send_value_values = '';
            foreach ($_POST['send_to'] as $key => $value) {
               $send_value_values .= $value . ',';
            }
            $trim_send_to_value = trim($send_value_values, ',');
         }
         $trim_status_allowed_value = '';
         if (isset($_POST['status_allowed'])) {
            $send_status_allowed = '';
            foreach ($_POST['status_allowed'] as $key => $value) {
               $send_status_allowed .= $value . ',';
            }
            $trim_status_allowed_value = trim($send_status_allowed, ',');
         }
         $query2 = mysqli_query($con, "INSERT into `sms_templates`(template_content,template_name,template_code,send_to,sms_events,status,created_on,status_allowed)values('$template_content','$template_name','$template_code','$trim_send_to_value','$sms_events',$status,'$date','$trim_status_allowed_value')") or die(mysqli_error($con));
         $rowscount = mysqli_affected_rows($con);
         if ($query2) {
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you Add a New Template successfully</div>';
         } else {
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Add a New Template unsuccessfully.</div>';
         }
      }

      if (isset($_POST['updatetemplate'])) {
         $id = mysqli_real_escape_string($con, $_POST['edit_id']);
         $template_content = mysqli_real_escape_string($con, $_POST['template_content']);

         $template_name = mysqli_real_escape_string($con, $_POST['template_name']);

         $template_code = mysqli_real_escape_string($con, $_POST['template_code']);

         $sms_events = mysqli_real_escape_string($con, $_POST['sms_events']);

         $status = isset($_POST['status']) ? $_POST['status'] : 0;
         $trim_send_to_value = '';
         if (isset($_POST['send_to'])) {
            $send_value_values = '';
            foreach ($_POST['send_to'] as $key => $value) {
               $send_value_values .= $value . ',';
            }
            $trim_send_to_value = trim($send_value_values, ',');
         }
         $trim_status_allowed_value = '';
         if (isset($_POST['status_allowed'])) {
            $send_status_allowed = '';
            foreach ($_POST['status_allowed'] as $key => $value) {
               $send_status_allowed .= $value . ',';
            }
            $trim_status_allowed_value = trim($send_status_allowed, ',');
         }
         $query2 = mysqli_query($con, "UPDATE `sms_templates` set template_content='$template_content',  template_name= '$template_name',  template_code= '$template_code',send_to='$trim_send_to_value',sms_events='$sms_events',status='$status',status_allowed='$trim_status_allowed_value' where id=$id") or die(mysqli_error($con));
         $rowscount = mysqli_affected_rows($con);
         if ($query2) {
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you updated a New Template successfully</div>';
         } else {
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not upadated a New Template unsuccessfully.</div>';
         }
      }
      if (isset($_GET['id']) && !empty($_GET['id'])) {
         $query2 = mysqli_query($con, "SELECT * FROM sms_templates WHERE id=" . $_GET['id']);
         $edit = mysqli_fetch_assoc($query2);
      }
      $teplate_code = '';
      $teplate_code_q = mysqli_query($con, "SELECT MAX(Id) AS Max_Id FROM sms_templates");
      $template_codeq = mysqli_fetch_assoc($teplate_code_q);
      if (!empty($template_codeq)) {
         $last_id = $template_codeq['Max_Id'] + 1;
         $teplate_code = '00' . $last_id;
      } else {
         $teplate_code = 001;
      }
      $tempalte_id = '';
      if (isset($edit['id'])) {
         $tempalte_id = 'AND id!=' . $edit['id'];
      }
      $status = mysqli_query($con, "SELECT * from order_status order by sts_id desc");
      $email_templates = mysqli_query($con, "SELECT * from sms_templates WHERE status='1' $tempalte_id");
      $tempalte_status_allowed = '';
      while ($template = mysqli_fetch_array($email_templates)) {
         $tempalte_status_allowed .= $template['status_allowed'] . ',';
      }
      $trim_tempalte_status_allowed = trim($tempalte_status_allowed, ',');

      ?>

        <form method="post">

            <div class="col-sm-8 template_form">

                <div class="top_heading">
                    <h3>Create SMS Template</h3>
                </div>
                <div class="row">
                    <div class="col-sm-4 form_box">
                        <label>Template Code</label>
                        <input type="text" class="" value="<?php if (isset($edit)) {
                                                         echo $edit['template_code'];
                                                      } else {
                                                         echo $teplate_code;
                                                      } ?>" name="template_code" autocomplete="off" readonly>
                    </div>
                    <div class="col-sm-4 form_box">
                        <label>Template Name</label>
                        <input type="text" class="" value="<?php if (isset($edit)) {
                                                         echo $edit['template_name'];
                                                      } ?>" name="template_name" autocomplete="off" required>
                    </div>
                    <div class="col-sm-4 form_box">
                        <label>SMS EVENTS</label>
                        <select type="text" class="js-example-basic-single" id="sms_events" name="sms_events"
                            autocomplete="off" required>
                            <option value="" selected="" disabled="">Select</option>
                            <option value="Customer Booking" <?php if (isset($edit) && $edit['sms_events'] == 'Customer Booking') {
                                                         echo 'Selected';
                                                      } ?>>Customer Booking</option>
                            <option value="Admin Booking" <?php if (isset($edit) && $edit['sms_events'] == 'Admin Booking') {
                                                      echo 'Selected';
                                                   } ?>>Admin Booking</option>
                            <option value="Pickup Pequest" <?php if (isset($edit) && $edit['sms_events'] == 'Pickup Pequest') {
                                                         echo 'Selected';
                                                      } ?>>Pickup Request</option>
                            <option value="Status Update" <?php if (isset($edit) && $edit['sms_events'] == 'Status Update') {
                                                      echo 'Selected';
                                                   } ?>>Status Update</option>
                            <option value="Delivered" <?php if (isset($edit) && $edit['sms_events'] == 'Delivered') {
                                                   echo 'Selected';
                                                } ?>>Delivered</option>
                            <option value="Delivery SMS" <?php if (isset($edit) && $edit['sms_events'] == 'Delivery SMS') {
                                                      echo 'Selected';
                                                   } ?>>Delivery SMS</option>
                            <option value="Pickup SMS" <?php if (isset($edit) && $edit['sms_events'] == 'Pickup SMS') {
                                                   echo 'Selected';
                                                } ?>>Pickup SMS</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form_box status" style="display: none;">
                            <label>Status</label>
                            <select type="text" class="form-control js-example-basic-single" name="status_allowed[]"
                                id="select_status" autocomplete="off" required="false" multiple>
                                <?php
                        $status_allowed = isset($edit['status_allowed']) ? $edit['status_allowed'] : '';
                        $prev_array = explode(',', $status_allowed);
                        $prevs_array = explode(',', $trim_tempalte_status_allowed);
                        while ($row = mysqli_fetch_array($status)) {
                           $selected = '';
                           $disabled = '';
                           if (in_array($row['status'], $prev_array)) {
                              $selected = 'selected';
                           }
                           //   if (in_array($row['status'] , $prevs_array)) {
                           //       $disabled = 'disabled';
                           // }
                        ?>
                                <option <?php echo $selected . ' ' . $disabled; ?>
                                    value="<?php echo $row['status']; ?>"><?php echo getKeyWord($row['status']); ?>
                                </option>
                                <?php
                        } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 form_box">
                        <label>Compose </label>
                        <textarea id="textareaArea" type="text" class="" name="template_content" required> <?php if (isset($edit)) {
                                                                                                         echo $edit['template_content'];
                                                                                                      } ?></textarea>
                    </div>
                    <?php
               $admin_checked = '';
               $shipper_checked = '';
               $consignee_checked = '';
               $prev_array = explode(',', $edit['send_to']);
               if (in_array(1, $prev_array)) {
                  $admin_checked = 'checked';
               }
               if (in_array(2, $prev_array)) {
                  $shipper_checked = 'checked';
               }
               if (in_array(3, $prev_array)) {
                  $consignee_checked = 'checked';
               }
               ?>

                    <div class="col-sm-12 form_box send_to">
                        <h5>Send To</h5>
                        <div class="admin">
                            <label>Admin</label>
                            <input type="checkbox" <?php if (isset($edit)) {
                                                echo $admin_checked;
                                             } else {
                                                echo 'checked';
                                             } ?> value="1" name="send_to[]">
                        </div>
                        <div class="shipper">
                            <label>Shipper</label>
                            <input type="checkbox" <?php if (isset($edit)) {
                                                echo $shipper_checked;
                                             } ?> value="2" name="send_to[]">
                        </div>
                        <div class="consignee">
                            <label>Consignee</label>
                            <input type="checkbox" <?php if (isset($edit)) {
                                                echo $consignee_checked;
                                             } ?> value="3" name="send_to[]">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <!-- <div class="col-sm-3 form_box">
                  <label>Letter Count</label>
                  <input type="text" disabled="disabled" id="count" autocomplete="off" value="0">
               </div> -->
                    <!-- <div class="col-sm-3 form_box">
                  <label>SMS Count</label>
                  <input type="text" value="0" autocomplete="off" disabled="disabled">
               </div> -->
                    <div class="col-sm-4 form_box send_to_active">
                        <div class="active_status">
                            <label>Active</label>
                            <input type="checkbox" <?php if (isset($edit) && $edit['status'] == 1) {
                                                echo 'checked';
                                             } ?> value="1" name="status">
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 send_btn">
                    <input type="hidden" autocomplete="off" name="<?php if (isset($edit)) {
                                                                  echo 'edit_id';
                                                               } ?>"
                        value="<?php if (isset($edit)) {
                                                                                                                     echo $edit['id'];
                                                                                                                  } ?>">
                    <button class="send_button" name="<?php if (isset($edit)) {
                                                      echo 'updatetemplate';
                                                   } else {
                                                      echo 'add_tempate';
                                                   } ?>" type="SUBMIT">Submit</button>
                </div>

            </div>
        </form>
        <div class="col-sm-4 parametres_box">
            <?php include "partials/shortcodes.php";  ?>
        </div>
    </div>
</div>