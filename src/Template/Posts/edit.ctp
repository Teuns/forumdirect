<h1>Edit Post</h1>
<?php
echo $this->Form->create($post);
echo $this->Form->control('body', ['rows' => '6']);
echo $this->Form->button(__('Save Post'));
echo $this->Form->end();
?>
