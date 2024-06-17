<?php

declare(strict_types=1);

namespace Invo\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Mvc\Router;

class RouterProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('router', function () {
            $router = new Router(false); // false indicates that default routes will not be used

            // Set a default module, controller, and action
            $router->setDefaultModule('student');
            $router->setDefaultController('index');
            $router->setDefaultAction('index');

            // Module: Student
            $router->add('/student/:controller/:action/:params', [
                'module' => 'student',
                'controller' => 1,
                'action' => 2,
                'params' => 3,
            ])->setName('student-route');

            // Module: User
            $router->add('/user/:controller/:action/:params', [
                'module' => 'user',
                'controller' => 1,
                'action' => 2,
                'params' => 3,
            ])->setName('user-route');

            // Module: Auth
            $router->add('/auth/:controller/:action/:params', [
                'module' => 'auth',
                'controller' => 1,
                'action' => 2,
                'params' => 3,
            ])->setName('auth-route');

            // Default route for the main index
            $router->add('/', [
                'module' => 'student',
                'controller' => 'index',
                'action' => 'index'
            ])->setName('default-route');

            return $router;
        });
    }
}
