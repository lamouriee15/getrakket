<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $t) {
            $t->string('note', 500)->nullable();
            $t->string('sample_kind', 10)->nullable();
            $t->string('sample_mime', 100)->nullable();
            $t->string('sample_name', 200)->nullable();
            $t->string('sample_link', 500)->nullable();
            $t->longText('sample_data')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $t) {
            $t->dropColumn(['note', 'sample_kind', 'sample_mime', 'sample_name', 'sample_link', 'sample_data']);
        });
    }
};
