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
Route::get('/register', 'AuthController@register')->name('register');
Route::post('/register', 'AuthController@tryRegister')->name('tryRegister');

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
    Route::get('', 'ProfileController@getProfile');
    Route::post('', 'ProfileController@gotoModifyProfile');
    Route::post('/modify_user', 'ProfileController@modifyProfile');
});

// 모임 정보 관련 CRUD 요청
Route::prefix('meetings')->group(function () {
    //Meetings List
    Route::get('', 'MeetingController@meetings')->name('meetings');
    // Create meetings and groups
    Route::get('create', 'MeetingController@createMeeting')->name('createMeeting');
    Route::post('create', 'MeetingController@tryCreateMeeting')->name('tryCreateMeeting');
    Route::post('ajaxGroup', 'MeetingController@tryCreateGroup');
    // 모임-그룹 신청
    Route::get('detail/{meetingId?}', 'MeetingController@detail');
    Route::get('detail/{meetingId?}/{groupId?}', 'MeetingController@apply');
    Route::post('detail/{meetingId?}/{groupId?}', 'MeetingController@tryApplication');
    // 모임 수정
    Route::get('modify/meeting/{meetingId?}', 'ModifyMeetingController@getMeeting');
    Route::post('modify/meeting/{meetingId?}', 'ModifyMeetingController@postMeeting');
    // 그룹 수정
    Route::get('modify/groups/{meetingId?}', 'ModifyMeetingController@getGroups');
    Route::patch('modify/groups/{meetingId?}/accept','ModifyMeetingController@acceptUser');
    Route::patch('modify/groups/{meetingId?}/deny','ModifyMeetingController@denyUser');
});


