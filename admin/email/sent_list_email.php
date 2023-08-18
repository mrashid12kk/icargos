<style>
      md-table-container thead input {
      width: 100%;
      }
      .overaly_container.list_popup_box {
      max-width: 99% !important;
      }
      table.md-table th.md-column md-icon {
      height: 12px;
      width: 9px;
      font-size: 9px !important;
      line-height: 16px !important;
      }
      .list_popup_box table.md-table td.md-cell, .list_popup_box table.md-table th.md-column {
      padding: 2px 3px !important;
      }
      md-icon svg {
      width: 12px;
      vertical-align: middle;
      margin-top: 1px;
      }
      .md-row th:nth-child(2) {
      width: 46px !important;
      }
      md-icon {
      height: 6px;
      width: 6px;
      min-height: 6px;
      min-width: 6px;
      }
      table.md-table td.md-cell {
      font-size: 11px;
      }
      table.md-table th.md-column {
      font-size: 10px;
      }
      md-table-container table.md-table thead.md-head > tr.md-row {
      height: 30px;
      }
      md-table-pagination .md-button md-icon {
      color: #000;
      }
      md-table-pagination .md-button[disabled] md-icon {
      color: #6d6d6d;
      }
      md-table-container img {
      height: 35px;
      }
      table.md-table th.md-column md-icon:not(:first-child) {
      margin-left: 0;
      }
   </style>
<div class="col-sm-12 outer_shadow table_template">
   <div class="top_heading">
      <h3 class="">Filters</h3>
     <!--  <a href="single_email.php" class="btn btn-info" style="margin-top: -60px;margin-left: 785px;">Add New</a> -->
   </div>
   <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
      echo $_SESSION['msg'];
      unset($_SESSION['msg']);
   } 
   if (isset($_SESSION['email_msg']) && !empty($_SESSION['email_msg'])) {
      echo $_SESSION['email_msg'];
      unset($_SESSION['email_msg']);
   } 

    $from=date('Y-m-d');
   $to=date('Y-m-d');
   ?>
   <div class="row">
   
      <div class="col-sm-3 form_box">
         <label for="" class="">Date Form</label>
         <div class="form-group">
            <input id="date_from" type="text" placeholder="Enter date" value="<?php echo $from; ?>" class="datetimepicker4">
         </div>
      </div>
      <div class="col-sm-3 form_box">
         <label for="" class="">Date To</label>
         <div class="form-group">
            <input id="date_to" type="text" placeholder="Enter date" value="<?php echo $to; ?>" class="datetimepicker4">
         </div>
      </div>
      <div class="col-sm-3 send_btn">
         <button class="send_button" id="search_button" type="button">Search</button>
      </div>
   </div>
   <erp-table style="width: 100%;" erp-row-selection-disabled="1" erp-do-action="doAction" erp-options="tableOptions" erp-get-records="loadItems" erp-headers="headers" class="ng-isolate-scope">
   <md-table-container>
      <table id="sms_table" class="ng-pristine ng-untouched ng-valid md-table ng-isolate-scope ng-not-empty table-bordered" >
         <div class="fake_loader" id="image" style="text-align: center;">
               <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;"> 
            </div>
         <thead class="">
            <tr md-row="" class="">
               <th class="">Sr No.</th>
               <th class=""><span class="">Receiver Email</span></th>
               <th class=""><span class="">Message</span></th>
               <th class=""><span class="">Send To</span></th>
               <th class=""><span class="">DateTime</span></th>
               <th class=""><span class="">SMS EVENTS</span></th>
               <th class=""><span class="">Status</span></th>
               <th class=""><span class="">Actions</span></th>
            </tr>
            <tr class="md-row">
               <th class="">
               </th>
               <th class=""><input autocomplete="off"  type="text" class="" id="number">
               </th>
               <th class=""><input autocomplete="off"  type="text" class="" id="message">
               </th>
               <th class=""><input autocomplete="off"  type="text" class="" id="send_to">
               </th>
               <th class=""><input autocomplete="off" type="text" class="datetimepicker4 hidden" id="datetime" >
               </th>
               <th class="">
               </th>
               <th class="">
               </th>
               <th class="">
               </th>
            </tr>
         </thead>
          </md-table-container>
      </table>
      <form method="post" id="delete_form" action="delete_sms.php">
          <input type="hidden" name="all_email" id="delete_email" >
          <input type="hidden" name="delete_email_all">
      </form>
   </table>
</div>