<?php

namespace Core\Operation;


/**
 * Class AbstractDeleteOperation
 *
 * @package Component\Operation\Base
 */
abstract class BaseDeleteOperation extends AbstractOperation
{
    /** @var AbstractTable */
    protected $table;

    /** @var AbstractModel */
    protected $record;

    /** @var string */
    protected $table_name;

    /**
     * 初期処理
     */
    protected function init()
    {
        $this->table = $this->getServiceLocator()->get($this->table_name . 'Table');
        $this->record = $this->table->findByPrimaryKey($this->getParams());
    }

    /**
     * @return bool
     */
    protected function _exec()
    {
        return ($this->record->delete() === 1);
    }

    /**
     * @return bool
     */
    protected function validate()
    {
        if (!$this->record) {
            $this->addError('system', 'パラメータが不正です');
            return false;
        } else {
            return true;
        }
    }

    public function execAfter()
    {
        // TODO: Implement execAfter() method.
    }
}