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
?>

<div id="bit-80">
    <?php foreach( $forums as $forum ): ?>
        <?php $len = count($forum->subforums); ?>
            <div class="head2"><?= $forum['title']; ?></div>
            <?php for ($i = 0; $i < $len; $i++): ?>
                <div class="catstuff">
                    <ul>
                        <li class="icon"><i class="fa fa-comment icon0"></i></li>
                        <li class="desc">
                            <div class="board_link"><a  href="subforums/view/<?= h($forum->subforums[$i]->id); ?>"><?= h($forum->subforums[$i]->title); ?></a></div>
                            <div class="board_desc">
                                <?= h($forum->subforums[$i]->description); ?>
                            </div>
                        </li>
                        <?php if($forum->subforums[$i]->threads): ?>
                            <li class="posts"> <?= count($forum->subforums[$i]->threads); ?> topics<br> <?= array_reduce($forum->subforums[$i]->threads, "count_posts"); ?> posts</li>
                            <li class="lastpost">
                                <a href="threads/<?= $forum->subforums[$i]->threads[0]->id; ?>-<?= $forum->subforums[$i]->threads[0]->slug; ?>"><?= $forum->subforums[$i]->threads[0]->title; ?></a>
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
    <div class="box">
        <div class="head1">Forum Information</div>
        <div class="box_stuff">
            <ul class="list-forum_information">
                <li>Online users: -</li>
                <li>Roles: <b>Administrator</b> <b>Member</b></li>
                <li>
                    <span> - Members</span>
                    <span> - Threads</span>
                    <span> - Posts</span>
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
                <?php $lastElementKey = $recent_activity->count() - 1 ?>
                <?php foreach( $recent_activity as $key => $threads ): ?>
                    <li>
                        <a href="threads/<?= $threads->id; ?>-<?= $threads->slug; ?>">
                            <?= $this->Text->truncate(h($threads->title), 75, array('ending' => '...', 'exact' => true)); ?>
                        </a>
                        <p>
                            <a href="#">
                                <?= h($threads->user->username); ?>
                            </a>
                            <span class="float-right">
                              <?= $this->Text->truncate($this->Time->timeAgoInWords($threads->lastpost_date), 18, array('ending' => '...', 'exact' => true)); ?>
                            </span>
                        </p>
                    </li>
                    <?php if($key !== $lastElementKey): ?>
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
