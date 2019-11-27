<?php
$this->Breadcrumbs->add([
    ['title' => 'Inbox']
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
                <li><a href="<?= $this->Url->build(["controller" => "Direct", "action" => "inbox"]); ?>">Inbox</a></li>
                <li><a href="<?= $this->Url->build(["controller" => "Direct", "action" => "outbox"]); ?>">Outbox</a></li>
                <li><a href="#">edit signature</a></li>
                <li><a href="#">manage sessions</a></li>
            </ul>
        </div>
    </div>
    <br />
</div>
<div id="bit-80">
    <div class="box">
        <div class="head1">Inbox</div>
        <div class="box_stuff">
            <div style="overflow-x:auto;">
                <table id="direct_messages">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Created</th>
                        <th>Author</th>
                    </tr>
                    <?php foreach($directMessages as $direct): ?>
                        <tr>
                            <td><?= $direct->id ?></td>
                            <td><a href="../direct/view/<?= $direct->direct_id ?>"><?= h($direct->title) ?></a></td>
                            <td><?= $direct->created ?></td>
                            <td><?= $direct->user->username ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if($directMessages->isEmpty()): ?>
                        <tr>
                            <td colspan="4">There are no direct messages to you found.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
