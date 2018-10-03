<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace App\Domain\Task;

use App\Models\Task;

/**
 * Class TaskHandler
 *
 * @package App\Domain\Task
 */
class TaskHandler
{
    /**
     * @var \App\Domain\Task\TaskRepository
     */
    protected $repository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->repository = $taskRepository;
    }

    /**
     * @param int $id
     * @return Task|null
     */
    public function findTask(int $id): ?Task
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $params
     * @return \App\Models\Task[]|\Illuminate\Database\Eloquent\Collection
     */
    public function searchTask(array $params)
    {
        return $this->repository->searchTask($params);
    }

    /**
     * 保存する
     *
     * @param Task $task
     * @throws \Throwable
     */
    public function persist(Task $task): void
    {
        throw_unless($task->save(), \RuntimeException::class);
    }

    /**
     * 削除する
     *
     * @param Task $task
     * @throws \Throwable
     */
    public function delete(Task $task): void
    {
        throw_unless($task->delete(), \RuntimeException::class);
    }
}
