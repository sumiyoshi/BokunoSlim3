<?php

namespace Http\Front;

use Core\Application\Controller;

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
}