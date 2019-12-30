<?php $this->assign('title', 'Forum overview'); ?>

<?php
    $this->Breadcrumbs->add([
        ['title' => 'Forum overview']
    ]);
?>

<?php
function count_posts($count, $item)
{
    $count += $item['posts_total'];
    return $count;
}

function getWhispers($str){
    if (strpos($str, "/whisper") !== false) {
        $username = explode(' ', explode("/whisper", $str)[1])[1];
        $message = explode('/whisper ' . $username, $str)[1];
        if($message && strlen(trim($message))){
            $str = "<b>Whisper to " . $username . ": </b>" . $message;
        }
    }

    return $str;
}
?>

<div id="bit-80">
    <div class="box">
        <div class="head1">Chatbox</div>
        <div class="box_stuff">
            <ul id="chatbox">
                <?php foreach($chats as $chat): ?>
                    <li><b style="float: left;" onclick="document.getElementById('text').value = '/whisper ' + this.innerText + ' '; document.getElementById('text').focus()"><?= $chat->user->username ?></b>:
                        <span style="float: right;">
                            <?php echo $chat->created->format('H:i:s') ?>
                        </span>
                        <p>
                            <?= getWhispers(h($chat->body)) ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="card-footer bevelled">
                <div class="form-group">
                    <input type="text" id="session" class="form-control" value="<?= $this->request->session()->id(); ?>" style="display:none">
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <span id="chatbox-error"></span>
                    <input type="text" id="text" name="text" class="form-control" placeholder="Enter message" autocomplete="off"onkeyup="handleKey(event)" disabled>
                    <input type="button" id="send" name="send" value="Send" onclick="send()" style="display: none">
                </div>
            </div>
        </div>
    </div>
    <br/>

    <?php foreach( $forums as $forum ): ?>
        <?php $len = count($forum->subforums); ?>
            <div class="head2"><?= $forum['title']; ?></div>
            <?php for ($i = 0; $i < $len; $i++): ?>
                <div class="catstuff">
                    <ul>
                        <li class="icon"><i class="fa fa-comment icon0"></i></li>
                        <li class="desc">
                            <div class="board_link"><a  href="subforums/view/<?= h($forum->subforums[$i]->id); ?>/<?= h($forum->subforums[$i]->title); ?>"><?= h($forum->subforums[$i]->title); ?></a></div>
                            <div class="board_desc">
                                <?= h($forum->subforums[$i]->description); ?>
                            </div>
                        </li>
                        <?php if($forum->subforums[$i]->threads): ?>
                            <li class="posts"> <?= count($forum->subforums[$i]->threads); ?> topics<br> <?= array_reduce($forum->subforums[$i]->threads, "count_posts"); ?> posts</li>
                            <li class="lastpost">
                                <a href="threads/<?= $forum->subforums[$i]->threads[0]->id; ?>-<?= $forum->subforums[$i]->threads[0]->slug; ?>"><?= h($forum->subforums[$i]->threads[0]->title); ?></a>
                                <p>by <?= $forum->subforums[$i]->threads[0]->users['username']; ?></p>
                                <p>created at <?= $forum->subforums[$i]->threads[0]->lastpost_date; ?></p>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endfor; ?>

            <?php if (!$len): ?>
                <div class="catstuff"><ul><li class="desc"><span>No subforums in this forum</span></li></ul></div>
            <?php endif; ?>
        <br />
    <?php endforeach; ?>
    <?php if (!$forums->count()): ?>
        <div class="error-msg"><i class="fa fa-times-circle"></i> No forums found. Make one!</div>
    <?php endif; ?>
    <div class="box">
        <div class="head1">Forum Information</div>
        <div class="box_stuff">
            <ul class="list-forum_information">
                <?php $onlineUsersArr = array(); ?>
                <?php foreach($online_users as $user): ?>
                <?php $onlineUsersArr[] = "<a href='#'>".$user->username."</a>"; ?>
                <?php endforeach; ?>
                <?php $onlineUsersLastArr = array_pop($onlineUsersArr);
                $onlineUsers = implode(', ', $onlineUsersArr);
                if ($onlineUsers) {
                    $onlineUsers .= ' and ';
                }
                $onlineUsers .= $onlineUsersLastArr;
                ?>
                <li>Online users (in the last 15 minutes): <?= $onlineUsers ? $onlineUsers : '-' ?></li>
                <li>Roles:
                    <?php $passed = false; ?>
                    <?php foreach ($roles as $role): ?><b><?= ($passed ? ', ' : '') . $role->name ?></b><?php $passed = true; ?><?php endforeach; ?>
                </li>
                <li>
                    <span> <?= $total_users ?> Members</span>
                    <span> <?= $total_threads ?> Threads</span>
                    <span> <?= $total_posts ?> Posts</span>
                </li>
            </ul>
        </div>
    </div>
    <br />
</div>

<div id="bit-20">
    <div class="box">
        <div class="head1">Recent Activity</div>
        <div class="box_stuff">
            <ul class="list-recent_activity">
                <?php $lastElementId = $recent_activity->last()->id ?>
                <?php foreach( $recent_activity as $threads ): ?>
                    <li>
                        <a href="threads/<?= $threads->id; ?>-<?= $threads->slug; ?>?action=lastpost">
                            <?= $this->Text->truncate(h($threads->title), 75, array('ending' => '...', 'exact' => true)); ?>
                        </a>
                        <p>
                            <a href="#">
                                <?= h($threads->users['username']); ?>
                            </a>
                            <span class="float-right">
                              <?= $this->Text->truncate($this->Time->timeAgoInWords($threads->lastpost_date), 18, array('ending' => '...', 'exact' => true)); ?>
                            </span>
                        </p>
                    </li>
                    <?php if($threads->id !== $lastElementId): ?>
                        <hr />
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if ($recent_activity->isEmpty()): ?>
                    <li>No data to display.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
