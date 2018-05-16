<?php
namespace Edu\Cnm\AbqOutside\Test;

use Edu\Cnm\AbqOutside\Comment;
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");
// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Tweet class
 *
 * This is a complete PHPUnit test of the Comment class. It is complete because *ALL* enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Comment
 * @author Gus Liakos <gusliakos@outlook.com>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 */
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
	/** @var string derivative from Oauth*/
	protected $VALID_PROFILE_REFRESH_TOKEN;
	/**
	 * content of the Comment
	 * @var string $VALID_COMMENT_CONTENT
	 *
	 */
	protected $VALID_COMMENT_CONTENT = "PHPUnit test passing";
	/**
	 * content of the updated comment
	 * @var string $VALID_COMMENTCONTENT2
	 **/
	protected $VALID_COMMENT_CONTENT2 = "PHPUnit test still passing";
	/**
	 * timestamp of the comment; this starts as null and is assigned later
	 * @var \DateTime $VALID_COMMENT_TIMESTAMP
	 **/
	protected $VALID_COMMENT_TIMESTAMP = null;
//TODO finish setUP re: Trail, refresh Token
	protected final function setUp(): void {
		// run setUp() method
		parent::setUp();
		$this->VALID_PROFILE_REFRESH_TOKEN = bin2hex(random_bytes(16));

		// create and insert a Profile to own the test (write the comment)
		//order: profileId email image Refresh token username
		$this->profile = new Profile(generateUuidV4(), "email", "imagehandle", "https://media.giphy.com/media/3og0INyCmHlNylks9O/giphy.gif", "username");
		$this->profile->insert($this->getPDO());

		// create trail to be commented on
		// do a  base api call first?
		//trail order: trailId, trailExternalId,trailAddress,trailImage,trailName,trailLocation, trailSummary, trailAscent, trailRating, trailLength,trailLat,trailLong
		$this->trail = new Trail(generateUuidV4(), "7475773", "address", "imagehandle", "trailname","trail location","trail description summary","13","3", "13.3","81.6","21.5");
		$this->trail->insert($this->getPDO());

		// calculate the date (just use the time the unit test was setup...)
		$this->VALID_COMMENT_TIMESTAMP = new \DateTime();
	}

	/**
	 * test adding a new comment to system
	 *
	 */
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
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentTrailId(), $this->trail->getTrailId());
		$this->assertEquals($pdoComment->getCommmentContent(), $this->VALID_COMMENT_CONTENT);
		$this->assertEquals($pdoComment->getCommentTimestamp()->getTimestamp(), $this->VALID_COMMENT_TIMESTAMP->getTimestamp());
	}

	/**
	 * test inserting a comment, editing it, and then updating it
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
	 * test creating and deleting a comment
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

	/**
	 * test grabbing a comment out of range
	 * commentId
	 */
	public function testGetInvalidCommentByCommentId(): void {
		// grab a profile id that exceeds the maximum allowable profile id
		$comment = Comment::getCommentByCommentId($this->getPDO(), generateUuidV4());
		$this->assertNull($comment);
	}

	/**
	 * test grabbing a comment by its profile
	 * profile Id
	 */
	public function testGetValidCommentByCommentProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create and insert new comment to into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->trail->getTrailId(), $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_TIMESTAMP);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Comment::getCommentByCommentProfileId($this->getPDO(), $comment->getCommentProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqOutside\\Comment", $results);

		// grab the result from the array and validate it
		$pdoComment = $results[0];

		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentTrailId(), $this->trail->getTrailId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENT_CONTENT);
		//format the date two seconds after beginning of time, for round off error
		$this->assertEquals($pdoComment->getCommentTimestamp()->getTimestamp(), $this->VALID_COMMENT_TIMESTAMP->getTimestamp());
	}

	/**
 	* test grabbing a comment by its trail
 	* trail Id
 	*/
	public function testGetValidCommentByCommentTrailId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		// create a new Comment and insert to into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->trail->getTrailId(), $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_TIMESTAMP);
		$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Comment::getCommentBycommentTrailId($this->getPDO(), $comment->getCommentTrailId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqOutside\\Comment", $results);

		// grab the result from the array and validate it
		$pdoComment = $results[0];

		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentTrailId(), $this->trail->getTrailId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENT_CONTENT);
		//format the date two seconds after beginning of time, for round off error
		$this->assertEquals($pdoComment->getCommentTimestamp()->getTimestamp(), $this->VALID_COMMENT_TIMESTAMP->getTimestamp());
	}
	/**
	 * test grabbing a Comment that does not exist (Profile)
	 *   profile Id
	 **/
	public function testGetInvalidCommentByCommentProfileId(): void {
		// grab a profile id that exceeds the maximum allowable profile id
		$comment = Comment::getCommentByCommentProfileId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $comment);
	}
	/**
	 * test grabbing a Comment that does not exist (Trail)
	 *   trail Id
	 **/
	public function testGetInvalidCommentByCommentTrailId(): void {
		// grab a trail id  that exceeds the maximum allowable trail id
		$comment = Comment::getCommentByCommentTrailId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $comment);
	}
	/**
	 * test grabbing comments by content
	 *
	 */
	public function testGetValidCommentByCommentContent(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");
		// create and insert a comment  into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->trail->getTrailId(),$this->VALID_COMMENT_TIMESTAMP, $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_TIMESTAMP);
		$comment->insert($this->getPDO());

			// grab the data from mySQL and enforce the fields match our expectations
			$results = Comment::getCommentByCommentContent($this->getPDO(), $comment->getCommentContent());
			$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
			$this->assertCount(1, $results);

			// enforce no other objects are bleeding into test-- only Comment instantiations found
			$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqOutside\\Comment", $results);

			// grab the result from the array and validate it
			$pdoComment = $results[0];
			$this->assertEquals($pdoComment->getCommentId(), $commentId);
			$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
			$this->assertEquals($pdoComment->getCommentTrailId(), $this->trail->getTrailId());
			$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENT_CONTENT);
			$this->assertEquals($pdoComment->getCommentTimestamp()->getTimestamp(), $this->VALID_COMMENT_TIMESTAMP->getTimestamp());
		}
	/**
	 * test grabbing a Comment by content that does not exist
	 **/
	public function testGetInvalidCommentByCommentContent(): void {
		// grab a comment by content that does not exist (*i'm keeping your comment!)
		$comment = Comment::getCommentByCommentContent($this->getPDO(), "Comcast has the best service EVER #comcastLove");
		$this->assertCount(0, $comment);
	}
	/**
	 * test grabbing all Comments
	 **/
	public function testGetAllValidComments() : void {
			// count the number of rows and save it for later
			$numRows = $this->getConnection()->getRowCount("comment");
			// create a new Comment and insert to into mySQL
			$commentId = generateUuidV4();
			$comment = new Comment($commentId, $this->profile->getProfileId(),$this->trail->getTrailId(), $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_TIMESTAMP);
			$comment->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
			$results = Comment::getAllComments($this->getPDO());
			$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
			$this->assertCount(1, $results);
			$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqOutside", $results);

			// grab the result from the array and validate it
			$pdoComment = $results[0];
			$this->assertEquals($pdoComment->getCommentId(), $commentId);
			$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
			$this->assertEquals($pdoComment->getCommentTrailId(), $this->trail->getTrailId());
			$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENT_CONTENT);
			$this->assertEquals($pdoComment->getCommentTimestamp()->getTimestamp(), $this->VALID_CONTENT_TIMESTAMP->getTimestamp());
		}
}