<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api')->name('api.v1')->group(function () {
    Route::middleware('throttle:' . config('api.rate_limits.sign'))->group(function () {
        //短信验证码
        Route::post('verificationCodes', 'VerificationCodesController@store')->name('verificationCodes.store');
        //用户注册
        Route::post('users', 'UsrsController@store')->name('users.store');
        //图片验证码
        Route::post('captchas', 'CaptchasController@store')->name('captchas.store');
        //第三方登录
        Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->where('social_type', 'wechat')
            ->name('socials.authorizations.store');
        //登录
        Route::post('authorizations', 'AuthorizationsController@store')->name('authorization.store');
        //刷新token
        Route::put('authorizations', 'AuthorizationsController@update')->name('authorization.update');
        //删除token
        Route::delete('authorizations/current', 'AuthorizationsController@destroy')->name('authorization.destroy');
    });
    Route::middleware('throttle:' . config('api.rate_limits.access'))->group(function () {
        //某个用户的详情
        Route::get('users/{user}', 'UserController@show')->name('users.show');

        //登录后可以访问的接口
        Route::middleware('auth:api')->group(function () {
            //当前登录用户信息
            Route::get('user', 'UsersController@me')->name('user.show');
        });
    });

});
