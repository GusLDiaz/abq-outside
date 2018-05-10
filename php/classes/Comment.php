<?php

namespace Edu\Cnm\AbqOutside;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use PDOException;
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
	public function __construct($newCommentId, $newCommentProfileId = null, string $newCommentTrailId, $newCommentContent, $newCommentDateTime = null) {
		try {
			$this->setcommentId($newCommentId);
			$this->setcommentProfileId($newCommentProfileId);
			$this->setcommentTrailId($newCommentTrailId);
			$this->setcommentContent($newCommentContent);
			$this->setcommentDateTime($newCommentDateTime);
		} //determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
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
	 * gets the Comment by comment Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $commentId comment id to search for
	 * @return Comment|null Comment found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getCommentByCommentId(\PDO $pdo, $commentId): ?Comment {
		// sanitize the commentId before searching
		try {
			$commentId = self::validateUuid($commentId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT commentId, commentProfileId, commentTrailId, commentContent, commentDateTime FROM comment WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		// bind the comment id to the place holder in the template
		$parameters = ["commentId" => $commentId->getBytes()];
		$statement->execute($parameters);

		// grab the comment from mySQL
		try {
			$comment = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentTrailId"], $row["commentContent"], $row["commentDateTime"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($comment);
	}

	/**
	 * gets the Comment by comment profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $commentProfileId comment profile id to search by
	 * @return \SplFixedArray SplFixedArray of Comments found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getCommentByCommentProfileId(\PDO $pdo, $commentProfileId): \SplFixedArray {

		try {
			$commentProfileId = self::validateUuid($commentProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT commentId, commentProfileId, commentTrailId, commentContent, commentDateTime FROM comment WHERE commentProfileId = :commentProfileId";
		$statement = $pdo->prepare($query);
		// bind the comment profile id to the place holder in the template
		$parameters = ["commentProfileId" => $commentProfileId->getBytes()];
		$statement->execute($parameters);
		// build an array of comments
		$comments = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentTrailId"], $row["commentContent"], $row["commentDateTime"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($comments);
	}

	/**
	 * gets the Comment by comment trail id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $commentTrailId comment trail id to search for
	 * @return \SplFixedArray SplFixedArray of Comments found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getCommentBycommentTrailId(\PDO $pdo, string $commentTrailId): \SplFixedArray {
		// sanitize the description before searching
		$commentTrailId = trim($commentTrailId);
		$commentTrailId = filter_var($commentTrailId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($commentTrailId) === true) {
			throw(new \PDOException("comment trail id is invalid"));
		}

		// escape any mySQL wild cards
		$commentTrailId = str_replace("_", "\\_", str_replace("%", "\\%", $commentTrailId));

		// create query template
		$query = "SELECT commentId, commentProfileId, commentTrailId, commentContent, commentDateTime FROM comment WHERE commentTrailId LIKE :commentTrailId";
		$statement = $pdo->prepare($query);

		// bind the comment trail id to the place holder in the template
		$commentTrailId = "%$commentTrailId%";
		$parameters = ["commentTrailId" => $commentTrailId];
		$statement->execute($parameters);

		// build an array of comments
		$comments = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentTrailId"], $row["commentContent"], $row["commentDateTime"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($comments);
	}

	/**
	 * gets the Comment by comment Content
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $commentContent comment content to search for
	 * @return \SplFixedArray SplFixedArray of Comments found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getCommentByCommentContent(\PDO $pdo, string $commentContent): \SplFixedArray {
		// sanitize the description before searching
		$commentContent = trim($commentContent);
		$commentContent = filter_var($commentContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($commentContent) === true) {
			throw(new \PDOException("comment content is invalid"));
		}

		// escape any mySQL wild cards
		$commentContent = str_replace("_", "\\_", str_replace("%", "\\%", $commentContent));

		// create query template
		$query = "SELECT commentId, commentProfileId, commentTrailId, commentcontent, commentDateTime FROM comment WHERE commentContent LIKE :commentContent";
		$statement = $pdo->prepare($query);

		// bind the comment content to the place holder in the template
		$commentContent = "%$commentContent%";
		$parameters = ["commentContent" => $commentContent];
		$statement->execute($parameters);

		// build an array of comments
		$comments = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentTrailId"], $row["commentContent"], $row["commentDateTime"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($comments);
	}

	/**
	 * gets the Comments by comment date time
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $commentDate comment date time to search for
	 * @return \SplFixedArray SplFixedArray of Comments found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getCommentByCommentDateTime(\PDO $pdo, string $commentDateTime): \SplFixedArray {
		// sanitize the description before searching
		$commentDateTime = trim($commentDateTime);
		$commentDateTime = filter_var($commentDateTime, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($commentDateTime) === true) {
			throw(new \PDOException("comment content is invalid"));
		}

		// escape any mySQL wild cards
		$commentDateTime = str_replace("_", "\\_", str_replace("%", "\\%", $commentDateTime
		));

		// create query template
		$query = "SELECT commentId, commentProfileId, commentTrailId, commentContent, commentDateTime FROM comment WHERE commentDateTime LIKE :commentDateTime";
		$statement = $pdo->prepare($query);

		// bind the comment content to the place holder in the template
		$commentDateTime = "%$commentDateTime%";
		$parameters = ["commentDateTime" => $commentDateTime];
		$statement->execute($parameters);

		// build an array of comments
		$comments = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentTrailId"], $row["commentContent"], $row["$commentDateTime"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($comments);
	}

	public static function getAllComments(\PDO $pdo): \SPLFixedArray {
		// create query template
		$query = "SELECT commentId, commentProfileId, commentTrailId, commentContent, commentDateTime FROM comment";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of comments
		$comments = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentTrailId"], $row["commentContent"], $row["commentDateTime"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($comments);
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
	 * accessor method for comment content
	 *
	 * @return string value of comment content
	 **/
	public function getCommentContent(): string {
		return ($this->commentContentContent);
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

		// store the comment content
		$this->CommentContentId = $newCommentContentId;
	}

	/**
	 * accessor method for content date time
	 *
	 * @return \DateTime value of content date time
	 **/
	public function getContentDateTime(): \DateTime {
		return ($this->commentDateTime);
	}

	/**
	 * mutator method for content date time
	 *
	 * @param \DateTime|string|null $newContentDatetime content date time as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newContentDateTime is not a valid object or string
	 * @throws \RangeException if $newContentDateTime is a date that does not exist
	 **/
	public function setCommentTimestamp($newCommentTimestamp = null): void {
		// base case: if the date is null, use the current date and time
		if($newCommentTimestamp === null) {
			$this->getContentDateTime = new \DateTime();
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
	 * @throws PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {

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
	 * @throws PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo): void {

		// create query template
		$query = "DELETE FROM comment WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["commentId" => $this->commentId->getBytes()];
		$statement->execute($parameters);
	}

	/* gets all Comments
	*
	* @param \PDO $pdo PDO connection object
	* @return \SplFixedArray SplFixedArray of Comments found or null if not found
	* @throws \PDOException when mySQL related errors occur
	* @throws \TypeError when variables are not the correct data type
	**/

	/**
	 * updates this Comment in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo): void {

		// create query template
		$query = "UPDATE comment SET commentId = :commentId, commentProfileId = :commentProfileId, commentTrailId = :commentTrailId, commentTrailId = :commentContent, = :commentContent, commentDateTime = :commentDateTime WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);


		$formattedDate = $this->commentDateTime->format("Y-m-d H:i:s.u");
		$parameters = ["commentId" => $this->commentId->getBytes(), "commentProfileId" => $this->commentProfileId->getBytes(), "commentTrailId" => $this->commentTrailId, "commentContent" => $this->commentContent, "commentDateTime" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize(): array {
		$fields = get_object_vars($this);

		$fields["commentId"] = $this->commentId->toString();
		$fields["commentProfileId"] = $this->commentProfileId->toString();

		//format the date so that the front end can consume it
		$fields["commentDateTime"] = round(floatval($this->commentDateTime->format("U.u")) * 1000);
		return ($fields);
	}
}

