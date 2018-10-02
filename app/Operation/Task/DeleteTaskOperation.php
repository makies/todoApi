<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace App\Operation\Task;

use App\Domain\Task\TaskHandler;
use App\Models\Task;

/**
 * タスクを削除するオペレーション
 *
 * @package App\Domain\Task
 */
class DeleteTaskOperation
{
	/**
	 * @var TaskHandler
	 */
	private $taskHandler;

	/**
	 * DeleteTaskOperation constructor.
	 *
	 * @param TaskHandler $taskHandler
	 */
	public function __construct(TaskHandler $taskHandler)
	{
		$this->taskHandler = $taskHandler;
	}

	/**
	 * @param int $taskId
	 * @throws \Throwable
	 */
	public function __invoke(int $taskId): void
	{
		$task = $this->taskHandler->findTask($taskId);
		if ($task instanceof Task) {
			$this->taskHandler->delete($task);
		}
	}
}
