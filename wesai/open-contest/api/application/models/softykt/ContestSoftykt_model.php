<?php

/**
 * User: zhaodc
 * Date: 05/12/2016
 * Time: 21:03
 */

require_once __DIR__ . '/../ModelBase.php';

class ContestSoftykt_model extends ModelBase
{

    /**
     * ContestSoftykt_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getByUnqKey($scenic_id, $product_id)
    {
        $result = $this->setTable($this->tableNameContestExtPartnerSoftykt)
                       ->addQueryConditions('scenicid', $scenic_id)
                       ->addQueryConditions('productid', $product_id)
                       ->doSelect();
        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    private function createContest($params)
    {
        return $this->setTable($this->tableNameContest)
                    ->addInsertColumns($params)
                    ->doInsert();
    }

    private function createContestItem($params)
    {
        return $this->setTable($this->tableNameContestItem)
                    ->addInsertColumns($params)
                    ->doInsert();
    }

    private function createContestSoftykt($params)
    {
        return $this->setTable($this->tableNameContestExtPartnerSoftykt)
                    ->addInsertColumns($params)
                    ->doInsert();
    }

    public function create($contestParams, $contestItemParams, $contestSoftyktParams)
    {
        try {
            $this->beginTransaction();

            $contestId = $this->createContest($contestParams);
            if (empty($contestId)) {
                $this->rollBack();

                return false;
            }

            $contestItemParams['fk_contest'] = $contestId;
            $itemId = $this->createContestItem($contestItemParams);
            if (empty($itemId)) {
                $this->rollBack();

                return false;
            }

            $contestSoftyktParams['fk_contest'] = $contestId;
            $contestSoftyktId = $this->createContestSoftykt($contestSoftyktParams);
            if (empty($contestSoftyktId)) {
                $this->rollBack();

                return false;
            }

            $this->commit();

            return $contestSoftyktId;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function modify($contestParams, $contestItemParams, $contestSoftyktParams)
    {
        try {
            $this->beginTransaction();

            $affectedRows = 0;
            if (!empty($contestParams)) {
                $pk_contest = $contestParams['pk_contest'];
                unset($contestParams['pk_contest']);
                $affectedRows += $this->updateContest($contestParams, $pk_contest);
            }

            if (!empty($contestItemParams)) {
                $pk_contest_items = $contestItemParams['pk_contest_items'];
                unset($contestItemParams['pk_contest_items']);
                $affectedRows += $this->updateContestItem($contestItemParams, $pk_contest_items);
            }

            if (!empty($contestSoftyktParams)) {
                $pk_contest_ext_partner_softykt = $contestSoftyktParams['pk_contest_ext_partner_softykt'];
                $affectedRows += $this->updateContestSoftykt($contestSoftyktParams, $pk_contest_ext_partner_softykt);
            }

            $this->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    private function getUpdateColumns($params)
    {
        $returnVal = [];
        foreach ($params as $key => $val) {
            $returnVal[] = [$key, $val, '='];
        }

        return $returnVal;
    }

    private function updateContest($params, $pk)
    {
        $params = $this->getUpdateColumns($params);

        return $this->setTable($this->tableNameContest)
                    ->addUpdateColumns($params)
                    ->addQueryConditions('pk_contest', $pk)
                    ->doUpdate();
    }

    private function updateContestItem($params, $pk)
    {
        $params = $this->getUpdateColumns($params);

        return $this->setTable($this->tableNameContestItem)
                    ->addUpdateColumns($params)
                    ->addQueryConditions('pk_contest_items', $pk)
                    ->doUpdate();
    }

    private function updateContestSoftykt($params, $pk)
    {
        $params = $this->getUpdateColumns($params);

        return $this->setTable($this->tableNameContestExtPartnerSoftykt)
                    ->addUpdateColumns($params)
                    ->addQueryConditions('pk_contest_ext_partner_softykt', $pk)
                    ->doUpdate();
    }

    public function getByContestId($cid)
    {
        $result = $this->setTable($this->tableNameContestExtPartnerSoftykt)
                       ->addQueryConditions('fk_contest', $cid)
                       ->doSelect();

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

}
