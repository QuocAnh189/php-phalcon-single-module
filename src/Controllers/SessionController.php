<?php

declare(strict_types=1);

namespace Invo\Controllers;

use Invo\Constants\Status;
use Invo\Forms\LoginForm;
use Invo\Models\Users;


class SessionController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();

        $this->tag->title()->set('Sign Up/Sign In')
        ;
    }

    public function indexAction(): void
    {
        $form = new LoginForm();

        $form->get('username')->setDefault('');
        $form->get('password')->setDefault('');

        $this->view->form = $form;
    }

    public function startAction(): void
    {
        if ($this->request->isPost()) {
            $username    = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            /** @var Users $user */
            $user = Users::findFirst(
                [
                    "conditions" => "username = :username:"
                        . "AND password = :password: ",
                    'bind'       => [
                        'username'    => $username,
                        'password' => sha1($password),
                    ],
                ]
            );

            if ($user) {
                $this->registerSession($user);
                $this->flash->success('Welcome ' . $user->username);

                $this->dispatcher->forward([
                    'controller' => 'index',
                    'action'     => 'index',
                ]);

                return;
            }

            $this->flash->error('Wrong username/password');
        }

        $this->dispatcher->forward([
            'controller' => 'session',
            'action'     => 'index',
        ]);
    }

    /**
     * Finishes the active session redirecting to the index
     */
    public function endAction(): void
    {
        $this->session->remove('auth');

        $this->flash->success('Goodbye!');

        $this->dispatcher->forward([
            'controller' => 'index',
            'action'     => 'index',
        ]);
    }

    /**
     * Register an authenticated user into session data
     *
     * @param Users $user
     */
    private function registerSession(Users $user): void
    {
        $this->session->set('auth', [
            'id'   => $user->id,
            'username' => $user->username,
            'role' => $user->role
        ]);
    }
}
