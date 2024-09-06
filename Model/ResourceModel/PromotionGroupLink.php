<?php

declare(strict_types=1);

namespace Kodano\Promotions\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PromotionGroupLink extends AbstractDb
{
    /**
     * Define main table and primary key
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('kodano_promotion_group_link', 'link_id');
    }
}
