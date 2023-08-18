<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include_once 'includes/tarif/tarif_price.php';
function delivery_calculation($origin, $destination, $weight, $customer_id, $order_type, $product_id,$type=null)
{
    global $con;

    //get zone
    $price = getTarifPrice($product_id, $origin, $destination, $weight, $customer_id, $order_type,$type);
    return $price;
    exit();

    // if ($destination == '' || $destination == '0') {
    //     return $price;
    // }
    // $whr_dist = '';
    // if ($destination != 'other' or $destination != 'others') {
    //     $whr_dist = " AND  zc.destination ='" . $destination . "'  ";
    // }
    // $pricing_query = mysqli_query($con, "SELECT cp.point_5_kg,cp.upto_1_kg,cp.upto_3_kg,cp.upto_10_kg,cp.other_kg,cp.additional_point_5_kg,cp.addition_kg_type FROM customer_pricing cp INNER JOIN  zone_cities zc ON(cp.zone_id = zc.zone) INNER JOIN zone z ON(z.id = cp.zone_id) WHERE zc.origin ='" . $origin . "' " . $whr_dist . " AND z.service_type='" . $order_type . "'  AND cp.customer_id='" . $customer_id . "'");
    // $countrow = mysqli_num_rows($pricing_query);
    // if ($countrow == 0) {
    //     $whr_dist = '';
    //     if ($destination != 'other') {
    //         $whr_dist = " AND (  zc.destination ='other' or  zc.destination ='others' ) ";
    //     }

    //     $pricing_query = mysqli_query($con, "SELECT cp.point_5_kg,cp.upto_1_kg,cp.upto_3_kg,cp.upto_10_kg,cp.other_kg,cp.additional_point_5_kg,cp.addition_kg_type FROM customer_pricing cp INNER JOIN  zone_cities zc ON(cp.zone_id = zc.zone) INNER JOIN zone z ON(z.id = cp.zone_id) WHERE zc.origin ='" . $origin . "' " . $whr_dist . " AND z.service_type='" . $order_type . "'  AND cp.customer_id='" . $customer_id . "'   ");
    // }

    // $record = mysqli_fetch_array($pricing_query);
    // $price = 0;
    // if ($weight <= 0.5 && $record['point_5_kg'] > 0) {
    //     $price = $record['point_5_kg'];
    //     return $price * 1;
    // } else if ($weight <= 0.5 && $record['point_5_kg'] <= 0 && $record['upto_1_kg'] > 0) {
    //     $price = $record['upto_1_kg'];
    //     return $price;
    // } else if ($weight <= 0.5 && $record['upto_1_kg'] <= 0 && $record['point_5_kg'] <= 0) {
    //     $price = $record['upto_3_kg'];
    //     return $price;
    // } else if ($weight >= 0.5 && $weight <= 1 && $record['point_5_kg'] <= 0 && $record['upto_1_kg'] > 0) {
    //     $price = $record['upto_1_kg'];
    //     return $price;
    // } else if ($weight >= 0.5 && $weight <= 3 && $record['upto_1_kg'] <= 0 && $record['point_5_kg'] <= 0) {
    //     // echo 'here';die();
    //     $price = $record['upto_3_kg'];
    //     return $price;
    // }
    // if ($weight > 0.5 && $weight <= 1) {
    //     $price = $record['upto_1_kg'];
    //     return $price;
    // }
    // if ($weight > 1 && $weight <= 3 && $record['upto_3_kg'] > 0) {
    //     $price = $record['upto_3_kg'];
    //     return $price;
    // }
    // if ($weight > 3 && $weight <= 10 && $record['upto_10_kg'] > 0) {
    //     $price = $record['upto_10_kg'];
    //     return $price;
    // }
    // $value = 0;
    // if ($record['additional_point_5_kg'] && $record['additional_point_5_kg'] > 0 && $record['addition_kg_type'] == 'Additional Weight 0.5 kg') {
    //     $value = $record['additional_point_5_kg'];
    //     if ($weight > 1 && ($record['upto_3_kg'] <= 0 && $record['upto_10_kg'] <= 0)) {
    //         $wt = explode('.', ($weight - 1));
    //         $new_weight = 0;
    //         if (isset($wt[1]) && $wt[1] <= 5) {
    //             $wt[1] = 5;
    //             $wt = implode('.', $wt);
    //             $new_weight = $wt / 0.5;
    //         } else {
    //             $new_weight = (ceil($weight - 1));
    //             $new_weight = $new_weight / 0.5;
    //         }
    //         $price =  (($value * $new_weight)) + ($record['upto_1_kg']);
    //         return $price;
    //     }
    //     if ($weight > 3 && ($record['upto_3_kg'] > 0 && $record['upto_10_kg'] <= 0)) {
    //         $wt = explode('.', ($weight - 3));
    //         $new_weight = 0;
    //         if (isset($wt[1]) && $wt[1] <= 5) {
    //             $wt[1] = 5;
    //             $wt = implode('.', $wt);
    //             $new_weight = $wt / 0.5;
    //         } else {
    //             $new_weight = (ceil($weight - 3));
    //             $new_weight = $new_weight / 0.5;
    //         }
    //         $price =  (($value * $new_weight)) + ($record['upto_3_kg']);
    //         return $price;
    //     }
    //     if ($weight > 10) {
    //         $wt = explode('.', ($weight - 10));
    //         $new_weight = 0;
    //         if (isset($wt[1]) && $wt[1] <= 5) {
    //             $wt[1] = 5;
    //             $wt = implode('.', $wt);
    //             $new_weight = $wt / 0.5;
    //         } else {
    //             $new_weight = (ceil($weight - 10));
    //             $new_weight = $new_weight / 0.5;
    //         }
    //         $price =  (($value * $new_weight)) + ($record['upto_10_kg']);
    //         return $price;
    //     }
    // } else if ($record['other_kg'] > 0) {
    //     $value = $record['other_kg'];
    //     if ($weight > 1 && ($record['upto_3_kg'] <= 0 && $record['upto_10_kg'] <= 0)) {

    //         $price =  (($value) * (ceil($weight - 1))) + ($record['upto_1_kg']);
    //         return $price;
    //     }
    //     if ($weight > 3 && ($record['upto_3_kg'] > 0 && $record['upto_10_kg'] <= 0)) {
    //         $price =  (($value) * (ceil($weight - 3))) + ($record['upto_3_kg']);
    //         return $price;
    //     }
    //     if ($weight > 10) {

    //         $price =  (($value) * (ceil($weight - 10))) + ($record['upto_10_kg']);
    //         return $price;
    //     }
    // }

    // return $price;
}