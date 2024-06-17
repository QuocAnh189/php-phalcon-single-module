<?php

declare(strict_types=1);

namespace Invo\Forms;

use Phalcon\Filter\Validation\Validator\PresenceOf;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;

class LoginForm extends Form
{
    public function initialize()
    {
        // Username/Email text field
        $username = new Text('username');
        $username->setLabel('Username');
        $username->setFilters(['striptags', 'string']);
        $username->addValidators([
            new PresenceOf(['message' => 'Username/Email is required']),
        ]);

        $this->add($username);

        // Password field
        $password = new Password('password');
        $password->setLabel('Password');
        $password->addValidators([
            new PresenceOf(['message' => 'Password is required']),
        ]);

        $this->add($password);
    }
}
