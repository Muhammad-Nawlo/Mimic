<?php

use App\Http\Controllers\Dashboard\CategoryController;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard\CountryController;
use App\Http\Controllers\Dashboard\ClassroomController;
use App\Http\Controllers\Dashboard\SubjectController;
use App\Http\Controllers\Dashboard\TermController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\dashboard\classroom_subjectcontroller;
use App\Http\Controllers\dashboard\groupcontroller;

Auth::routes();

Route::get('/clear',function(){
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    // Artisan::call('jwt:secret');
    // Artisan::call('key:generate');
    return "cache clear";
});

Route::get('/', function () {
    return view('auth.login');
});



Route::group(
    [
        'prefix'     => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ],
    function () {

        Route::get('/dashboard/home','HomeController@index')->name('dashboard.home')->middleware('admin');

        Route::prefix('dashboard')->namespace('Dashboard')->middleware(['auth','admin'])->name('dashboard.')->group(function () {

            Route::resource('users', 'UserController');
            Route::resource('roles', 'RoleController');
            Route::resource('categories','CategoryController');
            Route::resource('countries','CountryController');
            Route::resource('cities','CityController');
            Route::resource('clients','ClientController')->except(['store','create']);
            Route::resource('ranks','RankController');
            Route::resource('hashtags','HashtagController');
            Route::resource('challenges','ChallengeController')->except(['store','create']);
            Route::resource('videos','VideoController')->except(['store','create']);
            Route::resource('stories','StoryController')->except(['store','create']);
            Route::resource('reports','ReportController')->except(['store','create','update','edit']);
            Route::resource('notifications','NotificationController')->except(['create','update','edit']);

            Route::post('changeStatus','ClientController@changeStatus')->name('changeStatus');
            Route::get('client/{id}','ClientController@client')->name('client');
            Route::get('getCities/{id}','CountryController@getCities')->name('getCities');
            Route::get('challenge/{id}','ChallengeController@challenge')->name('challenge');
            Route::get('Mychallenges/{id}','ChallengeController@Mychallenges')->name('Mychallenges');
            Route::get('MyWinnerChallenges/{id}','ChallengeController@MyWinnerChallenges')->name('MyWinnerChallenges');
            Route::post('changeChallengeStatus','ChallengeController@changeChallengeStatus')->name('changeChallengeStatus');
            Route::post('changeStoryStatus','StoryController@changeStoryStatus')->name('changeStoryStatus');
            Route::post('reportStatus','ReportController@reportStatus')->name('reportStatus');
            Route::post('changeVideoeStatus','VideoController@changeVideoeStatus')->name('changeVideoeStatus');
            Route::get('delet_comment/{id}','VideoController@delet_comment')->name('delet_comment');
            Route::get('delet_replay/{id}','VideoController@delet_replay')->name('delet_replay');
            Route::get('video/{id}','VideoController@video')->name('video');
            Route::get('challengVideos/{id}','VideoController@challengVideos')->name('challengVideos');
            Route::get('clientVideos/{id}','VideoController@clientVideos')->name('clientVideos');


        });


    });

