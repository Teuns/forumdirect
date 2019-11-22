<?= $this->Flash->render('auth') ?>
<?= $this->Form->create() ?>
    <h1><?= __('Login') ?></h1>
<?= $this->Form->input('username') ?>
<?= $this->Form->input('password') ?>
<p>Nog geen account? <a href="<?= $this->Url->build(["controller" => "Users", "action" => "add"]); ?>">Maak er een aan</a>.</p>
<?= $this->Form->button(__('Inloggen')); ?>
<?= $this->Form->end() ?>
