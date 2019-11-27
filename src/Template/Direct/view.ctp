<?php
$this->Breadcrumbs->add([
    ['title' => 'Users']
]);
?>

<div id="bit-20" class="sidebar-left">
    <div class="box">
        <div class="head1">Quick Menu</div>
        <div class="box_stuff">
            <ul class="vertical-menu">
                <li><a href="/users">user cp</a></li>
                <li><a href="/users/edit-avatar">upload avatar</a></li>
                <li><a href="/users/edit-profile">edit profile</a></li>
                <li><a href="#">edit signature</a></li>
                <li><a href="#">manage sessions</a></li>
            </ul>
        </div>
    </div>
    <br />
</div>
<div id="bit-80">
    <div class="box">
        <div class="head1"><?= $direct->title ?></div>
        <div class="box_stuff">
            <div class="comment initial">
                <div class="comment-avatar-container">
                    <h2 class="comment-author"><?= h($direct->user->username); ?></h2>
                    <img class="comment-avatar" src="<?= $direct->user->avatar; ?>">
                </div>
                <div class="comment-text" style="overflow: unset;">
                    <?php $this->Markdown->Parsedown->setMarkupEscaped(true); ?>
                    <?= $this->Markdown->parse($direct->body); ?>
                </div>
                <p class="comment-time-stamp">created at <?= $direct->created; ?></p>
            </div>
        </div>
        <br />
    </div>
    <br />
    <?php foreach ($replies as $reply): ?>
        <div class="box">
            <div class="head1">RE: <?= $direct->title ?></div>
            <div class="box_stuff">
                <div class="comment initial">
                    <div class="comment-avatar-container">
                        <h2 class="comment-author"><?= h($reply->user->username); ?></h2>
                        <img class="comment-avatar" src="<?= $reply->user->avatar; ?>">
                    </div>
                    <div class="comment-text" style="overflow: unset;">
                        <?php $this->Markdown->Parsedown->setMarkupEscaped(true); ?>
                        <?= $this->Markdown->parse($reply->body); ?>
                    </div>
                    <p class="comment-time-stamp">created at <?= $reply->created; ?></p>
                </div>
            </div>
            <br />
        </div>
    <?php endforeach; ?>
    <br />
    <h1>Reply Direct</h1>
    <?php
    echo $this->Form->create(null, ['url' => '../direct/reply/'.$direct->id]);
    echo $this->Form->control('body', ['rows' => '8']);
    echo $this->Form->button(__('Save Reply'));
    echo $this->Form->end();
    ?>
</div>
