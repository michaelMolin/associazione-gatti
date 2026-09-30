<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->string('sex', 6);
            $table->boolean('sterilized')->default(false);
            $table->text('character')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('status', 20)->default('available')->index(); // available, adopted, not_adoptable
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cats');
    }
};
