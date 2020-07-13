<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('hello/html', function () {
    return View::make('hello.html');
});

Route::get('task/view', function () {
    $task = ['name' => 'task 1', 'due_date' => '20200713'];
    return view('task.view')
        ->with('task',$task);

});
