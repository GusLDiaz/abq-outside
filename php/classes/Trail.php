<?php
namespace Edu\Cnm\AbqOutside;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;
/**
 *
 * This is the Trail class where we find data about each trail such as Id, Extrenal Id, Address, Image, Name, Location, Summary, Ascent, Rating, Length, Latitude and Longitude.
 *
 * @author Jullyane Hawkins <jhawkins20@cnm.edu>
 * @version 1.0.0
 **/
class Trail implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for this Trail; this is the primary key
	 * @var Uuid $trailId
	 **/
	protected $trailId;
	/**
	 * extrenal id for this Trail
	 * @var Uuid $trailExternalId
	 **/
	protected $trailExternalId;
	/**
	 * physical address for this Trail
	 * @var string $trailAddress
	 **/
	protected $trailAddress;
	/**
	 * image of this Trail
	 * @var string $trailImage
	 **/
	protected $trailImage;
	/**
	 * name of this Trail
	 * @var string $trailName
	 **/
	protected $trailName;
	/**
	 * detailed description of this Trail location
	 * @var string $trailLocation
	 **/
	protected $trailLocation;
	/**
	 * summary of this Trail
	 * @var string $trailSummary
	 **/
	protected $trailSummary;

	/**
	 * constructor for this Trail
	 *
	 * @param string|UUID $newTrailId id of this Trail
	 * @param string|UUID $newTrailExternalId id of this Trail
	 * @param string $newTrailAddress address of this Trail
	 * @param string $newTrailImage image of this Trail
	 * @param string $newTrailName name of this Trail
	 * @param string $newTrailLocation location of this trail
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct($newTrailId, $newTrailExternalId, string $newTrailAddress, string $newTrailImage, string $newTrailName, string $newTrailLocation) {
		try {
			$this->setTrailId($newTrailId);
			$this->setTrailExternalId($newTrailExternalId);
			$this->setTrailAddress($newTrailAddress);
			$this->setTrailImage($newTrailImage);
			$this->setTrailName($newTrailName);
			$this->setTrailLocation($newTrailLocation);
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
	 * accessor method for trail external id
	 *
	 * @return Uuid value of trail external id
	 **/
	public function getTrailExternalId() : Uuid {
		return($this->trailExternalId);
	}
	/**
	 * mutator method for trail external id
	 *
	 * @param Uuid|string $newTrailExternalId new value of trail external id
	 * @throws \RangeException if $newTrailExternalId is not positive
	 * @throws \TypeError if $newTrailExternalId is not a uuid or string
	 **/
	public function setTrailExternalId($newTrailExternalId) : void {
		try {
			$uuid = self::validateUuid($newTrailExternalId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the trail external id
		$this->trailExternalId = $uuid;
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
		$this->trailAddress = $newtrailAddress;
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
	/**
	 * inserts this Trail into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		// create query template
		$query = "INSERT INTO trail(trailId, trailExternalId, trailAddress, trailImage, trailName, trailLocation, trailSummary, trailAscent, trailRating, trailLength, trailLat, trailLong) VALUES(:trailId, :trailExternalId, :trailAddress, :trailImage, :trailName, :trailLocation, :trailSummary, :trailAscent, :trailRating, :trailLength, :trailLat, :trailLong)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["trailId" => $this->trailId->getBytes(), "trailExternalId" => $this->trailExternalId->getBytes(), "trailAddress" => $this->trailAddress, "trailImage" => $this->trailImage, "trailName" => $this->trailName, "trailLocation" => $this->trailLocation, "trailSummary" => $this->trailSummary, "trailAscent" => $this->trailAscent, "trailRating" => $this->trailRating, "trailLength" => $this->trailLength, "trailLat" => $this->trailLat, "trailLong" => $this->trailLong];
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
}