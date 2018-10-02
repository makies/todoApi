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
     * タスクを作成するテスト
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
            [['body' => 'ないよう' . microtime()]],
            [['title' => 'タイトル' . microtime()]],
            [['title' => [], 'body' => []]],
        ];
    }

    /**
     * validationエラーとなるパターンのテスト
     *
     * @dataProvider validateErrorDataProvider
     * @param array $params
     */
    public function testValidateError(array $params): void
    {
        $this->post('/task/', $params);

        $this->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        // TODO エラーメッセージもテストしたい
    }

    /**
     * 存在しないタスクを更新しようとする
     */
    public function testTaskNotFound(): void
    {
        $params = [
            'title' => 'たいとる' . microtime(),
            'body' => 'ないよう' . microtime(),
        ];
        $this->put('/task' . 1234, $params);

        $this->assertResponseStatus(404);

        $this->markTestIncomplete('エラーメッセージのテストができていない');
    }
}
