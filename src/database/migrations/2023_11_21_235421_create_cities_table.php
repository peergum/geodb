<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->index();
            $table->string('cc')->index();
            $table->string('cc2')->index();
            $table->string('feature_class')->index();
            $table->string('feature_code')->index();
            $table->float('latitude')->index();
            $table->float('longitude')->index();
            $table->string('admin1')->index();
            $table->string('admin2')->index();
            $table->string('admin3')->index();
            $table->string('admin4')->index();
            $table->unsignedMediumInteger('population')->index();
            $table->float('elevation')->index();
            $table->string('timezone')->index();
            $table->foreignId('country_id')->constrained();
            $table->foreignId('state_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
