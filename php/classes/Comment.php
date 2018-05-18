<?php
namespace Edu\Cnm\AbqOutside;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use PDOException;
use Ramsey\Uuid\Uuid;
/**
 * Small Cross Section of a comment
 *
 * This Comment section is where we collect data from commentId, commentProfileId, commentTrailId, commentContent, and commentTimestamp.
 *
 *
 * @author Edith Thakkar <egarcia262@cnm.edu>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 3.0.0
 **/
class Comment implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;
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
	 * @var Uuid $commentTrailId
	 **/
	private $commentTrailId;
	/**
	 * content for comment sent
	 * @var string $commentContent
	 **/
	private $commentContent;
	/**
	 * date and time this Comment was sent, in a PHP DateTime object
	 * @var \DateTime $commentTimestamp
	 **/
	private $commentTimestamp;
	/**
	 * constructor for this Comment
	 *
	 * @param string|Uuid $newCommentId id of this Comment or null if a new Comment
	 * @param string|Uuid $newCommentProfileId id of the profile who sent Comment
	 * @param string|Uuid $newCommentTrailId id of the trail comment
	 * @param string $newCommentContent string containing actual comment data
	 * @param \DateTime|string|null $newCommentTimestamp date and time Comment was sent or null if set to current date and time
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	//hint the parameters
	public function __construct($newCommentId, $newCommentProfileId, $newCommentTrailId, string $newCommentContent, $newCommentTimestamp) {
		try {
			$this->setCommentId($newCommentId);
			$this->setCommentProfileId($newCommentProfileId);
			$this->setCommentTrailId($newCommentTrailId);
			$this->setCommentContent($newCommentContent);
			$this->setCommentTimestamp($newCommentTimestamp);
		} //determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * acessor method for comment id
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
			$newCommentId = self::ValidateUuid($newCommentId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the comment id
		$this->commentId = $newCommentId;
	}

	/**
	 * accessor method for comment profile id
	 *
	 * @return Uuid value of comment profile
	 **/
	public function getCommentProfileId(): Uuid {
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
			$newCommentProfileId = self::ValidateUuid($newCommentProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the comment profile id
		$this->commentProfileId = $newCommentProfileId;
	}

	/**
	 * accessor method for comment trail id
	 *
	 * @return Uuid value of comment trail id
	 **/
	public function getCommentTrailId(): Uuid {
		return ($this->commentTrailId);
	}

	/**
	 * mutator method for comment trail id
	 *
	 * @param Uuid $newCommentTrailId new value of comment trail id
	 * @throws \RangeException if $newCommentTrailId is not positive
	 * @throws \TypeError if $newCommentTrailId is not a uuid or string
	 **/
	public function setCommentTrailId($newCommentTrailId): void {
		try {
			$newCommentTrailId = self::ValidateUuid($newCommentTrailId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the comment trail id
		$this->commentTrailId = $newCommentTrailId;
	}

	/**
	 * accessor method for comment Content
	 *
	 * @return string value of comment Content
	 **/
	public function getCommentContent(): string {
		return ($this->commentContent);
	}

	/**
	 * mutator method for comment content
	 *
	 * @param string $newCommentContent new value of comment content
	 * @throws \InvalidArgumentException if $newCommentContent is not a string or insecure
	 * @throws \RangeException if $newCommentContent is > 140 characters
	 * @throws \TypeError if $newCommentContent is not a string
	 **/
	public function setCommentContent(string $newCommentContent): void {
		// verify the comment content is secure
		$newCommentContent = trim($newCommentContent);
		$newCommentContent = filter_var($newCommentContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newCommentContent) === true) {
			throw(new \InvalidArgumentException("comment content is empty or insecure"));
		}
		// verify the comment content id will fit in the database
		if(strlen($newCommentContent) > 255) {
			throw(new \RangeException("comment content too large"));
		}
		// store the comment content
		$this->commentContent = $newCommentContent;
	}

	/**
	 * accessor method for comment Timestamp
	 *
	 * @return \DateTime value of comment Timestamp
	 **/
	public function getCommentTimestamp(): \DateTime {
		return ($this->commentTimestamp);
	}

	/**
	 * mutator method for comment timestamp
	 *
	 * @param \DateTime|string|null $newCommentTimestamp content date time as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newCommentTimestamp is not a valid object or string
	 * @throws \RangeException if $newCommentTimestamp is a date that does not exist
	 **/
	public function setCommentTimestamp($newCommentTimestamp = null): void {
		// base case: if the date is null, use the current date and time
		if($newCommentTimestamp === null) {
			$this->commentTimestamp = new \DateTime();
			return;
		}
		// store the comment timestamp using the ValidateDate trait
		try {
			$newCommentTimestamp = self::validateDateTime($newCommentTimestamp);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->commentTimestamp = $newCommentTimestamp;
	}

	/**
	 * gets the Comment by commentId
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
		$query = "SELECT commentId, commentProfileId, commentTrailId, commentContent, commentTimestamp FROM comment WHERE commentId = :commentId";
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
				$comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentTrailId"], $row["commentContent"], $row["commentTimestamp"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($comment);
	}

	/**
	 * gets the Comment by commentProfileId
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
		$query = "SELECT commentId, commentProfileId, commentTrailId, commentContent, commentTimestamp FROM comment WHERE commentProfileId = :commentProfileId";
		$statement = $pdo->prepare($query);
		// bind the comment profile id to the place holder in the template
		$parameters = ["commentProfileId" => $commentProfileId->getBytes()];
		$statement->execute($parameters);
		// build an array of comments
		$comments = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentTrailId"], $row["commentContent"], $row["commentTimestamp"]);
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
	 * gets the Comment by commentTrailId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param uuid |string $commentTrailId comment trail id to search for
	 * @return \SplFixedArray SplFixedArray of Comments found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getCommentByCommentTrailId(\PDO $pdo, $commentTrailId): \SplFixedArray {
		try {
			$commentTrailId = self::validateUuid($commentTrailId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT commentId, commentProfileId, commentTrailId, commentContent, commentTimestamp FROM comment WHERE commentTrailId = :commentTrailId";
		$statement = $pdo->prepare($query);
		// bind the comment profile id to the place holder in the template
		$parameters = ["commentTrailId" => $commentTrailId->getBytes()];
		$statement->execute($parameters);
		// build an array of comments
		$comments = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentTrailId"], $row["commentContent"], $row["commentTimestamp"]);
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
	 * gets the Comment by commentContent
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
		$query = "SELECT commentId, commentProfileId, commentTrailId, commentContent, commentTimestamp FROM comment WHERE commentContent LIKE :commentContent";
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
				$comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentTrailId"], $row["commentContent"], $row["commentTimestamp"]);
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
	 * gets the Comments by commentTimestamp
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param \DateTime $commentTimestamp comment time stamp to search for
	 * @return \SplFixedArray SplFixedArray of Comments found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getCommentByCommentTimestamp(\PDO $pdo, string $commentTimestamp): \SplFixedArray {
		// sanitize the description before searching
		$commentTimestamp = trim($commentTimestamp);
		$commentTimestamp = filter_var($commentTimestamp, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($commentTimestamp) === true) {
			throw(new \PDOException("comment content is invalid"));
		}
		// escape any mySQL wild cards
		$commentTimestamp = str_replace("_", "\\_", str_replace("%", "\\%", $commentTimestamp));
		// create query template
		$query = "SELECT commentId, commentProfileId, commentTrailId, commentContent, commentTimestamp FROM comment WHERE commentTimestamp LIKE :commentTimestamp";
		$statement = $pdo->prepare($query);
		// bind the comment content to the place holder in the template
		$commentTimestamp = "%$commentTimestamp%";
		$parameters = ["commentTimestamp" => $commentTimestamp];
		$statement->execute($parameters);
		// build an array of comments
		$comments = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentProfileId"], $row["commentTrailId"], $row["commentContent"], $row["$commentTimestamp"]);
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
	 * inserts this Comment into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {
		// create query template
		$query = "INSERT INTO comment(commentId,commentProfileId, commentTrailId, commentContent, commentTimestamp) VALUES(:commentId, :commentProfileId, :commentTrailId, :commentContent, :commentTimestamp)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$newCommentTimestamp = $this->commentTimestamp->format("Y-m-d H:i:s.u");
		$parameters = ["commentId" => $this->commentId->getBytes(), "commentProfileId" => $this->commentProfileId->getBytes(), "commentTrailId" => $this->commentTrailId->getBytes(), "commentContent" => $this->commentContent, "commentTimestamp" => $newCommentTimestamp];
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

	/**
	 * updates this Comment in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo): void {
		// create query template
		$query = "UPDATE comment SET commentId = :commentId, commentProfileId = :commentProfileId, commentTrailId = :commentTrailId, commentContent = :commentContent, commentTimestamp = :commentTimestamp WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);
		$newCommentTimestamp = $this->commentTimestamp->format("Y-m-d H:i:s");
		$parameters = ["commentId" => $this->commentId->getBytes(), "commentProfileId" => $this->commentProfileId->getBytes(), "commentTrailId" => $this->commentTrailId->getBytes(), "commentContent" => $this->commentContent, "commentTimestamp" => $newCommentTimestamp];
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
		$fields["commentTrailId"] = $this->commentTrailId->toString();
		//format the date so that the front end can consume it
		$fields["commentTimestamp"] = round(floatval($this->commentTimestamp->format("U.u")) * 1000);
		return ($fields);
	}
}
