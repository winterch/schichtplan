<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     * Add or modify subscription table
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('subscription')) {
            Schema::create('subscription', function (Blueprint $table) {
                $table->id();
                // the typo is in the origin repo
                $table->char('name', 60);
                $table->string('phone', 20);
                $table->string('email', 200);
                $table->string('comment',500);
                $table->foreignIdFor(\App\Models\Shift::class)
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

        // add timestamps to existing table
        if (!Schema::hasColumn('subscription', 'created_at')) {
            Schema::table('subscription', function (Blueprint $table) {
                $table->timestamps();
            });
        }
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     * Drop subscription table
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('subscription');
    }
}
