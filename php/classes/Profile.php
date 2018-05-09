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
	 * The profile's Image
	 * @var string $profileUsername
	 **/
	private $profileImage;
	/**
	 * The profile's Refresh Token
	 * @var string $profileUsername
	 **/
	private $profileRefreshToken;
	/**
	 * The profile's Username
	 * @var string $profileUsername
	 **/
	private $profileUsername;

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
	public function __construct($newProfileId, string $newProfileEmail, string $newProfileImage, string $newProfileRefreshToken, string $newProfileUsername) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfileImage($newProfileImage);
			$this->setProfileRefreshToken($newProfileRefreshToken);
			$this->setProfileUsername($newProfileUsername);
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

	/**
	 * accessor method for profile email
	 *
	 **/
	public function getProfileEmail(): string {
		return ($this->profileEmail);
	}

	/**
	 * mutator method for profile email
	 *
	 * @param string | $newProfileEmail new value of profileEmail
	 * @throws \RangeException if $newProfileEmail is not positive
	 **/
	public function setProfileEmail($newProfileEmail): void {
		try {
			$uuid = self::validateUuid($newProfileEmail);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile email
		$this->profileEmail = $newProfileEmail;
	}

	/**
	 * accessor method for profile image
	 *
	 * @return string value of profile image
	 **/
	public function getProfileImage(): string {
		return ($this->profileImage);
	}

	/**
	 * mutator method for profile image
	 *
	 * @param string $newProfileImage new value of profile image
	 * @throws \InvalidArgumentException if $newProfileImage is not a string or insecure
	 * @throws \TypeError if $newProfileImage is not a string
	 **/
	public function setProfileImage(string $newProfileImage): void {
		// verify the profile image is secure
		$newProfileImage = trim($newProfileImage);
		$newProfileImage = filter_var($newProfileImage, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileImage) === true) {
			throw(new \InvalidArgumentException("profile image is empty or insecure"));
		}
		// verify the profile image will fit in the database
		if(strlen($newProfileImage) > 140) {
			throw(new \RangeException("profile image too large"));
		}
		// store the profile image
		$this->profileImage = $newProfileImage;
	}

	/**
	 * @return string
	 */
	public function getProfileRefreshToken(): string {
		return $this->profileRefreshToken;
	}

	/**
	 * @param string $profileRefreshToken
	 */
	public function setProfileRefreshToken(string $profileRefreshToken): void {
		$this->profileRefreshToken = $profileRefreshToken;
	}


	/**
	 * accessor method for profile username
	 *
	 * @return string value of profile username
	 **/
	public function getProfileUsername(): string {
		return ($this->profileUsername);
	}

	/**
	 * mutator method for profile username
	 *
	 * @param string $newProfileUsername new value of profile username
	 * @throws \InvalidArgumentException if $newProfileUsername is not a string or insecure
	 * @throws \TypeError if $newProfileUsername is not a string
	 **/
	public function setProfileUsername(string $newProfileUsername): void {
		// verify the profile username is secure
		$newProfileUsername = trim($newProfileUsername);
		$newProfileUsername = filter_var($newProfileUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileUsername) === true) {
			throw(new \InvalidArgumentException("profile username is empty or insecure"));
		}
		// verify the profile username will fit in the database
		if(strlen($newProfileUsername) > 140) {
			throw(new \RangeException("profile username too large"));
		}
		// store the profile username
		$this->profileUsername = $newProfileUsername;
	}
}