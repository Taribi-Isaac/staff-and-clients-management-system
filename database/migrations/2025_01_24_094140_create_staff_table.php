<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->unique();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('role');
            $table->enum('status', ['active', 'inactive', 'leave', 'suspension']);
            $table->enum('employment_type', ['full-time', 'contract', 'intern']);
            $table->string('passport')->nullable();
            $table->string('state_of_origin');
            $table->string('local_government_area');
            $table->string('home_town')->nullable();
            $table->string('residential_address');
            
            // Guarantor fields
            $table->string('guarantor_1_name')->nullable();
            $table->string('guarantor_1_email')->nullable();
            $table->string('guarantor_1_phone')->nullable();
            $table->string('guarantor_1_address')->nullable();

            $table->string('guarantor_2_name')->nullable();
            $table->string('guarantor_2_email')->nullable();
            $table->string('guarantor_2_phone')->nullable();
            $table->string('guarantor_2_address')->nullable();

            // Bank info fields
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();

            // Submitted documents fields
            $table->string('submit_doc_1')->nullable();
            $table->string('submit_doc_2')->nullable();
            $table->string('submit_doc_3')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff');
    }
};
