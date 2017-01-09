<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once __DIR__ . '/../ModelBase.php';

class TextImageList_model extends ModelBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saveWxPicUrl($text_image_id, $params)
    {
        $params = $this->makeORMUpdateColumns($params);

        return $this->setTable($this->tableNameCardTextImageList)
                    ->addUpdateColumns($params)
                    ->addQueryConditions('pk_card_text_image_list', $text_image_id)
                    ->doUpdate();
    }
}
