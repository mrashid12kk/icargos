<?php
   session_start();
   require 'includes/conn.php';
   if (isset($_SESSION['users_id']) && ($_SESSION['type'] !== 'driver' && $_SESSION['type'] == 'admin')) {
       require_once "includes/role_helper.php";
       if (!checkRolePermission($_SESSION['user_role_id'], 8, 'add_only', $comment = null)) {
   
           header("location:access_denied.php");
       }
       include "includes/header.php";
   
       ?>
<body data-ng-app>
   <?php
      include "includes/sidebar.php";
      
      ?>
   <!-- Aside Ends-->
   <section class="content">
   <?php
      include "includes/header2.php";
      
      ?>
      <style type="text/css">.container-fluid.footer{display: none;}</style>
   <!-- Header Ends -->
    <div class="warper container-fluid">

        <div class="inner_gapp">
            <div class="top_bar_trip">
                <div class="row">
                    <div class="col-sm-6 trip_no">
                        <p><a href="#">Trips</a> <span>/40112623</span></p>
                    </div>
                    <div class="col-sm-6 trip_print">
                        <ul>
                            <li>
                                <p><a href="#"><i class="fa fa-check"></i> Change Trip Driver</a></p>
                            </li>
                            <li>
                                <p><a href="#"><i class="fa fa-print"></i> Print</a></p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="according_box" id="padding_area">
            <div class="row">
                <div class="col-sm-3 according_info">
                    <div class="accordion-items">
                        <div class="accordion-heading">
                            <h4>Basic Details</h4>
                        </div>
                        <div class="accordion-content">
                            <ul>
                                <li><p>City > Branch <span>Johanneshurg > Johanneshurg</span></p> </li>
                                <li><p>Trip Start Date <span>19-02-2023</span></p> </li>
                                <li><p>Current Trip State <span><i class="fa fa-circle"></i>Yet to Start</span></p> </li>
                                <li><p>Current Status <span>On Time</span></p> </li>
                            </ul>
                        </div>

                        <div class="accordion-heading">
                            <h4>Total Orders (46)</h4>
                        </div>
                        <div class="accordion-content">
                            <ul>
                                <li><p>City > Branch <span>Johanneshurg > Johanneshurg</span></p> </li>
                                <li><p>Trip Start Date <span>19-02-2023</span></p> </li>
                                <li><p>Current Trip State <span>Yet to Start</span></p> </li>
                                <li><p>Current Status <span>On Time</span></p> </li>
                            </ul>
                        </div>

                        <div class="accordion-heading">
                            <h4>Trip Schedule - Planned / Actual</h4>
                        </div>
                        <div class="accordion-content">
                            <ul>
                                <li><p>City > Branch <span>Johanneshurg > Johanneshurg</span></p> </li>
                                <li><p>Trip Start Date <span>19-02-2023</span></p> </li>
                                <li><p>Current Trip State <span>Yet to Start</span></p> </li>
                                <li><p>Current Status <span>On Time</span></p> </li>
                            </ul>
                        </div>

                        <div class="accordion-heading">
                            <h4>Driver Details</h4>
                        </div>
                        <div class="accordion-content">
                            <ul>
                                <li><p>City > Branch <span>Johanneshurg > Johanneshurg</span></p> </li>
                                <li><p>Trip Start Date <span>19-02-2023</span></p> </li>
                                <li><p>Current Trip State <span>Yet to Start</span></p> </li>
                                <li><p>Current Status <span>On Time</span></p> </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 trip_summary_table">
                    <div class="inner__tabs">
                        <div class="row order_summary_box">
                            <div class="col-sm-8 alert_map">
                                <ul>
                                    <li><a href="#" class="active">Order Summary</a></li>
                                    <li><a href="#">Alerts</a></li>
                                    <li><a href="#">Map</a></li>
                                </ul>
                            </div>
                            <div class="col-sm-4 scan_id">
                                <div class="input_scanid">
                                    <input type="text" placeholder="Scan or enter reference id" name="">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div>
                        </div>

                        <ul class="tabs__box">
                            <li class="tab-link current__box" data-tab="tab-1">Pending (46)</li>
                            <li class="tab-link" data-tab="tab-2">Failed (0)</li>
                            <li class="tab-link" data-tab="tab-3">Successful (0)</li>
                        </ul>

                        <div id="tab-1" class="tab-content__box current__box">
                            <div class="inner_table_wrap table-responsive">
                                <h4>108. LMC Delivery</h4>
                                <table class="table__box">
                                  <thead>
                                      <tr>
                                        <th><input type="checkbox" class="checkbox_id" name=""></th>
                                        <th>Reference No</th>
                                        <th>SEQ</th>
                                        <th>RE-SEQ</th>
                                        <th>Status</th>
                                        <th>Consignee Name</th>
                                        <th>Contact No</th>
                                        <th>Job ETA</th>
                                        <th>Address</th>
                                        <th>Failure Reasons</th>
                                        <th>ODOMETER Start Reading</th>
                                        <th>End ODOMETER Reading</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>1</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>2</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>3</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>4</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>5</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>6</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>7</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>8</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>9</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>10</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>11</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>12</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>13</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>14</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>15</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>16</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>17</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>18</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>19</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>20</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                  </tbody>
                                  
                                </table>
                            </div>
                        </div>
                        <div id="tab-2" class="tab-content__box">
                            <div class="inner_table_wrap table-responsive">
                                <h4>108. LMC Delivery</h4>
                                <table class="table__box">
                                  <thead>
                                      <tr>
                                        <th><input type="checkbox" class="checkbox_id" name=""></th>
                                        <th>Reference No</th>
                                        <th>SEQ</th>
                                        <th>RE-SEQ</th>
                                        <th>Status</th>
                                        <th>Consignee Name</th>
                                        <th>Contact No</th>
                                        <th>Job ETA</th>
                                        <th>Address</th>
                                        <th>Failure Reasons</th>
                                        <th>ODOMETER Start Reading</th>
                                        <th>End ODOMETER Reading</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>1</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>2</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>3</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td><input type="checkbox" class="checkbox_id" name=""></td>
                                        <td><a href="#">UN107434</a></td>
                                        <td>4</td>
                                        <td><input type="text" placeholder="" class="input_fieldbox" name=""></td>
                                        <td>Pending</td>
                                        <td>Tsukudu</td>
                                        <td>27852574569</td>
                                        <td></td>
                                        <td>38 Zone 3 Sebokeng 12 Njozela</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      </tr>
                                      
                                  </tbody>
                                  
                                </table>
                            </div>
                        </div>
                        <div id="tab-3" class="tab-content__box">
                            <div class="inner_table_wrap table-responsive">
                                <h4>108. LMC Delivery</h4>
                                <table class="table__box">
                                  <thead>
                                      <tr>
                                        <th>Reference No</th>
                                        <th>SEQ</th>
                                        <th>RE-SEQ</th>
                                        <th>Status</th>
                                        <th>Job Location</th>
                                        <th>Job ETA</th>
                                        <th>Job Master</th>
                                        <th>FE Name</th>
                                        <th>Sequence</th>
                                        <th>FE Code</th>
                                        <th>Transaction Location</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>1</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>

                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>3</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>4</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>5</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>6</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>7</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>8</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>9</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>10</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>11</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>12</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>13</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>14</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>15</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>16</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>17</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>18</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>19</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>20</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>21</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>22</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      <tr>
                                        <td><a href="#">UN107434</a></td>
                                        <td>23</td>
                                        <td>0</td>
                                        <td>Pickup Success</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                        <td></td>
                                        <td>103. Bulk Pickup</td>
                                        <td>Tshepo Ventre</td>
                                        <td>1</td>
                                        <td>Tsherpov_madi</td>
                                        <td style="text-align: center;"><i class="fa fa-map-marker"></i></td>
                                      </tr>
                                      
                                  </tbody>
                                  
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>


        </div>


        

    </div>

    <div class="bottom_fixed_bar">
        <ul>
            <li><a href="#" class="re_sbtn">Re-sequence</a></li>
            <li><a href="#" class="move_uns_btn">Move To Unassign </a></li>
            <li><a href="#" class="change_d_btn">Change Driver  </a></li>
            <li class="end_tripbox"><a href="#" class="end_trip">End Trip</a></li>
        </ul>
    </div>

<?php
  include "includes/footer.php";
  } 
  else {
     header("location:index.php");
  }
?>

<script type="text/javascript">
    $(document).ready(function () {
    $(".accordion-items").on("click", ".accordion-heading", function () {
        $(this).toggleClass("active").next().slideToggle();

        $(".accordion-content").not($(this).next()).slideUp(300);

        $(this).siblings().removeClass("active");
    });
});

</script>

<script type="text/javascript">
    $(document).ready(function(){
    
    $('ul.tabs__box li').click(function(){
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs__box li').removeClass('current__box');
        $('.tab-content__box').removeClass('current__box');

        $(this).addClass('current__box');
        $("#"+tab_id).addClass('current__box');
    })

})
</script>