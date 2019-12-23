<?= $this->Flash->render('auth') ?>
<?= $this->Form->create() ?>
    <h1><?= __('Login') ?></h1>
<?= $this->Form->input('username') ?>
<?= $this->Form->input('password') ?>
<p>Not a member? <a href="<?= $this->Url->build(["controller" => "Users", "action" => "add"]); ?>">Sign up!</a></p>
<p>Forgot Password? <a href="<?= $this->Url->build(["controller" => "Users", "action" => "password"]); ?>">Click here.</a></p>
<?= $this->Form->button(__('Log in')); ?>
<?= $this->Form->end() ?>
