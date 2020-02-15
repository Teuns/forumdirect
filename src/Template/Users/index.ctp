<?php
$this->Breadcrumbs->add([
    ['title' => 'Users']
]);
?>

<div id="bit-30" class="sidebar-left">
    <div class="box">
        <div class="head1">Quick Menu</div>
        <div class="box_stuff">
            <ul class="vertical-menu">
                <li><a href="/users">user cp</a></li>
                <li><a href="/users/edit-avatar">upload avatar</a></li>
                <li><a href="/users/edit-profile">edit profile</a></li>
                <li><a href="<?= $this->Url->build(["controller" => "Direct", "action" => "inbox"]); ?>">Inbox</a></li>
                <li><a href="<?= $this->Url->build(["controller" => "Direct", "action" => "outbox"]); ?>">Outbox</a></li>
                <li><a href="#">edit signature</a></li>
                <li><a href="#">manage sessions</a></li>
            </ul>
        </div>
    </div>
    <br />
</div>
<div id="bit-70">
    <div class="box">
        <div class="head1">User Information</div>
        <div class="box_stuff">
            <div class="comment initial">
                <div class="comment-avatar-container">
                    <h2 class="comment-author"><?= h($user['username']); ?></h2>
                    <img class="comment-avatar" src="<?= $user['avatar']; ?>">
                    <span class="comment-role"><?= $user->roles_users[0]->role['name'] ?></span>
                </div>
                <div class="comment-text" style="overflow: unset;">
                    <ul>
                        <li>Posts: <?= $total_posts; ?></li>
                        <li>Threads: <?= $total_threads; ?></li>
                        <li>Likes: 0</li>
                    </ul>
                </div>
                <p class="comment-time-stamp">registered at <?= $user['created']; ?></p>
            </div>
        </div>
    </div>
    <br />
    <div class="box">
        <div class="head1">Last Threads</div>
        <div class="box_stuff">
            <div style="overflow-x:auto;">
                <table id="last_threads">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Created</th>
                        <th>Last post at</th>
                    </tr>
                    <?php foreach($last_threads as $thread): ?>
                        <tr>
                            <td><?= $thread->id ?></td>
                            <td><a href="../../threads/<?= $thread->id ?>-<?= $thread->slug ?>"><?= h($thread->title) ?></a></td>
                            <td><?= $thread->created ?></td>
                            <td><?= $thread->lastpost_date ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if($last_threads->isEmpty()): ?>
                        <tr>
                            <td colspan="4">There are no last threads from you found. Make one!</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
