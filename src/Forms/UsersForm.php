<?php

declare(strict_types=1);

namespace Invo\Forms;

use Phalcon\Filter\Validation\Validator\PresenceOf;
use Phalcon\Filter\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;

class UsersForm extends Form
{
    /**
     * @param null $entity
     * @param null $options
     */
    public function initialize($entity = null, $options = null)
    {

        if (!isset($options['edit'])) {
            $this->add((new Text('id'))->setLabel('Id'));
        } else {
            $this->add(new Hidden('id'));
        }

        $username = new Text('id');
        $username->setLabel('Id');
        $username->addValidators([
            new PresenceOf(['message' => 'Please enter Id']),
            new UniquenessValidator(
                [
                    'message' => 'Sorry, That id is already taken',
                ]
            )
        ]);

        $this->add($username);

        $username = new Text('username');
        $username->setLabel('Username');
        $username->addValidators([
            new PresenceOf(['message' => 'Please enter username']),
            new UniquenessValidator(
                [
                    'message' => 'Sorry, That code is already taken',
                ]
            )
        ]);

        $this->add($username);
    
        // username text field
        $role = new Text('role');
        $role->setLabel('Role');
        $role->addValidators([
            new PresenceOf(['message' => 'Please enter role']),
        ]);

        $this->add($role);
    }
}
