<h1>Edit Subforum</h1>
<?php
echo $this->Form->create($subforum);
echo $this->Form->control('title');
echo "<label>Forum Id</label>";
echo $this->Form->select('forum_id', $forums, ['value' => $subforum->forum_id]);
echo $this->Form->control('description');
echo $this->Form->button(__('Save Subforum'));
echo $this->Form->end();
?>
