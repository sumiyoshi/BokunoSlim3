<?php

namespace Core\Application;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Controller
 *
 * @package Core\Application
 * @author sumiyoshi
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
     * Controller constructor.
     * @param Request $request
     * @param Response $response
     * @param $template
     */
    public function __construct(Request $request, Response $response, $template)
    {
        $this->request = $request;
        $this->response = $response;
        $this->template = $template;
    }

    public function render($argument)
    {
        if ($argument instanceof ViewModel) {
            $this->dto['viewModel'] = $argument;
        } elseif (is_array($argument)) {
            $this->dto['data'] = $argument;
        }
        
        return true;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

}