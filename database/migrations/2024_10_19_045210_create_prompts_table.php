<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromptsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('prompts', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title')->nullable()->default(null); // Example field for a title or general info
            $table->string('user_id')->nullable()->default(null); // Store the user who created the prompt (if needed)
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('prompts');
    }
}
