<?php

use App\Http\Controllers\Api\Client\ClientController;
use App\Http\Controllers\Api\Client\InterestingController;
use App\Http\Controllers\Api\Client\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//rgister and verify account
Route::group(['middleware' => ['authGuest', 'throttle:30,1']], function () {
    //Start Register
    Route::post('register', 'registerController@register');
    Route::post('VerifyAccount', 'registerController@VerifyAccount');
    Route::post('resendVerifiyCode', 'registerController@resendVerifiyCode');
    //End Register
    //sociail login or register
    Route::post('authSocial', 'registerController@authSocial');
    //login  by email and password
    Route::post('login', 'authenticationController@authenticate');
    //foreget password
    Route::post('clientForgetPassword', 'resetPasswordController@clientForgetPassword');
    Route::post('clientResetPassword', 'resetPasswordController@clientResetPassword');
    //end foreget
    Route::post('ClientById', 'profileController@ClientById');

    //get all video
    Route::get('Video/getVideos', [VideoController::class, 'index']);


    Route::group(['middleware' => 'client'], function () {
        //addYouFavouriteCategory
        Route::post('addYouFavouriteCategory', 'profileController@addYouFavouriteCategory');
        //logout
        Route::post('logout', 'authenticationController@logout');
        //profile
        Route::get('getProfile', 'profileController@getProfile');
        Route::get('myStatistics', 'profileController@myStatistics');

        Route::post('updateProfile', 'profileController@updateProfile');
        Route::post('searchClients', 'profileController@searchClients');
        //change password
        Route::post('clientChangPassword', 'profileController@changeClientPassword');

        //get all client
        Route::get('getClients', [ClientController::class, 'index']);
        //start challenge
        Route::group(['prefix' => 'Challenge'], function () {

            Route::get('/', 'ChallengeController@index');
            Route::post('store', 'ChallengeController@CreateChallenge');
            Route::get('getCurrentChallenges', 'ChallengeController@getCurrentChallenges');
            Route::post('searchInChallenges', 'ChallengeController@searchInChallenges');
            Route::post('getMyChallenges', 'ChallengeController@getMyChallenges');
            Route::post('SearchByHashtagIDInCurrentChallenges', 'ChallengeController@SearchByHashtagIDInCurrentChallenges');
            Route::post('getCurrentChallengesVideos', 'ChallengeController@getCurrentChallengesVideos');
            Route::post('getChallengeById', 'ChallengeController@getChallengeById');
            Route::post('getChallengesByCreaterId', 'ChallengeController@getChallengesByCreaterId');
            Route::post('joinChallenges', 'ChallengeController@joinChallenges');
        });
        //end challenge

        //start video
        Route::group(['prefix' => 'Video'], function () {
            Route::post('store', 'VideoController@store');
            Route::post('getMyVideos', 'VideoController@getMyVideos');
            Route::post('AddReason', 'VideoController@AddReason');
        });
        //end video

        //start Story
        Route::group(['prefix' => 'Story'], function () {
            Route::post('store', 'StoryController@store');
            Route::get('getStories', 'StoryController@getStories');
        });
        //end Story
        //start Favourite
        Route::group(['prefix' => 'Favourite'], function () {
            Route::post('toggleFavourite', 'FavouriteController@toggleFavourite');
            Route::get('getMyFavouriteChallenge', 'FavouriteController@getMyFavouriteChallenge');
        });
        //end Favourite
        //start Report
        Route::group(['prefix' => 'Report'], function () {
            Route::post('store', 'ReportController@store');
        });
        //end Report
        //start comment
        Route::group(['prefix' => 'Comment'], function () {
            Route::post('store', 'CommentController@store');
            Route::post('update', 'CommentController@update');
            Route::post('delete', 'CommentController@delete');
            Route::post('getCommentsByVideoId', 'CommentController@getCommentsByVideoId');
        });
        //end comment

        //start replay
        Route::group(['prefix' => 'Reply'], function () {
            Route::post('store', 'ReplayController@store');
            Route::post('update', 'ReplayController@update');
            Route::post('delete', 'ReplayController@delete');
            Route::post('getReplysByCommentId', 'ReplayController@getReplysByCommentId');
        });
        //end replay

        //start replay
        Route::group(['prefix' => 'WatchOrShare'], function () {
            Route::post('watch', 'WatchshareController@watch');
            Route::post('share', 'WatchshareController@share');
            Route::post('watchStory', 'WatchshareController@watchStory');
        });
        //end replay

        //start like
        Route::group(['prefix' => 'Like'], function () {
            Route::post('toggleLike', 'LikeController@toggleLike');
            Route::post('reactStory', 'LikeController@reactStory');
            Route::post('getReacts', 'LikeController@getReacts');
        });
        //end like

        //start hastage
        Route::group(['prefix' => 'Hashtag'], function () {
            Route::post('SearchHastage', 'HashtagController@SearchHastage');
        });
        //end hastage

        //start Rank
        Route::group(['prefix' => 'Rank'], function () {
            Route::get('getRanks', 'RankController@getRanks');
        });
        //end Rank

        //start Rank
        Route::group(['prefix' => 'Invitation'], function () {
            Route::post('store', 'InvitationController@store');
        });
        //end Rank
        //notification
        Route::get('getNotifications', 'NotificationController@getNotifications');
        Route::get('readNotification', 'NotificationController@readNotification');
        Route::post('deleteNotify', 'NotificationController@deleteNotify');
        Route::post('changeNotifyStatus', 'NotificationController@changeNotifyStatus');
        Route::get('getunReadNotificationsCount', 'NotificationController@getunReadNotificationsCount');
        //end notifivartio

        //interesting
        Route::prefix('Interesting')->group(function () {
            Route::get('getInteresting', [InterestingController::class, 'index']);
            Route::post('saveInteresting', [InterestingController::class, 'store']);
        });
        //end interesting


    });
});
