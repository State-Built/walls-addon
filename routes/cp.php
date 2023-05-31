<?php


Route::namespace('\State\Walls\Http\Controllers\CP')
     ->prefix('/walls')
     ->name('walls.')
     ->group(function() {

         Route::get('/walls', 'GatesController@index')->name('index');

         Route::get('/walls/new', 'GatesController@create')->name('create');

         Route::post('/walls', 'GatesController@store')->name('store');

     });