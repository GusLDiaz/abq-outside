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
	/**
	 * valid image url for OAUTH
	 * @var string $VALID_PROFILE_IMAGE_URL
	 **/
	protected $VALID_PROFILE_IMAGE_URL = "https://media.giphy.com/media/3og0INyCmHlNylks9O/giphy.gif";
	/**
	 * valid email to use
	 * @var string $VALID_EMAIL
	 **/
	protected $VALID_PROFILE_EMAIL = "test@phpunit.de";
	/**re: comment_Content_2 do we need state vars for updated fields of prof. if theyre coming from OAUTH (GOOGLE?)
*/

public final function setUp(): void {
	// run setUp() method
	parent::setUp();
//	$password = "abc123";
//	$this->VALID_PROFILE_REFRESH_TOKEN = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
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