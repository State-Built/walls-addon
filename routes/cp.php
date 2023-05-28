<?php


Route::namespace('\State\Gated\Http\Controllers\CP')
     ->prefix('/gated')
     ->name('gated.')
     ->group(function() {

         Route::get('/gates', 'GatesController@index')->name('index');

         Route::get('/gates/new', 'GatesController@create')->name('create');

         Route::post('/gates', 'GatesController@store')->name('store');

     });