<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('_id');
            $table->unsignedInteger('meeting_id')
                ->nullable(false);
            $table->dateTime('apv_date');
            $table->dateTime('act_date');
            $table->integer('full',false,true)
                ->nullable(false);
            $table->tinyInteger('apv_opt')
                ->default(0);

            $table->timestamps();

            $table->foreign('meeting_id')
                ->references('_id')->on('meetings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign('groups_meeting_id_foreign');
        });
        Schema::dropIfExists('groups');
    }
}
