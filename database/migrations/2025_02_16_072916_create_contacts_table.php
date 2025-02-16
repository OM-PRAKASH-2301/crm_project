<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('profile_image')->nullable();
            $table->string('additional_file')->nullable();
            $table->json('custom_fields')->nullable();
            $table->string('deleted_at')->default('N')->nullable()->change();
            $table->timestamps();
        });
    }
    
    
    public function down() {
        Schema::dropIfExists('contacts');
    }
};
