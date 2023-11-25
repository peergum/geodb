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
            $table->string('ascii_name')->index();
            $table->string('feature_class')->index();
            $table->string('feature_code')->index();
            $table->float('latitude')->nullable()->index();
            $table->float('longitude')->nullable()->index();
            $table->string('admin1')->nullable()->index();
            $table->string('admin2')->nullable()->index();
            $table->string('admin3')->nullable()->index();
            $table->string('admin4')->nullable()->index();
            $table->unsignedMediumInteger('population')->nullable()->index();
            $table->float('elevation')->nullable()->index();
            $table->string('timezone')->nullable()->index();
            $table->foreignId('country_id')->constrained();
//            $table->foreignId('state_id')->constrained();
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
