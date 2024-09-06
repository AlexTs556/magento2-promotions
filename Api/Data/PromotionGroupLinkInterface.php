<?php

declare(strict_types=1);

namespace Kodano\Promotions\Api\Data;

interface PromotionGroupLinkInterface
{
    public const LINK_ID = 'link_id';
    public const PROMOTION_ID = 'promotion_id';
    public const GROUP_ID = 'group_id';

    /**
     * Get Link ID
     *
     * @return int|null
     */
    public function getLinkId(): ?int;

    /**
     * Set Link ID
     *
     * @param int $linkId
     * @return $this
     */
    public function setLinkId(int $linkId): static;

    /**
     * Get Promotion ID
     *
     * @return int|null
     */
    public function getPromotionId(): ?int;

    /**
     * Set Promotion ID
     *
     * @param int $promotionId
     * @return $this
     */
    public function setPromotionId(int $promotionId): static;

    /**
     * Get Group ID
     *
     * @return int|null
     */
    public function getGroupId(): ?int;

    /**
     * Set Group ID
     *
     * @param int $groupId
     * @return $this
     */
    public function setGroupId(int $groupId): static;
}
