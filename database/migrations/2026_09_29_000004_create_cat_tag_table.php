<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_tag', function (Blueprint $table) {
            $table->foreignId('cat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['cat_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_tag');
    }
};
