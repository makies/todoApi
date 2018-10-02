<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace Unit\Operation\Task;

use App\Operation\Task\SearchTaskOperation;
use App\Domain\Task\TaskHandler;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Mockery as M;
use TestCase;

/**
 * Class SearchTaskOperationTest
 *
 * @package Unit\Operation\Task
 */
class SearchTaskOperationTest extends TestCase
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
            SearchTaskOperation::class,
            new SearchTaskOperation($this->taskHandler)
        );
    }

    /**
     * __invoke
     */
    public function test__invoke(): void
    {
        $task1 = factory(Task::class)->create(['title' => 'たいとる1', 'body' => 'ほんぶん']);
        $task2 = factory(Task::class)->create(['title' => 'たいとる2', 'body' => '']);
        $task3 = factory(Task::class)->create(['title' => 'たいとる3', 'deleted_at' => Carbon::now()]);
        $task4 = factory(Task::class)->create(['title' => 'たいとる4', 'body' => 'ほんぶん']);
        $task5 = factory(Task::class)->create(['title' => 'たいとる5', 'body' => 'ほんぶん', 'deleted_at' => Carbon::now()]);

        $expect = collect([$task1, $task2, $task4]);

        $this->taskHandler->shouldReceive('searchTask')
            ->once()
            ->with([])
            ->andReturn($expect);

        $operation = new SearchTaskOperation($this->taskHandler);
        $response = $operation->__invoke([]);

        $this->assertInstanceOf(Collection::class, $response);
    }
}
