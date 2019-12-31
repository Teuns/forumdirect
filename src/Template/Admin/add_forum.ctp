<h1>Add Forum</h1>
<?php
echo $this->Form->create($forum);
echo $this->Form->control('title');
echo $this->Form->button(__('Save Forum'));
echo $this->Form->end();
?>
