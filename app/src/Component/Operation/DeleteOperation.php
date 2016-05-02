<?php

namespace Component\Operation;

/**
 * Class DeleteOperation
 *
 * @package Component\Operation
 * @author sumiyoshi
 */
abstract class DeleteOperation extends Operation
{

    /** @var mixed */
    protected $model;

    public function init()
    {
        $model = $this->model;
        if ($id = $this->param($model::$_id_column)) {
            $this->model = $model::getORM()->find_one($id);
        }
        #endregion
    }

    /**
     * @return bool
     */
    protected function _run()
    {
        return $this->model->delete();
    }

    protected function validate()
    {
        $model = $this->model;

        if (!$model) {
            $this->addError('system', 'パラメータが不正です');
            return;
        }
    }
}