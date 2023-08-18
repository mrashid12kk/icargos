<?php
    session_start();
        require 'includes/conn.php';
        require 'includes/functions.php';
    if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver')){
        include "includes/header.php";
        $cities1 = mysqli_query($con,"SELECT * FROM cities WHERE 1 ");
        $cities2 = mysqli_query($con,"SELECT * FROM cities WHERE 1 order by id desc ");
        $drivers = mysqli_query($con,"SELECT * FROM users WHERE type='driver' order by id desc ");
        $customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
        $order = mysqli_query($con,"SELECT * FROM orders ");     
        $active_customer_query = "";
        if(isset($_POST['active_customer'])){
           $active_customer = $_POST['active_customer'];
          if(empty($active_customer)){
             $active_customer_query = "";
          }else{
            $active_customer_query = " AND customer_id=$active_customer ";
          }
        }
        $order_status= mysqli_query($con,"SELECT * from order_status");
        $customer_id='';
        $customerquery='';
        if (isset($_POST['customer'])){
            $customer_id=$_POST['customer'];
            $customerqu=" id='".$customer_id."'";
            $customerquery="and customer_id='".$customer_id."'";
        }
            $customer_id= isset($_GET['customer_id']) ? $_GET['customer_id'] : '';
            $customer_get_query = '';
            $customer_get_query_order='';
            if (isset($_GET['customer_id']) && !empty($_GET['customer_id'])) {
                 $customer_get_query .= " AND id=".$_GET['customer_id'];
                 $customer_get_query_order .= " AND customer_id=".$_GET['customer_id'];
            }
        if (isset($_POST['submit'])){
            $value=$_POST['checkbox'];
            $string=implode(',',$value);
            $update=mysqli_query($con,"UPDATE `config` SET `value`='$string' WHERE name = 'status_report_setting'");
        }

   ?>
<body data-ng-app>
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
      .select2-container ,.form-control{
      margin: 0;
      width: 97% !important;
      }
      .select2-container--default .select2-selection--single {
      border: 1px solid #ccc !important;
      }
      .select2-container .select2-selection--single {
      height: 34px !important;
      }
      .buttons-print,.buttons-pdf{
      display: none !important;
      }
      table.dataTable thead>tr>th.sorting{
        padding-right: 9px;
      }
   </style>
   <?php
      include "includes/sidebar.php";
      ?>
      <div class="show_panel">
        <button type="button"><?php echo getLange('show'); ?></button>
      </div>
    
      <div class="panel panel-default">
         <div class="panel-heading order_box"><?php echo getLange('statusreport'); ?></div>
        <div class="panel-body" id="same_form_layout">
            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">
                <div class="favourite_checkbox">
                <h3><?php echo getLange('selectfaveouritestatus'); ?></h3>
                    <ul>
                    <?php
                        $status = mysqli_query($con,"SELECT * FROM order_status");
                        while($row = mysqli_fetch_array($status)){
                            $query =mysqli_query($con,"SELECT *  FROM config where name='status_report_setting'");
                            $selected = '';
                            $record = mysqli_fetch_assoc($query);
                            $multiVluesWithComma = $record['value'];
                            $array = explode(',', $multiVluesWithComma);
                            $a = array();
                            foreach ($array as  $value) {
                                array_push($a, $value);
                            }
                            if (in_array($row['sts_id'] , $a)) {
                               $selected = 'checked';
                            }else{
                               $selected = '';
                            }
                    ?>
                    <form action="" method="POST">
                        <li class="track">
                            <label data-track = "<?php echo $row['status']; ?>">
                                <input type="checkbox" data-id="<?php  echo $row['sts_id'];?>" class="status" name="checkbox[]" value="<?php  echo $row['sts_id'];?>"  <?php echo  $selected; ?> > <?php  echo $row['status'];?>
                             </label>
                        </li>
                            <?php
                        }
                            ?>
                    </ul>

                    <div class="row">
                        <div class="col-sm-4" style="padding: 0;">
                            <button type="submit" name="submit" class="submitt"><?php echo getLange('save') ?></button>
                        </div>
                    </form>
                     <div class="col-sm-4">

                     </div>
                     <div class="col-sm-4 hide_box">
                        <button type="button"><?php echo getLange('hide'); ?></button>
                     </div>
                     </div>
                </div>
                <div class="row">
                  <div class="row">
        <div class="col-sm-2 left_right_none" style="margin-left: 27px;">
            <div class="form-group">
                <label><?php echo getLange('select').' '.getLange('customer'); ?> </label>
                <select class="form-control origin js-example-basic-single" name="customer" onchange="window.location.href='order_status_report.php?customer_id='+this.value">
                    <option value="" <?php if($customer_id == ''){ echo "selected"; } ?> >All</option>
                    <?php while($row = mysqli_fetch_array($customers)){ ?>
                    <option value="<?php echo $row['id']; ?>" <?php if($customer_id == $row['id']){ echo "selected"; }   ?>     ><?php echo isset($row['bname']) ? $row['bname'] : ''; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>

                    <div class="col-sm-12 table-responsive gap-none bordernone">
          <?php if(isset($_GET['customer_id'])){

                        ?>
                        <table class="table table-striped table-bordered dataTable_with_sorting no-footer orders_tbl" >
                            <thead>
                                <tr role="row">
                                    <th style="width: 10px;"><input type="checkbox" name="" class="main_select"></th>
                                    <th style="width: 41px;">S.No</th>
                                    <th style="width: 103px;"><?php echo getLange('customername'); ?></th>
                                    <?php
                                        $query =mysqli_query($con,"SELECT * FROM config  where name='status_report_setting'");
                                        $record = mysqli_fetch_assoc($query);
                                        $allValues = $record['value'];
                                        $status = mysqli_query($con,"SELECT * from order_status where sts_id IN($allValues) ");
                                        while($row = mysqli_fetch_array($status)){
                                    ?>
                                        <th style="width: 78px;"><?php echo $row['status'];?></th>
                                    <?php
                                     }
                                    ?>
                                        <th style="width: 56px !important;">Total</th>
                                </tr>
                            </thead>
                       <tbody>
                            <?php
                            $total=0;
                            $sr=1;
                            $querry=mysqli_query($con,"SELECT * from customers where 1 $customer_get_query ");

                            while($cus=mysqli_fetch_assoc($querry)){
                                ?>
                                <tr class="gradeA odd" role="row">
                                    <td class="sorting_1" tabindex="0"><input type="checkbox" name="" class="order_check" data-id="200"></td>
                                    <td><?php echo $sr;?></td>
                                    <td><?php echo $cus['fname'];?></td>
                                <?php
                                    $query =mysqli_query($con,"SELECT * FROM config  where name='status_report_setting'");
                                    $record = mysqli_fetch_assoc($query);
                                    $allValues = $record['value'];
                                    $status = mysqli_query($con,"SELECT * from order_status where sts_id IN($allValues) ");
                                    $totalOfColumns  = 0;
                                    $array = explode(',', $multiVluesWithComma);
                                    $allValuesNames = array();
                                    foreach ($array as  $value) {
                                        $nameQuery = mysqli_query($con, "SELECT * FROM order_status where sts_id = ".$value);
                                        $statusNameResult = mysqli_fetch_assoc($nameQuery);
                                        $statusName = $statusNameResult['status'];
                                        $countQuery= mysqli_query($con, "SELECT count(id) as totalcount from orders where status = '$statusName' and customer_id=".$cus['id']);
                                        $countRes = mysqli_fetch_assoc($countQuery);
                                        $resCount = $countRes['totalcount'];
                                        $totalOfColumns +=$resCount;
                                    }
                                    // $countQuery= mysqli_query($con, "SELECT count(id) as totalcount from orders where status IN($allValuesNames ) and customer_id='".$cus['id']."'");
                                    // echo $totalOfColumns;
                                    // die();
                                    // $countResults = mysqli_fetch_assoc($countQuery);
                                    // $totalOfColumns = $countResults['totalcount'];
                                    while($row = mysqli_fetch_array($status)){

                                        $orders = mysqli_query($con,"SELECT count(id) as count FROM orders where status='".$row['status']."' and customer_id='".$cus['id']."' ".$customerquery);
                                        $total+=$stat['count'];
                                        $stat = mysqli_fetch_assoc($orders);
                                    ?>
                                        <td><?php echo $stat['count']?></td>
                                    <?php
                                    }
                                    ?>
                                    <?php

                                    $GrandTotal += $totalOfColumns;

                                     ?>
                                        <td><?php echo $totalOfColumns; ?></td>
                                </tr>
                                <?php
                                $sr++;
                            }
                                ?>
                        </tbody>
                        <tfoot style="background: #0d0150;">
                            <td></td>
                            <td></td>
                            <td style="color: #fff;font-size: 14px !important;">Total</td>
                            <?php
                            $query =mysqli_query($con,"SELECT * FROM config  where name='status_report_setting'");
                            $record = mysqli_fetch_assoc($query);
                            $allValues = $record['value'];
                            $status = mysqli_query($con,"SELECT * from order_status where sts_id IN($allValues) ");
                            while($row = mysqli_fetch_array($status)){
                                $orders = mysqli_query($con,"SELECT count(id) as count FROM orders where status='".$row['status']."' $customer_get_query_order ");
                                $stat = mysqli_fetch_assoc($orders);
                            ?>
                                <td style="color: #fff;font-size: 14px !important;"><?php echo $stat['count']?></td>
                            <?php
                            }
                            ?>
                                 <td></td>
                        </tfoot>
                    </table>
                    <?php
                  }else{
                    ?>
                        <table class="table table-striped table-bordered dataTable_with_sorting no-footer orders_tbl" >
                            <thead>
                                <tr role="row">
                                    <th style="width: 10px;"><input type="checkbox" name="" class="main_select"></th>
                                    <th style="width: 41px;">S.No</th>
                                    <th style="width: 103px;"><?php echo getLange('customername'); ?></th>
                                    <?php
                                        $query =mysqli_query($con,"SELECT * FROM config  where name='status_report_setting'");
                                        $record = mysqli_fetch_assoc($query);
                                        $allValues = $record['value'];
                                        $status = mysqli_query($con,"SELECT * from order_status where sts_id IN($allValues) ");
                                        while($row = mysqli_fetch_array($status)){
                                    ?>
                                        <th style="width: 78px;"><?php echo $row['status'];?></th>
                                    <?php
                                     }
                                    ?>
                                        <th style="width: 56px !important;">Total</th>
                                </tr>
                            </thead>
                        <tbody>
                            <?php
                            $total=0;
                        $sr=1;
                            $querry=mysqli_query($con,"SELECT * from customers $customer_get_query");

                            while($cus=mysqli_fetch_assoc($querry)){
                                ?>
                                 <tr class="gradeA odd" role="row">
                                    <td class="sorting_1" tabindex="0"><input type="checkbox" name="" class="order_check" data-id="200"></td>
                                    <td><?php echo $sr;?></td>
                                    <td><?php echo $cus['fname'];?></td>
                                <?php
                                    $query =mysqli_query($con,"SELECT * FROM config  where name='status_report_setting'");
                                    $record = mysqli_fetch_assoc($query);
                                    $allValues = $record['value'];
                                    $status = mysqli_query($con,"SELECT * from order_status where sts_id IN($allValues) ");
                                    $totalOfColumns  = 0;
                                    $array = explode(',', $multiVluesWithComma);
                                    $allValuesNames = array();
                                    foreach ($array as  $value) {
                                        $nameQuery = mysqli_query($con, "SELECT * FROM order_status where sts_id = ".$value);
                                        $statusNameResult = mysqli_fetch_assoc($nameQuery);
                                        $statusName = $statusNameResult['status'];
                                        $countQuery= mysqli_query($con, "SELECT count(id) as totalcount from orders where status = '$statusName' and customer_id=".$cus['id']);
                                        $countRes = mysqli_fetch_assoc($countQuery);
                                        $resCount = $countRes['totalcount'];
                                        $totalOfColumns +=$resCount;
                                    }
                                  
                                    while($row = mysqli_fetch_array($status)){

                                        $orders = mysqli_query($con,"SELECT count(id) as count FROM orders where status='".$row['status']."' and customer_id='".$cus['id']."' ".$customerquery);
                                        $total+=$stat['count'];
                                        $stat = mysqli_fetch_assoc($orders);
                                    ?>
                                        <td><?php echo $stat['count']?></td>
                                    <?php
                                    }
                                    ?>
                                    <?php

                                    $GrandTotal += $totalOfColumns;

                                     ?>
                                        <td style=" background-color:; color: #black;font-size: 14px !important;"><?php echo $totalOfColumns; ?></td>
                                </tr>
                                <?php
                                $sr++;
                              }
                                ?>
                        </tbody>
                        <tfoot style="background: #0d0150;">
                            <td></td>
                            <td></td>
                            <td style="color: #fff;font-size: 14px !important;"><?php echo getLange('total'); ?></td>
                            <?php
                            $query =mysqli_query($con,"SELECT * FROM config  where name='status_report_setting'");
                            $record = mysqli_fetch_assoc($query);
                            $allValues = $record['value'];
                            $status = mysqli_query($con,"SELECT * from order_status where sts_id IN($allValues) ");
                            while($row = mysqli_fetch_array($status)){
                                $orders = mysqli_query($con,"SELECT count(id) as count FROM orders where status='".$row['status']."' ");
                                $stat = mysqli_fetch_assoc($orders);
                            ?>
                                <td style="color: #fff;font-size: 14px !important;"><?php echo $stat['count']?></td>
                            <?php
                            }
                            ?>
                           
                             <td style="color: #fff;font-size: 14px !important;"></td>
                             <?php

                             ?>
                        </tfoot>
                    </table>
                    <?php
                  }
                    ?>
                    <form method="GET" id="bulk_submit" action="bulk_invoice.php" target="_blank">
                        <input type="hidden" name="print_data" id="print_data" >
                        <input type="hidden" name="save_print">
                    </form>
                  </div>
                </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Warper Ends Here (working area) -->
   <?php
      include "includes/footer.php";
}
      else{
          header("location:index.php");
      }
      ?>
