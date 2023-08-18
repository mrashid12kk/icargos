<?php  
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$customer_id='';
$date_type='order_date';
$searchQuery='';
$customerSearch='';
if(isset($_POST['submit']))
  {
      $customer_id = $_POST['customer_id'];
      $check_status = $_POST['status'];
      $date_type = $_POST['date_type'];
     if($customer_id != ''){

         $customerSearch= " and (id='".$customer_id."') ";

      }
       if($check_status != ''){

         $searchQuery .= " and (status='".$check_status."') ";

      }
      $from = date('Y-m-d',strtotime($_POST['from']));

      $to = date('Y-m-d',strtotime($_POST['to']));
      if($from != '' && $to !=''){
        $from = date('Y-m-d',strtotime($_POST['from']));

          $to = date('Y-m-d',strtotime($_POST['to']));

         $searchQuery .= " and DATE_FORMAT(`".$date_type."`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`".$date_type."`, '%Y-%m-%d') <= '".$to."' ";

      }

  }
  else{

      $from = date('Y-m-d');

      $to = date('Y-m-d');
        $searchQuery .= " and DATE_FORMAT(`".$date_type."`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`".$date_type."`, '%Y-%m-%d') <= '".$to."' ";
  }
 
    $customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1 AND customer_type='1' $customerSearch order by id ASC  ");
    $customer = mysqli_query($con,"SELECT * FROM customers WHERE status=1 AND customer_type='1' order by id ASC  ");
    $citieselect = mysqli_query($con,"SELECT * FROM cities WHERE 1 order by id desc ");
    $order_status = mysqli_query($con,"SELECT * FROM order_status");
 ?>


 <style type="text/css">
   #same_form_layout table tr th {
    border-bottom: none;
    text-align: center;
    background: #efeeee !important;
}
table.dataTable thead>tr>th.sorting_asc, table.dataTable thead>tr>th.sorting_desc, table.dataTable thead>tr>th.sorting, table.dataTable thead>tr>td.sorting_asc, table.dataTable thead>tr>td.sorting_desc, table.dataTable thead>tr>td.sorting {
    padding: 1px 5px;
    vertical-align: middle;
}
table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:after {
   
    bottom: auto;
    right: 2px;
    font-size: 8px;
    margin-top: -11px;
}
#same_form_layout {
    padding: 7px 10px;
}


 </style>

<div class="panel panel-default">

<div class="panel-heading"><?php echo getLange('cod_payables'); ?>



    </div>





        <div class="panel-body" id="same_form_layout">



            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">



                <div class="row">



                    <div class="col-sm-12 table-responsive gap-none bordernone">

                   <form method="POST" action="">
                         <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                        <label><?php echo getLange('customer'); ?>  </label>
                                        <select class="form-control origin js-example-basic-single" name="customer_id" id="customer_id">
                                            <option value="" <?php if($customer_id == ''){ echo "selected"; } ?> >All</option>
                                            <?php while($row = mysqli_fetch_array($customer)){ ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($customer_id == $row['id']){ echo "selected"; } ?> ><?php echo $row['bname']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                              </div>
                               <div class="col-sm-2 left_right_none">
                                    <div class="form-group">
                                        <label>Date Type </label>
                                        <select class="form-control" name="date_type" id="date_type">
                                            <option value="order_date" <?php if (isset($date_type) && $date_type=='order_date'){echo "Selected";} ?>>Order Date</option>
                                            <option value="action_date" <?php if (isset($date_type) && $date_type=='action_date'){echo "Selected";} ?>>Status Date</option>
                                        </select>

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

                            <div class="col-sm-1 sidegapp-submit left_right_none">

                                <input type="submit" style="margin-top: 9px;"  name="submit" class="btn btn-info" value="<?php echo getLange('submit'); ?>">

                            </div>

                      

                        </form>
  </div>
                        <table class="table table-striped table-bordered dataTable_with_sorting no-footer orders_tbl" >
                    
                                      <thead class="bg-tsk-o-1">
                                        <tr>
                                            <th rowspan="2" class="text-center" style="background-color: #bfbfbf;"><?php echo getLange('srno'); ?></th>
                                            <th rowspan="2" class="text-center" style="background-color: #bfbfbf;"><?php echo getLange('customername'); ?></th>
                                            <th colspan="2" class="text-center border-left border-right border-bottom" style="background-color: #bfbfbf;"><?php echo getLange('unpaid_open_orders') ?></th>
                                            <th rowspan="2" class="text-center"  style="background-color: #bfbfbf;"><?php echo getLange('total_payable_open_orders'); ?> #</th>
                                            <th colspan="3" class="text-center border-left border-right border-bottom" style="background-color: #bfbfbf;"><?php echo getLange('unpaid_closed_orders'); ?></th>
                                            <th rowspan="2" class="text-center" style="background-color: #bfbfbf;"><?php echo getLange('total_payable_closed_orders'); ?> #</th>
                                            <th rowspan="2" class="text-center" style="background-color: #bfbfbf;"><?php echo getLange('net_payables'); ?> #</th>
                                         </tr>
                                         <tr class="text-center">
                                            <th style="background-color: #bfbfbf;"><?php echo getLange('totalcod'); ?></th>
                                            <th style="background-color: #bfbfbf;"><?php echo getLange('totalcharges'); ?></th>
                                            <th style="background-color: #bfbfbf;"><?php echo getLange('delivered_cod'); ?></th>
                                            <th style="background-color: #bfbfbf;"><?php echo getLange('delivered_orders_charges'); ?></th>
                                            <th style="width: 45px;border-right: 1px solid #ddd;background-color: #bfbfbf;"><?php echo getLange('returned_orders_charges'); ?></th>
                                         </tr>
                                      </thead>
                                        <tbody>
                                          <?php
                                           $srno=1;
                                           $totalcod_open =0;
            										$totalgrandtotal_open =0;
            										$totalpayable_open =0;
            										$totalcod_delivered =0;
            										$totalgrandtotal_delivered =0;
            										$totalgrandtotal_returned =0;
            										$totalpayable_closed_order =0;
            										$totalnet_payable =0;
                                while($customersq=mysqli_fetch_array($customers)){
                               $totalcollection_amount_open=0;
                               $totalgrand_total_charges_open=0;
                               $total_payable_open_order=0;
                               $total_payable_closed_order=0;
                               $net_payables=0;
                               $totalcollection_amount_deliverd=0;
                               $totalgrand_total_charges_deliverd=0;
                               $totalgrand_total_charges_returned=0;
                                 $query1 = mysqli_query($con,"SELECT SUM(collection_amount) as collection_amount,SUM(net_amount) as grand_total_charges FROM orders WHERE 1  AND status!='Delivered' AND status!='cancelled' AND status!='Returned to Shipper' AND status!='New Booked' AND status!='Pick up in progress' AND payment_status='Pending' $searchQuery AND customer_id='".$customersq['id']."'");
                                
                                while($fetch1=mysqli_fetch_array($query1)){
                                  
                                  if($fetch1['collection_amount']!='' || $fetch1['grand_total_charges']!=''){

                                  $totalcollection_amount_open =$fetch1['collection_amount'];

                                  $totalgrand_total_charges_open =$fetch1['grand_total_charges'];
                                   }
                                }
                               
                                $query2 = mysqli_query($con,"SELECT SUM(collection_amount) as collection_amount,SUM(net_amount) as grand_total_charges FROM orders WHERE 1 AND status='Delivered' AND payment_status='Pending' $searchQuery AND customer_id='".$customersq['id']."'");
                                 
                                while($fetch2=mysqli_fetch_array($query2)){

                                  if($fetch2['collection_amount']!='' || $fetch2['grand_total_charges']!=''){
                                  $totalcollection_amount_deliverd =$fetch2['collection_amount'];

                                  $totalgrand_total_charges_deliverd =$fetch2['grand_total_charges'];
                                   }
                                }
                                
                                $query3 = mysqli_query($con,"SELECT SUM(net_amount) as grand_total_charges FROM orders WHERE 1 AND status='Returned to Shipper' AND payment_status='Pending' $searchQuery AND customer_id='".$customersq['id']."'");
                                 
                                while($fetch3=mysqli_fetch_array($query3)){

                                  if($fetch3['grand_total_charges']!=''){

                                  $totalgrand_total_charges_returned =$fetch3['grand_total_charges'];
                                   }
                                }
                                if($totalcollection_amount_open!=0 || $totalgrand_total_charges_open!=0 || $totalcollection_amount_deliverd!=0 || $totalgrand_total_charges_deliverd!=0 || $totalgrand_total_charges_returned!=0){
                                  $total_payable_open_order=$totalcollection_amount_open - $totalgrand_total_charges_open;
                                  $total_payable_closed_order=$totalcollection_amount_deliverd - $totalgrand_total_charges_deliverd - $totalgrand_total_charges_returned;
                                  $net_payables=$total_payable_open_order  + $total_payable_closed_order;
                                  // total
                                  $totalcod_open +=$totalcollection_amount_open;
                                  $totalgrandtotal_open +=$totalgrand_total_charges_open;
                                  $totalpayable_open +=$total_payable_open_order;
                                  $totalcod_delivered +=$totalcollection_amount_deliverd;
                                  $totalgrandtotal_delivered +=$totalgrand_total_charges_deliverd;
                                  $totalgrandtotal_returned +=$totalgrand_total_charges_returned;
                                  $totalpayable_closed_order +=$total_payable_closed_order;
                                  $totalnet_payable +=$net_payables;
                                ?>
                                   <tr>
                                      <td><?php echo $srno++ ; ?></td>
                                      <td><?php echo $customersq['bname']; ?></td>
                                      <td><?php echo number_format((float)$totalcollection_amount_open , 2); ?></td>
                                      <td><?php echo number_format((float)$totalgrand_total_charges_open , 2); ?></td>
                                      <td style="background-color: #b6dde8;"><?php echo number_format((float)$total_payable_open_order , 2); ?></td>
                                      <td><?php echo number_format((float)$totalcollection_amount_deliverd , 2); ?></td>
                                      <td><?php echo number_format((float)$totalgrand_total_charges_deliverd , 2); ?></td>
                                      <td><?php echo number_format((float)$totalgrand_total_charges_returned , 2); ?></td>
                                      <td style="background-color: #b6dde8;"><?php echo number_format((float)$total_payable_closed_order , 2); ?></td>
                                      <td style="background-color: #c2d69a;"><?php echo number_format((float)$net_payables , 2); ?></td>
                                   </tr>
                                 <?php
                               }
                               
                              }
                                ?>
                                        </tbody>
                                        <tfoot>
                                        	<td colspan="2"><?php echo getLange('total'); ?></td>
                                        	<td><?php echo number_format((float)$totalcod_open , 2); ?></td>
                                        	<td><?php echo number_format((float)$totalgrandtotal_open , 2); ?></td>
                                        	<td style="background-color: #b6dde8;"><?php echo number_format((float)$totalpayable_open , 2); ?></td>
                                        	<td><?php echo number_format((float)$totalcod_delivered , 2); ?></td>
                                        	<td><?php echo number_format((float)$totalgrandtotal_delivered , 2); ?></td>
                                        	<td><?php echo number_format((float)$totalgrandtotal_returned , 2); ?></td>
                                        	<td style="background-color: #b6dde8;"><?php echo number_format((float)$totalpayable_closed_order , 2); ?></td>
                                        	<td style="background-color: #c2d69a;"><?php echo number_format((float)$totalnet_payable , 2); ?></td>
                                        </tfoot>
                                      </table>
                            <form method="GET" id="bulk_submit" action="../<?php echo getConfig('print_template'); ?>" target="_blank">
                                <input type="hidden" name="order_id" id="print_data" >
                                <input type="hidden" name="save_print">
                            </form>
                </div>
            </div>
        </div>
    </div>
</div>

