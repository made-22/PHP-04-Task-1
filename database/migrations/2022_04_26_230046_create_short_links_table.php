<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('short_links', function (Blueprint $table) {
            $table->string('id')
                ->primary();
            $table->timestamps();
            $table->string('long_url', 2048);
            $table->string('title')
                ->nullable();
            $table->json('tags')
                ->nullable();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('short_links');
    }
};
