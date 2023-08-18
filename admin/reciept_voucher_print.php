<?php
session_start();
require 'includes/conn.php';

include "includes/header.php";


if(isset($_GET['voucher_number']))
{
    $voucherNumber = $_GET['voucher_number'];
    $master_query = "SELECT master.voucher_no,master.id,master.created_on,master.description,users.Name,users.user_name FROM `tbl_reciept_voucher_master` as master LEFT JOIN users ON master.user_id = users.id  WHERE master.voucher_no = '{$voucherNumber}'";
      
    $master_query_result = mysqli_query($con,$master_query);
    if($master_query_result->num_rows > 0)
    {
        $master_data = mysqli_fetch_assoc($master_query_result);
        $detail_query = "SELECT detail.narration,detail.chequeNo,detail.id,detail.amount,ledger.ledgerName,detail.type ,CONCAT(detail.amount,'-',detail.type) as  amount_value, DATE_FORMAT(detail.chequeDate,'%b-%d-%Y') as cheque_date FROM `tbl_reciept_voucher_detail` as detail LEFT JOIN tbl_accountledger as ledger ON detail.ledgerId = ledger.id WHERE detail.master_id = ".$master_data['id'];
        $detail_query_result = mysqli_query($con,$detail_query);
        $detail_data = mysqli_fetch_all($detail_query_result,MYSQLI_ASSOC);

    }
    
  

}
else{
    die("voucher number not set");
}

?>
 

<body >

<style type="text/css">
body{
    background: #fff;
}
.print_wrap{
    padding:10px 0;
}
.head_info_box h1 {
    font-weight: bold;
    font-size: 24px;
    color: #000;
}
.head_info_box {
    padding: 0 0 10px;
}
.sale_id b ,.date_invoice_right p{
    font-size: 16px;
}
.table-box tbody tr td, .table-box tbody tr th, .table-box thead tr th {
    padding: 3px 2px 2px 4px !important;
    font-size: 13px !important;
}

 .table-box thead tr th,.table-box thead tr th {
    background: #426394 !important;
    color: #fff !important;
    -webkit-print-color-adjust: exact;
}
.table-box tr:nth-child(even) {
    background: #f3f0f0;
}

.fl{ float:left}
.fr{ float:right}
.cl{ clear:both; font-size:0; height:0; }
.clearfix:after {
    clear: both;
    content: ' ';
    display: block;
    font-size: 0;
    line-height: 0;
    visibility: hidden;
    width: 0;
    height: 0;
}
.sale_id {
    margin-bottom: 20px;
}
.sale_id b {
    display: block;
    color: #000;
}
.sale_id b span {
    color: #676a6c;
    font-weight: 500;
}
.reports .table-box {
    width: 100% ;
        border-collapse: collapse;
        border-bottom: 1px solid #000;
}


@media print {
  .printPageButton {
    display: none;
  }
@page{margin: 0;}
.table-box th{
    background: #6a6a6a !important;
        color: #fff !important;
        -webkit-print-color-adjust: exact;
    }
}
</style>
    <div class="print_wrap">
        <div class="container">
        <?php
            if(isset($_GET['preview']))
            {
                echo "<button class='btn btn-warning printPageButton' onclick='window.print()'>Print</button>";
            }

        ?>
        <div class="head_info_box">
            <h1 class="text-center bt-1">Reciept Voucher</h1>
        </div>

        <div class="clearfix" style="margin-top: 20px;">
            <div class="sale_id" style="text-align: left;float: left;width: 50%;">
                <b>Voucher Number: <span style="font-weight: 500;"><?php echo $master_data['voucher_no']; ?></span></b>
                <b>Employee: <span>  <?php echo $master_data['Name']; ?></span></b>
            </div>

            <div class="date_invoice_right" style="float: left;width: 50%;text-align: right;">
               <p style="color: #676a6c;font-weight: 500;"><b style="color: #000;">Date:</b>
                  <?php echo date("M d Y H:i A",strtotime($master_data['created_on'])); ?>
              </p>
          </div>
      </div>
        
        

        <table class="table table-bordered table-box" style="margin-bottom:0;">
            <tr>
                <th style="background: #6a6a6a;color: #fff;">Sr No.</th>
                <th style="background: #6a6a6a;color: #fff;">Account Ledger</th>
                <th style="background: #6a6a6a;color: #fff;">Date</th>
                <th style="background: #6a6a6a;color: #fff;">Cheque No</th>
                <th style="background: #6a6a6a;color: #fff;">Amount</th>
                <th style="background: #6a6a6a;color: #fff;">Narration</th>
            </tr>
            <?php  
                $sr_no = 0;
                $total_debit = 0;
                $total_credit = 0;
                foreach ($detail_data as $record) 
                {
                
                    if($record['type'] == 'Dr')
                    {
                        $total_debit += floatval($record['amount']);
                    }
                    else{
                        $total_credit += floatval($record['amount']);

                    }
            ?>
            <tr>
                <td><?php echo ++$sr_no; ?></td>
                <td><?php echo $record['ledgerName']; ?></td>
                <td><?php echo $record['cheque_date']; ?></td>
                <td><?php echo $record['chequeNo']; ?></td>
                <td><?php echo $record['amount_value']; ?></td>
                <td><?php echo $record['narration']; ?></td>
            </tr>
            <?php
                }
            ?>
        </table>


        <div class="clearfix" style="    width: 100%;    margin: 0;border-top: 1px solid #868686;border-bottom: 1px solid #868686;padding: 13px 0;">
                <div class="remakrs_box" style="float: left;width: 60%;">
                    <b style="float: left;margin-right: 14px;color: #000;font-size: 14px;">Remarks:</b>
                    <p style="float: left;width:68%;font-size: 14px;margin: 0;    color: #676a6c;text-align: left;"><?php echo $master_data['description']; ?>
                    </p>
                </div>

                <div class="total_amount" style="    float: left;width: 40%;text-align: right;">
                    <b style="float: left;margin-right: 14px;color: #000;font-size: 14px;width: 68%;">Total Debit:</b>
                    <p style="float: left;width: 25%;font-size: 14px;margin: 0;"><span class=""></span><?php echo $total_debit; ?><span class="">/-</span></p>
                    <b style="float: left;margin-right: 14px;color: #000;font-size: 14px;width: 68%;">Total Credit:</b>
                    <p style="float: left;width: 25%;font-size: 14px;margin: 0;"><span class=""></span><?php echo $total_credit; ?><span class="">/-</span></p>
                </div> 
            </div>

    </div>
    </div>

    <?php  
        if(!isset($_GET['preview']))
        {


    ?>
    
        <script type="text/javascript">
            

            $(document).ready(function () {
                window.print();
                setTimeout("closePrintView()", 2000);
            });
            function closePrintView() {
                document.location.href = 'https://a.icargos.com/portal/admin/reciept_voucher.php';
            }
        </script>
    <?php

        }
    ?>
</body>