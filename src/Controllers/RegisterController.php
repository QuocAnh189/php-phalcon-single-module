<?php

declare(strict_types=1);

namespace Invo\Controllers;

use Invo\Forms\RegisterForm;
use Invo\Models\Users;
use Phalcon\Db\RawValue;

class RegisterController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->title()->set('Sign Up/Sign In');

        parent::initialize();
    }

    public function indexAction(): void
    {
        $form = new RegisterForm();

        if ($this->request->isPost()) {
            $newUser = new Users();
            if ($form->isValid($this->request->getPost(), $newUser)) {
                $newUser->password = sha1($form->getFilteredValue('password'));
                $newUser->created_at = new RawValue('now()');

                if ($newUser->save()) {
                    $this->flash->success(
                        'Register successfully, please log-in'
                    );

                    $this->dispatcher->forward([
                        'controller' => 'session',
                        'action'     => 'index',
                    ]);

                    return;
                }
            } else {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            }
        }

        $this->view->form = $form;
    }
}
