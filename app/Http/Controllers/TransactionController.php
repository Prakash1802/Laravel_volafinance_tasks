<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{   

    /*************************************
     * ***********************************
     * ***Task 1 Daily  closing balance***
     * ***********************************
     * ***********************************/

    public  function calculateDailyClosingBalances($initialBalance, $dailyTransactions){

        $closingBalances = [];
        $currentBalance = $initialBalance;

        $closingBalances = [];

        foreach($dailyTransactions as $datewise=>$amount){

            if($amount > 0){
                $closingBalances[$datewise] = $currentBalance+$amount;
                
            }else{

                $closingBalances[$datewise] = $currentBalance+($amount); 
            }

            $currentBalance =  $currentBalance+($amount);
           
        }

       return $closingBalances;
    }


    public function calculateDailyTotalForClosingBalance()
    {   
        $dailyTransactions = [];

        $dailyTotals = DB::table('transactions')
            ->select(DB::raw('trans_plaid_date, SUM(trans_plaid_amount) as total_amount'))
            ->groupBy('trans_plaid_date')
            ->get()
            ->toArray();
          
            foreach ($dailyTotals as $key=> $total) {
                
                $dailyTransactions[$total->trans_plaid_date] = $total->total_amount;
            }

        $finalClosingArray = $this->calculateDailyClosingBalances(500,$dailyTransactions);

        // echo "<pre>";
        dd($finalClosingArray);
    }


    /*************************************
     * ***********************************
     * ***Task 2 90 days average balance***
     * ***********************************
     * ***********************************/

    function find90DaysAverageBalance() {

        $dailyTransactions = [];

        $minDate = Transaction::min('trans_plaid_date'); //2022-08-27

        //Next 90 days from  $minDate
        $next_ninty_Days = date ("Y-m-d", strtotime ($minDate ."+90 days"));//2022-11-25

    
        $daily_totals = DB::table('transactions')
            ->select(DB::raw('trans_plaid_date, SUM(trans_plaid_amount) as total_amount'))
             ->whereBetween('trans_plaid_date', [$minDate, $next_ninty_Days])
            ->groupBy('trans_plaid_date')
            ->get()
            ->toArray();

    
        foreach ($daily_totals as $key=> $value) {
            $tdate = $value->trans_plaid_date;
            $tamount = $value->total_amount;     
            $dailyTransactions[$tdate] = $tamount;
        }


        $finalAmountArr = [];

        $totalBalanceAmount = 0;
        $totalDays = count($dailyTransactions);

        foreach($dailyTransactions as $datewise=>$amount){

            $finalAmountArr[] = $amount;
            

        }

        foreach($finalAmountArr as $value){                       

           $totalBalanceAmount += $totalBalanceAmount+($value); 

        }

        $averageBalance = ($totalBalanceAmount / $totalDays);

        dd($averageBalance);// Output: 3.3273158556166E+24 

        //return $averageBalance
       
    }


    /*************************************
     * ***********************************
     * ***Task 3 Calculate last 30 days income 
     * except category id 180200004***********
     * ***********************************
     * ***********************************/

public function calculateLast30DaysIncomeExceptCatid(){

    $maxDate = Transaction::max('trans_plaid_date');//2023-01-04
      
    //Last 30 days from  $maxDate
    $last_30_days = date ("Y-m-d", strtotime ($maxDate ."-30 days"));//2022-12-05

        
    $income = DB::table('transactions')
            ->where('trans_plaid_category_id', '!=',180200004)
            ->whereBetween('trans_plaid_date', [$last_30_days, $maxDate])
            ->sum('trans_plaid_amount');

    dd($income);//Output:610

    //return $income;


    }


    /*************************************
     * ***********************************
     * ***Task 4 Calculate  debit ********
     * transaction count in first 30 days**
     * ***********************************
     * ***********************************/

    public function calculateDebitTransactionCountInFirst30Days(){

       $minDate = Transaction::min('trans_plaid_date'); //2022-08-27

        //First 30 days from  $minDate
        $first_30_Days = date ("Y-m-d", strtotime ($minDate ."+30 days"));//2022-09-26


        $debitTransactionCount = DB::table('transactions')
            ->whereBetween('trans_plaid_date', [$minDate, $first_30_Days])
            ->where('trans_plaid_amount','<', 0)
            ->count();

        dd($debitTransactionCount);// Output:47

        //return $debitTransactionCount;
    }

     /****************************************
     * ***************************************
     * ***Task 5 Calculate  Sum of Income with 
     * *****transaction amount > 15**********
     * **************************************
     * ***********************************/

    public function sumIncomeAmountGreaterThanFifteen() {

        $sumIncome = DB::table('transactions')
            ->where('trans_plaid_amount', '>', 15)
            ->sum('trans_plaid_amount');

        dd($sumIncome);  //Output:54216  

        //return $sumIncome;
    }

    /***********************************************
     * *********************************************
     * ***Task 6 Calculate  Sum of Debit Transaction
     *****Amount done on Friday/Saturday/Sunday****
     * *********************************************
     * *********************************************/

    public function sumDebitTransactionsOnSpecificDays() {

        //trans_plaid_amount < 0 means debit

        $minDate = Transaction::min('trans_plaid_date'); //2022-08-27

        $maxDate = Transaction::max('trans_plaid_date');//2023-01-04
    
        $sum = DB::table('transactions')
            ->where('trans_plaid_amount', '<', 0)
            ->whereBetween('trans_plaid_date', [$minDate, $maxDate])
            ->whereIn(DB::raw('DAYOFWEEK(trans_plaid_date)'), [1, 6, 7]) // 1 for Sunday, 6 for Friday, 7 for Saturday
            ->sum('trans_plaid_amount');

        dd($sum);//Output: -4075

        //return $sum;
    }

}
