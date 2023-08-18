<div class="col-sm-12 outer_shadow table_template" >
   <div class="top_heading">
      <h3 class="ng-binding">SMS Templates</h3>
      <a href="template_form.php" class="btn btn-info" style="margin-top: -60px;margin-left: 785px;">Add New</a>
   </div>
   <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
      echo $_SESSION['msg'];
      unset($_SESSION['msg']);
   } ?>
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
         .template_content{
          padding: 7px 8px 10px;
          line-height: 16px;
          height: 24px;
          display: -webkit-box;
          max-width: 410px;
          -webkit-line-clamp: 2;
          -webkit-box-orient: vertical;
          overflow: hidden;
          text-overflow: ellipsis;
       }
      </style>
      <table class="table table-striped table-bordered  no-footer dtr-inline" id="templates_table">
        <div class="fake_loader" id="image" style="text-align: center;">
               <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;"> 
            </div>
      	<thead>
      		<th>srno</th>
      		<th>Code</th>
            <th>Name</th>
            <th>Template</th>
            <th>SMS Event</th>
            <th>Send to</th>
            <th>Created by</th>
      		<th>Status</th>
      		<th>Action</th>
      	</thead>
      
      </table>
</div>