<?php
$form = "";
if (isset($editForm)) {
    $form = $editForm;
} elseif (isset($replyForm)) {
    $form = $replyForm;
}
?>

<div class="comments">

    <h4>Skriv en kommentar</h4>
    <?php if ($isLoggedIn) : ?>
        <?= $this->renderView('comment/form', ["method" => "", "submit" => "Skicka", "postid" => $postid, "form" => $newForm, "parent_id" => 0]) ?>
    <?php else : ?>
        <p><a href="<?= $this->url('login') ?>">Logga in</a> för att lämna en kommentar.</p>
    <?php endif; ?>

    <h3>Kommentarer:</h3>
    <p>Sortera på <a href="<?= $this->url("comment/$postid?sort=best") ?>">bästa</a> |
                  <a href="<?= $this->url("comment/$postid?sort=old") ?>">äldsta</a> |
                  <a href="<?= $this->url("comment/$postid?sort=new") ?>">nyaste</a></p>

    <?= $this->renderView("comment/comment-tree", ["comments" => $comments, "textfilter" => $textfilter, "postid" => $postid, "action" => $action, "actionID" => $actionID, "form" => $form, "isLoggedIn" => $isLoggedIn]) ?>
</div>
