<?php
/**
 * Created by PhpStorm.
 * User: Gusli
 * Date: 5/8/2018
 * Time: 2:43 PM
 */
namespace Edu\Cnm\AbqOutside\Test;

use Edu\Cnm\AbqOutside\{Profile, Trail};

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
	protected $VALID_PROFILE_HASH;

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
}
