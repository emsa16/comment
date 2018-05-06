<?php

namespace Emsa\Comment;

/**
 * Test cases for class Guess.
 */
class CommentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test case
     */
    public function testTimeElapsedString()
    {
        $comment = new Comment();
        $comment->created = date('Y-m-d H:i:s', strtotime('-2 week'));
        $timeElapsedString = $comment->timeElapsedString($comment->created);
        $this->assertEquals('2 veckor sedan', $timeElapsedString);
    }
}
