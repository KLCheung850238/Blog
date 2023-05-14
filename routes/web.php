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

use App\Http\Controllers\HelloController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AdminController;


Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

// Index
Route::any('/index', [IndexController::class, 'index']);
Route::any('/', [IndexController::class, 'index']);

Route::any('/article/{id}', [IndexController::class, 'article']);

Route::post('/register_user', [IndexController::class, 'register_user']);
Route::any('/logout', [IndexController::class, 'logout']);
Route::post('/login_verify', [IndexController::class, 'verify']);

Route::any('/profile/{id}', [IndexController::class, 'profile']);
Route::get('/category/{id}', [IndexController::class, 'category']);

// Admin
Route::get('/admin/index', [AdminController::class, 'index']);
Route::post('/admin/addcomment', [AdminController::class, 'addcomment']);

Route::post('/admin/add_blog', [AdminController::class, 'add_blog']);
Route::delete('/admin/blog', [AdminController::class, 'delete_blog']);
Route::get('/admin/blog', [AdminController::class, 'get_blog']);
Route::put('/admin/blog', [AdminController::class, 'edit_blog']);
Route::put('/admin/verify', [AdminController::class, 'verify_blog']);
Route::get('/admin/comments', [AdminController::class, 'get_comments']);
Route::delete('/admin/comment', [AdminController::class, 'delete_comment']);

Route::get('/admin/count_comment', [AdminController::class, 'count_comment']);

// API
Route::get('/api/blogs', [IndexController::class, 'blogs']);

