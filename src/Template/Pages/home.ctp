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

use Emojione\Client;

$client = new Client();
$client->ascii = true;
$client->unicodeAlt = true;

function getWhispers($str, $userName){
    if (strpos($str, "/whisper") !== false) {
        $username = explode(' ', explode("/whisper", $str)[1])[1];
        $message = explode('/whisper ' . $username, $str)[1];
        if($message && strlen(trim($message))){
            if ($username !== $userName) {
                $str = "<b>Whisper to " . $username . ": </b>" . $message;
            } else {
                $str = "<b>Whisper: </b>" . $message;
            }
        }
    }

    return $str;
}
?>

<div id="bit-70">
    <?php if($loggedIn && !$this->AuthUser->hasRole('banned')): ?>
        <div class="box">
            <div class="head1">Chatbox</div>
            <div class="box_stuff">
                <div class="chatbox">
                    <div class="channels">
                        <ul id="channels">
                            <li><a href="#" class="active" onclick="toggleChat(this, 'main'); return false;">Main</a></li>
                            <?php foreach($channels as $channel): ?>
                                <li><a href="#" id="channel-<?= $channel->user->username ?>" onclick="toggleChat(this, '<?= $channel->user->username ?>'); return false;"><?= $channel->user->username ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <ul id="chatbox-main" style="display: block;">
                        <?php foreach($chats as $chat): ?>
                            <li><b style="float: left;" onclick="document.getElementById('text').value = '/whisper ' + this.innerText + ' '; document.getElementById('text').focus()"><?= $chat->user->username ?></b>:
                                <span style="float: right;">
                                    <?php echo $chat->created->format('H:i') ?>
                                </span>
                                <p>
                                    <?= $client->toImage($this->Text->autoLink($chat->body, array('escape' => true))) ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php foreach($channels as $channel): ?>
                        <ul id="chatbox-<?= $channel->user->username ?>" style="display: none;">
                            <?= $this->cell('Chat::getPrivateChats', [$userId, $channel->user->id, $userName]); ?>
                        </ul>
                    <?php endforeach; ?>
                </div>
                <div class="card-footer bevelled">
                    <div class="form-group">
                        <input type="text" id="username" class="form-control" value="<?= $userName; ?>" style="display:none">
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
    <?php endif; ?>

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

<div id="bit-30">
    <div class="box">
        <div class="head1">Recent posts</div>
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

<script type="text/javascript">
    function toggleChat(el, channel)
    {
        [].forEach.call(
            document.querySelectorAll('#channels > li .active'),
            function (el) {
                el.classList.remove('active');
            }
        );

        el.classList.add("active");

        el.innerText = el.innerText.split(" (+1)")[0];

        if (channel == "main") {
            document.getElementById('text').value = '';
            var old_element = document.getElementById("text");
            var new_element = old_element.cloneNode(true);
            old_element.parentNode.replaceChild(new_element, old_element);
        } else {
            document.getElementById('text').value = '/whisper ' + channel + ' ';

            document.getElementById('text').addEventListener('click', function () {
                document.getElementById('text').value = '/whisper ' + channel + ' ';
                document.getElementById('text').focus();
            });
        }

        [].forEach.call(
            document.querySelectorAll('.chatbox ul:not(#chatbox-' + channel + ')'),
            function (el) {
                if (el.style.display == "block") {
                    el.style.display = "none";
                }
            }
        );

        var el = document.getElementById("chatbox-" + channel);

        if (el.style.display !== "block") {
            el.style.display = "block";

            el.scrollTop = el.scrollHeight;
        } else {
            el.style.display = "none";
        }
    }
</script>
