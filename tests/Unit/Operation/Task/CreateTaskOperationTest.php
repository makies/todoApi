<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace Unit\Operation\Task;

use App\Operation\Task\CreateTaskOperation;
use App\Domain\Task\TaskHandler;
use App\Models\Task;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Mockery as M;
use TestCase;

/**
 * Class CreateTaskOperationTest
 *
 * @package Unit\Operation\Task
 * @covers \App\Operation\Task\CreateTaskOperation
 */
class CreateTaskOperationTest extends TestCase
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
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            CreateTaskOperation::class,
            new CreateTaskOperation($this->taskHandler)
        );
    }

    /**
     * __invoke
     */
    public function testInvoke(): void
    {
        $request = [
            'title' => 'たいとる',
            'body' => 'ほんぶん',
        ];

        $task = factory(Task::class)->create($request);

        $this->taskHandler->shouldReceive('persist')
            ->once()
            ->with(Task::class)
            ->andReturn($task);

        $operation = new CreateTaskOperation($this->taskHandler);
        $response = $operation->__invoke($request);

        $this->assertInstanceOf(Task::class, $response);
        $this->assertNull($response->deleted_at);
    }
}
