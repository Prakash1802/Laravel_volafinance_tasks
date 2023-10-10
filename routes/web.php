<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/calculateDailyTotal',[TransactionController::class,'calculateDailyTotalForClosingBalance']);

Route::get('/calculate90DaysAvgBalance',[TransactionController::class,'find90DaysAverageBalance']);

Route::get('/calculateLast30DaysIncome',[TransactionController::class,'calculateLast30DaysIncomeExceptCatid']);


Route::get('/debitTransactionCount',[TransactionController::class,'calculateDebitTransactionCountInFirst30Days']);

Route::get('/sumIncomeGreaterThan15',[TransactionController::class,'sumIncomeAmountGreaterThan15']);


Route::get('/sumDebitTransactionsOnWeekends',[TransactionController::class,'sumDebitTransactionsOnSpecificDays']);



/*...........Routes Related to Student Controller...........*/



Route::get('/students', [StudentController::class, 'getStudentByStandard']);

Route::get('/fetch_results',[StudentController::class,'fetchResults']);




