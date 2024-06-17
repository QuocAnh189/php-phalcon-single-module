<?php

declare(strict_types=1);

namespace Invo\Forms;

use Phalcon\Filter\Validation\Validator\PresenceOf;
use Phalcon\Filter\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;

class StudentsForm extends Form
{
    /**
     * @param null $entity
     * @param null $options
     */
    public function initialize($entity = null, $options = null)
    {

        if (!isset($options['edit'])) {
            $this->add((new Text('code'))->setLabel('Code'));
        } else {
            $this->add(new Hidden('code'));
        }   


        // Code text field
        $code = new Text('code');
        $code->setLabel('Code');
        $code->addValidators([
            new PresenceOf(['message' => 'Please enter code']),
            new UniquenessValidator(
                [
                    'message' => 'Sorry, That code is already taken',
                ]
            )
        ]);

        $this->add($code);
    
        // username text field
        $username = new Text('username');
        $username->setLabel('Username');
        $username->addValidators([
            new PresenceOf(['message' => 'Please enter username']),
        ]);

        $this->add($username);


        // email text field
        $email = new Text('email');
        $email->setLabel('Email');
        $email->addValidators([
            new PresenceOf(['message' => 'Please enter email']),
            new UniquenessValidator(
                [
                    'message' => 'Sorry, That email is already taken',
                ]
            )
        ]);

        $this->add($email);

        // department text field
        $department = new Text('department');
        $department->setLabel('Department');
        $department->addValidators([
            new PresenceOf(['message' => 'Please enter deparment']),
        ]);

        $this->add($department);

        // major text field
        $major = new Text('major');
        $major->setLabel('major');
        $major->addValidators([
            new PresenceOf(['message' => 'Please enter major']),
        ]);

        $this->add($major);
    }
}
