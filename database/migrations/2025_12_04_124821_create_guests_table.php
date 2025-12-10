<?php
/*
 * Copyright (c) 2025 Kaung Khant Kyaw and Khun Htetz Naing.
 *
 * This file is part of the PaungPhet app.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

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
