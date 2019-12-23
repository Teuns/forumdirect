<?= $this->Form->create($user) ?>
    <h1><?= __('Add User') ?></h1>
<?= $this->Form->input('username') ?>
<?= $this->Form->input('email') ?>
<?= $this->Form->input('password') ?>
<?= $this->Form->input('confirm_password', ['type' => 'password']) ?>
<?= $this->Form->button(__('Add')); ?>
<?= $this->Form->end() ?>
