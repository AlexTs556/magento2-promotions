<?php

declare(strict_types=1);

namespace Kodano\Promotions\Model\ResourceModel\PromotionGroupLink;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Kodano\Promotions\Model\Data\PromotionGroupLink;
use Kodano\Promotions\Model\ResourceModel\PromotionGroupLink as PromotionGroupLinkResource;

class Collection extends AbstractCollection
{
    /**
     * Define model and resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(PromotionGroupLink::class, PromotionGroupLinkResource::class);
    }
}
