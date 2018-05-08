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