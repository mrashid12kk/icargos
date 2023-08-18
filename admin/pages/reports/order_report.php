
<?php $customers = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
$order_status = mysqli_query($con,"SELECT * FROM order_status");
 ?>
<div class="panel panel-default">
<div class="panel-heading"><?php echo getLange('orderreport'); ?>

    </div>


        <div class="panel-body" id="same_form_layout">

            <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

                <div class="row">

                    <div class="col-sm-12 table-responsive gap-none bordernone">
 <form method="POST" action="">
                            <div class="row">
                                <div class="col-sm-1 left_right_none upate_Btn_box" >
                    <a href="#" class="btn btn-info print_invoice btn-sm" ><?php echo getLange('printinvoice'); ?> </a>
                </div>
                              <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                        <label><?php echo getLange('trackingno'); ?>  </label>
                                        <input type="text" value="<?php echo $active_tracking; ?>" class="form-control " name="tracking_no">
                                    </div>
                              </div>

                              <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                        <label><?php echo getLange('selectpickupcity'); ?>  </label>
                                        <select class="form-control origin js-example-basic-single" name="origin">
                                            <option value="" <?php if($active_origin == ''){ echo "selected"; } ?> >All</option>
                                            <?php while($row = mysqli_fetch_array($cities1)){ ?>
                                            <option <?php if($row['city_name'] == 'Karachi'){ echo "selected"; } ?>  <?php if($active_origin == $row['city_name']){ echo "selected"; } ?>><?php echo $row['city_name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                              </div>

                              <div class="col-sm-2 left_right_none">
                                <div class="form-group">

                                        <label><?php echo getLange('selectdeliverycity'); ?>  </label>
                                        <select class="form-control destination js-example-basic-single" name="destination">
                                            <option value="" <?php if($active_destination == ''){ echo "selected"; } ?>>All</option>
                                            <?php while($row = mysqli_fetch_array($cities2)){ ?>
                                            <option <?php if($active_destination == $row['city_name']){ echo "selected"; } ?>><?php echo $row['city_name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                              </div>
                                <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                        <label><?php echo getLange('customer'); ?>  </label>
                                        <select class="form-control origin js-example-basic-single" name="customer_id">
                                            <option value="" <?php if($customer_id == ''){ echo "selected"; } ?> >All</option>
                                            <?php while($row = mysqli_fetch_array($customers)){ ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($customer_id == $row['id']){ echo "selected"; } ?> ><?php echo $row['bname']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                              </div>
                              <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                        <label><?php echo getLange('customertype'); ?>  </label>
                                        <select class="form-control origin js-example-basic-single" name="customer_type">
                                            <option value="" <?php if($customer_type == ''){ echo "selected"; } ?> >All</option>
                                            <option value="9" <?php if($customer_type == '0'){ echo "selected"; } ?> >COD</option>
                                            <option value="1" <?php if($customer_type == '1'){ echo "selected"; } ?> >NON COD</option>
                                            <option value="2" <?php if($customer_type == '2'){ echo "selected"; } ?> >Corporate</option>
                                        </select>
                                    </div>
                              </div>
                              <div class="col-sm-2 left_right_none">
                                <div class="form-group">
                                        <label><?php echo getLange('paymentstatus'); ?></label>
                                        <select class="form-control origin js-example-basic-single" name="payment_status">
                                            <option value="" <?php if($payment_status == ''){ echo "selected"; } ?> >All</option>
                                            <option value="Paid" <?php if($payment_status == 'Paid'){ echo "selected"; } ?> >PAID</option>
                                            <option value="Pending" <?php if($payment_status == 'Pending'){ echo "selected"; } ?> >Pending</option>
                                            
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

                                        <label><?php echo getLange('selectider'); ?> </label>
                                        <select class="form-control courier js-example-basic-single" name="courier">
                                            <option value="" <?php if($active_courier == ''){ echo "selected"; } ?>>All</option>
                                            <?php while($row = mysqli_fetch_array($drivers)){ ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($active_courier == $row['id']){ echo "selected"; } ?>><?php echo $row['Name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                              </div>
                            <div class="col-sm-1 left_right_none">
                                <div class="form-group">
                                    <label><?php echo getLange('from'); ?></label>
                                    <input type="text" value="<?php echo $from; ?>" class="form-control datetimepicker4" name="from">
                                </div>
                            </div>
                            <div class="col-sm-1 left_right_none">
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
                                    <th><input type="checkbox" name="" class="main_select"></th>
                                    <th><?php echo getLange('trackingno'); ?> </th>
                                    <th><?php echo getLange('servicetype'); ?>  </th>
                                    <th><?php echo getLange('ordertype'); ?>  </th>
                                    <th><?php echo getLange('status'); ?></th>
                                    <th><?php echo getLange('orderdate'); ?> </th>
                                    <th><?php echo getLange('pickupname'); ?> </th>
                                    <th><?php echo getLange('pickupcompany'); ?> </th>
                                    <th><?php echo getLange('pickupphone'); ?> </th>
                                    <th><?php echo getLange('pickupaddress'); ?> </th>
                                    <th><?php echo getLange('deliveryname'); ?> </th>
                                    <th><?php echo getLange('deliveryphone'); ?> </th>
                                    <th><?php echo getLange('deliveryaddress'); ?> </th>
                                    <th><?php echo getLange('vendor'); ?></th>
                                    <th><?php echo getLange('orderstatus'); ?> </th>
                                    <th><?php echo getLange('pickupcity'); ?> </th>
                                    <th><?php echo getLange('deliverycity'); ?> </th>
                                    <th><?php echo getLange('refernceno'); ?></th>
                                    <th><?php echo getLange('orderid'); ?></th>
                                     <th><?php echo getLange('noofpiece'); ?></th>
                                     <th><?php echo getLange('parcelweight'); ?></th>
                                    <th><?php echo getLange('fragile'); ?></th>
                                    <th><?php echo getLange('insureditemdeclare'); ?></th>
                                    <th><?php echo getLange('codamount'); ?> </th>
                                     <th><?php echo getLange('deliveryfee'); ?> </th>
                                    <th><?php echo getLange('specialcharges'); ?> </th>
                                    <th><?php echo getLange('extra_charges'); ?> </th>
                                    <th><?php echo getLange('insurancepremium'); ?> </th>
                                    <th><?php echo getLange('grand_total_charges'); ?> </th>
                                    <th><?php echo getLange('fuelsurcharge'); ?> </th>
                                    <th><?php echo getLange('salestax'); ?> </th> 
                                    <th><?php echo getLange('inc_amount'); ?> </th>
                                    <th><?php echo getLange('netamount'); ?> </th>
                                    <th><?php echo getLange('paymentstatus'); ?> </th>
                                    <th><?php echo getLange('action'); ?></th>
                                </tr>

                            </thead>

                            <tbody>

                            <?php
                                // $query1 = '';
                                // if(isset($_SESSION['type']) && $_SESSION['type'] == 'admin') {
                                //  $query1=mysqli_query($con,"Select * from orders where status='delivered' order by id desc");
                                // } else {
                                //  if(isset($user->branch_id)) {
                                //      $query1=mysqli_query($con,"Select * from orders where status='pending'  order by id desc");
                                //  }
                                // }
                              $cod=0;
                               $deliveryfee=0;
                               $specialcharges=0;
                               $extracharges=0;
                               $insurancepremium=0;
                               $grandtotalcharges=0;
                               $fuelsurcharge=0;
                               $sales_tax=0;
                               $incamount=0;
                               $netamount=0;
                        function insurance($id)
                            {
                                global $con;
                                $branchQ = mysqli_query($con, "SELECT * from insurance_type where id = $id");
                                $res = mysqli_fetch_array($branchQ);
                                return $res['name'];
                            }
                                while($fetch1=mysqli_fetch_array($query1)){
                                    // if (empty($fetch1['sbname']))
                                    // {
                                    //     $company_name = mysqli_fetch_array(mysqli_query($con,"SELECT bname FROM customers WHERE id='".$fetch1['customer_id']."'  "));
                                    //     if (isset($company_name['bname']))
                                    //     {
                                    //         $fetch1['sbname'] = $company_name['bname'];
                                    //     }
                                    // }
                                   $cod +=$fetch1['collection_amount'];
                                   $deliveryfee +=$fetch1['price'];
                                   $specialcharges +=$fetch1['special_charges'];
                                   $extracharges +=$fetch1['extra_charges'];
                                   $insurancepremium +=$fetch1['insured_premium'];
                                   $grandtotalcharges +=$fetch1['grand_total_charges'];
                                   $fuelsurcharge +=$fetch1['fuel_surcharge'];
                                   $sales_tax +=$fetch1['pft_amount'];
                                   $incamount +=$fetch1['inc_amount'];
                                   $netamount +=$fetch1['net_amount'];
                                    $if_vendor = mysqli_fetch_array(mysqli_query($con,"SELECT Name FROM users WHERE id='".$fetch1['delivery_rider']."' AND user_role_id = '3'   "));

                                ?>



                                <tr class="gradeA odd" role="row">
                                    <td><input type="checkbox" name="" class="order_check" data-id="<?php echo $fetch1['id']; ?>"></td>
                                     <td class="sorting_1"><?php echo $fetch1['track_no']; ?></td>
                                    <td class="sorting_1"><?php echo $fetch1['order_type_name']; ?></td>
                                    <td class="sorting_1"><?php
                                    if($fetch1['booking_type'] == '2'){
                                        echo 'Cash' ;
                                        }elseif($fetch1['booking_type'] == '3'){
                                          echo  'To Pay';
                                        }else{
                                            echo 'Invoice';
                                        }?></td>
                                    <td class="sorting_1" style="text-transform: capitalize;"><?php echo getKeyWord($fetch1['status']); ?></td>
                                    <td class="center">
                                        <?php echo date(DATE_FORMAT,strtotime($fetch1['order_date'])); ?>
                                    </td>

                                    <td class="center">
                                        <?php echo $fetch1['sname']; ?>
                                    </td>
                                    <td class="center">
                                        <?php echo $fetch1['businessname']; ?>
                                    </td>
                                    <td class="center">
                                        <?php echo $fetch1['sphone']; ?>
                                    </td>
                                    <td class="center">
                                        <?php echo $fetch1['sender_address']; ?>
                                    </td>
                                    <td class="center">
                                     <?php echo $fetch1['rname']; ?>
                                    </td>
                                    <td class="center">
                                     <?php echo $fetch1['rphone']; ?>
                                    </td>
                                    <td class="center">
                                     <?php echo $fetch1['receiver_address']; ?>
                                    </td>
                                    <td>
                                        <?php echo isset($if_vendor['Name']) ? $if_vendor['Name'] : '';  ?>
                                    </td>

                                    <td>
                                        <?php if ($fetch1['status'] == 'Delivered' or $fetch1['status'] == 'Returned to Shipper'): ?>
                                            <?php echo 'Closed'; ?>
                                        <?php else: ?>
                                            <?php echo 'Open'; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                      <?php echo $fetch1['origin']; ?>
                                   </td>
                                   <td>
                                      <?php echo $fetch1['destination']; ?>
                                   </td> 
                                    <td>
                                      <?php echo $fetch1['ref_no']; ?>
                                   </td>
                                   <td>
                                      <?php echo $fetch1['product_id']; ?>
                                   </td>
                                   <td>
                                      <?php echo $fetch1['quantity']; ?>
                                   </td>
                                   <td>
                                      <?php echo $fetch1['weight']; ?>
                                   </td>
                                   <td>
                                      <?php echo insurance($fetch1['is_fragile']); ?>
                                   </td>
                                   <td>
                                      <?php echo $fetch1['insured_item_value']; ?>
                                   </td>
                                    <td>
                                         <?php echo number_format((float)$fetch1['collection_amount'],2); ?>
                                    </td>
                                     <td>
                                        <?php echo number_format((float)$fetch1['price'],2); ?>
                                    </td>
                                   <td>
                                        <?php echo number_format((float)$fetch1['special_charges'],2); ?>
                                    </td>
                                     <td>
                                         <?php echo number_format((float)$fetch1['extra_charges'],2); ?>
                                    </td>
                                      <td>
                                        <?php echo number_format((float)$fetch1['insured_premium'],2); ?>
                                    </td>
                                     <td>
                                         <?php echo number_format((float)$fetch1['grand_total_charges'],2); ?>
                                    </td>
                                     <td>
                                         <?php echo number_format((float)$fetch1['fuel_surcharge'],2); ?>
                                    </td>
                                    <td>
                                        <?php echo number_format((float)$fetch1['pft_amount'],2); ?>
                                    </td>
                                   
                                    
                                    
                                    <td>
                                         <?php echo number_format((float)$fetch1['inc_amount'],2); ?>
                                    </td>
                                    <td>
                                         <?php echo number_format((float)$fetch1['net_amount'],2); ?>
                                    </td>
                                   <td>
                                      <span class="btn btn-info"><?php if($fetch1['customer_type']=='1'  && $fetch1['payment_status']=='Paid' || $fetch1['customer_type']=='2'){
                                        echo 'Invoiced';
                                      }else{ echo $fetch1['payment_status'];
                                      } ?></span>
                                   </td>
                                   
                                    <td class="center action_btns" >
                                        <a href="order.php?id=<?php echo $fetch1['id']; ?>"> <i class="fa fa-eye" style="font-size: 14px;"></i></a>
                                        <a target="_blank" href="<?php echo BASE_URL ?>track-details.php?track_code=<?php echo $fetch1['track_no'] ?>"  > <i style="color: #da1414;font-size: 14px;" class="fa fa-trash"></i></a>
                                    </td>

                                </tr>

                                <?php

                                }



                                ?>

                            </tbody>
                              <tfoot>
                                <td></td>
                                <td colspan="12"><?php echo getLange('total'); ?></td>
                                <td colspan="9"></td>
                                <td><?php echo number_format((float)$cod , 2); ?></td>
                                <td><?php echo number_format((float)$deliveryfee,2); ?></td>
                                <td><?php echo number_format((float)$special_charges,2); ?></td>
                                <td><?php echo number_format((float)$extracharges,2); ?></td>
                                <td><?php echo number_format((float)$insurancepremium,2); ?></td>
                                <td><?php echo number_format((float)$grandtotalcharges,2); ?></td>
                                <td><?php echo number_format((float)$fuelsurcharge,2); ?></td>
                                <td><?php echo number_format((float)$sales_tax,2); ?></td>
                                <td><?php echo number_format((float)$incamount,2); ?></td>
                                <td><?php echo number_format((float)$netamount,2); ?></td>
                                
                                <td></td>
                                <td></td>
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
