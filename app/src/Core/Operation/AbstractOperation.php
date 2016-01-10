<?php

namespace Core\Operation;

/**
 * Class AbstractCommand
 * @package Core\Command
 */
abstract class AbstractOperation implements OperationInterface
{
    use \Core\Traits\Result;
    use \Core\Traits\Param;

    public $mode;

    public function run()
    {
        $this->init();
        $this->validate();

        if (!$this->hasError()) {
            return $this->_run();
        } else {
            return false;
        }
    }

    protected function init()
    {

    }

    abstract protected function _run();

    abstract protected function validate();
}