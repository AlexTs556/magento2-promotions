<?php

declare(strict_types=1);

namespace Kodano\Promotions\Test\Unit\Model;

use Kodano\Promotions\Api\Data\PromotionGroupInterface;
use Kodano\Promotions\Model\Data\PromotionGroup;
use Kodano\Promotions\Model\Data\PromotionGroupFactory;
use Kodano\Promotions\Model\PromotionGroupRepository;
use Kodano\Promotions\Model\ResourceModel\PromotionGroup as PromotionGroupResource;
use Kodano\Promotions\Model\ResourceModel\PromotionGroup\Collection;
use Kodano\Promotions\Model\ResourceModel\PromotionGroup\CollectionFactory as PromotionGroupCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory as PromotionGroupSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Api\SearchResults;

class PromotionGroupRepositoryTest extends TestCase
{
    /** @var PromotionGroupResource|\PHPUnit\Framework\MockObject\MockObject */
    private $groupResourceMock;

    /** @var PromotionGroupFactory|\PHPUnit\Framework\MockObject\MockObject */
    private $groupFactoryMock;

    /** @var PromotionGroupCollectionFactory|\PHPUnit\Framework\MockObject\MockObject */
    private $groupCollectionFactoryMock;

    /** @var PromotionGroupSearchResultsInterfaceFactory|\PHPUnit\Framework\MockObject\MockObject */
    private $searchResultsFactoryMock;

    /** @var CollectionProcessor|\PHPUnit\Framework\MockObject\MockObject */
    private $collectionProcessorMock;

    /** @var PromotionGroupRepository */
    private $repository;

    protected function setUp(): void
    {
        // Create mocks for the required classes
        $this->groupResourceMock = $this->createMock(PromotionGroupResource::class);
        $this->groupCollectionFactoryMock = $this->createMock(PromotionGroupCollectionFactory::class);
        $this->searchResultsFactoryMock = $this->createMock(PromotionGroupSearchResultsInterfaceFactory::class);
        $this->collectionProcessorMock = $this->createMock(CollectionProcessor::class);
        $this->groupFactoryMock = $this->createPartialMock(PromotionGroupFactory::class, ['create']);

        // Initialize the repository with mocks
        $this->repository = new PromotionGroupRepository(
            $this->groupResourceMock,
            $this->groupFactoryMock,
            $this->groupCollectionFactoryMock,
            $this->searchResultsFactoryMock,
            $this->collectionProcessorMock
        );
    }

    public function testGetList()
    {
        // Mock the SearchCriteriaInterface and related classes
        $searchCriteriaMock = $this->createMock(SearchCriteriaInterface::class);
        $collectionMock = $this->createMock(Collection::class);
        $searchResultsMock = $this->createMock(SearchResults::class);

        // Configure the mocks
        $this->groupCollectionFactoryMock->method('create')->willReturn($collectionMock);
        $this->collectionProcessorMock
            ->expects($this->once())
            ->method('process')
            ->with($searchCriteriaMock, $collectionMock);
        $this->searchResultsFactoryMock->method('create')->willReturn($searchResultsMock);
        $collectionMock->method('getItems')->willReturn([]);
        $collectionMock->method('getSize')->willReturn(0);

        // Test the getList method
        $result = $this->repository->getList($searchCriteriaMock);
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetById()
    {
        $id = 1;

        // Create a partial mock for the PromotionGroup model
        $groupMock = $this->createPartialMock(PromotionGroup::class, ['load', 'getGroupId']);

        // Configure the mock methods
        $groupMock->expects($this->any())->method('load')->with($id)->willReturnSelf();
        $groupMock->expects($this->any())->method('getGroupId')->willReturn($id);
        $this->groupFactoryMock->expects($this->once())->method('create')->willReturn($groupMock);

        // Test the getById method
        $this->assertEquals($groupMock, $this->repository->getById($id));
    }

    public function testGetByIdThrowsNoSuchEntityException()
    {
        $groupId = 154;

        // Create a mock for the PromotionGroup model
        $groupMock = $this->createMock(PromotionGroup::class);

        // Configure the mocks
        $this->groupFactoryMock->method('create')->willReturn($groupMock);
        $this->groupResourceMock->expects($this->once())->method('load')->with($groupMock, $groupId);
        $groupMock->method('getGroupId')->willReturn(null);

        // Expect NoSuchEntityException to be thrown
        $this->expectException(NoSuchEntityException::class);
        $this->repository->getById($groupId);
    }

    public function testDelete()
    {
        // Create a mock for the PromotionGroup model
        $groupMock = $this->createMock(PromotionGroup::class);

        // Configure the mock
        $this->groupResourceMock->expects($this->once())->method('delete')->with($groupMock);

        // Test the delete method
        $result = $this->repository->delete($groupMock);
        $this->assertTrue($result);
    }

    public function testSave()
    {
        // Create a mock for the PromotionGroup model
        $groupMock = $this->createMock(PromotionGroup::class);

        // Configure the mock
        $this->groupResourceMock->expects($this->once())->method('save')->with($groupMock);

        // Test the save method
        $result = $this->repository->save($groupMock);
        $this->assertInstanceOf(PromotionGroupInterface::class, $result);
    }

    public function testSaveThrowsCouldNotSaveException()
    {
        // Create a mock for the PromotionGroup model
        $groupMock = $this->createMock(PromotionGroup::class);

        // Configure the mock to throw an exception
        $this->groupResourceMock->method('save')->willThrowException(new \Exception('Error'));

        // Expect CouldNotSaveException to be thrown
        $this->expectException(CouldNotSaveException::class);
        $this->repository->save($groupMock);
    }

    public function testDeleteById()
    {
        $groupId = 1;

        // Create a mock for the PromotionGroup model
        $groupMock = $this->createMock(PromotionGroup::class);

        // Configure the mocks
        $this->groupFactoryMock->method('create')->willReturn($groupMock);
        $this->groupResourceMock->expects($this->once())->method('load')->with($groupMock, $groupId);
        $this->groupResourceMock->expects($this->once())->method('delete')->with($groupMock);
        $groupMock->method('getGroupId')->willReturn($groupId);

        // Test the deleteById method
        $result = $this->repository->deleteById($groupId);
        $this->assertTrue($result);
    }

    public function testDeleteByIdThrowsNoSuchEntityException()
    {
        $groupId = 1;

        // Create a mock for the PromotionGroup model
        $groupMock = $this->createMock(PromotionGroup::class);

        // Configure the mocks
        $this->groupFactoryMock->method('create')->willReturn($groupMock);
        $this->groupResourceMock->expects($this->once())->method('load')->with($groupMock, $groupId);
        $groupMock->method('getGroupId')->willReturn(null);

        // Expect NoSuchEntityException to be thrown
        $this->expectException(NoSuchEntityException::class);
        $this->repository->deleteById($groupId);
    }
}
