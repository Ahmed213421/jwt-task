<?php

use App\Models\Admin;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();$table->string('name');
            $table->string('email')->unique();
            $table->enum('type',['super_admin','admin'])->default('admin');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('mobile')->nullable();
            $table->enum('status',['active','unactive'])->default('active');
            $table->rememberToken();
            $table->timestamps();
        });

        Admin::create([
            'name' => 'Ahmed Samir',
            'email' => 'admin@admin.com',
            'type' => 'super_admin',
            'password' => Hash::make('123'),
            'status' => 'active',
        ]);
        Admin::create([
            'name' => 'Ahmed Samir',
            'email' => 'spider@gmail.com',
            'type' => 'super_admin',
            'password' => Hash::make('123'),
            'status' => 'active',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
