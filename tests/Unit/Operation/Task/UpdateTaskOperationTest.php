<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace Unit\Operation\Task;

use App\Operation\Task\UpdateTaskOperation;
use App\Domain\Task\TaskHandler;
use App\Models\Task;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Mockery as M;
use TestCase;

/**
 * Class UpdateTaskOperationTest
 *
 * @package Unit\Operation\Task
 */
class UpdateTaskOperationTest extends TestCase
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
     * コンストラクトのテスト
     */
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            UpdateTaskOperation::class,
            new UpdateTaskOperation($this->taskHandler)
        );
    }

    /**
     * __invokeのテスト
     */
    public function test__invoke(): void
    {
        $before = [
            'title' => 'たいとる',
            'body' => 'ほんぶん',
            'deleted_at' => null,
        ];
        $request = [
            'title' => 'たいとる2',
            'body' => 'ほんぶん2',
            'deleted_at' => null,
        ];

        $taskBefore = factory(Task::class)->create($before);
        $taskAfter = factory(Task::class)->create($request);

        $this->taskHandler->shouldReceive('findTask')
            ->once()
            ->with($taskBefore->task_id)
            ->andReturn($taskBefore);

        $this->taskHandler->shouldReceive('persist')
            ->once()
            ->with(Task::class)
            ->andReturn($taskAfter);

        $operation = new UpdateTaskOperation($this->taskHandler);
        $response = $operation->__invoke($taskBefore->task_id, $request);

        $this->assertInstanceOf(Task::class, $response);
        $this->assertSame($request['title'], $response->title);
        $this->assertSame($request['body'], $response->body);
        $this->assertNull($response->deleted_at);
    }


    /**
     * __invokeのテスト
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Task not found.
     */
    public function test__invokeTaskNotFound(): void
    {
        $request = [
            'title' => 'たいとる',
            'body' => 'ほんぶん',
            'deleted_at' => null,
        ];
        $this->taskHandler->shouldReceive('findTask')
            ->once()
            ->with(1234)
            ->andReturnNull();

        $operation = new UpdateTaskOperation($this->taskHandler);
        $operation->__invoke(1234, $request);
    }
}
