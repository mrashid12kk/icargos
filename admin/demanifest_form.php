<?php
session_start();
require 'includes/conn.php';
if(isset($_SESSION['users_id']) && $_SESSION['type']=='admin')
{
     require_once "includes/role_helper.php";
    if (!checkRolePermission($_SESSION['user_role_id'],31,'add_only',$comment =null)) {

        header("location:access_denied.php");
    }
    include "includes/header.php";
$branches = mysqli_query($con,"SELECT * FROM branches");
$status_querys = mysqli_query($con, "SELECT * from order_status ");



?>


        <!-- Header Ends -->
        <body data-ng-app>


        <?php include "includes/sidebar.php"; ?>
        <!-- Aside Ends-->

        <section class="content">

            <?php include "includes/header2.php"; ?>

<?php
    if(isset($_POST['submit'])){
        $next_no = mysqli_query($con,"SELECT * from demanifest_master order by id desc limit 1");
        $number = mysqli_fetch_assoc($next_no);
        $next_available = $number['demanifest_no']+1;
        $manifest_no=$_POST['manifest_no'];
        $track_no=$_POST['track_no'];
        $demanifest_id=$_POST['demanifest_id'];
        $arrive_date=$_POST['arrival_date'];
        $branch_id=$_POST['branch_id'];
        $truck_no=$_POST['truck_no'];
        $total_cn=$_POST['total_cn'];
        $total_pieces=$_POST['total_pieces'];
        $total_weight=$_POST['total_weight'];
        $received_report=$_POST['received_report'];
        $query=mysqli_query($con,"INSERT into `demanifest_master` (manifest_no,demanifest_no,arrive_date,branch_id,truck_no,total_cn,total_pieces,total_weight,received_report)values('$manifest_no','$next_available','$arrive_date','$branch_id','$truck_no','$total_cn','$total_pieces','$total_weight','$received_report')")or die(mysqli_error($con));
        $last_id = mysqli_insert_id($con);
        if($last_id){

            foreach ($_POST['track_no'] as $key => $value) {

                $inQ = "INSERT INTO `demanifest_detail`(`demanifest_id`, `track_no`, `demanifest_no`) VALUES (".$last_id.",'".$value."',".$next_available.")";

                $up_ma_q = "UPDATE manifest_detail set is_demanifest = 1 where track_no = '".$value."'";

                $updateQuery = mysqli_query($con,$up_ma_q);


                $insertQu = mysqli_query($con,$inQ);
                $changeBranchQ='';
                if (isset($_POST['branch_id']) && !empty($_POST['branch_id'])) {
                    $changeBranchQ = "UPDATE orders set status='".$_POST['status']."' , current_branch= '".$_POST['branch_id']."' WHERE track_no = '".$value."'";
                }else{
                    $changeBranchQ = "UPDATE orders set status='".$_POST['status']."' , current_branch = 1 WHERE track_no = '".$value."'";
                }

                $changeBranch = mysqli_query($con,$changeBranchQ);
                 $date = date('Y-m-d H:i:s');
                if (isset($_POST['branch_id']) && !empty($_POST['branch_id'])) {
                    $order_logs_q = "INSERT INTO `order_logs`(`branch_id`, `assign_branch`, `order_no`, `order_status`,`created_on`,`tracking_remarks`,`country`,`city`,`voucher_type`,`trans_number`) VALUES (".$_POST['branch_id'].",".$_POST['branch_id'].",'".$value."','".$_POST['status']."','".$date."','".$_POST['tracking_remarks']."','".$_POST['country']."','".$_POST['city']."','Demanifest','".$next_available."')";
                }else{
                    $order_logs_q = "INSERT INTO `order_logs`(`branch_id`, `assign_branch`, `order_no`, `order_status`,`created_on`,`tracking_remarks`,`country`,`city`,`voucher_type`,`trans_number`) VALUES (1,1,'".$value."','".$_POST['status']."','".$date."','".$_POST['tracking_remarks']."','".$_POST['country']."','".$_POST['city']."','Demanifest','".$next_available."')";
                }
                // echo  $order_logs_q;
              
                $order_log = mysqli_query($con,$order_logs_q);
                include "includes/sms_helper.php";
                $sendSms = sendSmsMobileGateWay($value, 'Status Update');

            }
            if($last_id > 0){
                echo  '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">X</button><strong>Successfull!</strong> You have Added a Demanifest Detail .</div>';
                echo "<script>window.open('demanifest.php?print_id=".$last_id."')</script>";

            }
            else{
                echo  '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">X</button><strong>Unsuccessful!</strong> You have not Added a Demanifest Detail .</div>';
            }
        }
    }
$next_no = mysqli_query($con,"SELECT * from demanifest_master order by id desc limit 1");

$number = mysqli_fetch_assoc($next_no);

if (isset($number['manifest_no']) && !empty($number['manifest_no'])) {
    $number = $number['demanifest_no'];
}else{
    $number = 4000;
}
?>

                <form method="post" >
                    <div class="warper container-fluid">
                    <span class="msg"></span>
                <div class="page-header"><h1><?php echo getLange('demanifestform'); ?></h1></div>
                <div class="manifest_box">
                    <div class="row">
                        <div class="col-sm-5 colums_gapp gray-bg" style="padding-right: 8px;">
                            <div class="row">
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('demanifest'); ?> #</label>
                                        <input type="text" placeholder="" class="demanifest_id" value="<?php echo $number+ 1; ?>" name="demanifest_id" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('arrivaldate'); ?> </label>
                                        <input type="date" placeholder="" value="<?php echo date('Y-m-d'); ?>" name="arrival_date" autocomplete='off'>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('truckno'); ?> #</label>
                                        <input type="text" name="truck_no" class="truck_no" placeholder="" autocomplete='off'>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('branch'); ?></label>
                                            <?php if (isset($_SESSION['branch_id']) && !empty($_SESSION['branch_id'])): ?>
                                            <select class=" branch_id" name="branch_id" >

                                            <option value="<?php echo $_SESSION['branch_id']; ?>"><?php echo $current_branch; ?></option>

                                        </select>
                                        <?php else: ?>
                                        <select class=" branch_id" name="branch_id">

                                            <?php foreach($branches as $branch){ ?>
                                            <option  value="<?php echo $branch['id']; ?>"><?php echo $branch['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-sm-6 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('manifest'); ?> #</label>
                                        <div class="row">
                                            <div class="col-sm-8 colums_gapp ">
                                                <input type="text" placeholder="<?php echo getLange('manifest') ?> #" id="manifest_no" name="manifest_no" class="manifest manifest_no" value="" autocomplete='off'>
                                            </div>
                                            <div class="col-sm-4 colums_gapp">
                                                <button type="button" class="Pick_cn pick_cn_no_get" style="background: #23294c;"><?php echo getLange('pick'); ?></button>
                                            </div>
                                      </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-7 colums_gapp">
                                    <b class="list_below"><?php echo getLange('searchcninlistbelow'); ?></b>
                                    <div class="row" style="padding: 15px 0 0;">
                                        <div class="col-sm-8 colums_gapp padd_none colums_content">
                                           <input type="text" placeholder="<?php echo getLange('enter').' '.getLange('cnno') ?>." class="enter_cn" autocomplete='off'>
                                        </div>
                                        <div class="col-sm-4 colums_gapp padd_none">
                                            <button type="button"  class="append_cn_no" ><?php echo getLange('submit'); ?></button>
                                        </div>
                                  </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row cn_table">

                     <div class="col-sm-12 right_contents">
                        <div class="inner_contents table-responsive ">
                            <div class="tbody"></div>
                            <div class="foot"></div>


                            <table class="table_box">
                             <thead>
                                <tr>
                                <th style="width: 30px;"> </th>
                                <th style="width: 43px;"><?php echo getLange('ser'); ?>#</th>
                                <th><?php echo getLange('cn'); ?> #</th>
                                <th><?php echo getLange('pcs'); ?>.</th>
                                <th><?php echo getLange('weight'); ?></th>
                                <th><?php echo getLange('received'); ?></th>
                                <th><?php echo getLange('action'); ?> </th>

                              </tr>
                          </thead>
                               <tbody  class="clonetable">






                            </tbody></table>
                        </div>
                     </div>
                   </div>


                  <div class="manifest_box">
                    <div class="row">
                        <div class="col-sm-12 colums_gapp gray-bg" style="padding-right: 8px;">
                            <div class="row">
                                <!-- <b class="list_below">Totals</b> -->
                                <div class="col-sm-6 colums_gapp">
                                    <div class="row">
                                        <div class="col-sm-3 colums_gapp">
                                            <div class="colums_content">
                                                <label><?php echo getLange('totalcn'); ?></label>
                                                <input type="text" placeholder="" class="total_cn" name="total_cn">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 colums_gapp">
                                            <div class="colums_content">
                                                <label><?php echo getLange('totalpcs'); ?></label>
                                                <input type="text" placeholder="" class="total_pc" name="total_pieces">
                                            </div>
                                        </div>
                                        <div class="col-sm-3 colums_gapp">
                                            <div class="colums_content">
                                                <label><?php echo getLange('totalwt'); ?></label>
                                                <input type="text" placeholder="" class="total_wt" name="total_weight">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-3 colums_gapp">
                                    <div class="colums_content">
                                        <label><?php echo getLange('status'); ?></label>
                                        <select name="status" class="status">
                                        <?php while ($row= mysqli_fetch_assoc($status_querys)) {?>
                                            <?php
                                            $selected = '';
                                            if($row['sts_id'] == 5)
                                            {
                                                $selected = 'Selected';
                                            }

                                             ?>
                                            <option value="<?php echo $row['status'] ?>" <?php echo $selected; ?>><?php echo $row['status']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" >
                                               <div class="col-sm-4 left_right_none"></div>
                                               <div class="col-sm-3 left_right_none"></div>
                                               <div class="col-sm-2 left_right_none">
                                                <div class="form-group">
                                                    <label><?php echo getLange('Country'); ?> </label>
                                                    <select type="text" class="form-control js-example-basic-single country"
                                                        name="country" id="country">
                                                      <!--   <option selected value="">
                                                            <?php echo getLange('select') . ' ' . getLange('country'); ?></option> -->
                                                        <?php 
                                                        $country_query=mysqli_query($con,'SELECT * FROM country ORDER BY country_name ASC');
                                                        while ($row = mysqli_fetch_array($country_query)) {
                                                        // var_dump($row);
                                                         ?>
                                                        <option <?php echo isset($row['country_name']) && $row['country_name'] == 'Pakistan' ? 'selected' : ''; ?> value="<?php echo $row['country_name']; ?>">
                                                            <?php echo getKeyWord($row['country_name']); ?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                               </div>
                                                <div class="col-sm-2 left_right_none">
                                                    <div class="form-group">
                                                        <label><?php echo getLange('city'); ?> </label>
                                                        <select class="form-control js-example-basic-single city"
                                                            name="city" id="city">
                                                            <option selected value="">
                                                                <?php echo getLange('select') . ' ' . getLange('city'); ?></option>
                                                                <option>Select City</option>
                                                            <?php /*
                                                            $city_query=mysqli_query($con,'SELECT * FROM cities ORDER BY city_name ASC');

                                                            while ($row = mysqli_fetch_array($city_query)) { 
                                                                ?>
                                                            <option value="<?php echo $row['city_name']; ?>">
                                                                <?php echo getKeyWord($row['city_name']); ?></option>
                                                            <?php } */?>
                                                        </select>
                                                    </div>
                                                </div>
                                       
                                </div>
<div class="row" >
                                               <div class="col-sm-4 left_right_none"></div>
                                               <div class="col-sm-3 left_right_none"></div>
                                                <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Date Time'); ?> </label>
                                        <input type="text" class="form-control datetimepicker" name="created_on" value="<?php echo date('Y-m-d H:i:s');?>"> 
                                    </div>
                                </div>
                                              <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label><?php echo getLange('Tracking Remarks'); ?> </label>
                                        <input type="text" name="tracking_remarks" class="form-control tracking_remarks"> 
                                    </div>
                                </div>
                                       
                                </div>

                            </div>
                        </div>
                        <div class="colums_gapp">
                            <input style="width: auto;padding: 3px 19px;" type="submit" name="submit" class="Pick_cn" value="<?php echo getLange('submit') ?>">
                        </div>
                    </div>
                    <span class="msg"></span>
                </div>




            </div>

    </form>

          <?php include "includes/footer.php";
} else{
    header("location:index.php");
}
?>
<script type="text/javascript">
    $(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
});
</script>
<script type="text/javascript">

$(document).ready(function(){

        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$('#country').val()
        },
        success: function (data) {
           // alert(data);
           $('#city').html(data);

        },
        error: function (xhr, exception) {
            var msg = "";
            if (xhr.status === 0) {
                msg = "Not connect.\n Verify Network." + xhr.responseText;
            } else if (xhr.status == 404) {
                msg = "Requested page not found. [404]" + xhr.responseText;
            } else if (xhr.status == 500) {
                msg = "Internal Server Error [500]." +  xhr.responseText;
            } else if (exception === "parsererror") {
                msg = "Requested JSON parse failed.";
            } else if (exception === "timeout") {
                msg = "Time out error." + xhr.responseText;
            } else if (exception === "abort") {
                msg = "Ajax request aborted.";
            } else {
                msg = "Error:" + xhr.status + " " + xhr.responseText;
            }
           
        }
    }); 
     $('#country').on('change' , function(){
        // alert();
          $.ajax({
        url: 'ajax_new_country.php',
        type: "Post",
        async: true,
        data: { 
            name:$(this).val()
        },
        success: function (data) {
           // alert(data);
           $('#city').html(data);

        },
        error: function (xhr, exception) {
            var msg = "";
            if (xhr.status === 0) {
                msg = "Not connect.\n Verify Network." + xhr.responseText;
            } else if (xhr.status == 404) {
                msg = "Requested page not found. [404]" + xhr.responseText;
            } else if (xhr.status == 500) {
                msg = "Internal Server Error [500]." +  xhr.responseText;
            } else if (exception === "parsererror") {
                msg = "Requested JSON parse failed.";
            } else if (exception === "timeout") {
                msg = "Time out error." + xhr.responseText;
            } else if (exception === "abort") {
                msg = "Ajax request aborted.";
            } else {
                msg = "Error:" + xhr.status + " " + xhr.responseText;
            }
           
        }
    }); 
    });
});
   document.addEventListener('DOMContentLoaded', function() {
    $('.pick_cn_no_get').click(function(event){
        // event.preventDefault();
        var manifest=$('.manifest').val();
        var cnno=$('.cnno').val();
        var truck_no=$('.truck_no').val();

        if (manifest==='') {
                Swal.fire({
                     position: 'bottom-end',
                     icon: 'error',
                     title: 'Please enter manifest no. first',
                     showConfirmButton: false,
                     timer: 2500
                })
        }else{
            $.ajax({
                url:"demanifest_ajax.php",
                dataType:"json",
                data:{
                    cnno:cnno,
                    manifest:manifest,
                    truck_no:truck_no,
                },
                type:"post",
                success:function(response){
                    if (response.cn==0) {
                        Swal.fire({
                             position: 'bottom-end',
                             icon: 'error',
                             title: 'No record found',
                             showConfirmButton: false,
                             timer: 2500
                        })
                    }
                    if (response.error==1) {
                        Swal.fire({
                             position: 'bottom-end',
                             icon: 'error',
                             title: response.msg,
                             showConfirmButton: false,
                             timer: 3500
                        })
                    }
                   $('.clonetable').html("");
                   $('.clonetable').html(response.table);
                    $('.total_wt').val("");
                   $('.total_wt').val(response.wt);
                    $('.total_cn').val("");
                   $('.total_cn').val(response.cn);
                    $('.total_pc').val("");
                   $('.total_pc').val(response.qt);
                }

            });

        }

    })

       function totalPieces()
    {
        let totalpcs = 0;
        let nextTotal = $('body').find('.clonetable').find('tr').find('td').eq(3).html();
        $('body').find('.hidden_qunatity_value').each(function(index,value)
        {
            totalpcs +=parseFloat($(this).val());
        });
        $('body').find('.total_pc').val(totalpcs);

    }

     function totalWeight()
    {
        let totalweight = 0;
        let nextTotal = $('body').find('.clonetable').find('tr').find('td').eq(4).html();
        $('body').find('.hidden_weight').each(function(index,value)
        {
            totalweight +=parseFloat($(this).val());
        });
        $('body').find('.total_wt').val(totalweight);

    }

    $('body').on('keydown','.enter_cn',function(event){
        var manifest_no = $('.manifest').val();

        if(event.keyCode == 13)
        {

            if (manifest_no =='' ) {
                Swal.fire({
                     position: 'bottom-end',
                     icon: 'error',
                     title: 'Please enter manifest no. first',
                     showConfirmButton: false,
                     timer: 2500
                })
            }else{
                appendNextRow();
                event.preventDefault();
            }

        }
    });


    $(document).on("click", ".append_cn_no", function () {

        var manifest_no = $('.manifest').val();

        if (manifest_no =='' ) {
            Swal.fire({
                 position: 'bottom-end',
                 icon: 'error',
                 title: 'Please enter manifest no. first',
                 showConfirmButton: false,
                 timer: 2500
            })
        }else{
            appendNextRow();
        }

    });

    $(document).on('click','.delect_row',function(){
        var data_wt=$(this).attr('data-wt');
        var data_qt=$(this).attr('data-qt');
        $(this).closest ('tr').remove ();
        var total_wt=$('.total_wt').val();
        var total_pc=$('.total_pc').val();
        var total_cn=$('.total_cn').val();
        total_pcs=total_pc-data_qt;
        total_wts=total_wt-data_wt;
        total_cns=total_cn-1;
         $('.total_wt').val("");
       $('.total_wt').val(total_wts);
       $('.total_pc').val("");
       $('.total_pc').val(total_pcs);
       $('.total_cn').val("");
       $('.total_cn').val(total_cns);

    });
    // $(document).on('click','.submit',function(){
    //     $('#save_manifest_form').submit();
    //     var newid = $('body').find('.demanifest_id').val();
    //     var newidone  = parseFloat(newid) +parseFloat(1);
    //     $.ajax({
    //         // data: $('#save_manifest_form').submit();
    //         type: "POST",
    //         url: "demanifest_ajax.php",
    //         // data: $('#save_manifest_form').serialize(),
    //         success: function(msg){
    //             $('body').find('.demanifest_id').val(newidone);
    //             $('.msg').html("");
    //             $('.msg').html(msg);
    //             $('.clonetable').html("");
    //         }
    //     });
    // });

    function appendNextRow()
    {
        var length = $('body').find('.clonetable').find('tr').length;
        var a = $('body').find('.enter_cn').val();
        var manifest_no = $('body').find('.manifest_no').val();
        var tbody = $('body').find('.clonetable').find('tr');
            var existing_array=[];
            tbody.each(function(index){


                existing_array.push($(this).find('.all_cn_no').val());
            });

            var flag =false;
            tbody.each(function(index){
              if($.inArray(a, existing_array) !== -1)
              {

                flag = true;
                return false;
              }
              else
              {

                existing_array.push($(this).find('.all_cn_no').val());
              }
            });
          if(flag)
          {
            flag = false;
            Swal.fire({
                     position: 'bottom-end',
                     icon: 'error',
                     title: 'Track Number already exists.',
                     showConfirmButton: false,
                     timer: 2500
                })

            return false;
          }
        $.ajax({
            url: 'demanifest_ajax.php',
            type: 'POST',
            data:{demanifest_no:a,length:length,manifest_no:manifest_no},
            success: function (response) {
                if (response=='No Record Found') {
                    Swal.fire({
                         position: 'bottom-end',
                         icon: 'error',
                         title: 'No Record Found.',
                         showConfirmButton: false,
                         timer: 2500
                    });
                }else{
                    $('body').find(".clonetable").append(response);
                    $('body').find('.enter_cn').val('');
                    $('body').find('.enter_cn').focus();

                    var length = $('body').find('.clonetable').find('tr').length;
                    $('body').find('total_cn').val(length+1);

                    totalPieces();
                    totalWeight();


                }
             }
        });
    }
 }, false);
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".manifest").focus();
    })
</script>
