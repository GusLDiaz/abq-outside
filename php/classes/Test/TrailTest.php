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
	protected $VALID_TRAILLAT = 48;
	/**
	 * longitude of this trail
	 * @var float $VALID_TRAILLONGITUDE
	 **/
	protected $VALID_TRAILLONG = 192;
	/**
	 * test inserting a valid Trail and verify that the actual mySQL data matches
	 **/
	public function testInsertValidTrail() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("trail");
		// create a new Trail and insert to into mySQL
		$trailId = generateUuidV4();
		$trail = new Trail($trailId, $this->VALID_TRAILEXTERNALID, $this->VALID_TRAILADDRESS, $this->VALID_TRAILIMAGE, $this->VALID_TRAILNAME, $this->VALID_TRAILLOCATION, $this->VALID_TRAILSUMMARY, $this->VALID_TRAILASCENT, $this->VALID_TRAILRATING, $this->VALID_TRAILLENGTH, $this->VALID_TRAILLATITUDE, $this->VALID_TRAILLONGITUDE);
		$trail->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoTrail = Trail::getTrailByTrailId($this->getPDO(), $trail->getTrailId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("trail"));
		$this->assertEquals($pdoTrail->getTrailId(), $trailId);
		$this->assertEquals($pdoTrail->getTrailExternalId(), $this->VALID_TRAILEXTERNALID);
		$this->assertEquals($pdoTrail->getTrailAddress(), $this->VALID_TRAILADDRESS);
		$this->assertEquals($pdoTrail->getTrailImage(), $this->VALID_TRAILIMAGE);
		$this->assertEquals($pdoTrail->getTrailName(), $this->VALID_TRAILNAME);
		$this->assertEquals($pdoTrail->getTrailLocation(), $this->VALID_TRAILLOCATION);
		$this->assertEquals($pdoTrail->getTrailSummary(), $this->VALID_TRAILSUMMARY);
		$this->assertEquals($pdoTrail->getTrailAscent(), $this->VALID_TRAILASCENT);
		$this->assertEquals($pdoTrail->getTrailRating(), $this->VALID_TRAILRATING);
		$this->assertEquals($pdoTrail->getTrailLength(), $this->VALID_TRAILLENGTH);
		$this->assertEquals($pdoTrail->getTrailLat(), $this->VALID_TRAILLAT);
		$this->assertEquals($pdoTrail->getTrailLong(), $this->VALID_TRAILLONG);
	}
	/**
	 * test inserting a Trail, editing it, and then updating it
	 **/
	public function testUpdateValidTrail() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("trail");
		// create a new Trail and insert to into mySQL
		$trailId = generateUuidV4();
		$trail = new Trail($trailId, $this->VALID_TRAILEXTERNALID, $this->VALID_TRAILADDRESS, $this->VALID_TRAILIMAGE, $this->VALID_TRAILNAME, $this->VALID_TRAILLOCATION, $this->VALID_TRAILSUMMARY, $this->VALID_TRAILASCENT, $this->VALID_TRAILRATING, $this->VALID_TRAILLENGTH, $this->VALID_TRAILLATITUDE, $this->VALID_TRAILLONGITUDE);
		$trail->insert($this->getPDO());
		// edit the Trail and update it in mySQL
		//todo actually update the value
		$trail->setTrailAddress($this->VALID_TRAILADDRESS);
		$trail->update($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoTrail = Trail::getTrailByTrailId($this->getPDO(), $trail->getTrailId());
		//todo assertEquals all the things
		$this->assertEquals($pdoTrail->getTrailId(), $trailId);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("trail"));
		$this->assertEquals($pdoTrail->getTrailAddress(), $this->VALID_TRAILADDRESS);
	}
	/**
	 * test creating a Trail and then deleting it
	 **/
	public function testDeleteValidTrail() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("trail");
		// create a new Trail and insert to into mySQL
		$trailId = generateUuidV4();
		$trail = new Trail($trailId, $this->VALID_TRAILEXTERNALID, $this->VALID_TRAILADDRESS, $this->VALID_TRAILIMAGE, $this->VALID_TRAILNAME, $this->VALID_TRAILLOCATION, $this->VALID_TRAILSUMMARY, $this->VALID_TRAILASCENT, $this->VALID_TRAILRATING, $this->VALID_TRAILLENGTH, $this->VALID_TRAILLATITUDE, $this->VALID_TRAILLONGITUDE);
		$trail->insert($this->getPDO());
		// delete the Trail from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("trail"));
		$trail->delete($this->getPDO());
		// grab the data from mySQL and enforce the Trail does not exist
		$pdoTrail = Trail::getTrailByTrailId($this->getPDO(), $trail->getTrailId());
		$this->assertNull($pdoTrail);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("trail"));
	}
	/**
	 * test grabbing a Trail that does not exist
	 **/
	public function testGetInvalidTrailByTrailId() : void {
		// grab a trail id that exceeds the maximum allowable trail id
		$trail = Trail::getTrailByTrailId($this->getPDO(), generateUuidV4());
		$this->assertNull($trail);
	}
	/**
	 * test grabbing a Trail by trail distance
	 **/
	public function testGetValidTrailByDistance() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("trail");
		// create a new Trail and insert to into mySQL
		$trailId = generateUuidV4();
		$trail = new Trail($trailId, $this->VALID_TRAILEXTERNALID, $this->VALID_TRAILADDRESS, $this->VALID_TRAILIMAGE, $this->VALID_TRAILNAME, $this->VALID_TRAILLOCATION, $this->VALID_TRAILSUMMARY, $this->VALID_TRAILASCENT, $this->VALID_TRAILRATING, $this->VALID_TRAILLENGTH, $this->VALID_TRAILLATITUDE, $this->VALID_TRAILLONGITUDE);
		$trail->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Trail::getTrailByDistance($this->getPDO(), 48, 192, 100);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("trail"));
		$this->assertCount(1, $results);
		// enforce no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqOutside\\Trail", $results);
		// grab the result from the array and validate it
		$pdoTrail = $results[0];
		$this->assertEquals($pdoTrail->getTrailId(), $trailId);
		$this->assertEquals($pdoTrail->getTrailExternalId(), $this->VALID_TRAILEXTERNALID);
		$this->assertEquals($pdoTrail->getTrailAddress(), $this->VALID_TRAILADDRESS);
		$this->assertEquals($pdoTrail->getTrailImage(), $this->VALID_TRAILIMAGE);
		$this->assertEquals($pdoTrail->getTrailName(), $this->VALID_TRAILNAME);
		$this->assertEquals($pdoTrail->getTrailLocation(), $this->VALID_TRAILLOCATION);
		$this->assertEquals($pdoTrail->getTrailSummary(), $this->VALID_TRAILSUMMARY);
		$this->assertEquals($pdoTrail->getTrailAscent(), $this->VALID_TRAILASCENT);
		$this->assertEquals($pdoTrail->getTrailRating(), $this->VALID_TRAILRATING);
		$this->assertEquals($pdoTrail->getTrailLength(), $this->VALID_TRAILLENGTH);
		$this->assertEquals($pdoTrail->getTrailLat(), $this->VALID_TRAILLAT);
		$this->assertEquals($pdoTrail->getTrailLong(), $this->VALID_TRAILLONG);
	}
	/**
	 * test grabbing a Trail whose distance does not exist
	 **/
	public function testGetInvalidTrailByDistance() : void {
		// grab a trail by distance that does not exist
		$trail = Trail::getTrailByDistance($this->getPDO(), 040.717274011, 040.717274011, 7);
		$this->assertCount(0, $trail);
	}
	/**
	 * test grabbing all Trails
	 **/
	public function testGetAllValidTrails() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("trail");
		// create a new Trail and insert to into mySQL
		$trailId = generateUuidV4();
		$trail = new Trail($trailId, $this->VALID_TRAILEXTERNALID, $this->VALID_TRAILADDRESS, $this->VALID_TRAILIMAGE, $this->VALID_TRAILNAME, $this->VALID_TRAILLOCATION, $this->VALID_TRAILSUMMARY, $this->VALID_TRAILASCENT, $this->VALID_TRAILRATING, $this->VALID_TRAILLENGTH, $this->VALID_TRAILLATITUDE, $this->VALID_TRAILLONGITUDE);
		$trail->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Trail::getAllTrails($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("trail"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqOutside\\Trail", $results);
		// grab the result from the array and validate it
		$pdoTrail = $results[0];
		$this->assertEquals($pdoTrail->getTrailId(), $trailId);
		$this->assertEquals($pdoTrail->getTrailExternalId(), $this->VALID_TRAILEXTERNALID);
		$this->assertEquals($pdoTrail->getTrailAddress(), $this->VALID_TRAILADDRESS);
		$this->assertEquals($pdoTrail->getTrailImage(), $this->VALID_TRAILIMAGE);
		$this->assertEquals($pdoTrail->getTrailName(), $this->VALID_TRAILNAME);
		$this->assertEquals($pdoTrail->getTrailLocation(), $this->VALID_TRAILLOCATION);
		$this->assertEquals($pdoTrail->getTrailSummary(), $this->VALID_TRAILSUMMARY);
		$this->assertEquals($pdoTrail->getTrailAscent(), $this->VALID_TRAILASCENT);
		$this->assertEquals($pdoTrail->getTrailRating(), $this->VALID_TRAILRATING);
		$this->assertEquals($pdoTrail->getTrailLength(), $this->VALID_TRAILLENGTH);
		$this->assertEquals($pdoTrail->getTrailLat(), $this->VALID_TRAILLAT);
		$this->assertEquals($pdoTrail->getTrailLong(), $this->VALID_TRAILLONG);
	}
}