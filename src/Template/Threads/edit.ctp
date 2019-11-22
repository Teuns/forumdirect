<h1>Edit Thread</h1>
<?php
echo $this->Form->create($thread);
echo $this->Form->control('title');
echo $this->Form->control('body', ['rows' => '12']);
echo $this->Form->button(__('Save Thread'));
echo $this->Form->end();
?>
