<?php

session_start();
require 'includes/conn.php';
if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="POST"){
    if(!function_exists('getCustomer')){
        function getCustomer($id){
            $record=null;
            GLOBAL $con;
            $cquery=mysqli_query($con,"select client_code,bname from customers where id=".$id);
            if(mysqli_num_rows($cquery)>0){
                $record=mysqli_fetch_assoc($cquery);
            }
            return $record;
        }
    }
    function getTotal($flayer_id)
    {
        $sql_t = "Select * from flayer_orders WHERE flayer_order_index = " . $flayer_id;
        global $con;
        $query11 = mysqli_query($con, $sql_t);
        $total = 0;
        while ($fetch12 = mysqli_fetch_array($query11)) {
            $total += $fetch12['total_price'];
        }
        return $total;
    }

    $client_name = isset($_POST['client_name']) ? $_POST['client_name'] : 'all';
    $limit = isset($_POST['limit']) ? $_POST['limit'] : '20';
    $order_by = isset($_POST['order_by']) ? $_POST['order_by'] : 'DESC';
    $select_filter = isset($_POST['select_filter']) ? $_POST['select_filter'] : '';
    $to_date = isset($_POST['to_date']) ? date('Y-m-d',strtotime($_POST['to_date'])) : date('Y-m-d'); //action date
    $from_date = isset($_POST['from_date']) ? date('Y-m-d',strtotime($_POST['from_date'])) : date('Y-m-d');
    $where = '';
    if (isset($client_name) && $client_name != 'all') {
        $client_ids = implode(',', $client_name);
        $where = " AND customer_id IN ( $client_ids)";
    }
    $date_type=isset($_POST['date_type']) ? $_POST['date_type'] : 'action_date';

    $where.=" AND $date_type>='".$from_date."' AND $date_type<='".$to_date."'";

  

    $invoices_query = mysqli_query($con,"SELECT SUM(net_amount) AS `total_net_amount`,status,customer_id,SUM(collection_amount) AS `total_cod`,SUM(price) AS `total_charges`,SUM(fuel_surcharge) AS `total_fuel_surcharge`,SUM(pft_amount) AS `total_gst` FROM orders WHERE (status='Delivered' OR status='Returned to Shipper') AND payment_status = 'Pending'  $where GROUP BY customer_id ORDER BY id $order_by LIMIT " . $limit);


    if( mysqli_error($con)){
        echo  mysqli_error($con);
        exit;
    }
    $html="";
    $count = mysqli_num_rows($invoices_query); 
    if ($count>0)
    {
        while($invoice=mysqli_fetch_assoc($invoices_query))
        {
            $customer_id=isset($invoice['customer_id']) ? $invoice['customer_id'] : 0;
            $client=isset($invoice['customer_id']) ? getCustomer($customer_id) : null;
            $client_code=isset($client['client_code']) ? $client['client_code'] : '';
            $bname=isset($client['bname']) ? $client['bname'] : '';
            $total_cod=isset($invoice['total_cod']) ? $invoice['total_cod'] : 0;
            $total_returned_cod=isset($invoice['total_cod']) && $invoice['status']=="Returned to Shipper" ? $invoice['total_cod'] : 0;
            $total_charge=isset($invoice['total_charges']) ? $invoice['total_charges'] : 0;
            $total_fuel_surcharge=isset($invoice['total_fuel_surcharge']) ? $invoice['total_fuel_surcharge'] : 0;
            $total_gst=isset($invoice['total_gst']) ? $invoice['total_gst'] : 0;
            $total_net_amount=isset($invoice['total_net_amount']) ? $invoice['total_net_amount'] : 0;
            $total_returned_fee=0;
            $total_cash_handling_fee=0;
            $total_flyer_sell=0;
            $flyer_query = mysqli_query($con, "SELECT * FROM flayer_order_index WHERE DATE_FORMAT(`order_date`, '%Y-%m-%d') >= '" . $from_date . "' AND  DATE_FORMAT(`order_date`, '%Y-%m-%d') <= '" . $to_date . "'  AND customer=" . $customer_id . " AND payment_status = 'Pending'  order by id desc ");
            while ($row = mysqli_fetch_array($flyer_query)) {
                $flayer_order_index = $row['id'];
                $flayer_order_query = mysqli_query($con, "SELECT flayers.flayer_name,flayer_orders.qty FROM flayer_orders LEFT JOIN flayers ON(flayers.id = flayer_orders.flayer ) WHERE flayer_orders.flayer_order_index=" . $flayer_order_index . " ");
                $flyer_total = getTotal($row['id']);
                $total_flyer_sell +=$flyer_total; 
            }
            if ($customer_id) {
                $balance_query = mysqli_query($con, "SELECT (prev_balance + (total_payable - total_paid)) as total FROM customer_ledger_payments WHERE customer_id = $customer_id ORDER BY id DESC LIMIT 1");
                $balance_query = ($balance_query) ? mysqli_fetch_object($balance_query) : null;
                $customer_balance = ($balance_query) ? $balance_query->total : 0;
            }
            $total_payable=$customer_balance + $total_cod-$total_returned_cod-$total_returned_fee-$total_cash_handling_fee-$total_flyer_sell-$total_net_amount;
            if($select_filter==3){
                if($total_payable > 0){
                    $html.='<tr>
                    <td><input class="child_checkboxes" data-client_code="'.$client_code.'" data-bname="'.$bname.'" data-prev_balance="'.$customer_balance.'" data-total_cod="'.$total_cod.'" data-total_returned_cod="'.$total_returned_cod.'" data-total_charge="'.$total_charge.'" data-total_fuel_surcharge="'.$total_fuel_surcharge.'" data-total_gst="'.$total_gst.'" data-total_returned_fee="'.$total_returned_fee.'" data-total_cash_handling_fee="'.$total_cash_handling_fee.'" data-total_flyer_sell="'.$total_flyer_sell.'" data-total_net_amount="'.$total_net_amount.'" data-total_payable="'.$total_payable.'" type="checkbox" checked name="customer_id[]" value="'.$customer_id.'"></td>
                    <td>'.$client_code.'</td>
                    <td>'.$bname.'</td>
                    <td>'.number_format($customer_balance, 2).'</td>
                    <td>'.number_format($total_cod, 2).'</td>
                    <td>'.number_format($total_returned_cod,2).'</td>
                    <td>'.number_format($total_charge,2).'</td>
                    <td>'.number_format($total_fuel_surcharge,2).'</td>
                    <td>'.number_format($total_gst,2).'</td>
                    <td>'.number_format($total_returned_fee,2).'</td>
                    <td>'.number_format($total_cash_handling_fee,2).'</td>
                    <td>'.number_format($total_flyer_sell,2).'</td>
                    <td>'.number_format($total_net_amount,2).'</td>
                    <td>'.number_format($total_payable,2).'</td>

                </tr>';
                }
            }
            elseif($select_filter==2){
                $html.='<tr>
                    <td><input class="child_checkboxes" data-client_code="'.$client_code.'" data-bname="'.$bname.'" data-prev_balance="'.$customer_balance.'" data-total_cod="'.$total_cod.'" data-total_returned_cod="'.$total_returned_cod.'" data-total_charge="'.$total_charge.'" data-total_fuel_surcharge="'.$total_fuel_surcharge.'" data-total_gst="'.$total_gst.'" data-total_returned_fee="'.$total_returned_fee.'" data-total_cash_handling_fee="'.$total_cash_handling_fee.'" data-total_flyer_sell="'.$total_flyer_sell.'" data-total_net_amount="'.$total_net_amount.'" data-total_payable="'.$total_payable.'" type="checkbox" checked name="customer_id[]" value="'.$customer_id.'"></td>
                    <td>'.$client_code.'</td>
                    <td>'.$bname.'</td>
                    <td>'.number_format($customer_balance, 2).'</td>
                    <td>'.number_format($total_cod, 2).'</td>
                    <td>'.number_format($total_returned_cod,2).'</td>
                    <td>'.number_format($total_charge,2).'</td>
                    <td>'.number_format($total_fuel_surcharge,2).'</td>
                    <td>'.number_format($total_gst,2).'</td>
                    <td>'.number_format($total_returned_fee,2).'</td>
                    <td>'.number_format($total_cash_handling_fee,2).'</td>
                    <td>'.number_format($total_flyer_sell,2).'</td>
                    <td>'.number_format($total_net_amount,2).'</td>
                    <td>'.number_format($total_payable,2).'</td>

                </tr>';
            }else{
                $html.='<tr>
                    <td><input class="child_checkboxes" data-client_code="'.$client_code.'" data-bname="'.$bname.'" data-prev_balance="'.$customer_balance.'" data-total_cod="'.$total_cod.'" data-total_returned_cod="'.$total_returned_cod.'" data-total_charge="'.$total_charge.'" data-total_fuel_surcharge="'.$total_fuel_surcharge.'" data-total_gst="'.$total_gst.'" data-total_returned_fee="'.$total_returned_fee.'" data-total_cash_handling_fee="'.$total_cash_handling_fee.'" data-total_flyer_sell="'.$total_flyer_sell.'" data-total_net_amount="'.$total_net_amount.'" data-total_payable="'.$total_payable.'" type="checkbox" checked name="customer_id[]" value="'.$customer_id.'"></td>
                    <td>'.$client_code.'</td>
                    <td>'.$bname.'</td>
                    <td>'.number_format($customer_balance, 2).'</td>
                    <td>'.number_format($total_cod, 2).'</td>
                    <td>'.number_format($total_returned_cod,2).'</td>
                    <td>'.number_format($total_charge,2).'</td>
                    <td>'.number_format($total_fuel_surcharge,2).'</td>
                    <td>'.number_format($total_gst,2).'</td>
                    <td>'.number_format($total_returned_fee,2).'</td>
                    <td>'.number_format($total_cash_handling_fee,2).'</td>
                    <td>'.number_format($total_flyer_sell,2).'</td>
                    <td>'.number_format($total_net_amount,2).'</td>
                    <td>'.number_format($total_payable,2).'</td>

                </tr>';
            }
            
        }
    }
    else
    {
        $html='<tr><td colspan="14">No Records Found</td></tr>';
    }
    echo $html;
}

?>