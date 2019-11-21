<h1>Add Thread</h1>
<?php
echo $this->Form->create($thread);
echo $this->Form->control('title');
echo $this->Form->control('body', ['rows' => '8']);
echo $this->Form->button(__('Save Thread'));
echo $this->Form->end();
?>
