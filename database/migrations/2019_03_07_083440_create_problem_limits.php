<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problemset', function (Blueprint $table) {
            $table->integer('time_limit')->after('title');
            $table->integer('memory_limit')->after('time_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problemset', function (Blueprint $table) {
            $table->dropColumn('time_limit');
            $table->dropColumn('memory_limit');
        });
    }
}
