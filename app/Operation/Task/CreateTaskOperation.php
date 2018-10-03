<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace App\Operation\Task;

use App\Domain\Task\TaskHandler;
use App\Models\Task;
use Illuminate\Support\Arr;

/**
 * タスクを作成するオペレーション
 *
 * @package App\Domain\Task
 */
class CreateTaskOperation
{
    /**
     * @var TaskHandler
     */
    private $taskHandler;

    /**
     * CreateTaskOperation constructor.
     *
     * @param TaskHandler $taskHandler
     */
    public function __construct(TaskHandler $taskHandler)
    {
        $this->taskHandler = $taskHandler;
    }

    /**
     * @param array $request
     * @return \App\Models\Task
     * @throws \Throwable
     */
    public function __invoke(array $request): Task
    {
        $task = new Task([
            'title' => Arr::get($request, 'title'),
            'body' => Arr::get($request, 'body', null),
        ]);

        $this->taskHandler->persist($task);

        return $task;
    }
}
