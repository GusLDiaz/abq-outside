<?php
namespace Edu\Cnm\AbqOutside;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;

/**
 * Class for the Profile Entity
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @author Michael Figueroa <mfigueroa14@cnm.edu>
 **/
class Profile implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for the Profile; this is the primary key
	 * @var Uuid $profileId
	 **/
	private $profileId;
	/**
	 * The profile's Email
	 * @var string $profileEmail
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