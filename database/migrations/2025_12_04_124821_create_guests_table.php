<?php

use App\Models\Wedding;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Wedding::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->index();
            $table->string('status');
            $table->boolean('is_notable');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['wedding_id', 'slug']);
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('status');
            $table->index('is_notable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
