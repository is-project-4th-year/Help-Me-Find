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
        // Schema::create('chats', function (Blueprint $table) {
        //     $table->id();
        //     // seeker_id is the user looking for the item
        //     $table->foreignId('seeker_id')->constrained('users')->onDelete('cascade');
        //     // finder_id is the user who posted the item (hardcoded to 1 for this demo)
        //     $table->foreignId('finder_id')->constrained('users')->onDelete('cascade');
        //     $table->unsignedBigInteger('item_id')->unique(); // Unique conversation per item
        //     $table->timestamps();

        //     // Unique constraint to prevent duplicate chats between the same seeker and a single item
        //     $table->unique(['seeker_id', 'item_id']);
        // });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        // Schema::dropIfExists('chats');
    }
};
