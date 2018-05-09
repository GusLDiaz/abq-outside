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

	/**
	 * constructor for Profile
	 *
	 * @param string|Uuid $newProfileId id for Profile or null if a new Profile
	 * @param string $newProfileEmail string containing profile email data
	 * @param string $newProfileUsername string containing profile Username
	 * @param string $newProfileRefreshToken string containing /////////
	 * @param string $newProfileImage string containing profile image data
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newProfileId, string $newProfileEmail, string $newProfileUsername, string $newProfileRefreshToken, string $newProfileImage) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfileUsername($newProfileUsername);
			$this->setProfileRefreshToken($newProfileRefreshToken);
			$this->setProfileImage($newProfileImage);
		} //determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for profile id
	 *
	 * @return Uuid value of profile id
	 **/
	public function getProfileId(): Uuid {
		return ($this->profileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param Uuid/string $newProfileId new value of profile id
	 * @throws \RangeException if $newProfileId is null
	 * @throws \TypeError if $newProfileId is not a uuid.e
	 **/
	public function setProfileId($newProfileId): void {
		try {
			$uuid = self::validateUuid($newProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile id
		$this->profileId = $uuid;
	}
}