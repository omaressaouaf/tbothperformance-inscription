<?php

use App\Enums\EnrollmentStatus;
use App\Enums\FinancingType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->json("lead_data")->nullable();
            $table->string("financing_type")->nullable();
            $table->string("cpf_amount")->nullable();
            $table->string("cpf_dossier_number")->nullable();
            $table->string("cpf_start_date")->nullable();
            $table->string("status")->default(EnrollmentStatus::Pending->value);
            $table->timestamp('completed_at')->nullable();
            $table->foreignId("course_id")->nullable()->constrained()->onDelete("set null")->onUpdate("set null");
            $table->foreignId("plan_id")->nullable()->constrained()->onDelete("set null")->onUpdate("set null");
            $table->foreignId("lead_id")->nullable()->constrained()->onDelete("set null")->onUpdate("set null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
};
