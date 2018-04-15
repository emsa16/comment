<?php
$actionUrl = $this->url("comment/{$comment->post_id}/$method");
?>

<form class='delConfirm' method="post" action="<?= $actionUrl ?>">
    <input type='hidden' name='id' value='<?= $comment->id ?>'>
    Är du säker på att du vill radera denna kommentar?
    <br>
    <input class='delButton' type="submit" name="delete" value="Ja">
    <input type="submit" name="cancel" value="Nej">
</form>
