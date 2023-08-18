<?php
   $flayer_query = mysqli_query($con,"SELECT * FROM flayers WHERE 1");
   $customer_query = mysqli_query($con,"SELECT * FROM customers WHERE status=1");
    ?>
<style type="text/css">
   .picker-switch .btn{
   display: none;
   }
   input::-webkit-outer-spin-button,
   input::-webkit-inner-spin-button {
   /* display: none; <- Crashes Chrome on hover */
   -webkit-appearance: none;
   margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
   }
   input[type=number] {
   -moz-appearance:textfield; /* Firefox */
   }
</style>
<form method="post" action="sell_flyer_save.php" id="flyer_save">
   <div class="row">
      <div class="col-md-3">
         <a href="add_flayer.php" style="    margin-bottom: 15px;" class="btn btn-info"><?php echo getLange('addnewflyer'); ?></a>
      </div>
   </div>
   <h3><?php echo getLange('sellflyers'); ?></h3>
   <div class="right_main">
   	<div id="cmsg"></div>
      <div class="row">
         <div class="col-md-8">
         </div>
         <div class="col-sm-4">
            <div class="row">
               <div class="col-md-12">
                  <div class="form-group">
                     <label><?php echo getLange('choosecustomer'); ?></label>
                     <select class="form-control " id="flyer_customer" name="customer" >
                         <option selected disabled value=""><?php echo getLange('select');?>...</option>
                        <?php while($row = mysqli_fetch_array($customer_query)){ ?>
                        <option value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>"><?php echo isset($row['fname']) ? $row['fname'].' ('.$row['bname'].')' : ''; ?></option>
                        <?php } ?>
                     </select>
                     
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <div class="form-group">
                     <label><?php echo getLange('date'); ?></label>
                     <input type="text" name="order_date" class="form-control datetimepicker4" value="<?php echo date('Y-m-d'); ?>">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <table class="table table-bordered " id="flayer_tbl">
         <thead>
            <tr>
               <th style="width: 35%;"><?php echo getLange('chooseflyer'); ?></th>
               <th style="width: 10%;"><?php echo getLange('price'); ?></th>
               <th style="width: 10%;"><?php echo getLange('qty'); ?></th>
               <th style="width: 20%;"><?php echo getLange('totalprice'); ?></th>
               <th style="width: 10%;"><?php echo getLange('action'); ?></th>
            </tr>
         </thead>
         <tbody>
            <tr>
               <td>
                  <select name="flayer[0][flayer_id]" class=" form-control choose_product triger_price">
                     <option selected disabled value=""><?php echo getLange('select');?>...</option>
                     <?php while($row=mysqli_fetch_array($flayer_query)){ ?>
                     <option value="<?php echo $row['id']; ?>"><?php echo $row['flayer_name']; ?></option>
                     <?php } ?>
                  </select>
                  <div id="msg"></div>
               </td>
               <td><input type="number" name="flayer[0][original_price]" class="form-control original_price triger_price"></td>
               <td><input type="number" name="flayer[0][qty]" class="form-control qty triger_price" value="1"></td>
               <td><input type="number" name="flayer[0][total_price]" class="form-control total_price triger_price"></td>
               <td><a href="#" class="btn btn-success btn_add"><i class="fa fa-plus"></i></a></td>
            </tr>
            <tr>
               <td colspan="3"></td>
               <td><strong><?php echo getLange('totalprice'); ?> : </strong><span id="sub_total"></span></td>
               <td></td>
            </tr>
         </tbody>
      </table>
      <input type="submit" name="save_order" class="btn btn-info save_order" value="<?php echo getLange('save').' '.getLange('orders'); ?>">
   </div>
</form>