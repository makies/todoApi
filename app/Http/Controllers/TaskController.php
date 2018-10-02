<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
 */

namespace App\Http\Controllers;

use App\Operation\Task\CreateTaskOperation;
use App\Operation\Task\DeleteTaskOperation;
use App\Models\Task;
use App\Operation\Task\SearchTaskOperation;
use App\Operation\Task\UpdateTaskOperation;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class TaskController
 *
 * @package App\Http\Controllers
 */
class TaskController extends Controller
{
    /**
     * @var Task|null
     */
    protected $task;

    /**
     * 検証ルール
     *
     * @var array
     */
    private $validateRule = [
        'title' => [
            'required',
            'string',
            'max:255',
        ],
        'body' => [
            'string',
            'max:255',
        ],
    ];

    private $validateMessages = [

    ];

    /**
     * @param Request                                 $request
     * @param \App\Operation\Task\SearchTaskOperation $operation
     * @return Response
     */
    public function index(Request $request, SearchTaskOperation $operation): Response
    {
        return response($operation->__invoke($request->all()));
    }

    /**
     * @param \Illuminate\Http\Request                $request
     * @param \App\Services\TransactionService        $service
     * @param \App\Operation\Task\CreateTaskOperation $operation
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request, TransactionService $service, CreateTaskOperation $operation): Response
    {
        $this->validate($request, $this->validateRule, $this->validateMessages);

        // 保存
        $service->transaction(function () use ($request, $operation) {
            $this->task = $operation->__invoke($request->only(['title', 'body']));
        });

        return response($this->task->toArray(), 201);
    }

    /**
     * @param \Illuminate\Http\Request                $request
     * @param \App\Services\TransactionService        $service
     * @param \App\Operation\Task\UpdateTaskOperation $operation
     * @param int                                     $taskId
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(
        Request $request,
        TransactionService $service,
        UpdateTaskOperation $operation,
        int $taskId
    ): Response {
        $this->validate($request, $this->validateRule, $this->validateMessages);
        $service->transaction(function () use ($request, $operation, $taskId) {
            $this->task = $operation->__invoke($taskId, $request->only(['title', 'body']));
        });

        return response($this->task->toArray(), 204);
    }

    /**
     * @param \App\Services\TransactionService        $service
     * @param \App\Operation\Task\DeleteTaskOperation $operation
     * @param int                                     $taskId
     * @return Response
     * @throws \Exception
     */
    public function delete(TransactionService $service, DeleteTaskOperation $operation, int $taskId): Response
    {
        $service->transaction(function () use ($operation, $taskId) {
            $operation->__invoke($taskId);
        });

        return response([], 204);
    }
}
