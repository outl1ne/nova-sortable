<?php

use Illuminate\Support\Facades\Route;

Route::post('sort/update-order', 'SortableController@updateOrder');
Route::post('sort/move-to-start', 'SortableController@moveToStart');
Route::post('sort/move-to-end', 'SortableController@moveToEnd');
