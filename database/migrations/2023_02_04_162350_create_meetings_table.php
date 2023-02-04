<?php

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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string("event_id");
            $table->string("event_name");
            $table->dateTime("start_at");
            $table->dateTime("end_at");
            $table->json("invitee")->nullable();
            $table->json("location")->nullable();
            $table->foreignId("lead_id")->nullable()->constrained()->onDelete("set null")->onUpdate("set null");
            $table->foreignId("responsible_user_id")->nullable()->constrained("users")->onDelete("set null")->onUpdate("set null");
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
        Schema::dropIfExists('meetings');
    }
};
