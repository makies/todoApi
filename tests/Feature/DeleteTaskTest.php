<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

use App\Models\Task;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * Class DeleteTaskTest
 */
class DeleteTaskTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * タスクを削除する
     */
    public function testDeleteComplete(): void
    {
        $task = factory(Task::class)->create();
        $this->delete('/task/' . $task->task_id);

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * 削除対象のタスクが存在しない
     */
    public function testTaskNotFound(): void
    {
        $this->delete('/task/1234');

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * 削除対象のタスクを指定しない
     */
    public function testTaskUndefined(): void
    {
        $this->delete('/task');

        $this->assertResponseStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
