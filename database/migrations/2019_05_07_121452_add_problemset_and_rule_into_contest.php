<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProblemsetAndRuleIntoContest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contest', function (Blueprint $table) {
            $table->text('problemset');
        });
        Schema::table('contest', function (Blueprint $table) {
            $table->integer('rule');
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contest', function (Blueprint $table) {
            $table->dropColumn('problemset');
        });
        Schema::table('contest', function (Blueprint $table) {
            $table->dropColumn('rule');
        });
        //
    }
}
