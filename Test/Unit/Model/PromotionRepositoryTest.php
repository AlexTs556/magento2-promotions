<?php

declare(strict_types=1);

namespace Kodano\Promotions\Test\Unit\Model;

use Kodano\Promotions\Api\Data\PromotionExtensionInterface;
use Kodano\Promotions\Api\Data\PromotionInterface;
use Magento\Framework\Api\SearchResultsInterface as PromotionSearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory as PromotionSearchResultsInterfaceFactory;
use Kodano\Promotions\Model\Data\Promotion;
use Kodano\Promotions\Model\Data\PromotionFactory;
use Kodano\Promotions\Model\Data\PromotionGroupLinkFactory;
use Kodano\Promotions\Model\PromotionGroupLinkRepository;
use Kodano\Promotions\Model\PromotionRepository;
use Kodano\Promotions\Model\ResourceModel\Promotion as PromotionResource;
use Kodano\Promotions\Model\ResourceModel\Promotion\Collection;
use Kodano\Promotions\Model\ResourceModel\Promotion\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PromotionRepositoryTest extends TestCase
{
    /**
     * @var PromotionRepository
     */
    private PromotionRepository $repository;

    /**
     * @var MockObject|PromotionResource
     */
    private $promotionResource;

    /**
     * @var MockObject|PromotionInterface
     */
    private $promotion;

    /**
     * @var MockObject|PromotionFactory
     */
    private $promotionFactory;

    /**
     * @var MockObject|PromotionSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var MockObject|CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var MockObject|PromotionGroupLinkRepository
     */
    private $promotionGroupLinkRepository;

    /**
     * @var MockObject|PromotionGroupLinkFactory
     */
    private $promotionGroupLinkFactory;

    /**
     * @var MockObject|CollectionFactory
     */
    private $promotionCollectionFactory;

    protected function setUp(): void
    {
        $this->promotionResource = $this->createMock(PromotionResource::class);
        $this->searchResultsFactory = $this->createMock(PromotionSearchResultsInterfaceFactory::class);
        $this->collectionProcessor = $this->createMock(CollectionProcessor::class);
        $this->promotionGroupLinkRepository = $this->createMock(PromotionGroupLinkRepository::class);
        $this->promotionGroupLinkFactory = $this->createMock(PromotionGroupLinkFactory::class);
        $this->promotion = $this->createMock(Promotion::class);
        $this->promotionCollectionFactory = $this->createMock(CollectionFactory::class);
        $this->promotionFactory = $this->createPartialMock(PromotionFactory::class, ['create']);

        $this->repository = new PromotionRepository(
            $this->promotionResource,
            $this->promotionFactory,
            $this->promotionCollectionFactory,
            $this->searchResultsFactory,
            $this->collectionProcessor,
            $this->promotionGroupLinkRepository,
            $this->promotionGroupLinkFactory
        );
    }

    /**
     * Test the save method of the PromotionRepository.
     *
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testSave()
    {
        // Mock PromotionResource to expect a save method call with the Promotion object
        $this->promotionResource->expects($this->once())
            ->method('save')
            ->with($this->promotion)
            ->willReturn($this->promotion);

        // Set up extension attributes for the Promotion object
        $extensionAttributes = $this->createMock(PromotionExtensionInterface::class);
        $extensionAttributes->expects($this->once())
            ->method('getGroupLinks')
            ->willReturn([]); // Return expected data

        // Associate the extension attributes with the Promotion object
        $this->promotion->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($extensionAttributes);

        // Verify that the save method works correctly
        $this->assertEquals($this->promotion, $this->repository->save($this->promotion));
    }

    /**
     * Test the getById method of the repository.
     *
     * @return void
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function testGetById()
    {
        $id = 1;

        // Mock for the Promotion model
        $promotionMock = $this->createPartialMock(
            Promotion::class,
            [
                'load',
                'getPromotionId',
                'getExtensionAttributes',
                'setExtensionAttributes'
            ]
        );
        // Expect the load method to be called with $id and return self
        $promotionMock->expects($this->any())->method('load')->with($id)->willReturnSelf();
        // Expect the getPromotionId method to return $id
        $promotionMock->expects($this->any())->method('getPromotionId')->willReturn($id);

        // Mock for extension attributes
        $extensionAttributesMock = $this->createMock(PromotionExtensionInterface::class);

        // Expect the getExtensionAttributes() method to return the mock
        $promotionMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($extensionAttributesMock);

        // Expect the setExtensionAttributes() method to receive the mock as a parameter
        $promotionMock->expects($this->once())
            ->method('setExtensionAttributes')
            ->with($extensionAttributesMock);

        // Configure the PromotionFactory to return the mock Promotion object
        $this->promotionFactory->expects($this->once())->method('create')->willReturn($promotionMock);

        // Assert that the getById method returns the Promotion object
        $this->assertEquals($promotionMock, $this->repository->getById($id));
    }

    /**
     * Test the getList method of the PromotionRepository.
     *
     * @return void
     * @throws InputException
     */
    public function testGetList()
    {
        // Mock SearchCriteria
        $searchCriteria = $this->createMock(SearchCriteriaInterface::class);

        // Mock the Promotion collection
        $collection = $this->createMock(Collection::class);

        // Return the collection when the CollectionFactory is called
        $this->promotionCollectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($collection);

        // Mock the SearchResults
        $searchResults = $this->createMock(PromotionSearchResultsInterface::class);

        // Expect the SearchResultsFactory to create the SearchResults object
        $this->searchResultsFactory->expects($this->once())
            ->method('create')
            ->willReturn($searchResults);

        // Mock CollectionProcessor to process the collection with the search criteria
        $this->collectionProcessor->expects($this->once())
            ->method('process')
            ->with($searchCriteria, $collection);

        // Mock the items in the collection
        $promotionItems = [$this->promotion];
        $collection->expects($this->once())
            ->method('getItems')
            ->willReturn($promotionItems);

        // Mock the size of the collection
        $collection->expects($this->once())
            ->method('getSize')
            ->willReturn(1);

        // Set expectations for the SearchResults object
        $searchResults->expects($this->once())
            ->method('setSearchCriteria')
            ->with($searchCriteria);

        $searchResults->expects($this->once())
            ->method('setItems')
            ->with($promotionItems);

        $searchResults->expects($this->once())
            ->method('setTotalCount')
            ->with(1);

        // Ensure getItems() returns the correct mock items
        $searchResults->expects($this->once())
            ->method('getItems')
            ->willReturn($promotionItems);

        // Mock the ExtensionAttributes
        $extensionAttributes = $this->createMock(PromotionExtensionInterface::class);

        // Expect that the ExtensionAttributes object is returned by the Promotion
        $this->promotion->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($extensionAttributes);

        // Mock the setGroupLinks method on the ExtensionAttributes
        $extensionAttributes->expects($this->once())
            ->method('setGroupLinks')
            ->with([]); // Adjust as needed

        // Mock the setExtensionAttributes method on the Promotion
        $this->promotion->expects($this->once())
            ->method('setExtensionAttributes')
            ->with($extensionAttributes);

        // Call the method and verify the result
        $result = $this->repository->getList($searchCriteria);

        $this->assertEquals($promotionItems, $result);
    }

    /**
     * Test the delete method of the PromotionRepository.
     *
     * @throws CouldNotDeleteException
     */
    public function testDelete()
    {
        // Mock the PromotionResource to expect a delete method call with the Promotion object
        $this->promotionResource->expects($this->once())
            ->method('delete')
            ->with($this->promotion)
            ->willReturnSelf(); // Return self to mimic the real delete behavior

        // Call the delete method and assert the result
        $result = $this->repository->delete($this->promotion);
        $this->assertTrue($result);
    }

    /**
     * Test the delete method of the PromotionRepository with an exception.
     *
     * @throws CouldNotDeleteException
     */
    public function testDeleteWithException()
    {
        // Configure the PromotionResource to throw an exception when delete is called
        $this->promotionResource->expects($this->once())
            ->method('delete')
            ->with($this->promotion)
            ->will($this->throwException(new \Exception('Deletion error')));

        // Assert that a CouldNotDeleteException is thrown
        $this->expectException(CouldNotDeleteException::class);
        $this->expectExceptionMessage('Could not delete the promotion: Deletion error');

        // Call the delete method
        $this->repository->delete($this->promotion);
    }

    /**
     * Test the deleteById method of the PromotionRepository.
     *
     * @return void
     * @throws CouldNotDeleteException
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function testDeleteById()
    {
        $promotionId = 1; // ID for testing

        // Create a mock for Promotion
        $promotionMock = $this->createMock(Promotion::class);

        // Create a mock for PromotionRepository, replacing only the getById method
        $repositoryMock = $this->getMockBuilder(PromotionRepository::class)
            ->setConstructorArgs([
                $this->promotionResource,
                $this->promotionFactory,
                $this->promotionCollectionFactory,
                $this->searchResultsFactory,
                $this->collectionProcessor,
                $this->promotionGroupLinkRepository,
                $this->promotionGroupLinkFactory
            ])
            ->onlyMethods(['getById'])
            ->getMock();

        // Set up the mock for the getById method
        $repositoryMock->expects($this->once())
            ->method('getById')
            ->with($promotionId)
            ->willReturn($promotionMock);

        // Set up the mock for the delete method in PromotionResource
        $this->promotionResource->expects($this->once())
            ->method('delete')
            ->with($promotionMock)
            ->willReturn(true); // Simulate successful deletion

        // Check that the deleteById method returns true
        $result = $repositoryMock->deleteById($promotionId);
        $this->assertTrue($result);
    }
}
