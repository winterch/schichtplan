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
        if (!Schema::hasTable('plan')) {
            Schema::create('plan', function (Blueprint $table) {
                $table->id();
                // f.e. ac55616963a1624843019fd68af114f754d2baee
                $table->string('unique_link', 40)->unique();
                $table->string('title', 200);
                $table->string('message', 500);
                $table->string('owner_email', 200);
                $table->string('password');
                $table->timestamps();
            });
        }

        // add timestamps to existing table
        if (!Schema::hasColumn('plan', 'created_at')) {
            Schema::table('plan', function (Blueprint $table) {
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
        Schema::dropIfExists('plan');
    }
}
