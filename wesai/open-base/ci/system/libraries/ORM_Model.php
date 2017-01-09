<?php

require_once __DIR__ . '/DIY_Model.php';
require_once __DIR__ . '/ORM_TableJoin.php';


class ORM_Model extends DIY_Model
{
    private $dsnType       = '';
    private $dbConfig      = '';
    private $table         = '';
    private $queryFields   = array();
    private $conditions    = array();
    private $bindParams    = array();
    private $orderBy       = array();
    private $groupBy       = array();
    private $joins         = array();
    private $preTable      = '';
    private $insertColumns = array();
    private $insertValues  = array();
    private $updateColumns = array();
    private $debug         = false;
    private $lockRowSql    = null;

    public function __construct()
    {
        parent::__construct();
        $this->dsnType = Pdo_Mysql::DSN_TYPE_SLAVE;
    }

    private function clearAll()
    {
        $this->dsnType       = Pdo_Mysql::DSN_TYPE_SLAVE;
        $this->queryFields   = array();
        $this->conditions    = array();
        $this->bindParams    = array();
        $this->groupBy       = array();
        $this->orderBy       = array();
        $this->joins         = array();
        $this->preTable      = '';
        $this->insertColumns = array();
        $this->insertValues  = array();
        $this->updateColumns = array();
        $this->debug         = false;
        $this->lockRowSql    = null;
    }

    public function lockRow($lockRowSql = null)
    {
        $this->lockRowSql = ' FOR UPDATE ';
        if (!empty($lockRowSql)) {
            $this->lockRowSql = $lockRowSql;
        }

        return $this;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    public function get_db()
    {
        return $this->dbConfig;
    }

    protected function addJoin($table, $joinType)
    {
        if (!array_key_exists($table, $this->joins)) {
            $this->joins[$table] = new TableJoin($this->table, $table, $joinType);
            $this->preTable      = $table;
        }

        return $this;
    }

    protected function addJoinCondition($leftColumn, $rightColumn)
    {
        if (empty($this->preTable)) {
            return $this;
        }

        if (!array_key_exists($this->preTable, $this->joins)) {
            return $this;
        }

        $this->joins[$this->preTable]->addJoinCondition($leftColumn, $rightColumn);

        return $this;
    }

    protected function setDsnType($dsnType)
    {
        $this->dsnType = $dsnType;

        return $this;
    }

    protected function setDBConfig($dbConfig)
    {
        $this->dbConfig = $dbConfig;

        return $this;
    }

    /**
     * @param $table
     *
     * @return ORM_Model
     */
    protected function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @param      $fields  array | string(split by ',')
     *                      eg:
     *                      array('pk_order', 'ctime')
     *                      'pk_order, ctime'
     *
     * @param null $table   default current set table
     *
     * @return ORM_Model
     */
    protected function addQueryFields($fields, $table = null)
    {
        assert(is_array($fields) || is_string($fields));

        if (empty($table)) {
            $table = $this->table;
        }

        if (is_array($fields)) {
            $this->queryFields = array_merge($this->queryFields, $this->addDataPrefix($fields, $table));

            return $this;
        }

        if (is_string($fields)) {
            $this->queryFields = array_merge($this->queryFields, $this->addDataPrefix(explode(',', $fields), $table));

            return $this;
        }

        return $this;
    }

    private function addDataPrefix($data, $prefix)
    {
        return array_map(
            function ($d) use ($prefix) {
                $d = trim($d);

                return "$prefix.$d";
            }, $data
        );
    }

    /**
     * @param        $conditions  array
     *                            array(
     *                            array('id', 1, '>'),
     *                            array('name', 1),
     *                            )
     *
     * @param null   $value
     * @param string $operator
     *
     * @param bool   $isExp
     *
     * @return $this
     */
    protected function addQueryConditions($conditions, $value = null, $operator = '=', $isExp = false)
    {
        // assert(!empty($conditions));
        if (empty($conditions)) {
            return $this;
        }
        assert(is_array($conditions) || is_string($conditions));

        if (is_string($conditions) && isset($value)) {
            $this->addQueryCondition($conditions, $value, $operator, $isExp);

            return $this;
        }

        foreach ($conditions as $v) {
            $conditions = $v[0];
            $value      = $v[1];
            $operator   = null;
            if (!empty($v[2])) {
                $operator = $v[2];
            }

            if (isset($v[3])) {
                $isExp = $v[3];
            }

            $this->addQueryCondition($conditions, $value, $operator, $isExp);
        }

        return $this;
    }

    /**
     * @param        $column
     * @param        $value
     * @param string $operator
     * @param bool   $isExp
     *
     * @return $this
     * @example  ('id', 1, >)
     */
    private function addQueryCondition($column, $value, $operator = '=', $isExp = false)
    {
        // assert(!empty($column));
        if (empty($column)) {
            return $this;
        }

        if (true === $isExp) {
            $this->conditions[] = "$column $operator $value";

            return $this;
        }

        $bindKey = ':' . $column;
        if (array_key_exists($bindKey, $this->bindParams)) {
            $bindKey .= '_' . microtime(true) * 10000;
        }

        $this->conditions[]         = "$column $operator $bindKey";
        $this->bindParams[$bindKey] = $value;

        return $this;
    }

    /**
     * @param      $conditions
     *              array(
     *              'ctime' => 'desc',
     *              'id' => 'asc',
     *              )
     *
     * @param null $order
     *
     * @return $this
     */
    protected function addOrderBy($conditions, $order = null)
    {
        // assert(!empty($conditions));
        if (empty($conditions)) {
            return $this;
        }
        assert(is_array($conditions) || is_string($conditions));

        if (is_string($conditions) && !empty($order)) {
            $this->addOrderByCondition($conditions, $order);

            return $this;
        }

        foreach ($conditions as $k => $v) {
            if (empty($v)) {
                continue;
            }

            $this->addOrderByCondition($k, $v);
        }

        return $this;
    }

    private function addOrderByCondition($column, $order)
    {
        $this->orderBy[] = "$column $order";

        return $this;
    }

    /**
     * @param $conditions
     *        array: array('a', 'b')
     *        string: 'a,b'
     *
     * @return $this
     */
    protected function addGroupBy($conditions)
    {
        // assert(!empty($conditions));
        if (empty($conditions)) {
            return $this;
        }
        assert(is_array($conditions) || is_string($conditions));

        if (is_array($conditions)) {
            $this->groupBy = array_merge($this->groupBy, $conditions);
        }

        if (is_string($conditions)) {
            $this->groupBy = array_merge($this->groupBy, explode(',', $conditions));
        }

        return $this;
    }

    /**
     * @param int $pageNumber
     * @param int $pageSize
     *
     * @return array|bool    ###########  always return getAll  ##############
     */
    protected function doSelect($pageNumber = 0, $pageSize = 10)
    {
        $sqlData = $this->makeSelectSql();

        if ($sqlData->error < 0) {
            log_message_v2('error', $sqlData);

            return false;
        }

        $dsnType = $this->dsnType;
        if ($this->isTransBegan()) {
            $dsnType = Pdo_Mysql::DSN_TYPE_MASTER;
        }

        return $this->getAll($dsnType, $sqlData->sql, $sqlData->bindParams, $pageNumber, $pageSize);
    }

    protected function makeSelectSql()
    {
        $std        = new stdClass();
        $std->error = 0;
        $std->msg   = '';

        $check = $this->preCheck();
        if ($check->error < 0) {
            return $check;
        }

        $fields     = $this->makeQueryFields();
        $conditions = $this->makeConditions();
        $joinSql    = $this->makeJoinSql();

        $sql = sprintf('select %s from %s %s %s', $fields, $this->table, $joinSql, $conditions);

        if (!empty($this->lockRowSql)) {
            $sql .= $this->lockRowSql;
        }

        $bindParams = $this->bindParams;

        $std->sql        = $sql;
        $std->bindParams = $bindParams;

        if ($this->debug) {
            log_message_v2('error', $std);
        }

        $this->clearAll();

        return $std;
    }

    private function preCheck()
    {
        $std        = new stdClass();
        $std->error = 0;
        $std->msg   = '';

        if (empty($this->dbConfig)) {
            $std->error = -1;
            $std->msg   = 'dbConfig not set';

            return $std;
        }

        if (empty($this->table)) {
            $std->error = -2;
            $std->msg   = 'table not set';

            return $std;
        }

        return $std;
    }

    private function makeQueryFields()
    {
        $fields = '*';
        if (!empty($this->queryFields)) {
            $fields = implode(', ', $this->queryFields);
        }

        return $fields;
    }

    private function makeConditions()
    {
        $condition = '';
        if (!empty($this->conditions)) {
            $condition .= ' where ' . implode(' and ', $this->conditions);
        }

        if (!empty($this->groupBy)) {
            $condition .= ' group by ' . implode(',', $this->groupBy);
        }

        if (!empty($this->orderBy)) {
            $condition .= ' order by ' . implode(',', $this->orderBy);
        }

        return $condition;
    }

    private function makeJoinSql()
    {
        if (empty($this->joins)) {
            return '';
        }

        $joinSql = '';
        foreach ($this->joins as $join) {
            $joinSql .= $join->getJoinSql();
        }

        return $joinSql;
    }

    protected function addQueryFieldCalc($function, $column, $alias = null, $table = null)
    {
        if (empty($alias)) {
            $alias = $column;
        }

        if (empty($table)) {
            $table = $this->table;
        }

        assert(is_string($alias), 'invalid alias, not in string type');

        if ($column != '*') {
            $column = "$table.$column";
        }

        $this->queryFields[] = "$function($column) as $alias";

        return $this;
    }

    /**
     * @param      $column
     * @param null $alias default $column
     * @param null $table default current set table
     *
     * ('pk_order', 'cnt', 't_order')  => count(t_order.pk_order) as cnt
     *
     * @return $this
     */
    protected function addQueryFieldsCount($column, $alias = null, $table = null)
    {
        return $this->addQueryFieldCalc('count', $column, $alias, $table);
    }

    /**
     * @param      $column
     * @param null $alias default $column
     * @param null $table default current set table
     *
     *  ('amount', 'amount', 't_order') => sum(t_order.amount) as amount
     *
     * @return $this
     */
    protected function addQueryFieldsSum($column, $alias = null, $table = null)
    {
        return $this->addQueryFieldCalc('sum', $column, $alias, $table);
    }

    /**
     * @param $column
     * @param $values  array | string(split by ',')
     *
     * @return $this
     */
    protected function addQueryConditionIn($column, $values)
    {
        // assert(!empty($column));
        // assert(!empty($values));
        if (empty($column) || empty($values)) {
            return $this;
        }
        assert(is_array($values) || is_string($values));

        if (is_string($values)) {
            $values = explode(',', $values);
        }

        $values = $this->trimArrayData($values);

        $values = implode(',', $values);

        $this->conditions[] = "$column in ($values)";

        return $this;
    }

    private function trimArrayData($data)
    {
        return array_map(
            function ($d) {
                return trim($d);
            }, $data
        );
    }

    /**
     * @param       $columns
     *                  array(
     *                  'id' => 1,
     *                  'name' => 2,
     *                  )
     * @param array $exceptKeys
     *                  when corresponding value was empty, continue insert
     *                  else skip
     *
     *              eg:
     *              array(
     *                  'name'
     *              )
     *
     * @return $this
     */
    protected function addInsertColumns($columns, $exceptKeys = array())
    {
        // assert(!empty($columns));
        if (empty($columns)) {
            return $this;
        }
        assert(is_array($columns));

        foreach ($columns as $k => $v) {
            if (empty($v) && !in_array($k, $exceptKeys)) {
                continue;
            }

            $this->addInsertColumn($k, $v);
        }

        return $this;
    }

    private function addInsertColumn($column, $value)
    {
        // assert(!empty($column));
        if (empty($column)) {
            return $this;
        }

        $this->insertColumns[] = $column;

        $bindKey = ':' . $column;

        if (array_key_exists($bindKey, $this->bindParams)) {
            $bindKey .= '_' . microtime(true) * 10000;
        }
        $this->insertValues[]       = $bindKey;
        $this->bindParams[$bindKey] = $value;

        return $this;
    }

    protected function doInsert()
    {
        $sqlData = $this->makeInsertSql();
        if ($sqlData->error < 0) {
            log_message_v2('error', $sqlData);

            return false;
        }

        return $this->insert(Pdo_Mysql::DSN_TYPE_MASTER, $sqlData->sql, $sqlData->bindParams);
    }

    protected function makeInsertSql()
    {
        $std        = new stdClass();
        $std->error = 0;
        $std->msg   = '';

        $check = $this->preCheck();
        if ($check->error < 0) {
            return $check;
        }

        $columns = $this->makeInsertColumns();
        $values  = $this->makeInsertValues();

        $std->sql        = sprintf('insert into %s (%s) values (%s)', $this->table, $columns, $values);
        $std->bindParams = $this->bindParams;

        if ($this->debug) {
            log_message_v2('error', $std);
        }

        $this->clearAll();

        return $std;
    }

    private function makeInsertColumns()
    {
        return implode(',', $this->insertColumns);
    }

    private function makeInsertValues()
    {
        return implode(',', $this->insertValues);
    }

    /**
     * @param        $columns
     *              eg:
     *              array(
     *              array('state', 2),
     *              array('number', 1, '-')
     *              )
     *
     * @param null   $value
     * @param string $operator
     *
     * @return $this
     */
    protected function addUpdateColumns($columns, $value = null, $operator = '=')
    {
        // assert(!empty($columns));
        if (empty($columns)) {
            return $this;
        }
        assert(is_array($columns) || is_string($columns));

        if (is_string($columns) && isset($value)) {
            $this->addUpdateColumn($columns, $value, $operator);

            return $this;
        }

        foreach ($columns as $column) {
            $operator = '=';
            if (count($column) == 3) {
                $operator = $column[2];
            }

            $this->addUpdateColumn($column[0], $column[1], $operator);
        }

        return $this;
    }

    /**
     * @param        $column
     * @param        $value
     * @param string $operator
     *               eg:
     *               ('state', 1) => state = 1
     *               ('number', 1, '+') => number = number + 1
     *
     * @return $this
     */
    protected function addUpdateColumn($column, $value, $operator = '=')
    {
        // assert(!empty($column));
        if (empty($column)) {
            return $this;
        }

        if ($operator != '=') {
            $this->updateColumns[] = "$column = $column $operator $value";

            return $this;
        }

        $bindKey = ':' . $column;
        if (array_key_exists($bindKey, $this->bindParams)) {
            $bindKey .= '_' . microtime(true) * 10000;
        }

        $this->updateColumns[]      = "$column = $bindKey";
        $this->bindParams[$bindKey] = $value;

        return $this;
    }

    protected function doUpdate()
    {
        $sqlData = $this->makeUpdateSql();
        if ($sqlData->error < 0) {
            log_message_v2('error', $sqlData);

            return false;
        }

        return $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sqlData->sql, $sqlData->bindParams);
    }

    protected function makeUpdateSql()
    {
        $std        = new stdClass();
        $std->error = 0;
        $std->msg   = '';

        $check = $this->preCheck();
        if ($check->error < 0) {
            return $check;
        }

        $columns    = $this->makeUpdateColumns();
        $conditions = $this->makeConditions();

        if (empty($columns)) {
            $std->error = -1;
            $std->msg   = 'update columns empty';

            return $std;
        }

        $std->sql = sprintf('update %s set %s %s', $this->table, $columns, $conditions);

        $std->bindParams = $this->bindParams;

        if ($this->debug) {
            log_message_v2('error', $std);
        }

        $this->clearAll();

        return $std;
    }

    private function makeUpdateColumns()
    {
        if (empty($this->updateColumns)) {
            return false;
        }

        return implode(',', $this->updateColumns);
    }
}
