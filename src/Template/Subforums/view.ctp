<?php $this->assign('title', $subforum->title); ?>
<?php
    $this->Breadcrumbs->add([
        ['title' => $subforum->title]
    ]);
?>
<div id="bit-70">
    <?php $len = count($subforum->threads); ?>
    <div class="head2"><?= $subforum['title']; ?> <?php if($loggedIn): ?><?= $this->Html->link('Add Thread', ['controller' => 'Threads', 'action' => 'add', $subforum->id, $subforum->title], ['class' => 'float-right button']) ?><?php endif; ?></div>
        <div class="catstuff">
            <?php for ($i = 0; $i < $len; $i++): ?>
            <ul>
                <li class="icon"><i class="fa fa-comment icon0"></i></li>
                <li class="desc">
                    <div class="board_link"><a  href="../../../threads/<?= h($subforum->threads[$i]->id); ?>-<?= h($subforum->threads[$i]->slug); ?>"><?= h($subforum->threads[$i]->title); ?></a></div>
                    <div class="board_desc">
                        by <?= $subforum->threads[$i]->users['username']; ?>
                    </div>
                </li>
                <li class="posts"> <?= $subforum->threads[$i]->views; ?> views<br><?= $subforum->threads[$i]->posts_total; ?> posts</li>
                <li class="lastpost">
                    <a href="../../../threads/<?= $subforum->threads[$i]->id; ?>-<?= $subforum->threads[$i]->slug; ?>?action=lastpost"><?= h($subforum->threads[$i]->title); ?></a>
                    <p>by <?= $subforum->threads[$i]->users['username']; ?></p>
                    <p>created at <?= $subforum->threads[$i]->lastpost_date; ?></p>
                </li>
            </ul>
            <?php endfor; ?>

            <?php if (!$len): ?>
                <div class="catstuff"><ul><li class="desc"><span>No threads in this subforum</span></li></ul></div>
            <?php endif; ?>
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
                        <a href="../../../threads/<?= $threads->id; ?>-<?= $threads->slug; ?>?action=lastpost">
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
