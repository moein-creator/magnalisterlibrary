<?php

/**
 * SQL query builder
 */
class ML_DatabaseQuerySelectExperimental_Model_Query_Select extends ML_Database_Model_Query_Select {

    protected $aGroupBy = array();
    protected $aHaving = array();
    protected $aBuilders = array(
        'select' => 'Select',
        'from' => 'From',
        'join' => 'Join',
        'where' => 'Where',
        'groupby' => 'GroupBy',
        'having' => 'Having',
        'orderby' => 'OrderBy',
        'limit' => 'Limit'
    );

    /**
     * see createCondition document
     * @param type $mCondition
     * @return ML_Database_Model_Query_Select
     */
    public function having($mCondition) {
        $this->aResult = null;
        $this->iResult = null;
        $this->aHaving[] = $this->createCondition($mCondition);
        return $this;
    }

    /**
     * Add a GROUP BY restriction
     *
     * @param string $fields List of fields to groupby
     * @return ML_Database_Model_Query_Select
     */
    public function groupBy($sFields) {
        $this->aResult = null;
        $this->iResult = null;
        if (!empty($sFields)) {
            $this->aGroupBy[] = $sFields;
        }

        return $this;
    }

    protected function buildGroupBy() {
        if (count($this->aGroupBy) > 0) {
            return 'GROUP BY ' . implode(', ', $this->aGroupBy) . "\n";
        } else {
            return '';
        }
    }

    protected function buildHaving() {
        if (count($this->aHaving) > 0) {
            return 'HAVING (' . implode(') AND (', $this->aHaving) . ")\n";
        } else {
            return '';
        }
    }

    /**
     * return array of rows
     * @return array 
     */
    public function getAll() {
        if ($this->aResultAll === null) {
            $this->aResult = MLDatabase::getDbInstance()->fetchArray($this->buildSql(array('limit', 'having', 'groupby')));
        }
        return $this->aResult;
    }

    /**
     * rturn count of selected row ocording to with limit included or excluded
     * @param bool $blTotal , if true exclude limit from select and otherwise it will be included
     * @param string $sField
     * @return mixed
     */
    public function getCount($blTotal = true, $sField ='*' ) {
        if (!$blTotal) {
            if ($this->iResult === null) {
                $this->iResult = count($this->getResult());
            }
            return $this->iResult;
        } else {
            if ($this->iResultAll === null) {
                $aExcBuilder = array(
                    'order',
                    'select',
                    'limit',
                    'having',
                    'groupby'
                );
                $this->iResultAll = MLDatabase::getDbInstance()->fetchOne('SELECT COUNT(*) AS count ' . $this->buildSql($aExcBuilder));
            }
            return $this->iResultAll;
        }
    }

}
