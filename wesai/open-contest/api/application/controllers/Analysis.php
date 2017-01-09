<?php
require_once __DIR__ . '/Base.php';

/**
 * User: zhaodc
 * Date: 8/10/16
 * Time: 10:58
 */
class Analysis extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Analysis_model');
    }

    public function get_total_get()
    {
        $corp_id = $this->get_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY);

        $total = $this->Analysis_model->getTotal($corp_id);
        if (!empty($total)) {
            $total = $total[0];
        }

        return $this->response_object($total);
    }

    public function list_get()
    {
        $corp_id    = $this->get_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY);
        $date_start = $this->get_check('date_start', PARAM_NOT_NULL_NOT_EMPTY);
        $date_end   = $this->get_check('date_end', PARAM_NOT_NULL_NOT_EMPTY);

        $date_interval = 60;
        if ((strtotime($date_end) - strtotime($date_start)) > $date_interval * 24 * 60 * 60) {
            $this->response_error(Error_Code::ERROR_PARAM, "超过查询区间不能超过{$date_interval}天");
        }

        $details = $this->Analysis_model->listByDate($corp_id, $date_start, $date_end);
        $summary = array(
            'amount'           => 0,
            'amount_pay'       => 0,
            'numbers'          => 0,
            'enrol_data_count' => 0,
        );
        if (!empty($details)) {
            foreach ($details as $detail) {
                $summary['amount'] += $detail['amount'];
                $summary['amount_pay'] += $detail['amount_pay'];
                $summary['numbers'] += $detail['numbers'];
                $summary['enrol_data_count'] += $detail['enrol_data_count'];
            }
        }


        return $this->response_object(compact('summary', 'details'));
    }


}
