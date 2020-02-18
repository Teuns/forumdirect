<h1>Admin</h1>

<a href="/admin/auth">Auth page</a>

<br/>
<br/>

<div class="box">
    <div class="head2">Users</div>
    <div class="box_stuff">
        <div style="overflow-x:auto;">
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Primary role</th>
                    <th>Verified</th>
                    <th>Action</th>
                </tr>
                <?php foreach($users as $user): ?>
                    <tr>
                        <td><?= $user->id ?></td>
                        <td><?= $user->username ?></td>
                        <td><?= $user->email ?></td>
                        <td><?= $user->primary_role ?></td>
                        <td><?= $user->verified ? 'yes' : 'no' ?></td>
                        <td><a href="/admin/users/edit/<?= $user->id ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

<br/>

<div class="box">
    <div class="head2">Forums <?= $this->Html->link('Add Forum', ['controller' => 'Admin', 'action' => 'addForum'], ['class' => 'float-right button']) ?></div>
    <div class="box_stuff">
        <div style="overflow-x:auto;">
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Action</th>
                </tr>
                <?php foreach($forums as $forum): ?>
                    <tr>
                        <td><?= $forum->id ?></td>
                        <td><?= $forum->title ?></td>
                        <td><a href="/admin/forums/edit/<?= $forum->id ?>">Edit</a> | <a href="/admin/forums/delete/<?= $forum->id ?>" onclick="return confirm('Are you sure you want to perform this action?')">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

<br/>

<div class="box">
    <div class="head2">Subforums <?= $this->Html->link('Add Subforum', ['controller' => 'Admin', 'action' => 'addSubforum'], ['class' => 'float-right button']) ?></div>
    <div class="box_stuff">
        <div style="overflow-x:auto;">
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Forum</th>
                    <th>Action</th>
                </tr>
                <?php foreach($subforums as $subforum): ?>
                    <tr>
                        <td><?= $subforum->id ?></td>
                        <td><?= $subforum->title ?></td>
                        <td><?= $subforum->forum_title ?></td>
                        <td><a href="/admin/subforums/edit/<?= $subforum->id ?>">Edit</a> | <a href="/admin/subforums/delete/<?= $subforum->id ?>" onclick="return confirm('Are you sure you want to perform this action?')">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
