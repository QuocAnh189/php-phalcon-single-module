<?php

declare(strict_types=1);

namespace Invo\Controllers;

use Invo\Forms\usersForm;
use Invo\Models\Users;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class usersController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->tag->title()->set('Manage your users');
    }

    // Shows the index action
    public function indexAction(): void
    {
        $this->view->form = new UsersForm();
    }

    // Search user based on current criteria
    public function searchAction(): void
    {
        if ($this->request->isPost()) {
            $query = Criteria::fromInput(
                $this->di,
                users::class,
                $this->request->getPost()
            );

            $this->persistent->searchParams = ['di' => null] + $query->getParams();
        }

        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $users = Users::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice('The search did not find any user');

            $this->dispatcher->forward([
                'controller' => 'users',
                'action'     => 'index',
            ]);

            return;
        }

        $paginator = new Paginator([
            'model' => Users::class,
            'parameters' => $parameters,
            'limit' => 10,
            'page'  => $this->request->getQuery('page', 'int', 1),
        ]);

        $this->view->page      = $paginator->paginate();
        $this->view->users = $users;
    }

    // Shows the form to create a new user
    public function newAction(): void
    {
        $this->view->form = new UsersForm(null, ['edit' => true]);
    }

    /**
     * Edits a user based on its id
     *
     * @param int $id
     */
    public function editAction($id): void
    {
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error('user was not found');

            $this->dispatcher->forward([
                'controller' => 'users',
                'action'     => 'index',
            ]);

            return;
        }

        $this->view->form = new UsersForm($user, ['edit' => true]);
    }

    // Creates a new user
    public function createAction(): void
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => 'users',
                'action'     => 'index',
            ]);

            return;
        }

        $form    = new UsersForm();
        $user = new Users();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $user)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            $this->dispatcher->forward([
                'controller' => 'users',
                'action'     => 'new',
            ]);

            return;
        }

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            $this->dispatcher->forward([
                'controller' => 'users',
                'action'     => 'new',
            ]);

            return;
        }

        $form->clear();
        $this->flash->success('Created users successfully');

        $this->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'search',
        ]);
    }

    // Saves current user in screen
    public function saveAction(): void
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => 'users',
                'action'     => 'index',
            ]);

            return;
        }

        $id = $this->request->getPost('id', 'int');
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error('user does not exist');

            $this->dispatcher->forward([
                'controller' => 'users',
                'action'     => 'index',
            ]);

            return;
        }

        $data = $this->request->getPost();
        $form = new UsersForm();
        if (!$form->isValid($data, $user)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            $this->dispatcher->forward([
                'controller' => 'users',
                'action'     => 'new',
            ]);

            return;
        }

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            $this->dispatcher->forward([
                'controller' => 'users',
                'action'     => 'new',
            ]);

            return;
        }

        $form->clear();
        $this->flash->success('user was updated successfully');

        $this->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'index',
        ]);
    }

    /**
     * Deletes a user
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error('user was not found');

            $this->dispatcher->forward([
                'controller' => 'users',
                'action'     => 'index',
            ]);

            return;
        }

        if (!$user->delete()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            $this->dispatcher->forward([
                'controller' => 'users',
                'action'     => 'search',
            ]);

            return;
        }

        $this->flash->success('user was deleted');

        $this->dispatcher->forward([
            'controller' => 'users',
            'action'     => 'index',
        ]);
    }
}
