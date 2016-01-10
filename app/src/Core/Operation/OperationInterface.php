<?php

namespace Core\Operation;

/**
 * Interface OperationInterface
 * @package Core\Operations
 */
interface OperationInterface
{
    public function setParams(array $data);

    public function run();

    public function getResults();

    public function getErrors();

}