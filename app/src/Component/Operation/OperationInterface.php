<?php

namespace Component\Operation;

/**
 * Interface OperationInterface
 * @package Component\Operation
 */
interface OperationInterface
{
    public function setParams(array $data);

    public function run();

    public function getResults();

    public function getErrors();

}