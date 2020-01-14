<?php
namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Chat cell
 */
class ChatCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Initialization logic run at the end of object construction.
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadModel('Chats');
        $this->loadModel('Users');
    }

    public function getPrivateChats($fromUserId, $toUserId, $userName)
    {
        $connection = \Cake\Datasource\ConnectionManager::get('default');
        $subquery = $connection
            ->newQuery()
            ->select('*')
            ->from('chats')
            ->where([
                ['OR' =>
                    array('user_id' => $fromUserId,
                        'user_id' => $toUserId)
                ]
            ])
            ->andWhere([
                ['OR' =>
                    array('from_user_id' => $fromUserId,
                        'to_user_id' => $fromUserId)
                ]
            ])
            ->order(['created' => 'DESC'])->limit(10);

        $private_chats = $this->Chats->find('all')
            ->contain('Users')
            ->from(['Chats' => $subquery])
            ->where([
                ['OR' =>
                    array('user_id' => $fromUserId,
                        'user_id' => $toUserId)
                ]
            ])
            ->andWhere([
                ['OR' =>
                    array('from_user_id' => $fromUserId,
                        'to_user_id' => $fromUserId)
                ]
            ])->order(['Chats.created' => 'ASC']);

        $this->set('private_chats', $private_chats);
        $this->set('userName', $userName);
    }
}
