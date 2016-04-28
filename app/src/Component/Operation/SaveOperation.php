<?php

namespace Component\Operation;

use Core\Util\ArrayUtil;

/**
 * Class SaveOperation
 * @package Component\Operation
 */
abstract class SaveOperation extends Operation
{

    /** @var mixed */
    protected $model;

    /** @var mixed */
    protected $table;


    public function init()
    {
        $model = $this->model;
        $table = $this->table;

        #region 新規・更新別初期処理
        if ($id = $this->param($model::$_id_column)) {
            $this->mode = 'update';
            $this->model = $table::getORM([])->find_one($id);
        } else {
            $this->model = $model::create([]);
        }
        #endregion
    }

    /**
     * @return bool
     */
    protected function _run()
    {
        #region 保存
        $this->model->hydrate($this->getEntityData());
        $res = $this->model->save();
        #endregion

        if ($res) {
            $this->setResult($this->model->as_array());
            return true;
        } else {
            return false;
        }
    }

    protected function validate()
    {
        $model = $this->model;

        if (!$model) {
            $this->addError('system', 'パラメータが不正です');
            return;
        }

        /** @var \Core\Validator\Validator $validator */
        $validator = $model::getValidator();

        if (!$validator->validate($this->getEntityData())) {
            $this->setError(ArrayUtil::shiftArray($validator->getMessages()));
            return;
        }
    }

    /**
     * @return array
     */
    protected function getEntityData()
    {
        $model = $this->model;

        if ($this->mode == 'update') {
            return ArrayUtil::getMergeEntityData($this->getParams(), $model);
        } else {
            return ArrayUtil::getEntityData($this->getParams(), $model);
        }
    }
}