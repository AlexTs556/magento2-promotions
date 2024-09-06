<?php

declare(strict_types=1);

namespace Kodano\Promotions\Model\Data;

use Kodano\Promotions\Api\Data\PromotionGroupInterface;
use Magento\Framework\Model\AbstractModel;

class PromotionGroup extends AbstractModel implements PromotionGroupInterface
{
    /**
     * Initialize the model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\Kodano\Promotions\Model\ResourceModel\PromotionGroup::class);
    }

    /**
     * Getter for GroupId.
     *
     * @return int|null
     */
    public function getGroupId(): ?int
    {
        return $this->getData(self::GROUP_ID) === null ? null
            : (int)$this->getData(self::GROUP_ID);
    }

    /**
     * Setter for GroupId.
     *
     * @param int|null $groupId
     *
     * @return void
     */
    public function setGroupId(?int $groupId): void
    {
        $this->setData(self::GROUP_ID, $groupId);
    }

    /**
     * Getter for Name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * Setter for Name.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * Getter for CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Setter for CreatedAt.
     *
     * @param string|null $createdAt
     *
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void
    {
        $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Getter for UpdatedAt.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Setter for UpdatedAt.
     *
     * @param string|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
