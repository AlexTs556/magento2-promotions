<?php

declare(strict_types=1);

namespace Kodano\Promotions\Model;

use Kodano\Promotions\Api\PromotionRepositoryInterface;
use Kodano\Promotions\Api\Data\PromotionInterface;
use Kodano\Promotions\Model\Data\PromotionFactory;
use Kodano\Promotions\Model\Data\PromotionGroupLinkFactory;
use Kodano\Promotions\Model\ResourceModel\Promotion as PromotionResource;
use Kodano\Promotions\Model\ResourceModel\Promotion\CollectionFactory as PromotionCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;

class PromotionRepository implements PromotionRepositoryInterface
{
    /**
     * Constructor.
     *
     * @param PromotionResource $promotionResource
     * @param PromotionFactory $promotionFactory
     * @param PromotionCollectionFactory $promotionCollectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessor $collectionProcessor
     * @param PromotionGroupLinkRepository $promotionGroupLinkRepository
     * @param PromotionGroupLinkFactory $promotionGroupLinkFactory
     */
    public function __construct(
        private readonly PromotionResource $promotionResource,
        private readonly PromotionFactory $promotionFactory,
        private readonly PromotionCollectionFactory $promotionCollectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessor  $collectionProcessor,
        private readonly PromotionGroupLinkRepository $promotionGroupLinkRepository,
        private readonly PromotionGroupLinkFactory $promotionGroupLinkFactory
    ) {
    }

    /**
     * Get a list of promotions based on search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Kodano\Promotions\Api\Data\PromotionInterface[]
     * @throws InputException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array
    {
        $collection = $this->promotionCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        $promotionItems = $searchResults->getItems();
        foreach ($promotionItems as $promotionItem) {
            $this->setExtensionAttributes($promotionItem);
        }

        return $promotionItems ?: [];
    }

    /**
     * Get promotion by ID.
     *
     * @param int $promotionId
     * @return PromotionInterface
     * @throws NoSuchEntityException|InputException
     */
    public function getById(int $promotionId): PromotionInterface
    {
        $promotion = $this->promotionFactory->create();
        $this->promotionResource->load($promotion, $promotionId);

        if (!$promotion->getPromotionId()) {
            throw new NoSuchEntityException(__('Promotion with ID "%1" does not exist.', $promotionId));
        }

        return $this->setExtensionAttributes($promotion);
    }

    /**
     * Set extension attributes for a promotion.
     *
     * @param PromotionInterface $promotion
     * @return PromotionInterface
     * @throws InputException
     */
    private function setExtensionAttributes(PromotionInterface $promotion): PromotionInterface
    {
        $promotionGroupLinks = $this->promotionGroupLinkRepository->getByPromotionId((int)$promotion->getPromotionId());
        $extensionAttributes = $promotion->getExtensionAttributes();

        // Add group links to extension attributes
        $groupLinks = [];
        foreach ($promotionGroupLinks as $link) {
            $groupLinks[] = $link->getGroupId();
        }
        $extensionAttributes->setGroupLinks($groupLinks);
        $promotion->setExtensionAttributes($extensionAttributes);

        return $promotion;
    }

    /**
     * Save promotion.
     *
     * @param PromotionInterface $promotion
     * @return PromotionInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function save(PromotionInterface $promotion): PromotionInterface
    {
        try {
            $this->promotionResource->save($promotion);
            $this->manageGroupLinks($promotion);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $promotion;
    }

    /**
     * Manage group links for the promotion (delete existing and save new ones).
     *
     * @param PromotionInterface $promotion
     * @throws LocalizedException
     */
    private function manageGroupLinks(PromotionInterface $promotion): void
    {
        $promotionGroupLinkRepository = $this->promotionGroupLinkRepository;

        try {
            // Check if links exist for the promotion
            $linkExists = true;
            $promotionGroupLinkRepository->get((int)$promotion->getPromotionId(), PromotionInterface::PROMOTION_ID);
        } catch (NoSuchEntityException $exception) {
            $linkExists = false;
        }

        // Delete existing links if present
        if ($linkExists) {
            try {
                $promotionGroupLinkRepository->deleteByPromotionId((int)$promotion->getPromotionId());
            } catch (\Exception $e) {
                throw new LocalizedException(__($e->getMessage()));
            }
        }

        // Save new group links if any
        $this->saveGroupLinks($promotion);
    }

    /**
     * Save group links for the promotion.
     *
     * @param PromotionInterface $promotion
     * @throws LocalizedException
     */
    private function saveGroupLinks(PromotionInterface $promotion): void
    {
        $groupLinks = $promotion->getExtensionAttributes()->getGroupLinks();

        if ($groupLinks) {
            foreach ($groupLinks as $groupLink) {
                $promotionGroupLink = $this->promotionGroupLinkFactory->create();
                $promotionGroupLink->setPromotionId((int)$promotion->getPromotionId());
                $promotionGroupLink->setGroupId((int)$groupLink);
                $this->promotionGroupLinkRepository->save($promotionGroupLink);
            }
        }
    }

    /**
     * Delete promotion.
     *
     * @param PromotionInterface $promotion
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(PromotionInterface $promotion): bool
    {
        try {
            $this->promotionResource->delete($promotion);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the promotion: %1', $exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete promotion by ID.
     *
     * @param int $promotionId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException|InputException
     */
    public function deleteById(int $promotionId): bool
    {
        $promotion = $this->getById($promotionId);
        return $this->delete($promotion);
    }
}
