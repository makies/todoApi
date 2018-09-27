<?php
/**
 * @copyright makies <makies@gmail.com>
 */

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class CreateTaskTest
 */
class CreateTaskTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * タスクを作成するテスト
     */
    public function testCreateComplete(): void
    {
        $this->post('/task');

        $this->assertResponseStatus(Response::HTTP_CREATED);
        $this->markTestIncomplete('未完了');
    }
}
