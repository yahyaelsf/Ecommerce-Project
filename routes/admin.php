<?php

use Illuminate\Support\Facades\Route;

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
//define('PAGINATION_COUNT',10);
Route::group(['namespace'=>'Admin','middleware'=>'auth:admin'],function(){ //auth عشان يتحقق من وجود الادمن ولا لا
    Route::get('/', 'DashboardController@index') -> name('admin.dashboard');
    //************************** Languages route ***************************************
    Route::group(['prefix'=>'languages'] , function(){
        Route::get('/','LanguagesController@index')->name('admin.languages');
        Route::get('/create','LanguagesController@create')->name('admin.languages.create');
        Route::post('/store','LanguagesController@store')->name('admin.languages.store');
        Route::get('/edit/{id}','LanguagesController@edit')->name('admin.languages.edit');
        Route::post('/update/{id}','LanguagesController@update')->name('admin.languages.update');
        Route::get('/delete/{id}','LanguagesController@destroy')->name('admin.languages.delete');
    });
    //************************** Main Category route ***************************************
    Route::group(['prefix'=>'main_category'] , function(){
        Route::get('/','MainCategoryController@index')->name('admin.categories');
        Route::get('/create','MainCategoryController@create')->name('admin.categories.create');
        Route::POST('/store','MainCategoryController@store')->name('admin.categories.store');
        Route::get('/edit/{id}','MainCategoryController@edit')->name('admin.categories.edit');
        Route::post('/update/{id}','MainCategoryController@update')->name('admin.categories.update');
        Route::get('/delete/{id}','MainCategoryController@destroy')->name('admin.categories.delete');
        Route::get('changeStatus/{id}','MainCategoryController@changeStatus') -> name('admin.categories.status');
    });
    //************************** Vendors route ***************************************
    Route::group(['prefix'=>'vendors'] , function(){
        Route::get('/','VendorsController@index')->name('admin.vendors');
        Route::get('/create','VendorsController@create')->name('admin.vendors.create');
        Route::POST('/store','VendorsController@store')->name('admin.vendors.store');
        Route::get('/edit/{id}','VendorsController@edit')->name('admin.vendors.edit');
        Route::post('/update/{id}','VendorsController@update')->name('admin.vendors.update');
        Route::get('/delete/{id}','VendorsController@destroy')->name('admin.vendors.delete');
    });

});



Route::group(['namespace'=>'Admin','middleware'=>'guest:admin'],function(){
    Route::get('login', 'LoginController@index')->name('admin.index');
    Route::post('login', 'LoginController@login')->name('admin.login');

});

