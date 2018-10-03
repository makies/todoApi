<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

use App\Models\Task;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * Class UpdateTaskTest
 *
 * @covers \App\Http\Controllers\TaskController
 */
class UpdateTaskTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * タスクを更新する
     */
    public function testUpdateComplete(): void
    {
        $task = factory(Task::class)->create();

        $params = [
            'title' => 'たいとる' . microtime(),
            'body' => 'ないよう' . microtime(),
            'deleted_at' => null,
        ];

        $this->put('/task/' . $task->task_id, $params);

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);

        $response = json_decode($this->response->getContent(), true);
        $this->assertSame($params['title'], $response['title']);
        $this->assertSame($params['body'], $response['body']);
    }

    /**
     * タイトルだけでタスクを更新する
     */
    public function testUpdateCompleteOnlyTitle(): void
    {
        $task = factory(Task::class)->create();

        $params = [
            'title' => 'たいとる' . microtime(),
        ];

        $this->put('/task/' . $task->task_id, $params);

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);

        $response = json_decode($this->response->getContent(), true);
        $this->assertSame($params['title'], $response['title']);
        $this->assertEmpty($response['body']);
    }

    /**
     * 同名のタスクが存在しても登録できる
     */
    public function testDuplicateTask(): void
    {
        $task1 = factory(Task::class)->create(['title' => 'タイトル', 'body' => 'ないよう']);
        $task2 = factory(Task::class)->create(['title' => 'タイトル2', 'body' => 'ないよう2']);

        $params = [
            'title' => $task2->title,
            'body' => $task2->body,
        ];

        $this->put('/task/' . $task2->task_id, $params);

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);

        $response = json_decode($this->response->getContent(), true);
        $this->assertSame($params['title'], $response['title']);
        $this->assertSame($params['body'], $response['body']);
    }

    /**
     * Validationエラーとなるリクエストのパラメータ dataProvider
     */
    public function validateErrorDataProvider(): array
    {
        return [
            // タイトルがない
            [
                ['body' => 'ないよう' . microtime()],
                ['title' => ['The title field is required.']],
            ],
            // タイトルがarray
            [
                ['title' => [1]],
                ['title' => ['The title must be a string.']],
            ],
            // タイトルも本文もない
            [
                [],
                [
                    'title' => ['The title field is required.'],
                ],
            ],
            // タイトル・本文が255バイト以上ある
            [
                ['title' => str_random(256), 'body' => str_random(256)],
                [
                    'title' => ['The title may not be greater than 255 characters.'],
                    'body' => ['The body may not be greater than 255 characters.'],
                ],
            ],
        ];
    }

    /**
     * validationエラーとなるパターン
     *
     * @dataProvider validateErrorDataProvider
     * @param array $params
     * @param array $messages
     */
    public function testValidateError(array $params, array $messages): void
    {
        $task = factory(Task::class)->create();

        $this->put('/task/' . $task->task_id, $params);

        $this->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $content = json_decode($this->response->content(), true);
        $this->assertEquals($messages, $content);
    }

    /**
     * 存在しないタスクを更新しようとする
     */
    public function testTaskNotFound(): void
    {
        $params = [
            'title' => 'たいとる' . microtime(),
            'body' => 'ないよう' . microtime(),
            'deleted_at' => null,
        ];
        $this->put('/task' . 1234, $params);

        $this->assertResponseStatus(404);
        $response = json_decode($this->response->getContent(), true);
        $this->assertEquals('Task not found.', $response['message']);
    }

    /**
     * タスクIDを指定しない
     */
    public function testIdUndefined(): void
    {
        $params = [
            'title' => 'たいとる' . microtime(),
            'body' => 'ないよう' . microtime(),
            'deleted_at' => null,
        ];
        $this->put('/task', $params);

        $this->assertResponseStatus(405);
    }
}
