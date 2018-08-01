<?php

namespace Edu\Cnm\AbqOutside;

require_once("autoload.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once (dirname(__DIR__, 1) . "/lib/uuid.php");

class DataDownloader {
	//Test Edit

	public static function pullTrails() {
		$trailsX = null;
		$urlBase = "https://www.hikingproject.com/data/get-trails?lat=35.085470&lon=-106.649072&maxDistance=200&maxResults=500&key=200265121-1809e265008042f9977e435839863103";
		$trailsX = self::readDataJson($urlBase);
		$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/outside.ini");
		$imgCount=0;
		$sumCount=0;
		$trailCount=0;
		foreach($trailsX as $value) {
			$trailId = generateUuidV4();
			$trailExternalId = $value->id;
			$trailAddress = "outdoors";
			$trailImage = $value->imgMedium;
			//Missing image counter
			if (empty($value->imgMedium)=== true) {
				$trailImage = "needs an image";
			$imgCount = $imgCount + 1;
			}
			$trailName = $value->name;
			$trailLocation = $value->location;
			$trailLat = (float)$value->latitude;
			$trailLong = (float)$value->longitude;
			$trailLength = (float)$value->length;
			$trailSummary = $value->summary;
			//Missing description counter
			if ((empty($trailSummary)||$trailSummary ==="Needs Adoption" )===true){
				$trailSummary = "needs description";
				$sumCount = $sumCount + 1;
			}
			$trailAscent = (int)$value->ascent;
			$trailRating = (float)$value->stars;
			$trailCount = $trailCount + 1;
			try {
			$trail = new Trail($trailId, $trailAddress, $trailAscent, $trailExternalId, $trailImage, $trailLat, $trailLength, $trailLocation, $trailLong, $trailName, $trailRating, $trailSummary);
// Show how many trails are pulled from data downloader, missing summaries and images
//			var_dump($trail->getTrailId()->toString());
//			var_dump($imgCount);
//			var_dump($sumCount);
//			var_dump($trailCount);
			$trail->insert($pdo);
			} catch(\TypeError $typeError) {
				echo("Error Connecting to database");
			}
		}
	}
	public static function readDataJson($url) {

		$context = stream_context_create(["http" => ["ignore_errors" => true, "method" => "GET"]]);
		try {
			//file-get-contents returns file in string context
			if(($jsonData = file_get_contents($url, null, $context)) === false) {
				throw(new \RuntimeException("url doesn't produce results"));
			}
			//decode the Json file
			$jsonConverted = json_decode($jsonData);
			//format
			$jsonFeatures = $jsonConverted->trails;

			$trailsX = \SplFixedArray::fromArray($jsonFeatures);
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($trailsX);
	}
}

echo DataDownloader::pullTrails().PHP_EOL;