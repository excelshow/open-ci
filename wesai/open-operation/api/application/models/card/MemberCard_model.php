<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once __DIR__ . '/../ModelBase.php';

class MemberCard_model extends ModelBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saveWxPicUrl($card_id, $params)
    {
        $params = $this->makeORMUpdateColumns($params);

        return $this->setTable($this->tableNameCardMemberCard)
                    ->addUpdateColumns($params)
                    ->addQueryConditions('fk_card', $card_id)
                    ->doUpdate();
    }
}
