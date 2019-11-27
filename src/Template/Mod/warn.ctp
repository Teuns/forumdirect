<h1>Warn User</h1>
<?php
echo $this->Form->create($warning);
echo $this->Form->control('to_username', ['label' => 'To Username']);
echo $this->Form->control('reason', ['type' => 'textarea']);
echo $this->Form->control('percentage', ['label' => 'Percentage']);
echo $this->Form->input('valid_until', ['type' => 'datetime']);
echo $this->Form->button(__('Give Warning'));
echo $this->Form->end();
?>
