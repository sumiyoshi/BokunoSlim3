<?php

namespace Core\Operation;


/**
 * Class AbstractSaveOperation
 *
 * @package Component\Operation\Base
 */
abstract class BaseSaveOperation extends AbstractOperation
{
    /** @var  string */
    protected $table_name;

    /** @var AbstractTable */
    protected $table;

    /** @var AbstractModel */
    protected $record;

    /**
     * 初期処理
     */
    protected function init()
    {
        $this->table = $this->getServiceLocator()->get($this->table_name . 'Table');

        #region 初期設定
        if ($this->mode == 'update') {
            $this->record = $this->table->findByPrimaryKey($this->getParams());
            if ($this->record) {
                $this->record->exchangeUpdateArray($this->getParams());
            }

        } else {
            $this->record = $this->getServiceLocator()->get($this->table_name . 'Model');
            $this->record->exchangeArray($this->getParams());
        }
        #endregion
    }

    /**
     * @return bool
     */
    protected function _exec()
    {
        $update = ($this->mode == 'update') ? true : false;
        return $this->record->save($update);
    }

    /**
     * @return bool
     */
    protected function validate()
    {
        #region チェック対象
        if ($this->mode == 'update') {

            if (!$this->record) {
                $this->addError('system', 'パラメータが不正です');
                return false;
            }

            $group = $this->record->getGroup();
        } else {
            $group = $this->record->getGroup(false);
        }
        #endregion

        if (!$this->record->isValid($group)) {
            $this->setError($this->record->getErrors());
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