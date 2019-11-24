<?= $this->Form->create($user) ?>
<h1><?= __('Reset Password') ?></h1>
<?= $this->Form->input('password', ['value' => '']) ?>
<?= $this->Form->input('confirm_password', ['type' => 'password']) ?>
<?= $this->Form->button(__('Update')); ?>
<?= $this->Form->end() ?>
