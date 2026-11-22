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
        Schema::create('urls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')
            ->constrained('companies');

            $table->foreignUuid('user_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();
            
            $table->text('original_url');
            $table->string('short_code', 20)->unique();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
