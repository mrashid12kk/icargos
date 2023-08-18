<?php 

session_start();

include_once 'includes/conn.php';

function fetchRecordsRelVoucherNo($query,$voucherPreFix = null)
{

    if(!$con)
    {
        include "conn.php";
    }

    $result = mysqli_query($con,$query);

    if($result->num_rows > 0)
    {
        $records = mysqli_fetch_all($result,MYSQLI_ASSOC);
        $records[0]['vprefix'] = $voucherPreFix;
       
        echo json_encode($records);
    }
    else
    {
        echo json_encode(array("error"=>"record not found against this voucher number"));
    }

    
   
}

if(isset($_POST['voucher_number']))
{
   
    $voucher_number = $_POST['voucher_number'];
    $VoucherNumberPrefix = explode("-", $_POST['voucher_number']);
    $tableName = $_POST['tableName'];
    $masterTable = $_POST['masterTable'];

    if($VoucherNumberPrefix[0] == "PV" || $VoucherNumberPrefix[0] == "pv" || $VoucherNumberPrefix[0] == "RV" || $VoucherNumberPrefix[0] == "rv")
    {
        $query = "SELECT pv_master.id as masterId,pv_master.description,pv_master.cheque_date as masterChequeDate,ledg.ledgerName as masterLedgerName,ledg.id as masterledgerId, pv_detail.user_id,pv_detail.ledgerCode as detailLedgerCode,pv_detail.chart_account_id as ca_code,pv_detail.type,pv_detail.amount,pv_detail.id,pv_detail.chequeNo,pv_detail.chequeDate as detailChequeDate,pv_detail.ledgerId as detail_ledger_id,pv_detail.narration,ledgerAcc.ledgerName as detailLedgerName  FROM `{$masterTable}` as pv_master 
            LEFT JOIN tbl_accountledger as ledg ON pv_master.cash_bank_ledger_id = ledg.id
            INNER JOIN `{$tableName}` as pv_detail ON pv_master.id = pv_detail.master_id  
            LEFT JOIN tbl_accountledger as ledgerAcc ON pv_detail.ledgerId = ledgerAcc.id
            WHERE pv_master.voucher_no = '{$voucher_number}'";

            // echo $query;exit();

    }
    else{
        $query = "SELECT pv_master.id as masterId,pv_master.description,pv_master.cheque_date as masterChequeDate,pv_detail.user_id,pv_detail.ledgerCode as detailLedgerCode,pv_detail.chart_account_id as ca_code,pv_detail.type,pv_detail.amount,pv_detail.id,pv_detail.chequeNo,pv_detail.chequeDate as detailChequeDate,pv_detail.ledgerId as detail_ledger_id,pv_detail.narration,ledgerAcc.ledgerName as detailLedgerName  FROM `{$masterTable}` as pv_master 
            INNER JOIN `{$tableName}` as pv_detail ON pv_master.id = pv_detail.master_id  
            LEFT JOIN tbl_accountledger as ledgerAcc ON pv_detail.ledgerId = ledgerAcc.id
            WHERE pv_master.voucher_no = '{$voucher_number}'";
            // echo $query;exit();

    }
    
    
    fetchRecordsRelVoucherNo($query,$VoucherNumberPrefix[0]);
     
}


?>