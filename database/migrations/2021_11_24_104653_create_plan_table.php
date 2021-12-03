<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanTable extends Migration
{
    /**
     * Run the migrations.
     * Create the table plan
     *
     * @return void
     */
    public function up()
    {
        // migrate from old < v2.0 plan to plans
        if (Schema::hasTable('plan')) {
            Schema::rename('plan', 'plans');
            // add missing timestamps
            Schema::table('plans', function (Blueprint $table) {
                $table->timestamps();
                $table->char('remember_token');
            });
        }

        // Table doesn't exist. We need to create it
        if (!Schema::hasTable('plans')) {
            Schema::create('plans', function (Blueprint $table) {
                $table->id();
                // f.e. ac55616963a1624843019fd68af114f754d2baee
                $table->string('unique_link', 40)->unique();
                $table->string('title', 200);
                $table->string('description', 500);
                $table->string('contact', 200);
                $table->string('owner_email', 200);
                $table->char('password');
                $table->char('remember_token');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     * Drop te table plan
     *
     * @return void
     */
    public function down()
    {
        // just drop table
        Schema::dropIfExists('plans');
    }
}
