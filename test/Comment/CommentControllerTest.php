<?php

namespace Emsa\Comment;

/**
 * Test cases for class Guess.
 */
class CommentControllerTest extends \PHPUnit_Framework_TestCase
{
    protected $comments;

    /**
     * Test case
     */
    protected function setUp()
    {
        $comment1 = new Comment();
        $comment1->post_id = 1;
        $comment1->parent_id = 1;
        $comment1->user = 5;
        $comment1->content = 'varför';
        $comment1->id = 5;
        $comment1->created = '2017-09-10 12:00:00';
        $comment1->edited = '2017-09-20 12:00:00';
        $comment1->upvote = 0;
        $comment1->downvote = 5;
        $comment1->deleted = null;
        $comment1->isUserOwner = false;
        $comment1->isUserAdmin = false;
        $comment1->children = [];

        $comment2 = new Comment();
        $comment2->post_id = 1;
        $comment2->parent_id = 1;
        $comment2->user = 4;
        $comment2->content = 'ja!';
        $comment2->id = 7;
        $comment2->created = '2017-09-01 12:00:00';
        $comment2->edited = '2017-09-19 12:00:00';
        $comment2->upvote = 4;
        $comment2->downvote = 1;
        $comment2->deleted = null;
        $comment2->isUserOwner = false;
        $comment2->isUserAdmin = false;
        $comment2->children = [];

        $comment3 = new Comment();
        $comment3->post_id = 1;
        $comment3->parent_id = 1;
        $comment3->user = 19;
        $comment3->content = 'tjena';
        $comment3->id = 15;
        $comment3->created = '2017-09-11 12:00:00';
        $comment3->edited = null;
        $comment3->upvote = 8;
        $comment3->downvote = 2;
        $comment3->deleted = null;
        $comment3->isUserOwner = false;
        $comment3->isUserAdmin = false;
        $comment3->children = [];

        $comment4 = new Comment();
        $comment4->post_id = 1;
        $comment4->parent_id = 1;
        $comment4->user = 16;
        $comment4->content = 'hejsan';
        $comment4->id = 23;
        $comment4->created = '2017-09-11 09:00:00';
        $comment4->edited = null;
        $comment4->upvote = 10;
        $comment4->downvote = 20;
        $comment4->deleted = null;
        $comment4->isUserOwner = false;
        $comment4->isUserAdmin = false;
        $comment4->children = [];

        $comment5 = new Comment();
        $comment5->post_id = 1;
        $comment5->parent_id = 1;
        $comment5->user = 9;
        $comment5->content = 'hoppsan';
        $comment5->id = 12;
        $comment5->created = '2017-09-02 12:00:00';
        $comment5->edited = null;
        $comment5->upvote = 0;
        $comment5->downvote = 0;
        $comment5->deleted = null;
        $comment5->isUserOwner = false;
        $comment5->isUserAdmin = false;
        $comment5->children = [];

        $this->branchComments = [
            5 => $comment1,
            7 => $comment2,
            15 => $comment3,
            23 => $comment4,
            12 => $comment5
        ];

        $comment6 = new Comment();
        $comment6->post_id = 2;
        $comment6->parent_id = 0;
        $comment6->user = 3;
        $comment6->content = 'Söt hund.';
        $comment6->id = 3;
        $comment6->created = '2017-07-21 12:00:00';
        $comment6->edited = null;
        $comment6->upvote = 3;
        $comment6->downvote = 2;
        $comment6->deleted = null;
        $comment6->isUserOwner = true;
        $comment6->isUserAdmin = false;

        $comment7 = new Comment();
        $comment7->post_id = 2;
        $comment7->parent_id = 0;
        $comment7->user = 3;
        $comment7->content = 'Jag har en tax.';
        $comment7->id = 4;
        $comment7->created = '2016-07-21 12:00:00';
        $comment7->edited = null;
        $comment7->upvote = 3;
        $comment7->downvote = 2;
        $comment7->deleted = null;
        $comment7->isUserOwner = true;
        $comment7->isUserAdmin = false;

        $comment8 = new Comment();
        $comment8->post_id = 2;
        $comment8->parent_id = 4;
        $comment8->user = 6;
        $comment8->content = 'Jag älskar katter';
        $comment8->id = 6;
        $comment8->created = '2016-07-21 12:00:00';
        $comment8->edited = null;
        $comment8->upvote = 0;
        $comment8->downvote = 0;
        $comment8->deleted = null;
        $comment8->isUserOwner = false;
        $comment8->isUserAdmin = false;

        $comment9 = new Comment();
        $comment9->post_id = 2;
        $comment9->parent_id = 0;
        $comment9->user = 3;
        $comment9->content = 'test';
        $comment9->id = 12;
        $comment9->created = '2018-04-15 21:47:22';
        $comment9->edited = null;
        $comment9->upvote = 0;
        $comment9->downvote = 0;
        $comment9->deleted = null;
        $comment9->isUserOwner = true;
        $comment9->isUserAdmin = false;

        $this->comments = [$comment6, $comment7, $comment8, $comment9];
    }

    /**
     * Test case
     */
    public function testSortBranchComments()
    {
        $commentController = new CommentController();
        $di = new \Anax\DI\DIFactoryConfigMagic([
            "services" => [
            ]
        ]);
        $commentController->setDI($di);

        $sortedByBest = [
            $this->branchComments[15],
            $this->branchComments[7],
            $this->branchComments[12],
            $this->branchComments[5],
            $this->branchComments[23],
        ];
        $sortedByOld = [
            $this->branchComments[7],
            $this->branchComments[12],
            $this->branchComments[5],
            $this->branchComments[23],
            $this->branchComments[15],
        ];
        $sortedByNew = [
            $this->branchComments[15],
            $this->branchComments[23],
            $this->branchComments[5],
            $this->branchComments[12],
            $this->branchComments[7],
        ];
        $sortedByDefault = [
            $this->branchComments[15],
            $this->branchComments[7],
            $this->branchComments[12],
            $this->branchComments[5],
            $this->branchComments[23],
        ];

        $commentController->sortBranchComments($this->branchComments, "best");
        $this->assertEquals($sortedByBest, $this->branchComments);
        $commentController->sortBranchComments($this->branchComments, "old");
        $this->assertEquals($sortedByOld, $this->branchComments);
        $commentController->sortBranchComments($this->branchComments, "new");
        $this->assertEquals($sortedByNew, $this->branchComments);
        $commentController->sortBranchComments($this->branchComments);
        $this->assertEquals($sortedByDefault, $this->branchComments);
    }


    /**
     * Test case
     */
    public function testbuildCommentTree()
    {
        $commentController = new CommentController();
        $di = new \Anax\DI\DIFactoryConfigMagic([
            "services" => [
            ]
        ]);
        $commentController->setDI($di);

        $correctCommentTree = [
            $this->comments[0],
            $this->comments[1],
            $this->comments[3]
        ];
        $correctCommentTree[1]->children = $this->comments[2];

        $commentTree = $commentController->buildCommentTree($this->comments, "best");
        $this->assertEquals($correctCommentTree, $commentTree);
    }
}
