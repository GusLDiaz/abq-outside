<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\AbqOutside;

/**  ___________________________________
 *
 * Light PHP wrapper for the OAuth 2.0
 * ___________________________________
 *
 *
 * AUTHOR & CONTACT
 * ================
 *
 * Charron Pierrick
 * - pierrick@webstart.fr
 *
 * Berejeb Anis
 * - anis.berejeb@gmail.com
 *
 *
 *
 *
 * DOCUMENTATION & DOWNLOAD
 * ========================
 *
 * Latest version is available on github at :
 * - https://github.com/adoy/PHP-OAuth2
 *
 * Documentation can be found on :
 * - https://github.com/adoy/PHP-OAuth2
 *
 *
 * LICENSE
 * =======
 *
 * This Code is released under the GNU LGPL
 *
 * Please do not change the header of the file(s).
 *
 * This library is free software; you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * See the GNU Lesser General Public License for more details.  **/
// verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
// prepare an empty reply
$reply = new \stdClass();
$reply->status = 200;
$reply->data = null;
try {
	//grab the MySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/outside.ini");
	$config = readConfig("/etc/apache2/capstone-mysql/outside.ini");
	$oauth = json_decode($config["github"]);
	// now $oauth->github->clientId and $oauth->github->clientKey exist
	$REDIRECT_URI = "https://bootcamp-coders.cnm.edu/~egarcia262/abq-outside/public_html/oauth/";
	$AUTHORIZATION_ENDPOINT = 'https://github.com/login/oauth/authorize';
	$TOKEN_ENDPOINT = 'https://github.com/login/oauth/authorize';
	$client = new \OAuth2\Client($oauth->clientId, $oauth->clientKey);
	if(!isset($_GET['code'])) {
		$auth_url = $client->getAuthenticationUrl($AUTHORIZATION_ENDPOINT, $REDIRECT_URI, ['scope' => 'user:email']);
		header('Location: ' . $auth_url);
		die('Redirect');
	} else {

		$params = ['code' => $_GET['code'], 'redirect_uri' => $REDIRECT_URI];
		$response = $client->getAccessToken($TOKEN_ENDPOINT, 'authorization_code', $params);
		parse_str($response['result'], $info);
		$client->setAccessToken($info['access_token']);
		$profileRefreshToken = $info['access_token'];
		$response = $client->fetch('https://api.github.com/user', [], 'GET', ['User-Agent' => 'Jack Auto Deleter v NaN']);
		$profileName = $response["result"]["login"];
		$profileImage = $response["result"]["avatar_url"];
		$response = $client->fetch('https://api.github.com/user/emails', [], 'GET', ['User-Agent' => 'Jack Auto Deleter v NaN']);

		// get profile by email to see if it exists, if it does not then create a new one
		$profile = Profile::getProfileByProfileUsername($pdo, $profileName);
		if(($profile) === null) {
			// create a new profile
			$user = new Profile(generateUuidV4(), $profileImage, $profileRefreshToken, $profileName);
			$user->insert($pdo);
			$reply->message = "Welcome to Abq Outside!";
		} else {
			$reply->message = "Welcome back to Abq Outside!";
		}
		//grab profile from database and put into a session
		$profile = Profile::getProfileByProfileUsername($pdo, $profileName);
		$_SESSION["profile"] = $profile;

		header("Location: ../../");
	}
} catch(\Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
} catch(\TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
	$reply->trace = $typeError->getTraceAsString();
}