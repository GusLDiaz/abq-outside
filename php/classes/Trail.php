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
	private $trailId;
	/**
	 * extrenal id for this Trail
	 * @var Uuid $trailExternalId
	 **/
	private $trailExternalId;
	/**
	 * physical address for this Trail
	 * @var string $trailAddress
	 **/
	private $trailAddress;
	/**
	 * image of this Trail
	 * @var string $trailImage
	 **/
	private $trailImage;
	/**
	 * name of this Trail
	 * @var string $trailName
	 **/
	private $trailName;
	/**
	 * detailed description of this Trail location
	 * @var string $trailLocation
	 **/
	private $trailLocation;
	/**
	 * constructor for this Trail
	 *
	 * @param UUID|string $newtrailId id of this Trail
	 * @param UUID|string $newtrailExternalId id of this Trail
	 * @param string $newTrailAddress address of this Trail
	 * @param string $newTrailImage image of this Trail
	 * @param string $newTrailName name of this Trail
	 * @param string $newTrailLocation location of this trail

	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
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
}