<?php
/**
 * Created by PhpStorm.
 * User: Gusli
 * Date: 5/8/2018
 * Time: 2:43 PM
 */

namespace Edu\Cnm\AbqOutside\Test;

use Edu\Cnm\AbqOutside\{
	Comment, Profile, Trail
};

// grab the class under scrutiny
//require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
//require_once(dirname(__DIR__, 2) . "/lib/uuid.php");


class CommentTest extends AbqOutsideTest {
	/**
	 * Profile that created comment for foreign key relations
	 * @var Profile profile
	 **/
	protected $profile = null;
	/**
	 * @var Trail trail
	 */
	protected $trail = null;
	protected $VALID_PROFILE_REFRESH_TOKEN;

	/**
	 * content of the Comment
	 * @var string $VALID_COMMENT_CONTENT
	 *
	 */
	protected $VALID_COMMENT_CONTENT = "PHPUnit test passing";

	/**
	 * content of the updated comment
	 * @var string $VALID_COMMENT_CONTENT2
	 **/
	protected $VALID_COMMENT_CONTENT2 = "PHPUnit test still passing";

	/**
	 * timestamp of the comment; this starts as null and is assigned later
	 * @var \DateTime $VALID_COMMENT_TIMESTAMP
	 **/
	protected $VALID_COMMENT_TIMESTAMP = null;

	protected final function setUp(): void {
		// run setUp() method
		parent::setUp();
//	$password = "abc123";
//	$this->VALID_PROFILE_REFRESH_TOKEN = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);


		// create and insert a Profile to own the test (write the comment)
		//id email image Refreshtoken username
		$this->profile = new Profile(generateUuidV4(), "email", "imagehandle", "https://media.giphy.com/media/3og0INyCmHlNylks9O/giphy.gif", "username");//,$this->VALID_PROFILE_HASH, " 12125551212");
		$this->profile->insert($this->getPDO());
		// create trail To be commented on?
		// do a  base api call first?
		$this->trail = new Trail(generateUuidV4(), "7475773", "address", "imagehandle",);
		// calculate the date (just use the time the unit test was setup...)
		$this->$VALID_COMMENT_TIMESTAMP = new \DateTime();
	}

//

	public function testInsertValidComment(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment, refer to trail and profile, and insert to into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->trail->getTrailId(), $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_TIMESTAMP);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommnetProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommmentContent(), $this->VALID_COMMENT_CONTENT);
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentTimestamp()->getTimestamp(), $this->VALID_COMMENT_TIMESTAMP->getTimestamp());
	}


	/**
	 *      * test inserting a Comment, editing it, and then updating it
	 *      **/
	public function testUpdateValidComment(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_TIMESTAMP);
		$comment->insert($this->getPDO());

		// edit and update it in mySQL
		$comment->setCommentContent($this->VALID_COMMENT_CONTENT2);
		$comment->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentTrailId(), $this->trail->getTrailId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENT_CONTENT2);
		//format the date two seconds after beginning of time, for round off error
		$this->assertEquals($pdoComment->getCommentTimestamp()->getTimestamp(), $this->VALID_COMMENT_TIMESTAMP->getTimestamp());
	}

	/**
	 *      * test creating and deleting a comment
	 *      **/
	public function testDeleteValidComment(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->trail->getTrailId(), $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_TIMESTAMP);
		$comment->insert($this->getPDO());

		// delete from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
	 $comment->delete($this->getPDO());
	 
	       // use SQL data to enforce comment no longer exists
	 $pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
	 $this->assertNull($pdoComment);
	 $this->assertEquals($numRows, $this->getConnection()->getRowCount("comment"));
	 }
}
///tweet
///grab a comment that doesn't exist
/// 	by profile id
/// 	by trail id
///insert a Comment and regrabbing it from sql
/// 	again by trail
///
	public function testGetInvalidCommentByCommentId() : void {
		// grab a profile id that exceeds the maximum allowable profile id
		$comment = Comment::getCommentByCommentId($this->getPDO(), generateUuidV4());
		$this->assertNull($comment);
	}

	public function testGetValidCommentByCommentProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create and insert new comment to into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(),$this->trail->getTrailId(), $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_TIMESTAMP);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Comment::getCommentByCommentProfileId($this->getPDO(), $comment->getCommentProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
//		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DataDesign\\Tweet", $results);

		// grab the result from the array and validate it
		$pdoComment = $results[0];

		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
//		$this->assertEquals($pdoComment->getCommentProfileId(), $this->trail->getTrailId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENT_CONTENT);
	//format the date two seconds after beginning of time, for round off error
	$this->assertEquals($pdoComment->getCommentTimestamp()->getTimestamp(), $this->VALID_COMMENT_TIMESTAMP->getTimestamp());
}
public function testGetValidCommentByCommentProfileId() {
	// count the number of rows and save it for later
	$numRows = $this->getConnection()->getRowCount("comment");

	// create a new Comment and insert to into mySQL
	$commentId = generateUuidV4();
	$comment = new Comment($commentId, $this->profile->getProfileId(),$this->trail->getTrailId(), $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_TIMESTAMP);
	$comment->insert($this->getPDO());

	// grab the data from mySQL and enforce the fields match our expectations
	$results = Comment::getCommentByCommentProfileId($this->getPDO(), $comment->getCommentProfileId());
	$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
	$this->assertCount(1, $results);
//	$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DataDesign\\Tweet", $results);

	// grab the result from the array and validate it
	$pdoComment = $results[0];

	$this->assertEquals($pdoComment->getCommentId(), $commentId);
	$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
	$this->assertEquals($pdoComment->getCommentProfileId(), $this->trail->getTrailId());
	$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENT_CONTENT);
	//format the date two seconds after beginning of time, for round off error
	$this->assertEquals($pdoComment->getCommentTimestamp()->getTimestamp(), $this->VALID_COMMENT_TIMESTAMP->getTimestamp());
}

	/**
	 * test grabbing a Comment that does not exist
	 * 	profile Id
	 **/
	public function testGetInvalidCommentByCommentProfileId() : void {
		// grab a profile id that exceeds the maximum allowable profile id
		$tweet = Comment ::getTweetByTweetProfileId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $tweet);
	}
/**
 * test grabbing a Comment that does not exist
 * 	trail Id
 **/
public function testGetInvalidCommentByCommentTrailId() : void {
	// grab a trail id  that exceeds the maximum allowable trail id
	$comment = Comment::getCommentByCommentTrailId($this->getPDO(), generateUuidV4());
	$this->assertCount(0, $comment);
}