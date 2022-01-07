<?php

namespace App\Page;

use App\Controller\HomepageController;
use Page;

class Homepage extends Page
{
    public function getControllerName()
    {
        return HomepageController::class;
    }
}