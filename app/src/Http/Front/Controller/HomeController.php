<?php

namespace Http\Front\Controller;

use Http\Controller;

/**
 * Class HomeController
 * @package App\Front\Controller
 */
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
    public function userAction()
    {
        return $this->dto;
    }

}