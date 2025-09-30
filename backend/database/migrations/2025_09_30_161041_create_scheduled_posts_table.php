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
        Schema::create('scheduled_posts', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // LinkedIn person_id
            $table->text('access_token');
            $table->text('post_text');
            $table->string('media_url')->nullable();
            $table->string('media_type')->nullable();
            $table->timestamp('scheduled_datetime');
            $table->string('timezone');
            $table->string('status')->default('pending'); // pending, posted, failed
            $table->timestamps();
            
            $table->index(['user_id', 'scheduled_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_posts');
    }
};
