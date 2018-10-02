<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace Unit\Operation\Task;

use App\Operation\Task\DeleteTaskOperation;
use App\Domain\Task\TaskHandler;
use App\Models\Task;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Mockery as M;
use TestCase;

/**
 * Class DeleteTaskOperationTest
 *
 * @package Unit\Operation\Task
 */
class DeleteTaskOperationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var TaskHandler
     */
    private $taskHandler;

    public function setUp(): void
    {
        parent::setUp();
        $this->taskHandler = M::mock(TaskHandler::class);
    }

    /**
     * コンストラクト
     */
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            DeleteTaskOperation::class,
            new DeleteTaskOperation($this->taskHandler)
        );
    }

    /**
     * __invoke
     */
    public function test__invoke(): void
    {
        $request = [
            'title' => 'たいとる',
            'body' => 'ほんぶん',
            'deleted_at' => null,
        ];

        $task = factory(Task::class)->create($request);

        $this->taskHandler->shouldReceive('findTask')
            ->once()
            ->with($task->task_id)
            ->andReturn($task);


        $this->taskHandler->shouldReceive('delete')
            ->once()
            ->with(Task::class);

        $operation = new DeleteTaskOperation($this->taskHandler);
        $response = $operation->__invoke($task->task_id);
        $this->assertNull($response);
    }

    /**
     * 削除対象がない__invoke
     */
    public function test__invokeTaskNotFound(): void
    {
        $this->taskHandler->shouldReceive('findTask')
            ->once()
            ->with(1234)
            ->andReturnNull();


        $this->taskHandler->shouldReceive('delete')
            ->never();

        $operation = new DeleteTaskOperation($this->taskHandler);
        $response = $operation->__invoke(1234);
        $this->assertNull($response);
    }
}
