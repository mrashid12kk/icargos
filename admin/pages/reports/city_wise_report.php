<?php  
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$active_destination='';
$check_status='Delivered';
$date_type='action_date';
$searchQuery='';
$destinationcity='';
$default_city = getConfig('city');
if(isset($_POST['submit']))
  {
      $active_destination = $_POST['destination'];
      $check_status = $_POST['status'];
      $date_type = $_POST['date_type'];
     if($active_destination != ''){

         $destinationcity= " and (city_name='".$active_destination."') ";

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
 
    $cities = mysqli_query($con,"SELECT * FROM cities WHERE 1 $destinationcity order by id desc ");
    $citieselect = mysqli_query($con,"SELECT * FROM cities WHERE 1 order by id desc ");
    $order_status = mysqli_query($con,"SELECT * FROM order_status");
 ?>

<div class="panel panel-default">

<div class="panel-heading"><?php echo getLange('city_wise_report'); ?>



    </div>





        <div class="panel-body" id="same_form_layout">



            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">



                <div class="row">



                    <div class="col-sm-12 table-responsive gap-none bordernone">

                   <form method="POST" action="">
                         <div class="col-sm-2 left_right_none">
                                <div class="form-group">

                                        <label><?php echo getLange('selectdeliverycity'); ?>  </label>
                                        <select class="form-control destination js-example-basic-single" name="destination">
                                            <option value="" <?php if($active_destination == ''){ echo "selected"; } ?>>All</option>
                                            <?php while($row = mysqli_fetch_array($citieselect)){ ?>
                                            <option <?php if($active_destination == $row['city_name']){ echo "selected"; } ?>><?php echo $row['city_name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                              </div>
                              <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                        <label><?php echo getLange('status'); ?>  </label>
                                        <select class="form-control origin js-example-basic-single" name="status">
                                            <option value="" <?php if($check_status == ''){ echo "selected"; } ?> >All</option>
                                            <?php while($row = mysqli_fetch_array($order_status)){ ?>
                                            <option <?php if($check_status == $row['status']){ echo "selected"; } ?> ><?php echo getKeyWord($row['status']); ?></option>
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

                        </div>

                        </form>

                        <table class="table table-striped table-bordered dataTable_with_sorting no-footer orders_tbl" >



                            <thead>

                                <tr role="row">
                                    <th><?php echo getLange('srno'); ?> </th>

                                    <th><?php echo getLange('city'); ?></th>

                                    <th><?php echo getLange('codamount'); ?> </th>

                                    <th><?php echo getLange('deliveryfee'); ?></th>

                                    <th><?php echo getLange('gst'); ?></th>

                                     <th><?php echo getLange('fuelsurcharge'); ?></th>

                                     <th style="width: 112px !important;"><?php echo getLange('totalcharges'); ?></th>
                                </tr>



                            </thead>



                            <tbody>



                            <?php
                               $totalcollection_amount=0;

                               $totalprice=0;

                               $totalgrand_total_charges=0;

                               $totalfuel_surcharge=0;

                               $totalpft_amount=0;

                               $srno=0;
                                while($citiesq=mysqli_fetch_array($cities)){
                                 
                                 $query1 = mysqli_query($con,"SELECT SUM(collection_amount) as collection_amount,SUM(price) as price,SUM(pft_amount) as pft_amount,SUM(fuel_surcharge) as fuel_surcharge,SUM(grand_total_charges) as grand_total_charges,destination FROM orders WHERE 1 $searchQuery AND destination='".$citiesq['city_name']."'");

                                while($fetch1=mysqli_fetch_array($query1)){

                                  if($fetch1['collection_amount']!='' || $fetch1['price']!='' || $fetch1['grand_total_charges']!='' || $fetch1['fuel_surcharge']!='' || $fetch1['pft_amount']!=''){

                                  $totalcollection_amount +=$fetch1['collection_amount'];

                                  $totalprice +=$fetch1['price'];

                                  $totalgrand_total_charges +=$fetch1['grand_total_charges'];

                                  $totalfuel_surcharge +=$fetch1['fuel_surcharge'];

                                  $totalpft_amount +=$fetch1['pft_amount'];

                                   
                                ?>
                                <tr class="gradeA odd" role="row">
                                   <td>

                                         <?php echo ++$srno; ?>

                                    </td>

                                    <td>

                                         <?php echo $citiesq['city_name']; ?>

                                    </td>

                                    <td>

                                         <?php 
                                         echo number_format((float)$fetch1['collection_amount'],2); ?>

                                    </td>

                                     <td>

                                        <?php echo number_format((float)$fetch1['price'],2); ?>

                                    </td>

                                    <td>

                                        <?php echo number_format((float)$fetch1['pft_amount'],2); ?>

                                    </td>

                                     <td>

                                         <?php echo number_format((float)$fetch1['fuel_surcharge'],2); ?>

                                    </td>

                                     <td>

                                         <?php echo number_format((float)$fetch1['grand_total_charges'],2); ?>

                                    </td>

                                </tr>



                                <?php



                                }
                                }
                               
                              }
                                ?>
                            </tbody>
                            <tfoot>
                                <td colspan="2"style="background-color: #DEDEDE;"><?php echo getLange('total'); ?></td>

                                <td style="background-color: #b6dde8;"><?php echo number_format((float)$totalcollection_amount , 2); ?></td>

                                <td style="background-color: #c2d69a;"><?php echo number_format((float)$totalprice,2); ?></td>

                                <td style="background-color: #b6dde8;"><?php echo number_format((float)$totalpft_amount,2); ?></td>

                                <td style="background-color: #c2d69a;"><?php echo number_format((float)$totalfuel_surcharge,2); ?></td>

                                <td style="background-color: #b6dde8;"><?php echo number_format((float)$totalgrand_total_charges,2); ?></td>
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

