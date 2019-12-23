<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Database\Expression\QueryExpression;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Direct Controller
 *
 *
 * @method \App\Model\Entity\Direct[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DirectController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('DirectMessages');
    }

    public function inbox()
    {
        $directMessages = $this->DirectMessages->find('all')->where(['to_user_id' => $this->Auth->user('id')])->contain(['Users']);

        $this->set('directMessages', $directMessages);
    }

    public function outbox()
    {
        $directMessages = $this->DirectMessages->find('all')->join(['users' => [
            'table' => 'users',
            'type' => 'INNER',
            'conditions' => 'users.id = to_user_id',
        ]])->select(['id', 'direct_id', 'title', 'created', 'users.username'])->where(['from_user_id' => $this->Auth->user('id')]);

        $this->set('directMessages', $directMessages);
    }

    /**
     * View method
     *
     * @param string|null $id Direct id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $direct = $this->DirectMessages->find('all')->where(['direct_id' => $id, 'from_user_id' => $this->Auth->user('id')])->orWhere(['direct_id' => $id, 'to_user_id' => $this->Auth->user('id')])->contain(['Users'])->first();

        if (empty($direct)) {
            throw new NotFoundException(__('Direct message not found'));
        }

        $replies = $this->DirectMessages->find('all', [
            'contain' => ['Users']
        ])->where(['direct_id' => $id])->andWhere(['DirectMessages.id IS NOT' => $direct->id]);

        $direct_views = TableRegistry::get('direct_views');

        if (!$direct_views->find()->where([['user_id' => $this->Auth->user('id'), 'direct_id' => $direct->id]])->count()) {
            $direct_views->query()->insert(['direct_id', 'user_id'])->values(['direct_id' => $direct->id, 'user_id' => $this->Auth->user('id')])->execute();
        }

        foreach ($replies as $row) {
            if (!$direct_views->find()->where([['user_id' => $this->Auth->user('id'), 'direct_id' => $row->id]])->count()) {
                $direct_views->query()->insert(['direct_id', 'user_id'])->values(['direct_id' => $row->id, 'user_id' => $this->Auth->user('id')])->execute();
            }
        }

        $this->set('direct', $direct);
        $this->set('replies', $replies);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $direct = $this->DirectMessages->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->find('all')->where(['username' => $this->request->getData('to_username')])->first();

            if (empty($user)) {
                $this->Flash->error(__('The direct message could not be saved. Please, try again.'));
                return $this->redirect('/');
            }

            $direct['to_user_id'] = $user->id;

            $direct['user_id'] = $this->Auth->user('id');

            $direct['from_user_id'] = $this->Auth->user('id');

            $direct['direct_id'] = $direct->id;

            $direct = $this->DirectMessages->patchEntity($direct, $this->request->getData());
            if ($this->DirectMessages->save($direct)) {
                $direct_id_query = $this->DirectMessages->query();
                $direct_id_query->update()
                    ->set(['direct_id' => $direct->id])
                    ->where(['id' => $direct->id]);

                if ($direct_id_query->execute()) {
                    $this->Flash->success(__('The direct has been saved.'));

                    return $this->redirect(['action' => 'view', $direct->id]);
                }
            }
            $this->Flash->error(__('The direct message could not be saved. Please, try again.'));
        }
        $this->set(compact('direct'));
    }

    public function reply($directId)
    {
        $direct = $this->DirectMessages->newEntity();
        $direct_data = $this->DirectMessages->find('all')->where(['direct_id' => $directId]);

        if (empty($direct)) {
            $this->Flash->error(__('The direct message could not be saved. Please, try again.'));
            return $this->redirect('/');
        }

        $validator = new Validator();
        $validator->requirePresence('body');

        $direct['to_user_id'] = $direct_data->last()->user_id;

        if ($this->request->is('post')) {
            $errors = $validator->errors($this->request->getData());
            if (empty($errors)) {
                $direct['title'] = $direct_data->first()->title;

                $direct['user_id'] = $this->Auth->user('id');

                $direct['from_user_id'] = $this->Auth->user('id');

                $direct['direct_id'] = $direct_data->first()->id;

                $direct = $this->DirectMessages->patchEntity($direct, $this->request->getData(), ['validate' => false]);
                if ($this->DirectMessages->save($direct)) {
                    $this->Flash->success(__('The reply has been saved.'));

                    return $this->redirect(['action' => 'view', $direct_data->first()->id]);

                }
            }

            $this->Flash->error(__('The reply message could not be saved. Please, try again.'));
        }
        $this->set(compact('direct'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Direct id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $direct = $this->Direct->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $direct = $this->Direct->patchEntity($direct, $this->request->getData());
            if ($this->Direct->save($direct)) {
                $this->Flash->success(__('The direct has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The direct could not be saved. Please, try again.'));
        }
        $this->set(compact('direct'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Direct id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $direct = $this->Direct->get($id);
        if ($this->Direct->delete($direct)) {
            $this->Flash->success(__('The direct has been deleted.'));
        } else {
            $this->Flash->error(__('The direct could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
