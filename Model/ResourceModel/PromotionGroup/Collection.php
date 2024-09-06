<?php

declare(strict_types=1);

namespace Kodano\Promotions\Model\ResourceModel\PromotionGroup;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Kodano\Promotions\Model\Data\PromotionGroup as PromotionGroupModel;
use Kodano\Promotions\Model\ResourceModel\PromotionGroup as PromotionGroupResource;

class Collection extends AbstractCollection
{
    /**
     * Define model and resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(PromotionGroupModel::class, PromotionGroupResource::class);
    }
}
