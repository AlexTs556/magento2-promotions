<?php

declare(strict_types=1);

namespace Kodano\Promotions\Api;

use Kodano\Promotions\Api\Data\PromotionGroupLinkInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;

interface PromotionGroupLinkRepositoryInterface
{
    /**
     * Get list of promotion groups
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Kodano\Promotions\Api\Data\PromotionGroupLinkInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array;

    /**
     * Save PromotionGroupLink
     *
     * @param \Kodano\Promotions\Api\Data\PromotionGroupLinkInterface $promotionGroupLink
     * @return \Kodano\Promotions\Api\Data\PromotionGroupLinkInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(PromotionGroupLinkInterface $promotionGroupLink): PromotionGroupLinkInterface;

    /**
     * Retrieve PromotionGroupLink by ID
     *
     * @param int $value
     * @param string|null $field
     * @return PromotionGroupLinkInterface
     */
    public function get(int $value, string $field = null): PromotionGroupLinkInterface;

    /**
     * Delete
     *
     * @param int $value
     * @param string|null $field
     * @return bool
     */
    public function delete(int $value, string $field = null): bool;

    /**
     * Get By PromotionId
     *
     * @param int $promotionId
     * @return \Kodano\Promotions\Api\Data\PromotionGroupLinkInterface[]
     * @throws InputException
     */
    public function getByPromotionId(int $promotionId): array;

    /**
     * Delete By PromotionId
     *
     * @param int $promotionId
     * @return bool
     * @throws InputException
     * @throws LocalizedException
     */
    public function deleteByPromotionId(int $promotionId): bool;
}
