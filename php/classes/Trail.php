<?php
namespace Edu\Cnm\AbqOutside;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;
/**
 * This is the Trail class where we find data about each trail such as Id, External Id, Address, Image, Name, Location, Summary, Ascent, Rating, Length, Latitude and Longitude.
 *
 * @author Jullyane Hawkins <jhawkins20@cnm.edu>
 * @version 4.0.0
 **/
class Trail implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for this Trail; this is the primary key
	 * @var Uuid $trailId
	 **/
	protected $trailId;
	/**
	 * physical address for this Trail
	 * @var string $trailAddress
	 **/
	protected $trailAddress;
	/**
	 * ascent of this Trail
	 * @var int $trailAscent
	 **/
	protected $trailAscent;
	/**
	 * external id (from API)for this Trail
	 * @var string $trailExternalId
	 **/
	protected $trailExternalId;

	/**
	 * image of this Trail
	 * @var string $trailImage
	 **/
	protected $trailImage;
	/**
	 * latitude of this Trail
	 * @var float $trailLat;
	 **/
	protected $trailLat;
	/**
	 * length of this Trail
	 * @var float $trailLength;
	 **/
	protected $trailLength;
	/**
	 * detailed description of this Trail location
	 * @var string $trailLocation
	 **/
	protected $trailLocation;
	/**
	 * longitude of this Trail
	 * @var float $trailLong;
	 **/
	protected $trailLong;
	/**
	 * name of this Trail
	 * @var string $trailName
	 **/
	protected $trailName;
	/**
	 * rating of this Trail
	 * @var float $trailRating
	 **/
	protected $trailRating;
	/**
	 * summary of this Trail
	 * @var string $trailSummary
	 **/
	protected $trailSummary;
	/**
	 * constructor for this Trail
	 *
	 * @param string|UUID $newTrailId id of this Trail
	 * @param string $newTrailAddress address of this Trail
	 * @param int $newTrailAscent int containing trail ascent
	 * @param string $newTrailExternalId id of this Trail
	 * @param string $newTrailImage image of this Trail
	 * @param float $newTrailLat latitude of this trail
	 * @param float $newTrailLength length of this trail
	 * @param string $newTrailLocation location of this trail
	 * @param float $newTrailLong longitude of this trail
	 * @param string $newTrailName name of this Trail
	 * @param float $newTrailRating int containing trail rating
	 * @param string $newTrailSummary summary of this trail
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct($newTrailId, string $newTrailAddress, int $newTrailAscent, string $newTrailExternalId, string $newTrailImage, float $newTrailLat, float $newTrailLength, string $newTrailLocation, float $newTrailLong, string $newTrailName, float $newTrailRating, string $newTrailSummary) {
		try {
			$this->setTrailId($newTrailId);
			$this->setTrailAddress($newTrailAddress);
			$this->setTrailAscent($newTrailAscent);
			$this->setTrailExternalId($newTrailExternalId);
			$this->setTrailImage($newTrailImage);
			$this->setTrailLat($newTrailLat);
			$this->setTrailLength($newTrailLength);
			$this->setTrailLocation($newTrailLocation);
			$this->setTrailLong($newTrailLong);
			$this->setTrailName($newTrailName);
			$this->setTrailRating($newTrailRating);
			$this->setTrailSummary($newTrailSummary);
		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}
	/**
	 * accessor method for trail id
	 *
	 * @return Uuid value of trail id
	 **/
	public function getTrailId() : Uuid {
		return($this->trailId);
	}
	/**
	 * mutator method for trail id
	 *
	 * @param Uuid|string $newTrailId new value of trail id
	 * @throws \RangeException if $newTrailId is not positive
	 * @throws \TypeError if $newTrailId is not a uuid or string
	 **/
	public function setTrailId($newTrailId) : void {
		try {
			$uuid = self::validateUuid($newTrailId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the trail id
		$this->trailId = $uuid;
	}
	/**
	 * accessor method for trail address
	 *
	 * @return string value of trail address
	 **/
	public function getTrailAddress() : string {
		return($this->trailAddress);
	}
	/**
	 * mutator method for trail address
	 *
	 * @param string $newTrailAddress new value of trail address
	 * @throws \InvalidArgumentException if $newTrailAddress is not a string or insecure
	 * @throws \RangeException if $newTrailAddress is > 200 characters
	 * @throws \TypeError if $newTrailAddress is not a string
	 **/
	public function setTrailAddress(string $newTrailAddress) : void {
		// verify the address is secure
		$newTrailAddress = trim($newTrailAddress);
		$newTrailAddress = filter_var($newTrailAddress, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newTrailAddress) === true) {
			throw(new \InvalidArgumentException("trail address is empty or insecure"));
		}
		// verify the address will fit in the database
		if(strlen($newTrailAddress) > 200) {
			throw(new \RangeException("trail address too large"));
		}
		// store the address
		$this->trailAddress = $newTrailAddress;
	}
	/**
	 *accessor method for trail ascent
	 * @return int for trail ascent
	 **/
	public function getTrailAscent() : int {
		return ($this->trailAscent);
	}
	/**
	 * mutator method for trail ascent
	 *
	 * @param int $newTrailAscent new value of trail ascent
	 * @throws \InvalidArgumentException if $newTrailAscent is not an int or insecure
	 * @throws \RangeException if $newTrailAscent is > 255 characters
	 * @throws \TypeError if $newTrailAscent is not an int
	 **/
	public function setTrailAscent(int $newTrailAscent) {
		if($newTrailAscent < -32768 || $newTrailAscent > 32767) {
			throw(new RangeException("Trail ascent not positive"));
		}
		$this->trailAscent = intval($newTrailAscent);
	}
	/**
	 * accessor method for trail external id
	 *
	 * @return string proper trail external id
	 **/
	public function getTrailExternalId(): string {
		return($this->trailExternalId);
	}
	/**
	 * mutator method for trail external id
	 *
	 * @param string $newTrailExternalId new value of trail external id
	 * @throws \RangeException if $newTrailExternalId is not positive
	 * @throws \TypeError if $newTrailExternalId is not a uuid or string
	 **/
	public function setTrailExternalId(string $newTrailExternalId) : void {
		// verify the external Id is secure
		$newTrailExternalId = trim($newTrailExternalId);
		$newTrailExternalId = filter_var($newTrailExternalId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newTrailExternalId) === true) {
			throw(new \InvalidArgumentException("trail external id is empty or insecure"));
		}
		// verify the external id will fit in the database
		if(strlen($newTrailExternalId) > 200) {
			throw(new \RangeException("trail external id too large"));
		}
		// store the address
		$this->trailExternalId = $newTrailExternalId;
	}
	/**
	 * accessor method for trail image
	 *
	 * @return string value of trail image
	 **/
	public function getTrailImage() : string {
		return($this->trailImage);
	}
	/**
	 * mutator method for trail image
	 *
	 * @param string $newTrailImage new value of trail image
	 * @throws \InvalidArgumentException if $newTrailImage is not a string or insecure
	 * @throws \RangeException if $newTrailImage is > 200 characters
	 * @throws \TypeError if $newTrailImage is not a string
	 **/
	public function setTrailImage(string $newTrailImage) : void {
		// verify the image is secure
		$newTrailImage = trim($newTrailImage);
		$newTrailImage = filter_var($newTrailImage, FILTER_SANITIZE_URL);
		if(empty($newTrailImage) === true) {
			throw(new \InvalidArgumentException("trail image is empty or insecure"));
		}
		// verify the image will fit in the database
		if(strlen($newTrailImage) > 200) {
			throw(new \RangeException("image too large"));
		}
		// store the image
		$this->trailImage = $newTrailImage;
	}
	/** accessor method for trail latitude
	 *
	 * @return float value of trail latitude
	 **/
	public function getTrailLat() : float {
		return($this->trailLat);
	}
	/** mutator method for trail latitude
	 *
	 * @param float $newTrailLat new value of trail latitude
	 * @throws \InvalidArgumentException if $newTrailLat is not a float or insecure
	 * @throws \RangeException if $newTrailLat is not within -90 to 90
	 * @throws \TypeError if $newTrailLat is not a float
	 **/
	public function setTrailLat(float $newTrailLat) : void {
		// verify if the latitude exists
		if(floatval($newTrailLat) > 90) {
			throw(new \RangeException("trail latitude is not between -90 and 90"));
		}
		if (floatval($newTrailLat) < -90) {
			throw(new \RangeException("trail latitude is not between -90 and 90"));
		}
		// store the latitude
		$this->trailLat = $newTrailLat;
	}
	/** accessor method for trail length
	 *
	 * @return float value of trail length
	 **/
	public function getTrailLength() : float {
		return($this->trailLength);
	}
	/** mutator method for trail length
	 *
	 * @param float $newTrailLength new value of trail length
	 * @throws \InvalidArgumentException if $newTrailLength is not a float or insecure
	 * @throws \RangeException if $newTrailLength is not within 1 - 25
	 * @throws \TypeError if $newTrailLength is not a float
	 **/
	public function setTrailLength(float $newTrailLength) : void {
		// verify if the length exists
		if(floatval($newTrailLength) > 25) {
			throw(new \RangeException("trail length is not between 1 and 25"));
		}
		if (floatval($newTrailLength) < 1) {
			throw(new \RangeException("trail length is not between 1 and 25"));
		}
		// store the length
		$this->trailLength = $newTrailLength;
	}
	/**
	 * accessor method for trail location
	 *
	 * @return string value of trail location
	 **/
	public function getTrailLocation() : string {
		return($this->trailLocation);
	}
	/**
	 * mutator method for trail location
	 *
	 * @param string $newTrailLocation new value of trail location
	 * @throws \InvalidArgumentException if $newTrailLocation is not a string or insecure
	 * @throws \RangeException if $newTrailLocation is > 200 characters
	 * @throws \TypeError if $newTrailLocation is not a string
	 **/
	public function setTrailLocation(string $newTrailLocation) : void {
		// verify the trail location is secure
		$newTrailLocation = trim($newTrailLocation);
		$newTrailLocation = filter_var($newTrailLocation, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newTrailLocation) === true) {
			throw(new \InvalidArgumentException("trail location is empty or insecure"));
		}
		// verify if the location will fit in the database
		if(strlen($newTrailLocation) > 200) {
			throw(new \RangeException("trail location too large"));
		}
		// store the trail location
		$this->trailLocation = $newTrailLocation;
	}
	/** accessor method for trail longitude
	 *
	 *
	 * @return float value of trail longitude
	 **/
	public function getTrailLong() : float {
		return($this->trailLong);
	}
	/** mutator method for trail longitude
	 *
	 * @param float $newTrailLong new value of trail longitude
	 * @throws \InvalidArgumentException if $newTrailLong is not a float or insecure
	 * @throws \RangeException if $newTrailLong is not within -180 to 180
	 * @throws \TypeError if $newTrailLong is not a float
	 **/
	public function setTrailLong(float $newTrailLong) : void {
		// verify the longitude exists
		if(floatval($newTrailLong) > 180) {
			throw(new \RangeException("trail longitude is not between -180 and 180"));
		}
		if (floatval($newTrailLong) < -180) {
			throw(new \RangeException("trail longitude is not between -180 and 180"));
		}
		// store the longitude
		$this->trailLong = $newTrailLong;
	}
	/**
	 * accessor method for trail name
	 *
	 * @return string value of trail name
	 **/
	public function getTrailName() :string {
		return($this->trailName);
	}
	/**
	 * mutator method for trail name
	 *
	 * @param string $newTrailName new value of trail name
	 * @throws \InvalidArgumentException if $newTrailName is not a string or insecure
	 * @throws \RangeException if $newTrailName is > 200 characters
	 * @throws \TypeError if $newTrailName is not a string
	 **/
	public function setTrailName(string $newTrailName) : void {
		// verify the trail is secure
		$newTrailName = trim($newTrailName);
		$newTrailName = filter_var($newTrailName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newTrailName) === true) {
			throw(new \InvalidArgumentException("trail name is empty or insecure"));
		}
		// verify if the trail name will fit in the database
		if(strlen($newTrailName) > 200) {
			throw(new \RangeException("trail name too large"));
		}
		// store the trail name
		$this->trailName = $newTrailName;
	}
	/**
	 *accessor method for trail rating
	 * @return float for trail rating
	 **/
	public function getTrailRating() : float {
		return ($this->trailRating);
	}
	/**
	 * mutator method for trail rating
	 *
	 * @param float $newTrailRating new value of trail rating
	 * @throws \InvalidArgumentException if $newTrailRating is not an float or insecure
	 * @throws \RangeException if $newTrailLong is not within -180 to 180
	 * @throws \TypeError if $newTrailRating is not a float
	 **/
	public function setTrailRating(float $newTrailRating) {
		// verify the ratings exists
		if(floatval($newTrailRating) > 5) {
			throw(new \RangeException("trail rating is not between -180 and 180"));
		}
		if (floatval($newTrailRating) < 0) {
			throw(new \RangeException("trail rating is not between -180 and 180"));
		}
		// store the rating
		$this->trailRating = $newTrailRating;
	}
	/**
	 * accessor method for trail summary
	 *
	 * @return string value of trail summary
	 **/
	public function getTrailSummary() : string {
		return($this->trailSummary);
	}
	/**
	 * mutator method for trail summary
	 *
	 * @param string $newTrailSummary new value of trail summary
	 * @throws \InvalidArgumentException if $newTrailSummary is not a string or insecure
	 * @throws \RangeException if $newTrailSummary is > 200 characters
	 * @throws \TypeError if $newTrailSummary is not a string
	 **/
	public function setTrailSummary(string $newTrailSummary) : void {
		// verify if the summary is secure
		$newTrailSummary = trim($newTrailSummary);
		$newTrailSummary = filter_var($newTrailSummary, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newTrailSummary) === true) {
			throw(new \InvalidArgumentException("trail summary is empty or insecure"));
		}
		// verify the trail summary will fit in the database
		if(strlen($newTrailSummary) > 200) {
			throw(new \RangeException("trail summary too large"));
		}
		// store the summary
		$this->trailSummary = $newTrailSummary;
	}
	/**
	 * inserts this Trail into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		// create query template
		$query = "INSERT INTO trail(trailId, trailAddress, trailAscent, trailExternalId, trailImage, trailLat, trailLength, trailLocation, trailLong, trailName, trailRating, trailSummary) VALUES(:trailId, :trailAddress, :trailAscent, :trailExternalId, :trailImage, :trailLat, :trailLength, :trailLocation, :trailLong, :trailName, :trailRating, :trailSummary)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["trailId" => $this->trailId->getBytes(), "trailAddress" => $this->trailAddress, "trailAscent" => $this->trailAscent, "trailExternalId" => $this->trailExternalId, "trailImage" => $this->trailImage, "trailLat" => $this->trailLat, "trailLength" => $this->trailLength, "trailLocation" => $this->trailLocation, "trailLong" => $this->trailLong, "trailName" => $this->trailName, "trailRating" => $this->trailRating, "trailSummary" => $this->trailSummary];
		$statement->execute($parameters);
	}
	/**
	 * deletes this Trail from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		// create query template
		$query = "DELETE FROM trail WHERE trailId = :trailId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holder in the template
		$parameters = ["trailId" => $this->trailId->getBytes()];
		$statement->execute($parameters);
	}
	/**
	 * updates this Trail in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {
		// create query template
		$query = "UPDATE trail SET trailAddress = :trailAddress, trailAscent = :trailAscent, trailExternalId = :trailExternalId, trailImage = :trailImage, trailLat = :trailLat, trailLength = :trailLength, trailLocation = :trailLocation, trailLong = :trailLong, trailName = :trailName, trailRating = :trailRating, trailSummary = :trailSummary WHERE trailId = :trailId";
		$statement = $pdo->prepare($query);
		$parameters = ["trailId" => $this->trailId->getBytes(), "trailAddress" => $this->trailAddress, "trailAscent" => $this->trailAscent, "trailExternalId" => $this->trailExternalId, "trailImage" => $this->trailImage, "trailLat" => $this->trailLat, "trailLength" => $this->trailLength, "trailLocation" => $this->trailLocation, "trailLong" => $this->trailLong, "trailName" => $this->trailName, "trailRating" => $this->trailRating, "trailSummary" => $this->trailSummary];
		$statement->execute($parameters);
	}
	/**
	 * gets the Trail by trailId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $trailId trail id to search for
	 * @return Trail|null Trail found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getTrailByTrailId(\PDO $pdo, $trailId) : ?Trail {
		// sanitize the trailId before searching
		try {
			$trailId = self::validateUuid($trailId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT trailId, trailAddress, trailAscent, trailExternalId, trailImage, trailLat, trailLength, trailLocation, trailLong, trailName, trailRating, trailSummary FROM trail WHERE trailId = :trailId";
		$statement = $pdo->prepare($query);
		// bind the trail id to the place holder in the template
		$parameters = ["trailId" => $trailId->getBytes()];
		$statement->execute($parameters);
		// grab the trail from mySQL
		try {
			$trail = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$trail = new Trail($row["trailId"], $row["trailAddress"], $row["trailAscent"], $row["trailExternalId"], $row["trailImage"], $row["trailLat"], $row["trailLength"], $row["trailLocation"], $row["trailLong"], $row["trailName"], $row["trailRating"], $row["trailSummary"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($trail);
	}
	/**
	 * gets the Trail by distance
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param float $trailLat latitude coordinate of where trail is
	 * @param float $trailLong longitude coordinate of where trail is
	 * @param float $distance distance in miles that the trail is searched
	 * @return \SplFixedArray SplFixedArray of pieces of trail found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 * **/
	public static function getTrailByDistance(\PDO $pdo, float $trailLat, float $trailLong, float $distance) : \SplFixedArray {
		// create query template
		$query = "SELECT trailId, trailAddress, trailAscent, trailExternalId, trailImage, trailLat, trailLength, trailLocation, trailLong, trailName, trailRating, trailSummary FROM trail WHERE haversine(:trailLong, :trailLat, trailLong, trailLat) < :distance";
		$statement = $pdo->prepare($query);
		// bind the trail distance to the place holder in the template
		$parameters = ["distance" => $distance, "trailLat" => $trailLat, "trailLong" => $trailLong];
		$statement->execute($parameters);
		// build an array of trail
		$trails = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$trail = new Trail($row["trailId"], $row["trailAddress"], $row["trailAscent"], $row["trailExternalId"], $row["trailImage"], $row["trailLat"], $row["trailLength"], $row["trailLocation"], $row["trailLong"], $row["trailName"], $row["trailRating"], $row["trailSummary"]);
				$trails[$trails->key()] = $trail;
				$trails->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($trails);
	}
	/**
	 * gets all Trails
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Trails found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllTrails(\PDO $pdo) : \SPLFixedArray {
		// create query template
		$query = "SELECT trailId, trailAddress, trailAscent, trailExternalId, trailImage, trailLat, trailLength, trailLocation, trailLong, trailName, trailRating, trailSummary FROM trail";
		$statement = $pdo->prepare($query);
		$statement->execute();
		// build an array of trails
		$trails = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$trail = new Trail($row["trailId"], $row["trailAddress"], $row["trailAscent"], $row["trailExternalId"], $row["trailImage"], $row["trailLat"], $row["trailLength"], $row["trailLocation"], $row["trailLong"], $row["trailName"], $row["trailRating"], $row["trailSummary"]);
				$trails[$trails->key()] = $trail;
				$trails->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($trails);
	}
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);
		$fields["trailId"] = $this->trailId->toString();
		return($fields);
	}
}