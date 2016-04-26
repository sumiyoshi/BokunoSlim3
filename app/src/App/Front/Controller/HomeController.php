<?php

namespace App\Front\Controller;

class HomeController extends \Core\Http\AbstractController
{
    /**
     * @param $request
     * @param $response
     * @return array
     */
    public function indexAction($request, $response)
    {
        return $this->dto;
    }

    /**
     * @param $request
     * @param $response
     * @return array
     */
    public function userAction($request, $response)
    {
        return $this->dto;
    }

}