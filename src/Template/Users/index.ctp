<div id="bit-20" style="padding-left: 0; padding-right: 20px;">
    <div class="box">
        <div class="head1">Quick menu</div>
        <div class="box_stuff">
            aaa
        </div>
    </div>
</div>
<div id="bit-80">
    <div class="box">
        <div class="head1">User Information</div>
        <div class="box_stuff">
            <div class="comment initial">
                <div class="comment-avatar-container">
                    <h2 class="comment-author"><?= h($user['username']); ?></h2>
                    <img class="comment-avatar" src="<?= $user['avatar']; ?>">
                    <span class="comment-role"><?= $user['role']; ?></span>
                </div>
                <div class="comment-text" style="overflow: unset;">
                    <ul>
                        <li>Posts: 0</li>
                        <li>Threads: 0</li>
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
            fdfdsfdsf
        </div>
    </div>
</div>
