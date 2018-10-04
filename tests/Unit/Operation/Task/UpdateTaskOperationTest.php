<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace Unit\Operation\Task;

use App\Operation\Task\UpdateTaskOperation;
use App\Domain\Task\TaskHandler;
use App\Models\Task;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Mockery as M;
use TestCase;

/**
 * Class UpdateTaskOperationTest
 *
 * @package Unit\Operation\Task
 * @covers \App\Operation\Task\UpdateTaskOperation
 */
class UpdateTaskOperationTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

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
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            UpdateTaskOperation::class,
            new UpdateTaskOperation($this->taskHandler)
        );
    }

    /**
     * __invoke
     */
    public function testInvoke(): void
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
     * __invoke
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Task not found.
     */
    public function testInvokeTaskNotFound(): void
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
