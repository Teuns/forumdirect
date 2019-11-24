<?= $this->Form->create($user) ?>
<h1><?= __('Reset Password Request') ?></h1>
<?= $this->Form->input('email') ?>
<?= $this->Form->button(__('Request Reset Link')); ?>
<?= $this->Form->end() ?>
