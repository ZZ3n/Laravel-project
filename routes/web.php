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

Route::get('/register', 'AuthController@register')->name('register');
Route::post('/register', 'AuthController@tryRegister')->name('tryRegister');

Route::get('/login', 'AuthController@login')->name('login');
Route::post('/login', 'AuthController@tryLogin')->name('tryLogin');

Route::permanentRedirect('/', '/home');
Route::get('/home', 'HomeController@home')->name('home');

//delete 요청으로 바꿀까?
Route::post('/logout', 'AuthController@logout')->name('logout');

Route::prefix('/profile')->group(function () {
    Route::get('', 'ProfileController@getProfile');
    Route::post('', 'ProfileController@gotoModifyProfile');
    Route::post('/modify_user', 'ProfileController@modifyProfile');
});

Route::prefix('meetings')->group(function () {
    Route::get('', 'MeetingController@meetings')->name('meetings');

    Route::get('create', 'MeetingController@createMeeting')->name('createMeeting');
    Route::post('create', 'MeetingController@tryCreateMeeting')->name('tryCreateMeeting');
    Route::post('ajaxGroup', 'MeetingController@tryCreateGroup');

    Route::get('detail/{meetingId?}', 'MeetingController@detail');
    Route::get('detail/{meetingId?}/{groupId?}', 'MeetingController@apply');
    Route::post('detail/{meetingId?}/{groupId?}', 'MeetingController@tryApplication');

    Route::get('modify/meeting/{meetingId?}', 'ModifyMeetingController@getMeeting');
    Route::post('modify/meeting/{meetingId?}', 'ModifyMeetingController@postMeeting');

    Route::get('modify/groups/{meetingId?}', 'ModifyMeetingController@getGroups');
});


