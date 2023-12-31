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
        Schema::create('users', function (Blueprint $table) {
            # MySQL
            /*$table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('lastname');
            $table->integer('age');
            $table->string('genero');
            $table->integer('cash')->default(10000);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();*/

            # MongoDB
            $table->id();
            $table->unique('username');
            $table->unique('email');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
