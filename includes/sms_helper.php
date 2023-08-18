<?php 
include_once "conn.php";
/**

 * Helper to create PDF files using dompdf.

 * 

 * See http://code.google.com/p/dompdf/ for more info on dompdf.

 * 

 * This spark is basically a wrapper around the information supplied at

 * https://github.com/EllisLab/CodeIgniter/wiki/PDF-generation-using-dompdf

 * 

 * @link		https://github.com/POWCorp/codeigniter-dompdf-spark

 * @package		helpers

 * @author		Jan Lindblom <jan@powcorp.se>

 * @copyright	Copyright (c) 2012, POW! Corp.

 * @license		The dompdf helper is MIT licensed, dompdf is lGPL.

 * @version		0.5.3

 */



if ( ! function_exists('send_sms')) {

	/**

	 * Create a PDF using dompdf.

	 * 

	 * @access public

	 * @param string $html the HTML to render (default: '').

	 * @param string $filename optional file name to store the pdf (default: '').

	 * @param mixed $stream whether or not to stream to browser (default: false).

	 * @return mixed the raw PDF output if $stream is true, otherwise the PDF is

	 *         streamed to a file. **/

	function send_sms($to = '', $message = '', $config = array()) {

		require_once ("rest_api/Twilio/autoload.php");



		// $sid = "ACc45fca8f9ed94fe6b113fbc7cecada93"; // Your Account SID from www.twilio.com/console

		// $token = "904bb97b8623e4f9fe8dc61cf1a8658a"; // Your Auth Token from www.twilio.com/console

		$from = '+1 913-246-5691';

		$sid = 'AC7c8b77f0f55774183b42f8f17a65c746';

		$token = '0c6664a79a30b640e95c6a7f81382fff';

		$client = new Twilio\Rest\Client($sid, $token);

		



		try {

			$message = $client->messages->create(

			  $to, // Text this number

			  array(

			    'from' => $from, // From a valid Twilio number

			    'body' => $message

			  )

			);

		} catch (Exception $e) {

			return false;

		}

		if(isset($message->sid) && $message->sid != '')

			return true;

		else

			return false;

	}

}

 
if (!function_exists('send_sms_itvision')) 
{
	function send_sms_itvision( $message = '', $sender_phone_no, $reciever_phone_no) 
	{
		global $con;

		$sms_data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM sms_settings WHERE id=1 ")); 

		//////////////SMS///////////////
		// $sms = "";
	 //    $sms .= "Dear Customer, \r\n";
		// $sms .= "Your shipment from ".$record['sbname']." with tracking number ".$record['track_no']." has been picked by Maf Express. Track at www.mafexpress.com.";

		$sphone = $sender_phone_no;
		$sphone  = preg_replace('/[^0-9]/s','',$sphone);
		$pos0 = substr($sphone, 0,1);
		if($pos0 == '3'){
			$alterno=substr($sphone,1);
			$alterno = '0'.$sphone;
			$sphone = $alterno;
		}
		$pos = substr($sphone, 0,2);
		if($pos == '03'){
			$alterno=substr($sphone,1);
			$alterno = '92'.$alterno; 
			$sphone = $alterno;
		}


		$sms = $message; 

		$http_query = http_build_query([
			'action'  => 'send-sms',
			'api_key' => $sms_data['api_key'],
			'from'    => $sms_data['mask_from'],//sender ID
			'to'      => trim($reciever_phone_no.','.$sphone),
			'sms'     => $sms,
		]); 
		
		$url = 'https://login.brandedsms.me/sms/api?'.$http_query;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		ob_start();
		$response = curl_exec($ch);
		ob_end_clean();
		curl_close($ch);
		//////////////SMS/////////////// 

	}
	 
}




?>