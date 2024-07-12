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
        Schema::create('excute_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('email');
            $table->integer('time')->default(0);
            $table->string('sql', 512);
            $table->string('error', 512)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
