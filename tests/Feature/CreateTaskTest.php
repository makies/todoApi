<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * タスク作成のAPIテスト
 */
class CreateTaskTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * タスクを作成する
     */
    public function testCreateComplete(): void
    {
        Carbon::setTestNow(Carbon::now());
        $now = Carbon::now()->toDateTimeString();
        $this->post('/task', [
            'title' => 'たいとる',
            'body' => 'ないよう',
        ]);

        $this->assertResponseStatus(Response::HTTP_CREATED);
        $response = json_decode($this->response->content(), true);
        $this->assertNotEmpty($response['task_id']);
        $this->assertInternalType('numeric', $response['task_id']);
        $this->assertEquals('たいとる', $response['title']);
        $this->assertEquals('ないよう', $response['body']);
        $this->assertEquals($now, $response['created_at']);
        $this->assertEquals($now, $response['updated_at']);
    }

    /**
     * タイトルのみでタスクを作成する
     */
    public function testCreateCompleteOnlyTitle(): void
    {
        Carbon::setTestNow(Carbon::now());
        $now = Carbon::now()->toDateTimeString();
        $this->post('/task', [
            'title' => 'たいとる',
        ]);

        $this->assertResponseStatus(Response::HTTP_CREATED);
        $response = json_decode($this->response->content(), true);
        $this->assertNotEmpty($response['task_id']);
        $this->assertInternalType('numeric', $response['task_id']);
        $this->assertEquals('たいとる', $response['title']);
        $this->assertEmpty($response['body']);
        $this->assertEquals($now, $response['created_at']);
        $this->assertEquals($now, $response['updated_at']);
    }

    /**
     * 同名のタスクが存在しても登録できる
     */
    public function testDuplicateTask(): void
    {
        $task = factory(Task::class)->create(['title' => 'タイトル', 'body' => 'ないよう']);

        $params = [
            'title' => $task->title,
            'body' => $task->body,
        ];

        $this->post('/task/', $params);

        $this->assertResponseStatus(Response::HTTP_CREATED);

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
            // 本文がない
            [
                ['body' => 'ないよう' . microtime()],
                ['title' => ['The title field is required.']]
            ],
            // タイトル・本文がarray
            [
                ['title' => [1], 'body' => [2]],
                [
                    'title' => ['The title must be a string.'],
                    'body' => ['The body must be a string.'],
                ]
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
        $this->post('/task/', $params);

        $this->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $content = json_decode($this->response->content(), true);
        $this->assertEquals($messages, $content);
    }
}
