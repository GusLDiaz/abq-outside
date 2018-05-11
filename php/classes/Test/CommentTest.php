<?php
/**
 * Created by PhpStorm.
 * User: Gusli
 * Date: 5/8/2018
 * Time: 2:43 PM
 */

namespace Edu\Cnm\AbqOutside\Test;

use Edu\Cnm\AbqOutside\{
	Profile, Trail
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


		// create and insert a Profile to own the test (write the comment
		//id email image Refreshtoken username
		$this->profile = new Profile(generateUuidV4(), "email", "imagehandle", "https://media.giphy.com/media/3og0INyCmHlNylks9O/giphy.gif", "username");//,$this->VALID_PROFILE_HASH, "+12125551212");
		$this->profile->insert($this->getPDO());
		// create trail To be commented on?
		// do a  base api call first?
		$this->trail = new Trail(generateUuidV4(), "7475773", "address", "imagehandle",)
	// calculate the date (just use the time the unit test was setup...)
		$this->$VALID_COMMENT_TIMESTAMP = new \DateTime();
	}

//

	public function testInsertValidComment(): void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("tweet");

		// create a new Comment, refer to trail and profile, and insert to into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->trail->getTrailId(), $this->VALID_COMMENT_CONTENT, $this->VALID_COMMENT_TIMESTAMP);
		$comment
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

}
