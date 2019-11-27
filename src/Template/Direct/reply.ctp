<h1>Reply Direct</h1>
<?php
echo $this->Form->create($direct);
echo $this->Form->control('body', ['rows' => '8']);
echo $this->Form->button(__('Save Reply'));
echo $this->Form->end();
?>
