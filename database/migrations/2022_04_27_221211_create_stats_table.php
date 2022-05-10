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
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('short_link_id');

            $table->foreign('short_link_id')
                ->references('id')
                ->on('short_links')
                ->onDelete('cascade');

            $table->string('ip')
                ->nullable();
            $table->text('user_agent')
                ->nullable();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
