<?php
namespace Edu\Cnm\AbqOutside;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;
/**
 * Small Cross Section of a comment
 *
 * This Comment section is where we collect data from commentId, commentProfileId, commentTrailId, commentContent, and commentDateTime.
 *
 *
 * @author Edith Thakkar <egarcia262@cnm.edu>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 3.0.0
 **/
class Comment implements \JsonSerializable {
	use ValidateDate;
	use ValidateUuid;
	/**
	 * id for this Comment; this is the primary key
	 * @var Uuid $commentId
	 **/
	private $commentId;
	/**
	 * id of the commentProfileId that sent this Comment; this is a foreign key
	 * @var Uuid $commentProfileId
	 **/
	private $commentProfileId;
	/**
	 * Trail id for comment posted
	 * @var string $commentTrailId
	 **/
	private $commentTrailId;
	/**
	 * content for comment sent
	 * @var string $commentContent
	 **/
	private $commentContent;
	/**
	 * date and time this Comment was sent, in a PHP DateTime object
	 * @var \DateTime $commentDateTime
	 **/
	private $commentDateTime;

	/**
	 * constructor for this Comment
	 *
	 * @param string|Uuid $newCommentId id of this Comment or null if a new Comment
	 * @param string|Uuid $newCommentProfileId id of the profile who sent Comment
	 * @param string $newCommentTrailId id of the trail comment
	 * @param string $newCommentContent string containing actual comment data
	 * @param \DateTime|string|null $newCommentDateTime date and time Comment was sent or null if set to current date and time
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newcommentId, $commentProfileId, string $newcommentTrailId, $newcommentContent, $newcommentDateTime = null) {
		try {
			$this->setcommentId($newcommentId);
			$this->setcommentProfileId($newcommentProfileId);
			$this->setcommentTrailId($newcommentTrailId);
			$this->setcommentContent($newcommentContent);
			$this->setcommentDateTime($newcommentDateTime);
		} //determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}
}

/**
 * accessor method for comment id
 *
 * @return Uuid value of comment id
 **/
public function getCommentId() : Uuid {
	return($this->commentId);
}

/**
 * mutator method for comment id
 *
 * @param Uuid|string $newCommentId new value of comment id
 * @throws \RangeException if $newCommentId is not positive
 * @throws \TypeError if $newCommentId is not a uuid or string
 **/
public function setCommentId ( $newCommentId) : void {
	try {
		$uuid = self::validateUuid($newCommentId);
	} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
		$exceptionType = get_class($exception);
		throw(new $exceptionType($exception->getMessage(), 0, $exception));
	}

	// convert and store the comment id
	$this->commentId = $uuid;
}

/**
 * accessor method for comment profile id
 *
 * @return Uuid value of comment profile
 **/
public function getCommentProfilId() : Uuid {
	return($this->commentProfileId);
}

/**
 * mutator method for comment profile id
 *
 * @param Uuid|string $newCommentProfileId new value of comment profile id
 * @throws \RangeException if $newCommentProfileId is not positive
 * @throws \TypeError if $newCommentProfileId is not a uuid or string
 **/
public function setCommentProfileId ( $newCommentProfileId) : void {
	try {
		$uuid = self::validateUuid($newCommentProfileIdId);
	} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
		$exceptionType = get_class($exception);
		throw(new $exceptionType($exception->getMessage(), 0, $exception));
	}

	// convert and store the comment profile id
	$this->commentProfileId = $uuid;
}


