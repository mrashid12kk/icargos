<?php
require 'includes/conn.php';
$query1 = mysqli_query($con, "select value from config where name='website'");
$website = mysqli_fetch_array($query1);
$query2 = mysqli_query($con, "select value from config where name='conpany_name'");
$company = mysqli_fetch_array($query2);


?>

<footer class="container-fluid footer">

    <!-- Copyright &copy; 2019  <a href="https://www.itvision.com.pk/" target="_blank">  IT Vision</a> -->
    <a  href="#" class="designby hide_company">Developed by</a> <a  class="hide_company" href="<?php echo $website['value']; ?>" target="_blank"> IT Vision
    (Pvt.) LTD.</a>
    <a href="#" class="pull-right scrollToTop"><i class="fa fa-chevron-up"></i></a>

</footer>

<style type="text/css">
    .hide_company,.hide_company:hover{
      color: #fff !important;  
    }
    .footer  a::selection {
      color:#fff !important;
    }
    
</style>



</section>
</div>

<style type="text/css">
    .notification_box {
        position: fixed;
        right: 31px;
        bottom: 18px;
        background: #fff;
        padding: 27px 22px;
        box-shadow: 3px 13px 24px rgb(208 208 208 / 77%);
        z-index: 9999;
        border-radius: 8px;
        background-image: linear-gradient(#416baf, #274f90, #416baf);
    }

    .notification_box i {
        position: absolute;
        right: -9px;
        top: -9px;
        background: #e9573f;
        border-radius: 100%;
        width: 20px;
        cursor: pointer;
        height: 20px;
        text-align: center;
        padding: 4px 0 0;
        font-size: 12px;
    }

    .notification_box h4 {
        margin: 0 0 9px;
        font-size: 15px;
    }

    .notification_box p {
        margin: 0;
    }

    .notification_box p,
    .notification_box h4,
    .notification_box i {
        color: #fff;
    }

    .notification_box p a {
        color: #78ec1e;
        font-size: 16px;
        font-weight: 500;
        text-decoration: underline !important;
    }


    @-webkit-keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @-moz-keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .fade-in {
        opacity: 0;
        -webkit-animation: fadeIn ease-in 1;
        -moz-animation: fadeIn ease-in 1;
        animation: fadeIn ease-in 1;

        -webkit-animation-fill-mode: forwards;
        -moz-animation-fill-mode: forwards;
        animation-fill-mode: forwards;

        -webkit-animation-duration: 1s;
        -moz-animation-duration: 1s;
        animation-duration: 1s;
    }

    .fade-in.one {
        -webkit-animation-delay: 0.7s;
        -moz-animation-delay: 0.7s;
        animation-delay: 0.7s;
    }
</style>

<!-- Content Block Ends Here (right box)-->

<?php

$checkArray = checkOrdersLimit();
$limit_enable = getConfig('enable_orders_limit');
$orders_limit = getConfig('orders_limit');
$limit_message = getConfig('limit_message');
if (isset($limit_enable) && $limit_enable == 1 && isset($checkArray) && ($checkArray['orders_count']) >= $checkArray['limit_message']) : ?>
    <div class="notification_box fade-in one">
        <i class="fa fa-close"></i>
        <h4>Your monthly number of orders limit has been reached
            <?php echo $checkArray['orders_count'] . ' out of ' . $checkArray['orders_limit'] ?>.</h4>
            <p>You can upgrade your package by visiting your <a target="_blank" href="https://billing.icargos.com/">client
            area</a></p>
        </div>
    <?php endif ?>



    <!-- JQuery v1.9.1 -->
    <script type="text/javascript">
        var number_format=<?php echo getConfig('number_format'); ?>

    </script>
    <script src="assets/js/app/jquery-2.2.4.min.js"></script>
    <script src="assets/js/app/custom.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.0.1/Chart.min.js"></script>
    <!-- Bootstrap -->
    <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.12.5/sweetalert2.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js'></script>

    <script type="text/javascript" src="assets/js/jquery.inputmask.js" charset="utf-8"></script>
    <script type="text/javascript">
        $("#phone").inputmask({
            "mask": "999-999-999-99"
        });
    </script>
    <script src="assets/js/plugins/bootstrap-validator/bootstrapValidator.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>

    <script src="assets/js/moment/moment.js"></script>
    <script src="assets/css/demo.js"></script>

    <script src="assets/js/select2.js"></script>
<!-- 
    <script src="assets/js/chosen.jquery.js"></script> -->
    <script type="text/javascript">
        $('.js-example-basic-single').select2();
    </script>
    <script type="text/javascript">
        $('.js-example-basic-multiple').select2();
    </script>
    <script src="https://demo.a.eloerp.net/assets/insapinia_theme/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            format: 'M d yyyy'
        });
    </script>
    <!-- NanoScroll -->
    <script src="assets/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
    <?php
// if($type=="admin"||$type=="employee"){
    ?>
    <?php
// }
// else{
    ?>
    <script src="<?php echo getConfig('map_api'); ?>" async defer></script>

    <!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJoTnvdGgAuOLEBLbjQsxyqy8r3pY5I7g&libraries=geometry,places" defer></script> -->

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {

            window.onload = function() {
                obj2.pickup_map_rider();
                obj2.delivery_map_rider();
            }

            obj2 = {};
            obj2.pickup_map_rider = function() {
                var map = new google.maps.Map(document.getElementById('pickup_map_rider'), {
                    center: {
                        lat: 25,
                        lng: 55
                    },
                    zoom: 7
                });
                var infowindow = new google.maps.InfoWindow();
                var marker, i;
                console.log(pickup_locations);
                for (i = 0; i < pickup_locations.length; i++) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(pickup_locations[i].lat, pickup_locations[i].lng),
                        map: map
                    });
                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            infowindow.setContent(pickup_locations[i].info);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }
            }
            obj2.delivery_map_rider = function() {
                var map = new google.maps.Map(document.getElementById('delivery_map_rider'), {
                    center: {
                        lat: 25,
                        lng: 55
                    },
                    zoom: 7
                });
                var infowindow = new google.maps.InfoWindow();
                var marker, i;
                for (i = 0; i < delivery_locations.length; i++) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(delivery_locations[i].lat, delivery_locations[i]
                            .lng),
                        map: map
                    });
                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            infowindow.setContent(delivery_locations[i].info);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }
            }
        }, false);
    </script>

    <?php



// }



    ?>



    <!-- Custom JQuery -->





    <script src="assets/js/signature_pad.js" type="text/javascript"></script>




<script type="text/javascript">
    var app = {};
    $(document).ready(function() {
        $('body').on('change', '#customer_id, #client_code', function(e) {
            var city = $(this).find('option:selected').data('city');
            var phone = $(this).find('option:selected').data('phone');
            var address = $(this).find('option:selected').data('address');
            var code = $(this).find('option:selected').data('code');
            var id = $(this).find('option:selected').val();
            var bank = $(this).find('option:selected').data('bank');
            var account = $(this).find('option:selected').data('account');
            var holder = $(this).find('option:selected').data('holder');
            var iban = $(this).find('option:selected').data('iban');
            var statement = $(this).find('option:selected').data('statement');
            $(this).closest('form').find('.customer_city').val(city);
            $(this).closest('form').find('.customer_mobile').val(phone);
            $(this).closest('form').find('.customer_address').val(address);
            $(this).closest('form').find('#client_code').val(id);
            $(this).closest('form').find('#customer_id').val(id);
            $(this).closest('form').find('.bank_name').val(bank);
            $(this).closest('form').find('.bank_statement').val(statement);
            $(this).closest('form').find('.bank_ac_no').val(account);
            $(this).closest('form').find('.ac_holder_name').val(holder);
            $(this).closest('form').find('.iban_no').val(iban);
        });
        $('body').on('change', '#postponed', function(e) {
            if ($(this).val() == 'delayed') {
                $('.delayed_reason').show();
            } else {
                $('.delayed_reason').hide();
            }
            var value = $(this).val();
            if (value == 'canceled' || value == 'lost' || value == 'damaged' || value == 'return') {
                $('.lost_reason').show();
            } else {
                $('.lost_reason').hide();
            }
        });

        $('body').on('click', '[name="submitAccept"]', function(e) {
            if ($('#postponed').val() == 'delivered') {
                e.preventDefault();
                var status = $('#postponed').val();
                var id = $(this).closest('form').find('[name="id"]').val();
                var driver = $(this).closest('form').find('[name="driver"]').val();
                window.location.href = 'orderSignature.php?id=' + id + '&driver=' + driver;
                return false;
            }
        })

        var receiver = this.querySelector(".signature-pad");
        var driver = this.querySelector(".driver-signature-pad");
        if (receiver != null)
            app.makeReceiverSignature(receiver);
        if (driver != null)
            app.makeDriverSignature(driver);
        $('body').on('click', '.signature-pad .clear', function(event) {
            event.preventDefault();
            if (receiverSignaturePad != null)
                receiverSignaturePad.clear();
        });
        $('body').on('click', '.driver-signature-pad .clear', function(event) {
            event.preventDefault();
            if (driverSignaturePad != null)
                driverSignaturePad.clear();
        });
        $('body').on('click', '#signature_modal [name="submit_signature"]', function(e) {
            if (driverSignaturePad != null) {
                $(this).closest('form').find('[name="driver_signature"]').val(driverSignaturePad
                    .toDataURL());
                driverSignaturePad.clear();
            }
            if (receiverSignaturePad != null) {
                $(this).closest('form').find('[name="receiver_signature"]').val(receiverSignaturePad
                    .toDataURL());
                receiverSignaturePad.clear();
            }
        });
    });

    var driverSignaturePad = null;
    var receiverSignaturePad = null;
    app.resizeCanvas = function(canvas) {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = 500 * ratio;
    canvas.height = 200 * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    return canvas;
}
app.makeDriverSignature = function(element) {
    if (element != null) {
        var canvas = element.querySelector("canvas");
        canvas = app.resizeCanvas(canvas);
        driverSignaturePad = new SignaturePad(canvas);
        var signature = element.querySelector('input.value').value;
        driverSignaturePad.fromDataURL(signature);
    }
}
app.makeReceiverSignature = function(element) {
    if (element != null) {
        var canvas = element.querySelector("canvas");
        canvas = app.resizeCanvas(canvas);
        receiverSignaturePad = new SignaturePad(canvas);
        var signature = element.querySelector('input.value').value;
        receiverSignaturePad.fromDataURL(signature);
    }
}
</script>

<script type="text/javascript">
    $(".notification_box i").click(function() {
        $(".notification_box").fadeOut();
    });

    $(window).on('scroll', function() {
        var scrollTop = $(window).scrollTop();
        if (scrollTop > 20) {
            $('.footer').css('position', 'static');
            $('.footer').css('width', '97%');
            $('.footer .designby').css('padding-left', '0');
        } else {
            $('.footer').css('position', 'fixed');
            $('.footer').css('width', '83%');
            $('.footer .designby').css('padding-left', '0 ');
        }
    })
</script>

<script type="text/javascript" src="assets/js/chosen.jquery.js"></script>
<script type="text/javascript" src="assets/js/newjs/init.js"></script>
<script src="../assets/js/select2.min.js"></script>

<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.disableAutoInline = true;
    CKEDITOR.replace('messageArea');
</script>


<script type="text/javascript">
    $(".country-list li").click(function() {
        $(".default_number,.flag_default").hide();
    });
</script>


<!-- **** Fetching Ledger data ajax code ***** -->
<script>
    select2LibraryInitialize($(".ledger_name"));

    

    cashBankLedgerSelectlibInitialze();
  


    // ***** Add and Remove table rows on Debit Note Form  *******

    let count = 0;
    function addDynamicTableRow(count,vtype = null)
    {
        

        let objectDate = new Date();
        let day = objectDate.getDate();
        let month = objectDate.getMonth();
        month += 1;
        let year = objectDate.getFullYear();
        
        if(month < 10)
        {
            month =  `0${month}`;
        }
        if(day < 10)
        {
            day =  `0${day}`;
        }

        let currentDate = `${year}-${month}-${day}`;

        var html = '';
        html += `<tr class="table-item-row">
                    <td></td>
                            <td><input type="text" placeholder="01-03-05-01-L" class="code_for_this form-control ledger-code" name="ledger_code[]"></td>
                            <td>
                                <select class="form-control ledger_name" name="ledger_id[]">
                                    <option value="0">Choose Ledger</option>  
                                </select>
                            </td>
                            ${(vtype != 'no') ? `
                                    <td>
                                        <select class="form-control type" name="type[]">
                                            <option value="Dr">Dr</option>
                                            <option value="Cr">Cr</option>
                                        </select>
                                    </td>
                                ` : ''
                            }
                            
                            <td>
                                <input type="text"  class="form-control amount validate_input_decimal" placeholder="00.00" name="amount[]">
                            </td>
                            <td><input type="text" class="form-control" name="cheque_number[]"></td>
                            <td><input type="date" value="${currentDate}" class="form-control" name="detail_cheque_date[]"></td>
                            <td><input type="text" class="form-control" name="narration[]"></td>
                            <td>
                                <div class="action_btn">
                                    <button type="button" class="btn btn-info add" data-vtype="${vtype ?? ''}"><i class="fa fa-plus"></i></button>
                                `;

                 var remove_btn = '';
                 if(count > 0)
                 {
                    remove_btn = `<button type="button" class="btn btn-danger btn-sm remove"><i class="fa fa-trash"></i></button>`;
                 }

                 html += `${remove_btn} 
                            </div> 
                            <input type="hidden" name="id[]" />
                             </td> 
                        </tr>`;
                        count++;

                return html;

    }


    $(document).on("click",".add",function() {
        // count++;
        if($(this).data("vtype") == "no")
        {
            var vtype = $(this).data("vtype");
        }
        $(this).closest(".table-container").append(addDynamicTableRow($(".table-item-row").length,vtype));

        incrementRowNumber();
        select2LibraryInitialize($(".ledger_name"));

    })

     $(document).on("click",".remove",function() {
        $(this).closest(".table-item-row").remove();
        incrementRowNumber();
        // count--;
    })

     function select2LibraryInitialize(element)
     {
        element.select2({
            ajax: { 
                url:"https://a.icargos.com/portal/admin/includes/fetch-ledger-data.php",
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function (params) {
                return {
                    searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }  
        });
     }
     function incrementRowNumber()
     {
        $(".table-item-row").each(function(i) {
            $(this).find("td:first-child").text(i+1);
        })
     }

// **** on basis of ledger name changes, ledger code dynamic selected
    $(document).on("change",".ledger_name",function() {
        var ledgerId = $(this).val();
       var row = $(this).closest("tr");
        $.ajax({
            url: "https://a.icargos.com/portal/admin/includes/custom_functions.php",
            type: "POST",
            data: {ledgerId:ledgerId},
            success: function(res) {
                // console.log(res);
                row.find("input[name='ledger_code[]']").val(res);
                
                
            }
        });
    });

// **** on basis of ledger code changes, ledger name dynamic selected

    $(document).on("change",".ledger-code",function() {

        var ledgerCode = $(this).val();
       var row = $(this).closest("tr");
        $.ajax({
            url: "https://a.icargos.com/portal/admin/includes/custom_functions.php",
            type: "POST",
            data: {ledgerCode:ledgerCode},
            success: function(res) {

                // var ledgerData = JSON.parse(res);

                row.find('.ledger_name').select2('destroy');
                if(res)
                {
                    var ledgerData = JSON.parse(res);
                    row.find('.ledger_name').html('<option value="'+ledgerData.id+'" selected>'+ledgerData.ledgerName+'</option>');
                }
                else{
                    row.find('.ledger_name').html('<option value="" selected>Choose ledger</option>');
                }
                

                select2LibraryInitialize(row.find('.ledger_name'));
               

                
            }
        });
    });


    // **** onkey up of ledger code empty the value of ledger name******
    //    $(document).on("keyup",".ledger-code",function() {
    //     var ledgerCode = $(this).val();
    //    var row = $(this).closest("tr");
    //     row.find('.ledger_name').html('<option value="" selected>Choose ledger</option>');
    //     select2LibraryInitialize(row.find('.ledger_name'));
    // });

    // ****** Delete Record Ajax Code ****

    $(document).on("click",".delete-record",function() 
    {
        if(confirm("Are you sure to delete this record??"))
        {
            var recordId = $(this).data('id');
            var tableName = $(this).data('tblname');
            // alert(recordId);
            $.ajax({
                url: "https://a.icargos.com/portal/admin/includes/delete-single-record.php",
                type: "POST",
                data: {tableName:tableName,recordId:recordId},
                success: function(response) {
                    if(response)
                    {
                        alert("Deleted Successfully");
                        window.location.reload();

                    }
                    else{
                        alert("Something went wrong");

                    }
                }
            });
        }
    });


    // ******** DR or CR type with amount equality check code ******
    
    $(document).on("submit","#debit-form",function(e){
        let dr_total_amount = 0;
        let cr_total_amount = 0;
        $('.table-item-row').each(function() {
            if($(this).find(".type").val() == "Dr")
            {
                dr_total_amount += parseInt($(this).find(".amount").val());
            }
            if($(this).find(".type").val() == "Cr")
            {
                cr_total_amount += parseInt($(this).find(".amount").val());

            }
        });

        if(dr_total_amount != cr_total_amount)
        {
            alert("Dr and Cr amount should be equal");
            e.preventDefault();
        }

    });



   function displayDetailItems(debitNoteData)
   {
                let edit_count = 0;
                        let html = '';

                        let Dr_selected = "";
                        let Cr_selected = "";

                        const prType = $('#debit-search-btn').data('prtype');


                        // console.log(debitNoteData[0].vprefix.toLowerCase());
                        // var vprefix = debitNoteData[0].vprefix.toLowerCase();

                       $.each(debitNoteData, function(key,value)
                       {
                            // console.log(value.vprefix.toLowerCase())

                            let objectDate = new Date(value.detailChequeDate);
                            let day = objectDate.getDate();
                            let month = objectDate.getMonth();
                            month += 1;
                            let year = objectDate.getFullYear();
                            
                            if(month < 10)
                            {
                                month =  `0${month}`;
                            }
                            if(day < 10)
                            {
                                day =  `0${day}`;
                            }

                            let currentDate = `${year}-${month}-${day}`;
                            if(value.type == "Dr")
                            {
                                Dr_selected = "selected";
                                Cr_selected = "";

                            }
                            else{
                                Dr_selected = "";
                                Cr_selected = "selected";
                            }

                             

                            html += `<tr class="table-item-row">
                                        <td>${edit_count+1}</td>
                                                <td><input type="text" placeholder="01-03-05-01-L" class="code_for_this form-control ledger-code" name="ledger_code[]" value="${value.ca_code}"></td>
                                                <td>
                                                    <select class="form-control ledger_name" name="ledger_id[]">
                                                        <option value="0">Choose Ledger</option> 
                                                        <option value="${value.detail_ledger_id}" selected>${value.detailLedgerName}</option> 
                                                    </select>
                                                </td>
                                        ${(prType != 'no') ? `
                                               <td>
                                                    <select class="form-control type" name="type[]">

                                                        <option value="Dr" ${Dr_selected}>Dr</option>
                                                        <option value="Cr" ${Cr_selected}>Cr</option>
                                                    </select>
                                                </td>
                                            ` : ''



                                            }
                                                
                                                <td>
                                                    <input type="text"  class="form-control amount validate_input_decimal" placeholder="00.00" name="amount[]" value="${value.amount}">
                                                </td>
                                                <td><input type="text" class="form-control" name="cheque_number[]" value="${value.chequeNo}"></td>
                                                <td><input type="date" value="${currentDate}" class="form-control" name="detail_cheque_date[]"></td>
                                                <td><input type="text" class="form-control" name="narration[]" value="${value.narration}"></td>
                                                <td>
                                                    <div class="action_btn">
                                                        <button type="button" class="btn btn-info add" data-vtype="${prType ?? ''}"><i class="fa fa-plus" ></i></button>
                                                    `;

                                    
                                     var remove_btn = '';
                                     if(edit_count > 0)
                                     {
                                        remove_btn = `<button type="button" class="btn btn-danger btn-sm remove"><i class="fa fa-trash"></i></button>`;
                                     }

                                     html += `${remove_btn} 
                                                </div> 

                                    <input type="hidden" name="id[]" value="${value.id ?? ''}"
                                                 </td> 
                                            </tr>`;
                                        edit_count++;

                        });

                       $("#debit-table-body").html(html)
                       // $(".cash-bank-ledger-name").html("<option selected>bank</option>");
                       select2LibraryInitialize($(".ledger_name"));
   }

    function loadDetailItems(voucher_number,is_edit = false)
    {
        let tableName = $("#debit-search-btn").data("table");
        let masterTableName = $("#debit-search-btn").data("mastertable");
        $.ajax({
            url: "https://a.icargos.com/portal/admin/includes/search-by-voucher-number.php",
            type: "POST",
            data: {tableName:tableName,voucher_number:voucher_number,masterTable:masterTableName},
            success: function(response)
            {
                let debitNoteData = JSON.parse(response);

                // console.log(debitNoteData);
                if(debitNoteData.error != undefined)
                {
                    alert(debitNoteData.error);
                }
                else
                {
                    $('[name=voucher_number]').val(voucher_number);
                    $(".cash-bank-ledger-name").html(`<option value="${debitNoteData[0].masterledgerId}" selected>${debitNoteData[0].masterLedgerName}</option>`);
                    $("#remarks").val(debitNoteData[0].description);
                    $("#masterId").val(debitNoteData[0].masterId)

                    let date = new Date(debitNoteData[0].masterChequeDate);
                    let day = date.getDate();
                    let month = date.getMonth();
                    month += 1;
                    let year = date.getFullYear();
                    
                    if(month < 10)
                    {
                        month =  `0${month}`;
                    }
                    if(day < 10)
                    {
                        day =  `0${day}`;
                    }

                    let existDate = `${year}-${month}-${day}`;
                    $("#master_cheque_date").val(existDate);
                    displayDetailItems(debitNoteData);
                       
                }

             
               

             }
        });
    }


    function cashBankLedgerSelectlibInitialze()
    {
        $('.cash-bank-ledger-name').select2({
            ajax: { 
                url:"https://a.icargos.com/portal/admin/includes/fetch-cash-bank-ledger-data.php",
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function (params) {
                return {
                    searchItem: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }  
        });
    }



    // ****** debit form search by voucher number funcionality ajax based request****
    $("#debit-search-btn").on("click",function()
    {

        let voucher_number = $("#search_voucher_number").val();
        loadDetailItems(voucher_number);
    
    });

    if($("#search_voucher_number").val() != '')
    {
        let voucher_number = $("#search_voucher_number").val();
        loadDetailItems(voucher_number,true);
    }

    function searchVoucherOnEnter(e)
    {
        if(e.keyCode === 13) {
            e.preventDefault();
            loadDetailItems(e.target.value);
            return false;
        }
    }
    $('#search_voucher_number').on('keypress', function(e) {
        searchVoucherOnEnter(e);
    })

    $("#debit-form, #pr-form").on("keypress","input:not(#search_voucher_number)",function(e){
        if(e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
    })
</script>
</body>

</html>