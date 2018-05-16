<?php
namespace Edu\Cnm\AbqOutside\Test;
use Edu\Cnm\AbqOutside\Profile;

//require_once the base test class

require_once("AbqOutsideTest.php");
require_once(dirname(__DIR__) . "/autoload.php");
require_once (dirname(__DIR__, 2) . "/lib/uuid.php");


class ProfileTest extends AbqOutsideTest {
	/**
	 * test generated UUID
	 * @var UUID $VALID_PROFILE_ID
	 */
	protected $VALID_PROFILE_ID;
	/**
	 * valid email to use
	 * @var string $VALID_PROFILE_EMAIL
	 **/
	protected $VALID_PROFILE_EMAIL = "test@phpunit.de";
	/**
	 * valid image url for OAUTH
	 * @var string $VALID_PROFILE_IMAGE_URL
	 **/
	protected $VALID_PROFILE_IMAGE_URL = "https://media.giphy.com/media/3og0INyCmHlNylks9O/giphy.gif";
	/**
	 * placeholder until account activation is created
	 * @var string $VALID_PROFILE_REFRESH_TOKEN
	 */
	protected $VALID_PROFILE_REFRESH_TOKEN;
	/**
	 * valid profile
	 * @var string $VALID_PROFILE_USERNAME
	 **/
	protected $VALID_PROFILE_USERNAME = "profiletest";
	/**
	 * second valid profile
	 * @var string $VALID_PROFILE_USERNAME2
	 **/
	protected $VALID_PROFILE_USERNAME2 = "stillpassingprofile";

	public final function setUp(): void {
		// run setUp() method
		parent::setUp();
		$this->VALID_PROFILE_REFRESH_TOKEN = bin2hex(random_bytes(63));
	}

	/**
	 * test inserting a valid Profile and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProfile(): void {
		// count the number of rows for later
		$numRows = $this->getConnection()->getRowCount("profile");
		$profileId = generateUuidV4();
		//	//order: profileId email image Refresh token username
		$profile = new Profile($profileId, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE_URL,  $this->VALID_PROFILE_REFRESH_TOKEN, $this->VALID_PROFILE_USERNAME);
		$profile->insert($this->getPDO());

		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($pdoProfile->getProfileImage(), $this->VALID_PROFILE_IMAGE_URL);
		$this->assertEquals($pdoProfile->getProfileRefreshToken(), $this->VALID_PROFILE_REFRESH_TOKEN);
		$this->assertEquals($pdoProfile->getProfileUsername(), $this->VALID_PROFILE_USERNAME);
	}
	/**
	 * test inserting a Profile, editing it, and then updating it
	 **/
	public function testUpdateValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");
		// create a new Profile and insert to into mySQL
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE_URL,  $this->VALID_PROFILE_REFRESH_TOKEN, $this->VALID_PROFILE_USERNAME);		$profile->insert($this->getPDO());
		// edit the Profile and update it in mySQL
		$profile->update($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($pdoProfile->getProfileImage(), $this->VALID_PROFILE_IMAGE_URL);
		$this->assertEquals($pdoProfile->getProfileRefreshToken(), $this->VALID_PROFILE_REFRESH_TOKEN);
		$this->assertEquals($pdoProfile->getProfileUsername(), $this->VALID_PROFILE_USERNAME);
	}
	/**
	 * test creating a Profile and then deleting it
	 **/
	public function testDeleteValidProfile() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE_URL,  $this->VALID_PROFILE_REFRESH_TOKEN, $this->VALID_PROFILE_USERNAME);		$profile->insert($this->getPDO());
		// delete the Profile from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$profile->delete($this->getPDO());
		// grab the data from mySQL and enforce the Profile does not exist
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertNull($pdoProfile);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("profile"));
	}
	/**
	 * test inserting a Profile and re-grabbing it from mySQL
	 **/
	public function testGetValidProfileByProfileId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE_URL,  $this->VALID_PROFILE_REFRESH_TOKEN, $this->VALID_PROFILE_USERNAME);		$profile->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($pdoProfile->getProfileImage(), $this->VALID_PROFILE_IMAGE_URL);
		$this->assertEquals($pdoProfile->getProfileRefreshToken(), $this->VALID_PROFILE_REFRESH_TOKEN);
		$this->assertEquals($pdoProfile->getProfileUsername(), $this->VALID_PROFILE_USERNAME);
	}
	/**
	 * test grabbing a Profile that does not exist
	 **/
	public function testGetInvalidProfileByProfileId() : void {
		// grab a profile id that exceeds the maximum allowable profile id
		$fakeProfileId = generateUuidV4();
		$profile = Profile::getProfileByProfileId($this->getPDO(), $fakeProfileId );
		$this->assertNull($profile);
	}
	/**
	 * test grabbing a Profile by email
	 **/
	public function testGetValidProfileByEmail() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE_URL,  $this->VALID_PROFILE_REFRESH_TOKEN, $this->VALID_PROFILE_USERNAME);		$profile->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileEmail($this->getPDO(), $profile->getProfileEmail());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($pdoProfile->getProfileImage(), $this->VALID_PROFILE_IMAGE_URL);
		$this->assertEquals($pdoProfile->getProfileRefreshToken(), $this->VALID_PROFILE_REFRESH_TOKEN);
		$this->assertEquals($pdoProfile->getProfileUsername(), $this->VALID_PROFILE_USERNAME);
	}
	/**
	 * test grabbing a Profile by an email that does not exists
	 **/
	public function testGetInvalidProfileByEmail() : void {
		// grab an email that does not exist
		$profile = Profile::getProfileByProfileEmail($this->getPDO(), "does@not.exist");
		$this->assertNull($profile);
	}
	/**
	 * test grabbing a profile by its refresh token
	 */
	public function testGetValidProfileByRefreshToken() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->VALID_PROFILE_EMAIL, $this->VALID_PROFILE_IMAGE_URL,  $this->VALID_PROFILE_REFRESH_TOKEN, $this->VALID_PROFILE_USERNAME);		$profile->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileRefreshToken($this->getPDO(), $profile->getProfileRefreshToken());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileId(), $profileId);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILE_EMAIL);
		$this->assertEquals($pdoProfile->getProfileImage(), $this->VALID_PROFILE_IMAGE_URL);
		$this->assertEquals($pdoProfile->getProfileRefreshToken(), $this->VALID_PROFILE_REFRESH_TOKEN);
		$this->assertEquals($pdoProfile->getProfileUsername(), $this->VALID_PROFILE_USERNAME);
	}
	/**
	 * test grabbing a Profile by a refresh token that does not exists
	 **/
	public function testGetInvalidProfileByRefreshToken() : void {
		// grab an email that does not exist
		$profile = Profile::getProfileByProfileRefreshToken($this->getPDO(), "6675636b646f6e616c646472756d7066");
		$this->assertNull($profile);
	}
}