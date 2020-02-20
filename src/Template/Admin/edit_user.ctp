<?= $this->Form->create($user) ?>
<h1><?= __('Edit Profile') ?></h1>
<?= $this->Form->input('username') ?>
<?php $roles = array_map(function ($role) {
    return array(
        $role->role->id => $role->role->name
    );
}, $roles); ?>
<label for="role">Primary Role</label>
<?= $this->Form->select(
    'primary_role',
    $roles,
    ['value' => $user->roles_users[0]->role['id']]
);
?>
<label for="addRole">Add Role</label>
<?= $this->Form->checkbox('addRole') ?>
<?= $this->Form->input('role', ['value' => '', 'type' => 'text', 'placeholder' => 'Add new role to user']) ?>
<?= $this->Form->button(__('Update')); ?>
<?= $this->Form->end() ?>
