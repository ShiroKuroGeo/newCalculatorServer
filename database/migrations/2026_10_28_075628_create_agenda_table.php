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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kind', 20);
            $table->string('title', 20);
            $table->date('date');
            $table->time('time')->nullable();
            $table->string('place')->nullable();
            $table->text('notes');
            $table->decimal('budget', 12, 2);
            $table->string('status', 20)->default('upcoming');
            $table->timestamps();

            $table->index(['user_id', 'kind', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};
