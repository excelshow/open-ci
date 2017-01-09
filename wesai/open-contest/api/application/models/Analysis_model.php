<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once __DIR__ . '/ModelBase.php';

/**
 * 活动项目表单数据处理类
 *
 * @author: zhaodechang@wesai.com
 **/
class Analysis_model extends ModelBase
{
    public function __construct()
    {
        parent::__construct();
        $this->setDBConfig(BATCH_DB_CONFIG);
    }

    public function getSumNumber($fkCorp, $days = null)
    {
        $sql = 'select sum(contest_count) as contest_count, sum(item_count) as item_count, sum(order_count) as order_count, sum(amount_sum) as amount_sum from ' . $this->tableNameAnalysisContest . ' where fk_corp = :fkCorp';

        $params = compact('fkCorp');

        if (!empty($days)) {
            $fromDate = date('Y-m-d', strtotime('-' . ($days + 1) . ' days'));
            $toDate   = date('Y-m-d', strtotime('-1 days'));

            $sql .= ' and date > :fromDate and date <= :toDate';

            $params['fromDate'] = $fromDate;
            $params['toDate']   = $toDate;
        }

        return $this->getSingle(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params);
    }

    public function getTotal($corp_id)
    {
        return $this->setTable('t_contest_order_month')
                    ->addQueryFieldCalc('sum', 'amount', 'amount')
                    ->addQueryFieldCalc('sum', 'amount_pay', 'amount_pay')
                    ->addQueryFieldCalc('sum', 'numbers', 'numbers')
                    ->addQueryFieldCalc('sum', 'enrol_data_count', 'enrol_data_count')
                    ->addQueryConditions('fk_corp', $corp_id)
                    ->doSelect();
    }

    public function listByDate($corp_id, $date_start, $date_end)
    {
        return $this->setTable('t_contest_order_day')
                    ->addQueryConditions('fk_corp', $corp_id)
                    ->addQueryConditions('day', $date_start, '>=')
                    ->addQueryConditions('day', $date_end, '<')
                    ->doSelect();
    }
} // END class Msg_model
