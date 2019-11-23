<?php $this->assign('title', 'Upload avatar'); ?>

<?php
$this->Breadcrumbs->add([
    ['title' => 'Upload avatar']
]);
?>

<?= $this->Form->create($user, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add Avatar') ?></legend>
        <?php
        echo $this->Form->file('image', ['label' =>'']);
        ?>
    </fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
