<?php

use App\Http\Controllers\UserBooking;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group( function () {
    Route::get('/', function () {
        return view('home');
    });

Route::get('/jimCoreRegister', function () {

    return view('register');
});

Route::post('/signingUp', [UserController::class, 'verifying']);

Route::get('/logInPage', function () {
return view('logIn');
});


Route::get('/logOut', [UserController::class,'logOut']);


Route::post('/authenticateUser', [UserController::class, 'logIn']);

Route::get('/memberships', function () {
    return view ('memberships');
});

Route::get('/dayPass', function () {
    return view('bookDayPass');
});

Route::post('/dayPicked',[UserBooking::class, 'confirmDayPass']);

Route::get('/weeklyPass', function () {
    return view('bookWeekPass');
});

Route::get('/monthlyPass', function () {
    return view('bookMonthPass');
});



Route::post('/weekPicked',[UserBooking::class, 'confirmWeekPass']);
Route::post('/monthPicked',[UserBooking::class, 'confirmMonthPass']);
Route::get('/profile', function () {
    return view ('profile');
});
Route::post('/profile', function () {
    return view ('profile');
});


});