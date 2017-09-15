<?php

Route::group(['namespace' => '\Lefamed\LaravelBillwerk\Http\Controllers', 'middleware' => ['web', 'auth']], function () {
	require __DIR__ . '/routes/web.php';
});

Route::group(['namespace' => '\Lefamed\LaravelBillwerk\Http\Controllers', 'middleware' => ['api']], function () {
	require __DIR__ . '/routes/api.php';
});
