<?php

namespace LRC\Repository;

class DbRepository
{
    public function __construct($modelClass, $config)
    {
        $this->modelClass = $modelClass;
        $this->config = $config;

        $comment6 = new $modelClass();
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

        $comment7 = new $modelClass();
        $comment7->post_id = 1;
        $comment7->parent_id = 0;
        $comment7->user = 3;
        $comment7->content = 'Jag har en tax.';
        $comment7->id = 4;
        $comment7->created = '2016-07-21 12:00:00';
        $comment7->edited = null;
        $comment7->upvote = 3;
        $comment7->downvote = 2;
        $comment7->deleted = null;

        $comment8 = new $modelClass();
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

        $comment9 = new $modelClass();
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

        $this->comments = [$comment6, $comment7, $comment8, $comment9];
    }

    public function getAll($query, $values)
    {
        if ($query != 'post_id = ?') {
            return null;
        }

        $comments = [];
        foreach ($this->comments as $comment) {
            if ($values[0] == $comment->post_id) {
                $comments[] = $comment;
            }
        }
        return $comments;
    }
}
