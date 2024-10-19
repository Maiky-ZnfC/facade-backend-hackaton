<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPromptsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('detail_prompts', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('id_prompt')->constrained('prompts')->cascadeOnUpdate()->restrictOnDelete(); // Foreign key to prompts
            $table->string('role'); // Role of the bot or user in the conversation
            $table->text('content'); // Message field for the prompt and AI response
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('detail_prompts');
    }
}
