<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Shift extends Migration
{
    /**
     * Run the migrations.
     * Add or modify shift table
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('shift')) {
            Schema::create('shift', function (Blueprint $table) {
                $table->id();
                // the typo is in the origin repo
                $table->char('typ', 60);
                $table->string('title', 200);
                $table->string('description', 500);
                $table->dateTime('start');
                $table->dateTime('end');
                $table->integer('team_size', false, true);
                $table->integer('group');
                $table->foreignIdFor(Plan::class)
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        // add timestamps to existing table
        if (!Schema::hasColumn('shift', 'created_at')) {
            Schema::table('shift', function (Blueprint $table) {
                $table->timestamps();
            });
        }
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     * Drop shift table
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('shift');
    }
}
