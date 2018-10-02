<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace Unit\Domain\Task;

use App\Domain\Task\TaskHandler;
use App\Domain\Task\TaskRepository;
use App\Models\Task;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Mockery as M;
use TestCase;

/**
 * Class TaskHandlerTest
 *
 * @package Unit\Operation\Task
 * @covers \App\Domain\Task\TaskHandler
 */
class TaskHandlerTest extends TestCase
{
    use DatabaseMigrations;

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
