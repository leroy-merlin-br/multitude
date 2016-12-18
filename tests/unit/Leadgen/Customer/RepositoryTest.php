<?php
namespace Leadgen\Customer;

use Leadgen\Base\ResourceRepository;
use Mongolid\Cursor\CursorInterface;
use Mockery as m;
use PHPUnit_Framework_TestCase;

class RepositoryTest extends PHPUnit_Framework_TestCase
{
    protected $resourceRepo;

    public function setUp()
    {
        $this->resourceRepo = m::mock(ResourceRepository::class);
        app()->instance(ResourceRepository::class, $this->resourceRepo);
    }

    public function tearDown()
    {
        m::close();
        app()->forgetInstance(ResourceRepository::class);
    }

    public function testShouldGetAll()
    {
        // Arrange
        $repo = new Repository;
        $result = m::mock(CursorInterface::class);

        // Act
        $this->resourceRepo->shouldReceive('all')
            ->once()
            ->with(4, 2)
            ->andReturn($result);

        // Assert
        $this->assertEquals($result, $repo->all(4, 2));
    }

    public function testShouldQueryWhere()
    {
        // Arrange
        $repo = new Repository;
        $result = m::mock(CursorInterface::class);

        // Act
        $this->resourceRepo->shouldReceive('where')
            ->once()
            ->with(['foo' => 'bar'], 4, 2)
            ->andReturn($result);

        // Assert
        $this->assertEquals(
            $result,
            $repo->where(['foo' => 'bar'], 4, 2)
        );
    }

    public function testShouldFindExisting()
    {
        // Arrange
        $repo = new Repository;
        $result = m::mock(Customer::class);

        // Act
        $this->resourceRepo->shouldReceive('findExisting')
            ->once()
            ->with(42)
            ->andReturn($result);

        // Assert
        $this->assertEquals($result, $repo->findExisting(42));
    }

    public function testShouldCreateNew()
    {
        // Arrange
        $repo = new Repository;
        $result = m::mock(Customer::class);

        // Act
        $this->resourceRepo->shouldReceive('createNew')
            ->once()
            ->with(['foo' => 'bar'])
            ->andReturn($result);

        // Assert
        $this->assertEquals($result, $repo->createNew(['foo' => 'bar']));
    }

    public function testShouldUpdateExisting()
    {
        // Arrange
        $repo = new Repository;
        $interaction = m::mock(Customer::class);
        $result = true;

        // Act
        $this->resourceRepo->shouldReceive('updateExisting')
            ->once()
            ->with($interaction, ['foo' => 'bar'])
            ->andReturn($result);

        // Assert
        $this->assertEquals($result, $repo->updateExisting($interaction, ['foo' => 'bar']));
    }

    public function testShouldDeleteExisting()
    {
        // Arrange
        $repo = new Repository;
        $interaction = m::mock(Customer::class);
        $result = true;

        // Act
        $this->resourceRepo->shouldReceive('deleteExisting')
            ->once()
            ->with($interaction)
            ->andReturn($result);

        // Assert
        $this->assertEquals($result, $repo->deleteExisting($interaction));
    }

    public function testShouldGetLastErrors()
    {
        // Arrange
        $repo = new Repository;
        $errors = ['foo', 'bar'];

        // Act
        $this->resourceRepo->shouldReceive('getLastErrors')
            ->once()
            ->andReturn($errors);

        // Assert
        $this->assertEquals($errors, $repo->getLastErrors());
    }
}
