<?php
session_start();
require 'includes/conn.php';
if(isset($_SESSION['users_id'])){
   // require_once "includes/role_helper.php";
   // if (!checkRolePermission($_SESSION['user_role_id'], 68,'view_only',$comment =null)) {

   //  header("location:access_denied.php");
// }
    include "includes/header.php";
    ?>
    <body data-ng-app>

        <style type="text/css">
            .manual_api {
                padding: 23px 0 0;
            }
            .upload_btn{
                padding: 21px 0 0;
            }
            .download_bx .upload_btn {
                padding: 0 0 12px;
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

                <div class="page-header"><h1><?php echo getLange('dashboard'); ?> <small><?php echo getLange('letsgetquick'); ?></small></h1></div>

                <?php

                if(isset($_POST['manual_api_booking'])){
                    $track_no=mysqli_real_escape_string($con,$_POST['track_no']);
                    $vendor_track_no=mysqli_real_escape_string($con,$_POST['vendor_track_no']);
                    $vendor_id=mysqli_real_escape_string($con,$_POST['vendor_id']);
                    $vendor_name=mysqli_fetch_assoc(mysqli_query($con,"SELECT name FROM vendors WHERE id=".$vendor_id.""));
                    $vendor_check=mysqli_fetch_assoc(mysqli_query($con,"SELECT vendor_track_no,vendor_id FROM orders WHERE track_no='".$track_no."'"));
                    if (!isset($vendor_check['vendor_track_no']) && !isset($vendor_check['vendor_id'])) {
                        $query = "UPDATE orders SET vendor_track_no='".$vendor_track_no."',vendor_id=".$vendor_id." WHERE track_no='".$track_no."'";
                        $query1=mysqli_query($con,$query) or die(mysqli_error($con));
                        $rowscount=mysqli_affected_rows($con);
                        if($rowscount>0){
                            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Well done!</strong> you added a new Manual Api Booking</div>';
                        }
                        else{
                            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not added a new Manual Api Booking.</div>';
                        }
                    }
                    else{
                        $error=$_POST;
                        $vendor_name=mysqli_fetch_assoc(mysqli_query($con,"SELECT name FROM vendors WHERE id=".$vendor_check['vendor_id'].""));
                        echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> This Parcel is alrady booked on '.$vendor_name['name'].' Vendor with Vendor Tracking No '.$vendor_check['vendor_track_no'].' .</div>';
                    }
                }
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">Manual Reference Update</div>
                    <div class="panel-body">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-sm-2 side_gapp">
                                    <div class="form-group">
                                        <label  class="control-label"><span style="color: red">*</span>Track No</label>
                                        <input type="text" class="form-control" name="track_no" placeholder="Track No" required value="<?php echo isset($error['track_no']) ? $error['track_no'] : ''; ?>">
                                    </div>
                                </div>

                           <?php /*?>
                            <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><span style="color: red">*</span>Vendor</label>
                                        <select class="form-control js-example-basic-single" name="vendor_id" required>
                                            <option value=''>Select API</option>
                                            <?php
                                            $record = mysqli_query($con, "SELECT * FROM vendors WHERE is_active=1");

                                            while ($row = mysqli_fetch_array($record)) {
                                                ?>

                                                <option value="<?php echo $row['id']; ?>" <?php echo isset($error['vendor_id']) && $error['vendor_id']==$row['id'] ? 'selected'  : ''; ?>><?php echo $row['name'] ?>
                                            </option>

                                            <?php
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><span style="color: red">*</span>Vendor Tracking No</label>
                                    <input type="text" class="form-control" name="vendor_track_no" placeholder="Api Tracking No" required value="<?php echo isset($error['vendor_track_no']) ? $error['vendor_track_no'] : ''; ?>">
                                </div>
                            </div>
                            <?php
                            */
                            ?>
                            <div class="col-sm-3 manual_api">
                              <button  type="submit" name="manual_api_booking" class="btn btn-info" ><?php echo getLange('submit'); ?></button>
                          </div>
                    <!-- <div class="col-sm-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><span style="color: red">*</span>Service type</label>
                            <select class="form-control js-example-basic-single" required name="select_api">
                                <option value='overnight'>OVERNIGHT</option>
                                <option value='detain'>DETAIN</option>
                                <option value='overland'>OVERLAND</option>
                            </select>
                        </div>
                    </div> -->

                    
                </div>


            </form>
            <div class="download_bx">
                <div class="upload_btn">
                    <a class="btn btn-danger" href="assets/excel/manual_reference_update.xlsx" download="">Download Sample Sheet</a>
                </div>
            </div>
            <form action="" method="post">

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label  class="control-label"><span style="color: red">*</span>Upload Excel File</label>
                            <input type="file" accept=".xlsx,.xls" class="form-control" id="FileInput" required >
                        </div>
                    </div>
                    
                    
                    <div class="col-sm-1 upload_btn">
                        <input type="hidden" id="file_name_org">
                        <button style="margin: 0 0 8px 15px;" disabled="true" type="button" id="submit" class="btn btn-info" ><?php echo getLange('upload'); ?></button>
                        <div class="row">
                            <div id="msg"></div>
                            <div id="table_msg"></div>
                            <img src="https://new.cod.zoomparcelservice.com/images/loader_se.gif" style="width: 150px;display: none;" id="image1">
                        </div>
                    </div>
                </div>
                
            </form>
           <?php /* ?> <div class="row">
                <div class="col-sm-4">
                    <table class="table_box">
                        <thead>
                            <tr>
                                <th>SRNO</th>
                                <th>Vendor</th>
                                <th>Vendor Code</th>
                            </thead>
                            <tbody class="response_table_body">
                                <?php
                                $srno=1;
                                $vendor_code_q=mysqli_query($con,"SELECT * FROM vendors");
                                while ($row=mysqli_fetch_array($vendor_code_q)) { ?>
                                   <tr>
                                    <td><?php echo $srno++; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['vendor_code']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div><?php
                */
            ?>
        </div>
    </div>

</div>
<?php

include "includes/footer.php";
}
else{
    header("location:index.php");
}
?>
<script>
    $(document).ready(function() {
        $("#FileInput").change(function() {
            var validExtensions = ["xlsx", "xlsm"]
            var file = $(this).val().split('.').pop();
            if (validExtensions.indexOf(file) == -1) {
                var msg = ("Only formats are allowed : " + validExtensions.join(', '));
                $('#msg').html('');
                $('#msg').html(msg);
                $(this).val("");
            } else {
                $('#msg').html('');
                $('#submit').prop('disabled', false);
            }
        });
        $("#submit").click(function() {

            var fd = new FormData();
            fd.append('file', $('#FileInput')[0].files[0]);
            $.ajax({
                url: 'ajax_import_manual_reference.php',
                dataType:'Json',
                beforeSend: function() {
                    $('#image1').show();
                }, 
                cache: false,
                contentType: false,
                processData: false,
                data: fd,
                type: 'post',
                success: function(output) {
                    $('#msg').html(output.msg);
                    $('#file_name_org').val(output.filename);
                    $('#submit').prop('disabled', true);
                    $('#FileInput').prop('disabled', true);
                    update_data();
                }
            })
        })
        function update_data() {
            var file_name_org = $('#file_name_org').val();
            $.ajax({
                url: 'ajax_import_manual_reference.php',
                complete: function() {
                    $('#image1').hide();
                },
                type: 'POST',
                data: {
                    update_booking: 1,
                    file_name_org: file_name_org
                },
                dataType: 'json',
                success: function(response) {
                    $('#msg').html('');
                    $('#table_msg').html(response.data_msg);
                    $('#submit').prop('disabled', true);
                    $('#FileInput').prop('disabled', false);
                    $('#FileInput').val('');
                }
            });
        }
    });
</script>