<?php

	session_start();

	require 'includes/conn.php';

	 

	if(isset($_SESSION['users_id']) &&($_SESSION['type']!=='driver') && isset($_POST['flayer_order_id']))

	{

		if(isset($_POST['flayer_order_id']) && !empty($_POST['flayer_order_id']))

		{



			$flyer_order_id     =  $_POST['flayer_order_id'];

			$sql_flyer_index    =  "SELECT * FROM  flayer_order_index WHERE id= ".$flyer_order_id;

			$flyer_order_query  =  mysqli_query($con,$sql_flyer_index);

			$customer_id        = 0;

			$order_date         = "";

			$flyer_detail_id    = "";

			while($fetch33 = mysqli_fetch_array($flyer_order_query))

			{

				$customer_id       = $fetch33['customer'];

				$order_date        = $fetch33['order_date'];

				$flyer_detail_id   = $fetch33['id'];

			}

		}

?>



<!DOCTYPE html>

<!--<![endif]-->

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Invoice</title>

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,600,700,800,900' rel='stylesheet' type='text/css'>

<style>

  



/* Default Font Styles

______________________*/

body, input, select, textarea, p, a, b{

   font-family: 'Roboto', sans-serif;

    color:#000;

    line-height:1.4;

    max-width: 590px;

    margin: 0 auto;

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

/* p, blockquote, address

______________________*/

p{

    font-size: 15px;

    margin-bottom:15px;

}

.booked_packge{

        max-width: 100%;

    margin: 24px auto 13px;

}

.pacecourier_logo {

    float: left;

    width: 30%;

}

.pacecourier_logo img {

    width: 90px;

    padding-top: 15px;

    margin-left: 27px;

}

.booked_packges {

    float: right;

    width: 59%;

}

.booked_packges h4 {

    margin: 0;

    font-size: 20px;

}

.booked_packges ul{

        padding: 0;

    margin: 9px 0 0;

}

.booked_packges ul li {

    list-style: none;

    margin-bottom: 5px;

}

.booked_packges ul li b {

    float: left;

    width: 25%;

}

.table {

  border-collapse: collapse;

  width: 100%;

}



.table td, .table th {

  border: 1px solid #dddddd;

  text-align: left;

  padding: 6px 6px;

  vertical-align: top;

  font-size: 13px;

}



.table tr:nth-child(even) {

  background-color: #f5f5f5;

}

.table tr li {

    list-style: none;

    font-size: 14px;

    margin-bottom: 3px;

}

.table tr ul{

    padding-left: 0;

    margin: 0;

}

</style>



<?php 

	function getName($customer_id)

	{  

		global $con;

		$sql_cus = "SELECT * FROM customers WHERE id='".$customer_id."' "; 

  		$customer_query = mysqli_query($con,$sql_cus); 

  		$name = "";

	  	while($row_q = mysqli_fetch_array($customer_query))

	  	{  

			$name = $row_q['fname'];		

		}

		return $name;

	}



	function getFlyerName($flyers_id)

	{  

		global $con;

		$sql_fly = "SELECT * FROM flayers WHERE id='".$flyers_id."' "; 

  		$flyers_query = mysqli_query($con,$sql_fly); 

  		$name = "";

	  	while($row_f = mysqli_fetch_array($flyers_query))

	  	{  

			$name = $row_f['flayer_name'];		

		}

		return $name;

	}



?>

</head>

<body id="page-name">





<div class="clearfix booked_packge">

	<div class="pacecourier_logo">

		<img src="<?php echo BASE_URL.'admin/'.getConfig('logo'); ?>" alt="Transco Logistics">

	</div>

	<div class="booked_packges">

		<h4>Booked Packets Summary Report</h4>

		<ul>

		  	<li><b>Invoice :</b> <?php echo sprintf("%04d",$flyer_detail_id)?> </li>

		  	<li><b>Customer :</b> <?php echo getName($customer_id); ?> </li>

		  	<li><b>Date :</b> <?php echo date("d/m/Y",strtotime($order_date)); ?></li> 

		</ul>

	</div>

</div>



<table class="table">

  	<tr>

    	<th style="width: 40%">Flyer</th>

    	<th style="width: 20%">Price</th>

    	<th style="width: 20%">Qty</th>

   		<th style="width: 20%">Total Price</th> 

  	</tr>



  	<?php 



  		$sql_detail_fly  =  "SELECT * FROM  flayer_orders WHERE flayer_order_index= ".$flyer_detail_id." order by id asc ";

 

		$sql_detail_fly_query =  mysqli_query($con,$sql_detail_fly);

 

		$counter = 0;

		$total_price = 0;

		while($fetch44 = mysqli_fetch_array($sql_detail_fly_query))

		{

			$total_price += $fetch44['total_price'];

			?>

			  	<tr>

					<td><?php echo getFlyerName($fetch44['flayer']); ?></td>

					<td><?php echo $fetch44['original_price'] ?></td>

					<td><?php echo $fetch44['qty'] ?></td> 

					<td><?php echo $fetch44['total_price'] ?></td>  

			  	</tr> 



			<?php 

		} 

	?>

		<tr>

			<td colspan="3"> </td>

			<td><strong>Grand Total : </strong><?php echo $total_price ?> </td>

			 

	  	</tr> 

</table>



</body>

</html>

<?php 

}else{

	header("location:index.php");

}

?>