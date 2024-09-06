<?php

declare(strict_types=1);

namespace Kodano\Promotions\Test\Unit\Model;

use Kodano\Promotions\Api\Data\PromotionGroupLinkInterface;
use Magento\Framework\Api\SearchResultsInterface as PromotionGroupLinkSearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory as PromotionGroupLinkSearchResultsInterfaceFactory;
use Kodano\Promotions\Api\PromotionGroupLinkRepositoryInterface;
use Kodano\Promotions\Model\Data\PromotionGroupLink as ModelPromotionGroupLink;
use Kodano\Promotions\Model\Data\PromotionGroupLinkFactory;
use Kodano\Promotions\Model\PromotionGroupLinkRepository;
use Kodano\Promotions\Model\ResourceModel\PromotionGroupLink;
use Kodano\Promotions\Model\ResourceModel\PromotionGroupLink\Collection;
use Kodano\Promotions\Model\ResourceModel\PromotionGroupLink\CollectionFactory as PromotionGroupLinkCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\FilterBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Framework\Api\Filter;

/**
 * Unit test for PromotionGroupLinkRepository
 */
class PromotionGroupLinkRepositoryTest extends TestCase
{
    /** @var PromotionGroupLinkRepositoryInterface */
    private PromotionGroupLinkRepositoryInterface $repository;

    /** @var MockObject|PromotionGroupLink */
    private $resourceMock;

    /** @var MockObject|PromotionGroupLinkFactory */
    private $promotionGroupLinkFactoryMock;

    /** @var MockObject|PromotionGroupLinkCollectionFactory */
    private $promotionGroupLinkCollectionFactoryMock;

    /** @var MockObject|PromotionGroupLinkSearchResultsInterfaceFactory */
    private $searchResultsFactoryMock;

    /** @var MockObject|CollectionProcessor */
    private $collectionProcessorMock;

    /** @var MockObject|SearchCriteriaBuilder */
    private $searchCriteriaBuilderMock;

    /** @var MockObject|FilterBuilder */
    private $filterBuilderMock;

    /**
     * Set up mocks and repository instance before each test
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->resourceMock = $this->createMock(PromotionGroupLink::class);
        $this->promotionGroupLinkFactoryMock = $this->createMock(PromotionGroupLinkFactory::class);
        $this->promotionGroupLinkCollectionFactoryMock = $this->createMock(PromotionGroupLinkCollectionFactory::class);
        $this->searchResultsFactoryMock = $this->createMock(PromotionGroupLinkSearchResultsInterfaceFactory::class);
        $this->collectionProcessorMock = $this->createMock(CollectionProcessor::class);
        $this->searchCriteriaBuilderMock = $this->createMock(SearchCriteriaBuilder::class);
        $this->filterBuilderMock = $this->createMock(FilterBuilder::class);

        $this->repository = new PromotionGroupLinkRepository(
            $this->resourceMock,
            $this->promotionGroupLinkFactoryMock,
            $this->promotionGroupLinkCollectionFactoryMock,
            $this->searchResultsFactoryMock,
            $this->collectionProcessorMock,
            $this->searchCriteriaBuilderMock,
            $this->filterBuilderMock
        );
    }

    /**
     * Test getList method for PromotionGroupLinkRepository
     *
     * @return void
     */
    public function testGetList(): void
    {
        $searchCriteriaMock = $this->createMock(SearchCriteriaInterface::class);

        $collectionMock = $this->createMock(Collection::class);
        $collectionMock->method('getItems')->willReturn([
            $this->createMock(PromotionGroupLinkInterface::class)
        ]);
        $collectionMock->method('getSize')->willReturn(1);

        $this->promotionGroupLinkCollectionFactoryMock
            ->method('create')
            ->willReturn($collectionMock);

        $this->collectionProcessorMock
            ->method('process')
            ->with($searchCriteriaMock, $collectionMock)
            ->willReturn(null);

        $searchResultsMock = $this->createMock(PromotionGroupLinkSearchResultsInterface::class);
        $searchResultsMock->method('setSearchCriteria')->willReturnSelf();
        $searchResultsMock->method('setItems')->willReturnSelf();
        $searchResultsMock->method('setTotalCount')->willReturnSelf();
        $searchResultsMock->method('getItems')->willReturn([
            $this->createMock(PromotionGroupLinkInterface::class)
        ]);

        $this->searchResultsFactoryMock
            ->method('create')
            ->willReturn($searchResultsMock);

        $result = $this->repository->getList($searchCriteriaMock);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(PromotionGroupLinkInterface::class, $result[0]);
    }

    /**
     * Test save method for PromotionGroupLinkRepository
     *
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testSave(): void
    {
        $promotionGroupLinkMock = $this->createMock(ModelPromotionGroupLink::class);

        $this->resourceMock
            ->expects($this->once())
            ->method('save')
            ->with($promotionGroupLinkMock);

        $result = $this->repository->save($promotionGroupLinkMock);

        $this->assertSame($promotionGroupLinkMock, $result);
    }

    /**
     * Test getByPromotionId method for PromotionGroupLinkRepository
     *
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     */
    public function testGetByPromotionId(): void
    {
        $promotionId = 1;

        $filterMock = $this->createMock(Filter::class);
        $filterMock->method('setField')->with('promotion_id')->willReturnSelf();
        $filterMock->method('setValue')->with($promotionId)->willReturnSelf();
        $filterMock->method('setConditionType')->with('eq')->willReturnSelf();

        $this->filterBuilderMock->method('setField')->with('promotion_id')->willReturnSelf();
        $this->filterBuilderMock->method('setValue')->with($promotionId)->willReturnSelf();
        $this->filterBuilderMock->method('setConditionType')->with('eq')->willReturnSelf();
        $this->filterBuilderMock->method('create')->willReturn($filterMock);

        $searchCriteriaMock = $this->createMock(SearchCriteriaInterface::class);

        $this->searchCriteriaBuilderMock
            ->method('addFilters')
            ->with([$filterMock])
            ->willReturnSelf();
        $this->searchCriteriaBuilderMock
            ->method('create')
            ->willReturn($searchCriteriaMock);

        $collectionMock = $this->createMock(Collection::class);
        $collectionMock->method('getItems')->willReturn([
            $this->createMock(PromotionGroupLinkInterface::class)
        ]);
        $collectionMock->method('getSize')->willReturn(1);

        $this->promotionGroupLinkCollectionFactoryMock
            ->method('create')
            ->willReturn($collectionMock);

        $this->collectionProcessorMock
            ->method('process')
            ->with($searchCriteriaMock, $collectionMock)
            ->willReturn(null);

        $searchResultsMock = $this->createMock(PromotionGroupLinkSearchResultsInterface::class);
        $searchResultsMock->method('setSearchCriteria')->willReturnSelf();
        $searchResultsMock->method('setItems')->willReturnSelf();
        $searchResultsMock->method('setTotalCount')->willReturnSelf();
        $searchResultsMock->method('getItems')->willReturn([
            $this->createMock(PromotionGroupLinkInterface::class)
        ]);

        $this->searchResultsFactoryMock
            ->method('create')
            ->willReturn($searchResultsMock);

        $result = $this->repository->getByPromotionId($promotionId);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(PromotionGroupLinkInterface::class, $result[0]);
    }

    /**
     * Test delete method for PromotionGroupLinkRepository
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testDelete(): void
    {
        $linkId = 1;

        $promotionGroupLinkMock = $this->createMock(ModelPromotionGroupLink::class);
        $promotionGroupLinkMock->method('getLinkId')->willReturn($linkId);

        $this->promotionGroupLinkFactoryMock
            ->method('create')
            ->willReturn($promotionGroupLinkMock);

        $this->resourceMock
            ->expects($this->once())
            ->method('delete')
            ->with($promotionGroupLinkMock);

        $result = $this->repository->delete($linkId);

        $this->assertTrue($result);
    }

    /**
     *  Test deleteByPromotionId method.
     *
     *  This test verifies that the repository can delete a promotion group link
     *  by the provided promotion ID, and it ensures that the collection is filtered
     *  and the delete method is invoked for each item in the collection.
     *
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testDeleteByPromotionId(): void
    {
        $promotionId = 1;
        $linkId = 1;

        // Create mock for PromotionGroupLink
        $promotionGroupLinkMock = $this->createMock(ModelPromotionGroupLink::class);
        $promotionGroupLinkMock->method('getLinkId')->willReturn($linkId);

        // Configure mock for PromotionGroupLinkFactory to return the PromotionGroupLink mock
        $this->promotionGroupLinkFactoryMock
            ->method('create')
            ->willReturn($promotionGroupLinkMock);

        // Create mock for the collection and configure it to return the PromotionGroupLink mock
        $collectionMock = $this->createMock(Collection::class);
        $collectionMock->method('getItems')->willReturn([$promotionGroupLinkMock]);

        // Configure the PromotionGroupLinkCollectionFactory mock to return the collection mock
        $this->promotionGroupLinkCollectionFactoryMock
            ->method('create')
            ->willReturn($collectionMock);

        // Create and configure a mock for Filter
        $filterMock = $this->createMock(Filter::class);
        $filterMock->method('setField')->willReturnSelf();
        $filterMock->method('setValue')->willReturnSelf();
        $filterMock->method('setConditionType')->willReturnSelf();

        // Configure the FilterBuilder mock to return the Filter mock
        $this->filterBuilderMock->method('setField')->willReturnSelf();
        $this->filterBuilderMock->method('setValue')->willReturnSelf();
        $this->filterBuilderMock->method('setConditionType')->willReturnSelf();
        $this->filterBuilderMock->method('create')->willReturn($filterMock);

        // Create mock for SearchCriteria
        $searchCriteriaMock = $this->createMock(SearchCriteriaInterface::class);

        // Configure SearchCriteriaBuilder to return the SearchCriteria mock
        $this->searchCriteriaBuilderMock
            ->method('addFilters')
            ->with([$filterMock])
            ->willReturnSelf(); // Return self for chaining method calls
        $this->searchCriteriaBuilderMock
            ->method('create')
            ->willReturn($searchCriteriaMock);

        // Configure CollectionProcessor to process the collection
        $this->collectionProcessorMock
            ->method('process')
            ->with($searchCriteriaMock, $collectionMock)
            ->willReturn(null);

        // Configure mock for SearchResultsInterface
        $searchResultsMock = $this->createMock(PromotionGroupLinkSearchResultsInterface::class);
        $searchResultsMock->method('setSearchCriteria')->willReturnSelf();
        $searchResultsMock->method('setItems')->willReturnSelf();
        $searchResultsMock->method('setTotalCount')->willReturnSelf();
        $searchResultsMock->method('getItems')->willReturn([$promotionGroupLinkMock]);

        // Configure SearchResultsFactory to return the SearchResultsInterface mock
        $this->searchResultsFactoryMock
            ->method('create')
            ->willReturn($searchResultsMock);

        // Configure resource mock to expect delete method call with the PromotionGroupLink mock
        $this->resourceMock
            ->expects($this->once())
            ->method('delete')
            ->with($promotionGroupLinkMock);

        // Execute the deleteByPromotionId method and assert the result is true
        $result = $this->repository->deleteByPromotionId($promotionId);

        $this->assertTrue($result);
    }
}
