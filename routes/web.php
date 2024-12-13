<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\LeaseController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/create-contact', function () {
    return view('create_contact');
})->name('create-contact');
Route::get('/contact', function () {
    return view('contact_view');
})->name('contact-view');
Route::get('/user', function () {
    return view('user_view');
})->name('user-view');
Route::get('/edit-contact', function () {
    return view('edit_contact');
})->name('edit-contact');
Route::get('/create-user', function () {
    return view('create_user');
})->name('create-user');