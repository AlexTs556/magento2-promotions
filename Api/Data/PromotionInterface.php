<?php

declare(strict_types=1);

namespace Kodano\Promotions\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface PromotionInterface extends ExtensibleDataInterface
{
    public const PROMOTION_ID = "promotion_id";
    public const NAME = "name";
    public const CREATED_AT = "created_at";
    public const UPDATED_AT = "updated_at";

    /**
     * Getter for PromotionId.
     *
     * @return int|null
     */
    public function getPromotionId(): ?int;

    /**
     * Setter for PromotionId.
     *
     * @param int|null $promotionId
     *
     * @return void
     */
    public function setPromotionId(?int $promotionId): void;

    /**
     * Getter for Name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Setter for Name.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function setName(?string $name): void;

    /**
     * Getter for CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Setter for CreatedAt.
     *
     * @param string|null $createdAt
     *
     * @return void
     */
    public function setCreatedAt(?string $createdAt): void;

    /**
     * Getter for UpdatedAt.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Setter for UpdatedAt.
     *
     * @param string|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(?string $updatedAt): void;

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Kodano\Promotions\Api\Data\PromotionExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Kodano\Promotions\Api\Data\PromotionExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Kodano\Promotions\Api\Data\PromotionExtensionInterface $extensionAttributes
    ): static;
}
