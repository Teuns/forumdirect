<?php $this->assign('title', h($thread->title)); ?>
<?php
    $this->Breadcrumbs->add([
        ['title' => h($thread->title)]
    ]);
?>
<div class="box">
    <div class="head1"><?= h($thread->title) ?></div>
    <div class="box_stuff">
        <?php if($currPage == 0): ?>
            <div class="comment initial">
                <div class="comment-avatar-container">
                    <h2 class="comment-author"><?= h($thread->user->username) ?></h2>
                    <img class="comment-avatar" src="<?= $thread->user->avatar ?>" />
                    <span class="comment-role"><?= $thread->user->role ?></span>
                </div>
                <?php $this->Markdown->Parsedown->setMarkupEscaped(true); ?>
                <div class="comment-text"><span class="float-right"><a href="#">delete</a> <a href="javascript:openModal()">report</a></span><?= preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="/hashtag/$1">#$1</a>', $this->Markdown->parse($thread->body)); ?></div>
                <p class="comment-time-stamp"><?= $thread->created->i18nFormat('MMM dd, yyyy h:mm:ss a') ?> <?php if($thread->modified): ?> modified at <?= $thread->modified->i18nFormat('MMM dd, yyyy h:mm:ss a') ?> <?php endif; ?> <?php if($loggedIn && !$thread->closed && $userId == $thread->user_id || $loggedIn && $role == 'admin' || $loggedIn && $role == 'mod'): ?> <?= $this->Html->link('Edit', ['action' => 'edit', $thread->id]) ?> <?php endif; ?></p>
            </div>
        <?php endif; ?>

        <?php foreach($posts as $post): ?>
            <div class="comment initial" id="pid<?= $post->id ?>">
                <div class="comment-avatar-container">
                    <h2 class="comment-author"><?= h($post->user->username) ?></h2>
                    <img class="comment-avatar" src="<?= $post->user->avatar ?>" />
                    <span class="comment-role"><?= $post->user->role ?></span>
                </div>
                <?php $this->Markdown->Parsedown->setMarkupEscaped(true); ?>
                <div class="comment-text"><span class="float-right"><a href="#">delete</a> <a href="javascript:openModal()">report</a></span><?= preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="/hashtag/$1">#$1</a>', $this->Markdown->parse($post->body)); ?></div>
                <p class="comment-time-stamp"><?= $post->created->i18nFormat('MMM dd, yyyy h:mm:ss a') ?> <?php if($post->modified): ?> modified at <?= $post->modified->i18nFormat('MMM dd, yyyy h:mm:ss a') ?> <?php endif; ?> <?php if($loggedIn && !$thread->closed && $userId == $post->user_id || $loggedIn && $role == 'admin' || $loggedIn && $role == 'mod'): ?> <?= $this->Html->link('Edit', ['controller' => 'Posts', 'action' => 'edit', $post->id]) ?> <?php endif; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>Modal Header</h2>
        </div>
        <div class="modal-body">
            <p>Some text in the Modal Body</p>
            <p>Some other text...</p>
        </div>
        <div class="modal-footer">
            <h3>Modal Footer</h3>
        </div>
    </div>
</div>
<div class="pagination">
    <?php
    $this->Paginator->templates([
        'prevActive' => '<a href="{{url}}">{{text}}</a>',
        'prevDisabled' => '<a href="{{url}}">{{text}}</a>',
        'number' => '<a href="{{url}}">{{text}}</a>',
        'current' => '<a class="active" href="{{url}}">{{text}}</a>',
        'nextActive' => '<a href="{{url}}">{{text}}</a>',
        'nextDisabled' => '<a href="{{url}}">{{text}}</a>'
    ]); ?>
    <?= $this->Paginator->prev() ?>
    <?= $this->Paginator->numbers() ?>
    <?= $this->Paginator->next() ?>
</div>
<h1>Add Post</h1>
<?php if($loggedIn && !$thread->closed || $loggedIn && $role == 'admin' || $loggedIn && $role == 'mod'): ?>
    <?php if($role == 'admin' || $loggedIn && $role == 'mod'): ?>
        <span><b>Notice:</b> Thread is closed.</span>
    <?php endif; ?>
    <?php
        echo $this->Form->create('post', ['url' => 'posts/add/' . $thread->id . '']);
        echo $this->Form->control('body', ['rows' => '8']);
        echo $this->Form->button(__('Save Post'));
        echo $this->Form->end();
    ?>
<?php elseif($thread->closed): ?>
    <div class="error-msg">This thread has been closed. You can no longer participate in discussions.</div>
<?php else: ?>
    <div class="error-msg">Please <a href="../../users/login?redirect=%2Fthreads%2F<?= $thread->id; ?>-<?= $thread->slug; ?>">log in</a> or <a href="../../users/add">create an account</a> to participate in this discussion.</div>
<?php endif; ?>
