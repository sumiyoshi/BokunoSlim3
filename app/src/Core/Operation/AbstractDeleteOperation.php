<?php

namespace Core\Operation;

/**
 * Class AbstractDeleteOperation
 * @package Component\Operation
 */
abstract class AbstractDeleteOperation extends AbstractOperation
{

    /** @var mixed */
    protected $model;

    /** @var mixed */
    protected $table;

    public function init()
    {
        $model = $this->model;
        $table = $this->table;

        if ($id = $this->param($model::$_id_column)) {
            $this->model = $table::getORM([])->find_one($id);
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