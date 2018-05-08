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
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Tweet class
 *
 * This is a complete PHPUnit test of the Tweet class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Tweet
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class CommentTest extends AbqOutsideTest {
	/**
	 * Profile that created comment for foreign key relations
	 * @var Profile profile
	 **/
	protected $profile = null;

commentId BINARY(16),
commentProfileId BINARY(16) NOT NULL,
commentTrailId BINARY(16) NOT NULL,
commentTimestamp DATETIME(6) NOT NULL,
