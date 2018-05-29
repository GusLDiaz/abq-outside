<?php
/**
 * Created by PhpStorm.
 * User: Gusli
 * Date: 5/21/2018
 * Time: 3:06 PM
 */
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\AbqOutside\ {
	Trail
};

/**
 * Trail API
 **/
//verify the session, if it is not active, start it
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/outside.ini");
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
//sanitize input (id ~profileId)
	$trailId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$trailLength = filter_input(INPUT_GET, "trailLat", FILTER_SANITIZE_NUMBER_FLOAT);
	$distance = filter_input(INPUT_GET, "distance", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$userLat = filter_input(INPUT_GET, "userLat", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$userLong = filter_input(INPUT_GET, "userLong", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	if($method === "GET") {
//set XSRF cookie
		setXsrfCookie();
		if(empty($trailId) === false) {
			$trail = Trail::getTrailByTrailId($pdo, $trailId);
				$reply->data = $trail;
		} elseif(empty($userLat) === false && empty($userLong) === false && empty($distance) === false) {
			$trail = Trail::getTrailByDistance($pdo, $userLong, $userLat, $distance)->toArray();
				$reply->data = $trail;
		} else {
			$reply->data = Trail::getAllTrails($pdo)->toArray();
	}
}catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
	}
// In these lines, the Exceptions are caught and the $reply object is updated with the data from the caught exception. Note that $reply->status will be updated with the correct error code in the case of an Exception.
header("Content-type: application/json");
// sets up the response header.
if($reply->data === null) {
	unset($reply->data);
}
echo json_encode($reply);