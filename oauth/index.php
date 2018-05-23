<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DeepDiveOAuth\Profile;

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
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/deepDiveOauth.ini");
	$config = readConfig("/etc/apache2/capstone-mysql/deepDiveOauth.ini");
	$oauth = json_decode($config["github"]);
// now $oauth->github->clientId and $oauth->github->clientKey exist
	$REDIRECT_URI = "https://bootcamp-coders.cnm.edu/~egarcia262/~outside/public_html/oauth";
	$AUTHORIZATION_ENDPOINT = 'https://github.com/login/oauth/authorize';
	$TOKEN_ENDPOINT = 'https://github.com/login/oauth/authorize';
	$client = new \OAuth2\Client($oauth->clientId, $oauth->clientKey);
	if(!isset($_GET['code'])) {
		$auth_url = $client->getAuthenticationUrl($AUTHORIZATION_ENDPOINT, $REDIRECT_URI, ['scope' => 'user:email']);
		header('Location: ' . $auth_url);
		die('Redirect');
	} else {