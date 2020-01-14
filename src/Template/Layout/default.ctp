<?php use Cake\Core\Configure; ?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= Configure::read('App.name'); ?> - <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('style.css?md5='.md5_file('css/style.css')) ?>
    <?= $this->Html->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css') ?>

    <?= $this->Html->script('app.js') ?>
    <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/jQuery-linkify/2.1.7/linkify.min.js') ?>
    <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/jQuery-linkify/2.1.7/linkify-html.min.js') ?>
    <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/emojione/2.2.7/lib/js/emojione.min.js') ?>
    <?= $this->Html->script('chatclient.js?md5='.md5_file('js/chatclient.js')) ?>
    <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <div id="main_header">
        <div id="main_top" class="wrap">
            <div id="sglogo"><?= Configure::read('App.name'); ?></div>
            <div id="sgnav">
                <ul>
                    <li><a href="<?= $this->Url->build('/'); ?>">Forums</a></li>
                    <li><a href="#">Search</a></li>
                    <li><?php if($loggedIn): ?><?= $this->Form->postLink(__('Logout'), ['controller' => 'users', 'action' => 'logout'], ['confirm' => __('Are you sure you want to logout?')]) ?><?php else: ?><a href="<?= $this->Url->build(["controller" => "Users", "action" => "login"]); ?>">Login</a><?php endif; ?></li>
                </ul>
            </div>
            <div id="accent"></div>
        </div>
        <div id="main_nav" class="wrap">
            <div id="main_links">
                <ul>
                    <li><a href="<?= $this->Url->build('/'); ?>">Home</a></li>
                    <?php if ($this->AuthUser->hasRole('admin')): ?><li><a href="<?= $this->Url->build(["controller" => "Admin", "action" => "index"]); ?>">Admin</a></li><?php endif; ?>
                    <?php if ($this->AuthUser->hasRole('mod') || $this->AuthUser->hasRole('admin')): ?><li><a href="<?= $this->Url->build(["controller" => "Mod", "action" => "index"]); ?>">Mod</a></li><?php endif; ?>
                    <li>
                        <div class="dropdown">
                            <a href="#">
                                <?php if(isset($userName)): ?>
                                    <?= h($userName); ?>
                                <?php endif; ?>
                            </a>
                            <div class="dropdown-content">
                                <a href="<?= $this->Url->build(["controller" => "Users", "action" => "index"]); ?>">UCP</a>
                                <a href="<?= $this->Url->build(["controller" => "Users", "action" => "editAvatar"]); ?>">Upload Avatar</a>
                                <a href="#">Change Signature</a>
                                <?= $this->Form->postLink(__('Logout'), ['controller' => 'users', 'action' => 'logout'], ['confirm' => __('Are you sure you want to logout?')]) ?>
                            </div>
                        </div>
                    </li>
                    <?php if($loggedIn): ?><li><a href="<?= $this->Url->build(["controller" => "Direct", "action" => "inbox"]); ?>">Messages</a></li><?php endif; ?>
                </ul>
            </div>er
        </div>

        <div id="main_crumbs" class="wrap">
            <?php
                $this->Breadcrumbs->prepend(
                    'Home',
                    '/'
                );

                echo $this->Breadcrumbs->render(
                    ['class' => 'breadcrumb']
                );
            ?>
        </div>
    </div>
    <div id="main_content" class="wrap">
        <?= $this->Flash->render() ?>
        <?php if(isset($verified) && !$verified): ?>
            <div class="warning-msg"><i class="fa fa-times-circle"></i> Please verify your account. Contact us if you haven't received the email.</div>
        <?php endif; ?>
        <?php if(!is_null($reports) && $reports->count() && $this->AuthUser->hasRole('mod')): ?>
            <div class="warning-msg"><i class="fa fa-times-circle"></i> There are reports to be reviewed. Check it in the mod section.</div>
        <?php endif; ?>
        <?php if(!is_null($direct_messages) && $direct_messages->count()): ?>
            <div class="warning-msg"><i class="fa fa-times-circle"></i> You have received a DM named '<?= $direct_messages->last()->title ?>' from <?= $direct_messages->last()->user->username ?>, click <a href="/direct/view/<?= $direct_messages->last()->direct_id ?>"> here to view it</a></div>
        <?php endif; ?>
        <?= $this->fetch('content') ?>
    </div>
    <div style="clear:both;"></div>
    <div class="footer"><div class="wrap">Copyright &copy; ForumDirect. All rights reserved.</div></div>

    <script type="text/javascript">
        $(document).ready(function(){
            connect();
        });
    </script>
</body>
</html>
