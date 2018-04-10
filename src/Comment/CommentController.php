<?php

namespace Emsa\Comment;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;

/**
 * A controller for the comment system.
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
class CommentController implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    public function showComments($postid)
    {
        $allComments = $this->di->rem->getDataset("comments");
        $postComments = $this->di->comment->getCommentsForPost($postid, $allComments);
        $sortBy = $this->di->comment->getSortQuery($this->di->request);
        $hierarchedComments = $this->di->comment->buildTree($postComments, $sortBy);
        $baseUrl = $this->di->url->create("comment/$postid");
        $actionCommentDetails = $this->di->comment->getActionCommentDetails($this->di->request);
        $commentSection = $this->di->comment->buildCommentSection($hierarchedComments, $this->di->textfilter, $baseUrl, $actionCommentDetails["actionCommentId"], $actionCommentDetails["actionCommentMethod"]);
        $commentBox = $this->di->comment->buildNewCommentBox($baseUrl, $postid);
        $this->di->view->add("comments", [
            "commentBox" => $commentBox,
            "comments" => $commentSection,
            "postid" => $postid,
            "baseUrl" => $baseUrl
        ], "main", 2);
    }



    public function createComment($postid)
    {
        $postValues = $this->di->request->getPost();
        $entry = $this->di->comment->compileNewComment($postValues);
        if (!$entry) {
            $this->di->response->redirect("comment/$postid");
            exit;
        }
        $item = $this->di->rem->addItem("comments", $entry);
        $commentid = $item["id"];
        $this->di->response->redirect("comment/$postid#$commentid");
    }



    public function editComment($postid)
    {
        $postValues = $this->di->request->getPost();
        $item = $this->di->rem->getItem("comments", (int)$postValues["id"]);
        $item["text"] = $postValues["text"];
        $item["edited"] = date("Y-m-d H:i:s");
        $item = $this->di->rem->upsertItem("comments", (int)$postValues["id"], $item);
        $commentid = $item["id"];
        $this->di->response->redirect("comment/$postid#$commentid");
    }



    public function deleteComment($postid)
    {
        $postValues = $this->di->request->getPost();
        $item = $this->di->rem->getItem("comments", (int)$postValues["id"]);

        if (isset($postValues["delete"])) {
            // $this->di->rem->deleteItem("comments", (int)$postValues["id"]);
            $item["deleted"] = 1;
            $item = $this->di->rem->upsertItem("comments", (int)$postValues["id"], $item);
        }
        $commentid = $item["id"];
        $this->di->response->redirect("comment/$postid#$commentid");
    }



    public function voteComment($postid)
    {
        $postValues = $this->di->request->getPost();
        $item = $this->di->rem->getItem("comments", (int)$postValues["id"]);
        $commentid = $item["id"];

        if ($item["deleted"] == 1) {
            $this->di->response->redirect("comment/$postid#$commentid");
            exit;
        }

        if (isset($postValues["upvote"])) {
            $item["upvote"] += 1;
        } else if (isset($postValues["downvote"])) {
            $item["downvote"] += 1;
        }

        $item = $this->di->rem->upsertItem("comments", (int)$postValues["id"], $item);
        $this->di->response->redirect("comment/$postid#$commentid");
    }
}
