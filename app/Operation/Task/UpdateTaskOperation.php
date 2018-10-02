<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace App\Operation\Task;

use App\Domain\Task\TaskHandler;
use App\Models\Task;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * タスクを更新するオペレーション
 *
 * @package App\Domain\Task
 */
class UpdateTaskOperation
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
	 * @param int   $taskId
	 * @param array $request
	 * @return \App\Models\Task
	 * @throws \Throwable
	 */
	public function __invoke(int $taskId, array $request): Task
	{
		$task = $this->taskHandler->findTask($taskId);
		if (null === $task) {
			throw new NotFoundHttpException('Task not found.');
		}

		$task->title = Arr::get($request, 'title');
		$task->body = Arr::get($request, 'body', null);

		$this->taskHandler->persist($task);

		return $task;
	}
}
