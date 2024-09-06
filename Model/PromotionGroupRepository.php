<?php

declare(strict_types=1);

namespace Kodano\Promotions\Model;

use Kodano\Promotions\Api\PromotionGroupRepositoryInterface;
use Kodano\Promotions\Api\Data\PromotionGroupInterface;
use Kodano\Promotions\Model\Data\PromotionGroupFactory;
use Kodano\Promotions\Model\ResourceModel\PromotionGroup as PromotionGroupResource;
use Kodano\Promotions\Model\ResourceModel\PromotionGroup\CollectionFactory as PromotionGroupCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchResultsInterfaceFactory;

class PromotionGroupRepository implements PromotionGroupRepositoryInterface
{
    /**
     * Constructor.
     *
     * @param PromotionGroupResource $groupResource
     * @param PromotionGroupFactory $groupFactory
     * @param PromotionGroupCollectionFactory $groupCollectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessor $collectionProcessor
     */
    public function __construct(
        private readonly PromotionGroupResource $groupResource,
        private readonly PromotionGroupFactory $groupFactory,
        private readonly PromotionGroupCollectionFactory $groupCollectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessor $collectionProcessor
    ) {
    }

    /**
     * Get list of promotion groups based on search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Kodano\Promotions\Api\Data\PromotionGroupInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array
    {
        $collection = $this->groupCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults->getItems() ?: [];
    }

    /**
     * Get promotion group by ID.
     *
     * @param int $groupId
     * @return PromotionGroupInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $groupId): PromotionGroupInterface
    {
        $group = $this->groupFactory->create();
        $this->groupResource->load($group, $groupId);

        if (!$group->getGroupId()) {
            throw new NoSuchEntityException(__('Promotion group with ID "%1" does not exist.', $groupId));
        }

        return $group;
    }

    /**
     * Save promotion group.
     *
     * @param PromotionGroupInterface $group
     * @return PromotionGroupInterface
     * @throws CouldNotSaveException
     */
    public function save(PromotionGroupInterface $group): PromotionGroupInterface
    {
        try {
            $this->groupResource->save($group);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $group;
    }

    /**
     * Delete promotion group.
     *
     * @param PromotionGroupInterface $group
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(PromotionGroupInterface $group): bool
    {
        try {
            $this->groupResource->delete($group);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the promotion group: %1', $exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete promotion group by ID.
     *
     * @param int $groupId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $groupId): bool
    {
        $group = $this->getById($groupId);
        return $this->delete($group);
    }
}
