<?php

declare(strict_types=1);

namespace Invo\Controllers;

class IndexController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();

        $this->tag->title()->set('Welcome');
    }

    public function indexAction(): void
    {
        // $this->flash->notice('Hello guys');
    }
}
