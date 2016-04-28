<?php

namespace Component\Operation;

/**
 * Class Operation
 *
 * @package Component\Operation
 * @author sumiyoshi
 */
abstract class Operation implements OperationInterface
{
    use \Core\Traits\Result;
    use \Core\Traits\Param;

    protected $mode;

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