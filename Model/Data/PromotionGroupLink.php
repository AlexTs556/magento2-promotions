<?php

declare(strict_types=1);

namespace Kodano\Promotions\Model\Data;

use Kodano\Promotions\Api\Data\PromotionGroupLinkInterface;
use Magento\Framework\Model\AbstractModel;

class PromotionGroupLink extends AbstractModel implements PromotionGroupLinkInterface
{
    /**
     * Initialize the model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\Kodano\Promotions\Model\ResourceModel\PromotionGroupLink::class);
    }

    /**
     * Get the link ID.
     *
     * @return int|null
     */
    public function getLinkId(): ?int
    {
        return $this->getData(self::LINK_ID) === null ? null
            : (int)$this->getData(self::LINK_ID);
    }

    /**
     * Set the link ID.
     *
     * @param int $linkId
     * @return $this
     */
    public function setLinkId(int $linkId): static
    {
        return $this->setData(self::LINK_ID, $linkId);
    }

    /**
     * Get the promotion ID.
     *
     * @return int|null
     */
    public function getPromotionId(): ?int
    {
        return $this->getData(self::PROMOTION_ID) === null ? null
            : (int)$this->getData(self::PROMOTION_ID);
    }

    /**
     * Set the promotion ID.
     *
     * @param int $promotionId
     * @return $this
     */
    public function setPromotionId(int $promotionId): static
    {
        return $this->setData(self::PROMOTION_ID, $promotionId);
    }

    /**
     * Get the group ID.
     *
     * @return int|null
     */
    public function getGroupId(): ?int
    {
        return $this->getData(self::GROUP_ID) === null ? null
            : (int)$this->getData(self::GROUP_ID);
    }

    /**
     * Set the group ID.
     *
     * @param int $groupId
     * @return $this
     */
    public function setGroupId(int $groupId): static
    {
        return $this->setData(self::GROUP_ID, $groupId);
    }
}
