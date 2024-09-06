<?php

declare(strict_types=1);

namespace Kodano\Promotions\Api;

use Kodano\Promotions\Api\Data\PromotionInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface PromotionRepositoryInterface
{
    /**
     * Get list of promotions
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Kodano\Promotions\Api\Data\PromotionInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array;

    /**
     * Get promotion by ID
     *
     * @param int $promotionId
     * @return \Kodano\Promotions\Api\Data\PromotionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $promotionId): PromotionInterface;

    /**
     * Save promotion
     *
     * @param \Kodano\Promotions\Api\Data\PromotionInterface $promotion
     * @return \Kodano\Promotions\Api\Data\PromotionInterface
     */
    public function save(PromotionInterface $promotion): PromotionInterface;

    /**
     * Delete promotion
     *
     * @param \Kodano\Promotions\Api\Data\PromotionInterface $promotion
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(PromotionInterface $promotion): bool;

    /**
     * Delete promotion by ID
     *
     * @param int $promotionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $promotionId): bool;
}
