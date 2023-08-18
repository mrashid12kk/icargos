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
                <div class="row" id="driver_search">
                    <div class="col-sm-5 trip_no">
                        <p>In Transit Trips</p>
                    </div>
                    <div class="col-sm-3 scan_id">
                        <div class="input_scanid">
                            <input type="text" placeholder="Search by trip id and driver name" name="">
                            <i class="fa fa-search"></i>
                        </div>
                    </div>
                    <div class="col-sm-4 trip_print">
                        <ul>
                            <li>
                                <p><input type="date" name=""></p>
                            </li>
                            <li>
                                <p><input type="date" name=""></p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="according_box" id="padding_area">
            <div class="row">
                <div class="col-sm-12 trip_summary_table" id="trips_userdetails">
                    <div class="inner__tabs">
                        <ul class="tabs__box">
                            <li class="tab-link current__box" data-tab="tab-1">
                                <div class="counter_box">
                                    <h4>30</h4>
                                    <p>All Trips</p>
                                </div>
                            </li>
                            <li class="tab-link" data-tab="tab-2">
                                <div class="counter_box">
                                    <h4>30</h4>
                                    <p>Yet to Start</p>
                                </div>
                            </li>
                            <li class="tab-link" data-tab="tab-3">
                                <div class="counter_box">
                                    <h4>00</h4>
                                    <p>At Pickup</p>
                                </div>
                            </li>
                            <li class="tab-link" data-tab="tab-4">
                                <div class="counter_box">
                                    <h4>00</h4>
                                    <p>In Transit</p>
                                </div>
                            </li>
                            <li class="tab-link" data-tab="tab-5">
                                <div class="counter_box">
                                    <h4>00</h4>
                                    <p>On Break</p>
                                </div>
                            </li>
                        </ul>

                        <div id="tab-1" class="tab-content__box current__box">
                            <div class="row order_summary_box">
                                <div class="col-sm-8 alert_map">
                                    <ul>
                                        <li><a href="#" class="active">30 All</a></li>
                                        <li><a href="#">0 Delayed</a></li>
                                        <li><a href="#">0 Early</a></li>
                                        <li><a href="#">30 On Time</a></li>
                                    </ul>
                                </div>
                                <div class="col-sm-4 total_nobox">
                                    <ul>
                                        <li><p><b>Orders Total:</b> <span>288</span></p></li>
                                        <li><p><b>Delivered:</b> <span>0</span></p></li>
                                        <li><p><b>Failed:</b> <span>0</span></p></li>
                                        <li><p><b>Pending:</b> <span>288</span></p></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="inner_table_wrap table-responsive">
                                <table class="table__box">
                                  <thead>
                                      <tr>
                                        <th>Trip</th>
                                        <th>Origin Hub</th>
                                        <th>Orders</th>
                                        <th>Status</th>
                                        <th>Trip Status</th>
                                        <th>Alerts</th>
                                        <th>Driver Details</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                        <td>
                                            <div class="trip_info">
                                                <h5>#3553236</h5>
                                                <p><span></span> Yet to Start</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <h5>Johanneshurg</h5>
                                                <p>Johanneshurg</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <p><b>0</b> / 1</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="orders__boxes">
                                                <ul>
                                                    <li class="delayed_color">0 Delayed</li>
                                                    <li class="early_color">0 Early</li>
                                                    <li>0 On Time</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>On Time</td>
                                        <td>
                                            <div class="alert_box">
                                                <a class="mismatch_btn" href="#">Mismatch Location</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="driver_details">
                                                <img src="https://a.icargos.com/portal/admin/assets/images/avatar.png" alt="">
                                                <h4>Skhumbuzo Nonyane</h4>
                                                <p>085123582855</p>
                                            </div>
                                        </td>
                                      </tr>
                                      <tr>
                                          <td colspan="12">
                                              <div class="map_view">
                                                  <h5><i class="fa fa-map-marker"></i> Map View</h5>
                                                  <div class="divider__box">
                                                      <i class="fa fa-bolt"></i>
                                                      <span class="inline_divider"></span>
                                                      <i class="fa fa-flag"></i>
                                                  </div>
                                                  <div class="counter_number_box">
                                                      <ul>
                                                          <li>
                                                              <div class="info_hoverbox">
                                                                  <p>UNI07734</p>
                                                                  <b>108.LMC</b>
                                                                  <b>Delivery</b>
                                                                  <p>Seq/Re-seq <span>1/-</span></p>
                                                                  <p>ETA <span>18.02</span></p>
                                                                  <p>ETC <span>18.02</span></p>
                                                                  <p>PTA <span>18.02</span></p>
                                                              </div>
                                                              <div class="list_no_counter">
                                                                  <p>18:02</p>
                                                                  <span>1</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                            <div class="info_hoverbox">
                                                                  <p>UNI07734</p>
                                                                  <b>108.LMC</b>
                                                                  <b>Delivery</b>
                                                                  <p>Seq/Re-seq <span>1/-</span></p>
                                                                  <p>ETA <span>18.02</span></p>
                                                                  <p>ETC <span>18.02</span></p>
                                                                  <p>PTA <span>18.02</span></p>
                                                              </div>
                                                              <div class="list_no_counter">
                                                                  <p>17:02</p>
                                                                  <span>2</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>16:02</p>
                                                                  <span>3</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>14:02</p>
                                                                  <span>4</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>15:02</p>
                                                                  <span>5</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>16:02</p>
                                                                  <span>6</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>17:02</p>
                                                                  <span>7</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>18:02</p>
                                                                  <span>8</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>19:02</p>
                                                                  <span>9</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>20:02</p>
                                                                  <span>10</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>21:02</p>
                                                                  <span>11</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>22:02</p>
                                                                  <span>12</span>
                                                              </div>
                                                          </li>
                                                      </ul>
                                                  </div>
                                              </div>
                                          </td>
                                      </tr>
                                      <tr>
                                        <td>
                                            <div class="trip_info">
                                                <h5>#3553236</h5>
                                                <p><span></span> Yet to Start</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <h5>Johanneshurg</h5>
                                                <p>Johanneshurg</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <p><b>0</b> / 1</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="orders__boxes">
                                                <ul>
                                                    <li class="delayed_color">0 Delayed</li>
                                                    <li class="early_color">0 Early</li>
                                                    <li>0 On Time</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>On Time</td>
                                        <td></td>
                                        <td>
                                            <div class="driver_details">
                                                <img src="https://a.icargos.com/portal/admin/assets/images/avatar.png" alt="">
                                                <h4>Skhumbuzo Nonyane</h4>
                                                <p>085123582855</p>
                                            </div>
                                        </td>
                                      </tr>
                                      <tr>
                                          <td colspan="12">
                                              <div class="map_view">
                                                  <h5><i class="fa fa-map-marker"></i> Map View</h5>
                                                  <div class="divider__box">
                                                      <i class="fa fa-bolt"></i>
                                                      <span class="inline_divider"></span>
                                                      <i class="fa fa-flag"></i>
                                                  </div>
                                                  <div class="counter_number_box">
                                                      <ul>
                                                          <li>
                                                              <div class="info_hoverbox">
                                                                  <p>UNI07734</p>
                                                                  <b>108.LMC</b>
                                                                  <b>Delivery</b>
                                                                  <p>Seq/Re-seq <span>1/-</span></p>
                                                                  <p>ETA <span>18.02</span></p>
                                                                  <p>ETC <span>18.02</span></p>
                                                                  <p>PTA <span>18.02</span></p>
                                                              </div>
                                                              <div class="list_no_counter">
                                                                  <p>18:02</p>
                                                                  <span>1</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                            <div class="info_hoverbox">
                                                                  <p>UNI07734</p>
                                                                  <b>108.LMC</b>
                                                                  <b>Delivery</b>
                                                                  <p>Seq/Re-seq <span>1/-</span></p>
                                                                  <p>ETA <span>18.02</span></p>
                                                                  <p>ETC <span>18.02</span></p>
                                                                  <p>PTA <span>18.02</span></p>
                                                              </div>
                                                              <div class="list_no_counter">
                                                                  <p>17:02</p>
                                                                  <span>2</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>16:02</p>
                                                                  <span>3</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>14:02</p>
                                                                  <span>4</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>15:02</p>
                                                                  <span>5</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>16:02</p>
                                                                  <span>6</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>17:02</p>
                                                                  <span>7</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>18:02</p>
                                                                  <span>8</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>19:02</p>
                                                                  <span>9</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>20:02</p>
                                                                  <span>10</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>21:02</p>
                                                                  <span>11</span>
                                                              </div>
                                                          </li>
                                                          <li>
                                                              <div class="list_no_counter">
                                                                  <p>22:02</p>
                                                                  <span>12</span>
                                                              </div>
                                                          </li>
                                                      </ul>
                                                  </div>
                                              </div>
                                          </td>
                                      </tr>
                                      <tr>
                                        <td>
                                            <div class="trip_info">
                                                <h5>#3553236</h5>
                                                <p><span></span> Yet to Start</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <h5>Johanneshurg</h5>
                                                <p>Johanneshurg</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <p><b>0</b> / 1</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="orders__boxes">
                                                <ul>
                                                    <li class="delayed_color">0 Delayed</li>
                                                    <li class="early_color">0 Early</li>
                                                    <li>0 On Time</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>On Time</td>
                                        <td>
                                            <div class="alert_box">
                                                <a class="mismatch_btn" href="#">Mismatch Location</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="driver_details">
                                                <img src="https://a.icargos.com/portal/admin/assets/images/avatar.png" alt="">
                                                <h4>Skhumbuzo Nonyane</h4>
                                                <p>085123582855</p>
                                            </div>
                                        </td>
                                      </tr>

                                      <tr>
                                        <td>
                                            <div class="trip_info">
                                                <h5>#3553236</h5>
                                                <p><span></span> Yet to Start</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <h5>Johanneshurg</h5>
                                                <p>Johanneshurg</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <p><b>0</b> / 1</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="orders__boxes">
                                                <ul>
                                                    <li class="delayed_color">0 Delayed</li>
                                                    <li class="early_color">0 Early</li>
                                                    <li>0 On Time</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>On Time</td>
                                        <td></td>
                                        <td>
                                            <div class="driver_details">
                                                <img src="https://a.icargos.com/portal/admin/assets/images/avatar.png" alt="">
                                                <h4>Skhumbuzo Nonyane</h4>
                                                <p>085123582855</p>
                                            </div>
                                        </td>
                                      </tr>

                                      <tr>
                                        <td>
                                            <div class="trip_info">
                                                <h5>#3553236</h5>
                                                <p><span></span> Yet to Start</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <h5>Johanneshurg</h5>
                                                <p>Johanneshurg</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <p><b>0</b> / 1</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="orders__boxes">
                                                <ul>
                                                    <li class="delayed_color">0 Delayed</li>
                                                    <li class="early_color">0 Early</li>
                                                    <li>0 On Time</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>On Time</td>
                                        <td>
                                            <div class="alert_box">
                                                <a class="mismatch_btn" href="#">Mismatch Location</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="driver_details">
                                                <img src="https://a.icargos.com/portal/admin/assets/images/avatar.png" alt="">
                                                <h4>Skhumbuzo Nonyane</h4>
                                                <p>085123582855</p>
                                            </div>
                                        </td>
                                      </tr>

                                      <tr>
                                        <td>
                                            <div class="trip_info">
                                                <h5>#3553236</h5>
                                                <p><span></span> Yet to Start</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <h5>Johanneshurg</h5>
                                                <p>Johanneshurg</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <p><b>0</b> / 1</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="orders__boxes">
                                                <ul>
                                                    <li class="delayed_color">0 Delayed</li>
                                                    <li class="early_color">0 Early</li>
                                                    <li>0 On Time</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>On Time</td>
                                        <td></td>
                                        <td>
                                            <div class="driver_details">
                                                <img src="https://a.icargos.com/portal/admin/assets/images/avatar.png" alt="">
                                                <h4>Skhumbuzo Nonyane</h4>
                                                <p>085123582855</p>
                                            </div>
                                        </td>
                                      </tr>

                                      <tr>
                                        <td>
                                            <div class="trip_info">
                                                <h5>#3553236</h5>
                                                <p><span></span> Yet to Start</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <h5>Johanneshurg</h5>
                                                <p>Johanneshurg</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <p><b>0</b> / 1</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="orders__boxes">
                                                <ul>
                                                    <li class="delayed_color">0 Delayed</li>
                                                    <li class="early_color">0 Early</li>
                                                    <li>0 On Time</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>On Time</td>
                                        <td>
                                            <div class="alert_box">
                                                <a class="mismatch_btn" href="#">Mismatch Location</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="driver_details">
                                                <img src="https://a.icargos.com/portal/admin/assets/images/avatar.png" alt="">
                                                <h4>Skhumbuzo Nonyane</h4>
                                                <p>085123582855</p>
                                            </div>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>
                                            <div class="trip_info">
                                                <h5>#3553236</h5>
                                                <p><span></span> Yet to Start</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <h5>Johanneshurg</h5>
                                                <p>Johanneshurg</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <p><b>0</b> / 1</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="orders__boxes">
                                                <ul>
                                                    <li class="delayed_color">0 Delayed</li>
                                                    <li class="early_color">0 Early</li>
                                                    <li>0 On Time</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>On Time</td>
                                        <td>
                                            <div class="alert_box">
                                                <a class="mismatch_btn" href="#">Mismatch Location</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="driver_details">
                                                <img src="https://a.icargos.com/portal/admin/assets/images/avatar.png" alt="">
                                                <h4>Skhumbuzo Nonyane</h4>
                                                <p>085123582855</p>
                                            </div>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>
                                            <div class="trip_info">
                                                <h5>#3553236</h5>
                                                <p><span></span> Yet to Start</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <h5>Johanneshurg</h5>
                                                <p>Johanneshurg</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="origin_hub">
                                                <p><b>0</b> / 1</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="orders__boxes">
                                                <ul>
                                                    <li class="delayed_color">0 Delayed</li>
                                                    <li class="early_color">0 Early</li>
                                                    <li>0 On Time</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>On Time</td>
                                        <td></td>
                                        <td>
                                            <div class="driver_details">
                                                <img src="https://a.icargos.com/portal/admin/assets/images/avatar.png" alt="">
                                                <h4>Skhumbuzo Nonyane</h4>
                                                <p>085123582855</p>
                                            </div>
                                        </td>
                                      </tr>
                                      
                                  </tbody>
                                  
                                </table>
                            </div>
                        </div>
                        <div id="tab-2" class="tab-content__box"></div>
                        <div id="tab-3" class="tab-content__box"></div>
                        <div id="tab-4" class="tab-content__box"></div>
                        <div id="tab-5" class="tab-content__box"></div>


                    </div>
                </div>
            </div>
        </div>


        </div>


        

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