<h1>Add Post</h1>
<?php
echo $this->Form->create($post);
echo $this->Form->control('body', ['rows' => '8']);
echo $this->Form->button(__('Save Post'));
echo $this->Form->end();
?>
