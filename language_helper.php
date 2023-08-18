<?php
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	//If session is still not set so,
	$language = mysqli_query($con,"SELECT * FROM portal_language WHERE is_default=1");
    $response = mysqli_fetch_assoc($language);
    if (!isset($response) && empty($response)) {
    	$response = array(
                'language'=>'english',
                'direction'=>'ltr',
                'id'=>1
            );
    }
	if (!isset($_SESSION['language']) && empty($_SESSION['language'])) {
		$_SESSION['language'] = $response['language'];
	}
	if (!isset($_SESSION['language_id']) && empty($_SESSION['language_id'])) {
        $_SESSION['language_id'] = $response['id'];
	}
	// code for the GET language
	$dynamic_id='';

	if(isset($_GET['language'])){

		$language=$_GET['language'];

		$_SESSION['language'] = $_GET['language'];
		$_SESSION['language_id'] = $_GET['language_id'];
		$language_id = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : $_GET['language_id'];
		$sql_lang = mysqli_query($con,"SELECT * FROM portal_language WHERE id=".$language_id);
		$response = mysqli_fetch_assoc($sql_lang);
		$dynamic_id = isset($response['dynamic_id']) ? $response['dynamic_id'] : '';

		header('location: '.$_SERVER["HTTP_REFERER"]);
	}

	// code for the session language

	if (isset($_SESSION['language']) && !empty($_SESSION['language'])){
		$sql_lang = mysqli_query($con,"SELECT * FROM portal_language WHERE language='".$_SESSION['language']."'");

		$response = mysqli_fetch_assoc($sql_lang);
		$dynamic_id = isset($response['dynamic_id']) ? $response['dynamic_id'] : '';
		require_once('language/'.$_SESSION['language'].'.php');
	}
if (!function_exists("getLange")){
	function getLange($key)
	{
		global $lang;
		if(isset($lang[$key])){
			return $lang[$key];
		}
		else{
			return $key;
		}
	}
	}