<?php

declare(strict_types=1);

namespace Kodano\Promotions\Model\ResourceModel\Promotion;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Kodano\Promotions\Model\Data\Promotion as PromotionModel;
use Kodano\Promotions\Model\ResourceModel\Promotion as PromotionResource;

class Collection extends AbstractCollection
{
    /**
     * Define model and resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(PromotionModel::class, PromotionResource::class);
    }
}
