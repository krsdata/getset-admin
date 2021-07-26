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
Route::get('download-app',function(\Request $request){
    // write here code to capture previous url

     $url = URL::previous();
 
    $ip = \Request::ip() ;
    \DB::table('source_urls')->insert([
        'source_url' => $url,
        'ip' => $ip,
        'origin' => request()->headers->get('origin'),
    ]);
    return response()->download('public/upload/apk/justkhelo.apk');
});
Route::get('apkDownload',function(){
   return redirect(env('apk_url'));
});



Route::get('liveScore',function(){
    echo "Coming Soon!!";
});

Route::get('liveScore','HomeController@liveScore');

//liveScore.blade.php


Route::get('liveChat','HomeController@liveChat');

Route::get('chart-line', 'ChartController@chartLine');
Route::get('chart-line-ajax', 'ChartController@chartLineAjax');
Route::get('charts', 'ChartController@index');

if (App::environment('prod')) {
    \URL::forceScheme('https');
}

Route::match(['post','get'], 'changePassword', 'UserController@changePassword');

Route::match(['post','get'], 'changePasswordToken', 'UserController@changePasswordToken');

Route::match(['post','get'], '/', 'HomeController@home');


Route::match(['post','get'], '404', 'HomeController@page404');


Route::match(['post','get'], 'myAffiliate', 'HomeController@myAffiliate');


Route::match(
    ['post','get'],
    '/contactus',
    [
        'as'   => 'contactus',
        'uses' => 'HomeController@contactus',
    ]
);



Route::match(
    ['post','get'],
    '/aboutus',
    [
        'as'   => 'aboutus',
        'uses' => 'HomeController@aboutus',
    ]
);

Route::match(
    ['post','get'],
    '/topReferralUser',
    [
        'as'   => 'topReferralUser',
        'uses' => 'HomeController@topReferralUser',
    ]
);




Route::match(
    ['post','get'],
    '/{name}',
    [
        'as'   => 'contentspage',
        'uses' => 'HomeController@getPage',
    ]
);

