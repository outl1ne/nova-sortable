<?php

use Illuminate\Support\Facades\Route;

Route::post('sort/update-order', 'SortableController@updateOrder');
Route::post('sort/move-to-first', 'SortableController@moveToFirst');
Route::post('sort/move-to-last', 'SortableController@moveToLast');
