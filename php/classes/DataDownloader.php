<?php

namespace Edu\Cnm\AbqOutside;
require_once("autoload.php");
//require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once(ramsey)

class DataDownloader {
	/**
	 * https://www.hikingproject.com/data/get-trails?lat=40.0274&lon=-105.2519&maxDistance=10&key=200265
	 * two options: GetTrails, GetTrailsbyTrailId
	 *	method Get trails:
	 *
	 *Required Args:
	 * key: your private key ; Lat ; Lon
	 *
	 *Optional Args:
	 * maxDistance - Max distance, in miles, from lat, lon. Default: 30. Max: 200
	 * maxResults - Max number of trails to return. Default: 10. Max: 500.
	 * sort - Values can be 'quality', 'distance'. Default: quality.
	 * minLength - Min trail length, in miles. Default: 0 (no minimum).
	 * minStars - Min star rating, 0-4. Default: 0.
	 *
	 * URL:  basics + get-trails (method) + ? + "Lat=" + latitude + "Lon=" + longitude + "&" (ampersand) + "maxDistance=" + distance + "&"(ampersand)+ "key=" + private key from HP API
	 * EX: "https://www.hikingproject.com/data/get-trails" + "?" + "lat=" + "40.0274" + "&" + "lon=" + "-105.2519" + "&" + "maxDistance=" + "10" +"&" + "key=" + "200265121-1809e265008042f9977e435839863103"
	 * EX: https://www.hikingproject.com/data/get-trails?lat=40.0274&lon=-105.2519&maxDistance=10&key=200265121-1809e265008042f9977e435839863103
	 *
	 *	method GetTrailsById:
	 *
	 * Required Arguments:
	 * key - Your private key
	 * ids - one or more trail IDs, separated by commas
	 *
	 * URL:  basics + get-trails-by-id (method) + ? + "ids=" + trailExternalId (s) --char7 seperated by commas + "&" ampersand + "key=" + private key from HP API
	 * EX: "https://www.hikingproject.com/data/get-trails-by-id" + "?" + "ids=7000108,7002175,7005207,7001726,7005428" + "&" + "key=200265121-1809e265008042f9977e435839863103"
	 *      https://www.hikingproject.com/data/get-trails-by-id?ids=7000108,7002175,7005207,7001726,7005428&key=200265121-1809e265008042f9977e435839863103
	 *
	 * |||||||
	 * Gets the eTag from a file url
	 *
	 * @param string $url to grab from
	 * @return string $eTag to be compared to previous eTag to determine last download
	 * @throws \RuntimeException if stream cant be opened.
	 **/
	public static function getMetaData(string $url, string $eTag) {
//		if($eTag !== "art") {
//			throw(new \RuntimeException("not a valid etag", 400));
//		}
		//set up nested array $options
		$options = [];
		$options["http"] = [];
		$options["http"]["method"] = "HEAD";
		//options

		//Must be an associative array of associative arrays in the format $arr['wrapper']['option'] = $value.
		//Refer to context options for a list of available wrappers and options
		//Default to an empty array.
		$context = stream_context_create($options);
		//fopen( filename, mode, [boolean --false], [context]) | binds a resource (filename) to a stream($fileDownloader)
		$fileDownloader = fopen($url, 'r', false, $context);
		$metaData = stream_get_meta_data($fileDownloader);
		//throw exception if stream doesn't work
		if($fileDownloader === false) {
			throw(new \RuntimeException("unable to open HTTP stream"));
		}
		fclose($fileDownloader);

		//store wrapper data from stream of $url in $header
		$header = $metaData["wrapper_data"];
		//set $eTag as null to start
		$eTag = null;

		//In PHP 7, foreach does not use the internal array pointer. @doc- foreach

		//iterate through wrapper data ($header), assigning current element to $value
		foreach($header as $value) {
			//break up string ($value) by delimiter (":") | store in array $explodeETag
			$explodeETag = explode(":", $value);
			//find "Etag" in array-- false or first matching key
			//http://php.net/manual/en/function.stream-get-meta-data.php
			$findETag = array_search("ETag", $explodeETag);
			//if they don't find anything assign 1 to $eTag (see 63)
			if($findETag !== false) {
				$eTag = $explodeETag[1];
			}
		}
		if($eTag === null) {
			throw(new \RuntimeException("etag cannot be found", 404));
		}
		$config = parse_ini_file("/etc/apache2/capstone-mysql/outside.ini");
		$eTags = json_decode($config["etags"]);
		$previousETag = $eTags->art;
		if($previousETag < $eTag) {
			return ($eTag);
		} else {
			throw(new \OutOfBoundsException("Gus", 401));
		}
	}

	/**
	 *
	 * Decodes Json file, converts to string, sifts through the string and inserts the data into database
	 *
	 * @param string $url
	 * @throws \PDOException for PDO related errors
	 * @throws \Exception catch-all exceptions
	 * @return \SplFixedArray $allData
	 *
	 **/
	public function readDataJson($url) {
		// http://php.net/manual/en/function.stream-context-create.php creates a stream for file input
		$context = stream_context_create(["http" => ["ignore_errors" => true, "method" => "GET"]]);
		try {
			// http://php.net/manual/en/function.file-get-contents.php file-get-contents returns file in string context
			if(($jsonData = file_get_contents($url, null, $context)) === false) {
				throw(new \RuntimeException("cannot connect to city server"));
			}
			//decode the Json file
			$jsonConverted = json_decode($jsonData);
			//format
			$jsonFeatures = $jsonConverted->features;
			//create array from the converted Json file
			$features = \SplFixedArray::fromArray($jsonFeatures);
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($features);
	}

	public static function downloadFromX() {
		$urlX = "http://json";
		/**
		 *run getMetaData and catch exception if the data hasn't changed
		 **/
		$features = null;
		try {
			DataDownloader::getMetaData($urlX, "string");
			$contents = file_get_contents($urlX);
			$contents = utf8_encode($contents);
			$features = json_decode($contents);
//			$features = DataDownloader::readDataJson($urlX);
			$eTagX = DataDownloader::getMetaData($urlX, "string");
			$config = parse_ini_file("/etc/apache2/capstone-mysql/outside.ini");
			$eTags = json_decode($config["etags"]);
			$eTags->art * = $eTagX;
			$config["etags"] = json_encode($eTags);
//			writeConfig($config, "/etc/apache2/capstone-mysql/streetart.ini");
		} catch(\OutOfBoundsException $outOfBoundsException) {
			echo("no new art data found");
		}
		return ($features);
	}

	/**
	 *assigns data from object->features->attributes
	 **/
	public
	static function getTrails(\SplFixedArray $features) {
		$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/outside.ini");
		foreach($features as $feature) {
			$trailId = generateUuidV4();
			$trailExternalId = $feature->attributes->Id
			$trailAddress = $feature->attributes->ADDRESS;
			$trailImage = $feature->attributes->JPG_URL;
			$trailName = $feature->attributes->;
			$trailLocation = $feature->attributes->LOCATION;
			$trailLat = $feature->attributes->Y;
			$trailLong = $feature->attributes->X;
			$trailLength = $feature->attributes->TYPE;
			$trailSummary = $feature->attributes->YEAR;
			$trailAscent =;
			$trailRating =;
			try {
				$trail = new Trail($trailId, $trailExternalId, $trailAddress, $trailImage, $trailName, $trailLocation, $trailSummary, $trailAscent, $trailRating, $trailLength, $trailLat, $trailLong);
				$trail->insert($pdo);
			} catch(\TypeError $typeError) {
				echo("Gus");
			}
	}
	}
}
