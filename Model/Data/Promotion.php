<?php

declare(strict_types=1);

namespace Kodano\Promotions\Model\Data;

use Kodano\Promotions\Api\Data\PromotionInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Promotion extends AbstractExtensibleModel implements PromotionInterface
{
    /**
     * Initialize the model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\Kodano\Promotions\Model\ResourceModel\Promotion::class);
    }

    /**
     * Getter for PromotionId.
     *
     * @return int|null
     */
    public function getPromotionId(): ?int
    {
        return $this->getData(self::PROMOTION_ID) === null ? null
            : (int)$this->getData(self::PROMOTION_ID);
    }

    /**
     * Setter for PromotionId.
     *
     * @param int|null $promotionId
     *
     * @return void
     */
    public function setPromotionId(?int $promotionId): void
    {
        $this->setData(self::PROMOTION_ID, $promotionId);
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

    /**
     * @inheritdoc
     *
     * @return \Magento\Framework\Api\ExtensionAttributesInterface
     */
    public function getExtensionAttributes(): \Magento\Framework\Api\ExtensionAttributesInterface
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritdoc
     *
     * @param \Kodano\Promotions\Api\Data\PromotionExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Kodano\Promotions\Api\Data\PromotionExtensionInterface $extensionAttributes
    ): static {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
