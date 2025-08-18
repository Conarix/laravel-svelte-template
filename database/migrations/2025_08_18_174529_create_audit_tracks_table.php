<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->morphs('trackable');
            $table->boolean('creation');
            $table->json('changes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_tracks');
    }
};
