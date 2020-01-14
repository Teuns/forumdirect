<?php
use Emojione\Client;

$client = new Client();
$client->ascii = true;
$client->unicodeAlt = true;
?>

<?php foreach($private_chats as $private_chat): ?>
    <li><b style="float: left;" onclick="document.getElementById('text').value = '/whisper ' + this.innerText + ' '; document.getElementById('text').focus()"><?= $private_chat->user->username ?></b>:
        <span style="float: right;">
           <?php echo $private_chat->created->format('H:i') ?>
        </span>
        <p>
            <?= getWhispers($client->toImage($this->Text->autoLink($private_chat->body, array('escape' => true))), $userName) ?>
        </p>
    </li>
<?php endforeach; ?>
