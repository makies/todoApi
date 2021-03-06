<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace App\Operation\Task;

use App\Domain\Task\TaskHandler;

/**
 * Class SearchTaskOperation
 */
class SearchTaskOperation
{
    private $taskHandler;

    /**
     * SearchTaskOperation constructor.
     *
     * @param \App\Domain\Task\TaskHandler $taskHandler
     */
    public function __construct(TaskHandler $taskHandler)
    {
        $this->taskHandler = $taskHandler;
    }

    /**
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function __invoke(array $request)
    {
        return $this->taskHandler->searchTask($request);
    }
}
