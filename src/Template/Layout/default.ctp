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

    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css') ?>

    <?= $this->Html->script('app.js') ?>

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
                    <?php if(isset($role) && $role == 'admin'): ?><li><a href="#">Admin</a></li><?php endif; ?>
                    <li>
                        <div class="dropdown">
                            <a href="#">
                                <?php if(isset($username)): ?>
                                    <?= h($username); ?>
                                <?php endif; ?>
                            </a>
                            <div class="dropdown-content">
                                <a href="#">UCP</a>
                                <a href="#">Upload Avatar</a>
                                <a href="#">Change Signature</a>
                                <a href="#">Logout</a>
                            </div>
                        </div>
                    </li>
                    <?php if($loggedIn): ?><li><a href="#">Messages</a></li><?php endif; ?>
                </ul>
            </div>
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
        <?= $this->fetch('content') ?>
    </div>
    <div style="clear:both;"></div>
    <div class="footer"><div class="wrap">Copyright &copy; ForumDirect. All rights reserved.</div></div>
</body>
</html>
