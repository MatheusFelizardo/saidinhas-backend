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
        Schema::create('fake_exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('date');
            $table->decimal('rate_eur', 12, 2)->default(1);
            $table->decimal('rate_brl', 12, 2);
            $table->decimal('rate_usd', 12, 2);
            $table->decimal('rate_gbp', 12, 2);

            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rate');
    }
};
