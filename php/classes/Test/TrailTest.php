<?php
namespace Edu\Cnm\AbqOutside\Test;
use Edu\Cnm\AbqOutside\Trail;
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");
// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");
/**
 * Full PHPUnit test for the Trail class
 *
 * This is a complete PHPUnit test of the Trail class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Trail
 * @author Jullyane Hawkins <jhawkins20@cnm.edu>
 **/
class TrailTest extends AbqOutsideTest {
	/**
	 * external id of this trail
	 * @var string $VALID_TRAILEXTERNALID
	 **/
	protected $VALID_TRAILEXTERNALID = "313572";
	/**
	 * address of this trail
	 * @var string $VALID_TRAILADDRESS
	 **/
	protected $VALID_TRAILADDRESS = "13 Reel Avenue, Albuquerque, NM 87111";
	/**
	 * address of this trail
	 * @var string $VALID_TRAILADDRESS2
	 **/
	protected $VALID_TRAILADDRESS2 = "this is still a valid address for this trail";
	/**
	 * trail image
	 * @var string $VALID_TRAILIMAGE
	 **/
	protected $VALID_TRAILIMAGE = "https://i.ytimg.com/vi/pwFKqTGHbaU/maxresdefault.jpg";
	/**
	 * name of this trail
	 * @var string $VALID_TRAILNAME
	 **/
	protected $VALID_TRAILNAME = "Variable Valley";
	/**
	 * location of this trail
	 * @var string $VALID_TRAILLOCATION
	 **/
	protected $VALID_TRAILLOCATION = "Labyrinth of Loops";
	/**
	 * summary of this trail
	 * @var string $VALID_TRAILSUMMARY
	 **/
	protected $VALID_TRAILSUMMARY = "The Variable Valley offers an insight into the natural resources of this region.";
	/**
	 * ascent of this Trail
	 * @var int $VALID_TRAILASCENT
	 **/
	protected $VALID_TRAILASCENT = 40;
	/**
	 * rating of this Trail
	 * @var int $VALID_TRAILRATING
	 **/
	protected $VALID_TRAILRATING = 4;
	/**
	 * length of this trail
	 * @var float $VALID_TRAILLENGTH
	 **/
	protected $VALID_TRAILLENGTH = 3.14;
	/**
	 * latidude of this trail
	 * @var float $VALID_TRAILLATITUDE
	 **/
	protected $VALID_TRAILLATITUDE = 48;
	/**
	 * longitude of this trail
	 * @var float $VALID_TRAILLONGITUDE
	 **/
	protected $VALID_TRAILLONGITUDE = 192;
}