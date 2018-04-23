<?php

namespace Emsa\Comment;

/**
 * Test cases for class Guess.
 */
class CommentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test case
     */
    public function testTimeElapsedString()
    {
        $comment = new Comment();
        // $comment->created = '2017-09-20 12:00:00';
        $comment->created = date('Y-m-d H:i:s', strtotime('-2 week'));
        $timeElapsedString = $comment->timeElapsedString($comment->created);
        $this->assertEquals('2 veckor sedan', $timeElapsedString);
    }
}
