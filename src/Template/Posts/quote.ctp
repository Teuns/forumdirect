<h1>Quote Post</h1>
<?php
echo $this->Form->create($post_data);
echo $this->Form->control('body', ['rows' => '8']);
echo $this->Form->button(__('Quote Post'));
echo $this->Form->end();
?>

<script>
    $('#body').val(function() {
    return $('#body').val().split('\n').map(function(line) {
        return '> '+line;
    }).join('\n') + '\n\n';
});
</script>
