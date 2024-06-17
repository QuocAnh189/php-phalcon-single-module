<?php

declare(strict_types=1);

namespace Invo\Forms;

use Phalcon\Filter\Validation\Validator\PresenceOf;
use Phalcon\Filter\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Filter\Validation\Validator\Confirmation;
use Phalcon\Filter\Validation\Validator\StringLength\Min;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;

class RegisterForm extends Form
{
    /**
     * @param null $entity
     * @param null $options
     */
    public function initialize($entity = null, $options = null)
    {
        // Username text field
        $name = new Text('username');
        $name->setLabel('Username');
        $name->setFilters(['alnum']);
        $name->addValidators([
            new PresenceOf(['message' => 'Please enter your desired user name']),
            new UniquenessValidator(
                [
                    'message' => 'Sorry, That username is already taken',
                ]
            )
        ]);

        $this->add($name);

    
        // Password field
        $password = new Password('password');
        $password->setLabel('Password');
        $password->addValidators([
            new PresenceOf(['message' => 'Password is required']),
            
            new Min(['min' => 8, 'message' => 'Password must be at least 8 characters']),
        ]);

        $this->add($password);

        // Confirm Password field
        $confirmPassword = new Password('confirmPassword');
        $confirmPassword->setLabel('Confirm Password');
        $confirmPassword->addValidators([
            new PresenceOf(['message' => 'Confirmation password is required']),
            new Confirmation(["message" => "Passwords are different", "with" => "password",]),
        ]);

        // Confirm Role field
        $role = new Text('role');
        $role->setLabel('Roles');
        $role->addValidators([
            new PresenceOf(['message' => 'Role is required']),
        ]);

        $this->add($role);
    }
}
