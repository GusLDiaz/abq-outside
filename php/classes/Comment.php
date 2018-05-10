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

	/**
	 * accessor method for comment id
	 *
	 * @return Uuid value of comment id
	 **/
	public function getCommentId(): Uuid {
		return ($this->commentId);
	}

	/**
	 * mutator method for comment id
	 *
	 * @param Uuid|string $newCommentId new value of comment id
	 * @throws \RangeException if $newCommentId is not positive
	 * @throws \TypeError if $newCommentId is not a uuid or string
	 **/
	public function setCommentId($newCommentId): void {
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
	public function getCommentProfilId(): Uuid {
		return ($this->commentProfileId);
	}

	/**
	 * mutator method for comment profile id
	 *
	 * @param Uuid|string $newCommentProfileId new value of comment profile id
	 * @throws \RangeException if $newCommentProfileId is not positive
	 * @throws \TypeError if $newCommentProfileId is not a uuid or string
	 **/
	public function setCommentProfileId($newCommentProfileId): void {
		try {
			$uuid = self::validateUuid($newCommentProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the comment profile id
		$this->commentProfileId = $uuid;
	}

	/**
	 * accessor method for comment trail id
	 *
	 * @return String value of comment trail id
	 **/
	public function getCommentTrailId(): String {
		return ($this->commentTrailId);
	}

	/**
	 * mutator method for comment trail id
	 *
	 * @param string $newCommentTrailId new value of comment trail id
	 * @throws \RangeException if $newCommentTrailId is not positive
	 * @throws \TypeError if $newCommentTrailId is not an integer
	 **/
	public function setCommentTrailId($newCommentTrailId): void {
		try {
			$string = self::validateString($newCommentTrailId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the comment trail id
		$this->commentTrailId = $string;
	}

	/**
	 * accessor method for tweet content
	 *
	 * @return string value of tweet content
	 **/
	public function getTweetContent(): string {
		return ($this->tweetContent);
	}

	/**
	 * mutator method for comment content id
	 *
	 * @param string $newCommentContentId new value of comment content id
	 * @throws \InvalidArgumentException if $newCommentContentId is not a string or insecure
	 * @throws \RangeException if $newCommentContentId is > 140 characters
	 * @throws \TypeError if $newCommentContentId is not a string
	 **/
	public function setCommentContentId(string $newCommentContentId): void {
		// verify the comment content id is secure
		$newCommentContentId = trim($newCommentContentId);
		$newCommentContentId = filter_var($newCommentContentId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newCommentContentId) === true) {
			throw(new \InvalidArgumentException("comment content id is empty or insecure"));
		}

		// verify the comment content id will fit in the database
		if(strlen($newCommentContentId) > 140) {
			throw(new \RangeException("comment content id too large"));
		}

		// store the tweet content
		$this->setCommentContentId() = $newCommentContentId;
	}

	/**
	 * accessor method for content date time
	 *
	 * @return \DateTime value of content date time
	 **/
	public function getContentDateTime() : \DateTime {
		return($this->getContentDateTime());
	}

	/**
	 * mutator method for content date time
	 *
	 * @param \DateTime|string|null $newContentDatetime content date time as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newContentDateTime is not a valid object or string
	 * @throws \RangeException if $newContentDateTime is a date that does not exist
	 **/
	public function setContentDateTime($newContentDateTime = null) : void {
		// base case: if the date is null, use the current date and time
		if($newContentDateTime === null) {
			$this->getContentDateTime() = new \DateTime();
			return;
		}

		// store the comment date time using the ValidateDate trait
		try {
			$newContentDateTime = self::validateDateTime($newCommentDateTime);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->contentDateTime = $newContentDateTime;
	}

	/**
	 * inserts this Comment into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {

		// create query template
		$query = "INSERT INTO comment(commentId,commentProfileId, commentTrailId, commentContent, commentDateTime) VALUES(:commentId, :commentProfileId, :commentTrailId, :commentContent, :commentDateTime)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$formattedDate = $this->commentDateTime->format("Y-m-d H:i:s.u");
		$parameters = ["commentId" => $this->commentId->getBytes(), "commentProfileId" => $this->commentProfileId->getBytes(), "commentTrailId" => $this->commentTrailId, "commentContent" => $this->commentContent, "commentDateTime" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Comment from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {

		// create query template
		$query = "DELETE FROM comment WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["commentId" => $this->commentId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this Comment in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {

		// create query template
		$query = "UPDATE tweet SET commentId = :commentId, commentProfileId = :commentProfileId, commentTrailId = :commentTrailId, commentTrailId = :commentContent, = :commentContent, commentDateTime = :commentDateTime WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);


		$formattedDate = $this->commentDateTime->format("Y-m-d H:i:s.u");
		$parameters = ["commentId" => $this->commentId->getBytes(),"commentProfileId" => $this->commentProfileId->getBytes(), "commentTrailId" => $this->commentTrailId, "commentContent" => $this->commentContent, "commentDateTime" => $formattedDate];
		$statement->execute($parameters);
	}




