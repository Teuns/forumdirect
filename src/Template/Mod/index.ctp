<?php
$this->Breadcrumbs->add([
    ['title' => 'Mod Panel']
]);
?>

<div id="bit-20" class="sidebar-left">
    <div class="box">
        <div class="head1">Quick Menu</div>
        <div class="box_stuff">
            <ul class="vertical-menu">
                <li><a href="/mod">mod cp</a></li>
                <li><a href="/mod/warn">warn user</a></li>
            </ul>
        </div>
    </div>
    <br />
</div>
<div id="bit-80">
    <div class="box">
        <div class="head1">Reports</div>
        <div class="box_stuff">
            <div style="overflow-x:auto;">
                <table id="reports">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Reason</th>
                        <th>Type</th>
                        <th>ID</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach($reports as $report): ?>
                        <tr>
                            <td><?= $report->id ?></td>
                            <td><a href="#"><?= $report->user->username ?></a></td>
                            <td><?= $report->reason ?></td>
                            <td><?= $report->type ?></td>
                            <td><?= $report->to_id ?></td>
                            <td><a href="#">go to <?= $report->type ?></a> | <?= $this->Form->postLink(__('delete'), ['controller' => 'reports', 'action' => 'delete', $report->id], ['confirm' => __('Are you sure you want to delete this report?')]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if($reports->isEmpty()): ?>
                        <td colspan="6">There are no reports to be reviewed.</td>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
    <br />
    <div class="box">
        <div class="head1">Users</div>
        <div class="box_stuff">
            <div style="overflow-x:auto;">
                <table id="users">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Created</th>
                        <th>Modified</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td><?= $user->id ?></td>
                            <td><a href="#"><?= $user->username ?></a></td>
                            <td><?= $user->created ?></td>
                            <td><?= $user->modified ?></td>
                            <td><a href="/mod/ban/<?= $user->id ?>">ban user</a> | <a href="/mod/unban/<?= $user->id ?>">un-ban user</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
    <br />
    <div class="box">
        <div class="head1">Threads</div>
        <div class="box_stuff">
            <div style="overflow-x:auto;">
                <table id="threads">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Created</th>
                        <th>Modified</th>
                    </tr>
                    <?php foreach($threads as $thread): ?>
                        <tr>
                            <td><?= $thread->id ?></td>
                            <td><a href="#"><?= $thread->title ?></a></td>
                            <td><?= $thread->user->username ?></td>
                            <td><?= $thread->created ?></td>
                            <td><?= $thread->modified ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
    <br />
    <div class="box">
        <div class="head1">Posts</div>
        <div class="box_stuff">
            <div style="overflow-x:auto;">
                <table id="posts">
                    <tr>
                        <th>#</th>
                        <th>Author</th>
                        <th>Created</th>
                        <th>Modified</th>
                    </tr>
                    <?php foreach($posts as $post): ?>
                        <tr>
                            <td><?= $post->id ?></td>
                            <td><a href="#"><?= $post->user->username ?></a></td>
                            <td><?= $post->created ?></td>
                            <td><?= $post->modified ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
