<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * transactionを行うクラス
 *
 * @package App\Services
 */
class TransactionService
{
    /**
     * @param callable $callback
     * @return mixed
     * @throws \Exception
     */
    public function transaction($callback)
    {
        DB::beginTransaction();

        try {
            $result = call_user_func($callback);
            DB::commit();

            return $result;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error(sprintf('%s %s:%d', $exception->getMessage(), $exception->getFile(), $exception->getLine()));
            throw $exception;
        }
    }
}
