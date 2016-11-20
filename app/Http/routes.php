<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
| 200 OK
| 201 Created
| 204 No content
| 304 Not Modified
| 400 Bad Request
| 401 Unauthorized
| 403 Forbidden
| 404 Not Found
| 409 Conflict (Already Exist,...)
| 500 Internal Server Error
| http://www.restapitutorial.com/lessons/httpmethods.html
| http://www.restapitutorial.com/httpstatuscodes.html
*/
Route::group([
    'prefix' => 'user',
    'as'     => 'u'
], function() {

    Route::group(['middleware' => ['nonAuthed']], function() {
        Route::post('/sign-up', 'UserController@signUp')->name('.signUp');
        Route::post('/sign-in', 'UserController@signIn')->name('.signIn');
        // Forgot
    });

    Route::group(['middleware' => ['authed']], function() {
        Route::post('/sign-out', 'UserController@signOut')->name('.signOut');
        // Edit, Change password
    });
});

Route::group([
    'prefix' => 'hub',
    'as'     => 'h',
    'middleware' => ['authed']
], function() {

    Route::get('index','HubController@index')->name('.index');
    Route::post('create','HubController@create')->name('.create');
    Route::post('select','HubController@select')->name('.select');

    Route::group(['middleware' => ['hubSelected']], function() {

        Route::get('/','HubController@read')->name('.read');
        Route::match(['put','patch'],'/','HubController@update')->name('.update');
        Route::delete('/','HubController@destroy')->name('.destroy');

        Route::group([
            'prefix' => 'member',
            'as'     => '.m'
        ], function() {

            Route::post('index','MemberController@index')->name('.index');
            Route::post('create','MemberController@create')->name('.create');

            Route::group([], function () {
                Route::post('{id}/edit','MemberController@update')->name('.update');
                Route::post('{id}/destroy','MemberController@destroy')->name('.destroy');
            });
        });

        Route::group([
            'prefix' => 'bot',
            'as'     => '.b'
        ], function() {

            Route::post('index','BotController@index')->name('.index');
            Route::post('create','BotController@create')->name('.create');

            Route::group([], function () {
                Route::post('{id}/edit','BotController@update')->name('.update');
                Route::post('{id}/destroy','BotController@destroy')->name('.destroy');
            });

            // Control
        });
    });
});

Route::get('/restricted', ['middleware' => ['authed'],
   function () {

        $user = JWTAuth::toUser();

        return response()->json(compact('user'));
   }
]);
