<?php

namespace Emsa\Comment;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \LRC\Form\ModelForm as Modelform;

/**
 * A controller for the comment system.
 */
class CommentController implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    /**
     * @var \LRC\Repository\SoftDbRepository  Comment repository.
     */
    private $comments;



    /**
     * Configuration.
     */
    public function init()
    {
        $commentRepository = $this->di->manager->createRepository(Comment::class, [
            'db' => $this->di->db,
            'type' => 'db-soft',
            'table' => 'rv1_Comment'
        ]);
        $this->comments = $commentRepository;
        return $commentRepository;
    }



    public function showComments($postid)
    {
        $newForm = new ModelForm('new-comment-form', Comment::class);
        $loggedInUser = $this->di->userController->getLoggedInUserId();

        if ($this->di->request->getMethod() == 'POST' && $this->di->session->has("username")) {
            $comment = $newForm->populateModel();
            $newForm->validate();
            if ($newForm->isValid()) {
                $comment->user = $loggedInUser;
                $this->comments->save($comment);
                $this->di->response->redirect("comment/$postid#{$comment->id}");
            }
        }

        $comments = $this->getComments($postid, $loggedInUser);
        $sortBy = $this->sortBy();
        $sortedComments = $this->buildCommentTree($comments, $sortBy);

        $viewData = [
            "comments" => $sortedComments,
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "",
            "actionID" => "",
            "newForm" => $newForm,
            "isLoggedIn" => $this->di->session->has("username")
        ];

        $this->di->view->add("comment/comment-section", $viewData, "main", 2);
    }



    public function replyComment($postid)
    {
        $actionID = (int)$this->di->request->getGet("id");
        $loggedInUser = $this->di->userController->getLoggedInUserId();

        if ($this->di->request->getMethod() == 'GET' && (!$this->comments->find('id', $actionID) || !$this->di->session->has("username"))) {
            $this->di->response->redirect("comment/$postid");
        }

        $replyForm = new ModelForm('reply-comment-form', Comment::class);

        if ($this->di->request->getMethod() == 'POST') {
            $comment = $replyForm->populateModel();
            $replyForm->validate();
            if ($replyForm->isValid()) {
                $comment->user = $loggedInUser;
                $this->comments->save($comment);
                $this->di->response->redirect("comment/$postid#{$comment->id}");
            }
        }

        $newForm = new ModelForm('new-comment-form', Comment::class);

        $comments = $this->getComments($postid, $loggedInUser);
        $sortBy = $this->sortBy();
        $sortedComments = $this->buildCommentTree($comments, $sortBy);

        $viewData = [
            "comments" => $sortedComments,
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "reply",
            "actionID" => $actionID,
            "newForm" => $newForm,
            "replyForm" => $replyForm,
            "isLoggedIn" => $this->di->session->has("username")
        ];

        $this->di->view->add("comment/comment-section", $viewData, "main", 2);
    }



    public function editComment($postid)
    {
        $actionID = (int)$this->di->request->getGet("id");

        $currentComment = $this->comments->findSoft('id', $actionID);
        if (!$currentComment) {
            $this->di->response->redirect("comment/$postid");
        }

        $loggedInUser = $this->di->userController->getLoggedInUserId();
        if ($loggedInUser != $currentComment->user && !$this->di->session->has("admin")) {
            $this->di->response->redirect("comment/$postid");
        }

        $editForm = new ModelForm('edit-comment-form', $currentComment);

        if ($this->di->request->getMethod() == 'POST') {
            $comment = $editForm->populateModel(null, ['id', 'post_id', 'parent_id']);
            //Prevent edited column from being set to NULL
            unset($comment->edited);
            $editForm->validate();
            if ($editForm->isValid()) {
                $this->comments->save($comment);
                $this->di->response->redirect("comment/$postid#{$comment->id}");
            }
        }

        $newForm = new ModelForm('new-comment-form', Comment::class);

        $comments = $this->getComments($postid, $loggedInUser);
        $sortBy = $this->sortBy();
        $sortedComments = $this->buildCommentTree($comments, $sortBy);

        $viewData = [
            "comments" => $sortedComments,
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "edit",
            "actionID" => $actionID,
            "newForm" => $newForm,
            "editForm" => $editForm,
            "isLoggedIn" => $this->di->session->has("username")
        ];

        $this->di->view->add("comment/comment-section", $viewData, "main", 2);
    }



    public function deleteComment($postid)
    {
        $actionID = (int)$this->di->request->getGet("id");

        $currentComment = $this->comments->findSoft('id', $actionID);
        if (!$currentComment) {
            $this->di->response->redirect("comment/$postid");
        }

        $loggedInUser = $this->di->userController->getLoggedInUserId();
        if ($loggedInUser != $currentComment->user && !$this->di->session->has("admin")) {
            $this->di->response->redirect("comment/$postid");
        }

        if ($this->di->request->getMethod() == 'POST') {
            if ($this->di->request->getPost('delete') == 'Ja') {
                $this->comments->deleteSoft($currentComment);
            }
            $this->di->response->redirect("comment/$postid#{$currentComment->id}");
        }

        $newForm = new ModelForm('new-comment-form', Comment::class);

        $comments = $this->getComments($postid, $loggedInUser);
        $sortBy = $this->sortBy();
        $sortedComments = $this->buildCommentTree($comments, $sortBy);

        $viewData = [
            "comments" => $sortedComments,
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "delete",
            "actionID" => $actionID,
            "newForm" => $newForm,
            "isLoggedIn" => $this->di->session->has("username")
        ];

        $this->di->view->add("comment/comment-section", $viewData, "main", 2);
    }



    public function voteComment($postid)
    {
        $actionID = (int)$this->di->request->getGet("id");

        $comment = $this->comments->findSoft('id', $actionID);
        if (!$comment) {
            $this->di->response->redirect("comment/$postid");
        }

        if (!$this->di->session->has("username")) {
            $this->di->response->redirect("comment/$postid");
        }

        if ($this->di->request->getPost("upvote")) {
            $comment->upvote += 1;
        } elseif ($this->di->request->getPost("downvote")) {
            $comment->downvote += 1;
        }

        $this->comments->save($comment);
        $this->di->response->redirect("comment/$postid#{$comment->id}");
    }



    public function getComments($postid, $loggedInUser)
    {
        $comments = $this->comments->getAll('post_id = ?', [$postid]);

        foreach ($comments as $comment) {
            $comment->isUserOwner = ($loggedInUser == $comment->userObject->id);
            $comment->isUserAdmin = $this->di->session->has("admin");
        }

        return $comments;
    }



    public function sortBy()
    {
        $sortRequest = $this->di->request->getGet("sort");
        $sortRules = ["best", "old", "new"];
        return in_array($sortRequest, $sortRules) ? $sortRequest : "best";
    }



    public function buildCommentTree(array &$elements, $sortBy, $parentId = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $children = $this->buildCommentTree($elements, $sortBy, $element->id);
                if (!empty($children)) {
                    $element->children = $children;
                }
                $branch[$element->id] = $element;
                // Ingen aning varför jag haft unset tidigare, och nu funkar det inte som tidigare heller
                // unset($elements[$element->id]);
            }
        }
        $this->sortBranchComments($branch, $sortBy);
        return $branch;
    }



    public function sortBranchComments(array &$branch, $sortBy = "best")
    {
        $sortOrder = SORT_DESC;
        $sortArray = array();
        foreach ($branch as $key => $comment) {
            switch ($sortBy) {
                case 'old':
                    $sortOrder = SORT_ASC;
                    //Intentional fall through
                case 'new':
                    $sortArray[$key] = $comment->created;
                    break;
                case 'best':
                    //Intentional fall through
                default:
                    $sortArray[$key] = ($comment->upvote - $comment->downvote);
                    break;
            }
        }
        array_multisort($sortArray, $sortOrder, $branch);
    }
}
