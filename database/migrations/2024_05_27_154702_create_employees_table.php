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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->text('profile_picture')->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('type_card')->nullable();
            $table->string('id_card')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number', 10)->nullable();
            $table->string('education')->nullable();
            $table->string('degree')->nullable();
            $table->string('eps_entity')->nullable();
            $table->string('afp_entity')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_entity')->nullable();
            $table->string('type_contract')->nullable();
            $table->string('license_category', 3)->nullable();
            $table->date('license_issuance')->nullable();
            $table->date('license_expiration')->nullable();
            $table->date('start_date_contract')->nullable();
            $table->date('end_date_contract')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->foreignId('company_id')->constrained();
            $table->foreignId('employee_category_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
