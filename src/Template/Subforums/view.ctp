<div id="bit-80">
    <?php $len = count($subforum->threads); ?>
    <div class="head2"><?= $subforum['title']; ?> <?php if($loggedIn): ?><?= $this->Html->link('Add Thread', ['controller' => 'Threads', 'action' => 'add', $subforum->id], ['class' => 'float-right']) ?><?php endif; ?></div>
        <div class="catstuff">
            <?php for ($i = 0; $i < $len; $i++): ?>
            <ul>
                <li class="icon"><i class="fa fa-comment icon0"></i></li>
                <li class="desc">
                    <div class="board_link"><a  href="../../threads/<?= h($subforum->threads[$i]->id); ?>-<?= h($subforum->threads[$i]->slug); ?>"><?= h($subforum->threads[$i]->title); ?></a></div>
                    <div class="board_desc">
                        door: <?= $subforum->threads[$i]->users['username']; ?>
                    </div>
                </li>
                <li class="posts"> <?= count($subforum->threads); ?> topics<br><?= $subforum->threads[$i]->posts_total; ?> posts</li>
                <li class="lastpost">
                    <a href="threads/<?= $subforum->threads[0]->id; ?>-<?= $subforum->threads[0]->slug; ?>"><?= $subforum->threads[0]->title; ?></a>
                    <p>by <?= $subforum->threads[0]->users['username']; ?></p>
                    <p>created at <?= $subforum->threads[0]->lastpost_date; ?></p>
                </li>
            </ul>
            <?php endfor; ?>

            <?php if (!$len): ?>
                <div class="catstuff"><ul><li class="desc"><span>No threads in this subforum</span></li></ul></div>
            <?php endif; ?>
        </div>
    <br />
</div>

<div id="bit-20">
    <div class="box">
        <div class="head1">Recent Activity</div>
        <div class="box_stuff">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vel lorem vitae lacus pretium pretium. Integer cursus, magna eu ornare hendrerit, risus dolor maximus nulla, id sollicitudin enim tellus vitae purus. Proin volutpat leo in tempor aliquam. Mauris congue consequat purus vel condimentum. Donec sagittis risus eget ligula viverra, eu lacinia odio pulvinar. Ut in molestie purus. Integer vel diam rutrum, vulputate tortor nec, auctor ipsum. Suspendisse eleifend, turpis nec aliquam condimentum, augue tellus dapibus sapien, id tristique urna ex eget urna. Mauris vestibulum quam eleifend, tincidunt velit volutpat, imperdiet massa. Proin felis nunc, ornare vel convallis id, tempor sit amet nulla. Aenean auctor aliquet vestibulum. Nulla vehicula felis vel metus mattis fermentum. Maecenas eget egestas ligula, id condimentum nisl. Nulla vel suscipit quam.
        </div>
    </div>
</div>
