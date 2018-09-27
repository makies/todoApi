<?php
/**
 * @copyright makies <makies@gmail.com>
 */

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class UpdateTaskTest
 */
class UpdateTaskTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * タスクを更新するテスト
     */
    public function testUpdateComplete(): void
    {
        $this->put('/task/1'); // TODO id指定

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);
        $this->markTestIncomplete('未完了');
    }
}
