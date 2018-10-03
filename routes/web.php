<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// 検索
$router->get('/task', 'TaskController@index');
// 作成
$router->post('/task', 'TaskController@create');
// 更新
$router->put('/task/{taskId}', 'TaskController@update');
// 削除
$router->delete('/task/{taskId}', 'TaskController@delete');
