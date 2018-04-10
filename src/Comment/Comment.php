<?php

namespace Emsa\Comment;

/**
 * A controller for the comment system.
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
class Comment
{



    public function getCommentsForPost($postid, $allComments)
    {
        $postComments = [];

        foreach ($allComments as $comment) {
            if ($comment["post_id"] === $postid) {
                $postComments[$comment["id"]] = $comment;
            }
        }

        return $postComments;
    }



    public function getSortQuery($request)
    {
        $sortBy = $request->getGet("sort");
        $sortRules = ["best", "old", "new"];

        if (in_array($sortBy, $sortRules)) {
            return $sortBy;
        }
    }



    public function buildTree(array &$elements, $sortBy, $parentId = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $sortBy, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id']] = $element;
                unset($elements[$element['id']]);
            }
        }
        $this->sortComments($branch, $sortBy);
        return $branch;
    }



    public function sortComments(array &$comments, $sortBy = "best")
    {
        $sortOrder = SORT_DESC;
        $sortArray = array();
        foreach ($comments as $key => $comment) {
            switch ($sortBy) {
                case 'old':
                    $sortOrder = SORT_ASC;
                    //Intentional fall through
                case 'new':
                    $sortArray[$key] = $comment["created"];
                    break;
                case 'best':
                default:
                    $sortArray[$key] = ($comment['upvote'] - $comment['downvote']);
                    break;
            }
        }
        array_multisort($sortArray, $sortOrder, $comments);
    }



    public function getActionCommentDetails($request)
    {
        $deleteid = $request->getGet("deleteid");
        $editid = $request->getGet("editid");
        $replyid = $request->getGet("replyid");

        switch (true) {
            case $deleteid:
                $actionCommentId = (int)$deleteid;
                $actionCommentMethod = "delete";
                break;
            case $editid:
                $actionCommentId = (int)$editid;
                $actionCommentMethod = "edit";
                break;
            case $replyid:
                $actionCommentId = (int)$replyid;
                $actionCommentMethod = "post";
                break;
            default:
                $actionCommentId = "";
                $actionCommentMethod = "";
                break;
        }
        return [
            "actionCommentId" => $actionCommentId,
            "actionCommentMethod" => $actionCommentMethod
        ];
    }



    public function buildCommentSection($comments, $textfilter, $baseUrl, $actionCommentId, $actionCommentMethod)
    {
        $commentSection = "";
        foreach ($comments as $comment) {
            $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($comment["email"]))) . '.jpg?d=identicon&s=40';

            $points = ((int)$comment["upvote"] - (int)$comment["downvote"]);
            $created = $this->timeElapsedString($comment['created']);
            $edited = $comment["edited"] !== ""
                ? ", redigerad " . $this->timeElapsedString($comment['edited'])
                : ""
            ;

            $textRegion = $this->buildTextRegion($textfilter, $comment, $actionCommentId, $actionCommentMethod, $baseUrl);

            $replyLink = "<a href='$baseUrl?replyid={$comment["id"]}#{$comment["id"]}'>svara</a>";
            $editLink = "<a href='$baseUrl?editid={$comment["id"]}#{$comment["id"]}'>redigera</a>";
            $deleteLink = "<a href='$baseUrl?deleteid={$comment["id"]}#{$comment["id"]}'>radera</a>";
            $actions = $comment["deleted"] == 1
                ? "$replyLink"
                : "$replyLink | $editLink | $deleteLink";

            $actionBox = "";
            if ($actionCommentId == $comment["id"] && $actionCommentMethod == "post") {
                $actionBox .= $this->buildNewCommentBox($baseUrl, $comment["post_id"], $comment["id"]);
            } else if ($actionCommentId == $comment["id"] && $actionCommentMethod == "delete") {
                $actionBox .= $this->buildDelConfirmDialog($baseUrl, $comment["id"]);
            }

            $children = isset($comment["children"])
                ? $this->buildCommentSection($comment["children"], $textfilter, $baseUrl, $actionCommentId, $actionCommentMethod)
                : "";

            $commentSection .= "<div class='entry'><a name='{$comment["id"]}'></a>\n";
            $commentSection .= $this->buildVoteButtons($baseUrl, $comment["id"]);
            $commentSection .= "<img src='$gravatar'>";
            $commentSection .= "<div class='stats'>$points poäng | av {$comment['email']} tillagd $created$edited</div>\n";
            $commentSection .= $textRegion;
            $commentSection .= "<div class='actions'>$actions</div>\n";
            $commentSection .= $actionBox;
            $commentSection .= "<div class='children'>\n$children</div>\n";
            $commentSection .= "</div>\n";
        }
        return $commentSection;
    }



    public function buildTextRegion($textfilter, $comment, $actionCommentId, $actionCommentMethod, $baseUrl)
    {
        $text = $textfilter->parse($comment["text"], ["htmlentities", "markdown"]);
        $textRegion = "<div class='text'>{$text->text}</div>\n";
        if ($comment["deleted"] == 1) {
            $textRegion = "<div class='text'><p><i>raderad</i></p></div>\n";
        } else if ($actionCommentId == $comment["id"] && $actionCommentMethod == "edit") {
            $textRegion = $this->buildEditCommentBox($baseUrl, $comment["id"], $comment["text"]);
        }
        return $textRegion;
    }



    public function buildNewCommentBox($baseUrl, $postid, $parentid = 0)
    {
        return <<<EOD
        <form method="post" action="$baseUrl/post">
            <input type="hidden" name="post_id" value="$postid">
            <input type="hidden" name="parent_id" value="$parentid">
            <label for="email">Mejladress</label>
            <input type="email" name="email" id="email" required>
            <br>
            <textarea name="text" rows="6" cols="60" required></textarea>
            <br>
            <input type="submit" value="Skicka kommentar">
        </form>
EOD;
    }



    public function buildEditCommentBox($baseUrl, $id, $text)
    {
        //Note: $text does not seem to need to be sanitized since it is inside a textarea field
        return <<<EOD
        <form method="post" action="$baseUrl/edit">
            <input type='hidden' name='id' value='$id'>
            <textarea name="text" rows="6" cols="60" required>$text</textarea>
            <br>
            <input type="submit" value="Spara kommentar">
        </form>
EOD;
    }



    public function buildDelConfirmDialog($baseUrl, $id)
    {
        return <<<EOD
        <form class='delConfirm' method="post" action="$baseUrl/delete">
            <input type='hidden' name='id' value='$id'>
            Är du säker på att du vill radera denna kommentar?
            <br>
            <input class='delButton' type="submit" name="delete" value="Ja">
            <input type="submit" name="cancel" value="Nej">
        </form>
EOD;
    }



    public function buildVoteButtons($baseUrl, $id)
    {
        return <<<EOD
        <form class='vote-buttons' method="post" action="$baseUrl/vote">
            <input type='hidden' name='id' value='$id'>
            <input type="submit" name="upvote" value="&uarr;"><br>
            <input type="submit" name="downvote" value="&darr;">
        </form>
EOD;
    }



    public function compileNewComment($entry)
    {
        if (!filter_var($entry["email"], FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $entry["post_id"] = (int)$entry["post_id"];
        $entry["parent_id"] = (int)$entry["parent_id"];
        $entry["created"] = date("Y-m-d H:i:s");
        $entry["edited"] = "";
        $entry["upvote"] = 0;
        $entry["downvote"] = 0;
        $entry["deleted"] = 0;
        return $entry;
    }



    public function timeElapsedString($datetime, $full = false)
    {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7; //??????

        $string = array(
            'y' => ['år', 'år'],
            'm' => ['månad', 'månader'],
            'w' => ['vecka', 'veckor'],
            'd' => ['dag', 'dagar'],
            'h' => ['timme', 'timmar'],
            'i' => ['minut', 'minuter'],
            's' => ['sekund', 'sekunder'],
        );

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $singPlur = $diff->$k > 1 ? 1 : 0;
                $v = $diff->$k . ' ' . $v[$singPlur];
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string) . ' sedan' : 'nyligen';
    }
}
