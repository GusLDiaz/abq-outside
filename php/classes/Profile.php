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
	private $profileHash;
	/**
	 * The profile's Username
	 * @var string $profileUsername
	 **/
	private $profileUsername;
	/**
	 * constructor for this profile
	 *
	 * @param string|Uuid $newProfileId id of this profile or null if a new profile
	 * @param string $newProfileUsername string containing actual profile data
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/

	/**
	 * accessor method for profile id
	 *
	 * @return Uuid value of profile id
	 **/
	public function getProfileId() : Uuid {
		return($this->profileId);
	}
	/**
	 * mutator method for profile id
	 *
	 * @param Uuid/string $newProfileId new value of profile id
	 * @throws \RangeException if $newProfileId is n
	 * @throws \TypeError if $newProfileId is not a uuid.e
	 **/
	public function setProfileId( $newProfileId) : void {
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
	 * accessor method for profile password
	 *
	 * @return Uuid value of profile password
	 **/
	public function getProfileHash() : Uuid{
		return($this->profileHash);
	}
	/**
	 * mutator method for profile password
	 *
	 * @param string | Uuid $newProfileHash new value of profileHash
	 * @throws \RangeException if $newProfileHash is not positive
	 **/
	public function setProfileHash( $newProfileHash) : void {
		try {
			$uuid = self::validateUuid($newProfileHash);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile password
		$this->profileHash = $uuid;
	}
	/**
	 * accessor method for profile Username
	 *
	 * @return Uuid value of profile Username
	 **/
	public function getProfileUsername() : Uuid{
		return($this->profileHash);
	}
	/**
	 * mutator method for profile Username
	 *
	 * @param string | Uuid $newProfileUsername new value of profile Username
	 * @throws \RangeException if $newProfileUsername is not positive
	 **/
	public function setProfileUsername( $newProfileUsername) : void {
		try {
			$uuid = self::validateUuid($newProfileUsername);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile Username
		$this->profileUsername = $uuid;
	}

	/**
	 * inserts this profile into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		// create query template
		$query = "INSERT INTO profile (profileId, profileHash, profileUsername) VALUES(:profileId, :profileHash, :profileUsername)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["profileId" => $this->profileId->getBytes(), "profileHash" => $this->profileHash->getBytes(), "profileUsername" => $this->profileUsername->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this profile in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {
		// create query template
		$query = "UPDATE profile SET profileUsername = :profileUsername WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);
		$parameters = ["profileId" => $this->profileId->getBytes(),"profileHash" => $this->profileHash->getBytes(), "profileUsername" => $this->profileUsername];
		$statement->execute($parameters);
	}

	/**
	 * deletes this profile from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		// create query template
		$query = "DELETE FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holder in the template
		$parameters = ["profileId" => $this->profileId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * gets the Profile by profileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string|Uuid $profileId profile id to search for
	 * @return Profile|null Profile found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getProfileByProfileId(\PDO $pdo, $profileId) : ?Profile {
		// sanitize the profileId before searching
		try {
			$profileId = self::validateUuid($profileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT profileId, profileHash, profileUsername FROM profile WHERE profileId = :profileId";
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
				$profile = new Profile(); $row["profileId"]; $row["profileHash"]; $row["profileUsername"];
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($profile);
	}
	/**
	 * gets the Profile by Username
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $profileUsername profile username to search for
	 * @return \SplFixedArray SplFixedArray of Profiles found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProfileByProfileUsername(\PDO $pdo, string $profileUsername) : \SPLFixedArray {
		// sanitize the description before searching
		$profileUsername = trim($profileUsername);
		$profileUsername = filter_var($profileUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($profileUsername) === true) {
			throw(new \PDOException("profile content is invalid"));
		}
		// escape any mySQL wild cards
		$profileUsername = str_replace("_", "\\_", str_replace("%", "\\%", $profileUsername));
		// create query template
		$query = "SELECT profileId, profileHash, profileUsername FROM profile WHERE profileUsername LIKE :profileUsername";
		$statement = $pdo->prepare($query);
		// bind the profile content to the place holder in the template
		$profileUsername = "%$profileUsername%";
		$parameters = ["profileUsername" => $profileUsername];
		$statement->execute($parameters);
		// build an array of profiles
		$profiles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$profile = new Profile($row["profileId"], $row["profileHash"], $row["profileUsername"]);
				$profiles[$profiles->key()] = $profile;
				$profiles->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($profile);
	}
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["profileId"] = $this->profileId;
		$fields["profileHash"] = $this->profileHash;
		$fields["profileUsername"] = $this->profileUsername;
		return($fields);
	}
