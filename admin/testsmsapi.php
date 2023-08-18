$http_query = http_build_query([
			'action'  => 'send-sms',
			'api_key' => 'cnd6PU12PXpGTE9jemF3aUNqdHM=',
			'from'    => 'SMS Alert',//sender ID
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