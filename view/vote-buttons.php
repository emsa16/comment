<?php
$actionUrl = $this->url("comment/{$comment->post_id}/vote?id={$comment->id}");
?>

<form class='vote-buttons' method="post" action="<?= $actionUrl ?>">
    <input type="submit" name="upvote" value="&uarr;"><br>
    <input type="submit" name="downvote" value="&darr;">
</form>
