<?php

namespace database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->index();
            $table->string('cc')->unique();
            $table->string('cc2')->index();
            $table->unsignedBigInteger('sqkm')->index();
            $table->unsignedBigInteger('population')->index();
            $table->string('continent')->index();
            $table->string('tld',10)->index();
            $table->string('currency_code',4)->index();
            $table->string('currency_name')->index();
            $table->string('lang')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
