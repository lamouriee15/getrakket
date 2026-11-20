<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('recovery_hash')->default('');
            $t->string('role')->default('client');
            $t->string('bio', 300)->default('');
            $t->boolean('has_pic')->default(false);
            $t->unsignedInteger('pic_v')->default(0);
            $t->longText('pic')->nullable();
            $t->timestamp('last_seen_at')->nullable();
        });

        Schema::create('expertise', function (Blueprint $t) {
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('skill', 40);
            $t->primary(['user_id', 'skill']);
        });

        Schema::create('job_posts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $t->string('title', 120);
            $t->text('description');
            $t->string('budget', 40)->default('Open');
            $t->string('category', 40);
            $t->string('status', 10)->default('open');
            $t->foreignId('hired_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('applications', function (Blueprint $t) {
            $t->foreignId('job_post_id')->constrained()->cascadeOnDelete();
            $t->foreignId('freelancer_id')->constrained('users')->cascadeOnDelete();
            $t->timestamp('created_at')->useCurrent();
            $t->primary(['job_post_id', 'freelancer_id']);
        });

        Schema::create('messages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $t->text('body');
            $t->timestamps();
            $t->index(['sender_id', 'receiver_id']);
        });

        Schema::create('reviews', function (Blueprint $t) {
            $t->id();
            $t->foreignId('job_post_id')->unique()->constrained()->cascadeOnDelete();
            $t->foreignId('freelancer_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $t->unsignedTinyInteger('stars');
            $t->string('comment', 500)->default('');
            $t->timestamps();
        });

        Schema::create('call_signals', function (Blueprint $t) {
            $t->id();
            $t->foreignId('from_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('to_id')->constrained('users')->cascadeOnDelete();
            $t->string('type', 10);
            $t->text('payload')->nullable();
            $t->timestamp('created_at')->useCurrent();
            $t->index('to_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_signals');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('job_posts');
        Schema::dropIfExists('expertise');
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['recovery_hash', 'role', 'bio', 'has_pic', 'pic_v', 'pic', 'last_seen_at']);
        });
    }
};
