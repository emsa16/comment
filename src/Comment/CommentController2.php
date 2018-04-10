<?php

namespace Emsa\Comment;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \LRC\Form\ModelForm as Modelform;

/**
 * A controller for the comment system.
 */
class CommentController2 implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    /**
     * @var \LRC\Database\DbRepository  Book repository.
     */
    private $comments;



    /**
     * Configuration.
     */
    public function init()
    {
        $this->comments = $this->di->manager->createRepository(Comment2::class, [
            'db' => $this->di->db,
            'type' => 'db-soft',
            'table' => 'rv1_Comment'
        ]);
    }



    public function showComments($postid)
    {
        $form = new ModelForm('new-comment-form', Comment2::class);

        if ($this->di->request->getMethod() == 'POST' && $this->di->session->has("username")) {
            $comment = $form->populateModel();
            $form->validate();
            if ($form->isValid()) {
                $comment->user = $this->di->userController->getLoggedInUserId();
                $this->comments->save($comment);
                $this->di->response->redirect("comment/$postid#{$comment->id}");
            }
        }

        $comments = $this->getSortedComments($postid);

        $viewData = [
            "comments" => $comments,
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "",
            "actionID" => "",
            "form" => $form,
            "isLoggedIn" => $this->di->session->has("username")
        ];

        $this->di->view->add("comment/comment-section", $viewData, "main", 2);
    }



    public function replyComment($postid)
    {
        if (!$this->di->session->has("username")) {
            $this->di->response->redirect("comment/$postid");
        }

        $form = new ModelForm('reply-comment-form', Comment2::class);

        if ($this->di->request->getMethod() == 'POST') {
            $comment = $form->populateModel();
            $form->validate();
            if ($form->isValid()) {
                $comment->user = $this->di->userController->getLoggedInUserId();
                $this->comments->save($comment);
                $this->di->response->redirect("comment/$postid#{$comment->id}");
            }
        }

        $actionID = (int)$this->di->request->getGet("id");

        $currentComment = $this->comments->find('id', $actionID);
        if (!$currentComment) {
            $this->di->response->redirect("comment/$postid");
        }

        $comments = $this->getSortedComments($postid);

        $viewData = [
            "comments" => $comments,
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "reply",
            "actionID" => $actionID,
            "form" => $form,
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

        $loggedInUserId = $this->di->userController->getLoggedInUserId();
        if ($loggedInUserId != $currentComment->user && !$this->di->session->has("admin")) {
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

        $newForm = new ModelForm('new-comment-form', Comment2::class);

        $comments = $this->getSortedComments($postid);

        $viewData = [
            "comments" => $comments,
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "edit",
            "actionID" => $actionID,
            "form" => $newForm,
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

        $loggedInUserId = $this->di->userController->getLoggedInUserId();
        if ($loggedInUserId != $currentComment->user && !$this->di->session->has("admin")) {
            $this->di->response->redirect("comment/$postid");
        }

        if ($this->di->request->getMethod() == 'POST') {
            if ($this->di->request->getPost('delete') == 'Ja') {
                $this->comments->deleteSoft($currentComment);
            }
            $this->di->response->redirect("comment/$postid#{$currentComment->id}");
        }

        $form = new ModelForm('new-comment-form', Comment2::class);

        $comments = $this->getSortedComments($postid);

        $viewData = [
            "comments" => $comments,
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "delete",
            "actionID" => $actionID,
            "form" => $form,
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
        } else if ($this->di->request->getPost("downvote")) {
            $comment->downvote += 1;
        }

        $this->comments->save($comment);
        $this->di->response->redirect("comment/$postid#{$comment->id}");
    }



    public function getSortedComments($postid)
    {
        $comments = $this->comments->getAll('post_id = ?', [$postid]);

        $users = $this->di->userController->init();

        $username = $this->di->session->get("username");
        $loggedInUser = $users->findSoft('username', $username);

        foreach ($comments as $comment) {
            // TEMP Nu görs en databasförfrågan för varje kommentar, effektivisera med vyer?
            $user = $comment->getReference('user', $users, false);
            $comment->user = [
                'email' => $user->email,
                'username' => $user->username,
                'deleted' => $user->deleted,
                'isOwner' => ($loggedInUser && $loggedInUser->username == $user->username),
                'isAdmin' => $this->di->session->has("admin")
            ];
        }

        $sortRequest = $this->di->request->getGet("sort");
        $sortRules = ["best", "old", "new"];
        $sortBy = in_array($sortRequest, $sortRules) ? $sortRequest : "best";
        return $this->sort($comments, $sortBy);
    }



    public function sort(array &$elements, $sortBy, $parentId = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $children = $this->sort($elements, $sortBy, $element->id);
                if ($children) {
                    $element->children = $children;
                }
                $branch[$element->id] = $element;
                // Ingen aning varför jag haft unset tidigare, och nu funkar det inte som tidigare heller
                // unset($elements[$element->id]);
            }
        }
        $this->sortBranch($branch, $sortBy);
        return $branch;
    }



    public function sortBranch(array &$branch, $sortBy = "best")
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
