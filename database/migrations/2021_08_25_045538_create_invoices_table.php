<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['initialize', 'open', 'paid'])->default('initialize');
            $table->foreignId('debtor_id')->constrained();
            $table->foreignId('creditor_id')->constrained();
            $table->foreignId('currency_id')->constrained();
            $table->date('due_date')->nullable();
            $table->dateTime('open_date')->nullable();
            $table->dateTime('paid_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
