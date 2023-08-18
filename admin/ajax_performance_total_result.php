<?php



session_start();



include_once 'includes/conn.php';

include_once 'includes/role_helper.php';







$from = $_POST['from'];



$to = $_POST['to'];



$customer = $_POST['customer'];



$saleman = $_POST['saleman'];



$orderby = " GROUP BY orders.customer_id";



$where= " and DATE_FORMAT(`orders`.`order_date`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`orders`.`order_date`, '%Y-%m-%d') <= '".$to."' ";



if (isset($customer) && $customer != '') {

    $where .= " AND orders.customer_id=" . $customer;

}

if (isset($saleman) && $saleman != '') {

    $where .= " AND customers.sale_man_id=" . $saleman;

}

$where .= $orderby;

$empQuery = "SELECT SUM(orders.quantity) AS no_of_parcels,SUM(orders.price) AS total_price,SUM(orders.fuel_surcharge) AS total_fuel_surcharge,users.Name AS saleman,customers.fname,customers.bname from orders INNER JOIN customers ON customers.id=orders.customer_id  INNER JOIN users ON users.id=customers.sale_man_id WHERE 1 AND orders.status!='cancelled' " . $where;



$empRecords = mysqli_query($con, $empQuery);

$parcels=0;

$price=0;

$total_fuel_surcharge=0;

$toatlprice=0;

$data=[];

if ($empRecords) {

    while ($fetch1 = mysqli_fetch_assoc($empRecords)) {

        $parcels+=$fetch1['no_of_parcels'];

        $price+=$fetch1['total_price'];
        $total_fuel_surcharge+=$fetch1['total_fuel_surcharge'];
        $toatlprice+=$fetch1['total_price'] + $fetch1['total_fuel_surcharge'];

    }

}



$data['total_parcels'] = $parcels;



// $data['codamount'] = number_format((float)$codamount, 2);

$data['total_price']=$price;
$data['total_fuel_surcharge']=$total_fuel_surcharge;
$data['totalprice']=$toatlprice;

echo json_encode($data);



