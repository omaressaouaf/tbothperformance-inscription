<?php

use App\Enums\ProfessionalSituation;
use App\Enums\YearsWorkedInFrance;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("email")->unique();
            $table->string("phone");
            $table->string("years_worked_in_france")->default(YearsWorkedInFrance::Between1And2Years->value);
            $table->string("professional_situation")->default(ProfessionalSituation::Employee->value);
            $table->boolean("terms");
            $table->string("locale")->nullable()->default(config("app.locale"));
            $table->rememberToken();
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
        Schema::dropIfExists('leads');
    }
};
