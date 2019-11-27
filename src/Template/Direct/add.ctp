<h1>Add Direct Message</h1>
<?php
echo $this->Form->create($direct);
echo $this->Form->control('title');
echo $this->Form->control('to_username', ['label' => 'To Username']);
echo $this->Form->control('body', ['rows' => '12']);
echo $this->Form->button(__('Save Direct'));
echo $this->Form->end();
?>
