<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

use App\Models\Task;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * タスク検索APIのテスト
 *
 * @covers \App\Http\Controllers\TaskController
 */
class SearchTaskTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * パラメータなし
     */
    public function testSearchTask(): void
    {
        $task1 = factory(Task::class)->create(['title' => 'たいとる1', 'body' => 'ほんぶん']);
        $task2 = factory(Task::class)->create(['title' => 'たいとる2', 'body' => '']);
        $task3 = factory(Task::class)->create(['title' => 'たいとる3', 'deleted_at' => Carbon::now()]);
        $task4 = factory(Task::class)->create(['title' => 'たいとる4', 'body' => 'ほんぶん']);
        $task5 = factory(Task::class)->create(['title' => 'たいとる5', 'body' => 'ほんぶん', 'deleted_at' => Carbon::now()]);

        $this->get('/task');

        $response = json_decode($this->response->getContent(), true);
        $this->assertCount(3, $response);

        $this->assertEquals($task1->toArray(), $response[0]);
        $this->assertEquals($task2->toArray(), $response[1]);
        $this->assertEquals($task4->toArray(), $response[2]);
    }

    /**
     * タイトル部分一致
     */
    public function testSearchTasksWithTitle(): void
    {
        $task1 = factory(Task::class)->create(['title' => '牛乳を買う', 'body' => 'ほんぶん']);
        $task2 = factory(Task::class)->create(['title' => 'ひき肉を買う', 'body' => '合いびき肉 300g']);
        $task3 = factory(Task::class)->create(['title' => '餃子の皮', 'body' => '100枚']);
        $task4 = factory(Task::class)->create(['title' => 'アボカドを買う', 'deleted_at' => Carbon::now()]);

        $this->get('/task?title=買う');

        $response = json_decode($this->response->getContent(), true);
        $this->assertCount(2, $response);

        $this->assertEquals($task1->toArray(), $response[0]);
        $this->assertEquals($task2->toArray(), $response[1]);
    }

    /**
     * 本文部分一致
     */
    public function testSearchTasksWithBody(): void
    {
        $task1 = factory(Task::class)->create(['title' => '牛乳を買う', 'body' => 'ほんぶん']);
        $task2 = factory(Task::class)->create(['title' => 'ひき肉を買う', 'body' => '合いびき肉 300g']);
        $task3 = factory(Task::class)->create(['title' => '餃子の皮', 'body' => '100枚']);
        $task4 = factory(Task::class)->create(['title' => 'アボカドを買う', 'deleted_at' => Carbon::now()]);

        $this->get('/task?body=肉');

        $response = json_decode($this->response->getContent(), true);
        $this->assertCount(1, $response);

        $this->assertEquals($task2->toArray(), $response[0]);
    }

    /**
     * 該当するものがない
     */
    public function testTaskNotFound()
    {
        $this->get('/task');
        $this->assertEquals('[]', $this->response->getContent());
    }
}
