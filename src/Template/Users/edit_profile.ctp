<?php
$this->Breadcrumbs->add([
    ['title' => 'Edit Profile']
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
    <?= $this->Form->create($user) ?>
        <h1><?= __('Edit Profile') ?></h1>
    <?= $this->Form->input('username') ?>
    <?php $roles = array_combine(array_keys($this->AuthUser->roles()), array_values($this->AuthUser->roles())); ?>
    <label for="role">Primary Role</label>
    <?= $this->Form->select(
        'primary_role',
        array_flip($roles),
        ['value' => $user->roles_users[0]->role['id']]
    );
    ?>
    <?= $this->Form->input('password', ['value' => '']) ?>
    <?= $this->Form->input('confirm_password', ['type' => 'password']) ?>
    <?= $this->Form->button(__('Update')); ?>
    <?= $this->Form->end() ?>
</div>
