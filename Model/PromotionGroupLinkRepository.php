<?php

declare(strict_types=1);

namespace Kodano\Promotions\Model;

use Kodano\Promotions\Api\Data\PromotionGroupLinkInterface;
use Kodano\Promotions\Api\PromotionGroupLinkRepositoryInterface;
use Kodano\Promotions\Model\Data\PromotionGroupLinkFactory;
use Kodano\Promotions\Model\ResourceModel\PromotionGroupLink as ResourcePromotionGroupLink;
use Kodano\Promotions\Model\ResourceModel\PromotionGroupLink\CollectionFactory as PromotionGroupLinkCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;

class PromotionGroupLinkRepository implements PromotionGroupLinkRepositoryInterface
{
    /**
     * @param ResourcePromotionGroupLink $resource
     * @param PromotionGroupLinkFactory $promotionGroupLinkFactory
     * @param PromotionGroupLinkCollectionFactory $promotionGroupLinkCollectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessor $collectionProcessor
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        private readonly ResourcePromotionGroupLink $resource,
        private readonly PromotionGroupLinkFactory $promotionGroupLinkFactory,
        private readonly PromotionGroupLinkCollectionFactory $promotionGroupLinkCollectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessor $collectionProcessor,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly FilterBuilder $filterBuilder
    ) {
    }

    /**
     * Get a list of promotion group links by search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return PromotionGroupLinkInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array
    {
        $collection = $this->promotionGroupLinkCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults->getItems();
    }

    /**
     * Save promotion group link.
     *
     * @param PromotionGroupLinkInterface $promotionGroupLink
     * @return PromotionGroupLinkInterface
     * @throws CouldNotSaveException
     */
    public function save(PromotionGroupLinkInterface $promotionGroupLink): PromotionGroupLinkInterface
    {
        try {
            $this->resource->save($promotionGroupLink);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Unable to save promotion group link: %1', $exception->getMessage()));
        }

        return $promotionGroupLink;
    }

    /**
     * Get promotion group links by promotion ID.
     *
     * @param int $promotionId
     * @return PromotionGroupLinkInterface[]
     * @throws InputException
     */
    public function getByPromotionId(int $promotionId): array
    {
        if (!$promotionId) {
            throw new InputException(__('Promotion ID is required.'));
        }

        $filter = $this->filterBuilder
            ->setField('promotion_id')
            ->setValue($promotionId)
            ->setConditionType('eq')
            ->create();

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilters([$filter])
            ->create();

        return $this->getList($searchCriteria);
    }

    /**
     * Get promotion group link by value and field.
     *
     * @param int $value
     * @param string|null $field
     * @return PromotionGroupLinkInterface
     * @throws NoSuchEntityException
     */
    public function get(int $value, string $field = null): PromotionGroupLinkInterface
    {
        $promotionGroupLink = $this->promotionGroupLinkFactory->create();
        $this->resource->load($promotionGroupLink, $value, $field);

        if (!$promotionGroupLink->getLinkId()) {
            $field = $field ?? PromotionGroupLinkInterface::LINK_ID;
            throw new NoSuchEntityException(
                __('Link with field "%1" and value "%2" does not exist.', $field, $value)
            );
        }

        return $promotionGroupLink;
    }

    /**
     * Delete a promotion group link by value and field.
     *
     * @param int $value
     * @param string|null $field
     * @return bool
     * @throws LocalizedException
     */
    public function delete(int $value, string $field = null): bool
    {
        $promotionGroupLink = $this->get($value, $field);
        try {
            $this->resource->delete($promotionGroupLink);
        } catch (\Exception $e) {
            throw new LocalizedException(__('Unable to delete promotion group link: %1', $e->getMessage()));
        }

        return true;
    }

    /**
     * Delete promotion group links by promotion ID.
     *
     * @param int $promotionId
     * @return bool
     * @throws InputException
     * @throws LocalizedException
     */
    public function deleteByPromotionId(int $promotionId): bool
    {
        if (!$promotionId) {
            throw new InputException(__('Promotion ID is required.'));
        }

        $promotionGroupLinks = $this->getByPromotionId($promotionId);

        foreach ($promotionGroupLinks as $promotionGroupLink) {
            $this->delete($promotionGroupLink->getLinkId());
        }

        return true;
    }
}
