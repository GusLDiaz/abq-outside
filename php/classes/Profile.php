<?php
namespace Edu\Cnm\AbqOutside;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use PDOException;
use Ramsey\Uuid\Uuid;

/**
 * Class for the Profile Entity
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @author Michael Figueroa <mfigueroa14@cnm.edu>
 * @version 1.0.0
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
	public function __construct($newProfileId, string $newProfileEmail, ?string $newProfileImage, string $newProfileRefreshToken, string $newProfileUsername) {
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
			$newProfileId = self::validateUuid($newProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile id
		$this->profileId = $newProfileId;
	}

	/**
	 * accessor method for profile email
	 * @return string value of profile email
	 **/
	public function getProfileEmail(): string {
		return ($this->profileEmail);
	}

	/**
	 * mutator method for profile email
	 *
	 * @param string $newProfileEmail new value of profile email
	 * @throws \InvalidArgumentException if $newProfileEmail is not a string or insecure
	 * @throws \TypeError if $newProfileemail is not a string
	 **/
	public function setProfileEmail(string $newProfileEmail): void {
		// verify the profile email is secure
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileEmail) === true) {
			throw(new \InvalidArgumentException("profile email is empty or insecure"));
		}
		// verify the profile email will fit in the database
		if(strlen($newProfileEmail) > 128) {
			throw(new \RangeException("profile email too large"));
		}
		// store the profile email
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
	public function setProfileImage(?string $newProfileImage): void {
		// verify the profile image is secure
		$newProfileImage = trim($newProfileImage);
		$newProfileImage = filter_var($newProfileImage, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileImage) === true) {
			throw(new \InvalidArgumentException("profile image is empty or insecure"));
		}
		// verify the profile image will fit in the database
		if(strlen($newProfileImage) > 255) {
			throw(new \RangeException("profile image too large"));
		}
		// store the profile image
		$this->profileImage = $newProfileImage;
	}

	/**
	 * accessor method for profile Refresh token
	 * @return string value of the Refresh token
	 */
	public function getProfileRefreshToken() : ?string {
		return ($this->profileRefreshToken);
	}
	/**
	 * mutator method for profile Refresh token
	 * @param string $newProfileRefreshToken
	 * @throws \InvalidArgumentException  if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 128 characters
	 * @throws \TypeError if the activation token is not a string
	 */
	public function setProfileRefreshToken(?string $newProfileRefreshToken): void {
		if($newProfileRefreshToken === null) {
			$this->profileRefreshToken = null;
			return;
		}
		$newProfileRefreshToken = strtolower(trim($newProfileRefreshToken));
		if(ctype_xdigit($newProfileRefreshToken) === false) {
			throw(new\InvalidArgumentException("useq	r activation is not valid"));
		}
		//make sure user activation token is only 128 characters
		if(strlen($newProfileRefreshToken) > 128) {
			throw(new\RangeException("user activation token has to be 128"));
		}
		$this->profileRefreshToken = $newProfileRefreshToken;
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
		if(strlen($newProfileUsername) > 64) {
			throw(new \RangeException("profile username too large"));
		}
		// store the profile username
		$this->profileUsername = $newProfileUsername;
	}

	/**
	 * inserts Profile into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {

		// create query template
		$query = "INSERT INTO profile(profileId, profileEmail, profileImage, profileRefreshToken, profileUsername) VALUES(:profileId, :profileEmail, :profileImage, :profileRefreshToken, :profileUsername)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["profileId" => $this->profileId->getBytes(), "profileEmail" => $this->profileEmail, "profileImage" => $this->profileImage, "profileRefreshToken" => $this->profileRefreshToken, "profileUsername" => $this->profileUsername];
		$statement->execute($parameters);
	}

	/**
	 * deletes profile from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo): void {

		// create query template
		$query = "DELETE FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["profileId" => $this->profileId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates profile in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo): void {

		// create query template
		$query = "UPDATE profile SET profileId = :profileId, profileEmail = :profileEmail, profileImage = :profileImage, profileRefreshToken= :profileRefreshToken, profileUsername = :profileUsername WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		$parameters = ["profileId" => $this->profileId->getBytes(), "profileEmail" => $this->profileEmail, "profileImage" => $this->profileImage, "profileRefreshToken" => $this->profileRefreshToken, "profileUsername" => $this->profileUsername];
		$statement->execute($parameters);
	}

	/**
	 * gets the profile by profile Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $profileId profile id to search for
	 * @return profile|null profile found or null if not found
	 * @throws PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getProfileByProfileId(\PDO $pdo, $profileId): ?profile {
		// sanitize the profile before searching
		try {
			$profileId = self::validateUuid($profileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT profileId, profileEmail, profileImage, profileRefreshToken, profileUsername FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the profile id to the place holder in the template
		$parameters = ["profileId" => $profileId->getBytes()];
		$statement->execute($parameters);

		// grab the profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileEmail"], $row["profileImage"], $row["profileRefreshToken"], $row["profileUsername"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}

	/**
	 * gets the Profile by email
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $profileEmail email to search for
	 * @return Profile|null Profile or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProfileByProfileEmail(\PDO $pdo, string $profileEmail): ?Profile {
		// sanitize the email before searching
		$profileEmail = trim($profileEmail);
		$profileEmail = filter_var($profileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($profileEmail) === true) {
			throw(new \PDOException("not a valid email"));
		}
		// create query template
		$query = "SELECT profileId, profileEmail, profileImage, profileRefreshToken, profileUsername FROM profile WHERE profileEmail = :profileEmail";
		$statement = $pdo->prepare($query);
		// bind the profile id to the place holder in the template
		$parameters = ["profileEmail" => $profileEmail];
		$statement->execute($parameters);
		// grab the Profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileEmail"], $row["profileImage"], $row["profileRefreshToken"], $row["profileUsername"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}

	/**
	 * get the profile by profile refresh token
	 *
	 * @param string $profileRefreshToken
	 * @param \PDO object $pdo
	 * @return Profile|null Profile or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public
	static function getProfileByProfileRefreshToken(\PDO $pdo, string $profileRefreshToken) : ?Profile {
		//make sure activation token is in the right format and that it is a string representation of a hexadecimal
		$profileRefreshToken = trim($profileRefreshToken);
		if(ctype_xdigit($profileRefreshToken) === false) {
			throw(new \InvalidArgumentException("profile refresh token is empty or in the wrong format"));
		}
		//create the query template
		$query = "SELECT profileId, profileEmail, profileImage, profileRefreshToken, profileUsername FROM profile WHERE profileRefreshToken = :profileRefreshToken";
		$statement = $pdo->prepare($query);
		// bind the profile refresh token to the placeholder in the template
		$parameters = ["profileRefreshToken" => $profileRefreshToken];
		$statement->execute($parameters);
		// grab the Profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileEmail"], $row["profileImage"], $row["profileRefreshToken"], $row["profileUsername"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize(): array {
		$fields = get_object_vars($this);

		$fields["profileId"] = $this->profileId->toString();

		return ($fields);
	}
}

