<?php
// c
session_start();
include_once "includes/conn.php";
$page_title = 'Package Tracking';
include "includes/header.php";
$current = basename($_SERVER['PHP_SELF']);
?>
<style type="text/css">
.menu-bar {
    border-bottom: 1px solid #ccc9;
}

@media(max-width: 767px) {
    .container {
        width: auto;
    }

    .navbar-logo {
        margin-top: 0;
    }
}
</style>

<div class="main_body register_main track_shipment_page" id="desktop_view">
    <div class="login_screen">
        <div class="track_shipments pb-2">
            <div class="form_box get_quote   ">
                <input type="search" placeholder="Search">
                <svg class="search_box" viewBox="0 0 24 24">
                    <path
                        d="M9.5 4a6.5 6.5 0 0 1 4.932 10.734l5.644 5.644l-.707.707l-5.645-5.645A6.5 6.5 0 1 1 9.5 4zm0 1a5.5 5.5 0 1 0 0 11a5.5 5.5 0 0 0 0-11z"
                        fill="#626262"></path>
                </svg>
            </div>
        </div>
        <div class="main_table_fix delivery_table_">
            <table class="resposive_table">

                <tbody>
                    <?php
                    $id = $_SESSION['consignee_id'];
                    //print_r($id);
                    //die;
                    $sql = "SELECT orders.origin,orders.destination, orders.sname,orders.rname,orders.rphone,orders.remail,orders.receiver_address, consignee_records.track_no,consignee_records.id,orders.received_by, orders.status, orders.sbname, orders.sender_address, orders.semail, orders.collection_amount, consignee_records.created_at from orders join consignee_records on consignee_records.track_no = orders.track_no Where consignee_records.consignee_id = '" . $_SESSION['consignee_id'] . "' order by id desc";


                    $query = mysqli_query($con, $sql);
                    while ($row = mysqli_fetch_assoc($query)) {

                        // echo "<pre>";
                        // print_r($row);
                        // die;                    
                    ?>
                    <tr>
                        <td data-label="<?php echo $row['track_no'] ?>"><?php echo $row['created_at'] ?></td>
                        <td class="deliver_icon" data-label=" Shipper detail ">
                            <div class="delivery_icon_list">
                                <svg>
                                    <path
                                        d="M6 5h2.5a3 3 0 1 1 6 0H17a3 3 0 0 1 3 3v11a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V8a3 3 0 0 1 3-3zm0 1a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-1v3H7V6H6zm2 2h7V6H8v2zm3.5-5a2 2 0 0 0-2 2h4a2 2 0 0 0-2-2zm5.65 8.6L10 18.75l-3.2-3.2l.707-.707L10 17.336l6.442-6.443l.707.707z"
                                        fill="#626262"></path>
                                </svg>
                            </div>
                            <!-- <div class="delivery_icon_info">
                <svg><path d="M5.5 14a2.5 2.5 0 0 1 2.45 2H15V6H4a2 2 0 0 0-2 2v8h1.05a2.5 2.5 0 0 1 2.45-2zm0 5a2.5 2.5 0 0 1-2.45-2H1V8a3 3 0 0 1 3-3h11a1 1 0 0 1 1 1v2h3l3 3.981V17h-2.05a2.5 2.5 0 0 1-4.9 0h-7.1a2.5 2.5 0 0 1-2.45 2zm0-4a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm12-1a2.5 2.5 0 0 1 2.45 2H21v-3.684L20.762 12H16v2.5a2.49 2.49 0 0 1 1.5-.5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zM16 9v2h4.009L18.5 9H16z" fill="#626262"></path></svg>
              </div> -->
                            <i class="fa fa-bars" id="table_btn_menu" data-type="<?php echo $row['track_no'] ?>"></i>
                            <div class="main_wrapper_table<?php echo $row['track_no'] ?>" style="display: none;">
                                <ul class="fix_ul_li">
                                    <li><b> Pickup City <span class="float-right">
                                                :</span></b><span><?php echo $row['origin'] ?></span></li>
                                    <li><b>Account Name <span class="float-right">
                                                :</span></b><span><?php echo $row['sname'] ?></span></li>
                                    <li><b>Business Name <span class="float-right"> :</span></b>
                                        <span><?php echo isset($row['sbname']) ? $row['sbname'] : " "; ?></span>
                                    </li>
                                    <li><b>Email <span class="float-right"> :</span></b>
                                        <span><?php echo $row['semail'] ?></span>
                                    </li>
                                    <li><b>Address <span class="float-right"> :</span></b>
                                        <span><?php echo $row['sender_address'] ?></span>
                                    </li>
                                </ul>
                            </div>
                        </td>

                        <!-- Delivery details -->

                        <td class="deliver_icon" data-label=" Delivery detail ">
                            <div class="delivery_icon_list">
                                <svg>
                                    <path
                                        d="M6 5h2.5a3 3 0 1 1 6 0H17a3 3 0 0 1 3 3v11a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V8a3 3 0 0 1 3-3zm0 1a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-1v3H7V6H6zm2 2h7V6H8v2zm3.5-5a2 2 0 0 0-2 2h4a2 2 0 0 0-2-2zm5.65 8.6L10 18.75l-3.2-3.2l.707-.707L10 17.336l6.442-6.443l.707.707z"
                                        fill="#626262"></path>
                                </svg>
                            </div>
                            <div class="delivery_icon_info">
                                <svg>
                                    <path
                                        d="M5.5 14a2.5 2.5 0 0 1 2.45 2H15V6H4a2 2 0 0 0-2 2v8h1.05a2.5 2.5 0 0 1 2.45-2zm0 5a2.5 2.5 0 0 1-2.45-2H1V8a3 3 0 0 1 3-3h11a1 1 0 0 1 1 1v2h3l3 3.981V17h-2.05a2.5 2.5 0 0 1-4.9 0h-7.1a2.5 2.5 0 0 1-2.45 2zm0-4a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm12-1a2.5 2.5 0 0 1 2.45 2H21v-3.684L20.762 12H16v2.5a2.49 2.49 0 0 1 1.5-.5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zM16 9v2h4.009L18.5 9H16z"
                                        fill="#626262"></path>
                                </svg>
                            </div>
                            <i class="fa fa-bars" id="table_btn_menuu" data-type="<?php echo $row['track_no'] ?>"></i>
                            <div class="main_wrapper_tablee<?php echo $row['track_no'] ?>" style="display: none;">
                                <ul class="fix_ul_li">
                                    <li><b> Deliver City <span class="float-right">
                                                :</span></b><span><?php echo isset($row['destination']) ? $row['destination'] : " "; ?></span>
                                    </li>
                                    <li><b>Consignee: <span class="float-right"> :</span></b>
                                        <span><?php echo isset($row['rname']) ? $row['rname'] : " "; ?></span>
                                    </li>
                                    <?php if(isset($row['received_by']) && !empty($row['received_by'])):?>
                                    <li><b>Receiver Name: <span class="float-right"> :</span></b>
                                        <span><?php echo isset($row['received_by']) ? $row['received_by'] : " "; ?></span>
                                    </li>
                                    <?php endif;?>
                                    <li><b>Address <span class="float-right"> :</span></b>
                                        <span><?php echo isset($row['receiver_address']) ? $row['receiver_address'] : " "; ?></span>
                                    </li>
                                </ul>
                            </div>
                        </td>

                        <td data-label="COD">
                            <div class="delivery_icon_list cod_list">
                                <svg>
                                    <path
                                        d="M5.5 14a2.5 2.5 0 0 1 2.45 2H15V6H4a2 2 0 0 0-2 2v8h1.05a2.5 2.5 0 0 1 2.45-2zm0 5a2.5 2.5 0 0 1-2.45-2H1V8a3 3 0 0 1 3-3h11a1 1 0 0 1 1 1v2h3l3 3.981V17h-2.05a2.5 2.5 0 0 1-4.9 0h-7.1a2.5 2.5 0 0 1-2.45 2zm0-4a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zm12-1a2.5 2.5 0 0 1 2.45 2H21v-3.684L20.762 12H16v2.5a2.49 2.49 0 0 1 1.5-.5zm0 1a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3zM16 9v2h4.009L18.5 9H16z"
                                        fill="#626262"></path>
                                </svg>
                            </div>
                            <?php echo $row['collection_amount'] ?>
                        </td>
                        <td class="Pending_approval" data-label=""><a target="_blank" href="tracking.php"
                                class="track_btn"><?php echo getLange('tracking'); ?></a>
                            <h6><?php echo $row['status'] ?></h6>
                        </td>

                    </tr>

                    <?php
                    }
                    ?>

                </tbody>

            </table>
        </div>
    </div>
</div>


<?php

include "includes/footer.php";

// }

?>


<script>
$(document).on("click", "#table_btn_menu", function() {
    var code = $(this).attr("data-type");
    $(".main_wrapper_table" + code).toggle();
});
$(document).on("click", "#table_btn_menuu", function() {
    var code = $(this).attr("data-type");
    $(".main_wrapper_tablee" + code).toggle();
});
$("#table_btn_menu2").click(function() {
    $(".main_wrapper_table2").toggle();
});
$("#table_btn_menu3").click(function() {
    $(".main_wrapper_table3").toggle();
});
$("#table_btn_menu4").click(function() {
    $(".main_wrapper_table4").toggle();
});
$("#table_btn_menu5").click(function() {
    $(".main_wrapper_table5").toggle();
});
$("#table_btn_menu6").click(function() {
    $(".main_wrapper_table6").toggle();
});
</script>