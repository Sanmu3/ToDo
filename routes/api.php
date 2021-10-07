<?php

use Illuminate\Support\Facades\Route;

// User
Route::resource("/users", 'UserController');

// Book
Route::resource("/books", 'BookController');

//Rent
Route::get('/rents', 'RentController@index');
Route::post('/rents', 'RentController@store');
Route::get('/users/{userId}/rents', 'RentController@show');
