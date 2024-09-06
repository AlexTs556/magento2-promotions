<?php

declare(strict_types=1);

namespace Kodano\Promotions\Api\Data;

interface PromotionGroupInterface
{
    public const GROUP_ID = "group_id";
    public const NAME = "name";
    public const CREATED_AT = "created_at";
    public const UPDATED_AT = "updated_at";

    /**
     * Getter for GroupId.
     *
     * @return int|null
     */
    public function getGroupId(): ?int;

    /**
     * Setter for GroupId.
     *
     * @param int|null $groupId
     *
     * @return void
     */
    public function setGroupId(?int $groupId): void;

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
}
