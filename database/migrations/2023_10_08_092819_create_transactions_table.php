<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('trans_id')->nullable();
            $table->integer('trans_user_id')->nullable();
            $table->string('trans_plaid_trans_id','55')->nullable();
        $table->string('trans_plaid_categories','100')->nullable();
           $table->float('trans_plaid_amount', 3, 2);
           $table->integer('trans_plaid_category_id')->nullable();
           $table->date('trans_plaid_date');
           $table->string('trans_plaid_name','100');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
