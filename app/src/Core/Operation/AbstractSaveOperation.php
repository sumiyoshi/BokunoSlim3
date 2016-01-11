<?php

namespace Component\Operation;

use Component\Data\Model\User;
use Component\Data\Table\UserTable;
use Core\Util\ArrayUtil;


/**
 * Created by IntelliJ IDEA.
 * User: sumi
 * Date: 16/01/10
 * Time: 午後8:23
 */
class Save extends \Core\Operation\AbstractOperation
{

    /** @var User */
    protected $model;

    /** @var UserTable */
    protected $table;


    public function init()
    {
        #region 更新の場合はモデルセット
        if ($id = $this->param(User::$_id_column)) {
            $this->model = 'update';
            $this->table = new UserTable();
            $this->model = $this->table->getORM([])->find_one($id);
        } else {
            $this->model = User::create([]);
        }
        #endregion
    }

    protected function _run()
    {
        $this->model->hydrate($this->getParams());

        echo __CLASS__ . ":" . __line__;
        print'<pre>';
        var_dump($this->model->save());
        print'</pre>';
        exit;


        // TODO: Implement _run() method.
    }

    protected function validate()
    {
        $model = $this->model;
        $validator = $model::getValidator();

        if (!$validator->validate($this->getParams())) {
            $this->setError(ArrayUtil::shiftArray($validator->getMessages()));
            return;
        }
    }
}