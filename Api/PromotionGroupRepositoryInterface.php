<?php

declare(strict_types=1);

namespace Kodano\Promotions\Api;

use Kodano\Promotions\Api\Data\PromotionGroupInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface PromotionGroupRepositoryInterface
{
    /**
     * Get list of promotion groups
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Kodano\Promotions\Api\Data\PromotionGroupInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array;

    /**
     * Get promotion group by ID
     *
     * @param int $groupId
     * @return \Kodano\Promotions\Api\Data\PromotionGroupInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $groupId): PromotionGroupInterface;

    /**
     * Save promotion group
     *
     * @param \Kodano\Promotions\Api\Data\PromotionGroupInterface $group
     * @return \Kodano\Promotions\Api\Data\PromotionGroupInterface
     */
    public function save(PromotionGroupInterface $group): PromotionGroupInterface;

    /**
     * Delete promotion group
     *
     * @param \Kodano\Promotions\Api\Data\PromotionGroupInterface $group
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(PromotionGroupInterface $group): bool;

    /**
     * Delete promotion group by ID
     *
     * @param int $groupId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $groupId): bool;
}
