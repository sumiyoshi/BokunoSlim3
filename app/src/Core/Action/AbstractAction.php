<?php

namespace Core\Action;

use Core\CoreSlim;

/**
 * Class AbstractAction
 * @package Core\Actions
 */
abstract class AbstractAction
{
    /** @var CoreSlim */
    protected $app;

    /** @var array|mixed|null */
    protected $params = null;

    /** @var string */
    public $template = '';

    /** @var array */
    public $dto = array();


    public function setTemplate($module_name, $controller_name, $action_name)
    {
        $this->template = mb_strtolower($module_name) . '/' . mb_strtolower($controller_name) . '/' . mb_strtolower($action_name) . '.twig';
    }

    public function init()
    {
        $this->dto['params'] = $this->getActionParameters();
    }

    /**
     * @param CoreSlim $app
     */
    public function setApp(CoreSlim $app)
    {
        $this->app = $app;
    }

    /**
     * @return \Slim\Http\Request
     */
    protected function getRequest()
    {
        return $this->app->getContainer()->get('request');
    }

    /**
     * @return array|mixed|null
     */
    protected function getActionParameters()
    {
        if ($this->params == null) {
            $this->params = $this->getRequest()->getParams();
        }

        return $this->params;
    }
}