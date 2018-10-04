<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace Unit\Domain\Task;

use App\Domain\Task\TaskHandler;
use App\Domain\Task\TaskRepository;
use App\Models\Task;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Mockery as M;
use TestCase;

/**
 * Class TaskHandlerTest
 *
 * @package Unit\Operation\Task
 * @covers  \App\Domain\Task\TaskHandler
 */
class TaskHandlerTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * @var \App\Domain\Task\TaskRepository
     */
    private $taskRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = M::mock(TaskRepository::class);
    }

    /**
     * コンストラクト
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            TaskHandler::class,
            new TaskHandler($this->taskRepository)
        );
    }

    public function testSearchTask(): void
    {
        $params = [];

        $this->taskRepository->shouldReceive('searchTask')
            ->with($params)
            ->andReturn(collect());

        $handler = new TaskHandler($this->taskRepository);
        $this->assertEquals(collect(), $handler->searchTask($params));
    }

    public function testFindTask(): void
    {
        $task = factory(Task::class)->create();

        $this->taskRepository->shouldReceive('find')
            ->once()
            ->andReturn($task);

        $handler = new TaskHandler($this->taskRepository);
        $this->assertInstanceOf(Task::class, $handler->findTask($task->task_id));
    }

    public function testFindTaskNull(): void
    {
        $this->taskRepository->shouldReceive('find')
            ->once()
            ->andReturnNull();

        $handler = new TaskHandler($this->taskRepository);
        $this->assertNull($handler->findTask(1234));
    }

    public function testPersistSuccess(): void
    {
        $task = M::mock(Task::class);

        $task->shouldReceive('save')
            ->once()
            ->andReturnTrue();

        $handler = new TaskHandler($this->taskRepository);
        $handler->persist($task);
        $this->assertTrue(true);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testPersistError()
    {
        $task = M::mock(Task::class);

        $task->shouldReceive('save')
            ->once()
            ->withNoArgs();

        $handler = new TaskHandler($this->taskRepository);
        $handler->persist($task);
    }

    public function testDelete(): void
    {
        $task = M::mock(Task::class);
        $task->shouldReceive('delete')
            ->once()
            ->andReturnTrue();

        $handler = new TaskHandler($this->taskRepository);
        $handler->delete($task);
    }
}
