<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('weddings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('slug')->unique()->index();
            $table->date('event_date');
            $table->string('og_image_path');
            $table->string('address_url')->nullable();
            $table->json('partner_one')->nullable();
            $table->json('partner_two')->nullable();
            $table->json('content')->nullable();
            $table->json('event_time')->nullable();
            $table->json('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weddings');
    }
};
