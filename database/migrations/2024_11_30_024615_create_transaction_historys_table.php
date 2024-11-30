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
        Schema::create('transaction_historys', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->double('amount')->default(0);
            $table->timestamp('timestamp', precision: 0);
            $table->integer('status')->comment('1 => Success, 2 => Failed');
            $table->tinyText('type')->comment('C => Credit, D => Debit');
            $table->text('description')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_historys');
    }
};
