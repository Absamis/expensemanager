<?php

use App\Http\Controllers\ExpenseController;
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

Route::get('/', function () {
    session(["userid" => "WE34ewdDEe3o", "name" => "Adebiyi Abdulsamod"]);
    return view('welcome');
});

Route::get('/dashboard', [ExpenseController::class, "index"]);
Route::post('/add-expense', [ExpenseController::class, "addExpense"]);

Route::get('/filter', [ExpenseController::class, "filter"]);

Route::get('/edit/{expid}', [ExpenseController::class, "edit"]);
Route::put('/edit/{expid}', [ExpenseController::class, "update"]);
Route::delete('/edit/{expid}', [ExpenseController::class, "delete"]);
