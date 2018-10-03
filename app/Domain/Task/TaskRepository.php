<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace App\Domain\Task;

use App\Models\Task;

/**
 * Class TaskRepository
 *
 * @package App\Domain\Task
 */
class TaskRepository
{
    /**
     * @param array $params
     * @return \App\Models\Task[]|\Illuminate\Database\Eloquent\Collection
     */
    public function searchTask(array $params)
    {
        $query = Task::withoutTrashed();

        // タイトル検索
        if (isset($params['title'])) {
            $query->where('title', 'LIKE', "%{$params['title']}%");
        }
        // 本文検索
        if (isset($params['body'])) {
            $query->where('body', 'LIKE', "%{$params['body']}%");
        }

        return $query->get();
    }

    /**
     * @param int $id
     * @return Task|null
     */
    public function find(int $id)
    {
        return Task::find($id);
    }
}
