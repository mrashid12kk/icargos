<style>
    .row{
        margin: 0 !important;
    }
    .table-responsive{
        padding: 0 !important;
    }
    .col-md-2{
            padding-right: 20px !important;
    }
</style>
<?php
function getCustomeName($id)
{
    global $con;
    // return $id;
    $sql = mysqli_query($con,"SELECT fname From customers where id = '$id'");

    $result = mysqli_fetch_array($sql);
    if($result['fname']){

        return $result['fname'];
    }
} ?>

<?php
$readStatus= 0;
$readWhere ='1';
if(isset($_GET['status']) && $_GET['status']=='read')
{
    $readStatus = 1;
    $readWhere = "is_read = 1";
}elseif(isset($_GET['status']) && $_GET['status']=='unread'){
    $readStatus = 1;
    $readWhere = "is_read = 0";
}

if (isset($_POST['generate'])) {

    $from=mysqli_real_escape_string($con,$_POST['from']);
    $from = date('Y-m-d', strtotime('-1 day', strtotime($from)));
    $to=mysqli_real_escape_string($con,$_POST['to']);
    $to = date('Y-m-d', strtotime('+1 day', strtotime($to)));
    $customer_id=mysqli_real_escape_string($con,$_POST['customer_id']);
    $where = "";

    if($customer_id != '')
        $where = " AND customer_id = ".$customer_id;
        $query="Select * from order_comments where DATE_FORMAT(created_on, '%Y-%m-%d') >= '$from' and DATE_FORMAT(created_on, '%Y-%m-%d') <= '$to' ".$where." AND ".$readWhere." order by id desc";
        $sql=  mysqli_query($con,$query);
      
}else{
  
    $sql  = mysqli_query($con,"SELECT * From order_comments WHERE ".$readWhere." order by id DESC");

}

// echo $sql;
// die();

$result1=mysqli_query($con,"SELECT count(*) as readdata from order_comments where is_read=1");
$read_data=mysqli_fetch_assoc($result1);

$result2=mysqli_query($con,"SELECT count(*) as unread from order_comments where is_read=0");
$unread_data=mysqli_fetch_assoc($result2);

$result3=mysqli_query($con,"SELECT count(*) as data from order_comments");
$all_data=mysqli_fetch_assoc($result3);




 ?>




 <!-- Code for the modal -->


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-body">
        <div class="popup_data">
           <div class="row bdr_box">
              <div class="col-sm-3 main_head"><b> <?php echo getLange('trackingno'); ?>  Track no:</b></div>
              <div class="col-sm-9 main_contents">
                 <p class="track_no_val"></p>
              </div>
           </div>
           <div class="row bdr_box">
              <div class="col-sm-3 main_head"><b><?php echo getLange('orderdate'); ?> Order Date:</b></div>
              <div class="col-sm-9 main_contents">
                 <p  class="order_date_val"></p>
              </div>
           </div>
           <div class="row bdr_box">
              <div class="col-sm-3 main_head"><b><?php echo getLange('customername'); ?>Customer Name:</b></div>
              <div class="col-sm-9 main_contents">
                 <p   class="customer_name_val"></p>
              </div>
           </div>
           <div class="row bdr_box">
              <div class="col-sm-3 main_head"><b><?php echo getLange('subject'); ?>Subject:</b></div>
              <div class="col-sm-9 main_contents">
                 <p   class="subject_val"></p>
              </div>
           </div>
           <div class="row bdr_box">
              <div class="col-sm-3 main_head"><b> <?php echo getLange('commentby'); ?>  Comment By:</b></div>
              <div class="col-sm-9 main_contents">
                 <p   class="comment_by_val"></p>
              </div>
           </div>
           <div class="row bdr_box">
              <div class="col-sm-3 main_head"><b>  <?php echo getLange('ordercomment'); ?> Order Comment:</b></div>
              <div class="col-sm-9 main_contents">
                 <p   class="order_comment_val"></p>
              </div>
           </div>


        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class=" btn btn-primary comment_modal_id"><?php echo getLange('close'); ?>Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>


 <!-- End modal here -->

<div class="panel panel-default">
<div class="panel-heading"><?php echo getLange('commentreport'); ?> </div>
    

        <div class="panel-body" id="same_form_layout">

        <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

                <div class="row gap-none" style="padding-left: 10px;" >

                    <form action="" method="post">

                        <div class="form-group">

                            <label style="margin: 0 0 5px;" class="col-sm-12 control-label sidegap"><?php echo getLange('generatereport'); ?></label>
                                <?php if(isset($_SESSION['type']) && $_SESSION['type'] == 'admin') { ?>
                                <div class="col-sm-2 sidegap">
                                    <div class="input-group">
                                        <select class="form-control" name="customer_id">
                                            <option value="">Select Customer</option>
                                            <?php
                                            $query = mysqli_query($con, "SELECT * FROM customers");
                                            if($query) {
                                                while ($row = mysqli_fetch_object($query)) {
                                                    if(isset($_POST['id']) && $_POST['id'] == $row->id)
                                                        echo '<option selected value="'.$row->id.'">'.$row->fname.'</option>';
                                                    else
                                                        echo '<option value="'.$row->id.'">'.$row->fname.'</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="col-sm-2 sidegap">

                                    <div class="input-group date">

                                        <input type="text" name="from" class="form-control datepicker" value="<?php echo isset($_POST['from'])?$_POST['from']:date('Y-m-d'); ?>" data-date-format="YYYY-MM-DD">

                                        <!-- <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span> -->

                                        </span>

                                    </div>

                                </div>



                                <div class="col-sm-2 sidegap">

                                    <div class="input-group date">

                                        <input type="text" name="to" class="form-control datepicker" value="<?php echo isset($_POST['to'])?$_POST['to']:date('Y-m-d'); ?>" data-date-format="YYYY-MM-DD">

                                        <!-- <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span> -->

                                        </span>

                                    </div>

                                </div>

                                <div class="col-sm-2 sidegap">

                                    <button type="submit" name="generate" class="btn btn-info"><?php echo getLange('generatereport'); ?> </button>

                                </div>
                                <div class="col-sm-4 action_btns">
                                    <ul>
                                        <li><a href="comments_report.php"
                                                class="active"
                                            >All (<?php echo $all_data['data']; ?>)</a></li>

                                        <li><a href="comments_report.php?status=read"
                                            >Read (<?php echo $read_data['readdata']; ?>)</a></li>

                                        <li>
                                            >UnRead (<?php echo $unread_data['unread']; ?>)</a></li>
                                    </ul>
                                </div>



                        </div>

                    </form>



                    <div class="col-sm-12 table-responsive">

                        <div class="pdf">


                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="basic-datatable" role="grid" aria-describedby="basic-datatable_info">

                            <thead>

                                <tr role="row">

                                   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">#</th>
                                   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column "><?php echo getLange('trackingno'); ?> </th>

                                   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column "><?php echo getLange('orderdate'); ?> .</th>

                                   <!-- <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">Package Type</th> -->

                                   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column "><?php echo getLange('customername'); ?> </th>

                                    <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"><?php echo getLange('subject'); ?></th>

                                    <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending"><?php echo getLange('ordercomment'); ?> </th>



                                   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 100px !important;"><?php echo getLange('commentby'); ?> </th>
                                   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 100px !important;"><?php echo getLange('status'); ?></th>

                                   <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 100px !important;"><?php echo getLange('action'); ?></th>



                                 </tr>

                            </thead>

                            <tbody>

                            <?php
                                $sr_no=1;
                                while($fetch1=mysqli_fetch_array($sql)){
                                  // echo '<pre>';
                                  // print_r($fetch1);
                                  // die;

                            ?>

                                <tr class="gradeA odd" role="row">

                                    <td class="sorting_1"><?php echo $sr_no++; ?></td>

                                    <td class="sorting_1"><?php echo $fetch1['track_no']; ?></td>

                                    <td class="sorting_1"><?php echo $fetch1['created_on']; ?></td>

                                    <!-- <td class="sorting_1"><?php echo $fetch1['id']; ?></td> -->

                                    <td class="sorting_1"><?php echo getCustomeName($fetch1['customer_id']); ?></td>

                                    <td><?php echo $fetch1['subject']; ?></td>

                                    <td><?php echo $fetch1['order_comment']; ?></td>

                                    <td><?php echo $fetch1['comment_by']; ?></td>

                                    <td>
                                        <?php if ($fetch1['is_read']==1): ?>
                                          <span class="label label-default">Read</span>

                                        <?php else: ?>
                                            <span class="label label-primary">Unread</span>
                                        <?php endif; ?>
                                    </td>


                                    <td >
                                        <!-- <a href="#"><i class="fa fa-trash"></i></a> -->
                                        <?php if ($fetch1['is_read']==0): ?>
                                          <a href="#" class="read_msg"
                                           data-track = "<?php echo $fetch1['track_no']; ?>"
                                           data-id = "<?php echo $fetch1['id']; ?>"
                                           data-date = "<?php echo $fetch1['created_on']; ?>"
                                           data-name = "<?php echo getCustomeName($fetch1['customer_id']); ?>"
                                           data-subject = "<?php echo $fetch1['subject']; ?>"
                                           data-comment = "<?php echo $fetch1['order_comment']; ?>"
                                           data-commentby = "<?php echo $fetch1['comment_by']; ?>"

                                           ><i class="fa fa-book" data-toggle="modal" data-target="#exampleModal"></i></a>
                                        <?php endif ?>
                                     </td>

                                </tr>

                                <?php

                                }
                                ?>

                            </tbody>

                        </table>

                        </div>

                        <div class="text-center">

                            <img src="images/raw.gif" style="display:none;">

                            <a href="#" class="btn btn-success center" target="_blank" id="down_pdf"  style="display:none;">Download PDF</a>

                            <!-- <a href="#" class="btn btn-success center" id="gen_pdf">Generate PDF</a> -->

                        </div>

                </div>

            </div>

        </div>

    </div>

</div>
