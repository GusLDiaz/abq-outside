<?php
namespace Edu\Cnm\Assessment;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Class for the Profile Entity
 *
 * @version 3.0.0
 **/
class Profile implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for the Profile; this is the primary key
	 * @var Uuid $profileId
	 **/
	private $profileId;
	/**
	 * Atrribute for the profile's password
	 * @var Uuid $profileHash
	 **/
	private $profileEmail;
	/**
	 * The profile's Username
	 * @var string $profileUsername
	 **/
	private $profileUsername;
	/**
	 * The profile's Refresh Token
	 * @var string $profileUsername
	 **/
	private $profileRefreshToken;
	/**
	 * The profile's Image
	 * @var string $profileUsername
	 **/
	private $profileImage;
}