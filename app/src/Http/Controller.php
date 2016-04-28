<?php

namespace Http;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Controller
 * @package Core\Http
 */
abstract class Controller
{
    /**
     * @var array
     */
    public $dto = [];

    /**
     * @var string
     */
    protected $template;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;


    /**
     * Middleware constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response, $template)
    {
        $this->request = $request;
        $this->response = $response;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

}