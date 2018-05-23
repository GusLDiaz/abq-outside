<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\AbqOutside\{
	Comment, Profile, Trail
};
/**
 * api for the Comment class
 *
 * @author Jullyane Hawkins <jullyanehawkins@gmail.com>
 **/
//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/outside.ini");
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	//sanitize the search parameters
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$commentProfileId = $id = filter_input(INPUT_GET, "commentProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentTrailId = $id = filter_input(INPUT_GET, "commentTrailId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//gets a specific comment based on its commentId
		if(empty($id) === false) {
			$comment = Comment::getCommentByCommentId($pdo, $id);
			if($comment !== null) {
				$reply->data = $comment;
			}
			//get all the comments associated with a profileId
		} else if(empty($commentProfileId) === false) {
			$comment = Comment::getCommentByCommentProfileId($pdo, $commentProfileId)->toArray();
			if($comment !== null) {
				$reply->data = $comment;
			}
			//get all the comments associated with the trailId
		} else if(empty($commentTrailId) === false) {
			$comment = Comment::getCommentByCommentTrailId($pdo, $commentTrailId)->toArray();
			if($comment !== null) {
				$reply->data = $comment;
			}
		} else {
			throw new InvalidArgumentException("incorrect search parameters ", 404);
		}
		/**
		 * Post for Comment
		 **/
	} else if($method === "POST") {
		//enforce that the end user has a XSRF token
		verifyXsrf();
		//enforce the end user has a JWT token
		validateJwtHeader();
		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		if(empty($requestObject->commentProfileId) === true) {
			throw (new \InvalidArgumentException("no profile linked to the comment", 405));
		}
		if(empty($requestObject->commentTrailId) === true) {
			throw (new \InvalidArgumentException("no trail linked to the comment", 405));
		}
		if(empty($requestObject->commentTimestamp) === true) {
		//$requestObject->commentTimestamp = date("y-m-d H:i:s");
		}
		// enforce the user is signed in
		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("you must be logged in to comment on a trail", 403));
		}
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $requestObject->commentTrailId, $_SESSION["profile"]->getProfileId(), $requestObject->commentContent, $requestObject->commentTimestamp);
		$comment->insert($pdo);
		$reply->message = "comment posted successfully";
		// if any other HTTP request is sent throw an exception
	} else {
		throw new \InvalidArgumentException("invalid http request", 400);
	}
	//catch any exceptions that is thrown and update the reply status and message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
// encode and return reply to front end caller
echo json_encode($reply);