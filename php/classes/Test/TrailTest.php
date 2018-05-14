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
	 * address of this trail
	 * @var string $VALID_TRAILADDRESS
	 **/
	protected $VALID_TRAILADDRESS = "7601 St Josephs Ave, Albuquerque, NM 87120";
	/**
	 * address of this trail
	 * @var string $VALID_TRAILADDRESS2
	 **/
	protected $VALID_TRAILADDRESS2 = "this is still a valid address for this trail";
	/**
	 * trail image
	 * @var string $VALID_TRAILIMAGE
	 **/
	protected $VALID_TRAILIMAGE = "http://www.protrails.com/protrails/trails/243.jpg";
	/**
	 * name of this trail
	 * @var string $VALID_TRAILNAME
	 **/
	protected $VALID_TRAILNAME = "Rinconada Canyon Trail";
}