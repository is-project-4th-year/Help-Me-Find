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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('image_name');
            $table->text('description')->nullable();
            $table->string('finder_first_name');
            $table->string('finder_last_Name');
            $table->string('finder_email');
            $table->string('owner_first_name')->nullable();
            $table->string('owner_last_Name')->nullable();
            $table->string('owner_email')->nullable();
            $table->timestamp('found_date')->nullable();
            $table->string('found_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
