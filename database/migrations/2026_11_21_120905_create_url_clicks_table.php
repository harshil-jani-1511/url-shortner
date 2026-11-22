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
        Schema::create('url_clicks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('url_id')
            ->constrained('urls')
            ->cascadeOnDelete();

            $table->timestamp('clicked_at')->useCurrent();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['url_id', 'clicked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_clicks');
    }
};
