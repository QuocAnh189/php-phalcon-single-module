<?php

declare(strict_types=1);

namespace Invo\Controllers;

use Invo\Forms\StudentsForm;
use Invo\Models\Students;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class StudentsController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->tag->title()->set('Manage your students');
    }

    public function indexAction(): void
    {
        $this->view->form = new StudentsForm();
    }

    public function searchAction(): void
    {
        if ($this->request->isPost()) {
            $query = Criteria::fromInput(
                $this->di,
                Students::class,
                $this->request->getPost()
            );

            $this->persistent->searchParams = ['di' => null] + $query->getParams();
        }

        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $students = Students::find($parameters);
        if (count($students) == 0) {
            $this->flash->notice('The search did not find any student');

            $this->dispatcher->forward([
                'controller' => 'students',
                'action'     => 'index',
            ]);

            return;
        }

        $paginator = new Paginator([
            'model' => Students::class,
            'parameters' => $parameters,
            'limit' => 20,
            'page'  => $this->request->getQuery('page', 'int', 1),
        ]);

        $this->view->page      = $paginator->paginate();
        $this->view->students  = $students;
    }

    public function newAction(): void
    {
        $this->view->form = new StudentsForm(null, ['edit' => false]);
    }

    /**
     * Edits a student based on its id
     *
     * @param int $id
     */
    public function editAction($code): void
    {
        $student = Students::findFirstByCode($code);
        if (!$student) {
            $this->flash->error('student was not found');

            $this->dispatcher->forward([
                'controller' => 'students',
                'action'     => 'index',
            ]);

            return;
        }

        $this->view->form = new StudentsForm($student, ['edit' => true]);
    }

    public function createAction(): void
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => 'students',
                'action'     => 'index',
            ]);

            return;
        }   

        $form    = new StudentsForm();
        $student = new Students();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $student)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            $this->dispatcher->forward([
                'controller' => 'students',
                'action'     => 'new',
            ]);

            return;
        }

        if (!$student->save()) {
            foreach ($student->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            $this->dispatcher->forward([
                'controller' => 'students',
                'action'     => 'new',
            ]);

            return;
        }

        $form->clear();
        $this->flash->success('Create student successfully');

        $this->dispatcher->forward([
            'controller' => 'students',
            'action'     => 'search',
        ]);
    }

    /**
     * Saves current student in screen
     */
    public function saveAction(): void
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => 'students',
                'action'     => 'index',
            ]);

            return;
        }

        $code      = $this->request->getPost('code');
        $student = Students::findFirstByCode($code);
        if (!$student) {
            $this->flash->error('student does not exist');

            $this->dispatcher->forward([
                'controller' => 'students',
                'action'     => 'search',
            ]);

            return;
        }

        $data = $this->request->getPost();
        $form = new StudentsForm();
        if (!$form->isValid($data, $student)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            $this->dispatcher->forward([
                'controller' => 'students',
                'action'     => 'new',
            ]);

            return;
        }

        if (!$student->save()) {
            foreach ($student->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            $this->dispatcher->forward([
                'controller' => 'students',
                'action'     => 'new',
            ]);

            return;
        }

        $form->clear();
        $this->flash->success('Update student successfully');

        $this->dispatcher->forward([
            'controller' => 'students',
            'action'     => 'search',
        ]);
    }

    /**
     * Deletes a student
     *
     * @param string $id
     */
    public function deleteAction($code)
    {
        $student = Students::findFirstByCode($code);
        if (!$student) {
            $this->flash->error('student was not found');

            $this->dispatcher->forward([
                'controller' => 'students',
                'action'     => 'search',
            ]);

            return;
        }

        if (!$student->delete()) {
            foreach ($student->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            $this->dispatcher->forward([
                'controller' => 'students',
                'action'     => 'search',
            ]);

            return;
        }

        $this->flash->success('Delete student successfully');

        $this->dispatcher->forward([
            'controller' => 'students',
            'action'     => 'search',
        ]);
    }
}
