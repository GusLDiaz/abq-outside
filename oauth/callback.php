<?php
function
	$code = $_GET['code'];
	// a3de523a421210233a5

	if($code == "") {
		header('Location: https://bootcamp-coders.cnm.edu/');
		exit;
	}

	$CLIENT_ID =
	$CLIENT_SECRET =
	$URL = "https://github.com/login/oauth/access_token";
	// POST https://github.com/login/oauth/access_token

	// client_secret, client_id code

	$postParams = [
		'client_id' => $CLIENT_ID,
		'client_secret' => $CLIENT_SECRET,
		'code' => $code
	];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams),
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
	$response = curl_exec($ch);
	curl_close($ch);

	$data = json_decode($response);

	// Store token
	if($data->access_token != "") {
		session_start();
		$_SESSION['my_access_token_accessToken'] = $data->access_token;

		header('Location: https://bootcamp-coders.cnm.edu/');
		exit;
	}

	echo
	var_dump()

?>