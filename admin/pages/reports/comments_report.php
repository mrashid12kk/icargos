<style>
.row {
    margin: 0 !important;
}

.table-responsive {
    padding: 0 !important;
}

.col-md-2 {
    padding-right: 20px !important;
}

.modal_custom {
    position: absolute;
    z-index: 10000;
    /* 1 */
    top: 0;
    left: 0;
    visibility: hidden;
    width: 100%;
    height: 100%;
}

.modal_custom.is-visible {
    visibility: visible;
}

.modal-overlay_cust {
    position: fixed;
    z-index: 10;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: hsla(0, 0%, 0%, 0.5);
    visibility: hidden;
    opacity: 0;
    transition: visibility 0s linear 0.3s, opacity 0.3s;
}

.modal_custom.is-visible .modal-overlay_cust {
    opacity: 1;
    visibility: visible;
    transition-delay: 0s;
}

.modal_wrapper {
    position: absolute;
    z-index: 9999;
    top: 6em;
    left: 50%;
    width: 32em;
    margin-left: -16em;
    background-color: #fff;
    box-shadow: 0 0 1.5em hsla(0, 0%, 0%, 0.35);
}

.modal_transition {
    transition: all 0.3s 0.12s;
    transform: translateY(-10%);
    opacity: 0;
}

.modal_custom.is-visible .modal_transition {
    transform: translateY(0);
    opacity: 1;
}

.modal_header,
.modal_content {
    padding: 1em;
}

.modal_header {
    position: relative;
    background-color: #fff;
    box-shadow: 0 1px 2px hsla(0, 0%, 0%, 0.06);
    border-bottom: 1px solid #e8e8e8;
}

.modal_close {
    position: absolute;
    top: 0;
    right: 0;
    padding: 1em;
    color: #aaa;
    background: none;
    border: 0;
}

.modal_close:hover {
    color: #777;
}

.wrapper {
    width: 90%;
    max-width: 800px;
    margin: 4em auto;
    text-align: center;
}

.modal_heading {
    font-size: 1.125em;
    margin: 0;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.modal_content .popup_data {
    padding: 20px 0;
}

.modal_content .main_head b {
    font-size: 14px;
    padding: 3px 0;
    display: block;
}

.modal_content .main_contents p {
    padding: 3px 0;
    font-size: 14px;
}

.modal_content>*:first-child {
    margin-top: 0;
}

.modal_content>*:last-child {
    margin-bottom: 0;
}
</style>
<?php
function getCustomeName($id)
{
    global $con;
    // return $id;
    $sql = mysqli_query($con, "SELECT fname From customers where id = '$id'");

    $result = mysqli_fetch_array($sql);
    if ($result['fname']) {

        return $result['fname'];
    }
} ?>

<?php
$readStatus = 0;
$readWhere = '1';
if (isset($_GET['status']) && $_GET['status'] == 'read') {
    $readStatus = 1;
    $readWhere = "is_read = 1";
} elseif (isset($_GET['status']) && $_GET['status'] == 'unread') {
    $readStatus = 0;
    $readWhere = "is_read = 0";
}

if (isset($_POST['generate'])) {

    $from = mysqli_real_escape_string($con, $_POST['from']);
    $from = date('Y-m-d', strtotime('-1 day', strtotime($from)));
    $to = mysqli_real_escape_string($con, $_POST['to']);
    $to = date('Y-m-d', strtotime('+1 day', strtotime($to)));
    $customer_id = mysqli_real_escape_string($con, $_POST['customer_id']);
    $where = "";

    if ($customer_id != '')
        $where = " AND customer_id = " . $customer_id;
    $query = "Select * from order_comments where DATE_FORMAT(created_on, '%Y-%m-%d') >= '$from' and DATE_FORMAT(created_on, '%Y-%m-%d') <= '$to' " . $where . " AND " . $readWhere . " order by id desc";
    $sql =  mysqli_query($con, $query);
    // echo 'string'.$sql;
    // die();
} else {
    $from = date('Y-m-d', strtotime('today - 30 days'));
    $to = date('Y-m-d');
    $sql  = mysqli_query($con, "SELECT * From order_comments WHERE " . $readWhere . " order by id DESC");
}
if (isset($_POST['reply_comment']) && !empty($_POST['reply_comment'])) {
    echo "<pre>";
    print_r($_POST);
    die;
}
// echo $sql;
// die();

$result1 = mysqli_query($con, "SELECT count(*) as readdata from order_comments where is_read=1");
$read_data = mysqli_fetch_assoc($result1);

$result2 = mysqli_query($con, "SELECT count(*) as unread from order_comments where is_read=0");
$unread_data = mysqli_fetch_assoc($result2);

$result3 = mysqli_query($con, "SELECT count(*) as data from order_comments");
$all_data = mysqli_fetch_assoc($result3);




?>






<!-- custom modal -->

<div class="modal_custom">
    <div class="modal-overlay_cust modal_toggle"></div>
    <div class="modal_wrapper modal_transition">
        <!-- <div class="modal_header">
        <button class="modal_close modal_toggle"><svg class="icon-close icon" viewBox="0 0 32 32"><use xlink:href="#icon-close"></use></svg></button>
      </div> -->

        <div class="modal_body">
            <div class="modal_content">
                <div class="popup_data">
                    <div class="row bdr_box">
                        <div class="col-sm-3 main_head"><b> <?php echo getLange('trackingno'); ?> :</b></div>
                        <div class="col-sm-8 main_contents">
                            <p class="track_no_val"></p>
                        </div>
                    </div>
                    <div class="row bdr_box">
                        <div class="col-sm-4 main_head"><b><?php echo getLange('orderdate'); ?> :</b></div>
                        <div class="col-sm-8 main_contents">
                            <p class="order_date_val"></p>
                        </div>
                    </div>
                    <div class="row bdr_box">
                        <div class="col-sm-4 main_head"><b><?php echo getLange('customername'); ?> :</b></div>
                        <div class="col-sm-8 main_contents">
                            <p class="customer_name_val"></p>
                        </div>
                    </div>
                    <div class="row bdr_box">
                        <div class="col-sm-4 main_head"><b><?php echo getLange('subject'); ?> :</b></div>
                        <div class="col-sm-8 main_contents">
                            <p class="subject_val"></p>
                        </div>
                    </div>
                    <div class="row bdr_box">
                        <div class="col-sm-4 main_head"><b> <?php echo getLange('commentby'); ?> :</b></div>
                        <div class="col-sm-8 main_contents">
                            <p class="comment_by_val"></p>
                        </div>
                    </div>
                    <div class="row bdr_box">
                        <div class="col-sm-4 main_head"><b> <?php echo getLange('ordercomment'); ?> :</b></div>
                        <div class="col-sm-8 main_contents">
                            <p class="order_comment_val"></p>
                        </div>
                    </div>


                </div>
                <button type="button"
                    class=" btn btn-primary comment_modal_id"><?php echo getLange('close'); ?></button>
            </div>
        </div>
    </div>
</div>



<!-- Code for the modal -->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="popup_data">
                    <div class="row bdr_box">
                        <div class="col-sm-3 main_head"><b> <?php echo getLange('trackingno'); ?> :</b></div>
                        <div class="col-sm-9 main_contents">
                            <p class="track_no_val"></p>
                        </div>
                    </div>
                    <div class="row bdr_box">
                        <div class="col-sm-3 main_head"><b><?php echo getLange('orderdate'); ?> :</b></div>
                        <div class="col-sm-9 main_contents">
                            <p class="order_date_val"></p>
                        </div>
                    </div>
                    <div class="row bdr_box">
                        <div class="col-sm-3 main_head"><b><?php echo getLange('customername'); ?> :</b></div>
                        <div class="col-sm-9 main_contents">
                            <p class="customer_name_val"></p>
                        </div>
                    </div>
                    <div class="row bdr_box">
                        <div class="col-sm-3 main_head"><b><?php echo getLange('subject'); ?> :</b></div>
                        <div class="col-sm-9 main_contents">
                            <p class="subject_val"></p>
                        </div>
                    </div>
                    <div class="row bdr_box">
                        <div class="col-sm-3 main_head"><b> <?php echo getLange('commentby'); ?> :</b></div>
                        <div class="col-sm-9 main_contents">
                            <p class="comment_by_val"></p>
                        </div>
                    </div>
                    <div class="row bdr_box">
                        <div class="col-sm-3 main_head"><b> <?php echo getLange('ordercomment'); ?> :</b></div>
                        <div class="col-sm-9 main_contents">
                            <p class="order_comment_val"></p>
                        </div>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button"
                    class=" btn btn-primary comment_modal_id"><?php echo getLange('close'); ?></button>
                 <button type="button" class="btn btn-primary">Save changes</button> 
            </div>
        </div>
    </div>
</div>  -->

<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form action="#" method="POST">
                <div class="modal-body">
                    <div class="popup_data">
                        <div class="row bdr_box">
                            <input type="hidden" name="order_id" class="order_id_comment">
                            <input type="hidden" name="track_no" class="track_no_comment">
                            <input type="hidden" name="customer_id" class="customer_id_comment">
                            <label for="subject"> Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="Subject of message"
                                required>
                        </div>
                        <div class="row bdr_box">
                            <label for="message"> Message</label>
                            <input type="text" name="message" class="form-control" placeholder="Message " required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="submit" name="reply_comment" value="Submit" class="btn btn-success send_reply">
                </div>
            </form>
        </div>
    </div>
</div>


<!-- End modal here -->

<div class="panel panel-default">
    <div class="panel-heading"><?php echo getLange('commentreport'); ?> </div>


    <div class="panel-body" id="same_form_layout">

        <div id="basic-datatable_wrapper" class="dataTables_wrapper form-horizontal dt-bootstrap no-footer">

            <div class="row gap-none" style="padding-left: 10px;">

                <form action="" method="post">

                    <div class="form-group">

                        <label style="margin: 0 0 5px;"
                            class="col-sm-12 control-label sidegap"><?php echo getLange('generatereport'); ?></label>
                        <?php if (isset($_SESSION['type']) && $_SESSION['type'] == 'admin') { ?>
                        <div class="col-sm-2 sidegap">
                            <div class="input-group">
                                <select class="form-control" name="customer_id" id="customer_id">
                                    <option value="">Select Customer</option>
                                    <?php
                                        $query = mysqli_query($con, "SELECT * FROM customers");
                                        if ($query) {
                                            while ($row = mysqli_fetch_object($query)) {
                                                if (isset($_POST['id']) && $_POST['id'] == $row->id)
                                                    echo '<option selected value="' . $row->id . '">' . $row->bname . '</option>';
                                                else
                                                    echo '<option value="' . $row->id . '">' . $row->bname . '</option>';
                                            }
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-sm-2 sidegap">
                            <div class="input-group date">
                                <input type="text" name="from" id="from" class="form-control datepicker"
                                    value="<?php echo $from; ?>" data-date-format="YYYY-MM-DD">
                                <!-- <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span> -->
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-2 sidegap">
                            <div class="input-group date">
                                <input type="text" name="to" id="to" class="form-control datepicker"
                                    value="<?php echo $to; ?>" data-date-format="YYYY-MM-DD">
                                <!-- <span class="input-group-addon"><span class="glyphicon-calendar glyphicon"></span> -->
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-2 sidegap">
                            <button type="button" name="generate" class="btn btn-info"
                                id="submit_order"><?php echo getLange('generatereport'); ?> </button>
                        </div>
                        <div class="col-sm-4 action_btns">
                            <ul>
                                <li><a href="#" class="readhref active" data-read="">All (<span id="all"></span>)</a>
                                </li>
                                <li> <a href="#" class="readhref " data-read="1">Read (<span id="read"></span>)</a></li>
                                <li> <a href="#" class="readhref  " data-read="0">UnRead (<span id="unread"></span>)</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
                <div class="col-sm-12 table-responsive">
                    <div class="pdf">
                        <table id="comment_datatable" cellpadding="0" cellspacing="0" border="0"
                            class="table table-striped orders_tbl  table-bordered no-footer dtr-inline collapsed"
                            role="grid" aria-describedby="basic-datatable_info">
                            <div class="fake_loader" id="image" style="text-align: center;">
                                <img src="images/fake-loader-img.gif" alt="logo" style="width:130px;">
                            </div>
                            <thead>
                                <tr role="row">
                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">#
                                    </th>
                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">
                                        <?php echo getLange('trackingno'); ?> </th>

                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">
                                        <?php echo getLange('orderdate'); ?> .</th>

                                    <!-- <th  tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">Package Type</th> -->

                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-sort="ascending" aria-label="Rendering engine: activate to sort column ">
                                        <?php echo getLange('customername'); ?> </th>

                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending">
                                        <?php echo getLange('subject'); ?></th>

                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-label="Engine version: activate to sort column ascending">
                                        <?php echo getLange('ordercomment'); ?> </th>



                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-label="Browser: activate to sort column ascending"
                                        style="width: 100px !important;"><?php echo getLange('commentby'); ?> </th>
                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-label="Browser: activate to sort column ascending"
                                        style="width: 100px !important;"><?php echo getLange('status'); ?></th>

                                    <th tabindex="0" aria-controls="basic-datatable" rowspan="1" colspan="1"
                                        aria-label="Browser: activate to sort column ascending"
                                        style="width: 100px !important;"><?php echo getLange('action'); ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="text-center">
                        <img src="images/raw.gif" style="display:none;">

                        <a href="#" class="btn btn-success center" target="_blank" id="down_pdf"
                            style="display:none;">Download PDF</a>

                        <!-- <a href="#" class="btn btn-success center" id="gen_pdf">Generate PDF</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Quick & dirty toggle to demonstrate modal toggle behavior
$('.modal_toggle').on('click', function(e) {
    e.preventDefault();
    $('.modal_custom').toggleClass('is-visible');
});
</script>