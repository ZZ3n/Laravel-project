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
//register
Route::get('/register', 'AuthController@getRegister')->name('register');
Route::post('/register', 'AuthController@postRegister')->name('tryRegister');

//login
Route::get('/login', 'AuthController@login')->name('login');
Route::post('/login', 'AuthController@tryLogin')->name('tryLogin');

// home
Route::permanentRedirect('/', '/home');
Route::get('/home', 'HomeController@home')->name('home');

//로그아웃 요청
Route::post('/logout', 'AuthController@logout')->name('logout');

// 개인 정보 관련 ( 수정 )
Route::prefix('/profile')->group(function () {
    Route::get('', 'ProfileController@get')->middleware('auth');
    Route::get('/modify', 'ProfileController@fix')->middleware('auth');
    Route::post('/modify', 'ProfileController@update')->middleware('auth');
});

// 모임 정보 관련 CRUD 요청
Route::permanentRedirect('/meetings/{meetingId?}/groups', '/meetings/{meetingId?}');

Route::prefix('meetings')->group(function () {
    //Meetings List
    Route::get('', 'MeetingController@all')->name('meetings');
    // Create meetings and groups
    Route::get('create', 'MeetingController@build')->name('createMeeting')->middleware('auth');
    Route::post('create', 'MeetingController@store')->name('tryCreateMeeting')->middleware('auth');
    Route::post('create/group', 'GroupController@build')->middleware('auth');
    // 모임-그룹 신청
    Route::get('{meetingId?}', 'MeetingController@detail');
    Route::get('{meetingId?}/groups/{groupId?}', 'GroupController@select')->middleware('auth');
    Route::post('{meetingId?}/groups/{groupId?}', 'MeetingController@apply')->middleware('auth');
    // 모임 수정
    Route::get('{meetingId?}/modify', 'ManageMeetingController@fix')->middleware('auth');
    Route::post('{meetingId?}/modify', 'ManageMeetingController@update')->middleware('auth');
    // 그룹 수정
    Route::get('{meetingId?}/modify/groups', 'GroupController@manage')->middleware('auth');
    Route::patch('{meetingId?}/modify/groups/accept','ManageMeetingController@acceptUser')->middleware('auth');
    Route::patch('{meetingId?}/modify/groups/deny','ManageMeetingController@denyUser')->middleware('auth');
});


