<?php
/**
 * Created by PhpStorm.
 * User: Gusli
 * Date: 5/11/2018
 * Time: 12:45 PM
 */

namespace Edu\Cnm\AbqOutside\Test;


class ProfileTest extends AbqOutsideTest {
	/**
	 * test generated UUID
	 * @var UUID $VALID_PROFILE_ID
	 */
	protected $VALID_PROFILE_ID;
	/**
	 * valid email to use
	 * @var string $VALID_EMAIL
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


	/**re: comment_Content_2 do we need state vars for updated fields of prof. if theyre coming from OAUTH (GOOGLE?)
	 */

	public final function setUp(): void {
		// run setUp() method
		parent::setUp();
		$this->VALID_PROFILE_REFRESH_TOKEN = bin2hex(random_bytes(16));
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

//
//	//order: profileId email image Refresh token username
//	$this->profile = new Profile(generateUuidV4(), "email", "imagehandle", "https://media.giphy.com/media/3og0INyCmHlNylks9O/giphy.gif", "username");//,$this->VALID_PROFILE_HASH, " 12125551212");
//	$this->profile->insert($this->getPDO());
//	// create trail To be commented on?
//	// do a  base api call first?
//	$this->trail = new Trail(generateUuidV4(), "7475773", "address", "imagehandle",);
//	// calculate the date (just use the time the unit test was setup...)
//	$this->VALID_COMMENT_TIMESTAMP = new \DateTime();
//}

}