<?php
/**
 * @var \App\View\AppView $this
 * @var \TinyAuthBackend\Model\Entity\AclRule[]|\Cake\Collection\CollectionInterface $aclRules
 */
?>
<?php
$this->Paginator->templates([
    'prevActive' => '<a href="{{url}}">{{text}}</a>',
    'prevDisabled' => '<a href="{{url}}">{{text}}</a>',
    'number' => '<a href="{{url}}">{{text}}</a>',
    'current' => '<a class="active" href="{{url}}">{{text}}</a>',
    'nextActive' => '<a href="{{url}}">{{text}}</a>',
    'nextDisabled' => '<a href="{{url}}">{{text}}</a>',
    'first' => '<a href="{{url}}">{{text}}</a>',
    'last' => '<a href="{{url}}">{{text}}</a>'
]); ?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Tiny Auth Acl Rule'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="aclRules index large-9 medium-8 columns content">
	<h1>Authorization Backend</h1>

    <h2><?= __('Tiny Auth Acl Rules') ?></h2>
    <table class="table">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('role') ?></th>
                <th scope="col"><?= $this->Paginator->sort('path') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($aclRules as $aclRule): ?>
            <tr>
                <td><?= $this->Number->format($aclRule->id) ?></td>
                <td><?= $this->Number->format($aclRule->type) ?></td>
                <td><?= h($aclRule->role) ?></td>
                <td><?= h($aclRule->path) ?></td>
                <td><?= h($aclRule->created) ?></td>
                <td><?= h($aclRule->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $aclRule->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $aclRule->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $aclRule->id], ['confirm' => __('Are you sure you want to delete # {0}?', $aclRule->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        <?= $this->Paginator->first('<< ' . __('first')) ?>
        <?= $this->Paginator->prev('< ' . __('previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next') . ' >') ?>
        <?= $this->Paginator->last(__('last') . ' >>') ?>
    </div>
    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
</div>
