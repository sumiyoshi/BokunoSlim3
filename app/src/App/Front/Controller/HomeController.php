<?php

namespace App\Front\Controller;

use Core\Http\Controller;

class HomeController extends Controller
{

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->dto;
    }

    /**
     * @return array
     */
    public function userAction($id)
    {
        return $this->dto;
    }

}