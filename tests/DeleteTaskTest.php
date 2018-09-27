<?php
/**
 * @copyright makies <makies@gmail.com>
 */

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class DeleteTaskTest
 */
class DeleteTaskTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * タスクを削除するテスト
     */
    public function testDeleteComplete(): void
    {
        $this->delete('/task/1'); // TODO id

        $this->assertResponseStatus(Response::HTTP_NO_CONTENT);
        $this->markTestIncomplete('未完了');
    }
}
