 <div style="cursor: pointer;">
            <div class="sale_invoice ng-scope">
               <h3 class="parameters">Sender  Parameter<i class="fa fa-plus "></i></h3>
               <ul class="parameters_box" id="list_t_railway">
                   <li><a href="#">@Origin_City</a></li>
                  <li><a href="#">@Sender_Name</a></li>
                  <li><a href="#">@Sender_Phone</a></li>
                  <li><a href="#">@Sender_Address</a></li>
               </ul>
            </div>
            <div class="sale_invoice  ng-scope">
               <h3 class="parameters-1">Receiver Parameters <i class="fa fa-plus"></i></h3>
               <ul class="payment_box" id="list_t_railway">
                  <li><a href="#">@Destination_City</a></li>
                  <li><a href="#">@Receiver_Name</a></li>
                  <li><a href="#">@Receiver_Phone</a></li>
                  <li><a href="#">@Reciover_Email</a></li>
                  <li><a href="#">@Receiver_Address</a></li>
               </ul>
            </div>
            <div class="sale_invoice ng-scope">
               <h3 class="parameters-2">Shipment Parameters <i class="fa fa-plus"></i></h3>
               <ul class="key_box" id="list_t_railway">
                  <li><a href="#">@Tracking_NO</a></li>
                  <li><a href="#">@Item_Detail</a></li>
                  <li><a href="#">@Special_instruction</a></li>
                  <li><a href="#">@Reference_No</a></li>
                  <li><a href="#">@Order_id</a></li>
                  <li><a href="#">@No_of_pieces</a></li>
                  <li><a href="#">@Weight</a></li>
                  <li><a href="#">@COD_amount</a></li>
               </ul>
            </div>
             <div class="sale_invoice ng-scope">
               <h3 class="parameters-3">Status <i class="fa fa-plus"></i></h3>
               <ul class="key_box-1" id="list_t_railway" style="display: none;">
                  <li><a href="#">@Order_Status</a></li>
                  <li><a href="#">@Received_By</a></li>
               </ul>
            </div>
              <div class="sale_invoice ng-scope">
               <h3 class="parameters-4">Rider <i class="fa fa-plus"></i></h3>
               <ul class="key_box-2" id="list_t_railway" style="display: none;">
                  <li><a href="#">@Rider_Name</a></li>
                  <li><a href="#">@Rider_Phone</a></li>
                  <li><a href="#">@Rider_Location</a></li>
               </ul>
            </div>
            <div class="sale_invoice ng-scope">
               <h3 class="parameters-4">Tracking History <i class="fa fa-plus"></i></h3>
               <ul class="key_box-2" id="list_t_railway" style="display: none;">
                  <li><a href="#">@Tracking_History</a></li>
                  <li><a href="#">@Tracking_url</a></li>
               </ul>
            </div>
            


            <script type="text/javascript">
               document.addEventListener('DOMContentLoaded', function() {
                  $(".parameters").click(function(){
                    $(".parameters_box").toggle();
                  });
                  $(".parameters-1").click(function(){
                    $(".payment_box").toggle();
                  });
                  $(".parameters-2").click(function(){
                    $(".key_box").toggle();
                  });
                  $(".parameters-3").click(function(){
                    $(".key_box-1").toggle();
                  });
                  $(".parameters-4").click(function(){
                    $(".key_box-2").toggle();
                  });
                    
               }, false);
            </script>
         </div>