<h1>Quote Thread</h1>
<?php
echo $this->Form->create($thread_data);
echo $this->Form->control('body', ['rows' => '8']);
echo $this->Form->button(__('Quote Thread'));
echo $this->Form->end();
?>

<script>
    $('#body').val(function() {
        return $('#body').val().split('\n').map(function(line) {
            return '> '+line;
        }).join('\n') + '\n\n';
    });
</script>
