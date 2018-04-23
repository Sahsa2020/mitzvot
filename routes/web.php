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
    if (Auth::check())
        return File::get(public_path() . '/index.html');
    return view('auth.login');
});

Auth::routes();
Route::get('logout', function (){
    Auth::logout();
    return redirect('/login');
});
Route::get('/activateUser', 'HomeController@activateUser');
Route::get('/policy', 'HomeController@privatePolicy');

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'roles'], 'roles' => 'ADMIN'], function () {
    Route::get('/', 'AdminController@index');
    Route::get('give-role-permissions', 'AdminController@getGiveRolePermissions');
    Route::post('give-role-permissions', 'AdminController@postGiveRolePermissions');
    Route::resource('roles', 'RolesController');
    Route::resource('permissions', 'PermissionsController');
    Route::resource('users', 'UsersController');
    Route::resource('discounts', 'DiscountsController');
    Route::resource('sponsor', 'SponsorController');
    Route::resource('donates', 'DonatesController');
    Route::post('donates/approve/{id}', 'DonatesController@approve');
    Route::post('donates/unapprove/{id}', 'DonatesController@unapprove');
    Route::get('upload_policies_rules/', 'AdminController1@index');
    Route::post('upload_policies_rules/', 'AdminController1@upload_rule');
    Route::get('upload_firmware/', 'AdminController1@upload_firmware');
    Route::post('upload_firmware/', 'AdminController1@upload_firmware_post');
    Route::get('message_history/', 'MessageHistoryController@index');
    Route::get('message_history/reply/{id}', 'MessageHistoryController@reply');
    Route::post('message_history/reply/{id}', 'MessageHistoryController@replyMessage');

    Route::get('acceptticket/{id}', 'AdminController@acceptTicket');
    Route::get('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
    Route::post('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);
    /*
     * Route groups for post management
     */
    Route::group(['prefix' => 'posts'], function() {
        Route::get('/', [
            'uses' => 'PostController@index',
            'as' => 'admin.posts.index.get'
        ]);
        Route::put('/{id}', [
            'uses' => 'PostController@approve',
            'as' => 'admin.posts.approve.put'
        ]);
        Route::patch('/{id}', [
            'uses' => 'PostController@disapprove',
            'as' => 'admin.posts.disapprove.patch'
        ]);
        Route::delete('/{id}', [
            'uses' => 'PostController@delete',
            'as' => 'admin.posts.delete.delete'
        ]);
    });
});

Route::group(['namespace' => 'Shipper', 'prefix' => 'shipper', 'middleware' => ['auth', 'roles'], 'roles' => 'DROP_SHIPPER'], function () {
    Route::get('/', 'ShipperController@index');
    Route::get('/orders', 'ShipperController@orders');
    Route::get('/order/{id}', 'ShipperController@editOrder');
    Route::post('/order/{id}', 'ShipperController@ship');
    Route::get('/warehouses', 'ShipperController@warehouses');
    Route::get('/warehouses/create', 'ShipperController@createWareHouse');
    Route::post('/warehouses', 'ShipperController@createNewHouse');
    Route::put('/warehouses/{id}', 'ShipperController@editCurrentHouse');
    Route::delete('/warehouses/{id}', 'ShipperController@deleteHouse');
    Route::get('/warehouse/{id}/edit', 'ShipperController@editWareHouse');
    Route::get('/warehouse/{id}/addInventory', 'ShipperController@addInventory');
    Route::get('/warehouse/{id}/history', 'ShipperController@ioHistory');
    Route::post('/warehouses/inventory/{id}', 'ShipperController@add_remove_inventory');

});
Route::get('customer_support', 'MessageController@customerSupport');
Route::post('customer_support', 'MessageController@newMessage');
Route::get('isTicketClosed/{id}', 'MessageController@isTicketClosed');

Route::post('message/{id}', 'MessageController@removeChatHistory')->name('message.readAll');
Route::post('contactAdmin', 'MessageController@contactAdmin')->name('contactAdmin');
Route::get('messages', 'MessageController@index')->name('messages');
Route::get('message/{id}', 'MessageController@chatHistory')->name('message.read');
Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
   Route::post('message/send', 'MessageController@ajaxSendMessage')->name('message.new');
   Route::delete('message/delete/{id}', 'MessageController@ajaxDeleteMessage')->name('message.delete');
});
/* Twitter Account OAuth Aunthentication */
Route::group(['prefix' => 'twitter', 'middleware' => 'auth'], function(){
    Route::get('login', 'TwitterController@index')->name('twitter.login');
    Route::get('callback', 'TwitterController@postOAuth')->name('twitter.callback');
    Route::get('error', 'TwitterController@getErrors')->name('twitter.error');
    Route::get('logout', 'TwitterController@getLogout')->name('twitter.logout');
});
/*
 * Route groups for Facebook OAuth
 */
Route::group(['prefix' => 'facebook'], function() {
    Route::get('/login', [
        'uses' => 'FacebookController@facebookLogin',
        'as' => 'facebook.facebookLogin.get'
    ]);
    Route::get('/callback', [
        'uses' => 'FacebookController@facebookCallback',
        'as' => 'facebook.facebookCallback.get'
    ]);
});
